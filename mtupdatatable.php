<?php
include("CMUHconndata.php");
include("fun.php");

//叫出資料
$building=$_GET["building"];
$rDate=$_GET["rdate"];
$equipID=$_GET["equip"];
$shiftID=$_GET["shift"];
$MasterID=$_GET["id"];

$bname=sql_database('B_name','FA.Building','b_number',$building);
$equipname=sql_database('equipName','FA.Equipment_System','equipID',$equipID);
$shiftname=sql_database('shiftName','FA.Shift_Table','shiftID',$shiftID);

$updata_data=updata_select('FA.Water_System_Record_Detail',$rDate,$equipID,$MasterID);
foreach ($updata_data as $updatainfo) {
    echo $updatainfo["equipCheckID"].'</br>';
}
?>

<div class="container border border-info mt-5">
                <form action="" method="post" name="wa">
                    <h2 class="text-center font-weight-bold">
                        中國醫藥大學附設醫院-
                        <?= $build ?>--
                            <?= $system ?>
                    </h2>
                    <div class="row my-3">
                        <div class="col">
                            <p class="d-inline font-weight-bold">
                                班別：
                            </p>
                            <p class="d-inline text-primary">
                                <?= $class ?>
                            </p>
                        </div>
                        <div class="col text-center">
                            <p class="d-inline font-weight-bold">
                                檢查者：
                            </p>
                            <p class="d-inline text-primary">
                                <?= '檢查者' ?>
                            </p>
                        </div>
                        <div class="col text-right">
                            <p class="d-inline font-weight-bold">
                                檢查日期：
                            </p>
                            <p class="d-inline text-primary">
                                <?= $check_date ?>
                            </p>

                        </div>
                    </div>
                    <div class="my-3">
                        <p class="d-inline font-weight-bold">
                            設備：
                        </p>
                        <p class="d-inline text-primary">
                            <?= $equipment ?>
                        </p>
                    </div>
                    <table class="table my-5">
                        <thead>
                            <th>檢查項目</th>
                            <th>參考值</th>
                            <th>結果</th>
                        </thead>
                        <?php
                        for ($i=0; $i < $equip_check_no; $i++) { 
                            $equipinfo=$query_equip->fetch(PDO::FETCH_ASSOC);
                        ?>
                            <tbody class="text-primary">
                                <td>
                                    <?= $equipinfo['equipCheckName']?>
                                </td>
                                <td>
                                    <?= $equipinfo["ref"]?>
                                </td>
                                <?php
                            if ($equipinfo["ref"]=="V/X") { 
                            ?>
                                    <td>
                                        <input type='radio' name='<?= $i?>' value='true'>合格
                                        <input type='radio' name='<?= $i?>' valee='false'>不合格
                                    </td>
                                    <?php                
                            } else { 
                            ?>
                                    <td>
                                        <input type="text" name='<?= $i?>' maxlength="20">
                                    </td>
                                    <?php
                            }  
                            echo"</tr>";
                            }
                            ?>
                            </tbody>
                    </table>
