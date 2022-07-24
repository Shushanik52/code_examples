<?php

namespace App\Actions\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class DeletePost
{
    use AsAction;

    public function handle(int $id)
    {
        Post::where('id', $id)->first()?->delete();
        return Redirect::route('posts.index');
    }
}
