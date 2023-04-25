<?php

class Category_controller extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
        $this->load->helper('url_helper');
    }

    public function FStCCATDataList()
    {
        $data['categories'] = $this->category_model->FSaMCATGetCategory();
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data['categories']));
    }
}