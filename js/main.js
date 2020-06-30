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
        var $notice = data[0]['notice'];
        if (data[0]["login_success"] == false) {
            $("#result").html("帳號或密碼錯誤，請重新輸入");
        } else {
            $("#result").html("歡迎登入," + $Name);
            //登入隱藏/登出顯示
            $("#logInBtn").toggleClass("d-none");
            $("#logOutBtn").toggleClass("d-none");
            $("#changepassword").toggleClass("d-none");
            switch (data[0]["login_authority"]) {
                case "1":
                    $(".autho1,.autho2,.autho3,.autho4").removeAttr("disabled").removeAttr("style");
                    $(".billBoard1,.billBoard2,.billBoard3,.billBoard4").tooltip('dispose');
                    break;
                case "2":
                    $(".autho2,.autho3,.autho4").removeAttr("disabled").removeAttr("style");
                    $(".billBoard2,.billBoard3,.billBoard4").tooltip('dispose');
                    break;
                case "3":
                    $(".autho3,.autho4").removeAttr("disabled").removeAttr("style");
                    $(".billBoard3,.billBoard4").tooltip('dispose');
                    break;
                case "4":
                    $(".autho4").removeAttr("disabled").removeAttr("style");
                    $(".billBoard4").tooltip('dispose');
                    break;
            }
        }
        //產生新的異常案件，並且是由主導主管登錄時的顯示
        if ($notice == 1) {
            $.getJSON("php/abnormalNum.php", function (data) {
                var html = '<span class="badge badge-light" >' + data[0] + '</span>';
                $("#notice1").html(html);
            })
            $.getJSON("php/abnormalCaseReviewNum.php", function (data) {
                var html1 = '<span class="badge badge-light" >' + data[0] + '</span>';
                $("#caseReview1").html(html1);
            })
            $("#notice").toggleClass("d-none");
            $("#caseReview").toggleClass("d-none");
        }
    });

    //----20190820 Hyperlink Disabled 在chrome無效需要此段程式
    $(function () {
        //避免 disabled 的 hyperlink 被觸發， chrome,firefox 需要這段處理
        //判斷每一個超連結，如果屬性有設 disabled，就避免觸發 click 事件。
        $("a").click(function () {
            if ($(this).attr("disabled") == "disabled") {
                event.preventDefault();
            }
        });
    });
    //--------Hyperlink Disabled無效解決方式end---------------

    // 20190818登入者等級待啟用
    $.getJSON("php/security.php", function (data) {
        var num = data.length;
        for (let i = 0; i < num; i++) {
            var $securitylevel = data[i]["sid"];
            switch ($securitylevel) {
                case "1": //抄表空調系統保養人員
                    $(".mtcheck,.employeeCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoardcheck").tooltip('dispose');
                    break;
                case "2": //抄表空調系統審核人員
                    $(".mtcheck,.employeeCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoardcheck").tooltip('dispose');
                    break;
                case "3": //抄表消防系統保養人員
                    $(".mtcheck,.employeeCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoardcheck").tooltip('dispose');
                    break;
                case "4": //抄表消防系統廠商
                    $(".mtcheck,.employeeCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoardcheck").tooltip('dispose');
                    break;
                case "5": //抄表消防系統審核人員
                    $(".mtcheck,.managerCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoardcheck").tooltip('dispose');
                    break;
                case "6": //抄表氣體系統保養人員
                    $(".mtcheck,.managerCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoardcheck").tooltip('dispose');
                    break;
                case "7": //抄表氣體系統審核人員
                    $(".mtcheck,.managerCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoardcheck").tooltip('dispose');
                    break;
                case "8": //抄表氣體系統審核人員
                    $(".mtcheck,.managerCheck").removeAttr("disabled").removeAttr("style");
                    $(".billBoardcheck").tooltip('dispose');
                    break;
                    //----保養權限------
                case "9": //保養空調系統保養人員
                    $(".airconL1").removeAttr("disabled").removeAttr("style");
                    $(".billBoardairconL1").tooltip('dispose');
                    break;
                case "10": //保養空調系統專責
                    $(".airconL2").removeAttr("disabled").removeAttr("style");
                    $(".billBoardairconL2").tooltip('dispose');
                    break;
                case "11": //保養空調系統主管
                    $(".airconL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoardairconL3").tooltip('dispose');
                    break;
                case "12": //保養建築物保養人員
                    $(".buildL1").removeAttr("disabled").removeAttr("style");
                    $(".billBoardbuildL1").tooltip('dispose');
                    break;
                case "13": //保養建築物專責
                    $(".buildL2").removeAttr("disabled").removeAttr("style");
                    $(".billBoardbuildL2").tooltip('dispose');
                    break;
                case "14": //保養建築物主管
                    $(".buildL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoardbuildL3").tooltip('dispose');
                    break;
                case "15": //保養電力保養人員
                    $(".powerL1").removeAttr("disabled").removeAttr("style");
                    $(".billBoardpowerL1").tooltip('dispose');
                    break;
                case "16": //保養電力專責
                    $(".powerL2").removeAttr("disabled").removeAttr("style");
                    $(".billBoardpowerL2").tooltip('dispose');
                    break;
                case "17": //保養電力主管
                    $(".powerL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoardpowerL3").tooltip('dispose');
                    break;
                case "18": //保養消防保養人員
                    $(".fireL1").removeAttr("disabled").removeAttr("style");
                    $(".billBoardfireL1").tooltip('dispose');
                    break;
                case "19": //保養消防專責
                    $(".fireL2").removeAttr("disabled").removeAttr("style");
                    $(".billBoardfireL2").tooltip('dispose');
                    break;
                case "20": //保養消防主管
                    $(".fireL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoardfireL3").tooltip('dispose');
                    break;
                case "21": //保養氣體保養人員
                    $(".airL1").removeAttr("disabled").removeAttr("style");
                    $(".billBoardairL1").tooltip('dispose');
                    break;
                case "22": //保養氣體專責
                    $(".airL2").removeAttr("disabled").removeAttr("style");
                    $(".billBoardairL2").tooltip('dispose');
                    break;
                case "23": //保養氣體主管
                    $(".airL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoardairL3").tooltip('dispose');
                    break;
                case "24": //保養傳送保養人員
                    $(".conveyL1").removeAttr("disabled").removeAttr("style");
                    $(".billBoardconveyL1").tooltip('dispose');
                    break;
                case "25": //保養傳送專責
                    $(".conveyL2").removeAttr("disabled").removeAttr("style");
                    $(".billBoardconveyL2").tooltip('dispose');
                    break;
                case "26": //保養傳送主管
                    $(".conveyL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoardconveyL3").tooltip('dispose');
                    break;
                case "27": //保養監控保養人員
                    $(".monitorL1").removeAttr("disabled").removeAttr("style");
                    $(".billBoardmonitorL1").tooltip('dispose');
                    break;
                case "28": //保養監控專責
                    $(".monitorL2").removeAttr("disabled").removeAttr("style");
                    $(".billBoardmonitorL2").tooltip('dispose');
                    break;
                case "29": //保養監控主管
                    $(".monitorL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoardmonitorL3").tooltip('dispose');
                    break;
                case "30": //保養水保養人員
                    $(".waterL1").removeAttr("disabled").removeAttr("style");
                    $(".billBoardwaterL1").tooltip('dispose');
                    break;
                case "31": //保養水專責
                    $(".waterL2").removeAttr("disabled").removeAttr("style");
                    $(".billBoardwaterL2").tooltip('dispose');
                    break;
                case "32": //保養水主管
                    $(".waterL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoardwaterL3").tooltip('dispose');
                    break;
                case "33": //異常系統主管
                    $(".errL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoarderrL3").tooltip('dispose');
                    break;
                case "34": //工程系統主管
                    $(".engL3").removeAttr("disabled").removeAttr("style");
                    $(".billBoardengL3").tooltip('dispose');
                    break;
            }
        }
    });

    //------20190902保養表單各系統權限設定
    $("#mmtbtn").click(function () {
        var mmtsys = $("#mmtchoice").val();
        $.getJSON("php/security.php", function (data) {
            var num = data.length;
            var key = 0;
            var sysname = '';
            for (let i = 0; i < num; i++) {
                var $securitylevelstr = data[i]["sid"];
                $securitylevel = parseInt($securitylevelstr);
                if ($securitylevel > 8 && $securitylevel < 12) {
                    if (mmtsys == 'A') {
                        key = 1;
                    }
                } else if ($securitylevel > 11 && $securitylevel < 15) {
                    if (mmtsys == 'B') {
                        key = 1;
                    }
                } else if ($securitylevel > 14 && $securitylevel < 18) {
                    if (mmtsys == 'E') {
                        key = 1;
                    }
                } else if ($securitylevel > 17 && $securitylevel < 21) {
                    if (mmtsys == 'F') {
                        key = 1;
                    }
                } else if ($securitylevel > 20 && $securitylevel < 24) {
                    if (mmtsys == 'G') {
                        key = 1;
                    }
                } else if ($securitylevel > 23 && $securitylevel < 27) {
                    if (mmtsys == 'H') {
                        key = 1;
                    }
                } else if ($securitylevel > 26 && $securitylevel < 30) {
                    if (mmtsys == 'M') {
                        key = 1;
                    }
                } else if ($securitylevel > 29 && $securitylevel <= 32) {
                    if (mmtsys == 'W') {
                        key = 1;
                    }
                } else {
                    key = 0;
                }
            }
            if (key == 1) {
                $(".mmtsyskey").removeAttr("disabled").removeAttr("style");
                $(".billBoardmmtsyskey").tooltip('dispose');
                $("#MMT_Kind").submit();
            } else {
                alert('請用該系統相關人員身分登入');
            }
        })

    })
    //-------end------



    //被指派人員通知
    $.getJSON("php/abnormalDesEmp.php", function (data) {
        var $num = data[0];
        if ($num > 0) {
            var html = '<span class="badge badge-light" >' + data[0] + '</span>';
            $("#dseEmpNotice1").html(html);
            $("#dseEmpNotice").toggleClass("d-none");
        }
    })
    //異常案件明細通知
    $("#notice").click(function () {
        $("#notice").attr("href", "./abnormalLink.php?filter=no");
    })
    //被指派的異常案件明細通知
    $("#dseEmpNotice").click(function () {
        $("#dseEmpNotice").attr("href", "./abnormalDesLink.php?filter=no");
    })
    //待審核異常案件明細通知
    $("#caseReview").click(function () {
        $("#caseReview").attr("href", "./abnormalCaseReviewLink.php?filter=no");
    })
    //異常案件待指派人員清單
    $.getJSON("php/emp.php", function (data) {
        var html = '<option value=""> 請選擇人員 </option>';
        for (let i = 0; i < data.length; i++) {
            html += "<option value=\"" + data[i]["e_number"] + "\">" + data[i]["cname"] + "</option>";
            $(".desemp").html(html);
            $("#findemp").html(html);
        }
    })

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


    // 使用getJSON讀取mainlist.json內的title資料
    $.getJSON("json/mainlist.json", function (data) {
        for (let i = 0; i < data.length; i++) {
            $(".maindata" + i).html(data[i].name).attr("href", data[i].url);
        }
    });
    //工務設備運轉抄表文件新增表單 mtinsert.html
    $(".maindata0").click(function () {
        $.getJSON("json/mainlist.json", function (data) {
            $("#cardSendBtn1").attr("href", data[0].url);
        });
    });
    //工務設備運轉抄表文件修改表單 mtupdata.html
    // $(".maindata1").click(function () {
    //     $.getJSON("json/mainlist.json", function (data) {
    //         $("#cardSendBtn1").attr("href", data[1].url);
    //     });
    // });



    //20191127end

    //20190613新增    
    //把系統塞到卡片上做選擇
    $("#mainBtn2").one("click", function () {
        $.getJSON("php/mmtchoice.php", function (data) {
            var html = '<option value="" selected> 選擇系統 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["id"] + "\">" + data[i]["sName"] + "</option>";
                $("#mmtchoice").html(html);
            }
        });
    })

    //保養相關cookie
    $(".mmtcookiecs").change(function () {
        $.getJSON("php/mmtsyscookie.php", {
            mmtsysNo: $("#mmtchoice").val()
        }, function (data) {
            // alert(data);
            // $("#mmtsysa").html('<option value="' + data[0]["mmtsysNo"] + '"  selected>' + data[0]["mmtsysNo"] + '_' + data[0]["mmtsysName"] + "</option>");
        });
    });
    //取得保養系統的大樓
    $("#mmtbuilda").one('click', function () {
        var switched = 1;
        $.getJSON("php/mmtsys_a_data.php", {
            switchchoice: switched,
            mmtsysNo: $("#mmtsysa").val()
        }, function (data) {
            var html = '<option value="" selected> 請選擇大樓 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["id"] + "\">" + data[i]["bName"] + "</option>";
                $("#mmtbuilda").html(html);
            }
        });
    }).change();
    //取得保養系統的樓層
    $("#mmtbuilda").change(function () {
        var switched = 2;
        $.getJSON("php/mmtsys_a_data.php", {
            switchchoice: switched,
            mmtsysNo: $("#mmtsysa").val(),
            mmtbuildNo: $("#mmtbuilda").val()
        }, function (data) {
            var html = '<option value="" selected> 請選擇樓層 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["fid"] + "\">" + data[i]["fName"] + "</option>";
                $("#mmtfloora").html(html);
            }
        });
    }).change();
    //取得保養系統的設備
    $("#mmtfloora").change(function () {
        var switched = 3;
        $.getJSON("php/mmtsys_a_data.php", {
            switchchoice: switched,
            mmtsysNo: $("#mmtsysa").val(),
            mmtbuildNo: $("#mmtbuilda").val(),
            mmtfloorNo: $("#mmtfloora").val()
        }, function (data) {
            var html = '<option value="" selected> 請選擇設備 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["id"] + "\">" + data[i]["eName"] + "</option>";
                $("#mmtequipa").html(html);
            }
        });
    }).change();

    //取得保養系統設備的編號
    $("#mmtequipa").change(function () {
        var switched = 4;
        $.getJSON("php/mmtsys_a_data.php", {
            switchchoice: switched,
            mmtsysNo: $("#mmtsysa").val(),
            mmtbuildNo: $("#mmtbuilda").val(),
            mmtfloorNo: $("#mmtfloora").val(),
            mmtequipa: $("#mmtequipa").val()
        }, function (data) {
            var html = '<option value="" selected> 請選擇設備編號 </option>';
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["id"] + "\">" + data[i]["eid"] + "-" + data[i]["id"] + "</option>";
                $("#mmtequipNoa").html(html);
            }
        });
    }).change();



    //根據不同的系統去不同的保養清單
    $("#mmtchoice").change(function () {
        var mmtsys = $("#mmtchoice").val();
        switch (mmtsys) {
            case 'A':
                url = "mmt_list_a.php";
                break;
            case 'B':
                url = "mmt_list_b.php";
                break;
            case 'E':
                url = "mmt_list_e.php";
                break;
            case 'F':
                url = "mmt_list_f.php";
                break;
            case 'G':
                url = "mmt_list_g.php";
                break;
            case 'H':
                url = "mmt_list_h.php";
                break;
            case 'M':
                url = "mmt_list_m.php";
                break;
            case 'W':
                url = "mmt_list_w.php";
                break;
        }
        document.MMT_Kind.action = url;

    })

    //--END--
    //
    //務設備保養紀錄文件新增表單
    $(".maindata4").click(function () {
        $.getJSON("json/mainlist.json", function (data) {
            $("#cardSendBtn1").attr("href", data[4].url);
        });
    });
    //工務設備保養紀錄文件修改表單
    $(".maindata5").click(function () {
        $.getJSON("json/mainlist.json", function (data) {
            $("#cardSendBtn1").attr("href", data[5].url);
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
    });

    $("#build").one("click", function () { //one("click", function ()
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
    // $("#courtyard").one("click", function () { //change->one("click")
    //     var cyID = $("#courtyard").val();
    //     $.getJSON("php/insertdata.php", {
    //         colID: 'b_number',
    //         colName: 'B_name',
    //         cyID: cyID,
    //         seachNo: '2'
    //     }, function (data) {
    //         var html = '<option value="" selected> 請選擇棟別 </option>';
    //         for (let i = 0; i < data.length; i++) {
    //             html += "<option value=\"" + data[i]["b_number"] + "\">" + data[i]["B_name"] + "</option>";
    //             $("#build").html(html);
    //         }
    //     });
    // }).change();
    //----20190814測試----
    $("#courtyard").change(function () { //change->one("click")
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
    }).change();
    //-------------------


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
        var rDate = $("#bday").val();
        var shiftID = $("#Three_shifts").val();
        $.getJSON("php/insertfloor.php", {
            "buildNo": buildNo,
            "rDate": rDate,
            "shiftID": shiftID
        }, function (data) {
            var html = '<option value="" selected> 請選擇樓層 </option>';
            console.log(data);
            for (let i = 0; i < data.length; i++) {
                html += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
                $("#buildingfloor").html(html);
            }
        })
    });

    // $("#buildingfloor").on("click", function () {
    //     var buildNo = $("#build").val();
    //     var rDate = $("#bday").val();
    //     var shiftID = $("#Three_shifts").val();
    //     $.ajax({
    //         url: 'php/insertfloor.php',
    //         cache: false,
    //         type: 'get',
    //         fileElementId: 'file',
    //         datatype: 'JSON',
    //         data: {
    //             "buildNo": buildNo,
    //             "rDate": rDate,
    //             "shiftID": shiftID
    //         },
    //         success: function (data) {
    //             var html = '<option value="" selected> 請選擇樓層 </option>';
    //             for (let i = 0; i < data.length; i++) {
    //                 html += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
    //                 $("#buildingfloor").html(html);
    //             }
    //         },
    //         error: function (e) {
    //             console.log(e.message);
    //         }
    //     })
    // }).change();

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
        var rDate = $("#bday").val();
        var shiftID = $("#Three_shifts").val();
        $.getJSON("php/insertfloor.php", {
            "buildNo": buildNo,
            "rDate": rDate,
            "shiftID": shiftID
        }, function (data) {
            if (data == "") {
                html = '<option value="" selected> 此大樓已沒有需要抄表的樓層 </option>';
                $("#buildingfloor").html(html);
            } else {
                var html = '<option value="" selected> 請選擇樓層 </option>';
                for (let i = 0; i < data.length; i++) {
                    html += "<option value=\"" + data[i]["floorID"] + "\">" + data[i]["floorName"] + "</option>";
                    $("#buildingfloor").html(html);
                }
            }
        })
    }).change();

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
    // $("#buildingfloor").change(function () {
    //     var floorID = $("#buildingfloor").val();
    //     var rDate = $("#bday").val();
    //     var shiftID = $("#Three_shifts").val();
    //     var BuildID = $("#build").val();
    //     $.getJSON("php/insertsystem.php", {
    //         "floorID": floorID,
    //         "rDate": rDate,
    //         "shiftID": shiftID,
    //         "BuildID": BuildID
    //     }, function (data) {
    //         var html = '<option value="" selected> 請選擇系統 </option>';
    //         for (let i = 0; i < data.length; i++) {
    //             html += "<option value=\"" + data[i]["sysID"] + "\">" + data[i]["sysName"] + "</option>";
    //             $("#system").html(html);
    //         }
    //     })
    // }); //.change();不能加.change

    // $("#system").one("click", function () {
    //     var floorID = $("#buildingfloor").val();
    //     var rDate = $("#bday").val();
    //     var shiftID = $("#Three_shifts").val();
    //     var BuildID = $("#build").val();
    //     $.getJSON("php/insertsystem.php", {
    //         "floorID": floorID,
    //         "rDate": rDate,
    //         "shiftID": shiftID,
    //         "BuildID": BuildID
    //     }, function (data) {
    //         var html = '';
    //         if (data == "") {
    //             html = '<option value="" selected> 此樓層已沒有需要點檢的系統 </option>';
    //             $("#system").html(html);
    //         } else {
    //             html = '<option value="" selected> 請選擇系統 </option>';
    //             for (let i = 0; i < data.length; i++) {
    //                 html += "<option value=\"" + data[i]["sysID"] + "\">" + data[i]["sysName"] + "</option>";
    //                 $("#system").html(html);
    //             }
    //         }
    //     })
    // })

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
    //mtinsert選擇設備
    // $(".f2").change(function () {
    //     var system_eq = $("#system").val();
    //     var building_eq = $("#build").val();
    //     var rDate = $("#bday").val();
    //     var now_class = $("#Three_shifts").val();
    //     var floor_eq = $("#buildingfloor").val();
    //     var choiceNo = 0;
    //     if (system_eq == 4) {
    //         choiceNo = 1;
    //     }
    //     $.getJSON("php/ajax_system.php", {
    //         "system_eq": system_eq,
    //         "build_eq": building_eq,
    //         "floor_eq": floor_eq,
    //         "rDate": rDate,
    //         "now_class": now_class,
    //         "choiceNo": choiceNo
    //     }, function (data) {
    //         var html = '<option value=""> 請選擇設備/區域 </option>';
    //         if (choiceNo == 1) {
    //             for (let i = 0; i < data.length; i++) {
    //                 html += "<option value=\"" + data[i]["zoneNo"] + "\">" + data[i]["zoneName"] + "</option>";
    //                 $("#equipment").html(html);
    //             }
    //         } else {
    //             for (let i = 0; i < data.length; i++) {
    //                 html += "<option value=\"" + data[i]["equipID"] + "\">" + data[i]["equipName"] + "</option>";
    //                 $("#equipment").html(html);
    //             }
    //         }
    //     });
    // });


    //異常表單填寫上傳
    $("#errorbtn").click(function () {
        var errdate = $("#errordate").val();
        var errloction = $("#errortextlocation").val();
        var erremp = $("#findemp").val();
        var errtitle = $("#errortexttitle").val();
        var errfile = $("#FormControlFile1").val();
        var errtext = $("#errortext").val();
        if (errdate == "" | errloction == "" | erremp == "" | errtitle == "" | errtext == "") {
            if (errdate == "") {
                alert("日期不可空白");
                jQuery("#errordate").focus();
            }
            if (errloction == "") {
                alert("地點不可空白");
                jQuery("#errortextlocation").focus();
            }
            if (erremp == "") {
                alert("發現者不可空白");
                jQuery("#findemp").focus();
            }
            if (errtitle == "") {
                alert("主旨不可空白");
                jQuery("#errortexttitle").focus();
            }
            if (errtext == "") {
                alert("異常情況描述不可空白");
                jQuery("#errortext").focus();
            }
        } else {

        }
    })

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



    $('#emptable').bootstrapTable({
        columns: [ //欄位設定
            {
                field: 'id',
                title: '人員編號',
                align: 'center',
                width: 40,
                visible: true,
                sortable: true
            },
            {
                field: 'name',
                title: '人員姓名',
                align: 'center',
                width: 40,
                visible: true,
                sortable: true
            },
            {
                field: 'edit',
                title: '功能',
                align: 'center',
                width: 40,
                visible: true
            }
        ],
        classes: 'table',
        data: getRandomData(), //所有資料
        uniqueId: 'id', //哪一個欄位是key
        sortName: 'id', //依照那個欄位排序			
        height: 450,
        pagination: true, //使否要分頁

        //可於ToolBar上顯示的按鈕
        showColumns: true, //顯示/隱藏哪些欄位
        showToggle: true, //名片式/table式切換
        showPaginationSwitch: true, //分頁/不分頁切換
        showRefresh: true, //重新整理
        search: true, //查詢

        onPageChange: function (currentPage, pageSize) {
            console.log("目前頁數:" + currentPage + ",一頁顯示:" + pageSize + "筆");
        },
        pageSize: 10, //一頁顯示幾筆
        pageList: [10, 20, 50, 100], //一頁顯示幾筆的選項

        formatRecordsPerPage: function (pageSize) {
            return '&nbsp;&nbsp;每頁顯示' + pageSize + '筆';
        },
        formatShowingRows: function (fromIndex, toIndex, totalSize) {
            //目前第幾頁
            var currentPage = Math.ceil(fromIndex / this.pageSize);
            //總共幾頁
            var totalPageCount = Math.ceil(totalSize / this.pageSize);
            return '第' + currentPage + '頁&nbsp;&nbsp;共' + totalPageCount + '頁';
        }
    });
    $('#emptable').bootstrapTable('load', data);

    function getRandomData() {
        $.getJSON("php/testbootstraptable.php", function (data) {
            var ans = [];
            for (var i = 0; i < data.length; i++) {
                ans.push({
                    id: data[i]["e_number"],
                    name: data[i]["cname"],
                    edit: ""
                });
            }
            $('#emptable').bootstrapTable('load', ans);
            return ans;
        })
    }


    //===================================mtinsert結束(與mtupdata共用)===================================
    //===================================修改密碼&人員管理=====================
    $("#btn_modify").click(function () {
        var old_passwd = $("#old_passwd").val();
        var new_passwd = $("#new_passwd").val();
        var check_passwd = $("#check_passwd").val();
        $.getJSON("php/changepassword.php", {
            "old_passwd": old_passwd,
            "new_passwd": new_passwd,
            "check_passwd": check_passwd
        }, function (data) {
            if (data == '密碼更換成功，下次請用新密碼登錄') {
                window.location.replace('../CMUH/index.html');
            }
        })
    })
});