<?php namespace App\Dplus\Salesrep\YearOverYear\Data\Yoy;
// App
use App\Data\AbstractJsonData;
use App\Data\AbstractJsonDataList;

/**
 * 
 * 
 * {"january":{"month":"january","date":"2026-01-01","2023":201.33,"2024":167.79,"2025":null,"2026":null},"february":{"month":"february","date":"2026-02-01","2023":102.06,"2024":null,"2025":null,"2026":null}}
 */
class MonthsList extends AbstractJsonDataList {
    public function makeBlankItem() : AbstractJsonData
    {
        return new Month();
    }
    
    public function setFromJson(array $data) : void
    {
        foreach ($data as $monthData) {
            $month = $this->makeBlankItem();
            $month->setFromJson($monthData);
            $this->set($month->month, $month);
        }
    }
}