//當畫面元素載入完成後才執行主程式
$(function () {
    //===================================全域開始===================================
    // 動態載入頁首頁尾
    $("#header").load("packageHtml/header.html");
    $("#footer").load("packageHtml/footer.html");
    $('[data-toggle="tooltip"]').tooltip();
    //===================================全域結束===================================
    //===================================首頁開始===================================
    //登入功能
    // 登入表單提交時要執行getJSON指令
    $.getJSON("php/sessionData.php", function (data) {
        var $Name = data[0]["login_member"];
        if (data[0]["login_success"] == false) {
            $("#result").html("帳號或密碼錯誤，請重新輸入");
        } else {
            $("#result").html("歡迎登入," + $Name);
            //登入隱藏/登出顯示
            $("#logInBtn").toggleClass("d-none");
            $("#logOutBtn").toggleClass("d-none");
            switch (data[0]["login_authority"]) {
                case "1":
                    $(".autho1,.autho2,.autho3,.autho4,.managerCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoard1,.billBoard2,.billBoard3,.billBoard4").tooltip('dispose');
                    break;
                case "2":
                    $(".autho2,.autho3,.autho4,.managerCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoard2,.billBoard3,.billBoard4").tooltip('dispose');
                    break;
                case "3":
                    $(".autho3,.autho4,.employeeCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoard3,.billBoard4").tooltip('dispose');
                    break;
                case "4":
                    $(".autho4").removeAttr("disabled").removeAttr("style");
                    $(".billBoard4").tooltip('dispose');
                    break;
            }
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
    //選擇mainMenu新增Class(套用Bootstrap)
    $(".mainMenu").addClass("col-xl-2 col-lg-2 col-md-5 col-sm-5 col-10 mx-auto my-5 py-3 text-center");
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
    //用getJSON讀取data內的資料(班別)
    $("#Three_shifts").one("click", function () {
        $.getJSON("php/data.php", {
            colID: 'shiftID',
            colName: 'shiftName'
        }, function (data) {
            var html = '<option selected> 請選擇班別 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["shiftID"] + "\">" + data[i]["shiftName"] + "</option>";
                $("#Three_shifts").html(html);
            }
        });
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
    $(".f1").change(function () {
        var system_eq = $("#system").val();
        var building_eq = $("#build").val();
        var choiceNo = 0;
        if (system_eq == 4) {
            choiceNo = 1;
        }
        $.getJSON("php/zone.php", {
            "system_eq": system_eq,
            "build_eq": building_eq,
            "choiceNo": choiceNo
        }, function (data) {
            var html = '<option selected> 請選擇樓層 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
                $("#buildingfloor").html(html);
            }
        });
    });
    //mtinsert選擇設備
    $(".f2").change(function () {
        var system_eq = $("#system").val();
        var building_eq = $("#build").val();
        var floor_eq = $("#buildingfloor").val();
        var choiceNo = 0;
        if (system_eq == 4) {
            choiceNo = 1;
        }
        $.getJSON("php/ajax_system.php", {
            "system_eq": system_eq,
            "build_eq": building_eq,
            "floor_eq": floor_eq,
            "choiceNo": choiceNo
        }, function (data) {
            var html = '<option value=""> 請選擇設備/區域 </option>';
            if (choiceNo == 1) {
                for (let i = 0; i < data.length; i++) {
                    html += "<option value=\"" + data[i]["zoneNo"] + "\">" + data[i]["zoneName"] + "</option>";
                    $("#equipment").html(html);
                }
            } else {
                for (let i = 0; i < data.length; i++) {
                    html += "<option value=\"" + data[i]["equipID"] + "\">" + data[i]["equipName"] + "</option>";
                    $("#equipment").html(html);
                }
            }

        });
    });
    $("#reupdata").click(function () {
        var system_eq = $("#system").val();
        var building_eq = $("#build").val();
        var floor_eq = $("#buildingfloor").val();
        var rdate = $("#bday").val();
        var equipment = $("#equipment").val();
        var shift = $("#Three_shifts").val();
        //$("#reupdata").attr("href","reupdata.php?date=\""+rdate+"\"\& systemID=\""+system_eq+"\"\& shifts=\""+shift+"\"\& build=\""+building_eq+"\"\& floor=\""+floor_eq+"\"\& equipment=\""+equipment);
        $("#reupdata").attr("href", "reupdata.php?date=" + rdate + "& systemID=" + system_eq + " & shift=" + shift + " & build=" + building_eq + " & floor=" + floor_eq + " & equipment=" + equipment + "");
    })
    //偵測螢幕寬，然後出現按鈕選單
    var $tall = window.screen.width;
    //微軟平板長為912，寬為1368
    if ($tall <= 912) {
        $("#tfbtn1").removeClass("d-none");
        $("#tfbtn2").removeClass("d-none");
        $("#tfbtn3").removeClass("d-none");
        $("#tfresult2").addClass("d-none");
        $("#tfresult3").addClass("d-none");
    }
    $("#tfbtn1").click(function (e) {
        e.preventDefault();
        $("#tfresult1").removeClass("d-none");
        $("#tfresult2").addClass("d-none");
        $("#tfresult3").addClass("d-none");
    });
    $("#tfbtn2").click(function (e) {
        e.preventDefault();
        $("#tfresult1").addClass("d-none");
        $("#tfresult2").removeClass("d-none");
        $("#tfresult3").addClass("d-none");
    });
    $("#tfbtn3").click(function (e) {
        e.preventDefault();
        $("#tfresult1").addClass("d-none");
        $("#tfresult2").addClass("d-none");
        $("#tfresult3").removeClass("d-none");
    });
    //===================================mtinsert結束(與mtupdata共用)===================================
});