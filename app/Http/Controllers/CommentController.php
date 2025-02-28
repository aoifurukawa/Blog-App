<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    private $comment;
    public function __construct(Comment $comment){
        $this->comment = $comment;
    }

    public function store(Request $request, $post_id){
        $request->validate([
            'comment'=>'required|min:1|max:150',
        ]);

        $this->comment->user_id = Auth::user()->id;

        $this->comment->post_id = $post_id;

        $this->comment->body = $request->comment;

        $this->comment->save();

        return redirect()->back();
    }

    public function destroy($id){
        $comment = $this->comment->findOrFail($id);

        $comment->delete();

        return redirect()->back();
    }
}
