<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Подтверждение доступа</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; max-width: 600px; margin: 0 auto; }
        .card { border: 1px solid #ccc; border-radius: 8px; padding: 1.5rem; margin-top: 1rem; }
        button { background: #0d6efd; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; }
        .btn-danger { background: #dc3545; margin-left: 1rem; }
    </style>
</head>
<body>
    <h1>Авторизация</h1>
    <div class="card">
        <p><strong>Клиент:</strong> {{ $client->name ?? 'Неизвестное приложение' }}</p>
        <p>Запрашивает доступ к вашему аккаунту.</p>
        @if (count($scopes) > 0)
            <p><strong>Запрашиваемые права:</strong></p>
            <ul>
                @foreach ($scopes as $scope)
                    <li>{{ $scope->description ?? $scope->id }}</li>
                @endforeach
            </ul>
        @endif
        <form method="post" action="{{ route('passport.authorizations.approve') }}">
            @csrf
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit">Разрешить</button>
        </form>
        <form method="post" action="{{ route('passport.authorizations.deny') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="state" value="{{ $request->state }}">
            <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
            <input type="hidden" name="auth_token" value="{{ $authToken }}">
            <button type="submit" class="btn-danger">Отказать</button>
        </form>
    </div>
</body>
</html>
