<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Predictions;
use App\PredictionLines;
use App\SalesLineItem;
use Throwable;

class PredictionController extends BaseController
{
    // Mengambil data penjualan berdasarkan outlet
    public function getSinglePredictionSales(Request $request){
        $id_outlet_menu = $request->get('id_outlet_menu');
        $currentDate = date("yy-m-d", strtotime($request->get('current_date')));   
        try{
            $this->generatePredictionSales($id_outlet_menu,6);

            $prediction = Predictions::where('id_outlet_menu',$id_outlet_menu)->first();

            return response()->json([
                'success' => true,
                'message' => 'Get Single Prediction Sales Success!',
                'data' => $prediction
            ], 200);
        } catch(Throwable $e){
            print($e);
            return response()->json([
                'success' => false,
                'message' => 'Get Single Prediction Menu Error',
                'data' => ''
            ], 400);
        }        
    }

    // Mengambil data penjualan berdasarkan outlet
    public function generatePredictionSales($id_outlet_menu, $workDay){
        $firstDayEmpty = true;
        try{

            $periode = PredictionLines::where('id_outlet_menu', $id_outlet_menu)->orderBy('periode')->count();
             
            if($periode == 0){
                $periode++;
                $predictionLine = new PredictionLines();
                $predictionLine->id_outlet_menu = $id_outlet_menu;
                $predictionLine->periode = $periode;
                $predictionLine->sales_qty = 0;

                $salesLineItems = SalesLineItem::where('id_outlet_menu', $id_outlet_menu)->orderBy('created_at')->get();
                $startDatePeriode = date("y-m-d", strtotime($salesLineItems[0]->created_at->format("y-m-d")));
                $daystartDatePeriode = $salesLineItems[0]->created_at->format("D");

                switch($daystartDatePeriode){
                    case "Mon": 
                        $startDatePeriode = date("y-m-d", strtotime($startDatePeriode. ' + 6 days'));
                        break;
                    case "Tue": 
                        $startDatePeriode = date("y-m-d", strtotime($startDatePeriode. ' + 5 days'));
                        break;
                    case "Wed": 
                        $startDatePeriode = date("y-m-d", strtotime($startDatePeriode. ' + 4 days'));
                        break;
                    case "Thu": 
                        $startDatePeriode = date("y-m-d", strtotime($startDatePeriode. ' + 3 days'));
                        break;
                    case "Fri": 
                        $startDatePeriode = date("y-m-d", strtotime($startDatePeriode. ' + 2 days'));
                        break;
                    case "Sat": 
                        $startDatePeriode = date("y-m-d", strtotime($startDatePeriode. ' + 1 days'));
                        break;
                }
                
                $endDatePeriode = date("y-m-d", strtotime($startDatePeriode. ' + 6 days')); 
                $currentDate = date("y-m-d", strtotime($startDatePeriode));

                $predictionLine->start_periode_date = date('Y-m-d H:i:s', strtotime($startDatePeriode));
                $predictionLine->end_periode_date = date('Y-m-d H:i:s', strtotime($endDatePeriode));

                $this->generatePrediction($salesLineItems, $startDatePeriode, $endDatePeriode, $currentDate, $predictionLine, $workDay, $firstDayEmpty, $periode, $id_outlet_menu);
        }else{
            $firstDayEmpty = false;
            $endDatePeriode = PredictionLines::select('end_periode_date')->where([
                ['id_outlet_menu', '=', $id_outlet_menu],
                ['periode', '=', $periode],
            ])->first()->end_periode_date;
            
            $periode++;
            $predictionLine = new PredictionLines();
            $predictionLine->id_outlet_menu = $id_outlet_menu;
            $predictionLine->periode = $periode;

            $startDatePeriode = date("y-m-d", strtotime($endDatePeriode. ' + 1 days'));
            $endDatePeriode = date("y-m-d", strtotime($startDatePeriode. ' + 6 days')); 
            $currentDate = $startDatePeriode;

            $salesLineItems = SalesLineItem::where([
                ['id_outlet_menu', '=', $id_outlet_menu],
                ['created_at', '>=', date('Y-m-d H:i:s', strtotime($startDatePeriode))],
            ])->orderBy('created_at')->get();

            $predictionLine->start_periode_date = date('Y-m-d H:i:s', strtotime($startDatePeriode));
            $predictionLine->end_periode_date = date('Y-m-d H:i:s', strtotime($endDatePeriode));

            $this->generatePrediction($salesLineItems, $startDatePeriode, $endDatePeriode, $currentDate, $predictionLine, $workDay, $firstDayEmpty, $periode, $id_outlet_menu);
        }
        } catch(Throwable $e){
            print($e);
        }        
    }

