<?php namespace App\Dplus\Salesrep\YearOverYear\Data;
// Dplus
use App\Data\AbstractJsonData;
use App\Dplus\Salesrep\YearOverYear\Data\Yoy\CustomersList;

/**
 * @property CustomersList $customers
 * 
 * @see example-json/yoy.json for structure
 */
class YearOverYear extends AbstractJsonData {
    public function __construct() {
        $this->customers = new CustomersList();
    }

    public function setFromJson(array $data) : void 
    {
        parent::setFromJson($data);
        $this->customers->setFromJson($data);
    }
}