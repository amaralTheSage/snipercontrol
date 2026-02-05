<!DOCTYPE html>
<html>

<body>
    <form action="{{ url('/api/videos/upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="number" name="device_id" value="1" required>
        <input type="file" name="video" accept="video/*" required>
        <input type="number" step="0.0000001" name="start_lat" value="-23.5585" placeholder="Latitude">
        <input type="number" step="0.0000001" name="start_lng" value="-46.6253" placeholder="Longitude">
        <button type="submit">Upload</button>
    </form>
</body>

</html>