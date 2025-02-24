<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$active_group = 'default';
$active_record = TRUE;

# ============ LIVE SERVER
// $db['live']['hostname'] = '172.16.161.37';
// $db['live']['port'] = '3306';
// $db['live']['username'] = 'root';
// $db['live']['password'] = 'itprog2013';
// $db['live']['database'] = 'agc-pms';
// $db['live']['dbdriver'] = 'mysqli';
// $db['live']['dbprefix'] = '';
// $db['live']['pconnect'] = TRUE;
// $db['live']['db_debug'] = FALSE;
// $db['live']['cache_on'] = FALSE;
// $db['live']['cachedir'] = '';
// $db['live']['char_set'] = 'utf8';
// $db['live']['dbcollat'] = 'utf8_general_ci';
// $db['live']['swap_pre'] = '';
// $db['live']['autoinit'] = TRUE;
// $db['live']['stricton'] = FALSE;

# ============ FOR ICM, ALTA, PM, TUBIGON, AM
$db['default']['hostname'] = '172.16.170.10';
$db['default']['port'] 	   = '3306';
$db['default']['username'] = 'leasing';
$db['default']['password'] = 'leasing2023';
$db['default']['database'] = 'z-agc-pms-cas';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

// gwaps ====================================================
$db['pis']['hostname'] = '172.16.161.34';
$db['pis']['port'] = '3307';
$db['pis']['username'] = 'pis';
$db['pis']['password'] = 'itprog2013';
$db['pis']['database'] = 'pis';
$db['pis']['dbdriver'] = 'mysqli';
$db['pis']['dbprefix'] = '';
$db['pis']['pconnect'] = FALSE;
$db['pis']['db_debug'] = ENVIRONMENT !== 'production';
$db['pis']['cache_on'] = FALSE;
$db['pis']['cachedir'] = '';
$db['pis']['char_set'] = 'utf8';
$db['pis']['dbcollat'] = 'utf8_general_ci';
$db['pis']['swap_pre'] = '';
$db['pis']['autoinit'] = TRUE;
$db['pis']['stricton'] = FALSE;
// gwaps end ================================================

// $db['agc_cas']['hostname'] = '172.16.161.37';
// $db['agc_cas']['port'] = '3306';
// $db['agc_cas']['username'] = 'root';
// $db['agc_cas']['password'] = 'itprog2013';
// // $db['agc_cas']['database'] = 'agc-pms-cas';
// $db['agc_cas']['database'] = 'z-agc-pms-cas';
// $db['agc_cas']['dbdriver'] = 'mysqli';
// $db['agc_cas']['dbprefix'] = '';
// $db['agc_cas']['pconnect'] = TRUE;
// $db['agc_cas']['db_debug'] = FALSE;
// $db['agc_cas']['cache_on'] = FALSE;
// $db['agc_cas']['cachedir'] = '';
// $db['agc_cas']['char_set'] = 'utf8';
// $db['agc_cas']['dbcollat'] = 'utf8_general_ci';
// $db['agc_cas']['swap_pre'] = '';
// $db['agc_cas']['autoinit'] = TRUE;
// $db['agc_cas']['stricton'] = FALSE;

# ============= FOR TALIBON
// $db['default']['hostname'] = '172.16.170.10';
// $db['default']['port']     = '3306';
// $db['default']['username'] = 'leasing';
// $db['default']['password'] = 'leasing2023';
// $db['default']['database'] = 'agc-pms-cas-talibon';
// $db['default']['dbdriver'] = 'mysqli';
// $db['default']['dbprefix'] = '';
// $db['default']['pconnect'] = TRUE;
// $db['default']['db_debug'] = FALSE;
// $db['default']['cache_on'] = FALSE;
// $db['default']['cachedir'] = '';
// $db['default']['char_set'] = 'utf8';
// $db['default']['dbcollat'] = 'utf8_general_ci';
// $db['default']['swap_pre'] = '';
// $db['default']['autoinit'] = TRUE;
// $db['default']['stricton'] = FALSE;

$db['consol']['hostname'] = '172.16.43.75';
$db['consol']['port'] = '3306';
$db['consol']['username'] = 'root';
$db['consol']['password'] = '';
$db['consol']['database'] = 'consolidation_db';
$db['consol']['dbdriver'] = 'mysqli';
$db['consol']['dbprefix'] = '';
$db['consol']['pconnect'] = TRUE;
$db['consol']['db_debug'] = FALSE;
$db['consol']['cache_on'] = FALSE;
$db['consol']['cachedir'] = '';
$db['consol']['char_set'] = 'utf8';
$db['consol']['dbcollat'] = 'utf8_general_ci';
$db['consol']['swap_pre'] = '';
$db['consol']['autoinit'] = TRUE;
$db['consol']['stricton'] = FALSE;

