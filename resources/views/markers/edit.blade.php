@extends('layouts1.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">EDIT PHOTO</h4>
        </div>

        <div class="card-body">
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

            <form action="{{ route('markers.update', $marker->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row border-bottom pb-3 mb-3">
                    <div class="col-md-6 border-end pe-3">
                        <h5>IMAGE</h5>
                        <div class="upload-area p-4 text-center border rounded">
                            <p class="text-muted mb-2">App. ping (max file size: 12MB, dimension: min 320px, max 10000px)</p>

                            <!-- Tampilkan gambar saat ini -->
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $marker->photos->first()->path) }}" width="150" class="img-thumbnail mb-2">
                                <p class="text-muted">Current Image</p>
                            </div>

                            <div class="border-2 border-dashed rounded p-4">
                                <p class="mb-2">Drop new image here or</p>
                                <input type="file" name="photos[]" id="imageUpload" class="d-none" multiple>
                                <label for="imageUpload" class="btn btn-outline-primary">SELECT FILE</label>
                            </div>
                            <p class="mt-2 text-muted">Step 1: upload new image</p>
                        </div>
                    </div>

                    <div class="col-md-6 ps-3">
                        <h5>VIDEO</h5>
                        <div class="upload-area p-4 text-center border rounded">
                            <p class="text-muted mb-2">MP4 format (max file size: 20MB)</p>

                            <!-- Tampilkan video saat ini -->
                            <div class="mb-3">
                                <video width="200" controls class="mb-2">
                                    <source src="{{ Storage::url($marker->video_path) }}">
                                </video>
                                <p class="text-muted">Current Video</p>
                            </div>

                            <div class="border-2 border-dashed rounded p-4">
                                <p class="mb-2">Drop new video here or</p>
                                <input type="file" name="video" id="videoUpload" class="d-none">
                                <label for="videoUpload" class="btn btn-outline-primary">SELECT FILE</label>
                            </div>
                            <p class="mt-2 text-muted">Step 2: upload new video</p>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12 border-end pe-3">
                        <div class="mb-3">
                            <label class="form-label">Pilih Photobooth</label>
                            <select name="photobooth_id" class="form-select" required>
                                <option value="">-- Pilih Photobooth --</option>
                                @foreach($photobooths as $pb)
                                    <option value="{{ $pb->id }}" {{ $marker->photobooth_id == $pb->id ? 'selected' : '' }}>
                                        {{ $pb->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 ps-3">
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ $marker->description }}</textarea>
                        </div>
                    </div>
                </div>

                <p class="text-muted">Step 3: save and try out via the app</p>

                <div class="d-flex justify-content-center mt-4">
                    <a href="{{ route('markers.index') }}" class="btn btn-outline-secondary me-2">CANCEL</a>
                    <button type="submit" class="btn btn-primary">UPDATE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .upload-area {
        background-color: #f8f9fa;
    }
    .border-2 {
        border-width: 2px !important;
    }
    .border-dashed {
        border-style: dashed !important;
    }
</style>

<script>
    // Untuk menampilkan nama file yang dipilih
    document.getElementById('imageUpload').addEventListener('change', function(e) {
        const label = this.nextElementSibling;
        if(this.files.length > 0) {
            label.textContent = this.files.length + ' file dipilih';
        }
    });

    document.getElementById('videoUpload').addEventListener('change', function(e) {
        const label = this.nextElementSibling;
        if(this.files.length > 0) {
            label.textContent = this.files[0].name;
        }
    });
</script>
@endsection
