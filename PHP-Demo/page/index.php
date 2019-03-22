<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>Apollo Demo</title>
    <link rel="stylesheet" href="page/css/weui.min.css"/>
    <link rel="stylesheet" href="page/css/example.css"/>
</head>
<body ontouchstart>

<div class="container" id="container">
<div class="page js_show msg">
<div class="page__hd">
        <h1 class="page__title">Apollo Demo</h1>
        <p class="page__desc">Alpha v0.1</p>
    </div>

    <div class="page__bd page__bd_spacing">
        
        <a href="index.php?act=exchange_and_order" class="weui-btn weui-btn_default">汇率&下单</a>
        <a href="index.php?act=show_callback" class="weui-btn weui-btn_default">回调记录</a>
		<a href="index.php?act=query_order" class="weui-btn weui-btn_default">订单查询</a>
		<a href="index.php?act=balance" class="weui-btn weui-btn_default">余额查询</a>
        <a href="index.php?act=withdraw" class="weui-btn weui-btn_default">提币申请</a>
        
    </div>
    <div class="weui-msg__extra-area">
            <?php include('foot.php');?>
    </div>
</div>

</div>


    

</body>
</html>