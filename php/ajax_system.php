<?PHP
        include("CMUHconndata.php");
        //==========(接收資訊)===========//
        if (!empty($_REQUEST['system_eq'])) {
            $systemNo = $_REQUEST['system_eq'];
        } else {
            $systemNo=1;
        }        
        //==========(作品分類)===========//
       
        $sql_equipment="SELECT equipID,equipName FROM FA.Equipment_System WHERE sysID = $systemNo";
        $query_equipment =$pdo->query($sql_equipment);//->fetchAll()
        
        
        $equipment_num="SELECT COUNT(equipID) FROM FA.Equipment_System WHERE sysID = $systemNo";
        $equipment_total_num =Current($pdo->query($equipment_num)->fetch());

        for ($i=0; $i <$equipment_total_num ; $i++) { 
            $equipinfo = $query_equipment->fetch(PDO::FETCH_ASSOC);
            $data.= "<option value=". $equipinfo['equipID'].">". $equipinfo['equipName']."</option>";
        }
        echo ($data);
        /* foreach ($query_equipment as $equipinfo) {            
            $data[]=array(
                "equipID"=>$equipinfo['equipID'],
                "equipName"=>$equipinfo['equipName']
            );} */
        
        //echo "<option value= ".$equipmentinfo['equipID'].">".$equipmentinfo['equipName']."</option>";
        //$data = $query_equipment;


        /*         $data = '[';
         for ($a=0; $a < $equipment_total_num; $a++) { 
            $worktyperow=$query_equipment->fetch(PDO::FETCH_ASSOC);
            if($a != ($equipment_total_num-1)){
                $data.='{"equipID":"'.$worktyperow["equipID"].'","equipName":"'.$worktyperow["equipName"].'"},';
            }
            if($a == ($equipment_total_num-1)){
                $data.='{"equipID":"'.$worktyperow["equipID"].'","equipName":"'.$worktyperow["equipName"].'"}';
            }
        }
        $data.=']'; */
         
        //$data = [{equipID:1},{equipName:設備一},{equipID:2},{equipName:設備二}];        
        //$data = '["{"equipID":"1","equipName":"設備一"}","{"equipID":"2","equipName":"設備二"}"]';
        //echo $data;
?>