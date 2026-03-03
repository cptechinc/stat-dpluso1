<?php namespace App\Dplus\Salesrep\YearOverYear\Data\Yoy;
// App
use App\Data\AbstractJsonData;

/**
 * @property string $month e.g january
 * @property string $date e.g 2026-01-01
 * 
 * @see example-json/yoy-customer-month.json for structure
 */
class Month extends AbstractJsonData {
    const FIELDS_BOOL   = [];
    const FIELDS_STRING = ['month', 'date'];

    private static $years = 3;


    public function __construct() {
        parent::__construct();

        $nbrOfYears  = self::$years;
        $currentYear = intval(date('Y'));

        for ($i = ($currentYear - $nbrOfYears); $i <= $currentYear; $i++) {
            $this->set($i, null);
        }
    }

    public function setFromJson(array $data) : void 
    {
        parent::setFromJson($data);
        
        $nbrOfYears = self::$years;
        $currentYear = intval(date('Y'));

		for ($i = ($currentYear - $nbrOfYears); $i <= $currentYear; $i++) {
            if (array_key_exists("$i", $data) === false) {
                continue;
            }
			$this->set($i, $data["$i"]);
		}
    }
}