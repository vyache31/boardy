@extends('layouts.app')

@section('title', 'Все посты')

@section('content')
    <div>
        @if(session()->has('access_token'))
            <span>Привет, {{ auth()->user()->name }}</span>
        @else
            <button id="login-btn" class="btn btn-secondary">Войти</button>
        @endauth
    </div>
    <h1>Все посты</h1>
    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Создать пост</a>

    <div id="posts-feed">
        @foreach($posts as $post)
            <article class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    </h5>
                    <p class="card-text">{{ $post->body }}</p>
                    <p class="card-text">
                        <small class="text-muted">Автор: {{ $post->author->name }} · {{ $post->created_at->diffForHumans() }}</small>
                    </p>
                </div>
            </article>
        @endforeach
    </div>

    {{ $posts->links() }}

    <script>
    	@if(app()->environment('production'))
        	const wsUrl = 'wss://{{ config("app.fastapi_domain") }}/ws';
    	@else
        	const isSecure = window.location.protocol === 'https:';
    		const wsUrl = isSecure 
        		? 'wss://{{ config("app.fastapi_domain") }}/ws'
        		: 'ws://localhost:8000/ws';
    	@endif

	const token = localStorage.getItem('access_token');
	const loginBtn = document.getElementById('login-btn');
	if (token && loginBtn) {
    		loginBtn.style.display = 'none';
	}
	if (loginBtn) {
    		loginBtn.addEventListener('click', () => {
        		import('/js/auth.js').then(module => module.startLogin());
    		});
        }

    	function connect() {
        	const ws = new WebSocket(wsUrl);
        	ws.onopen = () => console.log('WS connected');
        	ws.onmessage = (e) => {
            	const msg = JSON.parse(e.data);
            	if (msg.type === 'new_post') prependPost(msg.post);
        	};
        	ws.onclose = () => setTimeout(connect, 3000);
    	}

    	function prependPost(post) {
        	const feed = document.getElementById('posts-feed');
    		if (!feed) return;
    
   		const article = document.createElement('article');
    		article.className = 'card mb-3';
    		article.innerHTML = `
        		<div class="card-body">
            			<h5 class="card-title">
                		<a href="/posts/${post.id}">${escapeHtml(post.title)}</a>
            			</h5>
            			<p class="card-text">${escapeHtml(post.body)}</p>
            			<p class="card-text">
                			<small class="text-muted">Автор: ${escapeHtml(post.author)} · ${timeAgo(post.created_at)}</small>
            			</p>
        		</div>
   		 `;
    
    		feed.prepend(article);
    	}

    	function escapeHtml(str) {
       		const d = document.createElement('div');
        	d.textContent = str;
        	return d.innerHTML;
    	}

	function timeAgo(isoDate) {
    		const now = new Date();
    		const past = new Date(isoDate);
    		const diffMs = now - past;
    		const diffSec = Math.floor(diffMs / 1000);
    		const diffMin = Math.floor(diffSec / 60);
    		const diffHour = Math.floor(diffMin / 60);
    		const diffDay = Math.floor(diffHour / 24);

    		if (diffDay > 0) return `${diffDay} дн. назад`;
    		if (diffHour > 0) return `${diffHour} ч. назад`;
    		if (diffMin > 0) return `${diffMin} мин. назад`;
    		return 'только что';
	}

    	connect();
    </script>
@endsection