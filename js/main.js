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

    //選擇mainMenu新增Class(套用Bootstrap)
    $(".mainMenu").addClass("col-lg-2 col-md-5 col-sm-5  mx-auto my-5 py-3 text-center");
    //選擇mainBtn新增Class(套用Bootstrap)
    $(".mainBtn").addClass(" mx-auto w-50 d-block btn btn-outline-primary");
    //用getJSON來取得運轉抄表日期
    $("#cardSendBtn1").click(function (e) {
        e.preventDefault();
        $.getJSON("php/test.php", {
            rankdate: $("#rank1date").val(),
            rank: $("#rank1").val()
        }, function (data) {
            $("#resultdate").text(data);
        });
    });
    //用getJSON來取得設備保養日期
    $("#cardSendBtn2").click(function (e) {
        e.preventDefault();
        $.getJSON("php/test.php", {
            rankdate: $("#rankmtdate").val(),
            rank: $("#rankmt").val()
        }, function (data) {
            $("#resultdate").text(data);
        });
    });
    //送出按出時#article1新增屬性show按點我進入屬性show移除
    $("#cardSendBtn1").click(function () {
        $("#article1").addClass(" show");
    });
    $("#mainBtn1").click(function () {
        $("#article1").removeClass(" show");
    });
    //送出按出時#article2新增屬性show按點我進入屬性show移除
    $("#cardSendBtn2").click(function () {
        $("#article2").addClass(" show");
    });
    $("#mainBtn2").click(function () {
        $("#article2").removeClass(" show");
    });
    // 使用getJSON讀取index1.json內的title資料
    $.getJSON("json/index1.json", function (data) {
        var $syslist1 = $("#syslist1");
        for (var i = 0; i < data.length; i++) {
            $syslist1.append(
                $("<li>").addClass("list-group-item").append(
                    $("<a>").addClass("text-dark").attr("href", data[i].url).text(data[i].name)
                )
            );

        }
    });
    // 使用getJSON讀取index2.json內的title資料
    $.getJSON("json/index2.json", function (data) {
        var $syslist2 = $("#syslist2");
        for (var i = 0; i < data.length; i++) {
            $syslist2.append(
                $("<li>").addClass("list-group-item").append(
                    $("<a>").addClass("text-dark").attr("href", data[i].url).text(data[i].name)
                )
            );

        }
    });
    // 使用getJSON讀取index3.json內的title資料
    $.getJSON("json/index3.json", function (data) {
        var $syslist3 = $("#syslist3");
        for (var i = 0; i < data.length; i++) {
            $syslist3.append(
                $("<li>").addClass("list-group-item").append(
                    $("<a>").addClass("text-dark").attr("href", data[i].url).text(data[i].name)
                )
            );

        }
    });
    //抓取當前時間並寫進時間選單內
    var presentYear = new Date().getFullYear();
    var presentMonth = new Date().getMonth() + 1;
    var presentDate = new Date().getDate();
    if (presentMonth < 10) {
        presentMonth = "0" + presentMonth;
    }
    if (presentDate < 10) {
        presentDate = "0" + presentDate;
    }
    $(".presentTime").attr("value", presentYear + "-" + presentMonth + "-" + presentDate);

    $.getJSON("php/data.php", {
        colID: 'b_number',
        colName: 'B_name'
    }, function (data) {
        for (let a = 0; a < data.length; a++) {
            $("#build").append('<option value="' + data[a]["b_number"] + '">' + data[a]["B_name"] + '</option>');
        }
    });

    $.getJSON("php/data.php", {
        colID: 'sysID',
        colName: 'sysName'
    }, function (data) {
        for (let i = 0; i < data.length; i++) {
            $(".system").append('<option value="' + data[i]["sysID"] + '">' + data[i]["sysName"] + '</option>');
        }
    });
    //mtinsert選擇設備
    $('select[name=system]').change(function () { //
        var system_eq = $(this).val();
        $.getJSON("php/ajax_system.php", {
            "system_eq": system_eq
        }, function (data) {
            for (let i = 0; i < data.length; i++) {
                $("#equipment").append('<option value="' + data[i]["equipID"] + '">' + data[i]["equipName"] + '</option>');
            }
        });
    }).change();

});