<?php

$app->get('/', function($req, $res, $args) {

  $this->view->render('portfolio.php', [
    'user' => 'Nicholas Waltz',
    'holdings' => $this->holdings->portfolio(),
    'cash' => $this->cash->currentMoney()
  ]);
  return $res;
})->add($login_mw);
