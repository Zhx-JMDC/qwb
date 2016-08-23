<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\ShopModel;
class ShopClassController extends AdminController {
    protected $shop;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->shop = new ShopModel();
    }

    /**
     * 热门商家列表显示页
     * @param shop_type 0热门商家
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index(){
        $data = $this->shop->get_shop_class();

        //分类图标组装
        foreach ($data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'icon'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.get_thumb($v, 's!');
                }elseif($k == 'id'){
                    $value['count'] = $this->shop->class_shop_count($v);
                }
            }
            $data[$key] = $value;
        }
        
        $this->assign('_list',$data);
        $this->display('ShopClass/index');
    }

    /**
     * 商家分类详情
     * @param id 分类id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_class_detail(){
        $id = I('get.id');
        //获取分类基本信息
        $data = $this->shop->shop_class_detail($id);
        if($data === false){
            $this->error("异常错误");
        }else{
            //获取指定分类所有商家
            $shop_data =$this->shop->get_class_all($id);
        }

        $this->assign('_class',$data);
        $this->assign('_shop',$shop_data);
        $this->display('ShopClass/details');
    }

    /**
     * 商家分类显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function add_shop_class(){
        $this->display('ShopClass/add');
    }
    

    /**
     * 商家分类添加操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function add_class(){
        //添加分基本信息
        $name = I('post.name');
        $map['name'] = $name;
        $ret = M('Shop_class')->data($map)->add();

        if($ret === false){
            $this->error("异常错误");
        }elseif($_FILES['icon']['error'] == 0){
            //加载图片配置
            $config = C('PIC_CONFIG');
            $config['savePath'] = '/Icon/';
            $upload = new \Think\Upload($config);// 实例化上传类

            // 图片上传
            $info   =   $upload->uploadOne($_FILES['icon']);
            if(!$info) {
                // 上传错误提示错误信息
                $this->error($upload->getError());
            }else{
                $path = $info['savepath'].$info['savename'];
                thumb_img('./Uploads'.$path,'./Uploads'.$info['savepath'].'s!'.$info['savename'],150);
                // 上传成功 获取上传文件信息
                $data['icon'] = $path;
                M('Shop_class')->where('id = '.$ret)->save($data);
            }
        }

        $this->redirect('ShopClass/add_shop_class');
    }

    /**
     * 分类修改显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function edit_shop_class_page(){
        $id = I('get.id');
        $data = $this->shop->shop_class_detail($id);
        $data['icon'] = C('DOMAIN_NAME').'Uploads'.get_thumb('icon', 's!');
        $this->assign('_class',$data);
        $this->display('ShopClass/edit');
    }

    /**
     * 分类修改操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function edit_shop_class(){
        $params = I('post.');
        //更新分类基本信息
        $where['id'] = $params['id'];
        $data['name'] = $params['name'];
        $ret = M('Shop_class')->where($where)->save($data);
        if($ret === false){
            $this->error("异常错误");
        }

        //图片更新
        //加载图片配置
        $config = C('PIC_CONFIG');
        $config['savePath'] = '/Icon/';
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
                        $pic_path = $this->shop->shop_pic_path($params['id']);
                        $this->delete_file($pic_path);

                        //广告图片
                        $map['icon'] = $path;
                        $where['id'] = $params['id'];
                        M('Shop_class')->where('id = '.$params['id'])->save($map);

                    }
                }
            }
        }
        $this->redirect('ShopClass/edit_shop_class_page',array('id'=>$params['id']));
    }

    /**
     * 商家分类删除操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_class_del(){
        $id = I('post.id');
        $map['id'] = $id;
        $ret = M('Shop_class')->where($map)->delete();
        if($ret === false){
            $this->error("异常错误");
        }else{
            $this->redirect("ShopClass/index");
        }
    }
}