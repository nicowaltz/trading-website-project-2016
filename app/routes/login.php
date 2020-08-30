<?php

$app->get('/login', function($req, $res, $args){
  $this->view->render('loginpage.php');
  return $res;
});

$app->post('/login', function($req, $res, $args){
  $body = $req->getParsedBody();

  $p = $this->db->prepare('SELECT * FROM users WHERE username = :user');
  $p->execute(['user' => $body['username']]);
  $user = $p->fetch(PDO::FETCH_ASSOC);
 

  $v = $this->validation;

  $v->validate([
    'username|Nutzernamen' => [$body['username'], 'required|alnumDash'],
    'password|Passwort' => [$body['password'], 'required']
  ]);

  if ($v->passes()) {

    if (! $user) {
      $errors = $v->createMessages(['username' => ['Nutzer nicht vorhanden']]);
      $this->view->render('loginpage.php', ['errors' => $errors]);
      return $res;
    }
    if (md5($body['password']) !== $user['password']) {
      $errors = $v->createMessages(['password' => ['Falsches Passwort']]);
      $this->view->render('loginpage.php', ['errors' => $errors, 'username' => $body['username']]);
      return $res;
    }

    $_SESSION['auth'] = $user['id'];
    return redirect($res);
  }

 
  $this->view->render('loginpage.php', [
    'errors' => $v->errors(),
    'username' => $body['username']
  ]);

  return $res;
});
