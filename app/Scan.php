<?php

namespace App;

class Scan
{
    public function __construct(
        $waybill_number,
        $type_of_scan,
        $site_of_scan,
        $last_site,
        $next_site,
        $time_of_scan,
        $scanner
    ) {

        $this->waybill_number = $waybill_number;
        $this->type_of_scan = $type_of_scan;
        $this->site_of_scan = $site_of_scan;
        $this->last_site = $last_site;
        $this->next_site = $next_site;
        $this->time_of_scan = $time_of_scan;
        $this->scanner = $scanner;
    }

    public $waybill_number;
    public $type_of_scan;
    public $site_of_scan;
    public $last_site;
    public $next_site;
    public $time_of_scan;
    public $scanner;
}
