<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imager - Material Picture Organizer</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa; /* Subtle background for light aesthetics */
            color: #007bff; /* Blue accents for text */
            font-family: 'Helvetica Neue', Arial, sans-serif; /* Modern, clean font choice */
        }
        .hero {
            text-align: center;
            width: 100%; /* Ensures the hero covers full width */
        }
        .hero h1 {
            font-size: 3rem; /* Large, prominent title */
            margin-bottom: 0.5rem;
        }
        .hero p {
            font-size: 1.5rem; /* Clearly visible subtext */
            margin-bottom: 2rem;
        }
        .button {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.3rem;
            background-color: #007bff;
            color: white;
            border: none;
            transition: background-color 0.3s, transform 0.3s;
        }
        .button:hover {
            background-color: #0056b3; /* Slightly darker on hover for interactive feel */
            transform: translateY(-2px); /* Subtle lift effect */
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>Imager</h1>
        <p>Material Picture Organizer</p>
        @if (Auth::check())
            <a href="{{ route('dashboard') }}" class="button">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="button">Login</a>
        @endif
    </div>
</body>
</html>
