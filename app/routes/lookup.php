<?php

use nFinance\Machines\Stock;


$app->get('/lookup', function($req, $res, $args) {

  if (! $req->isXhr())
    return redirect($res);

  $queryParams = $req->getQueryParams();
  $stock = new Stock($queryParams['s']);

  if (! $stock->lookup())
    return $res->withStatus(404)->withBody(null);

  $this->view->view('lookup.php', $stock->info());


  return $res->withStatus(200)->withHeader('Content-Type', 'text/html');
})->add($login_mw);
