<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://aframe.io/releases/1.5.0/aframe.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
  <style>
    #start-message {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      font-family: Arial, sans-serif;
      text-align: center;
      flex-direction: column;
    }

    #start-button {
      padding: 15px 30px;
      font-size: 18px;
      margin-top: 20px;
      background: #4285f4;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div id="start-message">
    <h2>AR Experience Ready</h2>
    <p>Please click the button below to start the AR experience</p>
    <button id="start-button">Start AR</button>
  </div>

  <a-scene mindar-image="imageTargetSrc: /storage/markers/targets.mind; autoStart: false;" embedded color-space="sRGB"
    renderer="colorManagement: true, physicallyCorrectLights" vr-mode-ui="enabled: false"
    device-orientation-permission-ui="enabled: false">
    <a-assets>
        <video id="video1" src="{{ asset('storage/videos/video1.mp4') }}" preload="auto" loop muted playsinline webkit-playsinline></video>
        <video id="video2" src="{{ asset('storage/videos/video2.mp4') }}" preload="auto" loop muted playsinline webkit-playsinline></video>
    </a-assets>

    <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

    <!-- Marker 1 -->
    <a-entity id="marker1" mindar-image-target="targetIndex: 0" target-fixed>
      <a-video src="#video1" width="0.8" height="1" position="0 0 0" rotation="0 0 0"></a-video>
    </a-entity>

    <!-- Marker 2 -->
    <a-entity id="marker2" mindar-image-target="targetIndex: 1" target-fixed>
      <a-video src="#video2" width="0.8" height="1" position="0 0 0" rotation="0 0 0"></a-video>
    </a-entity>
  </a-scene>

  <script>
    // Wait for everything to load
    document.addEventListener('DOMContentLoaded', () => {
      const scene = document.querySelector('a-scene');
      const video1 = document.querySelector("#video1");
      const video2 = document.querySelector("#video2");
      const marker1 = document.querySelector("#marker1");
      const marker2 = document.querySelector("#marker2");
      const startButton = document.querySelector("#start-button");
      const startMessage = document.querySelector("#start-message");

      // Start AR when button is clicked
      startButton.addEventListener('click', () => {
        // Hide the start message
        startMessage.style.display = 'none';

        // Start the AR experience
        const sceneEl = document.querySelector('a-scene');
        const mindarScene = sceneEl.systems["mindar-image-system"];
        mindarScene.start(); // Start the AR system

        // Pre-play videos (required for iOS)
        video1.play().catch(e => console.log("Video1 play error:", e));
        video2.play().catch(e => console.log("Video2 play error:", e));

        // Pause immediately (we'll play when marker is found)
        video1.pause();
        video2.pause();
      });

      // Marker event listeners
      marker1.addEventListener("targetFound", () => {
        video1.play().catch(e => console.log("Marker1 video play error:", e));
      });

      marker1.addEventListener("targetLost", () => {
        video1.pause();
        video1.currentTime = 0;
      });

      marker2.addEventListener("targetFound", () => {
        video2.play().catch(e => console.log("Marker2 video play error:", e));
      });

      marker2.addEventListener("targetLost", () => {
        video2.pause();
        video2.currentTime = 0;
      });

      // Fallback: click anywhere to unlock audio
      document.addEventListener('click', () => {
        video1.play().catch(e => console.log("Fallback video1 play error:", e));
        video2.play().catch(e => console.log("Fallback video2 play error:", e));
      }, { once: true });
    });
  </script>
</body>

</html>
