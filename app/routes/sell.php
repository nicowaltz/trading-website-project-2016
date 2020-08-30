<?php


$app->get('/sell', function($req, $res, $args) {

  $queryParams = $req->getQueryParams();


  $this->holdings->sell($queryParams['s']);


  return redirect($res);
})->add($login_mw);
