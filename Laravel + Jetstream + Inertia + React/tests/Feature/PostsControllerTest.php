<?php

namespace Tests\Feature\Http\Controllers;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostsControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ["*"]);

        $response = $this->get('/posts');

//        $response->assertInertia(fn (Assert $inertia) => $inertia
//            ->component('Post/Index')
//        );
    }
    public function test_create_post(){
        $user = User::factory()->create();
        Sanctum::actingAs($user, ["*"]);
        $response = $this->get('/posts/create');

//        $response->assertInertia(fn (Assert $inertia) => $inertia
//            ->component('Post/create')
//        );
    }

    public function test_store_post(){
        $this->refreshDatabase();
        $user = User::factory()->create();
        Sanctum::actingAs($user, ["*"]);
        $response = $this->postJson('/posts', [
            'title' => 'title',
            'body' => 'Body',
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'title',
            'body' => 'Body',
        ]);

        $response->assertRedirect('/posts');
//        $response->assertInertia(fn (Assert $inertia) => $inertia
//            ->component('Post/create')
//        );
    }

    public function test_update_post(){
        $user = User::factory()->create();
        Sanctum::actingAs($user, ["*"]);
        $this->postJson('/posts', [
            'title' => 'title',
            'body' => 'Body',
        ]);
        $postData = DB::table('posts')->where('title', '=', 'title')->where('body', '=', 'Body')->first();

        $response = $this->putJson('/posts/' . $postData->id, [
            'title' => 'title1',
            'body' => 'body1',
        ]);


        $this->assertDatabaseHas('posts', [
            'id' => $postData->id,
            'title' => 'title1',
            'body' => 'body1',
        ]);

        $response->assertRedirect('/posts');
//        $response->assertInertia(fn (Assert $inertia) => $inertia
//            ->component('Post/create')
//        );
    }
    public function test_delete_post(){

        $user = User::factory()->create();
        Sanctum::actingAs($user, ["*"]);
        $this->postJson('/posts', [
            'title' => 'title',
            'body' => 'Body',
        ]);

        $postData = DB::table('posts')->where('title', '=', 'title')->where('body', '=', 'Body')->first();

        $this->delete('/posts/' . $postData->id);
        $this->assertDatabaseMissing('posts', [
            'title' => 'title1',
            'body' => 'body1',
        ]);
    }
}
