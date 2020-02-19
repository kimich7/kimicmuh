<?php
    include("CMUHconndata.php");
    include("fun.php");
    $empArray=array();
    $empArray1=array();
    $emp=item("SELECT e_number,cname FROM FA.Employee ORDER BY e_number ASC ");//OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY"
    foreach ($emp as $value) {
        $empArray['e_number']=$value['e_number'];
        $empArray['cname']=$value['cname'];
        $empArray2=array_push($empArray1,$empArray);
    }
    echo json_encode($empArray1,JSON_UNESCAPED_UNICODE);
    // $result=$pdo->query($sqlstr);
    
    // $total_member="SELECT COUNT(e_number) FROM FA.Employee";    
    // $member_num=CURRENT($pdo->query($total_member)->fetch());



    //     if (!empty($_REQUEST["ubuildNo"])) {
    //         $buildNo=$_REQUEST["ubuildNo"];
    //         $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo'");
    //         foreach ($build_seach as $info) {
    //             $floorArray["floorID"]=$info["floorID"];
    //             $floorArray["floorName"]=$info["floorName"];
    //             $floorArray2=array_push($floorArray1,$floorArray);
    //         }
    //     }    
    // echo json_encode($floorArray1,JSON_UNESCAPED_UNICODE);
?>