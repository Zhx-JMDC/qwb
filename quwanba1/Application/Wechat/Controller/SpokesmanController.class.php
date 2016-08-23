<?php
namespace Wechat\Controller;
use Think\Controller;
use Wechat\Model\UserModel;
use Wechat\Model\IncomeModel;
use Wechat\Model\CardHistoryModel;
use Wechat\Model\OrderModel;
class SpokesmanController extends CommonController {
    protected $user;
    protected $income;
    protected $card;
    protected $order;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->user   = new UserModel();
        $this->income = new IncomeModel();
        $this->card   = new CardHistoryModel();
        $this->order  = new OrderModel();
    }

    /**
     * 我的饭友显示页(下线)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function firends(){
        $uid  = get_uid();
        $page = 1;
        $data = $this->user->get_firends($uid, 'fid', $page);
        $friend_count = $this->user->get_firends_count($uid, 'fid');
        $circle_count = $this->user->get_firends_count($uid, 'gid');
        if($data === flase){
            $this->error("查询出错");
        }else{
            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('friend_count',$friend_count);
            $this->assign('circle_count',$circle_count);
            $this->assign('page',$page);
            $this->assign('friends',$data);
        }

        $this->display('Spokesman/firends');
    }

    /**
     * 我的饭友下拉加载
     * $param  $page 当前页数
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function friends_more(){
        $uid  = get_uid();
        $page = I('post.page');
        $data = $this->user->get_firends($uid, 'fid', $page);
        $this->ajaxReturn($data);
    }

    /**
     * 我的饭圈显示页(下下线)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function circle(){
        $uid  = get_uid();
        $page = 1;
        $data = $this->user->get_firends($uid, 'gid', $page);
        $friend_count = $this->user->get_firends_count($uid, 'fid');
        $circle_count = $this->user->get_firends_count($uid, 'gid');
        if($data === flase){
            $this->error("查询出错");
        }else{
            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('friend_count',$friend_count);
            $this->assign('circle_count',$circle_count);
            $this->assign('page',$page);
            $this->assign('circle',$data);
        }
        $this->display('Spokesman/circle');
    }

    /**
     * 我的饭圈下拉加载
     * $param  $page 当前页数
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function circle_more(){
        $uid  = get_uid();
        $page = I('post.page');
        $data = $this->user->get_firends($uid, 'gid', $page);
        $this->ajaxReturn($data);
    }

    /**
     * 我的收入显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function my_income(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);
        switch ($data['rank']) {
            case 0: $rank = 'vip_letter_s'; break;
            case 1: $rank = 'vip_letter_v'; break;

            case 2: $rank = 'vip_letter'; break;
            default:;
        }

        //获取用户累计饭票status
        $total_map['uid'] = $uid;
        $total_income = M('Income')->where($total_map)->sum('income');

        //获取饭票记录
        $map['uid'] = $uid;
        $income_count = M('Income')->where($map)->count();
        $income_date  = $this->income->get_income_all($uid, 1, 3);
        foreach($income_date as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'time'){
                    $value[$k] = date('Y/m/d H-i:s',strtotime($v));
                }elseif($k == 'type'){
                    if($v == '1'){
                        $value['headimgurl'] = C('DOMAIN_NAME').'Uploads/Icon/return.png';
                        $value['nickname'] = '消费返利';
                    }
                }
            }
            $income_date[$key] = $value;
        }

        //获取推荐人
        if($data['fid'] == 0){
            $nickname = '小趣';
        }else{
            $fid_map['id'] = $data['fid'];
            $fid_data = M('User')->field('nickname')->where($fid_map)->find();
            $nickname = $fid_data['nickname'];
        }
        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('_count',$income_count);
        $this->assign('nickname',$nickname);
        $this->assign('income',$income_date);
        $this->assign('total_income',$total_income);
        $this->assign('user',$data);
        $this->assign('rank',$rank);
        $this->display('Spokesman/myIncome');
    }

    /**
     * 我的收入显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function my_income_more(){
        $uid   = get_uid();
        $count = I('post.count');
        $page  = I('post.page');

        //获取饭票记录
        $income_date  = $this->income->get_income_all($uid, $page, $count);

        $this->ajaxReturn($income_date);
    }

    /**
     * 我是旅行者主页显示
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function represent_v(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        //获取用户累计饭票status
        $total_map['uid'] = $uid;
        $total_income = M('Income')->where($total_map)->sum('income');

        //饭圈饭友人数
        $friend_count = $this->user->get_firends_count($uid, 'fid');
        $circle_count = $this->user->get_firends_count($uid, 'gid');
        switch ($data['rank']) {
            case 0:
                $rank = 'vip_letter_s';

                $this->income->startTrans();
                //统计可提现饭票
                $map['uid']    = $uid;
                $map['status'] = 0;
                $income_sum = M('Income')->where($map)->sum('income');

                if(!empty($income_sum)){
                    //修改可提现饭票状态
                    $income_data['status'] = 1;
                    $income_ret = M('Income')->where($map)->save($income_data);

                    //汇入账户
                    $account_map['uid'] = $uid;
                    $account_ret = M('Account')->where($account_map)->setInc('account',$income_sum);
                }
                if ($account_ret !== false && $income_ret !== false) {
                    $this->income->commit();
                } else {
                    $this->income->rollback();
                    $this->error("异常错误");
                }

                //查询账户余额,账户余额就是可提现饭票
                $account_balance_map['uid'] = $uid;
                $account_data    = M('Account')->field('account')->where($account_balance_map)->find();
                $withdraw_income = $account_data['account'];

                break;
            case 1:
                $rank = 'vip_letter_v';

                //V级,可提取饭票(账户余额)
                $this->income->startTrans();
                //是否存在可提现饭票,存在要汇入账户
                $map['uid']     = $uid;
                $map['status']  = 0;
                $map['require'] = 1;
                $income_sum = M('Income')->where($map)->sum('income');

                if(!empty($income_sum)){
                    //修改可提现饭票状态
                    $income_data['uid']     = $uid;
                    $income_data['status']  = 1;
                    $income_ret = M('Income')->where($map)->save($income_data);

                    //汇入账户
                    $account_map['uid'] = $uid;
                    $account_ret = M('Account')->where($account_map)->setInc('account',$income_sum);
                }
                if ($account_ret !== false && $income_ret !== false) {
                    $this->income->commit();
                } else {
                    $this->income->rollback();
                    $this->error("异常错误");
                }

                //查询账户余额,账户余额就是可提现饭票
                $account_balance_map['uid'] = $uid;
                $account_data    = M('Account')->field('account')->where($account_balance_map)->find();
                $withdraw_income = $account_data['account'];

                //待提取
                $wait_map['uid']     = $uid;
                $wait_map['require'] = 0;
                $wait_map['status']  = 0;
                $wait_income = M('Income')->where($wait_map)->sum('income');
                break;

            case 2: $rank = 'vip_letter';
                $withdraw_income = 0.00;

                //待提取
                $wait_map['uid']     = $uid;
                $wait_map['status']  = 0;
                $wait_income = M('Income')->where($wait_map)->sum('income');
                break;
            default:;
        }

        //获取推荐人
        if($data['fid'] == 0){
            $nickname = '小趣';
        }else{
            $fid_map['id'] = $data['fid'];
            $fid_data = M('User')->field('nickname')->where($fid_map)->find();
            $nickname = $fid_data['nickname'];
        }

        //获取未读消息数
        $message['status'] = 0;
        $message['uid']    = $uid;
        $message_count = M('Message')->where($message)->count();

        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('message_count',$message_count);
        $this->assign('nickname',$nickname);
        $this->assign('income',$total_income);
        $this->assign('wait_income',$wait_income);
        $this->assign('withdraw_income',$withdraw_income);
        $this->assign('friend_count',$friend_count);
        $this->assign('circle_count',$circle_count);
        $this->assign('user',$data);
        $this->assign('rank',$rank);

        $this->display('Spokesman/representV');
    }

    /**
     * 周游卡购卡显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function buy_card(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        switch ($data['rank']) {
            case 0: $rank = 'vip_letter_s'; break;
            case 1: $rank = 'vip_letter_v'; break;
            case 2: $rank = 'vip_letter';   break;
            default:;
        }

        //获取推荐人
        if($data['fid'] == 0){
            $nickname = '小趣';
        }else{
            $fid_map['id'] = $data['fid'];
            $fid_data = M('User')->field('nickname')->where($fid_map)->find();
            $nickname = $fid_data['nickname'];
        }

        //获取未读消息数
        $message['status'] = 0;
        $message['uid']    = $uid;
        $message_count = M('Message')->where($message)->count();

        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('message_count',$message_count);
        $this->assign('nickname',$nickname);
        $this->assign('user',$data);

        if($data['rank'] == 0 || $data['rank'] == 1){
            $this->redirect('Spokesman/app_download');
        }else{
            //获取V级海报
            $v_card = M('Goods_type')->field('icon')->where('id = 5')->find();
            $v_card['icon'] = C('DOMAIN_NAME').'Uploads'.$v_card['icon'];

            $this->assign('v_card',$v_card['icon']);
            $this->assign('rank',$rank);
            $this->display('Spokesman/buyCard');
        }
    }

    /**
     * 我的二维码显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function my_QRode(){
        $uid = get_uid();
        $openid = $this->user->get_openid($uid);
        
        $path = $this->user->get_user_QRcode($openid);
        if($path['qr_code'] == null){
            //重新生成
            $filename = $this->get_QRcode($path['id']);
            $this->user->update_QRcode($path['id'], $filename);
            $QRcode_url = C('DOMAIN_NAME').$filename;
        }elseif($path === flase){
            $this->error("查询出错");
        }else{
            $QRcode_url = C('DOMAIN_NAME').$path['qr_code'];
        }
        $this->assign('QRcode',$QRcode_url);
        $this->assign('head',$path['headimgurl']);
        $this->assign('nickname',$path['nickname']);
        $this->display('Spokesman/myQRode');
    }

    /**
     * 购买周游卡资料填写显示页
     * @param  周游卡类型(0 S级,1 V级)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_information(){
        $type = I('get.type');
        $uid = get_uid();

        //购买V级卡,跳转填写资料页
        if($type == 1){
            //用户基本信息
            $data = $this->user->get_user_message($uid);

            //获取推荐人
            if($data['fid'] == 0){
                $nickname = '小趣';
            }else{
                $fid_map['id'] = $data['fid'];
                $fid_data = M('User')->field('nickname')->where($fid_map)->find();
                $nickname = $fid_data['nickname'];
            }

            $this->assign('nickname',$nickname);

            //防止多次提交
            $this->assign('flag',1);
            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('type',$type);
            $this->display('Spokesman/cardInformation');
        }elseif($type == 0){
            $this->redirect('WechatPay/buy_card_pay');
        }
    }

    /**
     * 等待提现显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function wait_cash(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        //获取提现记录
        $history_map['uid']    = $uid;
        $history_map['status'] = 1;
        $history_count = M('Withdraw_history')->where($history_map)->count();
        $history_data  = $this->income->get_withdraw_history($uid, 1, 3);

        if($data['rank'] == 0){
            //S级用户,无待提现饭票
            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('_count',$history_count);
            $this->assign('history',$history_data);
            $this->display('Spokesman/cashGetZero');

        }else{
            //需要S级用户才可提取
            $wait_map_s['uid']     = $uid;
            $wait_map_s['require'] = 0;
            $wait_map_s['status']  = 0;
            $wait_income_s = M('Income')->where($wait_map_s)->sum('income');

            if($data['rank'] == 1){
                $this->assign('wait_income',$wait_income_s);
                $this->display('Spokesman/cashGetV');

            }elseif($data['rank'] == 2){
                //成为V级可提取饭票
                $withdraw_map_v['uid']     = $uid;
                $withdraw_map_v['require'] = 1;
                $withdraw_map_v['status']  = 0;
                $withdraw_income_v = M('Income')->where($withdraw_map_v)->sum('income');

                //成为S级可提取
                $withdraw_map_s['uid']     = $uid;
                $withdraw_map_s['status']  = 0;
                $withdraw_income_s = M('Income')->where($withdraw_map_s)->sum('income');

                $this->assign('footer_represent','footer_bottom-Clicked');
                $this->assign('income_v', $withdraw_income_v);
                $this->assign('income_s', $withdraw_income_s);
                $this->display('Spokesman/cashGetNone');
            }
        }
    }

    /**
     * 可提现金额显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function cash_get(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        if($data['rank'] == 2){
            //成为V级可提取饭票
            $withdraw_map_v['uid']     = $uid;
            $withdraw_map_v['require'] = 1;
            $withdraw_map_v['status']  = 0;
            $withdraw_income_v = M('Income')->where($withdraw_map_v)->sum('income');

            //成为S级可提取
            $wait_map_s['uid']     = $uid;
            $wait_map_s['status']  = 0;
            $withdraw_income_s = M('Income')->where($wait_map_s)->sum('income');

            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('income_v', $withdraw_income_v);
            $this->assign('income_s', $withdraw_income_s);
            $this->display('Spokesman/cashGetNone');
            exit();

        }else{
            //统计可提现饭票,即账户余额
            $account_balance_map['uid'] = $uid;
            $account_data = M('Account')->field('account')->where($account_balance_map)->find();

            //获取提现记录
            $history_map['uid']    = $uid;
            $history_map['status'] = 1;
            $history_count = M('Withdraw_history')->where($history_map)->count();
            $history_data  = $this->income->get_withdraw_history($uid, 1, 3);

            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('_count',$history_count);
            $this->assign('history',$history_data);
            $this->assign('account',$account_data['account']);
            $this->display('Spokesman/cashGet');
        }
    }

    /**
     * 下拉加载-用户提现记录
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function withdraw_history_more(){
        $uid   = get_uid();
        $count = I('post.count');
        $page  = I('post.page');

        //获取提现记录
        $history_data = $this->income->get_withdraw_history($uid, $page, $count);
        $this->ajaxReturn($history_data);
    }

    /**
     * 微信用户提现
     * @param value 提现金额
     * @param type  1公众号提现,0app提现
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function withdraw_deposit(){
        $uid     = get_uid();
        $type    = I('post.type');
        $value   = I('post.value');
        //金额验证
        if(trim($value) <= 0 && !is_numeric($value)){
            //不可提现
            if($type == 1){
                $this->cash_get();
            }elseif($type == 0){
                $this->app_cash_get();
            }
            $warn = '请输入正确的金额数';
            echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\"/><script> alert('{$warn}') </script>";
        }


        $account = M('Account');

        //验证用户账户余额
        $map['uid'] = $uid;
        $account_data = $account->field('account')->where($map)->find();

        if($value <= $account_data['account']){
            //可提现
            $account->startTrans();

            //扣除账户金额
            $account_ret = $account->where($map)->setDec('account',$value);

            //获取用户openid
            $openid = $this->user->get_openid($uid);

            //微信发起退款
            $trade_no   = get_order_num();
            $wechat_ret = $this->pay_wx($openid, $value*100, $trade_no);

            //创建提现记录
            $withdraw_data['time']     = date('Y-m-d H:i:s', time());
            $withdraw_data['trade_no'] = $trade_no;
            $withdraw_data['uid']      = $uid;
            $withdraw_data['value']    = $value;
            $withdraw_data['type']     = 1;
            $withdraw_data['status']   = 1;
            $add_ret = M('withdraw_history')->add($withdraw_data);

            if($wechat_ret === 0){
                if($type == 1){
                    $this->cash_get();
                }elseif($type == 0){
                    $this->app_cash_get();
                }
                $warn = '今日提现次数已达上线';
                echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\"/><script> alert('{$warn}') </script>";
                exit();
            }

            if($account_ret !== false && $wechat_ret && $add_ret !== false){
                $account->commit();

                if($type == 1){
                    $this->cash_get();
                }elseif($type == 0){
                    $this->app_cash_get();
                }

//                if($type == 1){
//                    //公众号
//                    $this->redirect('Spokesman/represent_v');
//                }elseif($type == 0){
//                    //app
//                    $this->redirect('Spokesman/app_represent_v');
//                }
                $warn = '提现成功';
                echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\"/><script> alert('{$warn}') </script>";
            }else{
                $account->rollback();
                if($type == 1){
                    $this->cash_get();
                }elseif($type == 0){
                    $this->app_cash_get();
                }
                $warn = '提现失败,检查金额或联系客服';
                echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\"/><script> alert('{$warn}') </script>";
            }
        }else{
            if($type == 1){
                $this->cash_get();
            }elseif($type == 0){
                $this->app_cash_get();
            }
            //不可提现
            $warn = '旅行家账户余额不足';
            echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\"/><script> alert('{$warn}') </script>";
        }
    }

    /**
     * 微信提现
     * @param $openid    用户openid
     * @param $price     提现金额
     * @param $trade_no  唯一订单号
     * @return array
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function pay_wx($openid, $price, $trade_no){
        Vendor('wxpay.example.WxPay#MchPay');
        $mchPay = new \MchPay();
        // 用户openid
        $mchPay->parameters['openid'] = $openid;
        $mchPay->parameters['partner_trade_no'] = $trade_no;
        $mchPay->parameters['check_name'] = "NO_CHECK";
        $mchPay->parameters['amount'] = $price;
        $mchPay->parameters['desc'] = "微信提现";
        $mchPay->parameters['spbill_create_ip'] = get_client_ip();
        $xml = $mchPay->createXml();

        $response = $mchPay->postXmlSSLCurl($xml,$mchPay->url);
        if( !empty($response) ) {
            $data = simplexml_load_string($response, null, LIBXML_NOCDATA);
            $json = json_encode($data);
            $json_data = json_decode($json);

            $return_data['return_code'] = $json_data->return_code;
            $return_data['return_msg'] = $json_data->return_msg;
            $return_data['result_code'] = $json_data->result_code;
            $return_data['err_code'] = $json_data->err_code;
            $return_data['err_code_des'] = $json_data->err_code_des;

            if($return_data['return_code'] == "SUCCESS" && $return_data['err_code'] == ''){
                return true;
            }elseif($return_data['err_code'] == 'SENDNUM_LIMIT'){
                //不可提现
                return 0;
            }else{
                //提现失败
                return false;
            }
        }else {
            return array('return_code' => 'FAIL', 'return_msg' => 'transfers_接口出错', 'return_ext' => array());
        }
    }

    /**
     * 统一下单接口,生成app参数
     * @param [int] [id] 商品订单号
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_wechat_pay(){
        $id = I('post.id');

        //获取订单详细信息
        $order_data = $this->order->get_order_detail($id);

        Vendor('wxpay.example.WxPay#AppPay');

        //填写配置参数
        $options = array(
            'spbill_create_ip' =>C('SPBILL_IP'),
            'appid' 	       => 'wx6c33c97e5e4a9056',		                                 //公众开放账号ID
            'mch_id'	       => '1376680402',				                                 //商户号
            'notify_url'       => C('DOMAIN_NAME')."index.php/Wechat/Order/app_shop_notify", //回调地址
            'key'		       => 'e10adc3949ba59abbe56e057f20f883e',		                 //商户支付密钥Key
        );

        //统一下单方法
        $wechatAppPay           = new \wechatAppPay($options);
        $params['body']         = '去玩吧旅游';						//商品描述
        $params['out_trade_no'] = $order_data['order_num'];                       //自定义的订单号
        $params['total_fee']    = '1';					            //订单金额 只能为整数 单位为分
        $params['trade_type']   = 'APP';					        //交易类型 JSAPI | NATIVE | APP | WAP
        $result = $wechatAppPay->unifiedOrder($params);

        //创建APP端预支付参数
        /** @var TYPE_NAME $result */
        $data = @$wechatAppPay->getAppPayParams( $result['prepay_id'] );
        response(1, 'success' ,$data);
    }

    /**
     * 下线消息通知
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function message(){
        $uid = get_uid();

        //获取饭圈消息
        $map['uid']  = $uid;
        $map['type'] = 1;
        $message_count = M('Message')->where($map)->count();
        $where['type'] = 1;
        $message_data  = $this->income->get_message($uid,$where, 1, 10);

        //修改饭圈消息为已读
        $data['status'] = 1;
        $data['type']   = 1;
        $message_where['uid']    = $uid;
        $message_where['type']   = 1;
        $message_where['status'] = 0;
        M('Message')->where($message_where)->save($data);

        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('_count', $message_count);
        $this->assign('message', $message_data);
        $this->display('Spokesman/message');

    }

    /**
     * 返佣消息通知
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function message2(){
        $uid = get_uid();

        //获取返佣消息
        $map['uid']    = $uid;
        $map['type']   = array(array('eq', 2),array('eq', 3), 'or') ;
        $message_count = M('Message')->where($map)->count();
        $where['type'] = array(array('eq', 2),array('eq', 3), 'or') ;
        $message_data  = $this->income->get_message($uid,$where, 1, 3);

        foreach($message_data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'type'){
                    if($v == '2'){
                        $value['headimgurl'] = C('DOMAIN_NAME').'Uploads/Icon/return.png';
                        $value['nickname'] = '消费返利';
                    }
                }
            }
            $message_data[$key] = $value;
        }

        //修改饭圈消息为已读
        $data['status'] = '1';
        $data['type']   = array(array('eq', 2),array('eq', 3), 'or') ;
        $message_where['uid']    = $uid;
        $message_where['type']   = array(array('eq', 2),array('eq', 3), 'or') ;
        $message_where['status'] = 0;
        M('Message')->where($message_where)->save($data);

        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('_count', $message_count);
        $this->assign('message', $message_data);
        $this->display('Spokesman/message2');
    }

    /**
     * 下拉加载-消息通知
     * @param type 消息类型 1下线消息, 2返佣'
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function message_more(){
        $uid   = get_uid();
        $count = I('post.count');
        $page  = I('post.page');
        $type  = I('post.type');

        if($type == 1){
            $where['type'] = 1;
        }else{
            $where['type'] = array(array('eq', 2),array('eq', 3), 'or') ;
        }

        $message_data  = $this->income->get_message($uid, $where, $page, $count);
        foreach ($message_data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'time'){
                    $value[$k] = date('Y-m-d', strtotime($v));
                }
            }
            $message_data[$key] = $value;
        }

        $this->ajaxReturn($message_data);
    }

    /**
     * 下载app显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_download(){
        $this->display('Spokesman/download');
    }

    /**
     * 趣玩社显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function play_community(){
        $this->display('Spokesman/play_community');
    }

    /****************************************************************************************
     * 我是旅行者主页显示
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_represent_v(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        //获取用户累计饭票status
        $total_map['uid'] = $uid;
        $total_income = M('Income')->where($total_map)->sum('income');

        //饭圈饭友人数
        $friend_count = $this->user->get_firends_count($uid, 'fid');
        $circle_count = $this->user->get_firends_count($uid, 'gid');
        switch ($data['rank']) {
            case 0:
                $rank = 'vip_letter_s';

                $this->income->startTrans();
                //统计可提现饭票
                $map['uid']    = $uid;
                $map['status'] = 0;
                $income_sum = M('Income')->where($map)->sum('income');

                if(!empty($income_sum)){
                    //修改可提现饭票状态
                    $income_data['status'] = 1;
                    $income_ret = M('Income')->where($map)->save($income_data);

                    //汇入账户
                    $account_map['uid'] = $uid;
                    $account_ret = M('Account')->where($account_map)->setInc('account',$income_sum);
                }
                if ($account_ret !== false && $income_ret !== false) {
                    $this->income->commit();
                } else {
                    $this->income->rollback();
                    $this->error("异常错误");
                }

                //查询账户余额,账户余额就是可提现饭票
                $account_balance_map['uid'] = $uid;
                $account_data    = M('Account')->field('account')->where($account_balance_map)->find();
                $withdraw_income = $account_data['account'];

                break;
            case 1:
                $rank = 'vip_letter_v';

                //V级,可提取饭票(账户余额)
                $this->income->startTrans();
                //是否存在可提现饭票,存在要汇入账户
                $map['uid']     = $uid;
                $map['status']  = 0;
                $map['require'] = 1;
                $income_sum = M('Income')->where($map)->sum('income');

                if(!empty($income_sum)){
                    //修改可提现饭票状态
                    $income_data['uid']     = $uid;
                    $income_data['status']  = 1;
                    $income_ret = M('Income')->where($map)->save($income_data);

                    //汇入账户
                    $account_map['uid'] = $uid;
                    $account_ret = M('Account')->where($account_map)->setInc('account',$income_sum);
                }
                if ($account_ret !== false && $income_ret !== false) {
                    $this->income->commit();
                } else {
                    $this->income->rollback();
                    $this->error("异常错误");
                }

                //查询账户余额,账户余额就是可提现饭票
                $account_balance_map['uid'] = $uid;
                $account_data    = M('Account')->field('account')->where($account_balance_map)->find();
                $withdraw_income = $account_data['account'];

                //待提取
                $wait_map['uid']     = $uid;
                $wait_map['require'] = 0;
                $wait_map['status']  = 0;
                $wait_income = M('Income')->where($wait_map)->sum('income');
                break;

            case 2: $rank = 'vip_letter';
                $withdraw_income = 0.00;

                //待提取
                $wait_map['uid']     = $uid;
                $wait_map['status']  = 0;
                $wait_income = M('Income')->where($wait_map)->sum('income');
                break;
            default:;
        }

        //获取推荐人
        if($data['fid'] == 0){
            $nickname = '小趣';
        }else{
            $fid_map['id'] = $data['fid'];
            $fid_data = M('User')->field('nickname')->where($fid_map)->find();
            $nickname = $fid_data['nickname'];
        }

        //获取未读消息数
        $message['status'] = 0;
        $message['uid']    = $uid;
        $message_count = M('Message')->where($message)->count();

        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('message_count',$message_count);
        $this->assign('nickname',$nickname);
        $this->assign('income',$total_income);
        $this->assign('wait_income',$wait_income);
        $this->assign('withdraw_income',$withdraw_income);
        $this->assign('friend_count',$friend_count);
        $this->assign('circle_count',$circle_count);
        $this->assign('user',$data);
        $this->assign('rank',$rank);

        $this->display('Spokesman/app_representV');
    }

    /**
     * 我的收入显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_my_income(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);
        switch ($data['rank']) {
            case 0: $rank = 'vip_letter_s'; break;
            case 1: $rank = 'vip_letter_v'; break;

            case 2: $rank = 'vip_letter'; break;
            default:;
        }

        //获取用户累计饭票status
        $total_map['uid'] = $uid;
        $total_income = M('Income')->where($total_map)->sum('income');

        //获取饭票记录
        $map['uid'] = $uid;
        $income_count = M('Income')->where($map)->count();
        $income_date  = $this->income->get_income_all($uid, 1, 3);
        foreach($income_date as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'time'){
                    $value[$k] = date('Y/m/d H-i:s',strtotime($v));
                }elseif($k == 'type'){
                    if($v == '1'){
                        $value['headimgurl'] = C('DOMAIN_NAME').'Uploads/Icon/return.png';
                        $value['nickname'] = '消费返利';
                    }
                }
            }
            $income_date[$key] = $value;
        }

        //获取推荐人
        if($data['fid'] == 0){
            $nickname = '小趣';
        }else{
            $fid_map['id'] = $data['fid'];
            $fid_data = M('User')->field('nickname')->where($fid_map)->find();
            $nickname = $fid_data['nickname'];
        }
        
        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('_count',$income_count);
        $this->assign('nickname',$nickname);
        $this->assign('income',$income_date);
        $this->assign('total_income',$total_income);
        $this->assign('user',$data);
        $this->assign('rank',$rank);
        $this->display('Spokesman/app_myIncome');
    }

    /**
     * 可提现金额显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_cash_get(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        if($data['rank'] == 2){
            //成为V级可提取饭票
            $withdraw_map_v['uid']     = $uid;
            $withdraw_map_v['require'] = 1;
            $withdraw_map_v['status']  = 0;
            $withdraw_income_v = M('Income')->where($withdraw_map_v)->sum('income');

            //成为S级可提取
            $wait_map_s['uid']     = $uid;
            $wait_map_s['status']  = 0;
            $withdraw_income_s = M('Income')->where($wait_map_s)->sum('income');

            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('income_v', $withdraw_income_v);
            $this->assign('income_s', $withdraw_income_s);
            $this->display('Spokesman/app_cashGetNone');
            exit();

        }else{
            //统计可提现饭票,即账户余额
            $account_balance_map['uid'] = $uid;
            $account_data = M('Account')->field('account')->where($account_balance_map)->find();

            //获取提现记录
            $history_map['uid']    = $uid;
            $history_map['status'] = 1;
            $history_count = M('Withdraw_history')->where($history_map)->count();
            $history_data  = $this->income->get_withdraw_history($uid, 1, 3);

            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('_count',$history_count);
            $this->assign('history',$history_data);

            if($account_data['account'] > 0){
                $this->assign('account',$account_data['account']);
                $this->display('Spokesman/app_cashGet');
            }else{
                $this->display('Spokesman/app_cashGetZero');
            }
        }
    }

    /**
     * 等待提现显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_wait_cash(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        //获取提现记录
        $history_map['uid']    = $uid;
        $history_map['status'] = 1;
        $history_count = M('Withdraw_history')->where($history_map)->count();
        $history_data  = $this->income->get_withdraw_history($uid, 1, 3);

        if($data['rank'] == 0){
            //S级用户,无待提现饭票
            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('_count',$history_count);
            $this->assign('history',$history_data);
            $this->display('Spokesman/app_cashGetZero');

        }else{
            //需要S级用户才可提取
            $wait_map_s['uid']     = $uid;
            $wait_map_s['require'] = 0;
            $wait_map_s['status']  = 0;
            $wait_income_s = M('Income')->where($wait_map_s)->sum('income');

            if($data['rank'] == 1){
                $this->assign('wait_income',$wait_income_s);
                $this->assign('footer_represent','footer_bottom-Clicked');
                $this->display('Spokesman/app_cashGetV');

            }elseif($data['rank'] == 2){
                //成为V级可提取饭票
                $withdraw_map_v['uid']     = $uid;
                $withdraw_map_v['require'] = 1;
                $withdraw_map_v['status']  = 0;
                $withdraw_income_v = M('Income')->where($withdraw_map_v)->sum('income');

                //成为S级可提取
                $wait_map_s['uid']     = $uid;
                $wait_map_s['status']  = 0;
                $withdraw_income_s = M('Income')->where($wait_map_s)->sum('income');

                $this->assign('footer_represent','footer_bottom-Clicked');
                $this->assign('income_v', $withdraw_income_v);
                $this->assign('income_s', $withdraw_income_s);
                $this->display('Spokesman/app_cashGetNone');
            }
        }
    }

    /**
     * 我的饭友显示页(下线)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_firends(){
        $uid  = get_uid();
        $page = 1;
        $data = $this->user->get_firends($uid, 'fid', $page);
        $friend_count = $this->user->get_firends_count($uid, 'fid');
        $circle_count = $this->user->get_firends_count($uid, 'gid');
        if($data === flase){
            $this->error("查询出错");
        }else{
            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('friend_count',$friend_count);
            $this->assign('circle_count',$circle_count);
            $this->assign('page',$page);
            $this->assign('friends',$data);
        }

        $this->display('Spokesman/app_firends');
    }

    /**
     * 我的饭圈显示页(下下线)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_circle(){
        $uid  = get_uid();
        $page = 1;
        $data = $this->user->get_firends($uid, 'gid', $page);
        $friend_count = $this->user->get_firends_count($uid, 'fid');
        $circle_count = $this->user->get_firends_count($uid, 'gid');
        if($data === flase){
            $this->error("查询出错");
        }else{
            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('friend_count',$friend_count);
            $this->assign('circle_count',$circle_count);
            $this->assign('page',$page);
            $this->assign('circle',$data);
        }
        $this->display('Spokesman/app_circle');
    }

    /**
     * 周游卡购卡显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_buy_card(){
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        switch ($data['rank']) {
            case 0: $rank = 'vip_letter_s'; break;
            case 1: $rank = 'vip_letter_v'; break;
            case 2: $rank = 'vip_letter';   break;
            default:;
        }

        //获取推荐人
        if($data['fid'] == 0){
            $nickname = '小趣';
        }else{
            $fid_map['id'] = $data['fid'];
            $fid_data = M('User')->field('nickname')->where($fid_map)->find();
            $nickname = $fid_data['nickname'];
        }

        if($data['rank'] == 2){
            //获取V级海报
            $card = M('Goods_type')->field('icon')->where('id = 5')->find();
        }elseif($data['rank'] == 1){
            //获取S级海报
            $card = M('Goods_type')->field('icon')->where('id = 4')->find();
        }
        $card['icon'] = C('DOMAIN_NAME').'Uploads'.$card['icon'];
        $this->assign('card',$card['icon']);

        //获取未读消息数
        $message['status'] = 0;
        $message['uid']    = $uid;
        $message_count = M('Message')->where($message)->count();

        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('message_count',$message_count);
        $this->assign('nickname',$nickname);
        $this->assign('rank',$rank);
        $this->assign('user',$data);
        $this->display('Spokesman/app_buyCard');
    }

    /**
     * 购买周游卡资料填写显示页
     * @param  周游卡类型(0 S级,1 V级)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_card_information(){
        $type = I('get.type');
        $uid = get_uid();

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        if($data['rank'] == 2){
            //获取V级海报
            $card = M('Goods_type')->field('icon')->where('id = 5')->find();
        }elseif($data['rank'] == 1){
            //获取S级海报
            $card = M('Goods_type')->field('icon')->where('id = 4')->find();
        }
        $card['icon'] = C('DOMAIN_NAME').'Uploads'.$card['icon'];
        $this->assign('card',$card['icon']);


        //购买V级卡,跳转填写资料页
        if($type == 1){
            //获取推荐人
            if($data['fid'] == 0){
                $nickname = '小趣';
            }else{
                $fid_map['id'] = $data['fid'];
                $fid_data = M('User')->field('nickname')->where($fid_map)->find();
                $nickname = $fid_data['nickname'];
            }

            $this->assign('nickname',$nickname);

            //防止多次提交
            $this->assign('flag',1);
            $this->assign('footer_represent','footer_bottom-Clicked');
            $this->assign('type',$type);
            $this->display('Spokesman/app_cardInformation');
        }elseif($type == 0){
            $this->redirect('WechatPay/app_buy_card_pay');
        }
    }

    /**
     * 消息通知
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_message(){
        $uid = get_uid();

        //获取饭圈消息
        $map['uid']  = $uid;
        $map['type'] = 1;
        $message_count = M('Message')->where($map)->count();
        $where['type'] = 1;
        $message_data  = $this->income->get_message($uid,$where, 1, 10);

        //修改饭圈消息为已读
        $data['status'] = 1;
        $data['type']   = 1;
        $message_where['uid']    = $uid;
        $message_where['type']   = 1;
        $message_where['status'] = 0;
        M('Message')->where($message_where)->save($data);

        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('_count', $message_count);
        $this->assign('message', $message_data);
        $this->display('Spokesman/app_message');

    }

    public function app_message2(){
        $uid = get_uid();

        //获取返佣消息
        $map['uid']    = $uid;
        $map['type']   = array(array('eq', 2),array('eq', 3), 'or') ;
        $message_count = M('Message')->where($map)->count();
        $where['type'] = array(array('eq', 2),array('eq', 3), 'or') ;
        $message_data  = $this->income->get_message($uid,$where, 1, 3);

        foreach($message_data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'type'){
                    if($v == '2'){
                        $value['headimgurl'] = C('DOMAIN_NAME').'Uploads/Icon/return.png';
                        $value['nickname'] = '消费返利';
                    }
                }
            }
            $message_data[$key] = $value;
        }

        //修改饭圈消息为已读
        $data['status'] = '1';
        $data['type']   = array(array('eq', 2),array('eq', 3), 'or') ;
        $message_where['uid']    = $uid;
        $message_where['type']   = array(array('eq', 2),array('eq', 3), 'or') ;
        $message_where['status'] = 0;
        M('Message')->where($message_where)->save($data);

        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('_count', $message_count);
        $this->assign('message', $message_data);
        $this->display('Spokesman/app_message2');
    }

    /**
     * 设置显示页
     * @param cache 缓存大小
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_personal_setup(){
        $uid   = get_uid();
        $cache = I('get.cache');

        //用户基本信息
        $data = $this->user->get_user_message($uid);

        switch ($data['rank']) {
            case 0: $rank = 'vip_letter_s'; break;
            case 1: $rank = 'vip_letter_v'; break;
            case 2: $rank = 'vip_letter';   break;
            default:;
        }

        //获取未读消息数
        $message['status'] = 0;
        $message['uid']    = $uid;
        $message_count = M('Message')->where($message)->count();

        $this->assign('footer_represent','footer_bottom-Clicked');
        $this->assign('cache',$cache);
        $this->assign('message_count',$message_count);
        $this->assign('rank',$rank);
        $this->assign('user',$data);
        $this->display('Spokesman/app_personalSetup');
    }
}