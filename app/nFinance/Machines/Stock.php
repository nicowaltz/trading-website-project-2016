<?php

namespace nFinance\Machines;

class Stock {
  public $symbol;
  public $name;
  public $price;
  public $change;
  public $charturl;
  public $exists;

  public function __construct($symbol) {
    $this->symbol = strtoupper($symbol);
  }

  public function lookup() {

      if (preg_match("/^\^/", $this->symbol) || preg_match("/,/", $this->symbol) || empty($this->symbol)) return false;

      $headers = [
          'Accept' => '*/*',
          'Connection' => 'Keep-Alive',
          'User-Agent' => sprintf("curl/%s", curl_version()['version'])
      ];
      $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'headers' => implode(array_map(function($value, $key) { return sprintf("%s: %s\r\n", $key, $value); }, $headers, array_keys($headers)))
        ]
      ]);

      $handle = @fopen("http://download.finance.yahoo.com/d/quotes.csv?f=nl1c1&s={$this->symbol}", "r", false, $context);
      if (! $handle)
        return false;

      $data = fgetcsv($handle);

      $this->name = $data[0];
      $this->price = $data[1];
      $this->change = $data[2];
      $this->charturl = "https://chart.finance.yahoo.com/t?s={$this->symbol}&lang=de-DE&region=DE&width=300&height=180";
      $this->exists = $data[0] !== 'N/A';



      fclose($handle);

      unset($handle);
      unset($data);

      return true;
  }

  public function info() {
    return [
      'symbol' => $this->symbol,
      'change' => $this->change,
      'price' => $this->price,
      'charturl' => $this->charturl,
      'csgn' => $this->change >= 0 ? 'positive' : 'negative',
      'name' => $this->name,
      'exists' => $this->exists
    ];
  }

}

