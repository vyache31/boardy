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


    <div id="comments-root" 
         data-post-id="{{ $post->id }}" 
         data-user-name="{{ auth()->user()->name ?? '' }}" 
         data-user-id="{{ auth()->user()->id ?? 0 }}">
     </div>
    @vite('resources/js/comments.jsx')    
@endsection
