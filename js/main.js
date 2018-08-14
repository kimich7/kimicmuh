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
    $(".cookiecs").change(function () {
        $.getJSON("php/data_class.php", {
            rankdate: $("#rank1date").val(),
            rank: $("#rank1").val(),
            courtyard: $("#cydID").val()
        }, function (data) {
            $("#resultdate").text(data);
        });
    })
    // $("#cardSendBtn1").click(function () {
    //     $.getJSON("php/data_class.php", {
    //         rankdate: $("#rank1date").val(),
    //         rank: $("#rank1").val()
    //     }, function (data) {
    //         $("#resultdate").text(data);
    //     });
    // });

    // 使用getJSON讀取mainlist.json內的title資料
    $.getJSON("json/mainlist.json", function (data) {
        for (let i = 0; i < data.length; i++) {
            $(".maindata" + i).html(data[i].name).attr("href", data[i].url);
        }
    });
    $(".maindata0").click(function () {
        $.getJSON("json/mainlist.json", function (data) {
            $("#cardSendBtn1").attr("href", data[0].url);
        });
    });
    $(".maindata1").click(function () {
        $.getJSON("json/mainlist.json", function (data) {
            $("#cardSendBtn1").attr("href", data[1].url);
        });
    });
    $(".maindata3").click(function () {
        $.getJSON("json/mainlist.json", function (data) {
            $("#cardSendBtn1").attr("href", data[3].url);
        });
    });
    $(".maindata4").click(function () {
        $.getJSON("json/mainlist.json", function (data) {
            $("#cardSendBtn1").attr("href", data[4].url);
        });
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
    $(document).ready(function () {
        $('#tablepanel').tabs();
    })

    //取得預載資料
    $.getJSON("php/cookiedata.php", function (data) {
        //取得日期
        $("#bday").attr("value", data[0]['date']);
        //取得班別
        $("#Three_shifts").html('<option value="' + data[0]["class"] + '">' + data[0]["shiftclass"] + "</option>");
        //取得院區
        $("#courtyard").html('<option value="' + data[0]["courtyardID"] + '">' + data[0]["courtyardName"] + "</option>");
        //取得日期(修改)
        $("#ubday").attr("value", data[0]['date']);
        //取得班別(修改)
        $("#uThree_shifts").html('<option value="' + data[0]["class"] + '">' + data[0]["shiftclass"] + "</option>");
        //取得院區(修改)
        $("#ucourtyard").html('<option value="' + data[0]["courtyardID"] + '">' + data[0]["courtyardName"] + "</option>");
        //如果cookie內有棟別&樓層則載入----20180810
        // if (data[0]["floorID"]) {
        //     html = "<option value=\"" + data[0]["buildID"] + "\">" + data[0]["buildName"] + "</option>";
        //     $("#build").html(html);
        //     $("#buildingfloor").html('<option value="' + data[0]["floorID"] + '">' + data[0]["floorName"] + "</option>");
        // }
    });



    //用getJSON讀取data內的資料(院區)
    $("#courtyard,#ucourtyard").one("click", function () {
        $.getJSON("php/data.php", {
            colID: 'c_number',
            colName: 'courtyard'
        }, function (data) {
            var html = '<option selected> 請選擇院區 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["c_number"] + "\">" + data[i]["courtyard"] + "</option>";
                $("#courtyard").html(html);
                $("#ucourtyard").html(html);
            }
        });
    });
    //用getJSON讀取data內的資料(班別)
    $("#Three_shifts,#uThree_shifts").one("click", function () {
        $.getJSON("php/data.php", {
            colID: 'shiftID',
            colName: 'shiftName'
        }, function (data) {
            var html = '<option selected> 請選擇班別 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["shiftID"] + "\">" + data[i]["shiftName"] + "</option>";
                $("#Three_shifts").html(html);
                $("#uThree_shifts").html(html);
            }
        });
    });

    //用getJSON讀取data內的資料(updata_build非單站修改)
    $("#build_updata").one("click", function () {
        $.getJSON("php/data.php", {
            colID: 'b_number',
            colName: 'B_name',
        }, function (data) {
            var html = '<option selected> 請選擇棟別 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["b_number"] + "\">" + data[i]["B_name"] + "</option>";
                $("#build_updata").html(html);
            }
        });
    });
    //用getJSON讀取data內的資料(updata_system非單站修改)
    $("#system_updata").one("click", function () {
        $.getJSON("php/data.php", {
            colID: 'sysID',
            colName: 'sysName',
        }, function (data) {
            var html = '<option selected> 請選擇系統 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["sysID"] + "\">" + data[i]["sysName"] + "</option>";
                $("#system_updata").html(html);
            }
        });
    });


    //用getJSON讀取data內的資料(棟別)
    $.getJSON("php/cookiedata.php", function (data) {
        if (data[0]["floorID"]) {
            var html = "<option value=\"" + data[0]["buildID"] + "\">" + data[0]["buildName"] + "</option>";
            var html_floor = "<option value=\"" + data[0]["floorID"] + "\">" + data[0]["floorName"] + "</option>"
            // var cyID = $("#courtyard").val();
            // $.getJSON("php/insertdata.php", {
            //     colID: 'b_number',
            //     colName: 'B_name',
            //     cyID: cyID,
            //     seachNo: '2'
            // }, function (data) {
            //     for (let i = 0; i < data.length; i++) {
            //         html += "<option value=\"" + data[i]["b_number"] + "\">" + data[i]["B_name"] + "</option>";
            //         $("#build").html(html);
            //     }
            // });
            $("#build").html(html);
            $("#buildingfloor").html(html_floor);
            // var buildNo = $("#build").val();
            // $.getJSON("php/insertfloor.php", {
            //     "buildNo": buildNo
            // }, function (data) {
            //     for (let i = 0; i < data.length; i++) {
            //         html_floor += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
            //         $("#buildingfloor").html(html_floor);
            //     }
            // })
        } else {
            $.getJSON("php/insertdata.php", {
                colID: 'b_number',
                colName: 'B_name',
                seachNo: '1'
            }, function (data) {
                var html = '<option value="" selected> 請選擇棟別 </option>';
                for (let i = 0; i < data.length; i++) {
                    html += "<option value=\"" + data[i]["b_number"] + "\">" + data[i]["B_name"] + "</option>";
                    $("#build").html(html);
                    $("#ubuild").html(html);
                }
            });
        }
    });

    $("#build").one("click", function () {
        var cyID = $("#courtyard").val();
        $.getJSON("php/insertdata.php", {
            colID: 'b_number',
            colName: 'B_name',
            cyID: cyID,
            seachNo: '2'
        }, function (data) {
            var html = '<option selected> 請選擇棟別 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["b_number"] + "\">" + data[i]["B_name"] + "</option>";
                $("#build").html(html);
            }
        });
    })

    $("#ubuild").one("click", function () {
        var cyID = $("#ucourtyard").val();
        $.getJSON("php/insertdata.php", {
            colID: 'b_number',
            colName: 'B_name',
            cyID: cyID,
            seachNo: '2'
        }, function (data) {
            var html = '<option selected> 請選擇棟別 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["b_number"] + "\">" + data[i]["B_name"] + "</option>";
                $("#ubuild").html(html);
            }
        });
    })

    //院區更換時，棟別的選項也要跟著變
    $("#courtyard").change(function () {
        var cyID = $("#courtyard").val();
        $.getJSON("php/insertdata.php", {
            colID: 'b_number',
            colName: 'B_name',
            cyID: cyID,
            seachNo: '2'
        }, function (data) {
            var html = '<option value="" selected> 請選擇棟別 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["b_number"] + "\">" + data[i]["B_name"] + "</option>";
                $("#build").html(html);
            }
        });
    })
    $("#ucourtyard").change(function () {
        var cyID = $("#ucourtyard").val();
        $.getJSON("php/insertdata.php", {
            colID: 'b_number',
            colName: 'B_name',
            cyID: cyID,
            seachNo: '2'
        }, function (data) {
            var html = '<option value="" selected> 請選擇棟別 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["b_number"] + "\">" + data[i]["B_name"] + "</option>";
                $("#ubuild").html(html);
            }
        });
    })
    //mtinsert選擇樓層
    $("#buildingfloor").one("click", function () {
        var buildNo = $("#build").val();
        $.getJSON("php/insertfloor.php", {
            "buildNo": buildNo
        }, function (data) {
            var html = '<option value="" selected> 請選擇樓層 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
                $("#buildingfloor").html(html);
            }
        })
    }).change();

    $("#ubuildingfloor").one("click", function () {
        var buildNo = $("#build").val();
        $.getJSON("php/insertfloor.php", {
            "buildNo": buildNo
        }, function (data) {
            var html = '<option value="" selected> 請選擇樓層 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
                $("#ubuildingfloor").html(html);
            }
        })
    }).change();

    $("#build").change(function () {
        var buildNo = $("#build").val();
        $.getJSON("php/insertfloor.php", {
            "buildNo": buildNo
        }, function (data) {
            var html = '<option value="" selected> 請選擇樓層 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
                $("#buildingfloor").html(html);
            }
        })
    })

    //mtinsert修改的選擇樓層
    $("#ubuild").change(function () {
        var ubuildNo = $("#ubuild").val();
        $.getJSON("php/uinsertfloor.php", {
            "ubuildNo": ubuildNo
        }, function (data) {
            var html = '<option value="" selected> 請選擇樓層 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
                $("#ubuildingfloor").html(html);
            }
        })
    })

    //mtinsert依據所選的樓層選系統
    $("#buildingfloor").change(function () {
        var floorID = $("#buildingfloor").val();
        var rDate = $("#bday").val();
        var shiftID = $("#Three_shifts").val();
        var BuildID = $("#build").val();
        $.getJSON("php/insertsystem.php", {
            "floorID": floorID,
            "rDate": rDate,
            "shiftID": shiftID,
            "BuildID": BuildID
        }, function (data) {
            var html = '<option value="" selected> 請選擇系統 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["sysID"] + "\">" + data[i]["sysName"] + "</option>";
                $("#system").html(html);
            }
        })
    }); //.change();不能加.change

    $("#system").one("click", function () {
        var floorID = $("#buildingfloor").val();
        var rDate = $("#bday").val();
        var shiftID = $("#Three_shifts").val();
        var BuildID = $("#build").val();
        $.getJSON("php/insertsystem.php", {
            "floorID": floorID,
            "rDate": rDate,
            "shiftID": shiftID,
            "BuildID": BuildID
        }, function (data) {
            var html = '';
            if (data == "") {
                html = '<option value="" selected> 此樓層已沒有需要點檢的系統 </option>';
                $("#system").html(html);
            } else {
                html = '<option value="" selected> 請選擇系統 </option>';
                for (let i = 0; i < data.length; i++) {
                    html += "<option value=\"" + data[i]["sysID"] + "\">" + data[i]["sysName"] + "</option>";
                    $("#system").html(html);
                }
            }
        })
    })

    //mtinsert_UP依據所選的樓層選系統
    $("#ubuildingfloor").change(function () {
        var ufloorID = $("#ubuildingfloor").val();
        $.getJSON("php/uinsertsystem.php", {
            "ufloorID": ufloorID
        }, function (data) {
            var html = '<option value="" selected> 請選擇系統 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["sysID"] + "\">" + data[i]["sysName"] + "</option>";
                $("#usystem").html(html);
            }
        })
    })

    //mtinsert選擇樓層
    // $(".f1").change(function () {
    //     var system_eq = $("#system").val();
    //     var building_eq = $("#build").val();
    //     var rDate = $("#bday").val();
    //     var now_class = $("#Three_shifts").val();

    // if (system_eq == 4) {
    //     choiceNo = 1;
    // }
    //     $.getJSON("php/zone.php", {
    //         "system_eq": system_eq,
    //         "build_eq": building_eq,
    //         "rDate": rDate,
    //         "now_class": now_class,
    //         // "insert_revise": insert_revise
    //     }, function (data) {
    //         var html = '<option selected> 請選擇樓層 </option>';
    //         for (let i = 0; i < data.length; i++) {
    //             html += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
    //             $("#buildingfloor").html(html);
    //         }
    //     });
    // });

    //mtinsert選擇設備
    $(".f2").change(function () {
        var system_eq = $("#system").val();
        var building_eq = $("#build").val();
        var rDate = $("#bday").val();
        var now_class = $("#Three_shifts").val();
        var floor_eq = $("#buildingfloor").val();
        var choiceNo = 0;
        if (system_eq == 4) {
            choiceNo = 1;
        }
        $.getJSON("php/ajax_system.php", {
            "system_eq": system_eq,
            "build_eq": building_eq,
            "floor_eq": floor_eq,
            "rDate": rDate,
            "now_class": now_class,
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

    //個人八小時內的修改
    $("#reupdata").click(function () {
        var system_eq = $("#usystem").val();
        var building_eq = $("#ubuild").val();
        var floor_eq = $("#ubuildingfloor").val();
        var rdate = $("#ubday").val();
        //var equipment = $("#uequipment").val();
        var shift = $("#uThree_shifts").val();
        //$("#reupdata").attr("href","reupdata.php?date=\""+rdate+"\"\& systemID=\""+system_eq+"\"\& shifts=\""+shift+"\"\& build=\""+building_eq+"\"\& floor=\""+floor_eq+"\"\& equipment=\""+equipment);
        $("#reupdata").attr("href", "reupdata.php?date=" + rdate + "&systemID=" + system_eq + "&shift=" + shift + "&build=" + building_eq + "&floor=" + floor_eq + ""); //"&equipment=" + equipment + "");
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