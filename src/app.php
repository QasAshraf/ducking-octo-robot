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

$app->put('/user', function(\Symfony\Component\HttpFoundation\Request $request) use($app) {
    $userController = $app['controller.user'];
    $user = array(
        'api_key' => $request->request->get('api_key'),
        'firstname'  => $request->request->get('firstname'),
        'lastname' => $request->request->get('lastname')
    );

    if(!$app['controller.device']->exists($user['api_key']))
    {
        return $app->json(array('status' => 404, 'message' => 'Couldn\'t find user with API key supplied'));
    }

    try {
        $userController->update($user);
    } catch(\Exception $e) {
        return $app->json(array('errors' => array($e->getMessage())), 500);
    }

    return $app->json($user, 200);
});

$app->get('/user/{key}', function($key) use ($app) {
      $userController = $app['controller.user'];

      if(!$app['controller.device']->exists($key))
      {
          return $app->json(array('status' => 404, 'message' => 'Couldn\'t find user with API key supplied'));
      }

      try {
          $uid = $app['controller.device']->getUserIdFromKey($key);
          $user = $userController->findById($uid);
          return $app->json(array('status' => 200, 'user' => $user->toArray(true)), 200);
      } catch(\Exception $e) {
          return $app->json(array('errors' => array($e->getMessage())), 500);
      }


  });

$app->get('/user/{email}', function($email) use($app) {
      $user = $app['controller.user']->find($email);

      if(empty($user)){
          return $app->json(null, 404);
      }
      return $app->json($user, 200);
  });

$app->post('/user/register', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
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

$app->post('/user/logon', function () use ($app) {
      $request = $app['request'];
      $user = array(
        'email' => $request->get('email'),
        'password' => $request->get('password'),
        'latitude' => $request->request->get('latitude'),
        'longitude' => $request->request->get('longitude'),
      );

      $userCtrl = $app['controller.user'];
      $db_user = $userCtrl->find($user['email']);

      if(!password_verify($user['password'], $db_user->getPassword()))
      {
          return $app->json(array('status' => 403, 'message' => 'Invalid credentials'));
      }
      else
      {
          // Generate new API key, woop.
          $user['id'] = $db_user->getId();
          $user['api_key'] = $app['controller.device']->create($user);
          unset($user['id']);
          unset($user['password']);
          unset($user['latitude']);
          unset($user['longitude']);
          return $app->json($user, 200);
      }
  });

$app->put('/user/tag', function(\Symfony\Component\HttpFoundation\Request $request) use ($app) {
     $api_key = $request->request->get('api_key');

     // Check we have some API key
     if(is_null($api_key))
     {
         return $app->json(array('status' => 400, 'message' => 'Missing API key :-('), 400);
     }

     // Make sure supplied API key exists
     $deviceCtrl = $app['controller.device'];
     if(!$deviceCtrl->exists($api_key))
     {
         return $app->json(array('status' => 404, 'message' => 'Couldn\'t find the supplied API key'), 400);
     }

     try {
         // Delete existing tags for this user
         $userCtrl = $app['controller.user'];
         $uid = $deviceCtrl->getUserIdFromKey($api_key);
         $userCtrl->deleteAllTags($uid);

         // Loop through tags received, create tags if they don't exist
         $tagCtrl = $app['controller.tag'];
         $tags = array();
         foreach($request->request->get('tags') as $tag)
         {
             // TODO: Add check if tag already exists
             if(is_null($tag['id']))
             {
                 $tag['id'] = $tagCtrl->create($tag['name']);
             }

             $userCtrl->bindTagToUser($uid, $tag['id']);
             $tags[] = $tag;
         }
     } catch(\Exception $e) {
         return $app->json(array('errors' => array($e->getMessage())), 500);
     }

     return $app->json(array('status' => 201, 'tags' => $tags), 201);
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

$app->put('/device', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $deviceCtrl = $app['controller.device'];
    $api_key = $request->request->get('api_key');
    $lat = $request->request->get('lat');
    $lon = $request->request->get('lon');

    try{
        $deviceCtrl->updateLocation($api_key, $lat, $lon);
    } catch(\Exception $e) {
        return $app->json(array('errors' => array($e->getMessage())), 404);
    }
    return $app->json(array('status' => '200', 'message' => 'location updated'), 200);
});

$app->get('/location', function() use ($app) {
      $locations = $app['controller.location']->findAll();

      if(empty($locations)){
          return $app->json('', 204);
      }
      return $app->json($locations, 200);
});

$app->error(function (\Exception $e, $code) use ($app) {
      return $app->json(array('status' => $code, 'message' => $e->getMessage()), $code);
  });

$app->run();