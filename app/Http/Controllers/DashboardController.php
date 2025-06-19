<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Marker;
use App\Models\Photobooth;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'report_date' => 'nullable|date'
        ]);

        // Total counts
        $totalPhotos = Photo::count();
        $totalMarkers = Marker::count();
        $totalPhotobooths = Photobooth::count();
        $totalUsers = User::count();

        // Current month counts
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $photosThisMonth = Photo::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $markersThisMonth = Marker::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $photoboothsThisMonth = Photobooth::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $usersThisMonth = User::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Tanggal laporan (default hari ini)
        $reportDate = $request->input('report_date', Carbon::today()->toDateString());

        // Daily photobooth report dengan filter tanggal
        $photobooths = Photobooth::withCount(['markers' => function($query) use ($reportDate) {
            $query->whereDate('created_at', $reportDate);
        }])->get();

        return view('dashboard', compact(
            'totalPhotos',
            'totalMarkers',
            'totalPhotobooths',
            'totalUsers',
            'photosThisMonth',
            'markersThisMonth',
            'photoboothsThisMonth',
            'usersThisMonth',
            'photobooths',
            'reportDate'
        ));
    }
}
