<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Media Upload Testing</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .test-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 8px;
            background: #f9f9f9;
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        .form-group {
            margin-bottom: 10px;
        }

        label {
            display: block;
            font-size: 0.9em;
            color: #666;
        }

        button {
            cursor: pointer;
            padding: 5px 15px;
        }
    </style>
</head>

<body>

    <div class="test-box">
        <h2>Video Test Upload</h2>
        <form action="{{ url('api/videos/upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Device ID</label>
                <input type="number" name="device_id" value="1" required>
            </div>
            <div class="form-group">
                <label>Video File</label>
                <input type="file" name="video" accept="video/*" required>
            </div>
            <div class="form-group">
                <label>Coordinates</label>
                <input type="number" step="0.0000001" name="start_lat" value="-23.5585">
                <input type="number" step="0.0000001" name="start_lng" value="-46.6253">
            </div>
            <button type="submit">Upload Video</button>
        </form>
    </div>

    <div class="test-box">
        <h2>Audio Test Upload</h2>
        <form action="{{ url('/api/audios/upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Device ID</label>
                <input type="number" name="device_id" value="1" required>
            </div>
            <div class="form-group">
                <label>Audio File</label>
                <input type="file" name="audio" accept="audio/*" required>
            </div>
            <div class="form-group">
                <label>Coordinates</label>
                <input type="number" step="0.0000001" name="start_lat" value="-23.5585">
                <input type="number" step="0.0000001" name="start_lng" value="-46.6253">
            </div>
            <button type="submit">Upload Audio</button>
        </form>
    </div>


    @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>

</html>