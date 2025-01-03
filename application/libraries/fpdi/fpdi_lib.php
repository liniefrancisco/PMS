<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'libraries/fpdi/autoload.php');

class Fpdi_lib extends \setasign\Fpdi\Fpdi {
    public function __construct() {
        parent::__construct();
    }
}