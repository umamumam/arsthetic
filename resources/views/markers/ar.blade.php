<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://aframe.io/releases/1.5.0/aframe.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
  <style>
    #loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.8);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      font-family: Arial, sans-serif;
      flex-direction: column;
    }
    #start-button {
      padding: 10px 20px;
      margin-top: 15px;
      background: #4285f4;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div id="loading-overlay">
    <p>AR Experience Ready</p>
    <button id="start-button">Start AR</button>
    <p id="status-text" style="margin-top: 15px; font-size: 0.9em;"></p>
  </div>

  <a-scene mindar-image="imageTargetSrc: /storage/markers/targets.mind; autoStart: false;"
           embedded color-space="sRGB"
           renderer="colorManagement: true, physicallyCorrectLights"
           vr-mode-ui="enabled: false"
           device-orientation-permission-ui="enabled: false">
    <a-assets>
      @foreach($markers as $marker)
        <video id="video{{ $marker['number'] }}"
               src="{{ $marker['video'] }}"
               preload="auto"
               loop
               muted
               playsinline
               webkit-playsinline></video>
      @endforeach
    </a-assets>

    <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

    @foreach($markers as $marker)
      <a-entity id="marker{{ $marker['number'] }}"
                mindar-image-target="targetIndex: {{ $marker['number'] - 1 }}">
        <a-video src="#video{{ $marker['number'] }}"
                 width="0.8"
                 height="1"
                 position="0 0 0"
                 rotation="0 0 0"></a-video>
      </a-entity>
    @endforeach
  </a-scene>

  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      const loadingOverlay = document.getElementById('loading-overlay');
      const startButton = document.getElementById('start-button');
      const statusText = document.getElementById('status-text');
      const scene = document.querySelector('a-scene');

      // Initialize video and marker elements
      const videos = {};
      const markers = {};

      @foreach($markers as $marker)
        videos[{{ $marker['number'] }}] = document.querySelector("#video{{ $marker['number'] }}");
        markers[{{ $marker['number'] }}] = document.querySelector("#marker{{ $marker['number'] }}");

        // Set video properties for better mobile compatibility
        videos[{{ $marker['number'] }}].playsInline = true;
        videos[{{ $marker['number'] }}].webkitPlaysInline = true;
        videos[{{ $marker['number'] }}].crossOrigin = "anonymous";

        // Marker event handlers
        markers[{{ $marker['number'] }}].addEventListener("targetFound", () => {
          videos[{{ $marker['number'] }}].currentTime = 0;
          videos[{{ $marker['number'] }}].play().catch(e => {
            statusText.textContent = "Tap screen to play video";
          });
        });

        markers[{{ $marker['number'] }}].addEventListener("targetLost", () => {
          videos[{{ $marker['number'] }}].pause();
        });
      @endforeach

      // Start AR experience
      const startAR = async () => {
        try {
          statusText.textContent = "Initializing AR...";

          // First satisfy autoplay policy
          await Promise.all(Object.values(videos).map(video => {
            return video.play().then(() => video.pause()).catch(() => {});
          });

          // Start MindAR
          const mindarScene = scene.systems["mindar-image-system"];
          await mindarScene.start();

          // Verify targets loaded
          if (!mindarScene.targets || mindarScene.targets.length === 0) {
            throw new Error("Failed to load AR targets");
          }

          statusText.textContent = "Point camera at a marker";
          startButton.style.display = 'none';
        } catch (error) {
          console.error("AR initialization failed:", error);
          statusText.textContent = "Error: " + error.message;
          startButton.textContent = "Try Again";
          startButton.onclick = startAR;
        }
      };

      // Start on button click
      startButton.addEventListener('click', startAR);

      // Fallback for slow loading
      setTimeout(() => {
        if (loadingOverlay.style.display !== 'none' && !startButton.disabled) {
          statusText.textContent = "Having trouble? Try clicking Start AR again";
        }
      }, 5000);

      // Additional click handler for iOS
      document.addEventListener('click', () => {
        Object.values(videos).forEach(video => {
          video.play().catch(() => {});
        });
      }, { once: true });
    });
  </script>
</body>
</html>
