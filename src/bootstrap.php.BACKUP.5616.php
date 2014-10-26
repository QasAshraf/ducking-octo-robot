<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// Figure out environment based upon APP_ENV variable (environment)
$env = getenv('APP_ENV') ? : 'prod';

$env = 'dev';

// Read configuration based upon environment
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__ . "/../config/$env.json"));
$app['debug'] = $app['config']['debug'];

// Database.
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
      'driver'   => 'pdo_mysql',
      'path'     => __DIR__.'/app.db',
      'dbname'   => $app['config']['database']['database'],
      'host'     => $app['config']['database']['server'],
      'user'     => $app['config']['database']['username'],
      'password' => $app['config']['database']['password'],
      'port'     => $app['config']['database']['port'],
    ),
  ));

$app['controller.tag'] = $app->share(
    function ($app) {
        return new TC\Controllers\TagController($app['db']);
    }
);

<<<<<<< HEAD
$app['controller.cron'] = $app->share(
    function($app) {
        return new TC\Controllers\CronController($app['db']);
    }
);

=======
$app['controller.device'] = $app->share(
  function ($app) {
      return new TC\Controllers\DeviceController($app['db']);
  }
);

$app['controller.user'] = $app->share(
  function ($app) {
      return new TC\Controllers\UserController($app['db']);
  }
);
>>>>>>> e6410981b5fa052c3775bc3d3afd0af9b0b98611


// On dev and stage...
if ($app['debug']) {
    $app->register(new Whoops\Provider\Silex\WhoopsServiceProvider);
}