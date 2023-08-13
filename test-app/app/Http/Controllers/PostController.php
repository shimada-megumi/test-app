<?php

namespace App\Http\Controllers;

use App\Models\Nice;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $posts=Post::orderBy('created_at','desc')->get();
        $posts=Post::latest('created_at')->paginate(10);
        $user=auth()->user();
        
        return view('post.index', compact('posts', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('post.create');        
    }        
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs=$request->validate([
            'title'=>'required|max:255',
            'body'=>'required|max:1000',
            'image'=>'image|max:1024'
        ]);
        $post=new Post();
        $post->title=$request->title;
        $post->body=$request->body;
        $post->user_id=auth()->user()->id;
         if (request('image')){
            $original = request()->file('image')->getClientOriginalName();
            $name = date('Ymd_His').'_'.$original;
            request()->file('image')->move('storage/images', $name);
            $post->image = $name;
        }
        $post->save();
        return redirect()->route('post.create')->with('message','投稿を作成しました');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // いいね機能
        $nice=Nice::where('post_id', $post->id)->where('user_id', auth()->user()->id)->first();
        return view('post.show',compact('post', 'nice'));    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('post.edit', compact('post'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $inputs=$request->validate([
            'title'=>'required|max:255',
            'body'=>'required|max:1000',
            'image'=>'image|max:1024'
        ]);

       $post->title=$inputs['title'];
       $post->body=$inputs['body'];
                
        if(request('image')){
            $original=request()->file('image')->getClientOriginalName();
            $name=date('Ymd_His').'_'.$original;
            $file=request()->file('image')->move('storage/images', $name);
            $post->image=$name;
        }

        $post->save();

        return redirect()->route('post.show', $post)->with('message', '投稿を更新しました'); 
        
        $this->authorize('update', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // 投稿に紐づいた画像を削除
        if($post->image) {
            Storage::disk('public')->delete('images/'.$post->image);
        }
        // ここまで追加
        $this->authorize('delete', $post);
        $post->comments()->delete();
        $post->delete();
        return redirect()->route('post.index')->with('message', '投稿を削除しました');    
    }

    public function mypost() {
        $user=auth()->user()->id;
        // $posts=Post::where('user_id', $user)->orderBy('created_at', 'desc')->get();
        $posts=Post::where('user_id', $user)->latest('created_at')->paginate(10);
        return view('post.mypost', compact('posts'));
    }

    public function mycomment() {
        $user=auth()->user()->id;
        // $comments=Comment::where('user_id', $user)->orderBy('created_at', 'desc')->get();
        $comments=Comment::where('user_id', $user)->latest('created_at')->paginate(10);
        return view('post.mycomment', compact('comments'));
    }
}
