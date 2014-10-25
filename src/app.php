<?php
require_once __DIR__.'/bootstrap.php';

//use controllers
//use TC\Controllers;

$app->get('/', function() use($app) {
      return 'Hello API';
  });

$app->get('/tag', function() use($app) {
      $tags = $app['controller.tag']->findAll();

      if(empty($tags)){
          return $app->json('', 204);
      }
      return $app->json($tags, 200);
  });

$app->get('/user/{email}', function($email) use($app) {
        $user = $app['controller.user']->find($email);

        if(empty($user)){
            return $app->json(null, 404);
        }
        return $app->json($user, 200);
    });

$app->get('/tag/{filter}', function($filter) use($app) {
    $tags = $app['controller.tag']->getFilterList($filter);

    if(empty($tags)){
        return $app->json('', 204);
    }
    return $app->json($tags, 200);
});

$app->run();