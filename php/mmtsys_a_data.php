<?php
    include("CMUHconndata.php");
    include("fun.php");
    $dataArray=array();
    $dataArray1=array();
    $x=$_GET["switchchoice"];
    $mmtsysNo=$_GET["mmtsysNo"];

    switch ($x) {
        case '1':
            $str="SELECT id,bName FROM FA.MMT_build WHERE sid ='$mmtsysNo'";
            $query=item($str);
            foreach ($query as $datainfo) {
                $dataArray["id"] = $datainfo["id"];
                $dataArray["bName"]=$datainfo["bName"];
                $dataArray2=array_push($dataArray1,$dataArray);
            }
        break;        
        case '2':
            $mmtbuildNo=$_GET["mmtbuildNo"];
            $str="SELECT fid,fName FROM FA.MMT_floor WHERE sid ='$mmtsysNo' AND bid='$mmtbuildNo'";
            $query=item($str);
            foreach ($query as $datainfo) {
                $dataArray["fid"] = $datainfo["fid"];
                $dataArray["fName"]=$datainfo["fName"];
                $dataArray2=array_push($dataArray1,$dataArray);
            }
            break;
        case '3':
            $mmtbuildNo=$_GET["mmtbuildNo"];
            $mmtfloorNo=$_GET["mmtfloorNo"];
            $str="SELECT id,eName FROM FA.MMT_equip WHERE sid ='$mmtsysNo' AND bid='$mmtbuildNo' AND fid='$mmtfloorNo'";
            $query=item($str);
            foreach ($query as $datainfo) {
                $dataArray["id"] = $datainfo["id"];
                $dataArray["eName"]=$datainfo["eName"];
                $dataArray2=array_push($dataArray1,$dataArray);
            }
        break;
        case '4':
            $mmtbuildNo=$_GET["mmtbuildNo"];
            $mmtfloorNo=$_GET["mmtfloorNo"];
            $mmtequipa=$_GET["mmtequipa"];
            $str="SELECT id,eid FROM FA.MMT_equipNo WHERE sid ='$mmtsysNo' AND bid='$mmtbuildNo' AND fid='$mmtfloorNo' AND eid='$mmtequipa' ORDER BY id";
            $query=item($str);
            foreach ($query as $datainfo) {
                $dataArray["id"] = $datainfo["id"];
                $dataArray["eid"]=$datainfo["eid"];
                $dataArray2=array_push($dataArray1,$dataArray);
            }
        break;
    }
    
    
    
    
    
    echo json_encode($dataArray1,JSON_UNESCAPED_UNICODE);
?>