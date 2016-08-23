<?php
namespace Wechat\Controller;
use Think\Controller;
use Wechat\Model\CollectModel;
class CollectController extends CommonController {
    protected $collect;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->collect = new CollectModel();
    }

    /**
     * 我的商城收藏显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function my_collect_goods(){
        $uid = get_uid();
        $data = $this->collect->get_collect_goods($uid);

        foreach ($data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'rebate'){
                    $value[$k] = sprintf("%.2f",$v*$value['ratio']/100);
                }elseif($k == 'pic'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $data[$key] = $value;
        }

        if($data === false){
            $this->error('异常错误');
        }else{
            $this->assign('footer_collect','footer_bottom-Clicked');
            $this->assign('collect',$data);
            $this->display('Collect/myCollectGoods');
        }
    }

    /**
     * 我的联盟商家收藏显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function my_collect_shop(){
        $uid  = get_uid();
        $data = $this->collect->get_collect_shop($uid);

        foreach ($data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'pic'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }elseif($k == 'discount_img'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $data[$key] = $value;
        }

        if($data === false){
            $this->error('异常错误');
        }else{
            $this->assign('footer_collect','footer_bottom-Clicked');
            $this->assign('collect',$data);
            $this->display('Collect/myCollectShop');
        }
    }

    /**
     * 用户收藏操作
     * @param uid  用户uid
     * @param id   收藏商品id
     * @param type 商品类型（0商品，1商家）
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function collect(){
        $uid  = get_uid();
        $id   = I('post.id');
        $type = I('post.type');

        $ret = $this->collect->collect($uid, $id, $type);
        if($ret === false){
            $this->error('异常错误');
        }else{
            $this->ajaxReturn($ret);
        }
    }

    /**
     * 用户删除收藏操作,(这里更改状态)
     * @param uid  用户uid
     * @param id   收藏商品id
     * @param type 商品类型（0商品，1商家）
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function collect_del(){
        $uid = get_uid();
        $collect_id  = I('post.id');
        $type = I('post.type');

        $ret = $this->collect->collect_del($uid, $collect_id, $type);
        if($ret === false){
            $this->error('异常错误');
        }elseif($ret){
            $this->ajaxReturn('3');
        }else{
            $this->ajaxReturn('0');
        }
    }

    /*********************************************************************************
     * 旅行家个人中心
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_traveller(){
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
            $nickname = '温州趣玩吧旅游';
        }else{
            $fid_map['id'] = $data['fid'];
            $fid_data = M('User')->field('nickname')->where($fid_map)->find();
            $nickname = $fid_data['nickname'];
        }

        //获取未读消息数
        $message['status'] = 0;
        $message['uid']    = $uid;
        $message_count = M('Message')->where($message)->count();

        $this->assign('footer_personal','footer_bottom-Clicked');
        $this->assign('message_count',$message_count);
        $this->assign('nickname',$nickname);
        $this->assign('rank',$rank);
        $this->assign('user',$data);

        $this->display('Spokesman/app_representCenter');
    }

    /**
     * 我的商城收藏显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_my_collect_goods(){
        $uid = get_uid();
        $data = $this->collect->get_collect_goods($uid);

        foreach ($data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'rebate'){
                    $value[$k] = sprintf("%.2f",$v*$value['ratio']/100);
                }elseif($k == 'pic'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $data[$key] = $value;
        }

        if($data === false){
            $this->error('异常错误');
        }else{
            $this->assign('footer_personal','footer_bottom-Clicked');
            $this->assign('collect',$data);
            $this->display('Collect/app_myCollectGoods');
        }
    }

    /**
     * 我的联盟商家收藏显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_my_collect_shop(){
        $uid  = get_uid();
        $data = $this->collect->get_collect_shop($uid);

        foreach ($data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'pic'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }elseif($k == 'discount_img'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $data[$key] = $value;
        }

        if($data === false){
            $this->error('异常错误');
        }else{
            $this->assign('footer_personal','footer_bottom-Clicked');
            $this->assign('collect',$data);
            $this->display('Collect/app_myCollectShop');
        }
    }
}