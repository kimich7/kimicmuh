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
	<title>選擇表單</title>
</head>

<body>
<!-- 連接SQL資料庫 -->
	<?PHP
		include("SQL_Database.php");
	?>
 <!-- 導覽列 -->
 <nav class="navbar fixed-top navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand" href="./index.php">
            <img src="./jpg/logo.png" alt="">
        </a>
        <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
            aria-expanded="false" aria-label="Toggle navigation"></button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0"></ul>
            <!-- TODO將搜尋改為登入 -->
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="text" placeholder="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>
    <!-- header網頁標題 -->
    <header class="jumbotron text-center" id="header">
        <div class="container header-content">
            <h1 class="display-4 wow zoomIn data-wow-duration=" 5s " ">中國附醫工務系統</h1>
        </div>
        <div class="header-cover"></div>
	</header>

	<!-- 將該網頁連結到mtwatertable.php -->
	<!-- form start -->
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
				<select name="build">
					<option value="">--請選擇棟別--</option>
                    <?PHP 
						foreach ($query_build as $buildinfo){
						echo "<option value= ".$buildinfo['b_number'].">".$buildinfo['B_name']."</option>";
						}		
					?>
				</select>
			</p>
			<!-- 點檢選擇系統 -->
			<p>點檢選擇系統：
				<select name="system">
					<option value= "">--請選擇系統--</option>
                    <?PHP 
						foreach ($query_system as $systeminfo){
						echo "<option value= ".$systeminfo['sysID'].">".$systeminfo['sysName']."</option>";
						}		
					?>
				</select>
			</p>
			<!-- 選擇點檢設備 -->
			<p>選擇點檢設備：
				<select name="equipment" id="equipment" >
	                <option value= "">--請選擇設備--</option>
                </select><p> 
	            <script type="text/javascript">
	                $('select[name=system]').change(function(){ //
                    var system_eq = $(this).val();					
                    $.ajax({
                        url:"ajax_system.php",//url:'撈資料的php'
                        method:'post',//'post'
                        cache:false,                
                        fileElementId:'file',
                        data:{"system_eq":system_eq},//{"傳送變數的名稱":傳送變數的值}
                        //dataType:'text',								
                        beforeSend :function(){},//function 執行前的程式
                        success:function(data){
                            $('select[name=equipment]').html('<option value="">--請選擇設備--</option>');//連動的選單
                            $('select[name=equipment]').append(data);                    
                    } , 
                    error:function(xhr){										
                        alert(xhr);
                        alert("錯誤");
                    }
                    });												
                });        
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
			<p>
				<input name="送出" type="submit" value="送出">
			</p>
		</div>
	</div>
	
</form>
	<!-- form end -->

 <!-- 網頁尾頁部分 -->
    <!-- footer -->
    <footer id="footer" class="bg-secondary text-white pt-5">
        <!-- 設定以下四塊FooterList在電腦螢幕開啟時排成一個橫排 -->
        <div class="row">
            <div class="footer-list">
                <h4 class="container-fluid">聯絡我們</h4>

                <ul>
                    <li>
                        <a class="text-dark" href="mailto:service@brand.com">聯絡信箱: service@brand.com</a>
                    </li>
                    <li>
                        <a class="text-dark" href="#">About Us</a>
                    </li>
                    <li>
                        <a class="text-dark" href="#">Blog</a>
                    </li>
                    <li>
                        <a class="text-dark" href="#">Press</a>
                    </li>
                    <li>
                        <a class="text-dark" href="#">Bonus</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-end bg-dark mt-5 py-2">
            <p class="text-center">&copy; Copy right 2018</p>
        </div>
    </footer>

</body>

</html>