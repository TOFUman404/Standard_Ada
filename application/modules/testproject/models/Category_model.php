<?php

class Category_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function FSaMCATGetCategory($id = FALSE)
    {
        if ($id === FALSE) {
            $query = $this->db->select('FNCatId as id , FNCatName as name')
                                ->from('TTPMCategory')
                                ->get();
            return $query->result_array();
        }

        $query = $this->db->select('FNCatId as id , FNCatName as name')
                            ->from('TTPMCategory')
                            ->where('FNCatId', $id)
                            ->get();
        return $query->row_array();
    }

//    public function set_category($data)
//    {
//        $this->db->insert('TMCats', $data);
//        return ($this->db->affected_rows() != 1) ? false : true;
//    }
}