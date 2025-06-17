@extends('layouts1.app')

@section('content')
<div class="container">
    <h2>Upload File .mind</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        @if(session('fileInfo'))
            <div class="mt-2">
                <strong>Detail File:</strong>
                <ul>
                    <li>Ukuran: {{ session('fileInfo')['size'] }}</li>
                    <li>Terakhir diubah: {{ session('fileInfo')['modified'] }}</li>
                </ul>
            </div>
        @endif
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5>Status File Saat Ini</h5>
            @if($fileExists)
                <div class="alert alert-info">
                    File targets.mind sudah ada di server.
                    <ul class="mt-2 mb-0">
                        <li>Ukuran: {{ $fileSize }}</li>
                        <li>Terakhir diubah: {{ $lastModified }}</li>
                    </ul>
                </div>
            @else
                <div class="alert alert-warning">
                    Tidak ada file .mind yang tersimpan di server.
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">Upload File Baru</div>
        <div class="card-body">
            <form action="{{ route('markers.upload-mind') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="mind_file">Pilih File .mind</label>
                    <input type="file" class="form-control-file" id="mind_file" name="mind_file" required>
                    <small class="form-text text-muted">
                        Maksimal ukuran file: 100MB. File lama akan otomatis diganti.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-upload"></i> Upload File
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
