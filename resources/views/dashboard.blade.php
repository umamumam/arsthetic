@extends('layouts1.app')

@section('content')

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Home</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->
<!-- [ Main Content ] start -->
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Photo</h6>
                <h4 class="mb-3">{{ number_format($totalPhotos) }} &nbsp;<span
                        class="badge bg-light-primary border border-primary"><i class="ti ti-polaroid"></i></span></h4>
                <p class="mb-0 text-muted text-sm">Foto bulan ini <span class="text-primary">{{
                        number_format($photosThisMonth) }}</span> foto</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Marker</h6>
                <h4 class="mb-3">{{ number_format($totalMarkers) }} &nbsp;<span
                        class="badge bg-light-success border border-success"><i class="ti ti-stack"></i></span></h4>
                <p class="mb-0 text-muted text-sm">Marker bulan ini <span class="text-success">{{
                        number_format($markersThisMonth) }}</span> marker</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Photobooth</h6>
                <h4 class="mb-3">{{ number_format($totalPhotobooths) }} &nbsp;<span
                        class="badge bg-light-warning border border-warning"><i class="ti ti-camera"></i></span></h4>
                <p class="mb-0 text-muted text-sm">Total semua <span class="text-warning">{{
                        number_format($totalPhotobooths) }}</span> lokasi</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total User</h6>
                <h4 class="mb-3">{{ number_format($totalUsers) }} &nbsp;<span
                        class="badge bg-light-danger border border-danger"><i class="ti ti-users"></i></span></h4>
                <p class="mb-0 text-muted text-sm">Total semua <span class="text-danger">{{ number_format($totalUsers)
                        }}</span> pengguna</p>
            </div>
        </div>
    </div>
    <!-- Daily Photobooth Report -->
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-report-analytics me-2"></i>
                            Laporan Harian Photobooth
                        </h5>

                        <!-- Filter Tanggal -->
                        <form method="GET" action="{{ url()->current() }}" class="d-flex">
                            <div class="input-group" style="width: 200px;">
                                <input type="date" name="report_date" class="form-control form-control-sm"
                                    value="{{ $reportDate }}" max="{{ Carbon\Carbon::today()->toDateString() }}">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="ti ti-filter"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="text-muted mb-3">
                        Tanggal: {{ \Carbon\Carbon::parse($reportDate)->translatedFormat('d F Y') }}
                    </h6>

                    <div class="row">
                        @foreach($photobooths as $photobooth)
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 f-w-400 text-muted">
                                            <i class="ti ti-camera me-2"></i>
                                            {{ $photobooth->name ?? 'Photobooth #'.$photobooth->nama }}
                                        </h6>
                                        <span class="badge bg-light-primary rounded-pill">
                                            {{ $photobooth->markers_count }} marker
                                        </span>
                                    </div>

                                    <div class="progress" style="height: 6px;">
                                        @php
                                        $percentage = $photobooth->markers_count > 0
                                        ? min(100, ($photobooth->markers_count / max(1, $markersThisMonth)) * 100)
                                        : 0;
                                        @endphp
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <div class="mt-2 d-flex justify-content-between small text-muted">
                                        <span>Hari Ini</span>
                                        <span>{{ $photobooth->markers_count }} dari {{ $markersThisMonth }}</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top">
                                    <a href="{{ route('markers.index', ['photobooth_id' => $photobooth->id, 'date' => $reportDate]) }}"
                                        class="btn btn-sm btn-outline-primary w-100">
                                        <i class="ti ti-list me-1"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($photobooths->isEmpty())
                    <div class="alert alert-warning">
                        Tidak ada data photobooth untuk tanggal yang dipilih.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-12 col-xl-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">Unique Visitor</h5>
            <ul class="nav nav-pills justify-content-end mb-0" id="chart-tab-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="chart-tab-home-tab" data-bs-toggle="pill"
                        data-bs-target="#chart-tab-home" type="button" role="tab" aria-controls="chart-tab-home"
                        aria-selected="true">Month</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="chart-tab-profile-tab" data-bs-toggle="pill"
                        data-bs-target="#chart-tab-profile" type="button" role="tab" aria-controls="chart-tab-profile"
                        aria-selected="false">Week</button>
                </li>
            </ul>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="tab-content" id="chart-tab-tabContent">
                    <div class="tab-pane" id="chart-tab-home" role="tabpanel" aria-labelledby="chart-tab-home-tab"
                        tabindex="0">
                        <div id="visitor-chart-1"></div>
                    </div>
                    <div class="tab-pane show active" id="chart-tab-profile" role="tabpanel"
                        aria-labelledby="chart-tab-profile-tab" tabindex="0">
                        <div id="visitor-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-4">
        <h5 class="mb-3">Income Overview</h5>
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">This Week Statistics</h6>
                <h3 class="mb-3">$7,650</h3>
                <div id="income-overview-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xl-8">
        <h5 class="mb-3">Recent Orders</h5>
        <div class="card tbl-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless mb-0">
                        <thead>
                            <tr>
                                <th>TRACKING NO.</th>
                                <th>PRODUCT NAME</th>
                                <th>TOTAL ORDER</th>
                                <th>STATUS</th>
                                <th class="text-end">TOTAL AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Camera Lens</td>
                                <td>40</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-danger f-10 m-r-5"></i>Rejected</span>
                                </td>
                                <td class="text-end">$40,570</td>
                            </tr>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Laptop</td>
                                <td>300</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-warning f-10 m-r-5"></i>Pending</span>
                                </td>
                                <td class="text-end">$180,139</td>
                            </tr>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Mobile</td>
                                <td>355</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span>
                                </td>
                                <td class="text-end">$180,139</td>
                            </tr>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Camera Lens</td>
                                <td>40</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-danger f-10 m-r-5"></i>Rejected</span>
                                </td>
                                <td class="text-end">$40,570</td>
                            </tr>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Laptop</td>
                                <td>300</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-warning f-10 m-r-5"></i>Pending</span>
                                </td>
                                <td class="text-end">$180,139</td>
                            </tr>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Mobile</td>
                                <td>355</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span>
                                </td>
                                <td class="text-end">$180,139</td>
                            </tr>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Camera Lens</td>
                                <td>40</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-danger f-10 m-r-5"></i>Rejected</span>
                                </td>
                                <td class="text-end">$40,570</td>
                            </tr>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Laptop</td>
                                <td>300</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-warning f-10 m-r-5"></i>Pending</span>
                                </td>
                                <td class="text-end">$180,139</td>
                            </tr>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Mobile</td>
                                <td>355</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span>
                                </td>
                                <td class="text-end">$180,139</td>
                            </tr>
                            <tr>
                                <td><a href="#" class="text-muted">84564564</a></td>
                                <td>Mobile</td>
                                <td>355</td>
                                <td><span class="d-flex align-items-center gap-2"><i
                                            class="fas fa-circle text-success f-10 m-r-5"></i>Approved</span>
                                </td>
                                <td class="text-end">$180,139</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-4">
        <h5 class="mb-3">Analytics Report</h5>
        <div class="card">
            <div class="list-group list-group-flush">
                <a href="#"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Company
                    Finance Growth<span class="h5 mb-0">+45.14%</span></a>
                <a href="#"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Company
                    Expenses Ratio<span class="h5 mb-0">0.58%</span></a>
                <a href="#"
                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Business
                    Risk Cases<span class="h5 mb-0">Low</span></a>
            </div>
            <div class="card-body px-2">
                <div id="analytics-report-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xl-8">
        <h5 class="mb-3">Sales Report</h5>
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">This Week Statistics</h6>
                <h3 class="mb-0">$7,650</h3>
                <div id="sales-report-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-4">
        <h5 class="mb-3">Transaction History</h5>
        <div class="card">
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-s rounded-circle text-success bg-light-success">
                                <i class="ti ti-gift f-18"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Order #002434</h6>
                            <p class="mb-0 text-muted">Today, 2:00 AM</P>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <h6 class="mb-1">+ $1,430</h6>
                            <p class="mb-0 text-muted">78%</P>
                        </div>
                    </div>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-s rounded-circle text-primary bg-light-primary">
                                <i class="ti ti-message-circle f-18"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Order #984947</h6>
                            <p class="mb-0 text-muted">5 August, 1:45 PM</P>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <h6 class="mb-1">- $302</h6>
                            <p class="mb-0 text-muted">8%</P>
                        </div>
                    </div>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="avtar avtar-s rounded-circle text-danger bg-light-danger">
                                <i class="ti ti-settings f-18"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Order #988784</h6>
                            <p class="mb-0 text-muted">7 hours ago</P>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <h6 class="mb-1">- $682</h6>
                            <p class="mb-0 text-muted">16%</P>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div> --}}
</div>
@endsection
