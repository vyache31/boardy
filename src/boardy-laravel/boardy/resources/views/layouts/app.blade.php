<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Boardy')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('posts.index') }}">Boardy</a>
        <div class="ms-auto">
            @auth
                <span class="navbar-text text-white me-3">{{ Auth::user()->name }}</span>
                <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Вход</a>
                <a href="{{ route('register') }}" class="btn btn-light btn-sm ms-2">Регистрация</a>
            @endauth
        </div>
    </div>
</nav>

<main class="container my-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @yield('content')
</main>
</body>
</html>