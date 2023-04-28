<?php defined ('BASEPATH') or exit ( 'No direct script access allowed' );

$route['products']['GET']  = 'testproject/Products_controller/FSxCPDTDataListview';
$route['products/create']  = 'testproject/Products_controller/FSxCPDTAddData';
$route['products/edit/(:num)']  = 'testproject/Products_controller/FSxCPDTEditData/$1';
$route['products/delete/(:num)']  = 'testproject/Products_controller/FSxCPDTDeleteData/$1';

$route['changeLanguage']['POST']  = 'testproject/Main_controller/FStCMANChangeLng';


$route['test'] = 'testproject/main_test/test_main';


//API
include_once(APPPATH . 'route/api.php');