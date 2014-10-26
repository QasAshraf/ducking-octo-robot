<?php
require_once __DIR__.'/bootstrap.php';

//use controllers
//use TC\Controllers;


$app->before(function (\Symfony\Component\HttpFoundation\Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

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

$app->get('/tag/{filter}', function($filter) use($app) {
    $tags = $app['controller.tag']->getFilterList($filter);

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

$app->post('/user', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $userController = $app['controller.user'];
    $user = array(
        'email' => $request->request->get('email'),
        'firstname'  => $request->request->get('firstname'),
        'lastname' => $request->request->get('lastname')
    );

    try {
        $user['id'] = $userController->create($user);
    } catch(\Exception $e) {
        return $app->json(array('errors' => array($e->getMessage())), 500);
    }

    return $app->json($user, 201);
});


$app->run();