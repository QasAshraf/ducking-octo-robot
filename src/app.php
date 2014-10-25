<?php
require_once __DIR__.'/bootstrap.php';

//use controllers
//use TC\Controllers;

$app->get('/', function() use($app) {
      return 'Hello API';
  });

$app->get('/tag/{filter}', function($filter) use($app) {
    $tags = $app['controller.tag']->getFilterList($filter);

    if(empty($tags)){
        return $app->json('', 204);
    }
    return $app->json($tags, 200);
});

$app->post('/customer', function(Request $request) use ($app){
    $customerController = new Controllers\Api\CustomerController($app['db.api']);
    $customer = $request->request->all();
    try{
        $customerModel = $customerController->addCustomer($customer);
    }
    catch(\Exception $e){
        return new Response($e->getMessage(),409);
    }
    return $app->json($customerModel->toArray(), 200); //was 201 but changed to suit x-editable
});

$app->run();