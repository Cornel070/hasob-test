<?php

namespace App\Http\Controllers;

use App\Http\Modules\User\Events\UserCreated;
use App\Http\Requests\UserFormRequest;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['res_type'=>'success', 'users'=>$users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        $file = $request->picture;
        $photo_name = time().$file->getClientOriginalName();

        $request->picture->move(public_path('assets/images/upload/users/'), $photo_name);

        $user = User::create(
            array_merge(
                $request->all(), 
                ['picture_url'=> asset('assets/images/upload/users').'/'.$photo_name]
            )
        );

        $token = auth()->login($user);

        //Trigger user created event
        event( new UserCreated($user) );
        
        return response()->json(['res_type'=>'success', 'message'=>'user created', 'user'=>$user, 'token'=>$token]);
    }

    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

         $token = auth()->attempt($credentials);

        if (!$token) {
            return response()->json(['res_type' => 'unauthorized', 'message'=>'Incorrect credentials'], 401);
        }

        return response()->json([
            'res_type'=>'success', 
            'user'=> auth()->user(), 
            'token'=>$token
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUser()
    {
        return response()->json(['res_type'=>'success', 'user'=>$this->user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {
        if ($request->picture) {
            $file = $request->picture;
            $photo_name = time().$file->getClientOriginalName();
            $request->picture->move(public_path('assets/images/upload/user/'), $photo_name);

            $this->user->update(
                array_merge(
                    $request->all(), 
                    ['picture_url'=>asset('assets/images/upload/user').'/'.$photo_name]
                )
            );
        }else{
            $this->user->update($request->all());
        }

        return response()->json(['res_type'=>'success', 'message'=>'User updated', 'user'=>$this->user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        auth()->logout($this->user);
        $this->user->delete();

        return response()->json(['deleted'=>true]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['res_type'=>'success','message' => 'Successfully logged out']);
    }
}
