<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://aframe.io/releases/1.3.0/aframe.min.js"></script>
    <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar-nft.js"></script>

    <style>
        body {
            margin: 0;
            overflow: hidden;
            background-color: #000;
        }
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            font-family: Arial, sans-serif;
        }
        .progress-bar {
            width: 80%;
            height: 20px;
            background: #333;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        .progress {
            height: 100%;
            background: #4CAF50;
            width: 0%;
            transition: width 0.3s;
        }
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <h2>Loading AR Experience</h2>
        <p>Please wait while we prepare your markers...</p>
        <div class="progress-bar">
            <div class="progress" id="progressBar"></div>
        </div>
    </div>

    <a-scene
        vr-mode-ui="enabled: false"
        loading-screen="enabled: false;"
        arjs="sourceType: webcam; debugUIEnabled: false; detectionMode: mono_and_matrix; matrixCodeType: 3x3;"
        renderer="logarithmicDepthBuffer: true; precision: medium;"
        embedded>

        <!-- Kamera -->
        <a-entity camera></a-entity>

        <!-- Marker dan Video akan dimuat dinamis -->
    </a-scene>

    <script>
        // Konfigurasi
        const config = {
            markerSize: 0.5, // Ukuran marker dalam meter (sesuai kebutuhan)
            videoSize: {
                width: 1.6,   // Lebar video dalam meter
                height: 0.9   // Tinggi video dalam meter
            },
            markerOffset: 0.1 // Jarak video di atas marker
        };

        // Data marker dan video
        const markers = [
            { id: 1, patternUrl: 'storage/markers/marker1.patt', videoUrl: 'storage/videos/video1.mp4' },
            { id: 2, patternUrl: 'storage/markers/marker2.patt', videoUrl: 'storage/videos/video2.mp4' }
            // Tambahkan marker lain sesuai kebutuhan
        ];

        // Fungsi untuk memuat marker secara dinamis
        async function loadMarkers() {
            const scene = document.querySelector('a-scene');
            const loadingScreen = document.getElementById('loadingScreen');
            const progressBar = document.getElementById('progressBar');

            let loadedCount = 0;

            // Buat assets terlebih dahulu
            const assets = document.createElement('a-assets');
            assets.setAttribute('timeout', '60000');

            // Tambahkan video ke assets
            markers.forEach(marker => {
                const video = document.createElement('video');
                video.id = `vid${marker.id}`;
                video.src = marker.videoUrl;
                video.setAttribute('preload', 'auto');
                video.setAttribute('loop', 'true');
                video.setAttribute('muted', 'true');
                video.setAttribute('playsinline', 'true');
                video.setAttribute('webkit-playsinline', 'true');
                assets.appendChild(video);
            });

            scene.appendChild(assets);

            // Tunggu sampai assets dimuat
            await new Promise(resolve => {
                assets.addEventListener('loaded', resolve);
            });

            // Buat marker satu per satu
            markers.forEach((marker, index) => {
                const markerEl = document.createElement('a-marker');
                markerEl.setAttribute('type', 'pattern');
                markerEl.setAttribute('preset', 'custom');
                markerEl.setAttribute('url', marker.patternUrl);
                markerEl.setAttribute('id', `marker${marker.id}`);
                markerEl.setAttribute('size', config.markerSize);
                markerEl.setAttribute('smooth', 'true');
                markerEl.setAttribute('smoothCount', '10');
                markerEl.setAttribute('smoothTolerance', '0.01');
                markerEl.setAttribute('smoothThreshold', '5');

                // Buat video entity
                const videoEl = document.createElement('a-video');
                videoEl.setAttribute('src', `#vid${marker.id}`);
                videoEl.setAttribute('width', config.videoSize.width);
                videoEl.setAttribute('height', config.videoSize.height);
                videoEl.setAttribute('position', `0 ${config.markerOffset} 0`);
                videoEl.setAttribute('rotation', '-90 0 0');
                videoEl.setAttribute('material', 'shader: flat; transparent: true');

                // Komponen stabilizer
                videoEl.setAttribute('stabilizer', '');

                markerEl.appendChild(videoEl);
                scene.appendChild(markerEl);

                // Update progress
                loadedCount++;
                progressBar.style.width = `${(loadedCount / markers.length) * 100}%`;

                // Event handler untuk video
                markerEl.addEventListener('markerFound', () => {
                    const video = document.querySelector(`#vid${marker.id}`);
                    if (video) {
                        video.currentTime = 0;
                        video.play().catch(e => console.log('Autoplay blocked:', e));
                    }
                });

                markerEl.addEventListener('markerLost', () => {
                    const video = document.querySelector(`#vid${marker.id}`);
                    if (video) video.pause();
                });
            });

            // Sembunyikan loading screen
            setTimeout(() => {
                loadingScreen.style.opacity = '0';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 500);
            }, 500);
        }

        // Komponen stabilizer
        AFRAME.registerComponent('stabilizer', {
            tick: function() {
                // Kunci posisi dan rotasi
                this.el.object3D.position.set(0, config.markerOffset, 0);
                this.el.object3D.rotation.set(-Math.PI/2, 0, 0);

                // Update video texture
                if (this.el.components.material) {
                    const texture = this.el.components.material.material.map;
                    if (texture) texture.needsUpdate = true;
                }
            }
        });

        // Mulai memuat ketika DOM siap
        document.addEventListener('DOMContentLoaded', () => {
            // Unlock autoplay dengan interaksi user
            document.body.addEventListener('click', () => {
                markers.forEach(marker => {
                    const video = document.querySelector(`#vid${marker.id}`);
                    if (video) video.play().catch(e => console.log('Autoplay blocked:', e));
                });
            }, { once: true });

            // Mulai memuat marker
            loadMarkers();
        });
    </script>
</body>
</html>
