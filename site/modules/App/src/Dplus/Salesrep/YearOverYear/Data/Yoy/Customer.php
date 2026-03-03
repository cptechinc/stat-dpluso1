<?php namespace App\Dplus\Salesrep\YearOverYear\Data\Yoy;
// App
use App\Data\AbstractJsonData;

/**
 * @property string     $custid  Customer ID
 * @property MonthsList $months
 * 
 * @see example-json/yoy-customer.json for structure
 */
class Customer extends AbstractJsonData {
    public function __construct() {
        $this->custid = '';
        $this->months = new MonthsList();
    }


    public function setFromJson(array $data) : void 
    {
        $this->months->setFromJson($data);
    }

    public function toArray() : array
    {
        return $this->months->toArray();
    }
}