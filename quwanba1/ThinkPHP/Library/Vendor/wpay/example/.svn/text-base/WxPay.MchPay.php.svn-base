<?php
class MchPay
{
	/**
	 * API 参数
	 * @var array
	 * 'mch_appid'         # 公众号APPID
	 * 'mchid'             # 商户号
	 * 'device_info'       # 设备号
	 * 'nonce_str'         # 随机字符串
	 * 'partner_trade_no'  # 商户订单号
	 * 'openid'            # 收款用户openid
	 * 'check_name'        # 校验用户姓名选项 针对实名认证的用户
	 * 're_user_name'      # 收款用户姓名
	 * 'amount'            # 付款金额
	 * 'desc'              # 企业付款描述信息
	 * 'spbill_create_ip'  # Ip地址
	 * 'sign'              # 签名
	 */
	public $parameters = [];

	public function __construct()
	{

		Vendor('wxpay.lib.WxPay#Config');
		$this->url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
		//$this->curl_timeout = WxPayConfig::CURL_TIMEOUT;
	}

	/**
	 * 生成请求xml数据
	 * @return string
	 */
	public function createXml()
	{
		$this->parameters['mch_appid'] = \WxPayConfig::APPID;
		$this->parameters['mchid']     = \WxPayConfig::MCHID;
		$this->parameters['nonce_str'] = $this->createNoncestr();
		$this->parameters['sign']      = $this->getSign($this->parameters);
		return $this->arrayToXml($this->parameters);
	}

	/**
	 * 生成随机字符串
	 * @param int $length
	 * @return string
	 */
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	/**
	 * 生成签名
	 * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
	 */
	public function getSign($data)
	{
		//签名步骤一：按字典序排序参数
		ksort($data);
		$string = $this->ToUrlParams($data);
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".\WxPayConfig::KEY;
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}
	/**
	 * 格式化参数格式化成url参数
	 */
	public function ToUrlParams($data)
	{
		$buff = "";
		foreach ($data as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}

		$buff = trim($buff, "&");
		return $buff;
	}
	/**
	 *     作用：使用证书，以post方式提交xml到对应的接口url
	 */
	function postXmlSSLCurl($xml,$url,$second=30)
	{
		$ch = curl_init();
		//超时时间
		//curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch,CURLOPT_HEADER,FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
		//设置证书
		curl_setopt($ch,CURLOPT_CAINFO, dirname(__FILE__).'/../cert/rootca.pem');
		//使用证书：cert 与 key 分别属于两个.pem文件
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLCERT, dirname(__FILE__).'/../cert/apiclient_cert.pem');
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLKEY, dirname(__FILE__).'/../cert/apiclient_key.pem');

		//post提交方式
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		}
		else {
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."
";
			echo "错误原因查询
";
			curl_close($ch);
			return false;
		}
	}
	/**
	 *  array转xml
	 */
	function arrayToXml($arr)
	{
		$xml = "<xml>";
		foreach ($arr as $key=>$val)
		{
			if (is_numeric($val))
			{
				$xml.="<".$key.">".$val."</".$key.">";

			}
			else
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
		}
		$xml.="</xml>";
		return $xml;
	}


}