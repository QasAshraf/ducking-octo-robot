<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// Figure out environment based upon APP_ENV variable (environment)
$env = getenv('APP_ENV') ? : 'prod';

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

$app['controller.user'] = $app->share(
    function ($app) {
        return new TC\Controllers\UserController($app['db']);
    }
);


// On dev and stage...
if ($app['debug']) {
    $app->register(new Whoops\Provider\Silex\WhoopsServiceProvider);
}