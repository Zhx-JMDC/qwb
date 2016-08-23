<?php
namespace Wechat\Controller;
use Think\Controller;
use Wechat\Model\UserModel;
class CommonController extends Controller {
    protected $user;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->user = new UserModel();
    }

    /**
     * 初始化控制器
     */
    protected function _initialize(){
        //检测unionid是否存在
        $unionid  = I('get.unionid');
        $nickname = I('get.nickname');
        if(!empty($unionid)){
            //通过unionid查询用户信息--手机端登录
            $headimgurl = I('get.headimgurl');

            $user = M('User');
            $map['unionid'] = $unionid;
            $user_ret = $user->field('id')->where($map)->find();

            if($user_ret === false){
                $this->error("异常错误");
            }elseif($user_ret){
                //用户存在获取uid
                $data['login_time'] = date('Y-m-d H:i:s',time());
                $where['unionid'] = (string)$unionid;
                $user->where($where)->save($data);
                $ret_uid = $user_ret['id'];

            }else{
                $user->startTrans();

                //创建用户获取uid
                $data['headimgurl'] = $headimgurl;
                $data['unionid']    = (string)$unionid;
                $data['nickname']   = $nickname;
                $data['login_time'] = date('Y-m-d H:i:s',time());
                $ret = $user->data($data)->add();

                //创建账户
                $account_data['uid'] = $ret;
                $account_ret = M('Account')->add($account_data);

                //创建唯一ID
                $data['qwb_id']  = 8008000+$ret;
                $where['unionid'] = (string)$unionid;
                $user->where($where)->save($data);

                if($ret !== false && $account_ret !== false){
                    $user->commit();
                    $ret_uid = $ret;
                }else {
                    $user->rollback();
                    $this->error('异常错误');
                }
            }

            //创建缓存
            cookie('uid',encrypt($ret_uid, 'E', C('ENCYRPT')));
            session('uid',encrypt($ret_uid, 'E', C('ENCYRPT')));
            session('city',null);
        }else{
            //检测当前用户是否存在--微信端登录
            $uid = get_uid();
            if(!$uid){
                //均不存在,授权获取
                $url = 'http://open.weixin.qq.com/connect/oauth2/authorize?appid=wxebafdf0a98c558b5&redirect_uri=http://www.qwb.2015tt.net/index.php/Wechat/Index/page_accredits&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
                header('location:'.$url);
            }
        }
    }

    /**
     *通过数据库获取微信的accessToken
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_token(){
        $where['past_time'] = array('EGT',time());
        $where['name'] = 'access_token';
        $access_token = M('system')->field('value')->where($where)->find();

        if($access_token){
            //未过期,数据库获取
            return $access_token['value'];

        }else{
            //已过期,微信重新获取并更新数据库
            $data['value'] = $this->get_access_token();
            $data['past_time'] = time()+6500;
            $map['name'] = 'access_token';
            M('system')->where($map)->save($data);
            return $data['value'];
        }
    }

    /**
     * 通过微信获取微信的accessToken,需要上传到服务器，本地可能不成功
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_access_token(){
        /*请求URL地址*/
        $appid  = C('APPID');
        $secret = C('SECRET');
        $url    = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;

        $ch = curl_init();                              /*初始化*/
        /*针对https抓取*/
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);/*跳过证书检查*/
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); /*从证书中检查SSL加密算法是否存在*/
        curl_setopt($ch, CURLOPT_URL, $url);            /*设置提交的页面*/
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    /*返回抓取的内容*/
        $data = curl_exec($ch);
        if(curl_errno($ch)){
            var_dump(curl_error($ch));
        }
        curl_close($ch);

        $arr = json_decode($data,true);
        return $arr['access_token'];
    }

    /**
     * 用户管理，通过用户openid获取用户基本信息
     * @param  $openid  用户openid
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_user_message($openid){
        $access_token = $this->get_token();

        $lang   = 'zh_CN';                        /*zh_CN 简体，zh_TW 繁体，en 英语*/
        $url    = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang='.$lang;
        $data   = get_curl($url);

        $user_message = json_decode($data,true);
        unset($user_message['tagid_list']);
        return $user_message;
    }

    /**
     * 生成带参数的二维码
     * @param $id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_QRcode($id){
        $access_token  = $this->get_token();

        /*输入scene_id的值,目前参数只支持1--100000相当于100000种*/
        $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$id.'}}}';
        $url  = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
        $qr_code = request($url,$data,'POST');
        $qr_code = json_decode($qr_code,true);

        /*利用票据，获取二维码,并将参数二维码下载到本地*/
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qr_code['ticket'];
        $imageInfo = $this->download_QRcode($url);   //获取参数二维码

        $path = './Uploads/'.date('Y-m-d');
        if(!file_exists($path)) {
            mkdir($path);
        }
        $filename = $path.'/'.md5($id).'.jpg'; //二维码存储路径
        $local_file = fopen($filename, 'w');
        if (false !== $local_file){
            if (false !== fwrite($local_file, $imageInfo["body"])) {
                fclose($local_file);
            }
        }
        return substr($filename,1);
    }

    /**
     * 将参数二维码下载到本地
     * @param $url  二维码请求地址
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function download_QRcode($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);    /*只取body头*/
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        return array_merge(array('body' => $package), array('header' => $httpinfo));
    }

    /**
     * 通过数据库获取jsapi_ticket
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_ticket(){
        $where['past_time'] = array('EGT',time());
        $where['name'] = 'jsapi_ticket';
        $jsapi_ticket = M('system')->field('value')->where($where)->find();

        if($jsapi_ticket){
            //未过期,数据库获取
            return $jsapi_ticket['value'];

        }else{
            //已过期,微信重新获取并更新数据库
            $data['value']     = $this->get_jsapi_ticket();
            $data['past_time'] = time()+6500;
            $map['name'] = 'jsapi_ticket';
            M('system')->where($map)->save($data);
            return $data['value'];
        }
    }

    /**
     * JSSDK签名生成jsapi_ticket
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_jsapi_ticket(){
        $access_token = $this->get_token();
        $url    = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';

        $data   = get_curl($url);
        $arr    = json_decode($data, true);
        return $arr['ticket'];
    }

    /**
     * 获取签名字符串
     * @return array
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_sign_package() {
        $jsapiTicket = $this->get_ticket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->create_nonce_str();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => C('APPID'),
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    /**
     * 生成微信支付订单号
     * @param int $length
     * @return string
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function create_nonce_str($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 发送模板消息
     * @param $openid 商家绑定的openid
     * @param $data       消息数据
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function order_template_message($openid, $data){
        $data = '{
			"touser":"'.$openid.'",
			"template_id":"OfDOHtYBBv33CdGjXkCNhyUiC9tKb2a2FQC8Bm_MPDk",
			"topcolor":"#FF0000",
			"data":{
				"first": {
					"value":"您的商品被预定成功:",
					"color":"#000"
				},
				"keyword1":{
					"value":"'.$data['order_num'].'",
					"color":"#000"
				},
				"keyword2":{
					"value":"'.$data['name'].'",
					"color":"#000"
				},
				"keyword3":{
					"value":"'.$data['experience_time'].'",
					"color":"#000"
				},
				"keyword4":{
					"value":"'.$data['price'].'",
					"color":"#000"
				},
				"remark":{
					"value":"用户使用时,请扫描订单二维码收款",
					"color":"#000"
				}
			}
		}';

        $access_token = $this->get_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;
    }

    /**
     * 发送模板消息(用户确认消费链接)
     * @param $openid 商家绑定的openid
     * @param $data       消息数据
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function template_message_url($openid, $data){
        $data = '{
			"touser":"'.$openid.'",
			"template_id":"aHbLT74MuFE9otP9J2pAqxduNgGZnoHawIOKJrRcNuE",
			"url":"'.$data['path'].'",
			"topcolor":"#FF0000",
			"data":{
				"first": {
					"value":"趣玩吧旅行消费通知:",
					"color":"#000"
				},
				"DateTime":{
					"value":"'.$data['time'].'",
					"color":"#000"
				},
				"PayAmount":{
					"value":"'.$data['price'].'",
					"color":"#000"
				},
				"Location":{
					"value":"'.$data['name'].'",
					"color":"#000"
				},
				"remark":{
					"value":"点击详情确认消费链接",
					"color":"#000"
				}
			}
		}';

        $access_token = $this->get_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;
    }
}