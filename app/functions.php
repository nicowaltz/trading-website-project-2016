<?php

  use Slim\Http\Response;

  function num($number): string {
    return number_format((float)$number, 2, ',', '.');
  }

  function e(string $string): string {
  	return htmlspecialchars($string, ENT_HTML5, 'utf-8', false);
  }

  function redirect(Response $res, $location = '/'): Response {
  	
  	return $res->withStatus(301)->withHeader('Location', URL . $location);
  }
  
