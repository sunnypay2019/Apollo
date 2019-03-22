<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>Apollo Demo</title>
    <link rel="stylesheet" href="page/css/weui.min.css"/>
    <link rel="stylesheet" href="page/css/example.css"/>
    <script type="text/javascript" src="page/js/jquery.min.js"></script>
	<script type="text/javascript" src="page/js/qrcode.min.js"></script>
	<style type="text/css">
    #PayQRCode img {
		  width:150px;
		  height:150px;
		} 
    </style>
</head>


<body ontouchstart>

<div class="container" id="container">
<div class="page input js_show">
    <div class="page__hd">
        <h1 class="page__title">Apollo Demo V0.1</h1>
        <p class="page__desc">汇率&下单</p>
    </div>
    <div class="page__bd">
        <div class="weui-cells__title">汇率转换</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">法币类型</label></div>
                <div class="weui-cell__bd">
                    <select class="weui-input" id="currency">
                        <option selected value="CNY">人民币</option>
                        <option value="HKD">港元</option>
                    </select>
                </div>
            </div>
            
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">法币金额</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" id="amount" placeholder="请输入法币金额">
                </div>
            </div>
            
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">数字货币</label></div>
                <div class="weui-cell__bd">
                    <select class="weui-input" id="symbol">
                        <option selected value="61027bec-d546-3d40-8d9c-4b1add177ce2">HKDT</option>
                        <option value="de7d29ba-432d-377d-95cd-e7ad81986bf2">BZKY</option>
                    </select>
                </div>
            </div>
            
            <div class="weui-cell weui-cell_vcode">
                <div class="weui-cell__hd">
                    <label class="weui-label">计算结果</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" disabled id="ASSET_NUM">
                </div>
                <div class="weui-cell__ft">
                    <button class="weui-vcode-btn" ID="EXCHANGE">转换汇率</button>
                </div>
            </div>
        </div>
    </div>
    <div class="page__bd">
    <div class="weui-btn-area">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="new_order">马上下单</a>
        </div>
        <div class="weui-cells__title">下单结果</div>
        <div class="weui-cells weui-cells_form">
        	<div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">订单号</label></div>
                <div class="weui-cell__bd" id="payOrderSN">
                    
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">支付二维码</label></div>
                <div class="weui-cell__bd" id="PayQRCode" style="height:150px;">
                    
                </div>
            </div>
            
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">支付链接</label></div>
                <div class="weui-cell__bd" id="payLink">
                    
                </div>
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
		$('#PayQRCode').html('');	
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