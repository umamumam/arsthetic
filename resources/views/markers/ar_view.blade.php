@extends('layouts1.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h5 class="m-0 font-weight-bold text-white">AR Camera</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Arahkan kamera ke marker yang sesuai
            </div>

            <div class="text-center mb-3">
                <button id="startAR" class="btn btn-success btn-lg">
                    <i class="fas fa-camera"></i> Start AR Camera
                </button>
            </div>

            <div id="arContainer" style="display: none;">
                <div style="margin: 0; overflow: hidden; position: relative;">
                    <a-scene vr-mode-ui="enabled: false" loading-screen="enabled: false;"
                        arjs='sourceType: webcam; debugUIEnabled: false; detectionMode: mono_and_matrix; matrixCodeType: 3x3;'
                        id="scene" embedded>
                        <a-assets>
                            @foreach($markers as $marker)
                            <video id="vid{{ $marker['number'] }}" src="{{ $marker['video'] }}" preload="auto" loop
                                muted playsinline webkit-playsinline>
                            </video>
                            @endforeach
                        </a-assets>

                        @foreach($markers as $marker)
                        <a-marker type="pattern" preset="custom" url="{{ $marker['patt'] }}"
                            id="marker{{ $marker['number'] }}" smooth="true" smoothCount="10" smoothTolerance="0.01"
                            smoothThreshold="5">
                            <a-video src="#vid{{ $marker['number'] }}" width="1.6" height="0.9" position="0 0.1 0"
                                rotation="-90 0 0">
                            </a-video>
                        </a-marker>
                        @endforeach

                        <a-entity camera></a-entity>
                    </a-scene>

                    <button id="stopAR" class="btn btn-danger"
                        style="position: absolute; bottom: 20px; left: 20px; z-index: 9999;">
                        <i class="fas fa-stop"></i> Stop AR
                    </button>
                </div>
            </div>

            <div class="mt-4">
                <h5>Daftar Marker dan Video:</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Marker File</th>
                                <th>Video File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($markers as $marker)
                            <tr>
                                <td>{{ $marker['number'] }}</td>
                                <td>{{ basename($marker['patt']) }}</td>
                                <td>{{ basename($marker['video']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Library AR.js -->
<script src="https://aframe.io/releases/1.0.4/aframe.min.js"></script>
<script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const startBtn = document.getElementById('startAR');
    const stopBtn = document.getElementById('stopAR');
    const arContainer = document.getElementById('arContainer');

    // Register video handlers for each marker
    @foreach($markers as $marker)
    AFRAME.registerComponent('videohandler{{ $marker['number'] }}', {
        init: function() {
            const marker = this.el;
            const vid = document.querySelector("#vid{{ $marker['number'] }}");

            marker.addEventListener('markerFound', function() {
                vid.play();
                console.log("Marker {{ $marker['number'] }} ditemukan");
            });

            marker.addEventListener('markerLost', function() {
                vid.pause();
                console.log("Marker {{ $marker['number'] }} hilang");
            });
        }
    });

    // Add component to marker
    document.querySelector("#marker{{ $marker['number'] }}").setAttribute('videohandler{{ $marker['number'] }}', '');
    @endforeach

    // Start AR
    startBtn.addEventListener('click', function() {
        arContainer.style.display = 'block';
        startBtn.style.display = 'none';

        // Reset all videos
        @foreach($markers as $marker)
        document.querySelector("#vid{{ $marker['number'] }}").currentTime = 0;
        @endforeach
    });

    // Stop AR
    stopBtn.addEventListener('click', function() {
        arContainer.style.display = 'none';
        startBtn.style.display = 'block';

        // Pause all videos
        @foreach($markers as $marker)
        document.querySelector("#vid{{ $marker['number'] }}").pause();
        @endforeach
    });
});
</script>

<style>
    #arContainer {
        width: 100%;
        height: 70vh;
        position: relative;
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    #startAR,
    #stopAR {
        transition: all 0.3s ease;
    }

    .a-canvas {
        border-radius: 8px;
    }
</style>
@endsection
