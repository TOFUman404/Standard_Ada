<?php

class Products_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function FSaMPDTGetProducts($pId = FALSE)
    {
        if ($pId === FALSE) {
            $oQuery = $this->db->get('TTPMProduct');
            return $oQuery->result_array();
        }

        $oQuery = $this->db->get_where('TTPMProduct', array('FNPrdId' => $pId));
        return $oQuery->row_array();
    }
    public function FSbMPDTSetProducts($pData)
    {
        $this->db->insert('TTPMProduct', $pData);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    public function FSbMPDTPatchProducts($pId,$pData)
    {
        $this->db->where('FNPrdId', $pId);
        $this->db->update('TTPMProduct', $pData);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    public function FSbMPDTDeleteProducts($pId)
    {
        $this->db->where('FNPrdId', $pId);
        $this->db->delete('TTPMProduct');
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    private function FSoMPDTGetProductsListQuery($pQuery = FALSE)
    {
        $oQuery = $this->db->select('TTPMProduct.FNPrdId, TTPMProduct.FTPrdCode, TTPMProduct.FTPrdName, TTPMProduct.FCPrdPrice, TTPMProduct.FTPrdImage, TTPMProduct.FTPrdDescription, TTPMProduct.FDPrdUpdated_at, TTPMCategory.FNCatName')
            ->from('TTPMProduct')
            ->join('TTPMCategory', 'TTPMCategory.FNCatId = TTPMProduct.FNPrdCatId', 'left');

        if($pQuery['length'] != -1){
            $oQuery->limit($pQuery['length'], $pQuery['start']);
        }

        if($pQuery['search'] != ''){
            $this->db->group_start();
            $oQuery->like('TTPMProduct.FTPrdName', $pQuery['search']);
            $oQuery->or_like('TTPMProduct.FTPrdCode', $pQuery['search']);
            $this->db->group_end();
        }

        if($pQuery['category']){
            $oQuery->where('TTPMProduct.FNPrdCatId', $pQuery['category']);
        }

        if($pQuery['dateStart']){
            $oQuery->where('TTPMProduct.FDPrdUpdated_at >=', $pQuery['dateStart'] . ' 00:00:00');
        }

        if ($pQuery['dateEnd']) {
            $oQuery->where('TTPMProduct.FDPrdUpdated_at <=', $pQuery['dateEnd'] . ' 23:59:59');
        }

        if($pQuery['order']){
            $oQuery->order_by($pQuery['order']['column'], $pQuery['order']['dir']);
        }

            return $oQuery;
    }

    public function FSoMPDTGetProductsList($pQuery = FALSE)
    {
        return $this->FSoMPDTGetProductsListQuery($pQuery)->get();
    }

    public function FSaMPDTGetLastMonthHistory()
    {
        $tSql = "SELECT dates.date_series, SUM(COALESCE(products.count,0)) AS count_product
                    FROM
                    generate_series_last1month() as dates
                    LEFT JOIN
                    (
                        SELECT CAST(TTPMProduct.FDPrdCreated_at AS DATE) AS date , COUNT(*) AS count
                        FROM TTPMProduct
                        group by TTPMProduct.FDPrdCreated_at) AS products
                    ON dates.date_series = products.date
                    GROUP BY dates.date_series
                    ORDER BY dates.date_series";
        return $this->db->query($tSql)->result_array();
    }

    public function FSoMPDTGetAllPorductsCount($pQuery): int
    {
        $pQuery['length'] = -1;
        $this->FSoMPDTGetProductsListQuery($pQuery);
        return $this->db->count_all_results();
    }
}