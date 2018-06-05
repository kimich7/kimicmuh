<?PHP
include("php/CMUHconndata.php");
include("php/fun.php");
echo date('Y-m-d H:i:s');

$pageRow_record=10;//每頁的筆數
$page_num=1;//預設的頁數
//更新$page_num
if (isset($_GET['page'])) {
    $page_num=$_GET['page'];
}

//本業開始的筆數
$startRow_record=($page_num-1)*$pageRow_record;

//所有的資料
$sqlstr_total="SELECT * FROM $tbl WHERE check_number IS NULL or check_manager IS NULL";
//篩選後給每頁的筆數
$sqlstr_page="SELECT * FROM $tbl WHERE check_number IS NULL or check_manager IS NULL ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
//總資料數量
$totalstr_num="SELECT COUNT(recordID) FROM $tbl WHERE check_number IS NULL or check_manager IS NULL";

$sql_page=$pdo->query($sqlstr_page);
$sql_total=$pdo->query($sqlstr_total);
$total_num=CURRENT($pdo->query('$totalstr_num')->fetch());

//計算總頁數
$total_page=ceil($total_num/$pageRow_record);

while ($data_page = $sqlstr_page->fetch()) {
   $buildName=$data_page['b_number'];
    echo '<a href="#" class="list-group-item list-group-item-action">'.$buildName."-"."水系統-".$rDate.'</a>';
}
echo '<table border="0" align="center">';
    echo '<tr>';
        if ($page_num>1) {
            echo '<td><a href="mtlistcheck.php?page=1">'."第一頁".'</a></td>';
            echo '<td><a href="mtlistcheck.php?page=$page_num-1">'."上一頁".'</a></td>';
        }
        if ($page_num<$total_page) {
            echo '<td><a href="mtlistcheck.php?page=$page_num+1">'."下一頁".'</a></td>';
            echo '<td><a href="mtlistcheck.php?page=$total_page">'."最末頁".'</a></td>';
        }
    echo '</tr>';
echo '</table>';
echo '<table border="0" align="center">';
        echo '<tr>';
            echo '<td>';
                echo '頁數';
                for ($i=1; $i < $total_page; $i++) {
                    if ($i==$page_num) {
                        echo $i;
                    } else {
                        echo "<a href=\"mtlistcheck.php?page={$i}\">{$i}</a>";
                    }
                }
            echo '</td>';
        echo '</tr>';
echo '</table>';
?>