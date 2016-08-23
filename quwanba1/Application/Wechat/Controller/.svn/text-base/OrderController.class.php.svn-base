<?php
namespace Wechat\Controller;
use Think\Controller;
use Wechat\Model\OrderModel;
use Wechat\Model\GoodsModel;
class OrderController extends CommonController {
    protected $goods;
    protected $order;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->goods = new GoodsModel();
        $this->order = new OrderModel();
    }

    /**
     * 所有订单显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function my_order(){
        $uid = get_uid();
        $data = $this->order->get_order($uid);

        if($data === false){
            $this->error("异常错误");
        }else{
            //海报和订单二维码地址
            foreach ($data as $key => $value){
                foreach ($value as $k => $v){
                    if ($k == 'status' && $value[$k] == '-1'){
                        $value = null;
                        break;
                    }
                    if($k == 'pic'){
                        $value[$k] = C(DOMAIN_NAME).'Uploads'.$v;
                    }elseif($k == 'qr_code'){
                        $value[$k] = C(DOMAIN_NAME).$v;
                    }

                }
                if ($value != null){
                    $data[$key] = $value;
                }else{
                    unset($data[$key]);
                }

            }

            $this->assign('footer_order','footer_bottom-Clicked');
            $this->assign('order', $data);
        }
        $this->display('Order/myOrder');
    }

    /**
     * 订单详情显示页
     * @param id 订单id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function order_intro(){
        $order_id = I('get.id');
        $data     = $this->order->get_order_detail($order_id);
        //二维码和商品海报地址
        $data['pic'] = C(DOMAIN_NAME).'Uploads'.$data['pic'];
        $data['qr_code'] = C(DOMAIN_NAME).$data['qr_code'];
        
        if($data){
            $this->assign('footer_order','footer_bottom-Clicked');
            $this->assign('id',$data['id']);
            $this->assign('name',$data['name']);
            $this->assign('order_num',$data['order_num']);
            $this->assign('count',$data['count']);
            $this->assign('price',$data['price']);
            $this->assign('buyer',$data['buyer']);
            $this->assign('mobile',$data['mobile']);
            $this->assign('buy_time',$data['buy_time']);
            $this->assign('experience_time',$data['experience_time']);
            $this->assign('message',$data['message']);
            $this->assign('qr_code',$data['qr_code']);
            $this->assign('pic',$data['pic']);
            $this->assign('status',$data['status']);
            $this->display('Order/orderIntro');
        }else{
            $this->error('异常错误');
        }
    }

    /**
     * 订单退订操作
     * @param id   订单id
     * @param type 1公众号,0App
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function unsubscribe(){
        $id   = I('post.id');
        $type = I('post.type');

        $ret = $this->order->unsubscribe($id);
        if($ret){
            if($type == 0){
                $url = U('Order/app_order_intro',array('id'=>$id));
            }elseif($type == 1){
                $url = U('Order/order_intro',array('id'=>$id));
            }
            $this->ajaxReturn($url);
        }else{
            $this->error('异常错误');
        }
    }

    /**
     * 订单删除操作
     * @param id 订单id
     * @param type 1公众号,0App
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function delete_order(){
        $id   = I('post.id');
        $type = I('post.type');

        $ret = $this->order->delete_order($id);
        if($ret){
            if($type == 0){
                $url = U('Order/app_my_order',array('id'=>$id));
            }elseif($type == 1){
                $url = U('Order/my_order',array('id'=>$id));
            }
            $this->ajaxReturn($url);
        }else{
            $this->ajaxReturn('0');
        }
    }

    /**
     * 订单添加操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function order_add(){
        $params = I('post.');
        $uid    = get_uid();

        $experience_time = date('Y-m-d',strtotime(str_replace("月","/",$params['year']).$params['day']));

        //生成唯一订单号
        $order_num = get_order_num();

        //生成的订单二维码保存到本地
        Vendor('phpqrcode.phpqrcode');
        $code = array(
            'id'  => $order_num,
            'uid' => $uid
        );
        $state = json_encode($code);
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxebafdf0a98c558b5&redirect_uri=http://www.qwb.2015tt.net/index.php/Wechat/Order/page_accreditss&response_type=code&scope=snsapi_base&state='.$state.'#wechat_redirect';
        $path = 'Uploads/OrderQRcode/'.date('Y-m-d');
        if(!file_exists($path)) {
            mkdir($path);
        }
        $fileName = $path.'/'.md5($order_num).'.png';
        \QRcode::png($url, $fileName, QR_ECLEVEL_L, 10, 10, true);

        //获取物品详情信息
        $goods_data = $this->goods->get_goods_detail($params['goods_id']);

        $data = array(
            'uid'      => $uid,
            'buyer'    => $params['name'],
            'name'     => $goods_data['name'],
            'price'    => $params['choose_value'],
            'status'   => '0',
            'buy_time' => date('Y-m-d H:i:s',time()),
            'order_num'=> $order_num,
            'qr_code'  => $fileName,
            'goods_id' => $params['goods_id'],
            'count'    => $params['count'],
            'mobile'   => $params['mobile'],
            'pic'      => $goods_data['pic'],
            'message'  => $params['message'],
            'experience_time' => $experience_time,
            'shop_id'  => $goods_data['shop_id']
        );

        $ret = $this->order->order_add($data);
        if($ret){
            //发起支付页面
            $this->shop_wechat_pay($ret);
        }else{
            $this->error("异常错误");
        }
    }

    /**
     * 商品-微信支付发起接口
     * @param $id   购买商品订单id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_wechat_pay($id){
        //获取交易记录的内容
        $uid = get_uid();
        $order_data = $this->order->get_order_detail($id);
        $order_data['pic'] = C('DOMAIN_NAME').'Uploads'.$order_data['pic'];

        $data['id']   = $order_data['id'];
        $data['uid']  = $uid;
        $data   = json_encode($data);

//        //获取支付用户openid
//        $openid =$this->user->get_openid($uid);
//
//        ini_set('date.timezone','Asia/Shanghai');
//        Vendor('wxpay.lib.WxPay#Api');
//        Vendor('wxpay.example.WxPay#JsApiPay');
//        Vendor('wxpay.example.log');
//
//        $tools = new \JsApiPay();
//        //②、统一下单
//        $input = new \WxPayUnifiedOrder();
//        $input->SetBody("趣玩吧周游卡");
//        $input->SetAttach($data);
//        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
//        $input->SetTotal_fee("1");
//        $input->SetTime_start(date("YmdHis"));
//        $input->SetTime_expire(date("YmdHis", time() + 600));
//        $input->SetGoods_tag("test");
//        $input->SetNotify_url(C('DOMAIN_NAME')."index.php/Wechat/Order/shop_notify");
//        $input->SetTrade_type("JSAPI");
//        $input->SetOpenid($openid);
//        $order = \WxPayApi::unifiedOrder($input);
//        $jsApiParameters = $tools->GetJsApiParameters($order);
//
//        $a['error'] = $jsApiParameters;
//        M('error')->add($a);
//
//        $this->assign('order', $order_data);
//        $this->assign('jsApiParameters',$jsApiParameters);
//        $this->display('Order/orderIntroToBuy');

        Vendor('wpay.lib.WxPay#Api');
        Vendor('wpay.example.WxPay#JsApiPay');
        Vendor('wpay.example.log');

        //①、获取用户openid
        $tools = new \JsApiPay();
        //获取支付用户openid
        $openid =$this->user->get_openid($uid);

        $num = \WxPayConfig::MCHID.date("YmdHis");
        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("趣玩吧周游卡");
        $input->SetAttach($data);
        $input->SetOut_trade_no($num);
        $input->SetTotal_fee(1);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url(C('DOMAIN_NAME')."index.php/Wechat/Order/shop_notify");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openid);
        $input->SetSpbill_create_ip(C('SPBILL_IP'));
        $UnifiedOrderResult = \WxPayApi::unifiedOrder($input);

        if(array_key_exists("prepay_id", $UnifiedOrderResult)){
            $jsApiParameters = $tools->GetJsApiParameters($UnifiedOrderResult);
            $this->assign('order', $order_data);
            $this->assign('jsApiParameters',$jsApiParameters);
            $this->display('Order/orderIntroToBuy');
        }else {
            $inputObj = new \WxPayOrderQuery();
            $inputObj->SetOut_trade_no($num);
            $UnifiedOrderResult = \WxPayApi::orderQuery($inputObj);
            $order['pay_state'] = '支付失败';
            D('LogFile','Api')->write("orderQuery".json_encode($UnifiedOrderResult));
            if($UnifiedOrderResult['return_code']=='SUCCESS'&&$UnifiedOrderResult['result_code']=='SUCCESS'){
                if($UnifiedOrderResult['trade_state']=='SUCCESS'){
                    $order['pay_state'] ='支付成功';
                }
            }
            $this->assign('order',$order);
            $this->display('success');
        }
    }

    /**
     * 公众号商品微信支付成功回调
     * */
    public function shop_notify(){
        $postStr = file_get_contents('php://input');
        $msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode($msg['attach'],true);
        //查看订单状态
        $order_ret = $this->order->get_order_detail($data['id']);

        if(!$order_ret['pay_status']){
            $ret = M('Wechat_pay')->data($msg)->add();

            //修改订单状态为已支付
            if($ret){
                $update_ret = $this->order->order_pay_update($data['id'], $msg['out_trade_no'], 1);

                if($update_ret){
                    //获取商家用户openid
                    $openid = $this->goods->get_shop_openid($data['uid']);

                    //预定成功,商家获得订单文本消息
                    $message_data = array(
                        'order_num'       => $order_ret['order_num'],
                        'name'            => $order_ret['name'],
                        'experience_time' => $order_ret['experience_time'],
                        'price'           => $order_ret['price']
                    );
                    $this->order_template_message((string)$openid,$message_data);

                    echo "SUCCESS";
                }else{
                    echo "FAIL";
                }
            }else{
                echo "FAIL";
            }
        }else{
            echo "FAIL";
        }
    }

    /**
     * 扫描订单二维码,网页授权获取openid(静默授权),发送文本消息,用户确认
     * @param state array id订单号,uid用户uid
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function page_accreditss(){
        $code    = $_GET['code'];
        $params  = json_decode($_GET['state'],true);

        //静默授权,获取扫描商家的oppenid
        $appid   = C('APPID');
        $secret  = C('SECRET');
        $url     = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $data    = get_curl($url);
        $message = json_decode($data,true);
        $openid  = $message['openid'];

        //获取订单的绑定的openid
        $shop_openid = $this->order->get_shop_openid($params['id']);
        $wechat_arr  = explode(',',$shop_openid['wechat_num']);

        //比对openid
        if(!empty($shop_openid['wechat_num']) && !empty($openid)  && in_array($openid, $wechat_arr) && $shop_openid['pay_status'] == 1){
            //订单完成,发送给商家
            if($shop_openid['status'] != 1){
                $path = C('DOMAIN_NAME').U('Order/order_enter').'?id='.encrypt($params['id'], 'E', C('ENCYRPT'));

                //获取消费用户openid
                $templete_data = array(
                    'path'    => $path,
                    'time'    => $shop_openid['experience_time'],
                    'price'   => $shop_openid['price'],
                    'name'    => $shop_openid['name'],
                );
                $this->template_message_url($openid,$templete_data);

                //获取订单详情
                $order_data = $this->order->num_order_detail($params['id']);

                $this->order->startTrans();
                //修改订单状态
                $order_ret = $this->order->order_update($order_data['id'], '1');

                //计算返佣
                $user_data = $this->user->get_user_message($order_data['uid']);          //购买者信息
                $goods_radio = $this->goods->get_goods_rebate($order_data['goods_id']);  //购买商品佣金金额
                $user_ratio = $this->goods->get_goods_ratio($order_data['goods_id']);

                //购买者获取佣金
                $user_income_data = array(
                    'provid_uid' => $order_data['uid'],
                    'uid'        => $order_data['uid'],
                    'income'     => $goods_radio['rebate'] * $user_ratio['ratio'] / 100,
                    'type'       => 1,
                    'detail_id'  => $order_data['id'],
                    'time'       => date('Y-m-d H:i:s', time()),
                    'require'    => 1
                );
                $user_ret = $this->user->add_income($user_income_data);

                //上级获取佣金,默认为10%
                if($user_data['fid'] != 0){
                    $fid_income_data = array(
                        'provid_uid' => $order_data['uid'],
                        'uid'        => $user_data['fid'],
                        'income'     => $goods_radio['rebate'] * 10 / 100,
                        'type'       => 1,
                        'detail_id'  => $order_data['id'],
                        'time'       => date('Y-m-d H:i:s', time()),
                        'require'    => 1
                    );
                    $fid_ret = $this->user->add_income($fid_income_data);

                    //上级发送消息通知
                    $fid_content['uid']        = $user_data['fid'];
                    $fid_content['time']       = date('Y-m-d H:i:s', time());
                    $fid_content['provide_id'] = $order_data['uid'];
                    $fid_content['value']      = $fid_income_data['income'];
                    $fid_content['type']       = 2;
                    $fid_content['message']    = "消费返利";
                    M('Message')->data($fid_content)->add();
                }

                //上上级获取佣金,默认30%
                if($user_data['gid'] != 0){
                    $gid_income_data = array(
                        'provid_uid' => $order_data['uid'],
                        'uid'        => $user_data['gid'],
                        'income'     => $goods_radio['rebate'] * 30 / 100,
                        'type'       => 1,
                        'detail_id'  => $order_data['id'],
                        'time'       => date('Y-m-d H:i:s', time()),
                        'require'    => 0
                    );
                    $gid_ret = $this->user->add_income($gid_income_data);

                    //上上级发送消息通知
                    $gid_content['uid']        = $user_data['gid'];
                    $gid_content['time']       = date('Y-m-d H:i:s', time());
                    $gid_content['provide_id'] = $order_data['uid'];
                    $gid_content['value']      = $gid_income_data['income'];
                    $gid_content['type']       = 2;
                    $gid_content['message']    = "消费返利";
                    M('Message')->data($gid_content)->add();
                }

                if ($order_ret !== false && $user_ret !== false && $fid_ret !== false && $gid_ret !== false) {
                    $this->order->commit();
                    $this->display('Qrcode/buy_success');
                } else {
                    $this->order->rollback();
                    //需要添加日志记录 出现错误
                    $this->display('Qrcode/buy_fail');
                }
            }else{
                $this->display('Qrcode/buy_fail');
            }
        }else{
            $this->display('Qrcode/buy_fail');
        }
    }

    public function aa(){
        $str = "zhang";
        $key = "abc";

        $data = encrypt($str, 'E', $key);
        print_r($data);
        print_r(encrypt($data, 'D', $key));

        print_r(encrypt('lJ7PBJnpDg/FQY9+Dbwlu/5MRX6K3Y9w','D' ,C('ENCYRPT')));
    }

    /**
     * 用户模板消息订单确认消费显示页
     * @param id 订单号(order_num)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function order_enter(){
        $order_num = substr($_SERVER["QUERY_STRING"], 2);

        //订单号解密
        $num = encrypt($order_num, 'D', C('ENCYRPT'));

        //订单号获取订单详情
        $order_data = $this->order->num_order_detail($num);
        $order_data['pic'] = C('DOMAIN_NAME').'Uploads'.$order_data['pic'];

        if($order_data['status'] != '1'){
            $this->assign('num', $order_num);
        }

        $this->assign('order', $order_data);
        $this->display('Qrcode/enter');
    }

    /**
     * 用户确认订单消费完成
     * @param id 订单号(order_num)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function user_order_enter(){
        $order_num = I('post.num');

        //订单号解密
        $num = encrypt($order_num, 'D', C('ENCYRPT'));

        //订单号获取订单详情
        $order_data = $this->order->num_order_detail($num);
        $order_data['pic'] = C('DOMAIN_NAME').'Uploads'.$order_data['pic'];

        if($order_data['status'] != '1') {
            $this->order->startTrans();
            //修改订单状态
            $order_ret = $this->order->order_update($order_data['id'], '1');

            //计算返佣
            $user_data = $this->user->get_user_message($order_data['uid']);          //购买者信息
            $goods_radio = $this->goods->get_goods_rebate($order_data['goods_id']);  //购买商品佣金金额
            $user_ratio = $this->goods->get_goods_ratio($order_data['goods_id']);

            //购买者获取佣金
            $user_income_data = array(
                'provid_uid' => $order_data['uid'],
                'uid'        => $order_data['uid'],
                'income'     => $goods_radio['rebate'] * $user_ratio['ratio'] / 100,
                'type'       => 1,
                'detail_id'  => $order_data['id'],
                'time'       => date('Y-m-d H:i:s', time()),
                'require'    => 1
            );
            $user_ret = $this->user->add_income($user_income_data);

            //上级获取佣金,默认为10%
            if($user_data['fid'] != 0){
                $fid_income_data = array(
                    'provid_uid' => $order_data['uid'],
                    'uid'        => $user_data['fid'],
                    'income'     => $goods_radio['rebate'] * 10 / 100,
                    'type'       => 1,
                    'detail_id'  => $order_data['id'],
                    'time'       => date('Y-m-d H:i:s', time()),
                    'require'    => 1
                );
                $fid_ret = $this->user->add_income($fid_income_data);

                //上级发送消息通知
                $fid_content['uid']        = $user_data['fid'];
                $fid_content['time']       = date('Y-m-d H:i:s', time());
                $fid_content['provide_id'] = $order_data['uid'];
                $fid_content['value']      = $fid_income_data['income'];
                $fid_content['type']       = 2;
                $fid_content['message']    = "消费返利";
                M('Message')->data($fid_content)->add();
            }

            //上上级获取佣金,默认30%
            if($user_data['gid'] != 0){
                $gid_income_data = array(
                    'provid_uid' => $order_data['uid'],
                    'uid'        => $user_data['gid'],
                    'income'     => $goods_radio['rebate'] * 30 / 100,
                    'type'       => 1,
                    'detail_id'  => $order_data['id'],
                    'time'       => date('Y-m-d H:i:s', time()),
                    'require'    => 0
                );
                $gid_ret = $this->user->add_income($gid_income_data);

                //上上级发送消息通知
                $gid_content['uid']        = $user_data['gid'];
                $gid_content['time']       = date('Y-m-d H:i:s', time());
                $gid_content['provide_id'] = $order_data['uid'];
                $gid_content['value']      = $gid_income_data['income'];
                $gid_content['type']       = 2;
                $gid_content['message']    = "消费返利";
                M('Message')->data($gid_content)->add();
            }

            if ($order_ret !== false && $user_ret !== false && $fid_ret !== false && $gid_ret !== false) {
                $this->order->commit();
            } else {
                $this->order->rollback();
                //需要添加日志记录 出现错误
                echo "FAIL";
            }

        }

        $this->assign('order', $order_data);
        $this->display('Qrcode/enter');
    }

    /****************************************************************************************************
     * app-订单添加操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_order_add(){
        $params = I('post.');
        $uid    = get_uid();
        
        $experience_time = date('Y-m-d',strtotime(str_replace("月","/",$params['year']).$params['day']));

        //生成唯一订单号
        $order_num = get_order_num();

        //生成的订单二维码保存到本地
        Vendor('phpqrcode.phpqrcode');
        $code = array(
            'id'  => $order_num,
            'uid' => $uid
        );
        $state = json_encode($code);
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxebafdf0a98c558b5&redirect_uri=http://www.qwb.2015tt.net/index.php/Wechat/Order/page_accreditss&response_type=code&scope=snsapi_base&state='.$state.'#wechat_redirect';
        $path = 'Uploads/OrderQRcode/'.date('Y-m-d');
        if(!file_exists($path)) {
            mkdir($path);
        }
        $fileName = $path.'/'.md5($order_num).'.png';
        \QRcode::png($url, $fileName, QR_ECLEVEL_L, 10, 10, true);

        //获取物品详情信息
        $goods_data = $this->goods->get_goods_detail($params['goods_id']);

        $data = array(
            'uid'      => $uid,
            'buyer'    => $params['name'],
            'name'     => $goods_data['name'],
            'price'    => $params['choose_value'],
            'status'   => '0',
            'buy_time' => date('Y-m-d H:i:s',time()),
            'order_num'=> $order_num,
            'qr_code'  => $fileName,
            'goods_id' => $params['goods_id'],
            'count'    => $params['count'],
            'mobile'   => $params['mobile'],
            'pic'      => $goods_data['pic'],
            'message'  => $params['message'],
            'experience_time' => $experience_time,
            'shop_id'  => $goods_data['shop_id']
        );

        $ret = $this->order->order_add($data);
        if($ret){
            //发起支付页面
            $data['pic'] = C('DOMAIN_NAME').'Uploads'.$data['pic'];
            $this->assign('order', $data);
            $this->assign('id', $ret);
            $this->assign('buyTicket_select', $params['buyTicket_select']);
            $this->display('Order/app_orderIntroToBuy');
        }else{
            $this->error("异常错误");
        }
    }

    /**
     * 所有订单显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_my_order(){
        $uid = get_uid();
        $data = $this->order->get_order($uid);

        if($data === false){
            $this->error("异常错误");
        }else{
            //海报和订单二维码地址
            foreach ($data as $key => $value){
                foreach ($value as $k => $v){
                    if ($k == 'status' && $value[$k] == '-1'){
                        $value = null;
                        break;
                    }
                    if($k == 'pic'){
                        $value[$k] = C(DOMAIN_NAME).'Uploads'.$v;
                    }elseif($k == 'qr_code'){
                        $value[$k] = C(DOMAIN_NAME).$v;
                    }

                }
                if ($value != null){
                    $data[$key] = $value;
                }else{
                    unset($data[$key]);
                }

            }

            $this->assign('footer_order','footer_bottom-Clicked');
            $this->assign('order', $data);
        }
        $this->display('Order/app_myOrder');
    }

    /**
     * App微信商品支付回调
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_shop_notify(){
        $postStr = file_get_contents('php://input');
        $msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        //查看订单状态
        $order_ret = $this->order->num_order_detail($msg['out_trade_no']);

        if(!$order_ret['pay_status']){
            $ret = M('Wechat_pay')->data($msg)->add();

            //修改订单状态为已支付
            if($ret){
                $update_ret = $this->order->order_pay_update($order_ret['id'], $msg['out_trade_no'], 2);

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

                    echo "SUCCESS";
                }else{
                    echo "FAIL";
                }
            }else{
                echo "FAIL";
            }
        }else{
            echo "FAIL";
        }
    }

    /**
     * 订单详情显示页
     * @param id 订单id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_order_intro(){
        $order_id = I('get.id');
        $data     = $this->order->get_order_detail($order_id);
        //二维码和商品海报地址
        $data['pic']     = C(DOMAIN_NAME).'Uploads'.$data['pic'];
        $data['qr_code'] = C(DOMAIN_NAME).$data['qr_code'];

        if($data){
            $this->assign('footer_order','footer_bottom-Clicked');
            $this->assign('id',$data['id']);
            $this->assign('order_num',$data['order_num']);
            $this->assign('count',$data['count']);
            $this->assign('price',$data['price']);
            $this->assign('buyer',$data['buyer']);
            $this->assign('mobile',$data['mobile']);
            $this->assign('buy_time',$data['buy_time']);
            $this->assign('experience_time',$data['experience_time']);
            $this->assign('message',$data['message']);
            $this->assign('qr_code',$data['qr_code']);
            $this->assign('pic',$data['pic']);
            $this->assign('status',$data['status']);
            $this->display('Order/app_orderIntro');
        }else{
            $this->error('异常错误');
        }
    }
}