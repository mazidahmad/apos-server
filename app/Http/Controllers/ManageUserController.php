<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\RegisterToken;
use App\User;
use Ramsey\Uuid\Uuid;

class ManageUserController extends BaseController
{
    //GET
    // Ambil data dari id user
    public function getUser(Request $request){
        $id_user = $request->header('id_user');

        $user = User::where('id_user', $id_user)->get();

        if(!empty($user)){
            return response()->json([
                'success' => true,
                'message' => 'User found',
                'data' => $user
            ], 201);
        } else{
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => ''
            ], 400);
        }
    }

    // POST
    // Tambah data user
    public function addUser(Request $request){
        $data = $this->validate($request,[
            'name_user' => 'required|min:2',
            'username' => 'required|min:8',
            'email_user' => 'required|email',
            'password_user' => 'required',
            'phone_user' => 'required|numeric|min:8',
        ]);

        $user = new User;

        $user->id_user = str_replace('-', '', Uuid::uuid4());
        $user->name_user = $data['name_user'];
        $user->username = $data['username'];
        $user->email_user = $data['email_user'];
        $user->password_user = Hash::make($data['password_user']);
        $user->phone_user = $data['phone_user'];
        $user->status = 'unactivated';

        if ($user != null) {
            $registerToken = new RegisterToken;

            $registerToken->id_token = str_replace('-', '', Uuid::uuid4());
            $registerToken->id_user = $user->id_user;
            $registerToken->is_active = true;
            
            if ($registerToken != null) {
                
                $user->save();
                $registerToken->save();
                
                app('App\Http\Controllers\EmailController')->sendRegisterEmail($user,$registerToken->id_token);

                return response()->json([
                    'success' => true,
                    'message' => 'Register Success!',
                    'data' => [$registerToken,$user]
                ], 201);
            }

            return response()->json([
                'success' => true,
                'message' => 'Create register token failed!',
                'data' => ''
            ], 201);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Register User failed!',
                'data' => ''
            ], 201);
        }
    }

}
