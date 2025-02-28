<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Storage;

class UserController extends Controller
{
    private $user;
    const LOCAL_STORAGE_FOLDER = 'avatars/';

    public function __construct(User $user){
        $this->user =$user;
    }

    public function show(){
        $user = $this->user->findOrFail(Auth::user()->id);

        return view('users.show')->with('user', $user);
    }

    public function edit(){
        $user = $this->user->findOrFail(Auth::user()->id);

        return view('users.edit')
                ->with('user', $user);
    }

    public function update(Request $request){
        $request->validate([
            'name'=>'required|max:50',
            'email'=>'required|max:50|unique:users,email,' . Auth::user()->id
        ]);

        $user                = $this->user->findOrFail(Auth::user()->id);
        $user->name         =   $request->name;
        $user->email          =   $request->email;
    
        

        if($request->avatar){
            if($user->avatar){
                $this->deleteImage($user->avatar);
            }

            // $this->deleteImage($user->avatar);
            $user->avatar = $this->saveImage($request);
        }

        $user->save();

        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function deleteImage($avatar_name){
        $avatar_path = self::LOCAL_STORAGE_FOLDER . $avatar_name;

        if(Storage::disk('public')->exists($avatar_path)){
            Storage::disk('public')->delete($avatar_path);
        }
    }

    public function saveImage($request){
        $avatar_name = time() . "." . $request->avatar->extension();

        // save the image inside storage/app/public/images
        $request->avatar->storeAs(self::LOCAL_STORAGE_FOLDER, $avatar_name);

        return $avatar_name;
    }

    public function face($userId)
{
    // ユーザーとその投稿を取得
    $user = User::with('posts')->findOrFail($userId);

    // ビューにユーザーとその投稿データを渡す
    return view('profile.face', compact('user'));
}

}
