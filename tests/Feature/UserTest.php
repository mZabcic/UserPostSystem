<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\User;
use JWTAuth;

class UserTest extends TestCase
{

    use RefreshDatabase;

    private $token;
    private $user;

    public function setUp() {
        parent::setUp();

        $user = factory(User::class)->create();
   
        $this->user = $user;
        $this->token = JWTAuth::fromUser($user);

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);
    }


    /**
     * A Test register
     *
     * @return void
     */
    public function testRegister()
    {
        $response = $this->json('POST', '/register', 
                [
                'first_name' => "test",
                'last_name' => "test",
                'email' => "testiranje@testiranje.test",
                'date_of_birth' => "1993-05-12",
                'password' => 'secret'
                ]);
        $data = json_decode($response->getContent());
        $user_id = $data->user->id;
        $token = $data->token;

        $response
            ->assertStatus(201)
            ->assertJson([
                'user' => true,
                'token' => true
            ]);

        $this->assertDatabaseHas('users', [
            'email' => "testiranje@testiranje.test"
        ]);
    }

     /**
     * A Test create
     *
     * @return void
     */
    public function testCreate()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])->json('POST', '/users', 
                [
                'first_name' => "test",
                'last_name' => "test",
                'email' => "testiranje@testiranje.test",
                'date_of_birth' => "1993-05-12",
                'password' => 'secret'
                ]);
                
        $data = json_decode($response->getContent());
        $user_id = $data->user->id;
        $token = $data->token;

        $response
            ->assertStatus(201)
            ->assertJson([
                'user' => true,
                'token' => true
            ]);

        $this->assertDatabaseHas('users', [
            'email' => "testiranje@testiranje.test"
        ]);
    }

      /**
     * A Test login
     *
     * @return void
     */
    public function testLogin()
    {

        $response = $this->withHeaders([
            'Authorization' => 'Bearer test',
            ])
            ->json('GET', '/users/me', []);

        $response
            ->assertStatus(401)
            ->assertJson([
                'error' => true
            ]);

        $response = $this->json('POST', '/login', 
                [
                'email' => $this->user->email,
                'password' => "secret"
                ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'user' => true,
                'token' => true
            ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])
            ->json('GET', '/users/me', []);

        $response
            ->assertStatus(200)
            ->assertJson([
                'user' => true
            ]);
       
    }


     /**
     * A Test get all users
     *
     * @return void
     */
    public function testGetUsers()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])->json('GET', '/users');
    
        $response
            ->assertStatus(200);
    }


        /**
     * A Test get all users
     *
     * @return void
     */
   public function testGetUserById()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])->json('GET', '/users/0');
    
        $response
            ->assertStatus(404)
            ->assertJson([
                'error' => true
            ]);

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                ])->json('GET', '/users/' . $this->user->id);
        
        $response
                ->assertStatus(200);
    } 



        /**
     * A Test get current user
     *
     * @return void
     */
   public function testMe()
   {
       $response = $this->withHeaders([
           'Authorization' => 'Bearer ' . $this->token,
           ])->json('GET', '/users/me');
   
       $response
           ->assertStatus(200)
           ->assertJson([
               'user' => true
           ]);

   } 


      /**
     * A Test get update user
     *
     * @return void
     */
    public function testUpdate()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-type' => 'application/x-www-form-urlencoded'
            ])->json('PUT', '/users/' . $this->user->id, 
            [
            'first_name' => "test123",
            'last_name' => "test123"
            ]);
    
        $response
            ->assertStatus(200);


        $user = $user = User::where('id', '=', $this->user->id)->firstOrFail();
        $this->assertArraySubset(['first_name' => 'test123', 'last_name' => 'test123'], $user->toArray());
                
    } 


    


      /**
     * A Test get update user
     *
     * @return void
     */
    public function testDelete()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            ])->json('DELETE', '/users/' . $this->user->id);
    
        $response
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', $this->user->toArray());        
    } 


}
