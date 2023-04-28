<?php
class Products_controller extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('category_model');
        $this->load->helper('url_helper');
    }
    public function FSxCPDTDataListview()
    {
        $tUserLng = $this->session->get_userdata('language');
        if (isset($tUserLng['language'])) {
            $this->lang->load('products/main_lang', $tUserLng['language']);
        } else {
            $this->lang->load('products/main_lang', 'en');
        }
        $aData['lang'] = $this->lang->language;
        $aData['products'] = $this->products_model->FSaMPDTGetProducts();
        $aData['title'] = 'Products archive';
        $this->load->view('templates/wHeader', $aData);
        $this->load->view('products/wProductList', $aData);
        $this->load->view('templates/wFooter');
        $this->load->view('products/script/wIndexJs');
    }

    public function FSxCPDTAddData()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('image_lib');
        if (isset($tUserLng['language'])) {
            $this->lang->load('products/form_lang', $tUserLng['language']);
        } else {
            $this->lang->load('products/form_lang', 'en');
        }
        $aData['lang'] = $this->lang->language;
        $aData['title'] = 'Add a product';
        $this->form_validation->set_rules('oetProductName', 'oetProductName', 'required');
        $this->form_validation->set_rules('oetProductPrice', 'oetProductPrice', 'required');
        $aData['categories'] = $this->category_model->FSaMCATGetCategory();
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/wHeader', $aData);
            $this->load->view('products/wProductCreate');
            $this->load->view('templates/wFooter');
        }
        else
        {
            $aDataArray = [
                'FTPrdCode' => $this->input->post_get('oetProductCode', true),
                'FTPrdName' => $this->input->post_get('oetProductName', true),
                'FCPrdPrice' => $this->input->post_get('oetProductPrice', true),
                'FTPrdDescription' => $this->input->post_get('otaProductDesc', true) ?? '',
                'FTPrdImage' => '',
                'FNPrdCatId' => (int) $this->input->post_get('ocmProductCategory', true),
                'FDPrdCreated_at' => date('Y-m-d H:i:s'),
                'FDPrdUpdated_at' => date('Y-m-d H:i:s'),
            ];
            $tImagePath = 'application/modules/testproject/assets/img/';
            $aConfig['upload_path'] = $tImagePath;
            $aConfig['allowed_types'] = 'gif|jpg|png';
            $aConfig['encrypt_name'] = TRUE;
//            $aConfig['max_size'] = 100;
//            $aConfig['max_width'] = 1024;
//            $aConfig['max_height'] = 768;
            $this->load->library('upload', $aConfig);
            if (!$this->upload->do_upload('oflProductImage')) {
                $aData['error'] = array('error' => $this->upload->display_errors());
                $this->load->view('products/wProductCreate', $aData);
            } else {
                $aDataArray['FTPrdImage'] = $this->upload->data()['file_name'];
                $aConfig['image_library'] = 'gd2';
                $aConfig['source_image'] = $tImagePath . $aDataArray['FTPrdImage'];
                $aConfig['maintain_ratio'] = TRUE;
                $aConfig['width'] = 200;
                $this->image_lib->initialize($aConfig);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
            }
            $this->products_model->FSbMPDTSetProducts($aDataArray);
            redirect('/products', 'refresh');
        }
    }

    public function FSxCPDTEditData($id)
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        if (isset($tUserLng['language'])) {
            $this->lang->load('products/form_lang', $tUserLng['language']);
        } else {
            $this->lang->load('products/form_lang', 'en');
        }
        $aData['lang'] = $this->lang->language;
        $aData['title'] = 'Edit a product';
        $aData['product'] = $this->products_model->FSaMPDTGetProducts($id);
        $aData['categories'] = $this->category_model->FSaMCATGetCategory();
        $this->form_validation->set_rules('oetProductName', 'oetProductName', 'required');
        $this->form_validation->set_rules('oetProductPrice', 'oetProductPrice', 'required');
