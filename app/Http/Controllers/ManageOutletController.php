<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Outlet;
use Ramsey\Uuid\Uuid;
use Throwable;

class ManageOutletController extends BaseController
{
    // OUTLET
    // POST
    // Add Outlet
    public function createNewOutlet(Request $request){
        $data = $this->validate($request,[
            'id_store' => 'required',
            'name_outlet' => 'required|min:2',
            'address_outlet' => 'nullable',
            'phone_outlet' => 'nullable'
        ]);

        try{            
                $outlet = new Outlet;
                $outletCount = Outlet::where("id_store",$data["id_store"])->count();
                $outlet->id_outlet = "O".$data['id_store'].(string)sprintf('%02u',$outletCount+1);
                $outlet->id_store = $data['id_store'];
                $outlet->name_outlet = $data['name_outlet'];
                $outlet->address_outlet = $data['address_outlet'];
                $outlet->phone_outlet = $data['phone_outlet'];
                $outlet->is_active = true;

                $outlet->save();                
                
        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Add Outlet Failed!',
                'data' => ''
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Add Outlet  Success!',
            'data' => $outlet
        ], 201);
        
    }

    //Update Outlet
    public function updateOutlet(Request $request){        
        $data = $this->validate($request,[
            'id_store' => 'required',
            'name_outlet' => 'required|min:2',
            'address_outlet' => 'nullable',
            'phone_outlet' => 'nullable'
        ]);

        $outlet = new Outlet;
        
        try {
            $outlet = Outlet::where("id_outlet", $data["id_outlet"])->first();
            $outlet->id_store = $data['id_store'];
            $outlet->name_outlet = $data['name_outlet'];
            $outlet->address_outlet = $data['address_outlet'];
            $outlet->phone_outlet = $data['phone_outlet'];
            $outlet->is_active = true;

            $outlet->save();     

        } catch(Throwable $e){
            print($e);
            return response()->json([
                'success' => false,
                'message' => 'Update Outlet Failed ',
                'data' => ''
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Update Outlet Success!',
            'data' => $outlet
        ], 201);   
    }

    // GET
    // Get info store
    public function getInfoOutlet(Request $request){
        $id_outlet = $request->get('id_outlet');

        try{
            $outlet = Outlet::find($id_outlet);

            return response()->json([
                'success' => true,
                'message' => 'Get Store Info Success!',
                'data' => $outlet
            ], 200);
        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Get Store Info Failed!',
                'data' => ''
            ], 400);
        }        
    }

    // Get Outlet by Store
    public function getOutletByIdStore(Request $request){
        $id_store = $request->get('id_store');
        try{
            $outlet = Outlet::where('id_store', $id_store)->get();
            return response()->json([
                'success' => true,
                'message' => 'Outlet Data Success Loaded!',
                'data' => $outlet
            ], 200);

        }catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Outlet Data Not Found',
                'data' => ''
            ], 404);
        } 
    }

    // Get id and name outlet by store
    public function getIdNameOutletByIdStore(Request $request){
        $id_store = $request->get('id_store');
        try{
            $outlet = Outlet::where('id_store', $id_store)->get(['id_outlet','name_outlet']);
            return response()->json([
                'success' => true,
                'message' => 'Outlet Data Success Loaded!',
                'data' => $outlet
            ], 200);

        }catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Outlet Data Not Found',
                'data' => ''
            ], 404);
        } 
    }
}
