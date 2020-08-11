<?php

namespace App\Http\Controllers;

use App\Employee;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Store;
use App\User;
use App\Outlet;
use App\Owner;
use Ramsey\Uuid\Uuid;
use Throwable;

class ManageStoreController extends BaseController
{
    // STORE
    // POST
    // Add Store First Time
    public function createNewStore(Request $request){
        $data = $this->validate($request,[
            'name_store' => 'required|min:2',
            // 'logo_store' => 'text'
            'address_outlet' => 'required',
            'phone_outlet' => 'required',
            'id_user' => 'required'
        ]);

        try{            
            $user = User::where('id_user',$data['id_user'])->first();
            if($user->status == 'activated'){
                $employee = Employee::where('id_user', $user->id_user)->first();
                $store = new Store;
                $store->id_store = substr($employee->id_employee,0,6);
                $store->name_store = $data['name_store'];
                // $store->logo_store = $data['logo_store'];
                
                $outlet = new Outlet;
                $outlet->id_outlet = "O".$store->id_store.(string)sprintf('%02u',1);
                $outlet->id_store = $store->id_store;
                $outlet->name_outlet = $store->name_store . ' (Pusat)';
                $outlet->address_outlet = $data['address_outlet'];
                $outlet->phone_outlet = $data['phone_outlet'];
                $outlet->is_active = true;
                
                $owner = new Owner;
                $owner->id_user = $user->id_user;
                $owner->id_store = $store->id_store;                
                $store->save();
                $outlet->save();                
                $owner->save();
                
            } else return response()->json([
                'success' => false,
                'message' => 'User invalid or unactivated!',
                'data' => ''
            ], 400);
        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Add Store Failed!',
                'data' => ''
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Add Store  Success!',
            'data' => $store . $outlet . $owner
        ], 200);
        
    }

    // GET
    // Get info store
    public function getInfoStore(Request $request){
        $id_store = $request->header('id_store');

        $store = Store::find($id_store);
        if($store){
            return response()->json([
                'success' => true,
                'message' => 'Get Store Info Success!',
                'data' => $store
            ], 201);
        } else{
            return response()->json([
                'success' => false,
                'message' => 'Get Store Info Failed!',
                'data' => ''
            ], 400);
        }
    }

    // OUTLET
    // POST
    //Add outlet
    public function addOutlet(Request $request){
        $id_outlet = str_replace('-','',Uuid::uuid4());
        $id_store = $request->input('id_store');
        $name_outlet = $request->input('name_outlet');
        $address_outlet = $request->input('address_outlet');
        $phone_outlet = $request->input('phone_outlet');

        if($id_store != null && $name_outlet != null && $address_outlet != null && $phone_outlet != null){
            
            $outlet = Outlet::create([
                'id_outlet' => $id_outlet,
                'id_store' => $id_store,
                'name_outlet' => $name_outlet,
                'address_outlet' => $address_outlet,
                'phone_outlet' => $phone_outlet,
            ]);
    
            if($outlet){
                return response()->json([
                    'success' => true,
                    'message' => 'Add outlet Success!',
                    'data' => $outlet
                ], 201);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Add Outlet Failed!',
                    'data' => ''
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data form is not completed',
                'data' => ''
            ], 400);
        }
        
    }

    // GET
    // Get Outlet by Store
    public function getOutletByIdStore(Request $request){
        $id_store = $request->header('id_store');
        
        if($id_store != null){
            $outlet = Outlet::where('id_store', $id_store)->get();

            if($outlet){
                return response()->json([
                    'success' => true,
                    'message' => 'Outlet Data Success Loaded!',
                    'data' => $outlet
                ], 201);
            } else{
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load outlet data!',
                    'data' => ''
                ], 400);
            }
        } 
    }

}
