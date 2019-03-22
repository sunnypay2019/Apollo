<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="page/css/weui.min.css"/>
    <link rel="stylesheet" href="page/css/example.css"/>
</head>
<body ontouchstart>

<div class="container" id="container">
<div class="page js_show msg">
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="weui-icon-<?php echo $type;?> weui-icon_msg"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title"><?php echo $title;?></h2>
            <p class="weui-msg__desc"><?php echo $message;?></p>
        </div>
        <div class="weui-msg__opr-area">
            <p class="weui-btn-area">
                <a href="javascript:;" onClick="WeixinJSBridge.call('closeWindow');" class="weui-btn weui-btn_primary">确定</a>
                
            </p>
        </div>
        <div class="weui-msg__extra-area">
            <?php include('foot.php');?>
        </div>
    </div>
</div>

</div>


    

</body>
</html>