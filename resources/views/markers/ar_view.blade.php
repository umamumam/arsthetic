<!doctype html>
<html>
<head>
    <script src="https://aframe.io/releases/1.0.4/aframe.min.js"></script>
    <script src="https://raw.githack.com/AR-js-org/AR.js/master/aframe/build/aframe-ar.js"></script>
    <script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-detector.js"></script>
    <script src="https://raw.githack.com/AR-js-org/studio-backend/master/src/modules/marker/tools/gesture-handler.js"></script>

    <script>
        // Konfigurasi jumlah maksimal marker
        const MAX_MARKERS = 10;

        // Fungsi untuk membuat video handler dinamis
        function createVideoHandler(markerNumber) {
            AFRAME.registerComponent(`videohandler${markerNumber}`, {
                init: function () {
                    const marker = this.el;
                    this.vid = document.querySelector(`#vid${markerNumber}`);

                    marker.addEventListener('markerFound', function () {
                        this.vid.play();
                        console.log(`Marker ${markerNumber} ditemukan`);
                    }.bind(this));

                    marker.addEventListener('markerLost', function () {
                        this.vid.pause();
                        console.log(`Marker ${markerNumber} hilang`);
                    }.bind(this));
                }
            });
        }

        // Buat handler untuk semua marker
        document.addEventListener('DOMContentLoaded', function() {
            for (let i = 1; i <= MAX_MARKERS; i++) {
                createVideoHandler(i);
            }
        });
    </script>
</head>

<body style="margin: 0; overflow: hidden;">
    <a-scene vr-mode-ui="enabled: false" loading-screen="enabled: false;"
        arjs='sourceType: webcam; debugUIEnabled: false;' id="scene" embedded gesture-detector>
        <a-assets>
            <!-- Video assets akan diisi secara dinamis -->
            <?php
            // Scan video directory
            $videoFiles = scandir('storage/videos');
            $videoFiles = array_filter($videoFiles, function($file) {
                return preg_match('/^video\d+\.mp4$/i', $file);
            });

            // Urutkan video secara numerik
            natsort($videoFiles);

            foreach ($videoFiles as $index => $videoFile) {
                $markerNumber = $index + 1;
                echo "<video id=\"vid{$markerNumber}\" src=\"storage/videos/{$videoFile}\"
                      preload=\"auto\" loop muted playsinline webkit-playsinline></video>";
            }
            ?>
        </a-assets>

        <!-- Marker akan diisi secara dinamis -->
        <?php
        // Scan marker directory
        $markerFiles = scandir('storage/markers');
        $markerFiles = array_filter($markerFiles, function($file) {
            return preg_match('/^marker\d+\.patt$/i', $file);
        });

        // Urutkan marker secara numerik
        natsort($markerFiles);

        foreach ($markerFiles as $index => $markerFile) {
            $markerNumber = $index + 1;
            echo "<a-marker type=\"pattern\" preset=\"custom\"
                  url=\"storage/markers/{$markerFile}\"
                  videohandler{$markerNumber} smooth=\"true\" smoothCount=\"10\"
                  smoothTolerance=\"0.01\" smoothThreshold=\"5\"
                  raycaster=\"objects: .clickable\" emitevents=\"true\"
                  cursor=\"fuse: false; rayOrigin: mouse;\" id=\"marker{$markerNumber}\">
                  <a-video src=\"#vid{$markerNumber}\" width=\"1.6\" height=\"0.9\"
                  position=\"0 0.1 0\" rotation=\"-90 0 0\" class=\"clickable\"
                  gesture-handler></a-video>
                  </a-marker>";
        }
        ?>

        <a-entity camera></a-entity>
    </a-scene>

    <div style="position: fixed; bottom: 20px; left: 20px; color: white; background: rgba(0,0,0,0.5); padding: 10px; z-index: 9999;">
        <p>Scan salah satu marker:</p>
        <?php
        foreach ($markerFiles as $index => $markerFile) {
            $markerNumber = $index + 1;
            echo "<p>{$markerNumber}. {$markerFile} (Video {$markerNumber})</p>";
        }
        ?>
    </div>
</body>
</html>
