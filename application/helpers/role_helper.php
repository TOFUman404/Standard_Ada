<?php
    /**
     * Functionality : Check user in role
     * Parameters : $ptCardTypeCode
     * Creator : 18/1/2019 Piya(tiger)
     * Last Modified : -
     * Return : Status true is have user, false is empty user
     * Return Type : Boolean
    */
    function FCNbIsHaveUserInRole($ptRoleCode){
        $ci = &get_instance();
        $ci->load->database();
        
        $bHaveUser = false;
        
        $tSQL = "SELECT USR.FTUsrCode
                FROM TCNMUser USR
                WHERE USR.FTRolCode = '$ptRoleCode'";
        
        $oQuery = $ci->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $bHaveUser = true;
        }
        
        return $bHaveUser;
    }
