<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\AdvertisementModel;
class ShopPlayController extends AdminController {
    protected $ad;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->ad = new AdvertisementModel();
    }

    /**
     * 商城的轮转页显示页 1
     * @author zhangxiang <zhxjmdc@gmail.com>]
     */
    public function index(){
        $data = $this->ad->alliance_shop_ad('1');
        //商家轮播图片地址
        $data = pic_replace($data, ['path']);

        $this->assign('_list',$data);
        $this->display('ShopPlay/index');
    }

    /**
     * 商城轮转修改 1
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_edit(){
        $params = I('post.');


        $ret = $this->ad->ad_edit(1,$params);
        if($ret === false){
            $this->error('异常错误');
        }

        if($_FILES['file']['error'] == 0){
            //加载图片配置
            $config = C('PIC_CONFIG');
            $config['savePath'] = '/Advertisement/';
            $upload = new \Think\Upload($config);// 实例化上传类

            $info   =   $upload->uploadOne($_FILES['file']);
            if(!$info) {
                // 上传错误提示错误信息
                $this->error($upload->getError());
            }else{
                //删除原图
                $pic_path = $this->ad->ad_pic_path($params['id']);
                $this->delete_file($pic_path);


                $path = $info['savepath'].$info['savename'];
                thumb_img('./Uploads'.$path,'./Uploads'.$info['savepath'].'s!'.$info['savename'],150);
                // 上传成功 获取上传文件信息
                $map['path'] = $path;
                M('Advertisement')->where('id = '.$params['id'])->save($map);
            }
        }
        $this->redirect("ShopPlay/index");
    }

    /**
     * 商城活动图标修改显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function activity_icon(){
        $data = M('Goods_type')->field('id,name,icon')->select();
        $data = pic_replace($data, ['icon']);

        $this->assign('_list', $data);
        $this->display("ShopIcon/index");
    }

    /**
     * 图标修改
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function icon_edit(){
        $params = I('post.');


        $ret = $this->ad->icon_edit($params);

        if($ret === false){
            $this->error('异常错误');
        }

        if($_FILES['file']['error'] == 0){
            //加载图片配置
            $config = C('PIC_CONFIG');
            $config['savePath'] = '/Icon/';
            $upload = new \Think\Upload($config);// 实例化上传类

            $info   =   $upload->uploadOne($_FILES['file']);
            if(!$info) {
                // 上传错误提示错误信息
                $this->error($upload->getError());
            }else{
                $path = $info['savepath'].$info['savename'];
                thumb_img('./Uploads'.$path,'./Uploads'.$info['savepath'].'s!'.$info['savename'],150);
                // 上传成功 获取上传文件信息
                $map['icon'] = $path;
                M('Goods_type')->where('id = '.$params['id'])->save($map);
            }
        }
        $this->redirect("ShopPlay/activity_icon");
    }
}