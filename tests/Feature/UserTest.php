<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\ImpersonatesUsers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\User;

class UserTest extends TestCase
{
    use WithoutMiddleware, WithFaker;
    /**
     * 
     * 
     * A basic feature test example.
     *
     * @return void
     */

    public function testGetAllAssets()
    {
        $this->json('GET', 'api/user', ['Accept' => 'application/json'])
        ->assertStatus(200);
    }

    public function testSuccessfulUserCreation()
    {
        $email = Str::random(3).'shupel16@gmail.com';

        $userData = [
            'first_name' => 'Cornel',
            'middle_name' => 'Abang',
            'last_name' => 'Doe',
            'email'  => $email,
            'phone'  => '08022899755',
            'picture' => UploadedFile::fake()->create('test.png', $kilobytes = 0),
            'password'  => 'Godsplan22@',
            'password_confirmation' =>'Godsplan22@',
        ];

        $this->json('POST', 'api/user/create', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "res_type",
                'message',
                "user" => [
                    'first_name',
                    'middle_name',
                    'last_name',
                    'email',
                    'phone',
                    'picture_url',
                    'updated_at',
                    'created_at',
                    'id'
                ],
                'token'
            ]);
    }

    public function testShowUser()
    {
        $this->createLoginUser();
        $this->json('GET', 'api/user/me', ['Accept' => 'application/json'])
        ->assertStatus(200)
            ->assertJsonStructure([
                "res_type",
                "user" => [
                    'first_name',
                    'middle_name',
                    'last_name',
                    'email',
                    'phone',
                    'picture_url',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }


    public function testSuccessfulLogin()
    {
        $user = $this->createLoginUser(false);
        $loginData = ['email' => $user->email, 'password' => 'Godsplan22@'];

        $this->json('POST', 'api/user/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "res_type",
               "user" => [
                   'id',
                   'first_name',
                   'middle_name',
                   'last_name',
                   'email',
                   'phone',
                   'picture_url',
                   'is_disabled',
                   'created_at',
                   'updated_at',
               ],
               "token"
            ]);

        $this->assertAuthenticated();
    }

    public function testUpdateUser()
    {
        $this->createLoginUser();
        $data = ['email'=> $this->faker->email];
        $this->json('POST', 'api/user/update', $data, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonPath('res_type','success')
        ->assertJsonPath('user.email', $data['email']);
    }

    public function testDeleteUser()
    {
        $user = $this->createLoginUser(false);
        auth()->login($user);
        $this->json('DELETE', 'api/user/'.$user->id, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonPath('deleted',true);
    }

    public function testLogoutUser()
    {
        $this->createLoginUser();
        $this->json('GET', 'api/user/logout', ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonPath('res_type','success');
    }

    private function createLoginUser($login = true)
    {
        $str = Str::random(3);

        $user = new User;
        $user->first_name = 'John';
        $user->last_name = 'Doe';
        $user->middle_name = 'Tan';
        $user->email = 'ekupnse16@gmail.com'.$str;
        $user->phone = '09088875666';
        $user->picture_url = 'http://hasob.test/assets/images/upload1624394551Me.JPG';
        $user->password = 'Godsplan22@';
        $user->save();

        if ($login) {
            auth()->login($user);
            return true;
        }
        return $user;
    }
}
