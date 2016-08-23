<?php
namespace Wechat\Controller;
use Think\Controller;
use Wechat\Model\AlipayModel;
use Wechat\Model\OrderModel;
use Wechat\Model\GoodsModel;
use Wechat\Model\CardHistoryModel;

class AlipayController extends CommonController {
	protected $pay;
	protected $order;
	protected $goods;
	protected $card;

	/**
	 * 构造方法，实例化操作模型
	 */
	public function __construct() {
		parent::__construct();
		$this->pay   = new AlipayModel();
		$this->order = new OrderModel();
		$this->goods = new GoodsModel();
		$this->card = new CardHistoryModel();
	}

	/**---------------------------------------------------------------------------
	 * 支付宝支付-获取支付宝签名后的字符串
	 * @param [int] 	[id] 	  	  [订单号id]
	 * @param [string]  [cpPayName]   [商品名称]
	 * @param [decimal] [cpPrice] 	  [总金额]
	 * @param [string]  [ordernu] 	  [商户网站唯一订单号]
	 * @param [string]  [cpPayDetail] [商品详情]
	 * @param [string]  [ticket_id]   [用户优惠券id,未使用为0]
	 */
	public function alipay_pay(){
		$id = I('post.id');

		//获取订单详细信息
		$order_data = $this->order->get_order_detail($id);

		$url          = C('DOMAIN_NAME')."index.php/Wechat/Alipay/pay_back";
		$cpPayName    = $order_data['name'];
		$cpOrderNum   = $order_data['order_num'];
//		$cpPrice      = (int)$order_data['price'];
		$cpPrice = '0.01';

		$ali = array(
			'service' 	     => 'mobile.securitypay.pay',
			'partner'     	 => C('ALI_PARTNER'),
			'_input_charset' => 'utf-8',
			'sign_type'      => 'RSA',
			'sign'           => "",
			'notify_url'     => urlencode($url),      //回调地址
			'out_trade_no'   => $cpOrderNum,          //商户网站唯一订单号
			'subject'        => '趣玩吧旅游',			  //商品名称
			'payment_type'   => 1,					  //支付类型
			'seller_id'      => C('ALI_SELLER_ID'),   //支付宝账号
			'total_fee'      => $cpPrice,	          //总金额
			'body'           => $cpPayName 		      //商品详情
		);

		//参数排序
		$ali = argSorts($ali);
		$str = '';

		foreach($ali as $key=>$val) {
			if ($key == 'sign_type' || $key == 'sign') {
				continue;
			} else {
				if ($str == '') {
					$str = $key . '=' . '"' . $val . '"';
				} else {
					$str = $str . '&' . $key . '=' . '"' . $val . '"';
				}
			}
		}

		//私钥获取支付宝签名
		$sign = urlencode($this->pay->sign($str));

        //公钥验证支付宝签名
		$ret = $this->pay->rsaVerify($str, urldecode($sign));

		if($ret){
			//拼接签名后的数组参数
			$str  = $str.'&sign='.'"'.$sign.'"'.'&sign_type='.'"'.$ali['sign_type'].'"';
			//传给支付宝接口的数据
			response(1,'支付宝秘钥',$str);

		}else{
			response(-70,'支付宝签名不合法',$str);
		}
	}

