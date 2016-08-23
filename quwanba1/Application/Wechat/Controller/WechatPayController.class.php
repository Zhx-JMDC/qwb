<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 16/7/10
 * Time: 下午3:15
 */
namespace Wechat\Controller;
use Think\Controller;
use Wechat\Model\CardHistoryModel;
use Wechat\Model\UserModel;
class WechatPayController extends CommonController {
    protected $card;
    protected $user;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->card = new CardHistoryModel();
        $this->user = new UserModel();
    }

    /**
     * 公众号购卡-微信支付发起接口
     * @param $id   购卡记录id
     * @param type  周游卡种id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function wechat_pay($id){
        //获取交易记录的内容
        $uid = get_uid();
        $history_data = $this->card->get_card_detail($id);
        $data['id']   = $history_data['id'];
        $data['type'] = $history_data['type'];
        $data['uid']  = $uid;
        $data   = json_encode($data);
        //获取支付用户openid
        $openid =$this->user->get_openid($uid);

        ini_set('date.timezone','Asia/Shanghai');
        Vendor('wxpay.lib.WxPay#Api');
        Vendor('wxpay.example.WxPay#JsApiPay');
        Vendor('wxpay.example.log');

        $tools = new \JsApiPay();
        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("趣玩吧周游卡");
        $input->SetAttach($data);
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url(C('DOMAIN_NAME')."index.php/Wechat/WechatPay/notify");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $order = \WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);

        $error['time']    = date('Y-m-d H:i:s',time());
        $error['params']  = $jsApiParameters;
        $error['openid']  = $openid;
        $error['uid']     = $uid;
        $error['orderid'] = $history_data['id'];
        M('error')->data($error)->add();

        $this->assign('type',$history_data['type']);
        $this->assign('jsApiParameters',$jsApiParameters);
        $this->display('Spokesman/buyCardPay');
    }

    /**
     * 公众号购卡-支付成功回调
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function notify(){
        $postStr = file_get_contents('php://input');
        $msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode($msg['attach'],true);

        //查看订单状态
        $history_ret = M('Card_history')->field('status')->where('id = '.$data['id'])->find();
        if(!$history_ret['status']){
            // 支付成功  写入数据库
            $ret = M('Wechat_pay')->data($msg)->add();
            if ($ret) {
                $this->card->startTrans();
                //修改订单状态
                $update_ret = $this->card->card_history_update($data['id'], $msg['out_trade_no']);

                //购卡佣金返还
                $card_data   = $this->card->get_card_detail($data['id']);    //够卡信息
                $user_data   = $this->user->get_user_message($data['uid']);  //购买者信息

                if($user_data['rank'] == '2' && $card_data['type'] == '1'){
                    //非旅行家用户,购买V级卡,上线50,上上线没有
                    if ($user_data['fid'] != 0){
                        //用户父级返佣
                        $fid_where['fid'] = $user_data['fid'];
                        $fid_income_count = M('User')->where($fid_where)->count();

                        //购买V 上级收入默认50
                        $fid_income_data = array(
                            'provid_uid'=> $data['uid'],
                            'uid'       => $user_data['fid'],
                            'income'    => 50,
                            'type'      => 0,
                            'detail_id' => $data['id'],
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
                        $fid_content['provide_id']= $data['uid'];
                        $fid_content['message']   = $user_data['nickname'];
                        M('Message')->data($fid_content)->add();

                        //上上级发送消息通知
                        $gid_content['uid']        = $user_data['gid'];
                        $gid_content['type']       = 1;
                        $gid_content['time']       = date('Y-m-d H:i:s', time());
                        $gid_content['provide_id'] = $data['uid'];
                        $gid_content['message']    = $user_data['nickname'];
                        M('Message')->data($gid_content)->add();

                    }
                }elseif($user_data['rank'] == '1' && $card_data['type'] == '0' && $user_data['gid'] != 0){
                    //V级用户,购买S级卡,上线没有,上上线100
                    $gid_income_data = array(
                        'provid_uid' => $data['uid'],
                        'uid'        => $user_data['gid'],
                        'require'    => 0,
                        'income'     => 100,
                        'type'       => 0,
                        'detail_id'  => $data['id'],
                        'time'       => date('Y-m-d H:i:s', time())
                    );
                    $gid_income_ret = $this->user->add_income($gid_income_data);

                    //上上级发送消息通知
                    $gid_content['uid']        = $user_data['gid'];
                    $gid_content['time']       = date('Y-m-d H:i:s', time());
                    $gid_content['value']      = 100;
                    $gid_content['type']       = 3;
                    $gid_content['provide_id'] = $data['uid'];
                    $gid_content['message']    = $user_data['nickname'];
                    M('Message')->data($gid_content)->add();

                    //上级发送消息通知
                    $fid_content['uid']        = $user_data['fid'];
                    $fid_content['time']       = date('Y-m-d H:i:s', time());
                    $fid_content['provide_id'] = $data['uid'];
                    $fid_content['type']       = 1;
                    $fid_content['message']    = $user_data['nickname'];
                    M('Message')->data($fid_content)->add();
                }

                //提升用户等级
                $change_ret = $this->user->change_rank($data['uid'], $data['type']);

                if ($update_ret !== false && $change_ret !== false && $fid_income_ret !== false && $gid_income_ret !== false) {
                    $this->card->commit();
                }else {
                    $this->card->rollback();
                    //需要添加日志记录 出现错误
                    echo "FAIL";
                }
            }
        }
    }

    /**
     * 支付显示页
     * @param type 需要购买的周游卡类型,0S级,1V级
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function buy_card_pay(){
        $params = I('post.');
        $uid    = get_uid();

        if(empty($params)){
            //购买S级卡,直接拉取用户信息
            $where['uid']     = $uid;
            $where['card_id'] = 2;
            $params = M('Card_history')->field('name,contact,postcode,address,message')->where($where)->find();
            $params['type'] = 0;
        }

        $data = array(
            'uid'      => $uid,
            'type'     => $params['type'],
            'name'     => $params['name'],
            'contact'  => $params['contact'],
            'postcode' => $params['postcode'],
            'address'  => $params['address'],
            'message'  => $params['message'],
            'time'     => date('Y-m-d H:i:s',time()),
            'order_num'=> get_order_num()
        );

        //添加购卡记录
        $ret = $this->card->card_history_add($data);
        if($ret){
            //$ret购卡订单记录的id
            $this->wechat_pay($ret);
        }else{
            $this->error("异常错误");
        }
    }

    /**********************************************************************************************************
     * 支付显示页
     * @param type 需要购买的周游卡类型,0S级,1V级
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_buy_card_pay(){
        $params = I('post.');
        $uid    = get_uid();

        if(empty($params)){
            //购买S级卡,直接拉取用户信息
            $where['uid']     = $uid;
            $where['card_id'] = 2;
            $params = M('Card_history')->field('name,contact,postcode,address,message')->where($where)->find();
            $params['type'] = 0;
        }

        if($params['type'] == 1){
            //获取V级海报
            $card = M('Goods_type')->field('icon')->where('id = 5')->find();
        }elseif($params['type'] == 0){
            //获取S级海报
            $card = M('Goods_type')->field('icon')->where('id = 4')->find();
        }
        $card['icon'] = C('DOMAIN_NAME').'Uploads'.$card['icon'];
        $this->assign('card',$card['icon']);

        $data = array(
            'uid'      => $uid,
            'type'     => $params['type'],
            'name'     => $params['name'],
            'contact'  => $params['contact'],
            'postcode' => $params['postcode'],
            'address'  => $params['address'],
            'message'  => $params['message'],
            'time'     => date('Y-m-d H:i:s',time()),
            'order_num'=> get_order_num()
        );

        //添加购卡记录
        $ret = $this->card->card_history_add($data);
        if($ret){
            //$ret购卡订单记录的id
            $this->assign('id',$ret);
            $this->assign('type',$params['type']);
            $this->display('Spokesman/app_buyCardPay');
        }else{
            $this->error("异常错误");
        }
    }

    /**
     * 购卡,统一下单接口,生成app参数
     * @param [int] [id] 商品订单号
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_wechat_pay(){
        $id = I('post.id');

        //获取订单详细信息
        $order_data = $this->card->get_card_detail($id);

        Vendor('wxpay.example.WxPay#AppPay');
        //填写配置参数
        $options = array(
            'spbill_create_ip' => C('SPBILL_IP'),
            'appid' 	       => 'wx6c33c97e5e4a9056',		                                 //公众开放账号ID
            'mch_id'	       => '1376680402',				                                 //商户号
            'notify_url'       => C('DOMAIN_NAME')."index.php/Wechat/WechatPay/app_card_notify", //回调地址
            'key'		       => 'e10adc3949ba59abbe56e057f20f883e',		                 //商户支付密钥Key
        );

        //统一下单方法
        $wechatAppPay           = new \wechatAppPay($options);
        $params['body']         = '去玩吧旅游周游卡';			     	//商品描述
        $params['out_trade_no'] = $order_data['order_num'];         //自定义的订单号
        $params['total_fee']    = '1';					            //订单金额 只能为整数 单位为分
        $params['trade_type']   = 'APP';					        //交易类型 JSAPI | NATIVE | APP | WAP
        $result = $wechatAppPay->unifiedOrder( $params );

        //创建APP端预支付参数
        /** @var TYPE_NAME $result */
        $data = @$wechatAppPay->getAppPayParams( $result['prepay_id'] );
        response(1, 'success' ,$data);
    }

    /**
     * app购卡微信支付回调
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_card_notify(){
        $postStr = file_get_contents('php://input');
        $msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        //查看订单状态
        $order_ret = $this->card->num_card_detail($msg['out_trade_no']);

        if(!$order_ret['pay_status']){
            $ret = M('Wechat_pay')->data($msg)->add();

            //修改订单状态为已支付
            if($ret){
                $this->card->startTrans();
                //修改订单状态
                $update_ret = $this->card->card_history_update($order_ret['id'], $msg['out_trade_no']);

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
                    echo "SUCCESS";
                }else {
                    $this->card->rollback();
                    //需要添加日志记录 出现错误
                    echo "FAIL";
                }
            }else{
                echo "FAIL";
            }
        }else{
            echo "FAIL";
        }
    }
}