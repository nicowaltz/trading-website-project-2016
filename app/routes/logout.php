<?php


$app->get('/logout', function($req, $res, $args) {
	unset($_SESSION['auth']);
	return redirect($res, '/login');
});
