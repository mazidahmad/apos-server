<?php

namespace App\Http\Controllers;

use App\Payments;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Sales;
use App\SalesLineItem;
use App\RegisterSales;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class ManageTransactionController extends BaseController
{
    // POST
    // Tambah data penjualan
    public function createSales(Request $request){
        $data = $this->validate($request,[
            'id_outlet' => 'required',
            'id_employee' => 'required',
            'list_order' => 'required',
            'discount' => 'nullable',
            'total_price' => 'required',
            'tax' => 'nullable',
            'customer_name' => 'nullable',
            'is_paid' => 'required',
        ]);

        $sales = new Sales;
        $date = (string)date("ymd");
        $trscCount = (string)sprintf('%03u',Sales::where("id_outlet",$data["id_outlet"])
        ->where("created_at",'<=',Carbon::now())->count()+1);

        $sales->id_sales = $date.$data['id_outlet'].$trscCount;
        $sales->id_outlet = $data['id_outlet'];
        $sales->id_employee = $data['id_employee'];
        $sales->total_price = $data['total_price'];
        $sales->tax = $data['tax'];
        $sales->customer_name = $data['customer_name'];
        $sales->is_paid = $data['is_paid'];

        $sales->save();

        $listOrderJson = $data['list_order'];

        $countSales = 1;        
    
        if($listOrderJson != null){
            foreach($listOrderJson as $listOrder){
                $salesLineItem = new SalesLineItem;
                $registerSales = new RegisterSales;
                $salesitemCount = sprintf('%03u',$countSales);
                $salesLineItem->id_sales_line = $date.$data['id_outlet']."SI".$trscCount.(string)$salesitemCount;
                $salesLineItem->id_custom_menu = null;
                $salesLineItem->id_outlet_menu = $listOrder['id_outlet_menu'];
                $salesLineItem->quantity = $listOrder['qty'];
                $salesLineItem->discount = $listOrder['discount'];
                $salesLineItem->subtotal_price = $listOrder['subtotal_price'];
                $salesLineItem->save();
                $countSales++;

                $registerSales->id_sales = $sales->id_sales;
                $registerSales->id_sales_line = $salesLineItem->id_sales_line;
                $registerSales->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Create Sales Success',
                'data' => ''
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Create Sales Failed',
                'data' => ''
            ], 401);
        }
    }

    public function createPayment(Request $request){
        $data = $this->validate($request,[
            'id_sales' => 'required',
            'cash' => 'required',
            'change_amount' => 'required'
        ]);

        $payment = new Payments();
        $payment->id_payment = "P".$data['id_outlet'];
        $payment->id_sales = $data['id_sales'];
        $payment->cash = $data['cash'];
        $payment->change_amount = $data['change_amount'];

        $payment->save();
    }

    // GET

    // Mengambil data penjualan berdasarkan outlet
    public function getAllSalesByOutlet(Request $request){
        $id_outlet = $request->get('id_outlet');

        try{
            $sales = Sales::where('id_outlet', $id_outlet)->get();
            return response()->json([
                'success' => true,
                'message' => 'Get Sales Success!',
                'data' => $sales
            ], 200);
        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Get Sales Failed!',
                'data' => ''
            ], 400);
        }        
    }
    
    // Mengambil data penjualan berdasarkan outlet
    public function getDetailSales(Request $request){
        $id_outlet = $request->get('id_sales');

        try{
            $sales = Sales::where('id_outlet', $id_outlet)->get();
            return response()->json([
                'success' => true,
                'message' => 'Get Sales Success!',
                'data' => $sales
            ], 200);
        } catch(Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Get Sales Failed!',
                'data' => ''
            ], 400);
        }        
    }

}
