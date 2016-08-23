<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Model;
use Wechat\Model\UserModel;

class IndexController extends CommonController {
    protected $user;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->user = new UserModel();
    }

    /**
     * 微信接入操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index(){
        $wechat = new\Org\Wechat\wechatCallbackapiTest();
        if($_GET['echostr']){
            /*存在，接入操作*/
            $wechat->valid();
        }else{
            /*不存在，接入后操作*/
            $this->response_msg();
        }
    }

    // 自定义接入后操作
    public function response_msg(){
        //创建自定义菜单
        echo $this->create_menu();

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        /*extract post data*/
        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            $fromUsername = $postObj->FromUserName;  /*开发者微信号*/
            $toUsername   = $postObj->ToUserName;    /*发送方帐号（一个OpenID）*/
            $keyword      = trim($postObj->Content); /*用户关键字*/
            $time         = time();                  /*系统时间*/
            $event        = $postObj->Event;         /*获取事件类型*/
            $eventkey     = $postObj->EventKey;      /*获取事件关键字，对应报文key数据*/
            $msgType      = $postObj->MsgType;       /*用户发送的消息类型*/

            $textTpl = "<xml>
	                <ToUserName><![CDATA[%s]]></ToUserName>
	                <FromUserName><![CDATA[%s]]></FromUserName>
	                <CreateTime>%s</CreateTime>
	                <MsgType><![CDATA[%s]]></MsgType>
	                <Content><![CDATA[%s]]></Content>
	                <FuncFlag>0</FuncFlag>
	                </xml>";


            //获取关注者信息并存入数据库
            $user_data = $this->get_user_message($fromUsername);
            if($user_data['unionid'] != null){
                $this->user->add_user_message($user_data);
            }

            //生成二维码
            $ret = $this->user->exit_QRcode($fromUsername);
            if($ret){
                $path = $this->get_QRcode($ret);
                $this->user->update_QRcode($ret, $path);
            }

            //获取自动回复文本消息
            $response_data = M('Response')->field('content')->where('id = 1')->find();


            //扫描带参数二维码，公众号做出响应(分为关注和未关注两种情况)
            if($event == 'subscribe' && substr($eventkey, 0,8) == 'qrscene_'){
                //未关注扫描，关注后触发本事件,添加用户发展为下线
                $this->user->add_user(substr($eventkey,8), $fromUsername);
                $msgType    = "text";
                $contentStr = strip_tags(htmlspecialchars_decode($response_data['content']));

                //$contentStr = $toUsername."您之前未关注过本账号".$fromUsername."，并且扫描了带参数的二维码".substr($eventkey,8);
                $resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }elseif($event == 'SCAN'){
                $this->user->add_user($eventkey, $fromUsername);
                //已经关注公众号,若无上线(属于一代),更新为下线
                $msgType    = "text";
                $contentStr = strip_tags(htmlspecialchars_decode($response_data['content']));

                //$contentStr = $toUsername."您之前未关注过本账号".$fromUsername."，并且扫描了带参数的二维码".$eventkey;
                $resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }elseif($event == 'subscribe'){
                $msgType    = "text";
                $contentStr = strip_tags(htmlspecialchars_decode($response_data['content']));
                $resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }elseif(!empty($keyword)){
                $msgType    = "text";
                $contentStr = "如果您发送的关键词没有回复，请联系小编（qwb8686）进行修改，我们的进步，离不开您的支持，谢谢!";
                $resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
        }
    }

    /**
     * 创建自定义菜单
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function create_menu(){
        $access_token  = $this->get_token();
        $data = '{
		    "button": [
		        {
		            "type": "view",
		            "name": "趣玩商城",
		            "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('APPID').'&redirect_uri='.C('DOMAIN_NAME').'index.php/Wechat/Index/shop_accredit&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect"
		        }, 
                {
                    "name": "周游卡", 
                    "sub_button": [
                        {
                            "type": "view", 
                            "name": "联盟商家",
                            "url" : "https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('APPID').'&redirect_uri='.C('DOMAIN_NAME').'index.php/Wechat/Index/alliance_shop_accredit&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect"
                         }, 
                        {
                            "type": "view", 
                            "name": "购买周游卡", 
                            "url" : "https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('APPID').'&redirect_uri='.C('DOMAIN_NAME').'index.php/Wechat/Index/buy_card_accredit&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect"
                        }
                    ]
                },
		        {
                    "name": "客服中心", 
                    "sub_button": [
                        {
                            "type": "view", 
                            "name": "我是旅行家",
                            "url" : "https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('APPID').'&redirect_uri='.C('DOMAIN_NAME').'index.php/Wechat/Index/spokesman_accredit&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect"
                         }, 
                        {
                            "type": "view", 
                            "name": "我的订单", 
                            "url" : "https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('APPID').'&redirect_uri='.C('DOMAIN_NAME').'index.php/Wechat/Index/order_accredit&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect"
                        }, 
                        {
                            "type": "view", 
                            "name": "我的收藏", 
                            "url" : "https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('APPID').'&redirect_uri='.C('DOMAIN_NAME').'index.php/Wechat/Index/collect_accredit&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect"
                        }, 
                        {
                            "type": "view", 
                            "name": "下载APP",
                            "url" : "https://open.weixin.qq.com/connect/oauth2/authorize?appid='.C('APPID').'&redirect_uri='.C('DOMAIN_NAME').'index.php/Wechat/Index/app_download_accredit&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect"
                        }
                    ]
                }
		    ]
		}';

        $url     = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        $tmpInfo = request($url, $data, 'POST');
        return $tmpInfo;
    }

    /**
     * 静默授权,进入商城首页
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_accredit(){
        $this->get_accredit();
        $this->redirect('Shop/shop_mall');
    }

    /**
     * 静默授权,进入联盟商家
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function alliance_shop_accredit(){
        $this->get_accredit();
        $this->redirect('AllianceShop/shop_index_hot');
    }

    /**
     * 静默授权,进入我是旅行家
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function spokesman_accredit(){
        $this->get_accredit();
        $this->redirect('Spokesman/represent_v');
    }

    /**
     * 静默授权,进入我的订单
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function order_accredit(){
        $this->get_accredit();
        $this->redirect('Order/my_order');
    }

    /**
     * 静默授权,进入我的收藏
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function collect_accredit(){
        $this->get_accredit();
        $this->redirect('Collect/my_collect_goods');
    }

    /**
     * 静默授权,周游卡显示页
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function buy_card_accredit(){
        $this->get_accredit();
        $this->redirect('Spokesman/buy_card');
    }

    /**
     * 静默授权,进入趣玩社
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_download_accredit(){
        $this->get_accredit();
        $this->redirect('Spokesman/app_download');
    }

    /**
     * 网页授权获取openid(手动授权),进入商城首页
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function page_accredits(){
        $code    = $_GET['code'];
        $appid   = C('APPID');
        $secret  = C('SECRET');
        $url     = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $data    = get_curl($url);
        $message = json_decode($data,true);
        $openid  = $message['openid'];

        //获取关注者信息并存入数据库
        $user_data = $this->get_user_message($openid);
        if($user_data['unionid'] != null){
            $uid = $this->user->add_user_message($user_data);
        }

        //生成二维码
        $ret = $this->user->exit_QRcode($openid);
        if($ret){
            $path = $this->get_QRcode($ret);
            $this->user->update_QRcode($ret, $path);
        }

        cookie('uid',encrypt($uid, 'E', C('ENCYRPT')));
        session('uid',encrypt($uid, 'E', C('ENCYRPT')));
        session('city',null);
        $this->redirect('Shop/shop_mall');
    }

    /**
     * 网页授权获取openid(手动授权)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_accredit(){
        $code    = $_GET['code'];
        $appid   = C('APPID');
        $secret  = C('SECRET');
        $url     = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $data    = get_curl($url);

        $message = json_decode($data,true);
        $openid  = $message['openid'];
        $uid     = $this->user->get_uid($openid);
        cookie('uid',encrypt($uid, 'E', C('ENCYRPT')));
        session('uid',encrypt($uid, 'E', C('ENCYRPT')));
        session('city',null);
    }
}