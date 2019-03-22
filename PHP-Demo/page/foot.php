<!--BEGIN toast-->
    <div id="toast" style="display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-icon-success-no-circle weui-icon_toast"></i>
            <p class="weui-toast__content">操作完成</p>
        </div>
    </div>
    <!--end toast-->

    <!-- loading toast -->
    <div id="loadingToast" style="display:none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading weui-icon_toast"></i>
            <p class="weui-toast__content">数据加载中</p>
        </div>
    </div>
    
	<script type="text/javascript">
    	function show_loading(){
			$('#loadingToast').fadeIn(100);
		}
		
		function hide_loading(){
			$('#loadingToast').hide();
		}
		
		function show_toast(msg,url){
			$('#toast').fadeIn(100);
            setTimeout(function () {
				if (url==''){
					$('#toast').fadeOut(100);
				}else{
					location.href = url;
				}     
            }, 2000);
		}
		
		function show_toptips(msg){
			$('.weui-toptips').html(msg);
			$('.weui-toptips').show();
			setTimeout(function () {
				hide_toptips();
            }, 3000);
		}
		
		function hide_toptips(){
			$('.weui-toptips').html('');
			$('.weui-toptips').hide();
		}
		
    </script>
	
<div class="weui-toptips weui-toptips_warn js_tooltips">错误提示</div>

<div class="weui-footer">
	
	<p class="weui-footer__links">
		<!-- <a href="javascript:void(0);" class="weui-footer__link">常见问题</a> -->
	</p>
	<p class="weui-footer__text"> © Copyright 2019 Sunnypay </p>
</div>
