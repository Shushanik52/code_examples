<?php

namespace App\Actions\Posts;

use App\Models\Post;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePostPage
{
    use AsAction;

    public function handle()
    {
        return Inertia::render('Post/Create');
    }
}
