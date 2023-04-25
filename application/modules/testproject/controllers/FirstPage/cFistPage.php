<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cFistPage extends MX_Controller {

    public function __construct() {
        parent::__construct (); 
        
        //session set lang
        $this->session->set_userdata ("lang", 'TH');
    }

    /*
    * Functionality : Get System Configuration
    * Parameters : $paAppCode, $paConfigGroupCode
    * Creator : 5/3/2019 piya(tiger)
    * Last Modified : -
    * Return : Configuration List
    * Return Type : array
    */
	public function index() {
        $this->load->view('common/FirstPage/wWellcome');
    }

}

