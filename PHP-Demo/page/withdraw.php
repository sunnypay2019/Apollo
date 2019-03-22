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
        <p class="page__desc">提币申请演示</p>
    </div>
    <div class="page__bd">
        <div class="weui-cells__title">提币申请（申请后需要后台审核）</div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">转出币种</label></div>
                <div class="weui-cell__bd">
                    <select class="weui-input" id="symbol">
                        <option selected value="61027bec-d546-3d40-8d9c-4b1add177ce2">HKDT</option>
                        <option value="de7d29ba-432d-377d-95cd-e7ad81986bf2">BZKY</option>
                    </select>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">转出地址</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" id="address" placeholder="请输入钱包ERC20地址">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">转出数量</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" id="asset_num" placeholder="请输入要转出的数量">
                </div>
            </div>
            
        </div>
        
    </div>
    <div class="page__bd">
    <div class="weui-btn-area">
            <a class="weui-btn weui-btn_primary" href="javascript:" id="withdraw">马上申请</a>
        </div>
    </div>
    <div class="page__ft">
        <div class="weui-msg__extra-area">
            <?php include('foot.php');?>
        </div>
    </div>
</div>
</div>

</body>
<script type="text/javascript">
	
	$('#withdraw').click(function(){
		var data = {
			asset_id:$('#symbol').val(),
			address:$('#address').val(),
			amount:$('#asset_num').val()
		};
		show_loading();
        $.post('index.php?act=withdraw_api', data, 
			function(result){
				hide_loading();
				if (result.status==1){
					alert(result.message);
				}else{
					show_toptips(result.message);
				}
			},
		'json');
    });

</script>

</html>