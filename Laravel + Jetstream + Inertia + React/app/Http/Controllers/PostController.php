<?php

namespace App\Http\Controllers;

use App\Actions\Posts\StorePost;
use App\Actions\Posts\DeletePost;
use App\Actions\Posts\EditPost;
use App\Actions\Posts\PostPage;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        return PostPage::run();
    }

    public function create()
    {
        return Inertia::render('Post/Create');
    }

    public function store(StorePostRequest $request)
    {
        StorePost::run($request->validated());
        return Redirect::route('posts.index');
    }

    public function edit(Post $post)
    {
        return Inertia::render('Post/Edit', [
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body
            ]
        ]);
    }

    public function update(StorePostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return Redirect::route('posts.index');


        return  EditPost::run($request->validated(), $post->id);
    }

    public function destroy(Post $post)
    {
        return DeletePost::run($post->id);
    }
}
