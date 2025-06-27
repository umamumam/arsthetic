<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://aframe.io/releases/1.0.4/aframe.min.js"></script>
    <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>
    <script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-detector.js"></script>
    <script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-handler.js"></script>

    <style>
        body {
            margin: 0;
            overflow: hidden;
            background-color: #000;
        }
        .marker-info {
            position: fixed;
            bottom: 20px;
            left: 20px;
            color: white;
            background: rgba(0,0,0,0.7);
            padding: 10px;
            border-radius: 5px;
            z-index: 9999;
            font-family: Arial, sans-serif;
        }
    </style>

    <script>
        // Komponen untuk stabilisasi video
        AFRAME.registerComponent('video-stabilizer', {
            schema: {
                markerNumber: { type: 'number', default: 1 }
            },

            init: function() {
                this.video = document.querySelector(`#vid${this.data.markerNumber}`);
                this.initialPosition = new THREE.Vector3(0, 0.1, 0);
                this.initialRotation = new THREE.Euler(-Math.PI/2, 0, 0);

                // Set initial position and rotation
                this.el.object3D.position.copy(this.initialPosition);
                this.el.object3D.rotation.copy(this.initialRotation);
            },

            tick: function() {
                // Kunci posisi dan rotasi
                this.el.object3D.position.copy(this.initialPosition);
                this.el.object3D.rotation.copy(this.initialRotation);

                // Update video texture jika perlu
                if (this.video && this.video.readyState >= 2) {
                    const videoTexture = this.el.components.material.material.map;
                    if (videoTexture) {
                        videoTexture.needsUpdate = true;
                    }
                }
            }
        });

        // Handler untuk video
        AFRAME.registerComponent('video-controller', {
            schema: {
                markerNumber: { type: 'number', default: 1 }
            },

            init: function() {
                this.video = document.querySelector(`#vid${this.data.markerNumber}`);
                this.marker = this.el;

                this.markerFound = this.markerFound.bind(this);
                this.markerLost = this.markerLost.bind(this);

                this.marker.addEventListener('markerFound', this.markerFound);
                this.marker.addEventListener('markerLost', this.markerLost);
            },

            markerFound: function() {
                if (this.video) {
                    this.video.currentTime = 0;
                    this.video.play().catch(e => console.log(`Autoplay blocked for video ${this.data.markerNumber}`));
                }
            },

            markerLost: function() {
                if (this.video) {
                    this.video.pause();
                }
            },

            remove: function() {
                this.marker.removeEventListener('markerFound', this.markerFound);
                this.marker.removeEventListener('markerLost', this.markerLost);
            }
        });
    </script>
</head>

<body>
    <a-scene
        vr-mode-ui="enabled: false"
        loading-screen="enabled: false;"
        arjs="sourceType: webcam; debugUIEnabled: false; detectionMode: mono_and_matrix; matrixCodeType: 3x3;"
        renderer="logarithmicDepthBuffer: true; precision: high;"
        embedded
        gesture-detector>

        <a-assets timeout="30000">
            <?php
            // Ambil file marker dan video yang sudah terurut
            $markerFiles = [];
            $videoFiles = [];

            // Scan direktori
            $markerDir = 'storage/markers/';
            $videoDir = 'storage/videos/';

            // Cari file marker dan video yang sesuai pola
            foreach (scandir($markerDir) as $file) {
                if (preg_match('/^marker(\d+)\.patt$/i', $file, $matches)) {
                    $number = (int)$matches[1];
                    $markerFiles[$number] = $file;
                }
            }

            foreach (scandir($videoDir) as $file) {
                if (preg_match('/^video(\d+)\.mp4$/i', $file, $matches)) {
                    $number = (int)$matches[1];
                    $videoFiles[$number] = $file;
                }
            }

            // Urutkan berdasarkan nomor
            ksort($markerFiles);
            ksort($videoFiles);

            // Generate video assets hanya untuk yang punya marker
            foreach ($markerFiles as $number => $markerFile) {
                if (isset($videoFiles[$number])) {
                    $videoFile = $videoFiles[$number];
                    echo "<video id=\"vid{$number}\" src=\"{$videoDir}{$videoFile}\"
                          preload=\"auto\" loop muted playsinline webkit-playsinline></video>";
                }
            }
            ?>
        </a-assets>

        <?php
        // Buat marker untuk setiap pasangan yang valid
        foreach ($markerFiles as $number => $markerFile) {
            if (isset($videoFiles[$number])) {
                echo "
                <a-marker
                    type=\"pattern\"
                    preset=\"custom\"
                    url=\"{$markerDir}{$markerFile}\"
                    id=\"marker{$number}\"
                    video-controller=\"markerNumber: {$number}\"
                    smooth=\"true\"
                    smoothCount=\"10\"
                    smoothTolerance=\"0.01\"
                    smoothThreshold=\"5\"
                    raycaster=\"objects: .clickable\"
                    emitevents=\"true\"
                    cursor=\"fuse: false; rayOrigin: mouse;\">

                    <a-video
                        src=\"#vid{$number}\"
                        width=\"1.6\"
                        height=\"0.9\"
                        position=\"0 0.1 0\"
                        rotation=\"0 0 0\"
                        class=\"clickable\"
                        video-stabilizer=\"markerNumber: {$number}\"
                        gesture-handler
                        material=\"shader: flat; transparent: true\">
                    </a-video>
                </a-marker>";
            }
        }
        ?>

        <a-entity camera></a-entity>
    </a-scene>

    <div class="marker-info">
        <p>Scan salah satu marker:</p>
        <?php
        foreach ($markerFiles as $number => $markerFile) {
            if (isset($videoFiles[$number])) {
                echo "<p>Marker {$number}: {$markerFile} (Video {$number}.mp4)</p>";
            }
        }
        ?>
    </div>

    <script>
        // Unlock autoplay policy
        document.addEventListener('DOMContentLoaded', () => {
            document.body.addEventListener('click', () => {
                const videos = document.querySelectorAll('video');
                videos.forEach(video => {
                    video.play().catch(e => console.log('Autoplay blocked:', e));
                });
            }, { once: true });
        });
    </script>
</body>
</html>
