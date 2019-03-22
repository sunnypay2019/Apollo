<?php
class SunnyPay {
	
	private $URL = 'https://api.sunnypay.io/index.php';
	private $CH_ID = '';
	private $CH_SECRET = '';

	function __construct($ch_id,$ch_sercet){
		$this->CH_ID = $ch_id;
		$this->CH_SECRET = $ch_sercet;
	}
	
	/**
	 * 法币转数字货币
	 * @param 法币币种(CNY、HKD、USD) $currency
	 * @param 数字货币币种(HKDT、BZKY) $symbol
	 * @param 要转换的数量 $amount
	 * @throws Exception
	 * @return Decimal
	 */
	public function CurrencyToDigital($currency,$symbol,$amount){
		try {
			$params = array(
					'c'=>'common',
					'm'=>'exchange',
					'currency'=>$currency,
					'asset_code'=>$symbol,
					'type'=>'to_digital',
					'amount'=>$amount
			);
			$result = $this->api_request($params);
			return round($result['data']['digital_amount'],6);			
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * 数字货币转法币
	 * @param 法币币种(CNY、HKD、USD) $currency
	 * @param 数字货币币种(HKDT、BZKY) $symbol
	 * @param 要转换的数量 $amount
	 * @throws Exception
	 * @return Decimal
	 */
	public function DigitalToCurrency($currency,$symbol,$amount){
		try {
			$params = array(
					'c'=>'common',
					'm'=>'exchange',
					'currency'=>$currency,
					'asset_code'=>$symbol,
					'type'=>'to_cash',
					'amount'=>$amount
			);
			$result = $this->api_request($params);
			return round($result['data']['cash_amount'],6);
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * 订单查询接口
	 * @param 商户号 $mch_id
	 * @param 下单返回的订单号 $order_sn
	 * @throws Exception
	 * @return object
	 */
	public function query_order($mch_id,$order_sn){
		try {
			$params = array(
					'c'=>'order',
					'm'=>'query',
					'mch_id'=>$mch_id,
					'order_sn'=>$order_sn
			);
			$result = $this->api_request($params);
			return $result['data'];
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * 查询商户某种数字货币的余额
	 * @param 商户号 $mch_id
	 * @param 数字货币ID $asset_id
	 * @throws Exception
	 * @return Decimal
	 */
	public function balance($mch_id,$asset_id){
		try {
			$params = array(
					'c'=>'account',
					'm'=>'balance',
					'mch_id'=>$mch_id,
					'asset_id'=>$asset_id
			);
			$result = $this->api_request($params);
			return $result['data']['balance'];
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * 数字货币转出申请接口
	 * @param 商户号 $mch_id
	 * @param 数字货币ID $asset_id
	 * @param 数量 $amount
	 * @param 转出地址 $address
	 * @param 备注 $remark
	 * @throws Exception
	 * @return 申请记录ID号
	 */
	public function withdraw($mch_id,$asset_id,$amount,$address,$remark=''){
		try {
			$params = array(
					'c'=>'account',
					'm'=>'withdraw',
					'mch_id'=>$mch_id,
					'asset_id'=>$asset_id,
					'amount'=>$amount,
					'address'=>$address,
					'remark'=>$remark
			);
			$result = $this->api_request($params);
			return $result['data']['withdraw_id'];
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	
	/**
	 * 新订单下单接口
	 * @param 商户号 $mch_id
	 * @param 外部订单号 $out_trade_no
	 * @param 币种ID $asset_id
	 * @param 数字货币数量 $amount
	 * @param 回调通知地址 $callback_url
	 * @param 交易描述 $desc
	 * @param 支付后回跳URL $redirect_url
	 * @throws Exception
	 * @return object
	 */
	public function new_order($mch_id,$out_trade_no,$asset_id,$amount,$callback_url,$desc='',$redirect_url=''){
		try {
			$params = array(
					'c'=>'order',
					'm'=>'new_order',
					'mch_id'=>$mch_id,
					'out_trade_no'=>$out_trade_no,
					'desc'=>$desc,
					'asset_id'=>$asset_id,
					'amount'=>$amount,
					'callback_url'=>$callback_url,
					'redirect_url'=>$redirect_url
			);
			$result = $this->api_request($params);
			return $result['data'];
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	
	/**
	 * 接口请求方法
	 * @param array $data
	 * @throws Exception
	 * @return object
	 */
	private function api_request($data =array()) {
		try {
			$data['ch_id'] = $this->CH_ID;
			$data['timestamp'] = time();
			$data['sign'] = $this->MakeSign($data);
			$result = $this->Post($this->URL,$data);
			$json = json_decode($result,true);
			if ($json['code']==0){
				return $json;
			}else{
				throw new Exception("SunnyPay api return an exception, error code : ".$json['code'] ,$json['code']);
			}
		} catch (Exception $e) {
			throw $e;
		}
	}
	

	/**
	 * 封装的POST方法
	 * @param String $url
	 * @param array $data
	 * @throws Exception
	 * @return String
	 */
	private function Post($url, $data =array()) {
		try {
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
			curl_setopt ( $ch, CURLOPT_USERAGENT, "SunnyPay PHP SDK 1.0");
			curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
			curl_setopt ( $ch, CURLOPT_POST, 1);
			curl_setopt ( $ch, CURLOPT_TIMEOUT, 20);
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($data) );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
			$temp = curl_exec ( $ch );
			$Status = curl_getinfo($ch);
			curl_close ( $ch );
			if(intval($Status["http_code"])==200){
				return $temp;
			}else{
				throw new Exception($temp,$Status["http_code"]);
			}
		} catch (Exception $e) {
			throw $e;
		}
	}
	
	
	/**
	 * 生成签名
	 * @param array $Params
	 * @return string
	 */
	public function MakeSign($Params)
	{
		$buff = "";
		ksort ( $Params );
		foreach ($Params as $k => $v)
		{
			if($k != "sign" && $v !== ''){
				$buff .= $k . "=" . $v . "&";
			}
		}
		$buff = trim($buff, "&");
		$buff = $buff . "&key=" . $this->CH_SECRET;
		return strtoupper ( md5 ( $buff ) );
	}
	

}





