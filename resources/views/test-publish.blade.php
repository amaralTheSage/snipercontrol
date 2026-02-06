<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Phone Camera Publisher</title>
</head>

<body style="margin: 0; padding: 20px; font-family: sans-serif;">
    <h2>Device Camera Stream</h2>
    <input type="text" id="deviceId" placeholder="Device ID (e.g., 123)" value="1"
        style="width: 100%; padding: 10px; margin-bottom: 10px;">
    <button id="startBtn"
        style="width: 100%; padding: 15px; font-size: 16px; background: #10b981; color: white; border: none; border-radius: 5px;">
        Start Streaming
    </button>
    <button id="stopBtn"
        style="width: 100%; padding: 15px; font-size: 16px; background: #ef4444; color: white; border: none; border-radius: 5px; margin-top: 10px; display: none;">
        Stop Streaming
    </button>
    <div id="status" style="margin-top: 20px; padding: 10px; background: #f3f4f6; border-radius: 5px;"></div>
    <video id="localVideo" autoplay muted playsinline
        style="width: 100%; margin-top: 20px; border-radius: 5px; display: none;"></video>

    <script type="module">
        import { Room } from 'https://unpkg.com/livekit-client/dist/livekit-client.esm.mjs';

        let room = null;

        document.getElementById('startBtn').addEventListener('click', async () => {
            const deviceId = document.getElementById('deviceId').value;
            const statusDiv = document.getElementById('status');

            try {
                statusDiv.textContent = 'Getting token...';

                const response = await fetch('/api/livekit/device-token', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ device_id: deviceId })
                });

                if (!response.ok) throw new Error('Failed to get token');
                const { token, url } = await response.json();

                statusDiv.textContent = 'Connecting to LiveKit...';

                room = new Room();
                await room.connect(url, token);

                statusDiv.textContent = 'Connected! Starting camera...';

                await room.localParticipant.setCameraEnabled(true);
                await room.localParticipant.setMicrophoneEnabled(true);

                const videoTrack = room.localParticipant.videoTrackPublications.values().next().value?.track;
                if (videoTrack) {
                    const videoElement = document.getElementById('localVideo');
                    videoElement.srcObject = new MediaStream([videoTrack.mediaStreamTrack]);
                    videoElement.style.display = 'block';
                }

                statusDiv.textContent = '✅ Streaming! Room: device-' + deviceId;
                statusDiv.style.background = '#d1fae5';
                document.getElementById('stopBtn').style.display = 'block';

            } catch (error) {
                statusDiv.textContent = '❌ Error: ' + error.message;
                statusDiv.style.background = '#fee2e2';
                console.error(error);
            }
        });

        document.getElementById('stopBtn').addEventListener('click', async () => {
            if (room) {
                await room.disconnect();
                room = null;
                document.getElementById('localVideo').style.display = 'none';
                document.getElementById('stopBtn').style.display = 'none';
                document.getElementById('status').textContent = 'Stopped';
                document.getElementById('status').style.background = '#f3f4f6';
            }
        });
    </script>
</body>

</html>