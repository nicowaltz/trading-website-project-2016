<?php

namespace nFinance\Helpers;

class View
{

  protected $data = [];

  public function render($view, $data = [])
  {
    if ($this->data) {
      $data = array_merge($data, $this->data);
    }
    extract($data);
    
    require APP . 'views/build/header.php';
    require APP . 'views/build/' . $view;
    require APP . 'views/build/footer.php';

  }

  public function view($view, $data = [])
  {
    if ($this->data) {
      $data = array_merge($data, $this->data);
    }
    extract($data);

    require APP . 'views/build/' . $view;


  }

  public function append($data = [])
  {
    $this->data = array_merge($this->data, $data);
  }

  public function dump($dump) {
    return $this->view('dump.php', ['dump' => $dump]);
  }

}
