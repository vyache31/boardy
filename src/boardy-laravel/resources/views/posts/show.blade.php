@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <h1>{{ $post->title }}</h1>
            <p class="text-muted">{{ $post->author->name }} · {{ $post->created_at->format('d.m.Y H:i') }}</p>
            <p>{{ $post->body }}</p>

            @can('update', $post)
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Редактировать</a>
            @endcan
            @can('delete', $post)
                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить?')">Удалить</button>
                </form>
            @endcan
        </div>
    </div>

    <h3>Комментарии</h3>
    @forelse($post->comments as $comment)
        <div class="card mb-2">
            <div class="card-body">
                <p>{{ $comment->body }}</p>
                <small class="text-muted">{{ $comment->author->name }} · {{ $comment->created_at->diffForHumans() }}</small>
            </div>
        </div>
    @empty
        <p>Комментариев нет. Хочешь быть первым?</p>
    @endforelse

    @auth
        <div class="card mt-4">
            <div class="card-body">
                <h5>Добавить комментарий</h5>
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <div class="mb-3">
                        <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="3" required></textarea>
                        @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    @else
        <p class="mt-3"><a href="{{ route('login') }}">Чтобы комментировать - залогинься</p>
    @endauth
@endsection
