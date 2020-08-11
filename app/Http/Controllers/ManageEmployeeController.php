<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Store;
use App\Employee;
use App\RegisterToken;
use Ramsey\Uuid\Uuid;
use Throwable;

class ManageEmployeeController extends BaseController
{
    //GET
    // Ambil Data Seluruh Pegawai dari Outlet tertentu
    public function getAllEmployeeByOutlet(Request $request){
        $id_outlet = $request->get('id_outlet');

        $employee = Employee::where('id_outlet', $id_outlet)->get();

        if(!empty($employee)){
            return response()->json([
                'success' => true,
                'message' => 'User Data by outlet Success to load',
                'data' => $employee
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => 'Get user data failed or no user data on outlet',
                'data' => ''
            ], 400);
        }
    }
    // Ambil Data Seluruh Pegawai dari toko tertentu
    public function getAllEmployeeByStore(Request $request){
        $id_store = $request->header('id_store');

        $user = User::where('id_owner', $id_store)->get();

        if(!empty($user)){
            return response()->json([
                'success' => true,
                'message' => 'User Data by owner Success to load',
                'data' => $user
            ], 201);
        } else{
            return response()->json([
                'success' => false,
                'message' => 'Get user data failed or no user data on owner',
                'data' => ''
            ], 400);
        }
    }
    // Ambil data pegawai dari id_user
    public function getUser(Request $request){
        $id_user = $request->header('id_user');

        $user = Employee::where('id_user', $id_user)->get();

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
    public function addEmployee(Request $request){        
        $data = $this->validate($request,[
            'name_user' => 'required|min:2',
            'username' => 'required|min:8',
            'email_user' => 'required|email',
            'password_user' => 'required',
            'phone_user' => 'required|numeric|min:8',
            'id_outlet' => 'required',
            'id_store' => 'required',
            'role' => 'required'
        ]);

        $employee = new Employee;
        $user = new User;
        
        try {
            $year = (string)date("y");
            $usercount = (string)sprintf('%04u',User::count()+1);

            $user->id_user = "ID"."$year"."$usercount";
            $user->name_user = $data['name_user'];
            $user->username = $data['username'];
            $user->email_user = $data['email_user'];
            $user->password_user = Hash::make($data['password_user']);
            $user->phone_user = $data['phone_user'];
            $user->status = 'unactivated';


            $year = (string)date("y");
            $storecount = (string)sprintf('%03u',Store::where("id_store",$data["id_store"])->count());
            $usercount = (string)sprintf('%03u',$storecount+1);
            
            $employee->id_employee = str_replace('-', '', "S"."$year"."$storecount"."$usercount");
            $employee->id_outlet = $data['id_outlet'];
            $employee->id_user = $user->id_user;
            $employee->name_employee = $user->name_user;
            $employee->role = $data['role'];
            $employee->status = 'hired';

            $registerToken = new RegisterToken;
            $registerToken->id_token = str_replace('-', '', Uuid::uuid4());
            $registerToken->id_user = $user->id_user;
            $registerToken->is_active = true;

            $user->save();
            $employee->save();
            $registerToken->save();
            
            app('App\Http\Controllers\EmailController')->sendRegisterEmail($user,$registerToken->id_token);

        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Add Employee Failed',
                'data' => ''
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Add Employee Success!',
            'data' => [$registerToken,$user, $employee]
        ], 201);
    }

    //Update Employee
    public function updateEmployee(Request $request){        
        $data = $this->validate($request,[
            'name_user' => 'required|min:2',
            'username' => 'required|min:8',
            'email_user' => 'required|email',
            'password_user' => 'required',
            'phone_user' => 'required|numeric|min:8',
            'id_outlet' => 'required',
            'id_store' => 'required',
            'role' => 'required'
        ]);

        $employee = new Employee;
        $user = new User;
        
        try {
            $user = User::where("id_user", $data["id_user"])->first();
            $user->name_user = $data['name_user'];
            $user->username = $data['username'];
            $user->email_user = $data['email_user'];
            $user->password_user = Hash::make($data['password_user']);
            $user->phone_user = $data['phone_user'];
            $user->status = 'unactivated';

            $employee = Employee::where("id_employee", $data["id_employee"])->first();
            $employee->id_outlet = $data['id_outlet'];
            $employee->id_user = $user->id_user;
            $employee->name_employee = $user->name_user;
            $employee->role = $data['role'];
            $employee->status = 'hired';
                        
            $user->save();
            $employee->save();

        } catch(Throwable $e){
            print($e);
            return response()->json([
                'success' => false,
                'message' => 'Update Employee Failed ',
                'data' => ''
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Update Employee Success!',
            'data' => [$user, $employee]
        ], 201);   
    }

}
