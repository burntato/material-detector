@extends('layout')

@section('title', 'Model Information Dashboard')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <div class="container">
        <h1>Model Information Dashboard</h1>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            <div><strong>Output:</strong>
                <pre>{{ session('output') }}</pre>
            </div>
            <div><strong>Error Output:</strong>
                <pre>{{ session('errorOutput') }}</pre>
            </div>
        @endif
        @if (isset($modelInfo))
            <h2>Latest Version: V{{ $modelInfo['version'] }}</h2>
            <h3>Model Files:</h3>
            <ul>
                @foreach ($modelInfo['files'] as $file)
                    <li>{{ $file }}</li>
                @endforeach
            </ul>
            <h3>Classes:</h3>
            <div class="classes-container">
                @foreach ($modelInfo['classes'] as $class)
                    <div>{{ $class }}</div>
                @endforeach
            </div>
        @else
            <p class="error">{{ $error ?? 'No model information available.' }}</p>
        @endif
        <form action="{{ route('update-model-info') }}" method="GET">
            @csrf
            <button type="submit">Update Model Info</button>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </form>
    </div>
@endsection
