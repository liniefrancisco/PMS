<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "welcome";
$route['404_override']       = '';
$route['tenants_contract']   = "leasing_reports/tenants_contract";

# DOCUMENT ROUTING
$route['getDocuments']  = "leasing_transaction/getDocuments";
$route['getDocuments2'] = "leasing_transaction/getDocuments2";
# RENTAL DEPOSIT SUMMARY
$route['rentaldepositsummary'] = 'ctrl_cfs/rentaldepositsummary';
$route['savesummary']          = 'ctrl_cfs/savesummary';
$route['showdenomitable']      = 'ctrl_cfs/showdenomitable';
# NEW TENANTS WHT
$route['tenantswithholding']         = "leasing_reports/tenantswithholding";
$route["gettenantwht/(:any)/(:any)"] = "leasing_reports/gettenantwht/$1/$2";
# NEW TENANTS WHT
$route['tenantswithtin']                    = "leasing_reports/tenantswithtin";
$route["gettenanttin/(:any)/(:any)/(:any)"] = "leasing_reports/gettenanttin/$1/$2/$3";




#TESTING
$route['test'] = 'testing/test';

/* End of file routes.php */
/* Location: ./application/config/routes.php */