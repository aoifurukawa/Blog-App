@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
    <form action="{{route('post.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label text-secondary">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{old('title')}}" placeholder="Enter title here" autofocus >
            @error('title')
                <p class="text-danger-small">{{$message}}</p>
            @enderror
        </div>

        <div class="mb-3">
        <label for="body" class="form-label text-secondary">Body</label>
        <textarea name="body" id="body" rows="5" class="form-control" value="{{old('body')}}" placeholder="Start writing ..." ></textarea>
        @error('body')
            <p class="text-danger-small">{{$message}}</p>
        @enderror
        </div>

        <div class="mb-3">
        <label for="image" class="form-label text-secondary">Image</label>
        <input type="file" name="image" class="form-control" id="image"  aria-describedby="image-info" >
            <div class="form-text" id="image-info">
                Acceptable forms jpeg, jpg, png, gif only. <br>
                Maximam file size is 1048kB.
            </div>
            @error('image')
                <p class="text-danger-small">{{$message}}</p>
            @enderror
        </div>
    
        <button type="submit" class="btn btn-primary px-5">Post</button>
    </form>
@endsection