<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\SalesLineItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class ManageReportController extends BaseController
{

    // GET
    // Mengambil data penjualan berdasarkan outlet
    public function getReportSales(Request $request){
        $id_outlet_menu = $request->get('id_outlet_menu');
        $startDate = date("yy-m-d", strtotime($request->get('start_date')));
        $endDate = date("yy-m-d", strtotime($request->get('end_date')));
        
        try{
            $sales = SalesLineItem::select('id_outlet_menu',DB::raw('SUM(quantity) as qty'),'created_at')->where([
                ['id_outlet_menu', '=', $id_outlet_menu],
                ['created_at', '>=', $startDate],
                ['created_at', '<=', $endDate]
            ])->groupBy('created_at','id_outlet_menu')->orderBy('created_at')->get();
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
    // GET
    // Mengambil data penjualan berdasarkan outlet
    public function getAllReportSales(Request $request){
        $startDate = date("Y-m-d", strtotime($request->get('start_date')));
        $endDate = date("Y-m-d", strtotime($request->get('end_date')));
        
        try{
            $sales = SalesLineItem::select(DB::raw('SUM(quantity) as qty, SUM(subtotal_price) 
            as subtotal_price'),'created_at')->where([
                ['created_at', '>=', $startDate],
                ['created_at', '<=', $endDate]
            ])->groupBy('created_at')->orderBy('created_at')->get();
            return response()->json([
                'success' => true,
                'message' => 'Get Sales Success!',
                'data' => $sales
            ], 200);
        } catch(Throwable $e){
            print($e);
            return response()->json([
                'success' => false,
                'message' => 'Get Sales Failed!',
                'data' => ''
            ], 400);
        }        
    }

}