    public function generatePrediction($salesLineItems, $startDatePeriode, $endDatePeriode, $currentDate, $predictionLine, $workDay, $firstDayEmpty, $periode, $id_outlet_menu){
        $weight1 = array(0.5, 0.3, 0.2);
        $weight2 = array(0.6, 0.3, 0.1);
        $weight3 = array(0.7, 0.2, 0.1);
        $weight4 = array(0.5, 0.4, 0.1);

        $dayWorkPass = 1;

        foreach($salesLineItems as $item){ 
            $salesDate = date("y-m-d", strtotime($item->created_at->format("y-m-d")));
            if($salesDate >= $startDatePeriode && $salesDate <= $endDatePeriode){
                if($salesDate == $startDatePeriode){
                    $firstDayEmpty = false;
                }
                if($currentDate < $salesDate){
                    $dayWorkPass++;
                    $currentDate = $salesDate;
                }
                $predictionLine->sales_qty = $predictionLine->sales_qty + $item->quantity; 
            } else{
                if(!$firstDayEmpty){                            
                    if($dayWorkPass >= round($workDay/2)){
                        $remainsDay = $workDay - $dayWorkPass;   
                        // Mencari jumlah penjualan setelah mencari rata rata penjualan                             
                        $predictionLine->sales_qty = $predictionLine->sales_qty + $remainsDay*round($predictionLine->sales_qty/$dayWorkPass);
                        if($periode > 3){
                            $predictionLine1 = PredictionLines::where([
                                ['id_outlet_menu', '=', $id_outlet_menu],
                                ['periode', '=', $periode-3],
                            ])->first();
                            $predictionLine2 = PredictionLines::where([
                                ['id_outlet_menu', '=', $id_outlet_menu],
                                ['periode', '=', $periode-2],
                            ])->first();
                            $predictionLine3 = PredictionLines::where([
                                ['id_outlet_menu', '=', $id_outlet_menu],
                                ['periode', '=', $periode-1],
                            ])->first();

                            $predictionLine->wma_1 = ($predictionLine1->sales_qty * $weight1[2])
                                                    +($predictionLine2->sales_qty * $weight1[1])
                                                    +($predictionLine3->sales_qty * $weight1[0]);

                            $predictionLine->wma_2 = ($predictionLine1->sales_qty * $weight2[2])
                                                    +($predictionLine2->sales_qty * $weight2[1])
                                                    +($predictionLine3->sales_qty * $weight2[0]);
                        
                            $predictionLine->wma_3 = ($predictionLine1->sales_qty * $weight3[2])
                                                    +($predictionLine2->sales_qty * $weight3[1])
                                                    +($predictionLine3->sales_qty * $weight3[0]);

                            $predictionLine->wma_4 = ($predictionLine1->sales_qty * $weight4[2])
                                                    +($predictionLine2->sales_qty * $weight4[1])
                                                    +($predictionLine3->sales_qty * $weight4[0]);

                            
                            $predictionLine->error_1 = round(abs($predictionLine->sales_qty - $predictionLine->wma_1),2);
                            $predictionLine->error_2 = round(abs($predictionLine->sales_qty - $predictionLine->wma_2),2);
                            $predictionLine->error_3 = round(abs($predictionLine->sales_qty - $predictionLine->wma_3),2);
                            $predictionLine->error_4 = round(abs($predictionLine->sales_qty - $predictionLine->wma_4),2);

                            $predictionLine->presentation_error_1 = round(($predictionLine->error_1 / $predictionLine->sales_qty)*100,3);
                            $predictionLine->presentation_error_2 = round(($predictionLine->error_2 / $predictionLine->sales_qty)*100,3);
                            $predictionLine->presentation_error_3 = round(($predictionLine->error_3 / $predictionLine->sales_qty)*100,3);
                            $predictionLine->presentation_error_4 = round(($predictionLine->error_4 / $predictionLine->sales_qty)*100,3);
                        }
                        $periode++;
                        $predictionLine->save();
                    }                                  
                    $dayWorkPass = 1;                      
                    $startDatePeriode = date("y-m-d", strtotime($endDatePeriode. ' + 1 days'));
                    $endDatePeriode = date("y-m-d", strtotime($startDatePeriode. ' + 6 days'));                             
                    $predictionLine = new PredictionLines();
                    $predictionLine->id_outlet_menu = $item->id_outlet_menu;
                    $predictionLine->periode = $periode;
                    $predictionLine->start_periode_date = date('Y-m-d H:i:s', strtotime($startDatePeriode));
                    $predictionLine->end_periode_date = date('Y-m-d H:i:s', strtotime($endDatePeriode));
                    $predictionLine->sales_qty = $item->quantity;
                }                   
            }
        }

        $lastPeriode = Predictions::where('id_outlet_menu',$id_outlet_menu)->first();
        
        $currentDate = date("yy-m-d", strtotime($currentDate));                

        if(!empty($lastPeriode)){
            if($lastPeriode->periode = $periode){                
                $this->generateMape($lastPeriode, $periode, $weight1, $weight2, $weight3, $weight4,$currentDate);
            }
        } else{
            $prediction = new Predictions();
            $prediction->id_outlet_menu = $id_outlet_menu;
            $menu = DB::table('outlet_menus')
                ->where('id_outlet_menu', $id_outlet_menu)
                ->join('menus','outlet_menus.id_menu','=','menus.id_menu')
                ->select('outlet_menus.id_outlet_menu','menus.name_menu')->first();
            $prediction->name_menu = $menu->name_menu;
            $this->generateMape($prediction, $periode, $weight1, $weight2, $weight3, $weight4,$currentDate);                
        }
    }
    
