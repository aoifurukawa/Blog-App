@extends('layouts.app')

@section('title', 'Show Post')

@section('content')

    <div class="mt-2 border border-2 rounded p-4 shadow-sm">
        <h2 class="h4">{{$post->title}}</h2>
        <h3 class="h6 text-muted">{{$post->user->name}}</h3>
        <p>{{$post->body}}</p>

        <img src="{{ asset('/storage/images/'. $post->image)}}" alt="{{$post->image}}" class="w-100 shadow rounded">
    </div>

    <form action="{{route('comment.store', $post->id)}}" method="post">
        @csrf
        <div class="input-group mt-5">
            <input type="text" name="comment" class="form-control" value="{{old('comment')}}" placeholder="Add a comment ...">
            <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
        </div>

        @error('comment')
            <p class="text-danger small">{{$message}}</p>
        @enderror
    </form>

    <!-- show all comment -->
     @if ($post->comments)
        <div class="mt-2 p-2">
            @foreach ($post->comments as $comment)
                <div class="row p-2">
                    <div class="col-10">
                        <a href="{{route('profile.face', $post->user->id)}}" class="text-decoration-none"><span class="fw-bold">{{$comment->user->name}}</span></a>
                        &nbsp;
                        <span class="small text-muted">{{$comment->created_at}}</span>
                        <p class="mb-0">{{$comment->body}}</p>
                    </div>

                    <div class="col-2 text-end">
                        @if ($comment->user_id === Auth::user()->id)
                            <form action="{{route('comment.destroy', $comment->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="delete comment">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
     @endif

@endsection

