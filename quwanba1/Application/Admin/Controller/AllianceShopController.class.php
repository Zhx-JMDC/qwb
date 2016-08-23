<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\ShopModel;
class AllianceShopController extends AdminController {
    protected $shop;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->shop  = new ShopModel();
    }

    /**
     * 商家列表显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index() {
        $params = I('get.');
        $where = array();
        if($params['province_id']){
            $where['province_id'] = $params['province_id'];
        }
        if($params['city_id']){
            $where['city_id'] = $params['city_id'];
        }
        if($params['district_id']){
            $where['district_id'] = $params['district_id'];
        }
        if($_GET['shop_names'] != ''){
            $shop_names = $_GET['shop_names'];
            $encode = mb_detect_encoding($shop_names, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
            if($encode != 'UTF-8'){
                $shop_names =iconv('GB2312', 'UTF-8', $shop_names);
            }
            $where['shop_name'] = array('like','%'.$shop_names.'%');
            $this->assign('shop_names',$shop_names);
        }
        if($params['shop_type'] != ''){
            $where['shop_type'] = $params['shop_type'];
            $this->assign('shop_type',$params['shop_type']);
        }

        $data = $this->shop->get_shop($where);

        //地区获取
        $data['goods'] = $this->address_replace($data['goods']);

        //获取省
        $province = $this->get_province_all();

        if($data){
            $this->assign('_province',$province);
            $this->assign('_page',$data['show']);
            $this->assign('_list',$data['goods']);
            $this->display('AllianceShop/index');
        }else{
            $this->error("异常错误");
        }
    }

    /**
     * 商品详情显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_detail(){
        $id = I('get.id');
        $data = $this->shop->get_shop_detail($id);

        //地区组装
        $data['province'] = get_province($data['province_id']);
        $data['city']     = get_city($data['city_id']);
        $data['district'] = get_district($data['district_id']);

        //海报,折扣图片
        $data['pic']          = C('DOMAIN_NAME').'Uploads'.get_thumb($data['pic'], 's!');
        $data['card_pic']     = C('DOMAIN_NAME').'Uploads'.get_thumb($data['card_pic'], 's!');
        $data['discount_img'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['discount_img'], 's!');
        $data['shop_play']    = pic_replace($data['shop_play'], ['path']);

        //文本转义
        $data['introduce'] = htmlspecialchars_decode($data['introduce']);
        $data['detail']    = htmlspecialchars_decode($data['detail']);

        //查询商家分类标签
        $class_data = $this->shop->shop_has_class($id);

        if($data == false){
            $this->error("异常错误");
        }else{
            $this->assign('class',$class_data);
            $this->assign('shop',$data);
            $this->display('AllianceShop/details');
        }
    }

    /**
     * 商家信息修改显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_edit_page(){
        $id = I('get.id');
        $data = $this->shop->get_shop_detail($id);
        //获取当前区域
        $data['province'] = get_province($data['province_id']);
        $data['city']     = get_city($data['city_id']);
        $data['district'] = get_district($data['district_id']);
        //获取素有省份
        $province = $this->get_province_all();

        //海报,折扣图片地址拼接
        $data['card_pic'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['card_pic'], 's!');
        $data['pic'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['pic'], 's!');
        $data['discount_img'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['discount_img'], 's!');

        //商家轮播
        $data['shop_play'] = pic_replace($data['shop_play'], ['path']);

        //查询商家拥有分类标签
        $shop_class_data = $this->shop->shop_has_class($id);

        //查询商家分类标签
        $class_data = $this->shop->get_shop_class();

        if($data == false){
            $this->error("异常错误");
        }else{
            $shop_class_data = json_encode($shop_class_data);
            $this->assign('_shop_class',$shop_class_data);
            $this->assign('_class',$class_data);
            $this->assign('_province',$province);
            $this->assign('shop',$data);
            $this->display('AllianceShop/edit');
        }
    }

    /**
     * 商家信息修改操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_edit(){
        $params = I('post.');
        $this->form_check(array($params['shop_name'],$params['discount'],$params['contact']));

        //权重
        if(empty($params['shop_order'])){
            $params['shop_order'] = 50;
        }

        //轮播图删除
        if($params['img_delete'] == ''){
            unset($params['img_delete']);
        }else{
            $img_arr = explode(',', $params['img_delete']);
            //删除原图
            $img_path = $this->shop->shop_play_path($img_arr);
            $this->delete_file($img_path);

            $img_ret = $this->shop->shop_play_delete($img_arr);
            if($img_ret === false){
                $this->error("异常错误");
            }
            unset($params['img_delete']);
        }

        //商家分类标签修改,取差集删除或添加
        $shop_class_data = $this->shop->shop_has_class($params['id']);
        $class_data = array();
        foreach ($shop_class_data as $key => $value){
            array_push($class_data, $shop_class_data[$key]['id']);
        }
        foreach (array_diff($class_data, $params['type']) as $k => $v){
            $type_where['shop_id']       = $params['id'];
            $type_where['shop_class_id'] = $v;
            M('Shop_to_class')->where($type_where)->delete();
        }
        foreach (array_diff($params['type'], $class_data) as $k => $v){
            $type_data['shop_id']       = $params['id'];
            $type_data['shop_class_id'] = $v;
            M('Shop_to_class')->data($type_data)->add();
        }

        //更新商家轮播图权重
        if(!empty($params['edit_order'])){
            foreach ($params['edit_order'] as $k => $v){
                $play_where['id'] = $v['id'];
                $play_data['play_order'] = $v['play_order'];
                M('Shop_play')->where($play_where)->save($play_data);
            }
        }

        //更新商家基本信息
        $shop_ret = $this->shop->shop_edit($params);
        if($shop_ret === false){
            $this->error("异常错误");
        }

        //图片更新
        //加载图片配置
        $config = C('PIC_CONFIG');
        $config['savePath'] = '/Shop/';
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
                    if($key == 'discount_img'){
                        //删除原图片
                        $dis_path = $this->shop->shop_dis_path($params['id']);
                        $this->delete_file($dis_path);

                        //折扣图片
                        $map['discount_img'] = $path;
                        $where['id'] = $params['id'];
                        M('Shop')->where($where)->save($map);
                    }elseif($key == 'pic'){
                        //删除原图片
                        $pic_path = $this->shop->shop_pic_path($params['id']);
                        $this->delete_file($pic_path);

                        //海报
                        $map['pic'] = $path;
                        $where['id'] = $params['id'];
                        M('Shop')->where($where)->save($map);
                    }elseif($key == 'card_pic'){
                        //海报
                        $map['card_pic'] = $path;
                        $where['shop_id'] = $params['id'];
                        M('Shop_detail')->where($where)->save($map);
                    }else{
                        //轮播图片
                        $i = substr($key,13);
                        $map['path']       = $path;
                        $map['shop_id']    = $params['id'];
                        $map['play_order'] = $params['order'.$i];
                        M('Shop_play')->data($map)->add();
                    }
                }
            }
        }
        $this->redirect('AllianceShop/shop_edit_page',array('id'=>$params['id']));
    }

    /**
     * 商家添加显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_add_page(){
        //获取省份
        $province = $this->get_province_all();
        //获取商家分类
        $type_data = $this->shop->get_shop_type();

        $this->assign('_type',$type_data);
        $this->assign('_province',$province);
        $this->display('AllianceShop/add');
    }

    /**
     * 商家添加操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_add(){
        $params = I('post.');
        $this->form_check(array($params['name'],$params['discount'],$params['contact']));
        //权重
        if(empty($params['shop_order'])){
            $params['shop_order'] = 50;
        }

        //是否有产品售卖
        if($params['buy_url'] != ''){
            $params['can_buy'] = 1;
        }

        //商家添加操作
        $ret = $this->shop->shop_add($params);

        if($ret){
            //商家分类添加
            foreach ($params['type'] as $k=>$v){
                $type_data['shop_class_id'] = $v;
                $type_data['shop_id'] = $ret;
                M('Shop_to_class')->data($type_data)->add();
            }

            //加载图片配置
            $config = C('PIC_CONFIG');
            $config['savePath'] = '/Shop/';
            $upload = new \Think\Upload($config);// 实例化上传类

            // 图片上传
            foreach ($_FILES as $key => $value){
                $info   =   $upload->uploadOne($value);
                if(!$info) {
                    // 上传错误提示错误信息
                     $this->error($upload->getError());
                }else{
                    $path = $info['savepath'].$info['savename'];
                    thumb_img('./Uploads'.$path,'./Uploads'.$info['savepath'].'s!'.$info['savename'],150);
                    // 上传成功 获取上传文件信息
                    if($key == 'discount_img'){
                        //折扣图片
                        $map['discount_img'] = $path;
                        $where['id'] = $ret;
                        M('Shop')->where($where)->save($map);
                    }elseif($key == 'pic'){
                        //海报
                        $map['pic'] = $path;
                        $where['id'] = $ret;
                        M('Shop')->where($where)->save($map);
                    }elseif($key == 'card_pic'){
                        //折扣信息海报
                        $map['card_pic']  = $path;
                        $where['shop_id'] = $ret;
                        M('Shop_detail')->where($where)->save($map);
                    } else{//轮播图片
                        $map['path']    = $path;
                        $map['shop_id'] = $ret;
                        M('Shop_play')->data($map)->add();
                    }
                }
            }
        }else{
            $this->error("异常错误");
        }
        $this->redirect('AllianceShop/shop_add_page');
    }

    /**
     * 商家删除操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_del(){
        $id = I('post.id');

        //删除海报图片
        $pic_path = $this->shop->shop_pic_path($id);
        $this->delete_file($pic_path);

        //删除折扣图片
        $dis_path = $this->shop->shop_dis_path($id);
        $this->delete_file($dis_path);


        //删除商家基本信息
        $shop_ret = $this->shop->shop_del($id);
        if($shop_ret === false){
            $this->error("异常错误");
        }

        //删除轮播图
        $play_path = $this->shop->shop_play_path($id);
        $this->delete_file($play_path);

        M('Shop_play')->where('shop_id = '.$id)->delete();
        $this->redirect('AllianceShop/index');
    }

    /**
     * 联盟商家导出excel
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_export(){
        $shop_type = I('get.type');

        if(!empty($shop_type) && $shop_type == 0 || $shop_type == 1){
            $where['shop_type'] = $shop_type;
        }

        $shop_data = $this->shop->shop_export($where);

        //区域替换
        $shop_data = $this->address_replace($shop_data);

        $fileName = "趣玩联盟商家";
        $headArr  = array('商家ID','商户名称','区域','类型','商品名称');

        //数据排序
        foreach ($shop_data as $key => $value){
            foreach ($value as $k => $v){
                switch ($k){
                    case 'id':            $data[$key]['0'] = $v; break;
                    case 'shop_name':     $data[$key]['1'] = $v; break;
                    case 'province':      $data[$key]['2'] = $v; break;
                    case 'city':          $data[$key]['2'] .= $v; break;
                    case 'district':      $data[$key]['2'] .= $v; break;
                    case 'shop_type':
                        if($v == '0'){
                            $data[$key]['3'] = "热门商家";
                        }else{
                            $data[$key]['3'] = "最新商家";
                        }
                        break;
                    case 'goods':
                        foreach ($v as $ke => $va){
                            $data[$key]['4'] .= $va['goods_name'].'、';
                        }
                }
            }
            ksort($data[$key]);
        }
        ob_end_clean();

        $this->getExcel($fileName,$headArr,$data);
    }
}