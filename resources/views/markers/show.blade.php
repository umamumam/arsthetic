@extends('layouts1.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Marker Details</h4>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            {{-- <h5>Basic Information</h5> --}}
                            <p><strong>Code:</strong> {{ $marker->unique_code }}</p>
                            <p><strong>Lokasi Photobooth:</strong> {{ $marker->photobooth->nama }}</p>
                            <p><strong>Description:</strong> {{ $marker->description ?? 'N/A' }}</p>
                            <p><strong>Tanggal:</strong> {{ $marker->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Video Preview</h5>
                            @if($marker->video_path)
                                <video width="100%" controls>
                                    <source src="{{ asset('storage/' . $marker->video_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <p>No video available</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h5>Marker Photos</h5>
                            <div class="row">
                                @foreach($marker->photos->sortBy('order') as $photo)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $photo->path) }}" class="card-img-top" alt="Marker Photo {{ $photo->order }}">
                                            <div class="card-body">
                                                <p class="card-text text-center">Photo #{{ $photo->order }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('markers.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>

                                <div>
                                    <a href="{{ route('markers.edit', $marker->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('markers.destroy', $marker->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this marker?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