	/**---------------------------------------------------------------------------
	 * 支付宝支付回调支付信息信息
	 * @param [notify_time] 		[通知时间]
	 * @param [notify_type] 		[通知类型]
	 * @param [notify_id] 			[通知校验ID]
	 * @param [sign_type] 			[签名方式]
	 * @param [sign] 				[签名]
	 * @param [out_trade_no] 		[商户网站统一订单号,流水号]
	 * @param [subject] 			[商品名称]
	 * @param [payment_type] 		[支付类型]
	 * @param [trade_no] 			[支付宝交易号]
	 * @param [trade_status] 		[交易状态]
	 * @param [seller_id] 			[卖家支付宝用户号]
	 * @param [seller_email] 		[卖家支付宝账号]
	 * @param [buyer_id] 			[买家支付宝用户号]
	 * @param [buyer_email] 		[买家支付宝账号]
	 * @param [total_fee] 			[交易金额]
	 * @param [quantity]
	 * @param [price] 				[商品单价]
	 * @param [body] 				[商品描述,存储优惠券ticket_id]
	 * @param [gmt_create] 			[购买数量]
	 * @param [gmt_payment] 		[交易创建时间]
	 * @param [is_total_fee_adjust] [是否调整总价]
	 * @param [use_coupon] 			[是否使用红包买家]
	 * @param [discount] 			[折扣]
	 * @param [refund_status] 		[退款状态]
	 * @param [gmt_refund] 			[退款时间]
	 * @param [bill_way]            [1支付宝支付]
	 * @return success 支付成功 error 支付失败
	 */
	public function pay_back(){
		$params       = I('post.');
		$out_trade_no = $params['out_trade_no'];

		if(!empty($params['gmt_payment'])){
			//查看订单是否已经完成,修改订单状态
			$order_ret = $this->order->num_order_detail($out_trade_no);

			if(!$order_ret['pay_status']){
				$ret = M('Alipay')->data($params)->add();

				//修改订单状态为已支付
				if($ret){
					$update_ret = $this->order->order_pay_update($order_ret['id'], $params['trade_no'], 3);

					if($update_ret){
						//获取商家用户openid
						$openid = $this->goods->get_shop_openid($order_ret['uid']);

						//预定成功,商家获得订单文本消息
						$message_data = array(
							'order_num'       => $order_ret['order_num'],
							'name'            => $order_ret['name'],
							'experience_time' => $order_ret['experience_time'],
							'price'           => $order_ret['price']
						);
						$this->order_template_message((string)$openid,$message_data);

						echo "success";
					}else{
						echo "error";
					}
				}else{
					echo "error";
				}
			}else{
				echo "FAIL";
			}
		}
	}

	/**---------------------------------------------------------------------------
	 * 支付宝支付-获取支付宝签名后的字符串
	 * @param [int] 	[id] 	  	  [订单号id]
	 * @param [string]  [cpPayName]   [商品名称]
	 * @param [decimal] [cpPrice] 	  [总金额]
	 * @param [string]  [ordernu] 	  [商户网站唯一订单号]
	 * @param [string]  [cpPayDetail] [商品详情]
	 * @param [string]  [ticket_id]   [用户优惠券id,未使用为0]
	 */
	public function card_alipay_pay(){
		$id = I('post.id');

		//获取订单详细信息
		$order_data = $this->card->get_card_detail($id);

		$url          = C('DOMAIN_NAME')."index.php/Wechat/Alipay/card_pay_back";
		$cpPayName    = '周游卡';
		$cpOrderNum   = $order_data['order_num'];
//		$cpPrice      = (int)$order_data['price'];
		$cpPrice = '0.01';

		$ali = array(
			'service' 	     => 'mobile.securitypay.pay',
			'partner'     	 => C('ALI_PARTNER'),
			'_input_charset' => 'utf-8',
			'sign_type'      => 'RSA',
			'sign'           => "",
			'notify_url'     => urlencode($url),      //回调地址
			'out_trade_no'   => $cpOrderNum,          //商户网站唯一订单号
			'subject'        => '趣玩吧旅游周游卡',	      //商品名称
			'payment_type'   => 1,					  //支付类型
			'seller_id'      => C('ALI_SELLER_ID'),   //支付宝账号
			'total_fee'      => $cpPrice,	          //总金额
			'body'           => $cpPayName 		      //商品详情
		);

		//参数排序
		$ali = argSorts($ali);
		$str = '';

		foreach($ali as $key=>$val){
			if($key == 'sign_type' || $key == 'sign'){
				continue;
			}else{
				if($str == ''){
					$str = $key.'='.'"'.$val.'"';
				}else{
					$str = $str.'&'.$key.'='.'"'.$val.'"';
				}
			}
		}

		//私钥获取支付宝签名
		$sign = urlencode($this->pay->sign($str));

		//公钥验证支付宝签名
		$ret = $this->pay->rsaVerify($str, urldecode($sign));

		if($ret){
			//拼接签名后的数组参数
			$str  = $str.'&sign='.'"'.$sign.'"'.'&sign_type='.'"'.$ali['sign_type'].'"';
			//传给支付宝接口的数据
			response(1,'支付宝秘钥',$str);

		}else{
			response(-70,'支付宝签名不合法',$str);
		}
	}

