<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://aframe.io/releases/1.5.0/aframe.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
</head>

<body>
  <a-scene
    mindar-image="imageTargetSrc: {{ asset('storage/markers/targets.mind') }}; autoStart: true;"
    embedded
    color-space="sRGB"
    renderer="colorManagement: true, physicallyCorrectLights"
    vr-mode-ui="enabled: false"
    device-orientation-permission-ui="enabled: false">

    <a-assets>
      @for($i = 1; $i <= 2; $i++)
        <video
          id="video{{$i}}"
          src="{{ asset("storage/videos/video$i.mp4") }}"
          preload="auto"
          loop
          muted
          playsinline
          webkit-playsinline
          crossorigin="anonymous">
        </video>
      @endfor
    </a-assets>

    <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

    @for($i = 1; $i <= 2; $i++)
      <a-entity
        id="marker{{$i}}"
        mindar-image-target="targetIndex: {{$i-1}}"
        target-fixed>
        <a-video
          src="#video{{$i}}"
          width="0.8"
          height="1"
          position="0 0 0"
          rotation="0 0 0"
          material="shader: flat;"
          crossorigin="anonymous">
        </a-video>
      </a-entity>
    @endfor
  </a-scene>

  <script>
    document.querySelectorAll('[id^="marker"]').forEach((marker, index) => {
      const video = document.querySelector(`#video${index+1}`);

      marker.addEventListener('targetFound', () => {
        video.play().catch(e => console.log('Play error:', e));
      });

      marker.addEventListener('targetLost', () => {
        video.pause();
      });
    });

    // Handle autoplay policy
    document.addEventListener('click', () => {
      document.querySelectorAll('video').forEach(video => {
        video.play().catch(e => console.log('Autoplay error:', e));
      });
    });
  </script>
</body>
</html>
