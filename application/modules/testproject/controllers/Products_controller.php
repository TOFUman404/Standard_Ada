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
        $data['products'] = $this->products_model->FSaMPDTGetProducts();
        $data['title'] = 'Products archive';
        $this->load->view('templates/wHeader', $data);
        $this->load->view('products/script/wIndexHeader');
        $this->load->view('products/wProductList', $data);
        $this->load->view('products/script/wIndexFooter');
        $this->load->view('templates/wFooter');
        $this->load->view('products/script/wIndexJs');
    }

    public function FSxCPDTAddData()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('image_lib');
        $data['title'] = 'Add a product';
        $this->form_validation->set_rules('oetProductName', 'oetProductName', 'required');
        $this->form_validation->set_rules('oetProductPrice', 'oetProductPrice', 'required');
        $this->form_validation->set_rules('otaProductDesc', 'otaProductDesc', 'required');
        $data['categories'] = $this->category_model->FSaMCATGetCategory();
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/wHeader', $data);
            $this->load->view('products/wProductCreate');
            $this->load->view('templates/wFooter');
        }
        else
        {
            $dataArray = [
                'FTPrdCode' => $this->input->post_get('oetProductCode', true),
                'FTPrdName' => $this->input->post_get('oetProductName', true),
                'FCPrdPrice' => $this->input->post_get('oetProductPrice', true),
                'FTPrdDescription' => $this->input->post_get('otaProductDesc', true),
                'FTPrdImage' => '',
                'FNPrdCatId' => (int) $this->input->post_get('ocmProductCategory', true),
                'FDPrdCreated_at' => date('Y-m-d H:i:s'),
                'FDPrdUpdated_at' => date('Y-m-d H:i:s'),
            ];
            $imagePath = 'assets/img/';
            $config['upload_path'] = $imagePath;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['encrypt_name'] = TRUE;
//            $config['max_size'] = 100;
//            $config['max_width'] = 1024;
//            $config['max_height'] = 768;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('oflProductImage')) {
                $error = array('error' => $this->upload->display_errors());
                $this->load->view('products/wProductCreate', $error);
            } else {
                $dataArray['FTPrdImage'] = $this->upload->data()['file_name'];
                $config['image_library'] = 'gd2';
                $config['source_image'] = $imagePath . $dataArray['image'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 200;
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
            }
            $this->products_model->FSbMPDTSetProducts($dataArray);
            redirect('/products', 'refresh');
        }
    }

    public function FSxCPDTEditData($id)
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $data['title'] = 'Edit a product';
        $data['product'] = $this->products_model->FSaMPDTGetProducts($id);
        $data['categories'] = $this->category_model->FSaMCATGetCategory();
        $this->form_validation->set_rules('oetProductName', 'oetProductName', 'required');
        $this->form_validation->set_rules('oetProductPrice', 'oetProductPrice', 'required');
        $this->form_validation->set_rules('otaProductDesc', 'otaProductDesc', 'required');
        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('templates/wHeader', $data);
            $this->load->view('products/wProductEdit', $data);
            $this->load->view('templates/wFooter');
        }
        else {
            $dataArray = [
                'name' => $this->input->post_get('oetProductName', true) ?? $data['product']['name'],
                'price' => $this->input->post_get('oetProductPrice', true) ?? $data['product']['price'],
                'description' => $this->input->post_get('otaProductDesc', true) ?? $data['product']['description'],
                'image' => $data['product']['image'],
                'category_id' => $this->input->post_get('ocmProductCategory', true) ?? $data['product']['category_id'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $imagePath = 'assets/img/';
            $config['upload_path'] = $imagePath;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['encrypt_name'] = TRUE;
//            $config['max_size'] = 100;
//            $config['max_width'] = 1024;
//            $config['max_height'] = 768;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('oflProductImage')) {
                $error = array('error' => $this->upload->display_errors());
                $this->load->view('products/wProductEdit', $error);
            } else {
                $dataArray['image'] = $this->upload->data()['file_name'];
            }
            $this->products_model->FSbMPDTPatchProducts($id, $dataArray);
            redirect('/wProductList', 'refresh');
        }
    }

    public function FSxCPDTDeleteData($id)
    {
        $product = $this->products_model->FSaMPDTGetProducts($id);
        $this->products_model->FSbMPDTDeleteProducts($id);
        $imagePath = 'assets/img/';
        if(file_exists($imagePath.$product['image'])){
            unlink($imagePath.$product['image']);
        }
        redirect('/wProductList', 'refresh');
    }

    public function FStCPDTDataList()
    {
        header('Access-Control-Allow-Origin:' . base_url());
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $draw = intval($_POST['draw']);
        $whereQuery = [
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
        $query = $this->products_model->FSoMPDTGetProductsList($whereQuery);
        $data = [];
        foreach ($query->result() as $r) {
            $data[] = [
                'id' => $r->FNPrdId,
                'FTPrdCode' => $r->FTPrdCode,
                'FTPrdName' => $r->FTPrdName,
                'FCPrdPrice' => $r->FCPrdPrice,
                'FTPrdDescription' => $r->FTPrdDescription,
                'FTPrdImage' => '<img src="' . base_url('assets/img/' . $r->FTPrdImage) . '" width="100" height="100" onerror="imgError(this);" />',
                'FNCatName' => $r->FNCatName,
                'FDPrdUpdated_at' => $r->FDPrdUpdated_at,
                'actions' => '<a href="' . site_url('products/edit/' . $r->FNPrdId) . '" class="btn btn-primary btn-sm">Edit</a>
                <a href="' . site_url('products/delete/' . $r->FNPrdId) . '" class="btn btn-danger btn-sm">Delete</a>'
            ];
        }
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $query->num_rows(),
            "recordsFiltered" => $this->products_model->FSoMPDTGetAllPorductsCount($whereQuery),
            "data" => $data,
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }
}