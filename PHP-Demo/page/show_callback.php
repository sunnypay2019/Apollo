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
        <p class="page__desc">回调记录</p>
    </div>
    <div class="page__bd">
        <div class="weui-cells__title">回调历史记录</div>
        <div class="weui-cells" style="font-size:12px;">
            <?php 
			if (empty($datalist)){
				echo '<div class="weui-cell">暂无数据</div>';
			}
			foreach ($datalist as $item){ ?>
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p><?php echo $item['order_sn'];?></p>
                </div>
                <div class="weui-cell__ft"><?php echo $item['amount'].$item['asset_code'];?></div>
            </div>
             <?php } ?>
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
	
     //转换汇率
	$('#EXCHANGE').click(function(){
		var data = {
			currency:$('#currency').val(),
			symbol:$('#symbol').find("option:selected").text(),
			amount:$('#amount').val()
		};
		show_loading();
        $.post('index.php?act=exchange_api', data, 
			function(result){
				hide_loading();
				if (result.status==1){
					$("#ASSET_NUM").val(result.data.digital);
				}else{
					show_toptips(result.message);
				}
			},
		'json');
    });
	
	//下单
	$('#new_order').click(function(){
		var data = {
			asset_id:$('#symbol').val(),
			amount:$('#ASSET_NUM').val()
		};
		show_loading();
        $.post('index.php?act=new_order_api', data, 
			function(result){
				hide_loading();
				if (result.status==1){
					$('#payLink').html('<a href="'+result.data.qrcode_content+'">马上支付</a>');
					$('#payOrderSN').html(result.data.order_sn);
					new QRCode(document.getElementById("PayQRCode"), result.data.qrcode_content);
				}else{
					show_toptips(result.message);
				}
			},
		'json');
	});
</script>
    

</body>
</html>