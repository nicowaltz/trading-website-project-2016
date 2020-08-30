<?php

namespace nFinance\Managers;

use nFinance\Machines\Stock;
use PDO;

class Holdings {
  
  protected $db;
  protected $cash;
  protected $history;
  
  public function __construct($db, $cash, $history) {
    $this->db = $db;
    $this->cash = $cash;
    $this->history = $history;
  }
  
  public function buy($symbol, $amount) { 
    $stock = new Stock($symbol);
    if (! $stock->lookup() || ! $stock->exists) 
      return false; 
    
    $cost = $amount * $stock->price;
    if (! $this->cash->check($cost)) 
      return false;
        
        $res = $this->get($stock->symbol);
        
        if ($res === false) {
            if(! $this->cash->spend($cost)) 
                return false;
            
            // insert
            $this->insert([
                'symbol' => $stock->symbol,
                'initial' => $cost, 
                'shares' => $amount
            ]);
            

        } else {
          if(! $this->cash->spend($cost)) 
                return false;
            //update
            $this->update([
                'symbol' => $stock->symbol,
                'initial' => $res['initial_payment'] + $cost,
                'shares' => $res['shares'] + $amount
            ]);
        }
      // info =  id, symbol, shares, price, cost
        $this->history->record(History::TRANS_BUY, [
          'symbol' => $stock->symbol,
          'shares' => $amount,
          'cost' => $cost,
          'price' => $stock->price
        ]);

      return true;
  }
  
  public function sell($symbol) {
      $stock = new Stock($symbol);
      if (! $stock->lookup() || ! $stock->exists)
          return false;
     
      try {
          $shares = $this->get($stock->symbol)['shares'];
      } catch (\Exception $e) {
          return false;
      }
      
      $value = $shares * $stock->price;
      $this->cash->earn($value);
      $this->delete($stock->symbol);
      $this->history->record(History::TRANS_SELL, [
        'symbol' => $stock->symbol,
        'amount' => $value,
        'price' => $stock->price
      ]);

      return true;
      
  }
  
  public function portfolio() {
      $holdings = $this->holdings();
        $portfolioData = [];
        if (empty($holdings))
          return [];
    
        foreach ($holdings as $key => $holding) {
            $stock = new Stock($holding['symbol']);
            
            if (! $stock->lookup()) 
                return [];
         
            $portfolioData[$key] = $holding;
            $portfolioData[$key]['name'] = $stock->name;
            $change = $stock->change;
            $portfolioData[$key]['change'] = $change;
            $portfolioData[$key]['change-sgn'] = $change >= 0 ? 'positive' : 'negative';
            $portfolioData[$key]['charturl'] = $stock->charturl;
            $portfolioData[$key]['price'] = $stock->price;
            $total = floatval(floatval($stock->price) * $holding['shares']);
            $portfolioData[$key]['total'] = $total;
            $difference = $total - $holding['initial_payment'];
            $portfolioData[$key]['difference'] = $difference;
            $portfolioData[$key]['difference-sgn'] = $difference >= 0 ? 'positive' : 'negative';
            unset($stock);
        }
        unset($holdings);
      return $portfolioData;
  }
  
  // symbol, initial payment, shares
  private function insert(array $details = []) {
    if (empty($details)) return false; 
    
    $p = $this->db->prepare('
      INSERT INTO holdings 
      (user_id, symbol, initial_payment, shares, created_at)
      VALUES(:userid, :symbol, :initial, :shares, NOW())
    ');
    $p->execute([
        ':userid' => $_SESSION['auth'], 
        ':symbol' => $details['symbol'], 
        ':initial' => $details['initial'],
        ':shares' => $details['shares'] 
    ]);

    

    


    unset($p);
    return true;
  } 
  
  // symbol, initial payment, new shares
  private function update(array $details = []) {
    if(empty($details))
        return false;
        
    $p = $this->db->prepare('
        UPDATE holdings
        SET 
        shares = :shares,
        updated_at = NOW(),
        initial_payment = :initial
        WHERE user_id = :userid AND symbol = :symbol
    ');
    
    $p->execute([
        ':shares' => $details['shares'], 
        ':initial' => $details['initial'], 
        ':symbol' => $details['symbol'], 
        ':userid' => $_SESSION['auth']
    ]);
    
    
    unset($p);
    return true; 
  }
  
  private function delete($symbol) {
    $p = $this->db->prepare('DELETE FROM holdings WHERE symbol = :symbol AND user_id = :userid');
    $p->execute([':symbol' => $symbol, ':userid' => $_SESSION['auth']]);
    
    unset($p);
    return true;
  }
  
  private function get($symbol) {
      $p = $this->db->prepare('SELECT * FROM holdings WHERE symbol = :symbol AND user_id = :userid');
      $p->execute([':symbol' => $symbol, ':userid' => $_SESSION['auth']]);
      $res = $p->fetch(PDO::FETCH_ASSOC);
      
      if (empty($res)) 
          return false;
        
        unset($p);
        return $res;
  }
  
  public function holdings() {
    $p = $this->db->prepare('SELECT * FROM holdings WHERE user_id = :userid');
      $p->execute([':userid' => $_SESSION['auth']]);
      $holdings = $p->fetchAll(PDO::FETCH_ASSOC);
      
      if (empty($holdings)) 
          return false;
        
        return $holdings;
  }
    
}   