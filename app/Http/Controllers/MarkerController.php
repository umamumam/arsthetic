<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Marker;
use App\Models\Photobooth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MarkerController extends Controller
{
    public function index()
    {
        $markers = Marker::with(['photos', 'photobooth'])->get();
        return view('markers.index', compact('markers'));
    }

    public function create()
    {
        $photobooths = Photobooth::all();
        return view('markers.create', compact('photobooths'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg|max:3072',
            'video' => 'required|mimes:mp4|max:10240',
            'photobooth_id' => 'required|exists:photobooths,id',
            'description' => 'nullable|string'
        ]);

        // Generate unique code
        $uniqueCode = Marker::generateUniqueCode();

        // Get next number for files
        $nextMarkerNumber = Marker::count() + 1;

        // Store video
        $videoExtension = $request->file('video')->extension();
        $videoName = 'video' . $nextMarkerNumber . '.' . $videoExtension;
        $videoPath = $request->file('video')->storeAs('videos', $videoName, 'public');

        // Create marker
        $marker = Marker::create([
            'unique_code' => $uniqueCode,
            'video_path' => 'videos/' . $videoName,
            'photobooth_id' => $request->photobooth_id,
            'description' => $request->description
        ]);

        // Store photos
        foreach ($request->file('photos') as $index => $photo) {
            $photoExtension = $photo->extension();
            $photoName = 'marker' . $nextMarkerNumber . '_' . ($index + 1) . '.' . $photoExtension;
            $photoPath = $photo->storeAs('markers', $photoName, 'public');

            Photo::create([
                'marker_id' => $marker->id,
                'path' => 'markers/' . $photoName,
                'order' => $index + 1
            ]);
        }

        return redirect()->route('markers.index')->with('success', 'Marker created successfully.');
    }

    public function show(Marker $marker)
    {
        $marker->load('photos');
        return view('markers.show', compact('marker'));
    }

    public function edit(Marker $marker)
    {
        $marker->load('photos');
        $photobooths = Photobooth::all();
        return view('markers.edit', compact('marker', 'photobooths'));
    }

    public function update(Request $request, Marker $marker)
    {
        $request->validate([
            'photobooth_id' => 'required|exists:photobooths,id',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'video' => 'nullable|mimes:mp4|max:10240',
            'description' => 'nullable|string',
        ]);

        $data = [
            'description' => $request->description,
            'photobooth_id' => $request->photobooth_id
        ];
        $currentNumber = $marker->id;

        // Handle video update
        if ($request->hasFile('video')) {
            Storage::disk('public')->delete($marker->video_path);
            $videoExtension = $request->file('video')->extension();
            $videoName = 'video' . $currentNumber . '.' . $videoExtension;
            $videoPath = $request->file('video')->storeAs('videos', $videoName, 'public');
            $data['video_path'] = 'videos/' . $videoName;
        }

        $marker->update($data);

        // Handle photo updates
        if ($request->hasFile('photos')) {
            // Delete old photos
            foreach ($marker->photos as $photo) {
                Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }

            // Store new photos
            foreach ($request->file('photos') as $index => $photo) {
                $photoExtension = $photo->extension();
                $photoName = 'marker' . $currentNumber . '_' . ($index + 1) . '.' . $photoExtension;
                $photoPath = $photo->storeAs('markers', $photoName, 'public');

                Photo::create([
                    'marker_id' => $marker->id,
                    'path' => 'markers/' . $photoName,
                    'order' => $index + 1
                ]);
            }
        }

        return redirect()->route('markers.index')->with('success', 'Marker updated successfully.');
    }

    public function destroy(Marker $marker)
    {
        // Delete associated files
        Storage::disk('public')->delete($marker->video_path);
        foreach ($marker->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        $marker->delete();
        return redirect()->route('markers.index')->with('success', 'Marker deleted successfully.');
    }

    // public function showAR()
    // {
    //     $markers = Marker::with('photos')->get();
    //     return view('markers.ar', compact('markers'));
    // }
    public function showAR()
    {
        // Ambil semua file .patt dari storage
        $pattFiles = Storage::disk('public')->files('markers');
        $pattFiles = array_filter($pattFiles, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'patt';
        });

        // Ambil semua video dari storage
        $videoFiles = Storage::disk('public')->files('videos');
        $videoFiles = array_filter($videoFiles, function ($file) {
            return in_array(pathinfo($file, PATHINFO_EXTENSION), ['mp4', 'webm']);
        });

        // Urutkan file berdasarkan nama
        natsort($pattFiles);
        natsort($videoFiles);

        // Gabungkan data
        $markers = [];
        $i = 1;
        foreach ($pattFiles as $index => $pattFile) {
            $videoFile = $videoFiles[$index] ?? null;

            if ($videoFile) {
                $markers[] = [
                    'patt' => Storage::url($pattFile),
                    'video' => Storage::url($videoFile),
                    'number' => $i++
                ];
            }
        }

        return view('markers.ar_view', compact('markers'));
    }

    public function uploadMindFile(Request $request)
    {
        $request->validate([
            'mind_file' => 'required|file|mimetypes:application/octet-stream|max:102400', // 100MB max
        ]);

        try {
            $directory = 'markers'; // This will go to storage/app/public/markers
            $fileName = 'targets.mind';

            // 1. Delete existing file if it exists
            if (Storage::disk('public')->exists("$directory/$fileName")) {
                Storage::disk('public')->delete("$directory/$fileName");
            }

            // 2. Store the new file using the same pattern as in store()
            $path = $request->file('mind_file')->storeAs($directory, $fileName, 'public');

            // 3. Verify the file was stored
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("File failed to save after upload");
            }

            return response()->json([
                'success' => true,
                'message' => 'File .mind berhasil diupload!',
                'path' => $path,
                'public_url' => asset(Storage::url($path)), // Generate public URL
                'file_info' => [
                    'size' => round(Storage::disk('public')->size($path) / (1024 * 1024), 2) . ' MB',
                    'modified_at' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($path))
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'GAGAL: ' . $e->getMessage(),
                'debug' => [
                    'storage_writable' => is_writable(storage_path('app/public')),
                    'public_writable' => is_writable(public_path('storage'))
                ]
            ], 500);
        }
    }

    public function showMindUploadForm()
    {
        $currentFile = storage_path('app/public/markers/targets.mind');
        $fileExists = file_exists($currentFile);

        return view('markers.upload_mind', [
            'fileExists' => $fileExists,
            'lastModified' => $fileExists ? date('Y-m-d H:i:s', filemtime($currentFile)) : null,
            'fileSize' => $fileExists ? round(filesize($currentFile) / (1024 * 1024), 2) . ' MB' : null
        ]);
    }

    public function monthlyPhotos(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        $markers = Marker::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->with(['photos' => function ($query) {
                $query->where('order', 1);
            }])
            ->get();
        $years = Marker::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return view('markers.monthly_photos', compact('markers', 'years', 'months', 'year', 'month'));
    }

    public function downloadMonthlyPhotos(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $markers = Marker::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->with(['photos' => function ($query) {
                $query->where('order', 1);
            }])
            ->get();
        $zipFileName = "photos-{$year}-{$month}.zip";
        $zipPath = storage_path("app/public/temp/{$zipFileName}");
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0777, true);
        }
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($markers as $marker) {
                if ($marker->photos->isNotEmpty()) {
                    $photoPath = storage_path("app/public/{$marker->photos->first()->path}");
                    if (file_exists($photoPath)) {
                        $zip->addFile($photoPath, basename($photoPath));
                    }
                }
            }
            $zip->close();
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }
        return back()->with('error', 'Gagal membuat file zip');
    }
    // public function uploadPattFile(Request $request)
    // {
    //     $request->validate([
    //         'patt_file' => 'required|file|mimetypes:text/plain|max:10240', // 10MB max (file .patt biasanya kecil)
    //     ]);

    //     try {
    //         $directory = 'markers'; // Folder penyimpanan
    //         $baseName = 'marker';

    //         // Cari nomor terakhir yang ada
    //         $existingFiles = Storage::disk('public')->files($directory);
    //         $maxNumber = 0;

    //         foreach ($existingFiles as $file) {
    //             if (preg_match('/' . $baseName . '(\d+)\.patt$/', $file, $matches)) {
    //                 $num = (int)$matches[1];
    //                 if ($num > $maxNumber) {
    //                     $maxNumber = $num;
    //                 }
    //             }
    //         }

    //         $nextNumber = $maxNumber + 1;
    //         $fileName = $baseName . $nextNumber . '.patt';

    //         // Simpan file baru
    //         $path = $request->file('patt_file')->storeAs($directory, $fileName, 'public');

    //         if (!Storage::disk('public')->exists($path)) {
    //             throw new \Exception("File failed to save after upload");
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'File .patt berhasil diupload!',
    //             'file_name' => $fileName,
    //             'path' => $path,
    //             'public_url' => asset(Storage::url($path)),
    //             'file_info' => [
    //                 'size' => round(Storage::disk('public')->size($path) / 1024, 2) . ' KB',
    //                 'modified_at' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($path))
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'GAGAL: ' . $e->getMessage(),
    //             'debug' => [
    //                 'storage_writable' => is_writable(storage_path('app/public')),
    //                 'public_writable' => is_writable(public_path('storage'))
    //             ]
    //         ], 500);
    //     }
    // }
    // public function showPattUploadForm()
    // {
    //     $directory = 'markers';
    //     $pattFiles = [];

    //     // List semua file .patt yang ada
    //     $files = Storage::disk('public')->files($directory);

    //     foreach ($files as $file) {
    //         if (pathinfo($file, PATHINFO_EXTENSION) === 'patt') {
    //             $pattFiles[] = [
    //                 'name' => basename($file),
    //                 'size' => round(Storage::disk('public')->size($file) / 1024, 2) . ' KB',
    //                 'modified_at' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
    //                 'url' => Storage::url($file)
    //             ];
    //         }
    //     }

    //     return view('markers.upload_patt', [
    //         'pattFiles' => $pattFiles
    //     ]);
    // }
    // public function deletePattFile(Request $request)
    // {
    //     $request->validate([
    //         'filename' => 'required|string'
    //     ]);

    //     try {
    //         $path = 'markers/' . $request->filename;

    //         if (!Storage::disk('public')->exists($path)) {
    //             throw new \Exception("File tidak ditemukan");
    //         }

    //         Storage::disk('public')->delete($path);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'File berhasil dihapus'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal menghapus file: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function showPattUploadForm()
    {
        $directory = 'markers';
        $pattFiles = [];

        try {
            $files = Storage::disk('public')->files($directory);

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'patt') {
                    $pattFiles[] = [
                        'name' => basename($file),
                        'size' => round(Storage::disk('public')->size($file) / 1024, 2) . ' KB',
                        'modified_at' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
                        'url' => Storage::disk('public')->url($file)
                    ];
                }
            }

            // Urutkan file berdasarkan nama
            usort($pattFiles, function ($a, $b) {
                return strnatcmp($a['name'], $b['name']);
            });
        } catch (\Exception $e) {
            \Log::error('Error accessing storage: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat file .patt: ' . $e->getMessage());
        }

        return view('markers.upload_patt', [
            'pattFiles' => $pattFiles
        ]);
    }

    public function uploadPattFile(Request $request)
    {
        $request->validate([
            'patt_file' => 'required|file|mimetypes:text/plain|max:10240',
        ]);

        try {
            $directory = 'markers';
            $baseName = 'marker';

            // Cari nomor terakhir yang ada
            $existingFiles = Storage::disk('public')->files($directory);
            $maxNumber = 0;

            foreach ($existingFiles as $file) {
                if (preg_match('/' . $baseName . '(\d+)\.patt$/', $file, $matches)) {
                    $num = (int)$matches[1];
                    if ($num > $maxNumber) {
                        $maxNumber = $num;
                    }
                }
            }

            $nextNumber = $maxNumber + 1;
            $fileName = $baseName . $nextNumber . '.patt';

            // Simpan file baru
            $path = $request->file('patt_file')->storeAs($directory, $fileName, 'public');

            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("File gagal disimpan setelah upload");
            }

            return response()->json([
                'success' => true,
                'message' => 'File .patt berhasil diupload!',
                'file_name' => $fileName,
                'path' => $path,
                'public_url' => Storage::disk('public')->url($path),
                'file_info' => [
                    'size' => round(Storage::disk('public')->size($path) / 1024, 2) . ' KB',
                    'modified_at' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($path))
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePattFile(Request $request)
    {
        $request->validate([
            'filename' => 'required|string'
        ]);

        try {
            $path = 'markers/' . $request->filename;

            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("File tidak ditemukan");
            }

            Storage::disk('public')->delete($path);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus file: ' . $e->getMessage()
            ], 500);
        }
    }
}
