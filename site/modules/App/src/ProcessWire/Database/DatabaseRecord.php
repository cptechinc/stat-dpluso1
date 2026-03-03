<?php namespace App\ProcessWire\Database;
// ProcessWire
use ProcessWire\WireData;


class DatabaseRecord extends WireData {
	const PRIMARYKEY = [];
	const GLUE = '|';

	public function primarykey() : array
	{
		$keys = [];

		foreach (static::PRIMARYKEY as $key) {
			$keys[] = $this->$key;
		}
		return $keys;
	}

	public function primarykeyString() : string
	{
		return implode(static::GLUE, $this->primarykey());
	}
}