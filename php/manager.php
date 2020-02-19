<?php
    echo '<h3>因某些原因，您表單在未全部填完之前按了送出鍵，共有'.$err.'個項目未填寫，事後如要補寫回去，請使用修改功能</h3><hr>';
    echo '本視窗將於3秒後關閉';
    sleep(3);
    echo "<script>window.close();</script>";
?>