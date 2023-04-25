<?php
    // Functionality: ฟังก์ชั่น Load View In Folder Datasources
    // Parameters:  Function Parameter
    // Creator: 19/07/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: View Report
    // ReturnType: View
    function JCNoHLoadViewAdvanceTable($tModuleName,$tViewName,$aDataViewRpt){
        ob_start();
        if(isset($aDataViewRpt)){
            extract($aDataViewRpt);
        }

        include('application/modules/'.$tModuleName.'/'.$tViewName.'.php');

        $oViewContents  = ob_get_contents();
        @ob_end_clean();
        return $oViewContents;
    }

    //สั่ง Grouping
    function FCNtHRPTHeadGroupping($pnRowPartID,$paGrouppingData){
        if($pnRowPartID == 1){
            echo "<tr class='xCNHeaderGroup'>";
            for($i = 0;$i<count($paGrouppingData);$i++){
                if($paGrouppingData[$i] !== 'N'){
                    echo "<td style='padding: 5px;'>".$paGrouppingData[$i]."</td>";
                }else{
                    echo "<td></td>";
                }
            }
            echo "</tr>";
        }
    }

    //สั่ง Summary Sub Footer
    function FCNtHRPTSumSubFooter($pnGroupMember,$pnRowPartID,$paSumFooter){
        if($pnRowPartID == $pnGroupMember){
            echo '<tr class="xCNTrSubFooter">';
            for($i = 0;$i<count($paSumFooter);$i++){
                if($i==0){
                $tStyle = 'text-align:left;border-top:1px solid #333;border-bottom:1px solid #333;background-color: #CFE2F3;';
                }else{
                $tStyle = 'text-align:right;border-top:1px solid #333;border-bottom:1px solid #333;background-color: #CFE2F3;';
                }
                if($paSumFooter[$i] !='N'){
                $tFooterVal =   $paSumFooter[$i];           
                }else{
                    $tFooterVal =   '';
                }
                echo '<td style="'.$tStyle.' ;padding: 4px;">'.$tFooterVal.'</td>';
            }
            echo '</tr>';
        }
    }

    //สั่ง Summary Sub Footer
    function FCNtHRPTSumSubFooter2($pnGroupMember,$pnRowPartID,$paSumFooter){
        if($pnRowPartID == $pnGroupMember){
            echo '<tr class="xCNTrSubFooter2">';
            for($i = 0;$i<count($paSumFooter);$i++){
                if($i==0){
                $tStyle = 'color: #232C3D !important; font-size: 18px !important; font-weight: bold; text-align:left;border-top:1px solid #333;border-bottom:1px solid #333;';
                }else{
                $tStyle = 'color: #232C3D !important; font-size: 18px !important; font-weight: bold; text-align:right;border-top:1px solid #333;border-bottom:1px solid #333;';
                }
                if($paSumFooter[$i] !='N'){
                $tFooterVal =   $paSumFooter[$i];           
                }else{
                    $tFooterVal =   '';
                }
                echo '<td style="'.$tStyle.' ;padding: 4px;">'.$tFooterVal.'</td>';
            }
            echo '</tr>';
        }
    }
    
    //สั่ง Sum Footer
    function FCNtHRPTSumFooter($pnPangNo,$pnTotalPage, $paFooterData){
        if($pnPangNo == $pnTotalPage){
             echo "<tr class='xCNTrFooter'>";
          
             for($i= 0;$i<count($paFooterData);$i++){
                 if($i==0){
                     $tStyle = 'text-align:left;border-top:1px solid #333;border-bottom:1px solid #333;background-color: #CFE2F3;';
                     }else{
                     $tStyle = 'text-align:right;border-top:1px solid #333;border-bottom:1px solid #333;background-color: #CFE2F3;';
                 }
                 if($paFooterData[$i] !='N'){
                     $tFooterVal =   $paFooterData[$i];           
                     }else{
                         $tFooterVal =   '';
                 }
                 echo "<td style='$tStyle;padding: 4px;'>".$tFooterVal."</td>";
             }
            echo "<tr>";
        }
    }

    //สั่ง Sum Footer
    function FCNtHRPTSumFooter2($pnPangNo,$pnTotalPage, $paFooterData){
        if($pnPangNo == $pnTotalPage){
             echo "<tr class='xCNTrFooter2'>";
          
             for($i= 0;$i<count($paFooterData);$i++){
                 if($i==0){
                     $tStyle = 'color: #232C3D !important; font-size: 18px !important; font-weight: bold; text-align:left;border-top:1px solid #333;border-bottom:1px solid #333;background-color: #CFE2F3;';
                     }else{
                     $tStyle = 'color: #232C3D !important; font-size: 18px !important; font-weight: bold; text-align:right;border-top:1px solid #333;border-bottom:1px solid #333;background-color: #CFE2F3;';
                 }
                 if($paFooterData[$i] !='N'){
                     $tFooterVal =   $paFooterData[$i];           
                     }else{
                         $tFooterVal =   '';
                 }
                 echo "<td style='$tStyle;padding: 4px;'>".$tFooterVal."</td>";
             }
            echo "<tr>";
        }
    }
    
?>







