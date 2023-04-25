<?php defined ('BASEPATH') or exit ( 'No direct script access allowed' );

$route['products']['GET']  = 'Products_controller/FSxCPDTDataListview';
$route['products/create']  = 'Products_controller/FSxCPDTAddData';
$route['products/edit/(:num)']  = 'Products_controller/FSxCPDTEditData/$1';
$route['products/delete/(:num)']  = 'Products_controller/FSxCPDTDeleteData/$1';


//API
include_once(APPPATH . 'route/api.php');