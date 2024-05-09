<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 5px;
            background: #eee;
            padding: 10px;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Model Information Dashboard</h1>
        @if(isset($modelInfo))
            <h2>Latest Version: V{{ $modelInfo['version'] }}</h2>
            <h3>Model Files:</h3>
            <ul>
                @foreach($modelInfo['files'] as $file)
                    <li>{{ $file }}</li>
                @endforeach
            </ul>
        @else
            <p class="error">{{ $error ?? 'No model information available.' }}</p>
        @endif
        <form action="{{ route('update-model-info') }}" method="GET">
            @csrf
            <button type="submit">Update Model Info</button>
        </form>
    </div>
</body>
</html>
