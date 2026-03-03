<?php namespace Dplus\Databases\Dplus;
// ProcessWire
use ProcessWire\WireDatabasePDO;
// App
use App\ProcessWire\Database\DatabaseQuerySelect as ParentDatabaseQuerySelect;


class DatabaseQuerySelect extends ParentDatabaseQuerySelect {
	public function database() : WireDatabasePDO
	{
		return $this->wire('db-dplus')->pw->pdo;
	}
}