$db['talibon-cas']['hostname'] = '172.16.170.10';
$db['talibon-cas']['port'] = '3306';
$db['talibon-cas']['username'] = 'leasing';
$db['talibon-cas']['password'] = 'leasing2023';
$db['talibon-cas']['database'] = 'agc-pms-cas-talibon';
$db['talibon-cas']['dbdriver'] = 'mysqli';
$db['talibon-cas']['dbprefix'] = '';
$db['talibon-cas']['pconnect'] = TRUE;
$db['talibon-cas']['db_debug'] = FALSE;
$db['talibon-cas']['cache_on'] = FALSE;
$db['talibon-cas']['cachedir'] = '';
$db['talibon-cas']['char_set'] = 'utf8';
$db['talibon-cas']['dbcollat'] = 'utf8_general_ci';
$db['talibon-cas']['swap_pre'] = '';
$db['talibon-cas']['autoinit'] = TRUE;
$db['talibon-cas']['stricton'] = FALSE;

$db['talibon']['hostname'] = '172.16.90.220';
$db['talibon']['port'] = '3306';
$db['talibon']['username'] = 'root';
$db['talibon']['password'] = 'itprog2013';
$db['talibon']['database'] = 'leasing_talibon';
$db['talibon']['dbdriver'] = 'mysqli';
$db['talibon']['dbprefix'] = '';
$db['talibon']['pconnect'] = TRUE;
$db['talibon']['db_debug'] = FALSE;
$db['talibon']['cache_on'] = FALSE;
$db['talibon']['cachedir'] = '';
$db['talibon']['char_set'] = 'utf8';
$db['talibon']['dbcollat'] = 'utf8_general_ci';
$db['talibon']['swap_pre'] = '';
$db['talibon']['autoinit'] = TRUE;
$db['talibon']['stricton'] = FALSE;


#HOSTGATOR
// $db['portal']['hostname'] = '50.116.94.177';
// $db['portal']['port'] = '3306';
// $db['portal']['username'] = 'duxvwc44_agc-pms';
// $db['portal']['password'] = '+AgIKfdyaC0d';
// $db['portal']['database'] = 'duxvwc44_agc-pms';
// $db['portal']['dbdriver'] = 'mysqli';
// $db['portal']['dbprefix'] = '';
// $db['portal']['pconnect'] = TRUE;
// $db['portal']['db_debug'] = FALSE;
// $db['portal']['cache_on'] = FALSE;
// $db['portal']['cachedir'] = '';
// $db['portal']['char_set'] = 'utf8';
// $db['portal']['dbcollat'] = 'utf8_general_ci';
// $db['portal']['swap_pre'] = '';
// $db['portal']['autoinit'] = TRUE;
// $db['portal']['stricton'] = FALSE;
$db['portal']['hostname'] = '172.16.170.10';
$db['portal']['port'] = '3306';
$db['portal']['username'] = 'leasing';
$db['portal']['password'] = 'leasing2023';
$db['portal']['database'] = 'duxvwc44_agc-pms';
$db['portal']['dbdriver'] = 'mysqli';
$db['portal']['dbprefix'] = '';
$db['portal']['pconnect'] = TRUE;
$db['portal']['db_debug'] = FALSE;
$db['portal']['cache_on'] = FALSE;
$db['portal']['cachedir'] = '';
$db['portal']['char_set'] = 'utf8';
$db['portal']['dbcollat'] = 'utf8_general_ci';
$db['portal']['swap_pre'] = '';
$db['portal']['autoinit'] = TRUE;
$db['portal']['stricton'] = FALSE;


// $db['CCM_DB']['hostname'] = '172.16.161.43';
// $db['CCM_DB']['port']     = '3307';
// $db['CCM_DB']['username'] = 'ccm2021';
// $db['CCM_DB']['password'] = 'ITPROG_CCM_2021';
// $db['CCM_DB']['database'] = 'ccm';
// $db['CCM_DB']['dbdriver'] = 'mysqli';
// $db['CCM_DB']['dbprefix'] = '';
// $db['CCM_DB']['pconnect'] = TRUE;
// $db['CCM_DB']['db_debug'] = FALSE;
// $db['CCM_DB']['cache_on'] = FALSE;
// $db['CCM_DB']['cachedir'] = '';
// $db['CCM_DB']['char_set'] = 'utf8';
// $db['CCM_DB']['dbcollat'] = 'utf8_general_ci';
// $db['CCM_DB']['swap_pre'] = '';
// $db['CCM_DB']['autoinit'] = TRUE;
// $db['CCM_DB']['stricton'] = FALSE;


$db['CCM_DB']['hostname'] = '172.16.43.75';
$db['CCM_DB']['port'] = '3306';
$db['CCM_DB']['username'] = 'root';
$db['CCM_DB']['password'] = '';
$db['CCM_DB']['database'] = 'ccm';
$db['CCM_DB']['dbdriver'] = 'mysqli';
$db['CCM_DB']['dbprefix'] = '';
$db['CCM_DB']['pconnect'] = TRUE;
$db['CCM_DB']['db_debug'] = FALSE;
$db['CCM_DB']['cache_on'] = FALSE;
$db['CCM_DB']['cachedir'] = '';
$db['CCM_DB']['char_set'] = 'utf8';
$db['CCM_DB']['dbcollat'] = 'utf8_general_ci';
$db['CCM_DB']['swap_pre'] = '';
$db['CCM_DB']['autoinit'] = TRUE;
$db['CCM_DB']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */