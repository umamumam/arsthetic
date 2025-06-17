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
            'photos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'required|mimes:mp4|max:20480',
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
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|mimes:mp4|max:20480',
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

    public function showAR()
    {
        $markers = Marker::with('photos')->get();
        return view('markers.ar', compact('markers'));
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
}
