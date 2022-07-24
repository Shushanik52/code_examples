<?php

namespace App\Actions\Posts;

use App\Models\Post;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class PostPage
{
    use AsAction;

    public function handle()
    {
        $posts = Post::latest()->get();
        return Inertia::render('Post/Index', ['posts' => $posts]);
    }
}
