<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://aframe.io/releases/1.5.0/aframe.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
</head>

<body>
  <a-scene mindar-image="imageTargetSrc: /storage/markers/targets.mind; autoStart: true;" embedded color-space="sRGB"
    renderer="colorManagement: true, physicallyCorrectLights" vr-mode-ui="enabled: false"
    device-orientation-permission-ui="enabled: false">
    <a-assets>
      <video id="video1" src="public\storage\videos\video1.mp4" preload="auto" loop muted playsinline webkit-playsinline></video>
      <video id="video2" src="public\storage\videos\video2.mp4" preload="auto" loop muted playsinline webkit-playsinline></video>
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
    const video1 = document.querySelector("#video1");
    const video2 = document.querySelector("#video2");

    const marker1 = document.querySelector("#marker1");
    const marker2 = document.querySelector("#marker2");

    marker1.addEventListener("targetFound", () => {
      video1.play();
    });
    marker1.addEventListener("targetLost", () => {
      video1.pause();
    });

    marker2.addEventListener("targetFound", () => {
      video2.play();
    });
    marker2.addEventListener("targetLost", () => {
      video2.pause();
    });

    // Optional: trigger play() with user gesture to unlock autoplay
    window.addEventListener("click", () => {
      video1.play().catch(() => { });
      video2.play().catch(() => { });
    });
  </script>
</body>

</html>
