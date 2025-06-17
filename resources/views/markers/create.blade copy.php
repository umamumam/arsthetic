@extends('layouts2.landing')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">ADD PROJECT</h5>
        </div>
        <div class="card-body">
            <nav class="nav nav-pills mb-4">
                <a class="nav-link active" href="#">BASIC</a>
                <a class="nav-link" href="#">3D / PRO</a>
            </nav>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('markers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <h6>IMAGE</h6>
                    <p class="text-muted small">(max file size: 12MB, dimension: min 320px, max 10000px)</p>

                    <div class="dropzone border rounded p-4 text-center" id="imageDropzone">
                        <div class="dz-message">
                            <p>Drop image here or</p>
                            <button type="button" class="btn btn-outline-primary" id="selectImageBtn">
                                SELECT FILE
                            </button>
                            <input type="file" name="photos[]" id="photosInput" multiple
                                   class="d-none" accept="image/*"
                                   data-max-size="12"
                                   data-min-width="320" data-max-width="10000"
                                   data-min-height="320" data-max-height="10000">
                        </div>
                        <div class="dz-preview d-flex flex-wrap gap-2 mt-3" id="imagePreviews"></div>
                    </div>
                    <p class="small text-muted mt-2">Step 1: upload image</p>
                </div>

                <div class="mb-4">
                    <h6>VIDEO</h6>
                    <div class="border rounded p-4 text-center" id="videoDropzone">
                        <p>Drop video here or</p>
                        <button type="button" class="btn btn-outline-primary" id="selectVideoBtn">
                            SELECT FILE
                        </button>
                        <input type="file" name="video" id="videoInput"
                               class="d-none" accept="video/mp4">
                    </div>
                    <div class="video-info mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="use_first_frame" id="useFirstFrame">
                            <label class="form-check-label" for="useFirstFrame">
                                Use the first video frame as an image
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="transparent_video" id="transparentVideo">
                            <label class="form-check-label" for="transparentVideo">
                                This video is transparent
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="extended_tracking" id="extendedTracking">
                            <label class="form-check-label" for="extendedTracking">
                                Extended tracking
                            </label>
                        </div>
                    </div>
                    <p class="small text-muted mt-2">Step 2: upload video</p>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <p class="small text-muted">Step 3: save and try out via the app</p>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('markers.index') }}" class="btn btn-outline-secondary">CANCEL</a>
                    <button type="submit" class="btn btn-primary">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .dropzone {
        border: 2px dashed #dee2e6;
        transition: all 0.3s;
    }
    .dropzone.active {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }
    .dz-preview {
        max-height: 200px;
        overflow-y: auto;
    }
    .thumbnail-container {
        position: relative;
        margin: 5px;
    }
    .thumbnail {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
    }
    .remove-btn {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #dc3545;
        color: white;
        border: none;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image upload handling
    const imageDropzone = document.getElementById('imageDropzone');
    const photosInput = document.getElementById('photosInput');
    const selectImageBtn = document.getElementById('selectImageBtn');
    const imagePreviews = document.getElementById('imagePreviews');

    // Video upload handling
    const videoDropzone = document.getElementById('videoDropzone');
    const videoInput = document.getElementById('videoInput');
    const selectVideoBtn = document.getElementById('selectVideoBtn');

    // Handle image selection
    selectImageBtn.addEventListener('click', () => photosInput.click());

    // Handle video selection
    selectVideoBtn.addEventListener('click', () => videoInput.click());

    // Drag and drop for images
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        imageDropzone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        imageDropzone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        imageDropzone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        imageDropzone.classList.add('active');
    }

    function unhighlight() {
        imageDropzone.classList.remove('active');
    }

    imageDropzone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        photosInput.files = files;
        handleFiles(files);
    }

    photosInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        imagePreviews.innerHTML = '';

        if (files.length > 0) {
            Array.from(files).forEach(file => {
                if (!file.type.match('image.*')) {
                    alert('Only image files are allowed');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'thumbnail-container';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'thumbnail';

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-btn';
                    removeBtn.innerHTML = '×';
                    removeBtn.onclick = function() {
                        previewContainer.remove();
                        updateFileInput();
                    };

                    previewContainer.appendChild(img);
                    previewContainer.appendChild(removeBtn);
                    imagePreviews.appendChild(previewContainer);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    function updateFileInput() {
        // This function would need to update the actual file input
        // Implementation depends on how you want to handle file removal
    }

    // Similar drag and drop for video (simplified)
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        videoDropzone.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        videoDropzone.addEventListener(eventName, () => {
            videoDropzone.classList.add('active');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        videoDropzone.addEventListener(eventName, () => {
            videoDropzone.classList.remove('active');
        }, false);
    });

    videoDropzone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0 && files[0].type.match('video.*')) {
            videoInput.files = files;
            // You could add video preview here if needed
        }
    }, false);
});
</script>
@endsection
