<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Posts\CreatePostsRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Post;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index')->with('posts',Post::all());

        // $employees = Employee::all();
        // return view('employeeform')->with('employees', $employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          

           $request->validate([
            'title' => 'required|unique:posts',
           'description' => 'required',
           'image' => 'required|image',
           'content' => 'required'
           ]);

       $post = new Post();

       $post->title = $request->input('title');
       $post->description = $request->input('description');
       $post->content = $request->input('content');
       $post->published_at = $request->input('published_at');

       if($request->hasfile('image')){
           $file = $request->file('image');
           $extension = $file->getClientOriginalExtension();
           $filename = time() .'.'. $extension;
           $file->move('uploads/blog_image/', $filename);
           $post->image = $filename;
       }else{
           return $request;
           $post->image = '';
       }
       $post->save();

       //return view('employee')->with('employee',$post);
       // flash message
       session()->flash('success','Post created successfully.');
       // redirect user
       return redirect(route('posts.index'));


        // Post::create([
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'content' => $request->content,
        //     'image' => $image
        // ]);
        // flash message
        // session()->flash('success','Post created successfully.');
        // redirect user
        //return redirect(route('posts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.create')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $post->title = $request->input('title');
       $post->description = $request->input('description');
       $post->content = $request->input('content');
       $post->published_at = $request->input('published_at');
       
       if($request->hasfile('image')){
        Storage::delete(unlink(public_path('uploads/blog_image/') .$post->image));
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() .'.'. $extension;
            $file->move('uploads/blog_image/', $filename);
            $post->image = $filename;

            } 

            $post->save();

            session()->flash('success','Post updated successfully.');

            return redirect(route('posts.index'));
        }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::withTrashed()->where('id',$id)->firstOrFail();
        if($post->trashed()){
            //  delete image from the server
           // unlink(public_path('uploads/blog_image/') .$post->image);
            // deleete image from the database
            //Storage::delete($post->image);
            Storage::delete(unlink(public_path('uploads/blog_image/') .$post->image));
            $post->forceDelete();
        }else{
            $post->delete();
        }

        session()->flash('success','Post Trashed successfully.');

        return redirect(route('posts.index'));
    }
   
    // Displaying a list of all trashed posts
    public function trashed()
    {
      $trashed = Post::onlyTrashed()->get();

      return view('posts.index')->withPosts($trashed);
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->where('id',$id)->firstOrFail();    
        
        $post->restore();
        session()->flash('success', 'Post restore successfully.');
        return redirect()->back();
    }
 

}

