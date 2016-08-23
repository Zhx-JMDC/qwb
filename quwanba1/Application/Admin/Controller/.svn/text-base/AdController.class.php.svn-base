<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 16/7/19
 * Time: 下午4:16
 */

namespace Admin\Controller;


use Admin\Model\AdvertisementModel;

class AdController extends AdminController{
    protected $ad;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct(){
        parent::__construct();
        $this->ad = new AdvertisementModel();
    }


    /**
     * 广告列表显示
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index()
    {
        $data = $this->ad->ads_gets();

        if($data){
            $this->assign('_page',$data['show']);
            $this->assign('_list',$data['ads']);
            $this->display('ShopAd/index');

        }else{
            $this->error("异常错误");
        }
    }

    /**
     * 商家分类显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_add_page(){
        $this->display('ShopAd/add');
    }

    /**
     * 广告添加页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_add(){
        $params = I('post.');

        //权重是否为数字
        if(is_numeric($params['order'])){
            $params['order'] = '50';
        }

        //广告基本信息添加操作
        $ret = $this->ad->ad_add($params);

        if($ret){
            //商家分类添加
            //加载图片配置
            $config = C('PIC_CONFIG');
            $config['savePath'] = '/Advertisement/';
            $upload = new \Think\Upload($config);// 实例化上传类

            // 图片上传
            $info = $upload->uploadOne($_FILES['path']);
            if(!$info) {
                // 上传错误提示错误信息
                $this->error($upload->getError());
            }else {
                $path = $info['savepath'] . $info['savename'];
                thumb_img('./Uploads'.$path,'./Uploads'.$info['savepath'].'s!'.$info['savename'],150);
                // 上传成功 获取上传文件信息
                $map['path'] = $path;
                $where['id'] = $ret;
                M('Advertisement')->where($where)->save($map);
            }
        }else{
            $this->error("异常错误");
        }
        $this->redirect('Ad/ad_add_page');
    }

    /**
     * 广告详情
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_detail(){
        $id = I('get.id');
        //获取广告基本信息
        $data = $this->ad->ad_detail($id);
        //广告图片
        $data['path'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['path'], 's!');

        if($data == false){
            $this->error("异常错误");
        }else{
            $this->assign('ad',$data);
            $this->display('ShopAd/details');
        }
    }

    /**
     * 广告修改显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_edit_page(){
        $id = I('get.id');
        $data = $this->ad->ad_detail($id);
        //广告图片
        $data['path'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['path'], 's!');

        if($data == false){
            $this->error("异常错误");
        }else{
            $this->assign('ad',$data);
            $this->display('ShopAd/edit');
        }
    }

    /**
     * 广告修改操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_edit(){
        $params = I('post.');
        //更新广告基本信息
        $ret = $this->ad->ad_edit($params['positions'],$params);
        if($ret === false){
            $this->error("异常错误");
        }

        //图片更新
        //加载图片配置
        $config = C('PIC_CONFIG');
        $config['savePath'] = '/Advertisement/';
        $upload = new \Think\Upload($config);// 实例化上传类

        // 图片上传
        foreach ($_FILES as $key => $value){
            if($value['error'] == 0){
                $info   =   $upload->uploadOne($value);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    $path = $info['savepath'].$info['savename'];
                    thumb_img('./Uploads'.$path,'./Uploads'.$info['savepath'].'s!'.$info['savename'],150);
                    // 上传成功 获取上传文件信息
                    if($key == 'pic'){
                        //删除原图片
                        $pic_path = $this->ad->ad_pic_path($params['id']);
                        $this->delete_file($pic_path);

                        //广告图片
                        $map['path'] = $path;
                        $where['id'] = $params['id'];
                        M('Advertisement')->where($where)->save($map);

                    }
                }
            }
        }
        $this->redirect('Ad/ad_edit_page',array('id'=>$params['id']));
    }

    /**
     * 广告删除操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_del(){
        $id = I('post.id');
        
        //删除图片
        $path = $this->ad->ad_pic_path($id);
        $this->delete_file($path);

        //删除广告基本信息
        $ret = $this->ad->ad_del($id);
        if($ret === false){
            $this->error("异常错误");
        }

        $this->redirect('Ad/index');
    }
}