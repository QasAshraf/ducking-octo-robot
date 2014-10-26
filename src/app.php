<?php
require_once __DIR__.'/bootstrap.php';

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

$app->delete('/device', function () use ($app) {
      if(!$app['controller.device']->exists($app['request']->get('api_key')))
      {
          return $app->json(array('code' => 404, 'message' => 'API key supplied not found'), 404);
      }
      else
      {
          $app['controller.device']->remove($app['request']->get('api_key'));
          return $app->json(array('code' => 200, 'message' => 'API key removed'), 200);
      }
  });


$app->error(function (\Exception $e, $code) use ($app) {
      return $app->json(array('code' => $code, 'message' => $e->getMessage()), $code);
  });

$app->run();