
<?php
  /**
   *  nFinance bootstrap
   *  @author Nicholas Waltz 2015. All Rights Reserved
   *
   */

  session_start([
    'cache_limiter' => false
  ]);


  define('ROOT', dirname(__DIR__) . '/');
  define('APP', __DIR__ . '/');
  define('URL', 'http://nfinance');

  use nFinance\Helpers\View;
  use nFinance\Machines\{Cash, Stock};
  
  use nFinance\Managers\{History, Holdings};
  
  use nFinance\Validation\Validation;


  require ROOT . 'vendor/autoload.php';

  $app = new \Slim\App();
  $c = $app->getContainer();

  require APP . 'functions.php';
  require APP . 'mw.php';

  // force user
  $_SESSION['auth'] = '1';

// ------------------------------------------------

  $c['db'] = function($c) {
    $dsn = 'mysql:dbname=nfinance;host=127.0.0.1';
    $user = 'root';
    $password = '';
    try {
      $pdo = new PDO($dsn, $user, $password);

    } catch (PDOException $e) {
      die('PDO Connection failed: ' . $e->getMessage());
    }
    return $pdo;
  };

  $c['view'] = function($c) {
    return new View;
  };

  $c['cash'] = function($c) {
    return new Cash($c['db']);
  };

  $c['history'] = function($c) {
    return new History($c['db']);
  };

  $c['holdings'] = function($c) {
    return new Holdings($c['db'], $c['cash'], $c['history']);
  };

  $c['validation'] = function($c) {
    return new Validation($c['db']);
  };

  $app->add(function($req, $res, $next) {
    $this['view']->append([
      'uniqid' => uniqid()
    ]);
    $res = $next($req, $res);
    return $res;
  });


  require APP . 'routes.php';
