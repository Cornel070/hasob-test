<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\Concerns\ImpersonatesUsers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\UserFormRequest as Request;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Http\Requests\UpdateUserRequest as UpdateRequest;
use App\Http\Controllers\UserController;
use App\User;

class UserTest extends TestCase
{
    use WithoutMiddleware, WithFaker;
    /**
     * @test
     * 
     * @return void
     */
    public function it_can_create_an_user()
    {
        Storage::fake('images');
        $userImg = UploadedFile::fake()->image('asset.jpg');
        $userData = [
            'first_name' => $this->faker->name,
            'middle_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'email'  => $this->faker->email,
            'phone'  => '08022899755',
            'picture' => $userImg,
            'password'  => 'Godsplan22@',
            'password_confirmation' =>'Godsplan22@',
        ];

        $request = new Request($userData);
      
        $userCreator = new UserController();
        $response = $userCreator->store($request);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($data['res_type'], 'success');
        $this->assertEquals($data['user']['email'], $userData['email']);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_show_the_user()
    {
        $createdUser = $this->createLoginUser();
        $userCreator = new UserController();

        $showRes = $userCreator->showUser();
        $showData = json_decode($showRes->getContent(), true);

        $this->assertEquals($showData['res_type'], 'success');
        $this->assertEquals($showData['user']['id'], $createdUser->id);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_update_user()
    {
        $createdUser = $this->createLoginUser();
      
        $userCreator = new UserController();

        $update = ['phone'=>'09088865433'];
        $updateRequest = new UpdateRequest($update);
        $updateRes = $userCreator->update($updateRequest, $createdUser->id);
        $updatedData = json_decode($updateRes->getContent(), true);

        $this->assertEquals($updatedData['user']['id'], $createdUser->id);
        $this->assertEquals($updatedData['user']['phone'], $createdUser->phone);
    }

    /**
     * @test
     * 
     * @return void
     */
    public function it_can_delete_user()
    {
        $createdUser = $this->createLoginUser();
      
        $userCreator = new UserController();

        $delRes = $userCreator->destroy($createdUser->id);
        $delData = json_decode($delRes->getContent(), true);

        $this->assertEquals($delData['deleted'], true);
    }

    public function it_can_login_user()
    {
        $userCreator = new UserController();
        $createdUser = $this->createLoginUser(false);

        $loginRes = $userCreator->login([
            'email'=>$createdUser->email,
            'password'=>$createdUser->password
        ]);

        $loginData = json_decode($loginRes->getContent(), true);

        $this->assertEquals($loginData['res_type'], 'success');
        $this->assertAuthenticated();
    }

    public function it_can_logout_user()
    {
        $userCreator = new UserController();
        $createdUser = $this->createLoginUser();

        $logoutRes = $userCreator->logout();
        
        $logoutData = json_decode($logoutRes->getContent(), true);

        $this->assertEquals($logoutData['res_type'], 'success');
    }

    private function createLoginUser($login = true)
    {
        $user = User::create([
            'first_name' => $this->faker->name,
            'middle_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'email'  => $this->faker->email,
            'phone'  => '08022899755',
            'picture_url' => 'http://hasob.test/assets/images/upload1624394551Me.JPG',
            'password'  => 'Godsplan22@',
            'password_confirmation' =>'Godsplan22@',
        ]);

        if ($login) {
            auth()->login($user);
        }

        return $user;
    }
}
