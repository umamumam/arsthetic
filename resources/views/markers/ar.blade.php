<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { margin: 0; overflow: hidden; }
    #play-button {
      position: absolute;
      z-index: 9999;
      padding: 15px 30px;
      background: #4285f4;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
  <script src="https://aframe.io/releases/1.5.0/aframe.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
</head>

<body>
  <button id="play-button" style="display:none;">Tap to Enable AR</button>

  <a-scene
    mindar-image="imageTargetSrc: {{ asset('storage/markers/targets.mind') }}; autoStart: false; showStats: true;"
    vr-mode-ui="enabled: false">

    <a-assets>
      @for($i = 1; $i <= 2; $i++)
        <video id="video{{$i}}" src="{{ asset("storage/videos/video$i.mp4") }}"
               preload="auto" loop muted playsinline webkit-playsinline crossorigin="anonymous">
        </video>
      @endfor
    </a-assets>

    <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

    @for($i = 1; $i <= 2; $i++)
      <a-entity id="marker{{$i}}" mindar-image-target="targetIndex: {{$i-1}}">
        <a-video src="#video{{$i}}" width="1.778" height="1"
                material="shader: flat; transparent: true">
        </a-video>
      </a-entity>
    @endfor
  </a-scene>

  <script>
    // Start AR setelah interaksi user
    document.getElementById('play-button').addEventListener('click', function() {
      this.style.display = 'none';
      const scene = document.querySelector('a-scene');
      scene.systems["mindar-image-system"].start();

      // Trigger play semua video
      document.querySelectorAll('video').forEach(v => {
        v.play().catch(e => console.log('Video play error:', e));
      });
    });

    // Tampilkan tombol play jika AR tidak auto start
    setTimeout(() => {
      if (!document.querySelector('a-scene').hasLoaded) {
        document.getElementById('play-button').style.display = 'block';
      }
    }, 2000);

    // Handle marker detection
    document.querySelector('a-scene').addEventListener('loaded', function() {
      document.querySelectorAll('[id^="marker"]').forEach((marker, index) => {
        const video = document.querySelector(`#video${index+1}`);

        marker.addEventListener('targetFound', () => {
          video.play().catch(e => console.log('Marker play error:', e));
        });

        marker.addEventListener('targetLost', () => {
          video.pause();
        });
      });
    });
  </script>
</body>
</html>