//        $this->form_validation->set_rules('otaProductDesc', 'otaProductDesc', 'required');
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/wHeader', $aData);
            $this->load->view('products/wProductEdit', $aData);
            $this->load->view('templates/wFooter');
        }
        else {
            $aDataArray = [
                'FTPrdCode' => $this->input->post_get('oetProductCode', true) ?? $aData['product']['FTPrdCode'],
                'FTPrdName' => $this->input->post_get('oetProductName', true) ?? $aData['product']['FTPrdName'],
                'FCPrdPrice' => $this->input->post_get('oetProductPrice', true) ?? $aData['product']['FCPrdPrice'],
                'FTPrdDescription' => $this->input->post_get('otaProductDesc', true) ?? $aData['product']['FTPrdDescription'],
                'FTPrdImage' => $aData['product']['FTPrdImage'],
                'FNPrdCatId' => $this->input->post_get('ocmProductCategory', true) ?? $aData['product']['FNPrdCatId'],
                'FDPrdUpdated_at' => date('Y-m-d H:i:s'),
            ];
            $tImagePath = 'application/modules/testproject/assets/img/';
            $aConfig['upload_path'] = $tImagePath;
            $aConfig['allowed_types'] = 'gif|jpg|png';
            $aConfig['encrypt_name'] = TRUE;
//            $aConfig['max_size'] = 100;
//            $aConfig['max_width'] = 1024;
//            $aConfig['max_height'] = 768;
            $this->load->library('upload', $aConfig);
            if (!$this->upload->do_upload('oflProductImage')) {
                $error = array('error' => $this->upload->display_errors());
                $this->load->view('products/wProductEdit', $error);
            } else {
                $aDataArray['image'] = $this->upload->data()['file_name'];
            }
            $this->products_model->FSbMPDTPatchProducts($id, $aDataArray);
            redirect('/products', 'refresh');
        }
    }

    public function FSxCPDTDeleteData($id)
    {
        $aProduct = $this->products_model->FSaMPDTGetProducts($id);
        $this->products_model->FSbMPDTDeleteProducts($id);
        $tImagePath = 'assets/img/';
        if(file_exists($tImagePath.$aProduct['image'])){
            unlink($tImagePath.$aProduct['image']);
        }
        redirect('/products', 'refresh');
    }

    public function FStCPDTDataList()
    {
        header('Access-Control-Allow-Origin:' . base_url());
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $draw = intval($_POST['draw']);
        $aWhereQuery = [
            'category' => $this->input->post('searchCategory'),
            'dateStart' => $this->input->post('searchDateStart'),
            'dateEnd' => $this->input->post('searchDateEnd'),
            'search' => $this->input->post('searchInput'),
            'start' => $this->input->post('start'),
            'length' => $this->input->post('length'),
            'order' => [
                'column' => $_POST['columns'][(int) $_POST['order'][0]['column']]['data'],
                'dir' => $_POST['order'][0]['dir'],
            ],
        ];
        $oQuery = $this->products_model->FSoMPDTGetProductsList($aWhereQuery);
        $aData = [];
        foreach ($oQuery->result() as $oResult) {
            $aData[] = [
                'id' => $oResult->FNPrdId,
                'FTPrdCode' => $oResult->FTPrdCode,
                'FTPrdName' => $oResult->FTPrdName,
                'FCPrdPrice' => $oResult->FCPrdPrice,
                'FTPrdDescription' => $oResult->FTPrdDescription,
                'FTPrdImage' => '<img src="' . base_url('application/modules/testproject/assets/img/' . $oResult->FTPrdImage) . '" width="100" height="100" onerror="JSCNTPJimgError(this);" />',
                'FNCatName' => $oResult->FNCatName,
                'FDPrdUpdated_at' => $oResult->FDPrdUpdated_at,
                'actions' => '<a href="' . site_url('products/edit/' . $oResult->FNPrdId) . '" class="btn btn-primary btn-sm">Edit</a>
                <a href="' . site_url('products/delete/' . $oResult->FNPrdId) . '" class="btn btn-danger btn-sm">Delete</a>'
            ];
        }
        $aOutput = array(
            "draw" => $draw,
            "recordsTotal" => $oQuery->num_rows(),
            "recordsFiltered" => $this->products_model->FSoMPDTGetAllPorductsCount($aWhereQuery),
            "data" => $aData,
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($aOutput));
    }
}