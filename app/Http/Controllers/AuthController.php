<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Store;
use App\Outlet;
use App\Employee;
use App\Owner;
use App\LoginSessions;
use App\RegisterToken;
use Dotenv\Result\Success;
use Ramsey\Uuid\Uuid;
use Throwable;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $data = $this->validate($request,[
            'username' => 'required',
            'password_user' => 'required'
        ]);
        try{
            $user = User::where('username',$data['username'])->first();
            if($user != null){
                $employee = Employee::where('id_user',$user->id_user)->first();
                $outlet = Outlet::where('id_outlet',$employee->id_outlet)->first();
                if($employee->status == "hired"){
                    if($outlet == null){
                        $employee->id_store = Owner::where('id_user',$employee->id_user)->first()->id_store;
                    }else{
                        $employee->id_store = $outlet->id_store;
                    }
                    if (Hash::check($data['password_user'], $user->password_user)) {                    
                        $loginSession = LoginSessions::where('id_user',$user->id_user)->first();
                        if($loginSession == null){
                            $loginSession = new LoginSessions();
                            $loginSession->id_session = str_replace('-', '', Uuid::uuid4());
                            $loginSession->id_user = $user->id_user;
                            $loginSession->id_employee = $employee->id_employee;
                            $loginSession->is_active = true;
                            $loginSession->save();
                        } else {
                            $loginSession->is_active = true;
                            $loginSession->save();
                        }
                        $employee->id_session = $loginSession->id_session;
                        return response()->json([
                            'success' => true,
                            'message' => 'Login Success',
                            'data' => $employee
                        ], 201);
                    }
                }                
            }
            return response()->json([
                'success' => false,
                'message' => 'Email or Password Wrong',
                'data' => ''
            ], 400);            
           
        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Login Error',
                'data' => ''
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $data = $this->validate($request,[
            'id_session' => 'required'
        ]);
        try{
            $loginSession = LoginSessions::where('id_session',$data['id_session'])->first();
            if($loginSession != null){
                $loginSession->is_active = false;
                $loginSession->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Login Success',
                    'data' => ''
                ], 400);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found',
                    'data' => ''
                ], 400);
            }
        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Logout Error',
                'data' => ''
            ], 400);
        }
    }

    public function register(Request $request)
    {
        $data = $this->validate($request,[
            'name_user' => 'required|min:2',
            'username' => 'required|min:8',
            'email_user' => 'required|email',
            'password_user' => 'required',
            'phone_user' => 'required|numeric|min:8',
        ]);

        $user = new User;
        $employee = new Employee;

        $user->id_user =  $this->generateIdUser();
        $user->name_user = $data['name_user'];
        $user->username = $data['username'];
        $user->email_user = $data['email_user'];
        $user->password_user = Hash::make($data['password_user']);
        $user->phone_user = $data['phone_user'];
        $user->status = 'unactivated';

        $employee->id_employee = $this->generateIdEmployee();
        $employee->id_outlet = null;
        $employee->id_user = $user->id_user;
        $employee->name_employee = $user->name_user;
        $employee->role = 'admin';
        $employee->status = 'hired';

        if ($user != null) {
            $registerToken = new RegisterToken;

            $registerToken->id_token = str_replace('-', '', Uuid::uuid4());
            $registerToken->id_user = $user->id_user;
            $registerToken->is_active = true;
            
            if ($registerToken != null) {
                
                $user->save();
                $registerToken->save();
                $employee->save();
                
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

    public function validationToken(Request $request)
    {
        $id_token = $request->input('token');
        $id_user = $request->input('id');

        try {
            $data = RegisterToken::where('id_token',$id_token)->first();
            if($data->is_active == true && $id_user == $data->id_user){
                $user = User::where('id_user',$data->id_user)->first();
                $user->status = 'activated';
                $data->is_active = false;
                $user->save();
                $data->save();
            }
        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Token not valid',
                'data' => ''
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Validation Success',
            'data' => ''
        ], 200);
    }

    public function generateIdUser(){
        $year = (string)date("y");
        $usercount = (string)sprintf('%04u',User::count()+1);
        return "ID"."$year"."$usercount";
    }

    public function generateIdEmployee(){
        $year = (string)date("y");
        $storecount = (string)sprintf('%03u',Store::count()+1);
        $usercount = (string)sprintf('%03u',1);
        return "S"."$year"."$storecount"."$usercount";
    }
}
