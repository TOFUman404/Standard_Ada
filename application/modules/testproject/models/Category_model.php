<?php

class Category_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function FSaMCATGetCategory($pId = FALSE)
    {
        if ($pId === FALSE) {
            $oQuery = $this->db->select('FNCatId as id , FNCatName as name')
                                ->from('TTPMCategory')
                                ->get();
            return $oQuery->result_array();
        }

        $oQuery = $this->db->select('FNCatId as id , FNCatName as name')
                            ->from('TTPMCategory')
                            ->where('FNCatId', $pId)
                            ->get();
        return $oQuery->row_array();
    }

//    public function set_category($data)
//    {
//        $this->db->insert('TMCats', $data);
//        return ($this->db->affected_rows() != 1) ? false : true;
//    }
}