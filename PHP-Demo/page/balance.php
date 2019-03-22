<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>SunnyPay Demo</title>
    <link rel="stylesheet" href="page/css/weui.min.css"/>
    <link rel="stylesheet" href="page/css/example.css"/>
    <script type="text/javascript" src="page/js/jquery.min.js"></script>
</head>


<body ontouchstart>

<div class="container" id="container">
<div class="page input js_show">
    <div class="page__hd">
        <h1 class="page__title">SunnyPay Demo V0.1</h1>
        <p class="page__desc">订单查询演示</p>
    </div>
    <div class="page__bd">
        <div class="weui-cells__title">订单查询</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">查询币种</label></div>
                <div class="weui-cell__bd">
                    <select class="weui-input" id="symbol">
                        <option selected value="61027bec-d546-3d40-8d9c-4b1add177ce2">HKDT</option>
                        <option value="de7d29ba-432d-377d-95cd-e7ad81986bf2">BZKY</option>
                    </select>
                </div>
            </div>
        </div>
        
    </div>
    <div class="page__bd">
    <div class="weui-btn-area">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="query_balance">马上查询</a>
        </div>
        <div class="weui-cells__title">查询结果</div>
        <div class="weui-cells weui-cells_form">
        	<div class="weui-cell" id="QueryResult">
               
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
	
	$('#query_balance').click(function(){
		var data = {
			asset_id:$('#symbol').val(),
		};
		show_loading();
        $.post('index.php?act=balance_api', data, 
			function(result){
				hide_loading();
				if (result.status==1){
					$("#QueryResult").html('余额：' + result.data.balance );
				}else{
					show_toptips(result.message);
				}
			},
		'json');
    });

</script>
    

</body>
</html>