	/**
	 * 支付宝购卡回调
	 * @author zhangxiang <zhxjmdc@gmail.com>
	 */
	public function card_pay_back(){
		$params       = I('post.');
		$out_trade_no = $params['out_trade_no'];

		if(!empty($params['gmt_payment'])){
			//查看订单是否已经完成,修改订单状态
			$order_ret = $this->card->num_card_detail($out_trade_no);

			if(!$order_ret['pay_status']){
				$ret = M('Alipay')->data($params)->add();

				//修改订单状态为已支付
				if($ret){
					$this->card->startTrans();
					//修改订单状态
					$update_ret = $this->card->card_history_update($order_ret['id'], $params['trade_no']);

					//购卡佣金返还
					$card_data   = $this->card->get_card_detail($order_ret['id']);    //够卡信息
					$user_data   = $this->user->get_user_message($order_ret['uid']);  //购买者信息

					if($user_data['rank'] == '2' && $card_data['type'] == '1'){
						//非旅行家用户,购买V级卡,上线50,上上线没有
						if ($user_data['fid'] != 0){
							//用户父级返佣
							$fid_where['fid'] = $user_data['fid'];
							$fid_income_count = M('User')->where($fid_where)->count();

							//购买V 上级收入默认50
							$fid_income_data = array(
								'provid_uid'=> $order_ret['uid'],
								'uid'       => $user_data['fid'],
								'income'    => 50,
								'type'      => 0,
								'detail_id' => $order_ret['id'],
								'time'      => date('Y-m-d H:i:s', time()),
							);

							//超过5个下线(1级),当前用户需要升到S级
							if ($fid_income_count > 5) {
								$fid_income_data['require'] = '0';
							} else {
								$fid_income_data['require'] = '1';
							}
							$fid_income_ret = $this->user->add_income($fid_income_data);

							//上级发送消息通知
							$fid_content['uid']       = $user_data['fid'];
							$fid_content['type']      = 3;
							$fid_content['time']      = date('Y-m-d H:i:s', time());
							$fid_content['value']     = 50;
							$fid_content['provide_id']= $order_ret['uid'];
							$fid_content['message']   = $user_data['nickname'];
							M('Message')->data($fid_content)->add();

							//上上级发送消息通知
							$gid_content['uid']        = $user_data['gid'];
							$gid_content['type']       = 1;
							$gid_content['time']       = date('Y-m-d H:i:s', time());
							$gid_content['provide_id'] = $order_ret['uid'];
							$gid_content['message']    = $user_data['nickname'];
							M('Message')->data($gid_content)->add();

						}
					}elseif($user_data['rank'] == '1' && $card_data['type'] == '0' && $user_data['gid'] != 0){
						//V级用户,购买S级卡,上线没有,上上线100
						$gid_income_data = array(
							'provid_uid' => $order_ret['uid'],
							'uid'        => $user_data['gid'],
							'require'    => 0,
							'income'     => 100,
							'type'       => 0,
							'detail_id'  => $order_ret['id'],
							'time'       => date('Y-m-d H:i:s', time())
						);
						$gid_income_ret = $this->user->add_income($gid_income_data);

						//上上级发送消息通知
						$gid_content['uid']        = $user_data['gid'];
						$gid_content['time']       = date('Y-m-d H:i:s', time());
						$gid_content['value']      = 100;
						$gid_content['type']       = 3;
						$gid_content['provide_id'] = $order_ret['uid'];
						$gid_content['message']    = $user_data['nickname'];
						M('Message')->data($gid_content)->add();

						//上级发送消息通知
						$fid_content['uid']        = $user_data['fid'];
						$fid_content['time']       = date('Y-m-d H:i:s', time());
						$fid_content['provide_id'] = $order_ret['uid'];
						$fid_content['type']       = 1;
						$fid_content['message']    = $user_data['nickname'];
						M('Message')->data($fid_content)->add();
					}

					//提升用户等级
					$change_ret = $this->user->change_rank($order_ret['uid'], $order_ret['type']);

					if ($update_ret !== false && $change_ret !== false && $fid_income_ret !== false && $gid_income_ret !== false) {
						$this->card->commit();
						echo "success";
					}else {
						$this->card->rollback();
						//需要添加日志记录 出现错误
						echo "error";
					}
				}else{
					echo "error";
				}
			}else{
				echo "FAIL";
			}
		}
	}
}