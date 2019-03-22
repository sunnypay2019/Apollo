<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

include 'config.php';
include 'Apollo.php';

$act = empty($_GET['act'])?'index':trim($_GET['act']);
$demo= new Demo();

if (empty($act)){
	show_msg('warn','Error','param [act] is empty !');
}elseif(method_exists($demo,$act)){
	call_user_func(array($demo,$act));
}else{
	show_msg('warn','Error','param [act] not exist !');
}

/**
 * 输出Json数据
 * @param number $status
 * @param string $message
 * @param array $params
 */
function show_json($status = 0, $message = '', $params = array()) {
	$result = array (
			'status' => ( int ) $status,
			'message' => $message,
			'data' => empty ( $params ) ? array () : $params
	);
	ob_clean ();
	header('Access-Control-Allow-Origin:*');
	header ( 'Content-Type:application/json; charset=utf-8' );
	echo json_encode ( $result );
	exit ();
}

/**
 * 输出提示
 * @param warn/info/success $type
 * @param string $title
 * @param string $message
 */
 function show_msg($type = 'success', $title = '', $message = '') {
	include_once './page/show_msg.php';
	exit ();
}



Class Demo{
	
	//首页
	public function index(){
		include_once './page/index.php';
		exit();
	}
	//余额页面
	public function balance(){
		include_once './page/balance.php';
		exit();
	}
	
	//提币申请页面
	public function withdraw(){
		include_once './page/withdraw.php';
		exit();
	}
	
	//订单查询页
	public function query_order(){
		include_once './page/query_order.php';
		exit();
	}
	
	//查看回调记录
	public function show_callback(){
		$path = './tmp/callback.txt';
		$datalist = array();
		if (file_exists($path)){
			$content = file_get_contents($path);
			if (!empty($content)){
				$datalist = explode("\r\n", $content);
				foreach ($datalist as $k=>$v){
					$datalist[$k] = json_decode($v,true);
				}
			}
		}
		include_once './page/show_callback.php';
		exit();
	}
	
	//汇率及下单页
	public function exchange_and_order(){
		include_once './page/exchange_and_order.php';
		exit();
	}
	
//========================================
	
	//回调通知处理
	public function callback(){
		 $sign = empty($_POST['sign'])?'':trim($_POST['sign']);
		 $apl = new Apollo(CH_ID, CH_SECRET);
		 $sign2 = $apl->MakeSign($_POST);
		 if ($sign==$sign2){
		 	  //这里处理回调的事务
		 	$path = './tmp/callback.txt';
		 	if ($handle = fopen ( $path, 'ab' )) {
		 		fwrite ( $handle, json_encode($_POST)."\r\n");
		 		fclose ( $handle );
		 		echo 'SUCCESS';
		 		exit();
		 	}else{
		 		echo 'Write File Failed';
		 		exit();
		 	}
		 }else{
		 	 echo '签名验证失败:'.$sign2;
		 	 exit();	
		 }		 
	}
	
	
	public function exchange_api(){
		try {
			$currency = empty($_POST['currency'])?'':trim($_POST['currency']);
			$symbol   =empty($_POST['symbol'])?'':trim($_POST['symbol']);
			$amount   =empty($_POST['amount'])?0:floatval($_POST['amount']);
			
			if ($amount==0){
				show_json(-1,'请先输入法币金额');
			}
			
			$apl = new Apollo(CH_ID, CH_SECRET);
			$result = $apl->CurrencyToDigital($currency, $symbol, $amount);
			show_json(1,'转换完成',array('digital'=>$result));
		} catch (Exception $e) {
			show_json(-100,$e->getMessage());
		}
	}
	
	public function balance_api(){
		try {
			$asset_id  =empty($_POST['asset_id'])?'':trim($_POST['asset_id']);
			$apl = new Apollo(CH_ID, CH_SECRET);
			$result = $apl->balance(MCH_ID, $asset_id);
			show_json(1,'查询完成',array('balance'=>$result));
		} catch (Exception $e) {
			show_json(-100,$e->getMessage());
		}
	}
	
	
	public function withdraw_api(){
		try {
			$asset_id  =empty($_POST['asset_id'])?'':trim($_POST['asset_id']);
			$amount  =empty($_POST['amount'])?0:floatval($_POST['amount']);
			$address  =empty($_POST['address'])?'':trim($_POST['address']);
			if ($amount==0){
				show_json(-1,'请先输入要转出的数量');
			}
			$apl = new Apollo(CH_ID, CH_SECRET);
			$withdraw_id = $apl->withdraw(MCH_ID, $asset_id, $amount, $address);
			show_json(1,'申请成功,申请ID:'.$withdraw_id,array('withdraw_id'=>$withdraw_id));
		} catch (Exception $e) {
			show_json(-100,$e->getMessage());
		}
	}
	
	
	public function query_order_api(){
		try {
			$order_sn = empty($_POST['order_sn'])?'':trim($_POST['order_sn']);
			if (empty($order_sn)){
				show_json(-1,'请先输入订单号');
			}
			$apl = new Apollo(CH_ID, CH_SECRET);
			$result = $apl->query_order(MCH_ID, $order_sn);
			
			show_json(1,'查询完成',$result);
		} catch (Exception $e) {
			show_json(-100,$e->getMessage());
		}
	}
	
	
	
	public function new_order_api(){
		try {
			$mch_id = MCH_ID;
			$out_trade_no = "DEMO".time().rand(10,99);
			$asset_id  =empty($_POST['asset_id'])?'':trim($_POST['asset_id']);
			$amount   =empty($_POST['amount'])?0:floatval($_POST['amount']);
			$callback_url = 'http://'.$_SERVER ['HTTP_HOST'].$_SERVER ['PHP_SELF'].'?act=callback' ;
			$desc = '支付DEMO订单';
			$red_url= 'https://'.$_SERVER ['HTTP_HOST'].$_SERVER ['PHP_SELF'].'?act=show_callback' ;
			
			if ($amount==0){
				show_json(-1,'请先转换得出数字货币数量');
			}
			
			$apl = new Apollo(CH_ID, CH_SECRET);
			$result = $apl->new_order($mch_id, $out_trade_no, $asset_id, $amount, $callback_url , $desc, $red_url);
			show_json(1,'下单成功',$result);
		} catch (Exception $e) {
			show_json(-100,$e->getMessage());
		}
	}
	
}