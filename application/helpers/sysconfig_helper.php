<?php
/**
* Functionality : Get System Configuration
* Parameters : $paAppCode, $paConfigGroupCode
* Creator : 5/3/2019 piya(tiger)
* Last Modified : -
* Return : Configuration List
* Return Type : array
*/
function FCNaGetSysConfig($paSysApp, $paGmnCode){
    
    $tSysApp = implode(",", $paSysApp);
    $tGmnCode = implode(",", $paGmnCode);
    
    $ci = &get_instance();
    $ci->load->database();
    $nLangEdit = $ci->session->userdata("tLangEdit");
    
    $tSQL = "   SELECT * 
                FROM [TSysConfig] SYSC WITH (NOLOCK)
                LEFT JOIN [TSysConfig_L] SYSCL WITH (NOLOCK)
                    ON SYSCL.FTSysCode = SYSC.FTSysCode 
                    AND SYSCL.FTSysApp = SYSC.FTSysApp 
                    AND SYSCL.FTSysKey = SYSC.FTSysKey 
                    AND SYSCL.FNLngID = $nLangEdit
                WHERE SYSC.FTSysApp IN ($tSysApp) AND SYSC.FTGmnCode IN ($tGmnCode)
                ORDER BY SYSC.FTSysSeq";
    
    $oQuery = $ci->db->query($tSQL);
    
    if ($oQuery->num_rows() > 0) { // Found Data
        
        $aList = $oQuery->result(); 

        $aResult = array(
                'raItems' => $aList,
                'rnAllRow' => $oQuery->num_rows(),
                'rtCode' => '1',
                'rtDesc' => 'success'
        );
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
            
    } else { // No Data
        $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found'
        );
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        
    }
    return $aResult;
}

/**
* Functionality : Get System Configuration
* Parameters : $paAppCode, $paConfigGroupCode
* Creator : 5/3/2019 piya(tiger)
* Last Modified : -
* Return : Configuration List
* Return Type : array
*/
function FCNaUpdateSysConfig($paSysKey, $paData){
    
    $ci = &get_instance();
    $ci->load->database();
    $nLangEdit = $ci->session->userdata("tLangEdit");
    
    // $ci->db->set('FTSysStaDefValue' , $paData['FTSysStaDefValue']);
    $ci->db->set('FTSysStaUsrValue' , $paData['FTSysStaUsrValue']);

    $ci->db->where('FTSysCode' , $paSysKey['FTSysCode']);
    $ci->db->where('FTSysApp' , $paSysKey['FTSysApp']);
    $ci->db->where('FTSysKey' , $paSysKey['FTSysKey']);
    $ci->db->where('FTSysSeq' , $paSysKey['FTSysSeq']);
    $ci->db->where('FTGmnCode' , $paSysKey['FTGmnCode']);
    $ci->db->update('TSysConfig');
    
    if($ci->db->affected_rows() > 0){
        $aStatus = array(
            'rtCode' => '1',
            'rtDesc' => 'Update Master Success',
        );
    }else{
        $aStatus = array(
            'rtCode' => '905',
            'rtDesc' => 'Error Cannot Add/Edit Master.',
        );
    }
    return $aStatus;
}
