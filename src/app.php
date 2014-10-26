<?php
require_once __DIR__.'/bootstrap.php';

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

$app->put('/user', function(\Symfony\Component\HttpFoundation\Request $request) use($app) {
    $userController = $app['controller.user'];
    $user = array(
        'email' => $request->request->get('email'),
        'firstname'  => $request->request->get('firstname'),
        'lastname' => $request->request->get('lastname')
    );

    try {
        $userController->update($user);
    } catch(\Exception $e) {
        return $app->json(array('errors' => array($e->getMessage())), 404);
    }

    return $app->json($user, 201);
});

$app->post('/user', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $userController = $app['controller.user'];
    $user = array(
        'email' => $request->request->get('email'),
        'firstname'  => $request->request->get('firstname'),
        'lastname' => $request->request->get('lastname'),
        'password' => password_hash($request->request->get('password'), PASSWORD_BCRYPT),
        'latitude' => $request->request->get('latitude'),
        'longitude' => $request->request->get('longitude'),
    );

    // TODO: Validate inputs, if any are empty then return 400 Bad Request

    try {
        $user['id'] = $userController->create($user);

        // Generate user an API key
        try {
            $user['api_key'] = $app['controller.device']->create($user);
            unset($user['id']);
            unset($user['password']);
            unset($user['latitude']);
            unset($user['longitude']);
        }
        catch (\Exception $e)
        {
            // Delete user as creating API was fail
            $userController->delete($user['id']);
            return $app->json(array('errors' => array($e->getMessage())), 500);
        }
    } catch(\Exception $e) {
        return $app->json(array('errors' => array($e->getMessage())), 500);
    }

    return $app->json($user, 201);
});

$app->delete('/device', function() use ($app) {
      $deviceCtrl = $app['controller.device'];
      $request = $app['request'];
      if(!$deviceCtrl->exists($request->get('api_key')))
      {
          return $app->json(array('status' => '404', 'message' => 'API key not found'), 404);
      }
      else
      {
          $deviceCtrl->remove($request->get('api_key'));
          return $app->json(array('status' => '200', 'message' => 'API key has been removed'), 200);
      }

  });

$app->error(function (\Exception $e, $code) use ($app) {
      return $app->json(array('status' => $code, 'message' => $e->getMessage()), $code);
  });

$app->run();