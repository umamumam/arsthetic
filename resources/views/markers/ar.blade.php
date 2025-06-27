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
    }
  </style>
</head>
<body>
  <div id="loading-overlay">
    <p>Loading AR experience...</p>
  </div>

  <a-scene mindar-image="imageTargetSrc: /storage/markers/targets.mind; autoStart: false;" embedded color-space="sRGB"
    renderer="colorManagement: true, physicallyCorrectLights" vr-mode-ui="enabled: false"
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
                mindar-image-target="targetIndex: {{ $marker['number'] - 1 }}"
                target-fixed>
        <a-video src="#video{{ $marker['number'] }}"
                 width="0.8"
                 height="1"
                 position="0 0 0"
                 rotation="0 0 0"></a-video>
      </a-entity>
    @endforeach
  </a-scene>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const loadingOverlay = document.getElementById('loading-overlay');
      const scene = document.querySelector('a-scene');

      // Initialize videos and markers
      const videos = {};
      const markers = {};

      @foreach($markers as $marker)
        videos['video{{ $marker['number'] }}'] = document.querySelector("#video{{ $marker['number'] }}");
        markers['marker{{ $marker['number'] }}'] = document.querySelector("#marker{{ $marker['number'] }}");

        markers['marker{{ $marker['number'] }}'].addEventListener("targetFound", () => {
          videos['video{{ $marker['number'] }}'].play().catch(e => console.log(e));
        });

        markers['marker{{ $marker['number'] }}'].addEventListener("targetLost", () => {
          videos['video{{ $marker['number'] }}'].pause();
        });
      @endforeach

      // Start AR after user interaction
      const startAR = () => {
        loadingOverlay.style.display = 'none';
        const mindarScene = scene.systems["mindar-image-system"];
        mindarScene.start();

        // Pre-play videos to satisfy autoplay policy
        Object.values(videos).forEach(video => {
          video.play().then(() => video.pause()).catch(e => console.log(e));
        });
      };

      // Start on click
      document.body.addEventListener('click', startAR, { once: true });

      // Fallback timeout
      setTimeout(() => {
        if (loadingOverlay.style.display !== 'none') {
          loadingOverlay.innerHTML = '<p>Click anywhere to start AR</p>';
        }
      }, 3000);
    });
  </script>
</body>
</html>
