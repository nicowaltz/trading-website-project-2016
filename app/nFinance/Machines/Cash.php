<?php

namespace nFinance\Machines;

use PDO;

class Cash
{

  protected $db;

  public function __construct($db) {
    $this->db = $db;
    return $this;
  }

  public function spend($amount) {

    $money = $this->currentMoney() - floatval($amount);

    if ($money < 0 || $amount < 0)
      return false;
    

    $this->update($money);
    
    return true;
  }

  public function earn($amount) {

    $money = $this->currentMoney() + floatval($amount);

    if ($amount < 0)
      return false;
    
    $this->update($money);
    return true;
  }

  public function check($money) {
    return $money <= $this->currentMoney(); 
  }

  public function currentMoney() {
 

    $p = $this->db->prepare('
      SELECT money
      FROM users
      WHERE id = :user
    ');
    $p->execute([':user' => $_SESSION['auth']]);

    return floatval($p->fetch(PDO::FETCH_ASSOC)['money']);


  }

  private function update($money) {

    $p = $this->db->prepare('
      UPDATE users
      SET money = :money
      WHERE id = :user
    ');

    $p->execute([':user' => $_SESSION['auth'], ':money' => $money]);


  }
}
