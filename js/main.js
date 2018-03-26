//當畫面元素載入完成後才執行主程式
$(function () {
    //啟用wow.js
    new WOW().init();
    // 動態載入頁首頁尾
    // 下方的ajax用來取代$("#navbar").load("packageHtml/navbar.html");
    $.ajax({
        url: "packageHtml/navbar.html",
        cache: true,
        async: false,
        success: function (html) {
            $("#navbar").html(html);
        }
    });
    $("#header").load("packageHtml/header.html");
    $("#footer").load("packageHtml/footer.html");
    // 使用getJSON讀取data.json內的title資料
    $.getJSON("json/login.json", function (data) {
        $(".title").text(data);
    });
    //登入功能
    // 登入表單提交時要執行ajax指令並將登入隱藏/登出顯示
    $("#logIn").submit(function (event) {
        // 阻止元素發生默認的行為
        event.preventDefault();
        // 利用ajax指令來傳遞logIn表單資料並回傳結果回ID為result的物件中
        $.ajax({
            type: "POST",
            url: "php/tlogin.php",
            data: $("#logIn").serialize(),
            success: function (msg) {
                console.log(msg);
                $("#result").html(msg);
            }
        });
        $("#logIn").hide();
        $("#logOut").show();
    });
    // 登出表單提交時要執行將登入顯示/登出隱藏
    $("#logOut").submit(function (event) {
        event.preventDefault();
        $("#logIn").show();
        $("#logOut").hide();
    });
    //mtinsert選擇設備
    $('select[name=system]').change(function () { //
        var system_eq = $(this).val();
        $.ajax({
            url: "php/ajax_system.php", //url:'撈資料的php'
            method: 'post', //'post'
            cache: false,
            fileElementId: 'file',
            data: {
                "system_eq": system_eq
            }, //{"傳送變數的名稱":傳送變數的值}
            //dataType:'text',								
            //beforeSend: function () {}, //function 執行前的程式
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

});