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

$app->run();