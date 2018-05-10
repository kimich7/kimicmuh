//當畫面元素載入完成後才執行主程式
$(function () {
    //===================================全域開始===================================
    //啟用wow.js
    new WOW().init();
    // 動態載入頁首頁尾
    $("#header").load("packageHtml/header.html");
    $("#footer").load("packageHtml/footer.html");
    //===================================全域結束===================================
    //===================================首頁開始===================================
    //登入功能
    //==================================================================================
    // 登入表單提交時要執行ajax指令並將登入隱藏/登出顯示
    $.getJSON("php/sessionData.php", function (data) {
        if (data["login_success"] == false) {
            $("#result").html("帳號或密碼錯誤，請重新輸入");
        } else {
            $("#result").html("歡迎登入");
            $("#logInBtn").toggleClass("d-none");
            $("#logOutBtn").toggleClass("d-none");
        }
    });
    // 登出表單提交時要執行將登入顯示/登出隱藏
    $("#logOutBtn").click(function () {
        $.getJSON("php/sessionUnset.php", {
            logOutValue: 1
        }, function (data) {
            if (data == 1000) {
                location.reload();
            }
        });
    });
    //=======================================================================================
    //選擇mainMenu新增Class(套用Bootstrap)
    $(".mainMenu").addClass("col-lg-2 col-md-5 col-sm-5  mx-auto my-5 py-3 text-center");
    //選擇mainBtn新增Class(套用Bootstrap)
    $(".mainBtn").addClass(" mx-auto w-50 d-block btn btn-outline-primary");
    //用getJSON來取得運轉抄表日期
    $("#cardSendBtn1").click(function (e) {
        e.preventDefault();
        $.getJSON("php/data_class.php", {
            rankdate: $("#rank1date").val(),
            rank: $("#rank1").val()
        }, function (data) {
            $("#resultdate").text(data);
        });
    });
    //用getJSON來取得設備保養日期
    $("#cardSendBtn2").click(function (e) {
        e.preventDefault();
        $.getJSON("php/data_class.php", {
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
    //===================================首頁結束===================================
    //===================================mtinsert開始(與mtupdata共用)===================================
    //取得日期
    $.getJSON("php/cookiedata.php", function (data) {
        $("#bday").attr("value", data[0]['date']);
    });
    //取得班別
    $.getJSON("php/cookiedata.php", function (data) {
        $("#Three_shifts").html('<option value="' + data[0]["class"] + '">' + data[0]["shiftclass"] + "</option>");
    });
    //用getJSON讀取data內的資料(棟別)
    $.getJSON("php/data.php", {
        colID: 'b_number',
        colName: 'B_name'
    }, function (data) {
        for (let i = 0; i < data.length; i++) {
            $("#build").append('<option value="' + data[i]["b_number"] + '">' + data[i]["B_name"] + '</option>');
        }
    });
    //用getJSON讀取data內的資料(系統)
    $.getJSON("php/data.php", {
        colID: 'sysID',
        colName: 'sysName'
    }, function (data) {
        for (let i = 0; i < data.length; i++) {
            $("#system").append('<option value="' + data[i]["sysID"] + '">' + data[i]["sysName"] + '</option>');
        }
    });

    //mtinsert選擇樓層
    $("#system, #build").change(function () { //
        var system_eq = $("#system").val();
        var building_eq = $("#build").val();
        $.getJSON("php/zone.php", {
            "system_eq": system_eq,
            "build_eq": building_eq
        }, function (data) {
            var html = "";
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["floorID "] + "\">" + data[i]["floorName"] + "</option>";
                $("#buildingfloor").html(html);
                6
            }
        });
    }).change();
    //mtinsert選擇設備
    $('select[name=system]').change(function () { //
        var system_eq = $(this).val();
        $.getJSON("php/ajax_system.php", {
            "system_eq": system_eq
        }, function (data) {
            var html = "";
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["equipID "] + "\">" + data[i]["equipName"] + "</option>";
                $("#equipment").html(html);
            }
        });
    }).change();
    //用getJSON讀取data內的資料(班別)
    $.getJSON("php/data.php", {
        colID: 'shiftID',
        colName: 'shiftName'
    }, function (data) {
        for (let i = 0; i < data.length; i++) {
            $("#Three_shifts").append('<option value="' + data[i]["shiftID"] + '">' + data[i]["shiftName"] + '</option>');
        }
    });
    //===================================mtinsert結束(與mtupdata共用)===================================
});