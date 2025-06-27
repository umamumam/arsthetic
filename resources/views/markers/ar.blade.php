<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MindAR with Video</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://aframe.io/releases/1.5.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.5/dist/mindar-image-aframe.prod.js"></script>
</head>

<body>
    <a-scene mindar-image="imageTargetSrc: /storage/markers/targets.mind; autoStart: true;" embedded color-space="sRGB"
        renderer="colorManagement: true, physicallyCorrectLights" vr-mode-ui="enabled: false"
        device-orientation-permission-ui="enabled: false">
        <a-assets>
            <video id="video1" src="{{ asset('storage/videos/video1.mp4') }}" preload="auto" loop muted playsinline
                webkit-playsinline crossorigin="anonymous"></video>
            <video id="video2" src="{{ asset('storage/videos/video2.mp4') }}" preload="auto" loop muted playsinline
                webkit-playsinline crossorigin="anonymous"></video>
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

        video1.load();
        video2.load();

        const unlockPlayback = () => {
        video1.play().then(() => video1.pause()).catch(() => {});
        video2.play().then(() => video2.pause()).catch(() => {});
        window.removeEventListener("click", unlockPlayback);
        };
        window.addEventListener("click", unlockPlayback);

        marker1.addEventListener("targetFound", () => {
        console.log("Marker 1 found");
        video1.play().catch((e) => console.warn("Video1 play error:", e));
        });
        marker1.addEventListener("targetLost", () => {
        console.log("Marker 1 lost");
        video1.pause();
        video1.currentTime = 0;
        });

        marker2.addEventListener("targetFound", () => {
        console.log("Marker 2 found");
        video2.play().catch((e) => console.warn("Video2 play error:", e));
        });
        marker2.addEventListener("targetLost", () => {
        console.log("Marker 2 lost");
        video2.pause();
        video2.currentTime = 0;
        });
    </script>
</body>

</html>
