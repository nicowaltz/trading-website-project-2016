<?php

namespace nFinance\Validation;

use PDO;
use Violin\Violin;
use Violin\Support\MessageBag;

class Validation extends Violin
{
	protected $db;

	public function __construct($db) {
		$this->db = $db;

		$this->addRuleMessages([
		    'required' => 'Bitte {field} eingeben',
		    'alnumDash' => 'Nur Buchstaben, Binde- oder Unterstriche und Zahlen sind erlaubt'
		]);

	}

	public function createMessages(array $messages) {
		return new MessageBag($messages);
	}


}
