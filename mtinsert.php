<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 連結自製化的Bootatrap.css -->
    <link rel="stylesheet" href="./css/bootstrap.css">
    <!-- 連結Normalize.min.css的網址使得網站在各個瀏覽器看起來相同 -->
    <link rel="stylesheet" href="./node_modules/normalize.css/normalize.css">
    <!-- 連結fontawesome的網址使得網站可以使用fontawesome的icon -->
    <link href="./node_modules/fontawesome-free-5.0.6/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet">
    <!-- 如果連結了客(自)製化的Bootstrap,原先連接的版本要註解掉 -->
    <!-- 連結Bootstrap的網址使得網站可以使用Bootstrap語法 -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
    <!-- 連結animate的網址使得網站可以使用animate語法 -->
    <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
    <!-- 連結自己的CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- 連結Bootstrap jQuery的網址使得網站可以使用JS, Popper.js, and jQuery語法 -->
    <!-- 並把jQuery變更為完整的jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>被變更的 -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- 連結wow.js的網址使得網站可以使用WOW的滾動動畫(必須連接animate.css) -->
    <script src="./node_modules/wow.js/dist/wow.min.js"></script>
    <!-- 連結自己的JS -->
    <script src="./js/jquery-3.3.1.min.js"></script>
    <script src="./js/main.js"></script>

    <title>設備保養表單選擇</title>
</head>

<body>
    <!-- TODO:連接SQL資料庫 -->s
    <?PHP
		include("php/SQL_Database.php");
	?>
   <!-- 導覽列 -->
   <div id="navbar"></div>
    <!-- header網頁標題 -->
    <div id="header"></div>
    <!-- 將該網頁連結到mtwatertable.php -->
    <!-- form start -->
    <!-- TODO:重新制定form表單格式,傾向Bootstrap的原始風格 -->
    <form action="mtwatertable.php" method="post" name="mttable">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-4 choice_table container my-5 border border-dark rounded text-white">
                <!-- 副標題 -->
                <h2 class="text-center pb-4">選擇表單</h2>
                <!-- 保養日期 -->
                <P>保養日期:
                    <input type="date" name="bday">
                </P>
                <!-- 選擇棟別 -->
                <p>選擇棟別：
                    <select name="build" id="build">
                        <option value="">--請選擇棟別--</option>
                        <!-- TODO:將PHP與Html完全分離 -->
                        <?PHP 
						// foreach ($query_build as $buildinfo){
						// echo "<option value= ".$buildinfo['b_number'].">".$buildinfo['B_name']."</option>";
						// }		
					?>
                    </select>
                </p>
                <!-- 點檢選擇系統 -->
                <p>點檢選擇系統：
                    <select name="system" class="system">
                        <option value="">--請選擇系統--</option>
                        <!-- TODO:將PHP與Html完全分離 -->
                        <?PHP 
						// foreach ($query_system as $systeminfo){
						// echo "<option value= ".$systeminfo['sysID'].">".$systeminfo['sysName']."</option>";
						// }		
					?>
                    </select>
                </p>
                <!-- 選擇點檢設備 -->
                <p>選擇點檢設備：
                    <select name="equipment" id="equipment">
                        <!-- TODO:這裡使用ajax技術產生,也要一併考量進去 -->
                        <option value="">--請選擇設備--</option>
                    </select>
                </p>
                    <!-- 選擇班別 -->
                    <p>選擇班別：
                        <select name="class">
                            <!-- TODO:將PHP與Html完全分離 -->
                            <?PHP 
					foreach ($query_shift as $shiftinfo){
					echo "<option value= ".$shiftinfo['shiftID'].">".$shiftinfo['shiftName']."</option>";
					}		
					?>
                        </select>
                    </p>
                    <!-- 送出鍵 -->
                    <button class="my-3 px-3 py-1 btn-outline-danger text-white" type="submit">送出</button>
            </div>
        </div>

    </form>
    <!-- form end -->

    <!-- 網頁尾頁部分 -->
    <!-- footer -->
    <div id="footer"></div>
</body>

</html>