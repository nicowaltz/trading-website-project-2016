<?php

use Slim\Http\{Response, Request};

$login_mw = function(Request $req, Response $res, $next) {
  if (! isset($_SESSION['auth'])) return redirect($res, '/login'); 

  return $next($req, $res);
};

