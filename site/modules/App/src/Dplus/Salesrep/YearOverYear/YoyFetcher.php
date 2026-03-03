<?php namespace App\Dplus\Salesrep\YearOverYear;
// ProcessWire
use ProcessWire\WireData;
// App
use App\Dplus\Util\JsonDataFetcher;

/**
 * @property JsonDataFetcher $fetcher
 * @property string          $filename
 */
class YoyFetcher extends WireData {
    const JSONPREFIX = 'yoy-sales';
    const FILENAME_SYSTEM = 'system';

    protected static $instance;

    public static function instance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function __construct() {
        $this->fetcher = JsonDataFetcher::instance();
        $this->filename = $this->user->mainrole == 'sales-rep' ? $this->user->salespersonid : self::FILENAME_SYSTEM;
    }

    public function filename() : string 
    {
        return static::JSONPREFIX . '-' . $this->filename;
    }

    protected function fetchJson() : array
    {
        return $this->fetcher->fetch($this->filename());
    }
}