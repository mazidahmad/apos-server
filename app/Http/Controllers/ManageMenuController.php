<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Menu;
use App\OutletMenu;
use Throwable;

class ManageMenuController extends BaseController
{
    //GET
    // Ambil Data Seluruh Pegawai dari Outlet tertentu
    public function getAllMenuOutlet(Request $request){
        $id_outlet = $request->get('id_outlet');
        // $id_outlet = $request->header('id_outlet');
        try{
            $menu = DB::table('outlet_menus')
                ->where('id_outlet', $id_outlet)
                ->join('menus','outlet_menus.id_menu','=','menus.id_menu')
                ->select('outlet_menus.id_outlet_menu','menus.name_menu','menus.category',
                'menus.description','menus.photo_menu','outlet_menus.cog',
                'outlet_menus.price','outlet_menus.stock')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Data Outlet Menu Success to load',
                'data' => $menu
            ], 200);
        }catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Data Outlet Menu Failed to load',
                'data' => ''
            ], 400);
        }
    }
    // Ambil Data Seluruh Pegawai dari toko tertentu
    public function getAllMenuByStore(Request $request){
        $id_owner = $request->header('id_owner');

        $user = User::where('id_owner', $id_owner)->get();

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
    // Ambil data pegawai dari id_outlet_menu
    public function getMenuOutlet(Request $request){
        $id_outlet_menu = $request->header('id_outlet_menu');

        $menu = $menu = DB::table('outlet_menus')
        ->where('id_outlet_menu', $id_outlet_menu)
        ->join('menus','outlet_menus.id_menu','=','menus.id_menu')
        ->select('outlet_menus.*', 'menus.name_menu','menus.category')->first();

        if(!empty($menu)){
            return response()->json([
                'success' => true,
                'message' => 'Data Menu found',
                'data' => $menu
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => ''
            ], 400);
        }
    }


    // POST
    // Tambah data menu
    public function addMenu(Request $request){        
        $data = $this->validate($request,[
            'id_store' => 'required',
            'id_outlet' => 'required',
            'name_menu' => 'required',
            'description' => 'nullable',
            'photo_menu' => 'nullable',
            'category' => 'required',
            'price' => 'required|numeric',
            'cog' => 'numeric|nullable',
            'is_stock' => 'required|boolean',
            'stock' => 'numeric|nullable'
        ]);

        $menu = new Menu;
        $outletMenu = new OutletMenu;
        
        try {
            $menuCount = Menu::where("id_store",$data["id_store"])->count();
            $menu->id_menu = "M".$data["id_store"].(string)sprintf('%03u',$menuCount+1);
            $menu->id_store = $data['id_store'];
            $menu->name_menu = $data['name_menu'];            
            $menu->description = $data['description'];
            $menu->category = $data['category'];
            $menu->photo_menu = $data['photo_menu'];
            $menu->is_active = true;

            $menuOutletCount = OutletMenu::where("id_outlet",$data["id_outlet"])->count();
            $outletMenu->id_outlet_menu = "M".$data["id_outlet"].(string)sprintf('%03u',$menuOutletCount+1);
            $outletMenu->id_menu = $menu->id_menu;
            $outletMenu->id_outlet = $data['id_outlet'];            
            $outletMenu->cog = $data['cog'];
            $outletMenu->price = $data['price'];
            $outletMenu->is_stock = $data['is_stock'];
            if($outletMenu->is_stock == true){
                $outletMenu->stock = $data['stock'];
            } else $outletMenu->stock = 0;
                        
            $menu->save();
            $outletMenu->save();

        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Add Menu Failed'.$e,
                'data' => ''
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Add Menu Success!',
            'data' => [$menu,$outletMenu]
        ], 201);   
    }

    //Update Data Menu
    public function updateMenu(Request $request){        
        $data = $this->validate($request,[
            'id_store' => 'required',
            'id_outlet' => 'required',
            'id_menu' => 'required',
            'id_outlet_menu' => 'required',
            'name_menu' => 'required',
            'description' => 'nullable',
            'photo_menu' => 'nullable',
            'category' => 'required',
            'price' => 'required|numeric',
            'cog' => 'numeric|nullable',
            'is_stock' => 'required|boolean'
        ]);

        $menu = new Menu;
        $outletMenu = new OutletMenu;
        
        try {
            $menu = Menu::where("id_menu", $data["id_menu"])->first();
            $menu->name_menu = $data["name_menu"];   
            $menu->description = $data["description"]; 
            $menu->category = $data["category"]; 
            $menu->is_active = true;

            $outletMenu = OutletMenu::where("id_outlet_menu", $data["id_outlet_menu"])->first();   
            $outletMenu->cog = $data["cog"]; 
            $outletMenu->price = $data["price"]; 
                        
            $menu->save();
            $outletMenu->save();

        } catch(Throwable $e){
            print($e);
            return response()->json([
                'success' => false,
                'message' => 'Update Menu Failed ',
                'data' => ''
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Update Menu Success!',
            'data' => [$menu,$outletMenu]
        ], 201);   
    }
}
