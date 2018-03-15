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
    //===================
    $("#logIn").submit(function (event) {
        event.preventDefault();
        console.log($(this).serialize());
        $("#result").html("<span id=\"test\">test</span>");
        $("#logIn").hide();
        $("#logOut").show();
    });
    $("#logOut").submit(function (event) {
        event.preventDefault();
        $("#test").remove();
        $("#logIn").show();
        $("#logOut").hide();
    });

    // $("#logIn").submit(function () { //
    //     var system_eq = $(this).val();
    //     $.ajax({
    //         url: "ajax_system.php", //url:'撈資料的php'
    //         method: 'post', //'post'
    //         data: {
    //             "system_eq": system_eq
    //         }, //{"傳送變數的名稱":傳送變數的值}
    //         //dataType:'text',								
    //         beforeSend: function () {}, //function 執行前的程式
    //         success: function (data) {
    //             $('select[name=equipment]').html(
    //                 '<option value="">--請選擇設備--</option>'); //連動的選單
    //             $('select[name=equipment]').append(data);
    //         },
    //         error: function (xhr) {
    //             alert(xhr);
    //             alert("錯誤");
    //         }
    //     });
    // }).submit();
});