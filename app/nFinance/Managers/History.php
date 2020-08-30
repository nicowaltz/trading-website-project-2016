<?php

namespace nFinance\Managers;

use PDO;

class History
{

  const TRANS_BUY = 1;
  const TRANS_SELL = 2;

  protected $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function record($type, array $details = [])
  {
    // info =  id, symbol, shares, price, cost

    if (empty($details))
      return;

    if ($type === self::TRANS_BUY) {
      // prepare statement
      $p = $this->db->prepare('
        INSERT INTO history_buy
        (user_id, symbol, shares, price, cost, created_at)
        VALUES (:userid, :symbol, :shares, :price, :cost, NOW())
      ');

      // set placeholders
      $placeholders = [];
      $placeholders[':userid'] = $_SESSION['auth'];
      foreach ($details as $key => $value) {
        $placeholders[":$key"] = $value;
      }

      // execute
      $p->execute($placeholders);

    } else if ($type === self::TRANS_SELL) {
      // prepare statement
      $p = $this->db->prepare('
        INSERT INTO history_sell
        (user_id, symbol, price, amount, created_at)
        VALUES (:userid, :symbol, :price, :amount, NOW())
      ');

      // set placeholders
      $placeholders = [];
      $placeholders[':userid'] = $_SESSION['auth'];
      foreach ($details as $key => $value) {
        $placeholders[":$key"] = $value;
      } 

      // execute
      $p->execute($placeholders);

    }
    // free RAM
    unset($p);
    unset($db);
    unset($placeholders);

  }

  public function history($type)
  {
    $db = $this->db;
    $p = $db->prepare('
      SELECT *
      FROM history_:type
      WHERE id = :id
      ORDER BY timestamp DESC
      LIMIT 40
    ');

    if ($type === self::TRANS_BUY)
      $rows = $p->execute([':id' => $this->user, ':type' => 'buy'])->fetch(PDO::FETCH_ASSOC);
    else if ($type === self::TRANS_SELL)
      $rows = $p->execute([':id' => $this->user, ':type' => 'sell'])->fetch(PDO::FETCH_ASSOC);
    else
      return false;

    unset($db);

    return $rows;

  }


}
