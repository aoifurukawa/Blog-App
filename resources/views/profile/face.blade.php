@extends('layouts.app')

@section('title', $user->name . ' - Posts')

@section('content')
<div class="container mt-4">
    <div class="row mt-2 mb-5">
        <div class="col-4">
                @if ($user->avatar)
                    <img src="{{asset('/storage/avatars/' . $user->avatar)}}" alt="{{$user->avatar}}" class="img-thumbnail w-100">
                @else
                    <i class="fa-solid fa-image fa-10x d-block text-center"></i>
                @endif
        </div>
        <div class="col-8">
                <h2 class="display-6">{{$user->name}}</h2>
        </div>
    </div>

    <h3>post history</h3>
    <hr>

    @forelse ($user->posts as $post)
        <div class="mt-3 border border-2 rounded p-4">
            <a href="{{ route('post.show', $post->id) }}">
                <h2 class="h4">{{$post->title}}</h2>
            </a>
            <p class="fw-light mb-0">{{$post->body}}</p>
            <small class="text-muted">Posted on {{$post->created_at->format('Y-m-d H:i:s')}}</small>
        </div>
    @empty
        <div class="text-center mt-5">
            <h2 class="text-muted">No posts yet</h2>
        </div>
    @endforelse
</div>
@endsection
