@extends('layouts1.app')

@section('content')
<div class="container">
    <h2>Tambah Marker</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Ada kesalahan input:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('markers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="photobooth_id">Pilih Photobooth</label>
            <select name="photobooth_id" class="form-select" required>
                <option value="">-- Pilih Photobooth --</option>
                @foreach($photobooths as $pb)
                    <option value="{{ $pb->id }}">{{ $pb->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Foto Marker (JPG/PNG) - Bisa pilih multiple</label>
            <input type="file" name="photos[]" class="form-control" multiple required>
            <small class="text-muted">Anda bisa memilih lebih dari satu foto</small>
        </div>

        <div class="mb-3">
            <label>Video (MP4)</label>
            <input type="file" name="video" class="form-control" required>
            <small class="text-muted">Maksimal 20MB</small>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('markers.index') }}" class="btn btn-secondary me-md-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>
        </div>
    </form>
</div>
@endsection
