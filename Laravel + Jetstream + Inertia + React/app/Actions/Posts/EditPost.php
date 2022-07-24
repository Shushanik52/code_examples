<?php

namespace App\Actions\Posts;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class EditPost
{
    use AsAction;

    public function handle($input, int $id)
    {

        $post = Post::where('id', $id)->first();
        $post?->update($input);

        Inertia::render('Post/Edit', [
            'post' => [
                'id' => $id,
                'title' => $input['title'],
                'body' => $input['body']
            ]
        ]);
        return Redirect::route('posts.index');
    }
}
