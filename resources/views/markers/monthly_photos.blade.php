@extends('layouts1.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Download Foto Per Bulan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('markers.monthly-photos') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <select name="year" class="form-select">
                            @foreach($years as $y)
                            <option value="{{ $y }}" {{ $y==$year ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="month" class="form-select">
                            @foreach($months as $key => $m)
                            <option value="{{ $key }}" {{ $key==$month ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                    @if($markers->isNotEmpty())
                    <div class="col-md-3 text-end">
                        <a href="{{ route('markers.download-monthly-photos', ['year' => $year, 'month' => $month]) }}"
                            class="btn btn-success">
                            Download Semua Foto
                        </a>
                    </div>
                    @endif
                </div>
            </form>

            @if($markers->isEmpty())
            <div class="alert alert-info">Tidak ada foto untuk bulan yang dipilih</div>
            @else
            <div class="row">
                @foreach($markers as $marker)
                @if($marker->photos->isNotEmpty())
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $marker->photos->first()->path) }}" class="card-img-top"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $marker->photobooth->nama ?? 'N/A' }}</h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    {{ $marker->created_at->format('d F Y H:i') }}
                                </small>
                            </p>
                            <a href="{{ asset('storage/' . $marker->photos->first()->path) }}" download
                                class="btn btn-sm btn-outline-primary">
                                Download
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
