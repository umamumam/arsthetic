@extends('layouts1.app')

@section('content')
<div class="container">
    <h2>Edit Marker</h2>

    <form action="{{ url('/markers/'.$marker->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="photobooth_id">Pilih Photobooth</label>
            <select name="photobooth_id" class="form-select" required>
                <option value="">-- Pilih Photobooth --</option>
                @foreach($photobooths as $pb)
                    <option value="{{ $pb->id }}" {{ $marker->photobooth_id == $pb->id ? 'selected' : '' }}>
                        {{ $pb->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Foto Marker (JPG)</label><br>
            @if($marker->photos && count($marker->photos))
                @foreach($marker->photos as $photo)
                    <img src="{{ Storage::url($photo->path) }}" width="150" class="mb-2 me-2">
                @endforeach
            @endif
            <input type="file" name="photos[]" class="form-control" multiple>
        </div>

        <div class="mb-3">
            <label>Video (MP4)</label><br>
            <video width="200" controls class="mb-2">
                <source src="{{ Storage::url($marker->video_path) }}">
            </video><br>
            <input type="file" name="video" class="form-control">
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control">{{ $marker->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ url('/markers') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
