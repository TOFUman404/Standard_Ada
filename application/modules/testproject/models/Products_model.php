<?php

class Products_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function FSaMPDTGetProducts($id = FALSE)
    {
        if ($id === FALSE) {
            $query = $this->db->get('TTPMProduct');
            return $query->result_array();
        }

        $query = $this->db->get_where('TTPMProduct', array('FNPrdId' => $id));
        return $query->row_array();
    }
    public function FSbMPDTSetProducts($data)
    {
        $this->db->insert('TTPMProduct', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    public function FSbMPDTPatchProducts($id,$data)
    {
        $this->db->where('FNPrdId', $id);
        $this->db->update('TTPMProduct', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    public function FSbMPDTDeleteProducts($id)
    {
        $this->db->where('FNPrdId', $id);
        $this->db->delete('TTPMProduct');
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    public function FSoMPDTGetProductsListQuery($q = FALSE)
    {
        $query = $this->db->select('TTPMProduct.FNPrdId, TTPMProduct.FTPrdCode, TTPMProduct.FTPrdName, TTPMProduct.FCPrdPrice, TTPMProduct.FTPrdImage, TTPMProduct.FTPrdDescription, TTPMProduct.FDPrdUpdated_at, TTPMCategory.FNCatName')
            ->from('TTPMProduct')
            ->join('TTPMCategory', 'TTPMCategory.FNCatId = TTPMProduct.FNPrdCatId', 'left');

        if($q['length'] != -1){
            $query->limit($q['length'], $q['start']);
        }

        if($q['category']){
            $query->where('TTPMProduct.FNPrdCatId', $q['category']);
        }

        if($q['search'] != ''){
            $query->like('TTPMProduct.FTPrdName', $q['search']);
        }

        if($q['dateStart']){
            $query->where('TTPMProduct.FDPrdUpdated_at >=', $q['dateStart'] . ' 00:00:00');
        }

        if ($q['dateEnd']) {
            $query->where('TTPMProduct.FDPrdUpdated_at <=', $q['dateEnd'] . ' 23:59:59');
        }

        if($q['order']){
            $query->order_by($q['order']['column'], $q['order']['dir']);
        }

            return $query;
    }

    public function FSoMPDTGetProductsList($q = FALSE)
    {
        return $this->FSoMPDTGetProductsListQuery($q)->get();
    }

    public function FSoMPDTGetAllPorductsCount($q)
    {
        $q['length'] = -1;
        $this->FSoMPDTGetProductsListQuery($q);
        return $this->db->count_all_results();
    }
}