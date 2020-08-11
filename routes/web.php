<?php

use Illuminate\Support\Str;

$router->get('/', function () use ($router) {
    return "APOS Restful Service - KoTA 202 / " . $router->app->version();
});

$router->group(['prefix' => 'auth'], function() use ($router){
    $router->post('/register','AuthController@register');
    $router->post('/login','AuthController@login');
    $router->get('/sendRegisterEmail','AuthController@sendRegisterEmail');
    $router->get('/token_validation','AuthController@validationToken');
});

$router->group(['prefix' => 'manageUser'], function() use ($router){
    $router->get('/getUser','ManageUserController@getUser');
    $router->get('/getAllUserByStore','ManageUserController@getUserByStore');
    $router->post('/addUser','ManageUserController@addUser');
    $router->get('/userId','ManageUserController@getUserId');
});

$router->group(['prefix' => 'manageEmployee'], function() use ($router){
    $router->post('/newEmployee','ManageEmployeeController@addEmployee');
    $router->put('/updateEmployee','ManageEmployeeController@updateEmployee');
    $router->get('/allEmployeeByOutlet','ManageEmployeeController@getAllEmployeeByOutlet');
});

$router->group(['prefix' => 'manageStore'], function() use ($router){
    $router->post('/newStore','ManageStoreController@createNewStore');
    $router->post('/addOutlet','ManageStoreController@addOutlet');
    $router->get('/infoStore','ManageStoreController@getInfoStore');
});

$router->group(['prefix' => 'manageOutlet'], function() use ($router){
    $router->put('/updateOutlet','ManageOutletController@updateOutlet');
    $router->post('/newOutlet','ManageOutletController@createNewOutlet');
    $router->get('/infoOutlet','ManageOutletController@getInfoOutlet');
    $router->get('/allOutletByStore','ManageOutletController@getOutletByIdStore');
});

$router->group(['prefix' => 'manageMenu'], function() use ($router){
    $router->post('/addMenu','ManageMenuController@addMenu');
    $router->put('/updateMenu','ManageMenuController@updateMenu');
    $router->get('/allMenuOutlet','ManageMenuController@getAllMenuOutlet');
    $router->get('/menuOutlet','ManageMenuController@getMenuOutlet');
});

$router->group(['prefix' => 'trsc'], function() use ($router){
    $router->post('/createSales','ManageTransactionController@createSales');
    $router->get('/outletSales','ManageTransactionController@getAllSalesByOutlet');
});

$router->group(['prefix' => 'report'], function() use ($router){
    $router->get('/reportSales','ManageReportController@getReportSales');
    $router->get('/allReportSales','ManageReportController@getAllReportSales');
});

$router->group(['prefix' => 'predict'], function() use ($router){
    $router->post('/currentPrediction','PredictionController@generatePrediction');
    $router->get('/predictionSales','PredictionController@getSinglePredictionSales');
    $router->get('/allPredictionOutletSales','PredictionController@getAllPredictionSalesByOutlet');
    $router->get('/allPredictionSales','PredictionController@getAllPredictionSales');
});