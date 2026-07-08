@extends('layouts.app')

@section('content')
    <div id="callback-content">Вход..</div>
    <script type="module">
        import { handleCallback } from '/js/auth.js';
        handleCallback().then(token => {
            if (token) {
                localStorage.setItem('access_token', token);
            } else {
                document.getElementById('callback-content').innerHTML = 'Ошибка входа';
            }
        }).catch(err => {
            console.error(err);
            document.getElementById('callback-content').innerHTML = 'Ошибка: ' + err.message;
        });
    </script>
@endsection