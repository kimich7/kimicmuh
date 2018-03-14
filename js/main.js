//當畫面元素載入完成後才執行主程式
$(document).ready(function () {
    //啟用wow.js
    new WOW().init();
    $("#navbar").load("navbar.html");
    $("#header").load("header.html");
    $("#footer").load("footer.html");
});

//上方這一串指令可以寫成 $(function () {});