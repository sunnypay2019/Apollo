<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>Apollo Demo</title>
    <link rel="stylesheet" href="page/css/weui.min.css"/>
    <link rel="stylesheet" href="page/css/example.css"/>
    <script type="text/javascript" src="page/js/jquery.min.js"></script>
</head>


<body ontouchstart>

<div class="container" id="container">
<div class="page input js_show">
    <div class="page__hd">
        <h1 class="page__title">Apollo Demo V0.1</h1>
        <p class="page__desc">订单查询演示</p>
    </div>
    <div class="page__bd">
        <div class="weui-cells__title">订单查询</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">订单号</label></div>
                <div class="weui-cell__bd">
                     <input class="weui-input" type="number" id="order_sn" placeholder="请输入订单号">
                </div>
            </div>
        </div>
    </div>
    <div class="page__bd">
    <div class="weui-btn-area">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="query_order">马上查询</a>
        </div>
        <div class="weui-cells__title">查询结果</div>
        <div class="weui-cells weui-cells_form">
        	<div class="weui-cell" id="QueryResult" style="font-size:10px;">
               
            </div>
        </div>
    </div>
    <div class="page__ft">
        <div class="weui-msg__extra-area">
            <?php include('foot.php');?>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
	
	$('#query_order').click(function(){
		var data = {
			order_sn:$('#order_sn').val(),
		};
		show_loading();
		$("#QueryResult").html('');
        $.post('index.php?act=query_order_api', data, 
			function(result){
				hide_loading();
				if (result.status==1){
					for(var p in result.data){
					  $("#QueryResult").append(p +":"+result.data[p]+'<br/>');
					}
				}else{
					show_toptips(result.message);
				}
			},
		'json');
    });

</script>
    

</body>
</html>