<?php


$app->get('/buy', function($req, $res, $args) {


  $this->view->render('buy.php');

  return $res;
})->add($login_mw);

$app->post('/buy', function($req, $res, $args) {
  $body = $req->getParsedBody();

  $v = $this->validation;

  $v->validate([
    'amount' => [$body['amount'], 'required|int|min(0,number)'],
    'stock' => [$body['stock'], 'required|max(20)']
  ]);

  if ($v->passes()) {
    $this->holdings->buy($body['stock'], $body['amount']);
    return redirect($res);
  }


  $this->view->render('buy.php', [
    'errors' => $v->errors()->all()
  ]);

  return $res;

})->add($login_mw);
