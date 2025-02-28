<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{

    const LOCAL_STORAGE_FOLDER = 'images/';
    private $post;


    public function __construct(Post $post){
        $this->post = $post;
    }

    public function index()
    {
        $all_posts = $this->post->latest()->get();

        return view('posts.index')
                ->with('all_posts', $all_posts);
    }

    // display the create page from the post folder
    public function create(){
        return view('posts.create');
    }

    public function store(Request $request){

        // validate data
        $request->validate([
            'title'=>'required|max:50',
            'body'=>'required|max:1000',
            'image'=>'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        // save the form data to the db
        $this->post->user_id = Auth::user()->id;

        // owner of the post    id of the logged in user
        $this->post->title   =   $request->title;
        $this->post->body    =   $request->body;
        $this->post->image   =   $this->saveImage($request);
        $this->post->save();

        #3 Back to homepage
        return redirect()->route('index');
    }

    public function saveImage($request){

        // change the name of the image to CURRENT YIME to avoid overwriting
        $image_name = time() . "." . $request->image->extension();

        // save the image inside storage/app/public/images
        $request->image->storeAs(self::LOCAL_STORAGE_FOLDER, $image_name);


        return $image_name;
    }

    public function show($id){
        $post = $this->post->findOrFail($id);

        return view('posts.show')
                ->with('post', $post);
    }

    public function edit($id){
        $post = $this->post->findOrFail($id);
        if($post->user->id != Auth::user()->id){
            return redirect()->back();
        }
        return view('posts.edit')->with('post', $post);
    }

    public function update(Request $request, $id){

        // validate data
        $request->validate([
            'title'=>'required|max:50',
            'body'=>'required|max:1000',
            'image'=>'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        // save the form data to the db
        $post                = $this->post->findOrFail($id);
        $post->title         =   $request->title;
        $post->body          =   $request->body;

        if($request->image){
            // delete the previous image
            $this->deleteImage($post->image);

            // move to image to public storage
            $post->image = $this->saveImage($request);
        }
        
        $post->save();

        #3 Back to homepage
        return redirect()->route('post.show', $id);
    }

    private function deleteImage($image_name){
        $image_path = self::LOCAL_STORAGE_FOLDER . $image_name;
        // $image_path = "/public/imgaes/13234454.jpg"

        // check if the imgae exsists then delete it
        if(Storage::disk('public')->exists($image_path)){
            Storage::disk('public')->delete($image_path);
        }
    }

    public function destroy($id){
        $post = $this->post->findOrFail($id);

        $this->deleteImage($post->image);

        $post->delete();
        return redirect()->route('index');
    }
}