    public function getAllPredictionSalesByOutlet(Request $request){
        $id_outlet = $request->get('id_outlet');
        $currentDate = date("yy-m-d", strtotime($request->get('current_date')));

        try{
            $menus = DB::table('outlet_menus')
                ->where('id_outlet', $id_outlet)
                ->join('menus','outlet_menus.id_menu','=','menus.id_menu')
                ->select('outlet_menus.id_outlet_menu','menus.name_menu')->get();
           
            foreach($menus as $menu){
                $this->generatePredictionSales($menu->id_outlet_menu,6);     
            }

            $listPredictionMenu = Predictions::where('id_outlet_menu', 'LIKE', '%'.$id_outlet.'%')->get();

            return response()->json([
                'success' => true,
                'message' => 'Get All Prediction Sales Success!',
                'data' => $listPredictionMenu
            ], 200);
        }catch(Throwable $e){
            print($e);
            return response()->json([
                'success' => false,
                'message' => 'Get All Prediction Menu Error',
                'data' => ''
            ], 400);
        }
    }

    public function generateMape($prediction, $periode, $weight1, $weight2, $weight3, $weight4, $currentDate){        
        if($periode > 3){
            if($periode > 4){
                $mape_1 = PredictionLines::select('presentation_error_1')->where([
                    ['id_outlet_menu', '=', $prediction->id_outlet_menu],
                ])->sum('presentation_error_1') / ($periode-4);
        
                $mape_2 = PredictionLines::select('presentation_error_2')->where([
                    ['id_outlet_menu', '=', $prediction->id_outlet_menu],
                ])->sum('presentation_error_2') / ($periode-4);
        
                $mape_3 = PredictionLines::select('presentation_error_3')->where([
                    ['id_outlet_menu', '=', $prediction->id_outlet_menu],
                ])->sum('presentation_error_3') / ($periode-4);
        
                $mape_4 = PredictionLines::select('presentation_error_4')->where([
                    ['id_outlet_menu', '=', $prediction->id_outlet_menu],
                ])->sum('presentation_error_4') / ($periode-4);
        
        
                $predictionLine1 = PredictionLines::where([
                    ['id_outlet_menu', '=', $prediction->id_outlet_menu],
                    ['periode', '=', $periode-3],
                ])->first();
                $predictionLine2 = PredictionLines::where([
                    ['id_outlet_menu', '=', $prediction->id_outlet_menu],
                    ['periode', '=', $periode-2],
                ])->first();
                $predictionLine3 = PredictionLines::where([
                    ['id_outlet_menu', '=', $prediction->id_outlet_menu],
                    ['periode', '=', $periode-1],
                ])->first();
        
                $mape = array($mape_1, $mape_2, $mape_3, $mape_4);
                
                $prediction->mape = $mape[0];            
                $loop = 0;
                $weight = 1;
                for($loop;$loop <= 3;$loop++){
                    if($mape[$loop] < $prediction->mape){
                        $prediction->mape = $mape[$loop];
                        $weight = $loop+1;
                    }
                }
        
                switch($weight){
                    case 1: $prediction->wma = ($predictionLine1->sales_qty * $weight1[2])
                            +($predictionLine2->sales_qty * $weight1[1])
                            +($predictionLine3->sales_qty * $weight1[0]);
                            $prediction->mape = $mape[0];
                            break;
                    case 2: $prediction->wma = ($predictionLine1->sales_qty * $weight2[2])
                            +($predictionLine2->sales_qty * $weight2[1])
                            +($predictionLine3->sales_qty * $weight2[0]);
                            $prediction->mape = $mape[1];
                            break;
                    case 3: $prediction->wma = ($predictionLine1->sales_qty * $weight3[2])
                            +($predictionLine2->sales_qty * $weight3[1])
                            +($predictionLine3->sales_qty * $weight3[0]);
                            $prediction->mape = $mape[2];
                            break;
                    case 4: $prediction->wma = ($predictionLine1->sales_qty * $weight4[2])
                            +($predictionLine2->sales_qty * $weight4[1])
                            +($predictionLine3->sales_qty * $weight4[0]);
                            $prediction->mape = $mape[3];
                            break;
                }
                $prediction->periode = $periode;
                $prediction->save();
            }        
        }            
    }

    public function getAllPredictionSales(Request $request){
        $id_outlet = $request->get('id_outlet');
        try{
            $listPredictionMenu = Predictions::where('id_outlet_menu', 'LIKE', '%'.$id_outlet.'%')->get();

            return response()->json([
                'success' => true,
                'message' => 'Get All Prediction Sales Success!',
                'data' => $listPredictionMenu
            ], 200);
        }catch(Throwable $e){
            print($e);
            return response()->json([
                'success' => false,
                'message' => 'Get All Prediction Menu Error',
                'data' => ''
            ], 400);
        }
    }
}
