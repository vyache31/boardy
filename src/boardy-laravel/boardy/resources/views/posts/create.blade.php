@extends('layouts.app')

@section('title', 'Новый пост')

@section('content')
    <h1>Новый пост</h1>
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Текст поста</label>
            <textarea name="body" id="body" rows="5" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
@endsection
