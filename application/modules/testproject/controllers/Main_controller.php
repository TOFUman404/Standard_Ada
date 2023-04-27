<?php

class Main_controller extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function FStCMANChangeLng(){
        $tLng = $this->input->post_get('tLng', true);
        $this->session->set_userdata('language', $tLng);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => 'success', 'lang' => $tLng]));
    }
}