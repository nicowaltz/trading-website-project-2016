<?php

namespace nFinance\Bot;
use Sunra\PhpSimple\HtmlDomParser as Dom;
 
class StockBot
{
	
	protected $page;
	protected $symbol;
	public function __construct($symbol) {
		Dom::file_get_html('https://finance.yahoo.com/q?s=' . $stock);
	}

	public function retrieve() {
		// find('.rtq-div .time-rtq-content .yfi-price-change-{test}')->innertext
		// find('.rtq-div .time-rtq-ticker span')->innertext
		// str_replace(find('.rtq-div .hd .title h2')->innertext, '(' . $this->symbol . ')', '')
	}
}
