@extends('layout')

@section('title', 'Model Information Dashboard')

@section('content')

    {{-- check user session --}}
    </p>{{ session('user') }}</p>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            <div><strong>Output:</strong>
                <pre>{{ session('output') }}</pre>
            </div>
            <div><strong>Error Output:</strong>
                <pre>{{ session('errorOutput') }}</pre>
            </div>
        @endif
        <h1>Model Information Dashboard</h1>
        
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
        </form>
        <hr>
        <div class="model-file-info">
            @if (isset($jsonData) && count($jsonData) > 0)
                <table id="file-table" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>File Name</th>
                            <th>Last Modified Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jsonData as $index => $file)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $file['file_name'] }}</td>
                                <td>{{ $file['last_modified_time'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No model file updates available.</p>
            @endif
            <button style="background-color:#0059c4" id="check-updates">Check Model File Updates</button>
        </div>
    </div>

    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#file-table').DataTable();

            $('#check-updates').click(function() {
                window.location.href = "{{ route('check-model-file-updates') }}";
            });
        });
    </script>

@endsection
