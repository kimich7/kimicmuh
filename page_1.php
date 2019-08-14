<?php
function page($page,$total,$phpfile,$pagesize=10,$pagelen=7){
    $pagecode = '';//定義變數，存放分頁生成的HTML
    $page = intval($page);//避免非數字頁碼
    $total = intval($total);//保證總記錄數值類型正確
    if(!$total) return array();//總記錄數為零返回空數組
    $pages = ceil($total/$pagesize);//計算總分頁
    //處理頁碼合法性
    if($page<1) $page = 1;
    if($page>$pages) $page = $pages;
    //計算查詢偏移量
    $offset = $pagesize*($page-1);
    //頁碼範圍計算
    $init = 1;//起始頁碼數
    $max = $pages;//結束頁碼數
    $pagelen = ($pagelen%2)?$pagelen:$pagelen+1;//頁碼個數
    $pageoffset = ($pagelen-1)/2;//頁碼個數左右偏移量
    
    //生成html
    $pagecode='<div class="page">';
    $pagecode.="<span>$page/$pages</span>";//第幾頁,共幾頁
    //如果是第一頁，則不顯示第一頁和上一頁的連接
    if($page!=1){
        $pagecode.="<a href=\"{$phpfile}?page=1\">&lt;&lt;</a>";//第一頁
        $pagecode.="<a href=\"{$phpfile}?page=".($page-1)."\">&lt;</a>";//上一頁
    }
    //分頁數大於頁碼個數時可以偏移
    if($pages>$pagelen){
        //如果當前頁小於等於左偏移
        if($page<=$pageoffset){
            $init=1;
            $max = $pagelen;
        }else{//如果當前頁大於左偏移
            //如果當前頁碼右偏移超出最大分頁數
            if($page+$pageoffset>=$pages+1){
                $init = $pages-$pagelen+1;
            }else{
                //左右偏移都存在時的計算
                $init = $page-$pageoffset;
                $max = $page+$pageoffset;
            }
        }
    }
    //生成html
    for($i=$init;$i<=$max;$i++){
        if($i==$page){
            $pagecode.='<span>'.$i.'</span>';
        } else {
            $pagecode.="<a href=\"{$phpfile}?page={$i}\">$i</a>";
        }
    }
    if($page!=$pages){
        $pagecode.="<a href=\"{$phpfile}?page=".($page+1)."\">&gt;</a>";//下一頁
        $pagecode.="<a href=\"{$phpfile}?page={$pages}\">&gt;&gt;</a>";//最後一頁
    }
    $pagecode.='</div>';
    return array('pagecode'=>$pagecode,'sqllimit'=>' limit '.$offset.','.$pagesize);
}
?>
<style type="text/css">
        body{font-family:Tahoma;}
        .page{padding:2px;font-size:20px;}
        .page a{border:1px solid #ccc;padding:0 5px 0 5px;margin:2px;text-decoration:none;color:#333;}
        .page span{padding:0 5px 0 5px;margin:2px;background:#09f;color:#fff;border:1px solid #09c;}
</style>