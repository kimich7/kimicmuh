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
    <script src="./js/main.js"></script>
    <title>設備保養表單修改選擇</title>
</head>

<body>
    <!-- 連接SQL資料庫 -->
    <?PHP
		include("SQL_Database.php");
	?>
    <!-- 導覽列 -->
    <div id="navbar"></div>
    <!-- header網頁標題 -->
    <div id="header"></div>
    <!-- 將該網頁連結到mtlistupch.php -->
    <!-- form start -->
    <form action="mtlistupch.php" method="post" name="mtlistupch">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-4 choice_table container my-5 border border-dark rounded text-white">
                <!-- 副標題 -->
                <h2 class="text-center pb-4">修改表單</h2>
                <!--選擇開始日期-->
                <P>選擇開始日期:
                    <input type="date" name="datestr">
                </P>
                <!-- 選擇結束日期 -->
                <P>選擇結束日期:
                    <input type="date" name="dateend">
                </P>
                <!-- 選擇棟別 -->
                <p>選擇棟別：
                    <select name="build">
                        <option value="">--請選擇棟別--</option>
                        <?PHP 
						foreach ($query_build as $buildinfo){
						echo "<option value= ".$buildinfo['b_number'].">".$buildinfo['B_name']."</option>";
						}		
					?>
                    </select>
                </p>
                <!-- 選擇修改系統 -->
                <p>選擇修改系統：
                    <select name="system">
                        <option value="">--請選擇系統--</option>
                        <?PHP 
						foreach ($query_system as $systeminfo){
						echo "<option value= ".$systeminfo['sysID'].">".$systeminfo['sysName']."</option>";
						}		
					?>
                    </select>
                </p>
                <!-- 選擇修改設備 -->
                <p>選擇修改設備：
                    <select name="equipment" id="equipment">
                        <option value="">--請選擇設備--</option>
                    </select>
                    <p>
                        <script type="text/javascript">
                            $('select[name=system]').change(function () { //
                                var system_eq = $(this).val();
                                $.ajax({
                                    url: "ajax_system.php", //url:'撈資料的php'
                                    method: 'post', //'post'
                                    cache: false,
                                    fileElementId: 'file',
                                    data: {
                                        "system_eq": system_eq
                                    }, //{"傳送變數的名稱":傳送變數的值}
                                    //dataType:'text',								
                                    beforeSend: function () {}, //function 執行前的程式
                                    success: function (data) {
                                        $('select[name=equipment]').html(
                                            '<option value="">--請選擇設備--</option>'); //連動的選單
                                        $('select[name=equipment]').append(data);
                                    },
                                    error: function (xhr) {
                                        alert(xhr);
                                        alert("錯誤");
                                    }
                                });
                            }).change();
                        </script>
                    </p>
                    <!-- 選擇班別 -->
                    <p>選擇班別：
                        <select name="class">
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