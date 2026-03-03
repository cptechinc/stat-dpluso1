<?php namespace App\Dplus\Salesrep\YearOverYear;
// App
use App\Dplus\Salesrep\YearOverYear\Data\Yoy\Customer;

class CustomerFetcher extends YoyFetcher {
    protected static $instance;

    public function fetch(string $custid) {
        if ($this->fetcher->exists($this->filename()) === false) {
            return false;
        }
        $json = $this->fetchJson();
        
        if ($json['jsonerror'] || array_key_exists($custid, $json) === false) {
            return false;
        }
        $customer = new Customer();
        $customer->custid = $custid;
        $customer->setFromJson($json[$custid]);
        return $customer;
    }
}