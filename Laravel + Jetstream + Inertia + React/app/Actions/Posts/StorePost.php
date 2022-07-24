<?php

namespace App\Actions\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class StorePost
{
    use AsAction;

    public function handle(array $input)
    {
        Post::create([
            'title' => $input['title'],
            'body' => $input['body'],
        ]);
        return Redirect::route('posts.index');
    }
}
