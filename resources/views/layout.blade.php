{{-- resources/views/layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <link rel="stylesheet" href="{{ asset('css/fa/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>

<body>
    <div class="top-nav">
        <div class="nav-item"><a href="{{ route('dashboard') }}">Home</a></div>
        <div class="nav-item"><a href="#collection">Collection</a></div>
        <div class="nav-item"><a href="#training">Training</a></div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <div class="sidebar-footer">
        @if (Auth::check())
            <span>{{ Auth::user()->name }}</span>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span class="icon-label">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endif
    </div>
</body>

</html>
