<?php

namespace App\Actions\Posts;

use App\Models\Post;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class EditPostPage
{
    use AsAction;

    public function handle($post)
    {

        return Inertia::render('Post/Edit', [
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body
            ]
        ]);
    }
}
