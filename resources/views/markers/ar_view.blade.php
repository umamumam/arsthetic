<!doctype html>
<html>
<head>
    <script src="https://aframe.io/releases/1.0.4/aframe.min.js"></script>
    <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>
    <script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-detector.js"></script>
    <script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-handler.js"></script>

    <script>
        // Function untuk membuat video handler dinamis
        function createVideoHandler(videoId) {
            return {
                init: function () {
                    var marker = this.el;
                    this.vid = document.querySelector("#" + videoId);

                    marker.addEventListener('markerFound', function () {
                        if (this.vid) {
                            this.vid.play();
                            console.log("Marker ditemukan - Video: " + videoId);
                        }
                    }.bind(this));

                    marker.addEventListener('markerLost', function () {
                        if (this.vid) {
                            this.vid.pause();
                            console.log("Marker hilang - Video: " + videoId);
                        }
                    }.bind(this));
                }
            };
        }

        // Daftar marker dari Laravel
        const markers = @json($markers);

        // Register komponen untuk setiap marker yang memiliki video
        markers.forEach((marker) => {
            if (marker.video_url) {
                AFRAME.registerComponent('videohandler-' + marker.id, createVideoHandler('vid-' + marker.id));
            }
        });
    </script>
</head>

<body style="margin: 0; overflow: hidden;">
    <a-scene
        vr-mode-ui="enabled: false"
        loading-screen="enabled: false;"
        arjs='sourceType: webcam; debugUIEnabled: true;'
        id="scene"
        embedded
        gesture-detector
    >
        <a-assets>
            @foreach($markers as $marker)
                @if($marker['video_url'])
                <video
                    id="vid-{{ $marker['id'] }}"
                    src="{{ $marker['video_url'] }}"
                    preload="auto"
                    loop
                    muted
                    playsinline
                    webkit-playsinline
                ></video>
                @endif
            @endforeach
        </a-assets>

        @foreach($markers as $marker)
        <a-marker
            type="pattern"
            preset="custom"
            url="{{ $marker['pattern_url'] }}"
            @if($marker['video_url']) videohandler-{{ $marker['id'] }} @endif
            smooth="true"
            smoothCount="10"
            smoothTolerance="0.01"
            smoothThreshold="5"
            raycaster="objects: .clickable"
            emitevents="true"
            cursor="fuse: false; rayOrigin: mouse;"
            id="{{ $marker['id'] }}"
        >
            @if($marker['video_url'])
            <a-video
                src="#vid-{{ $marker['id'] }}"
                width="1.6"
                height="0.9"
                position="0 0.1 0"
                rotation="-90 0 0"
                class="clickable"
                gesture-handler
            ></a-video>
            @endif
        </a-marker>
        @endforeach

        <a-entity camera></a-entity>
    </a-scene>
</body>
</html>
