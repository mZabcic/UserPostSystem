<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;
use Tests\TestCase;

class PostTest extends TestCase
{

    use RefreshDatabase;

    private $token;
    private $user;
    private $post;

    public function setUp()
    {
        parent::setUp();

        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->user = $user;
        $this->post = $post;
        $this->token = JWTAuth::fromUser($user);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
        ]);
    }

    /**
     * A Test create
     *
     * @return void
     */
    public function createPost()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('POST', '/posts',
            [
                'title' => "test",
                'content' => "test",
            ]);

        $response
            ->assertStatus(201);

        $this->assertDatabaseHas('posts', [
            'title' => "test",
        ]);
    }

    /**
     * A Test get all post
     *
     * @return void
     */
    public function testGetPost()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('GET', '/posts');

        $response
            ->assertStatus(200);
    }

    /**
     * A Test get all post
     *
     * @return void
     */
    public function testGetPostById()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('GET', '/posts/0');

        $response
            ->assertStatus(404)
            ->assertJson([
                'error' => true,
            ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('GET', '/posts/' . $this->post->id);

        $response
            ->assertStatus(200);
    }

    /**
     * A Test get all post
     *
     * @return void
     */
    public function testGetPostByUsersId()
    {

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('GET', '/users/' . $this->post->user_id . '/posts');

        $data = json_decode($response->getContent());
        $response
            ->assertStatus(200);

        Post::create([
            'title' => "test1",
            'content' => "test1",
            'user_id' => $this->post->user_id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->json('GET', '/users/' . $this->post->user_id . '/posts');

        $data1 = json_decode($response->getContent());
        $response
            ->assertStatus(200);

        $this->assertTrue(sizeof($data1) > sizeof($data));
    }

    /**
     * A Test get update post
     *
     * @return void
     */
    public function testUpdatePost()
    {
        $user = User::where('id', '=', $this->post->user_id)->first();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-type' => 'application/x-www-form-urlencoded',
        ])->json('PUT', '/posts/' . $this->post->id,
            [
                'title' => "new_test",
                'content' => "Testni",
            ]);

        $response
            ->assertStatus(200);

        $post = Post::where('id', '=', $this->post->id)->firstOrFail();
        $this->assertArraySubset(['title' => 'new_test', 'content' => 'Testni'], $post->toArray());

    }

    /**
     * A Test get update post
     *
     * @return void
     */
    public function testDeletePost()
    {
        $user = User::where('id', '=', $this->post->user_id)->first();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('DELETE', '/posts/' . $this->post->id);

        $response
            ->assertStatus(204);

        $this->assertDatabaseMissing('posts', $this->post->toArray());
    }

}
