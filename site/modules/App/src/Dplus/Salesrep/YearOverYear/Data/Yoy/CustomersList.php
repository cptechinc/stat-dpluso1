<?php namespace App\Dplus\Salesrep\YearOverYear\Data\Yoy;
// App
use App\Data\AbstractJsonData;
use App\Data\AbstractJsonDataList;


class CustomersList extends AbstractJsonDataList {
    public function makeBlankItem() : AbstractJsonData
    {
        return new Customer();
    }

    public function toArray() : array
    {
        $list = [];

        foreach ($this as $id => $data) {
            $list[$id] = $data->toArray();
        }
        return $list;
    }

    public function setFromJson(array $data) : void
    {
        foreach ($data as $custid => $customerData) {
            $customer = $this->makeBlankItem();
            $customer->custid = $custid;
            $customer->setFromJson($customerData);
            $this->set($customer->custid, $customer);
        }
    }
}