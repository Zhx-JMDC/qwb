<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\GoodsModel;
use Admin\Model\OrderModel;

class ShopController extends AdminController{
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
     * 商品列表显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index() {
        $params = I('get.');
        $where = array();

        if($params['province_id'] != ''){
            $where['province_id'] = $params['province_id'];
        }
        if($params['city_id'] != ''){
            $where['city_id'] = $params['city_id'];
        }
        if($params['district_id'] != ''){
            $where['district_id'] = $params['district_id'];
        }
        if($params['name'] != ''){
            $where['qwb_goods.name'] = array('like','%'.$params['name'].'%');
            $this->assign('name',$params['name']);
        }
        if($params['shop_name'] != ''){
            $where['shop_name'] = array('like','%'.$params['shop_name'].'%');
            $this->assign('shop_name',$params['shop_name']);
        }
        if($params['price'] != ''){
            if($params['price'] == 1){
                //降序
                $order = 'selling_price desc';
            }elseif($params['price'] == 0){
                //升序
                $order = 'selling_price asc';
            }
            $this->assign('price',$params['price']);
        }

        $data = $this->goods->get_goods($where,$order);

        //获取省
        $province = $this->get_province_all();
        //区域替换
        $data['goods'] = $this->address_replace($data['goods']);

        //获取完成订单数,退订订单数
        foreach ($data['goods'] as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'id'){
                    $value['complete'] = $this->order->get_order_status($v, '1');
                    $value['countermand'] = $this->order->get_order_status($v, '3');
                }
            }
            $data['goods'][$key] = $value;
        }
        
        if($data){
            $this->assign('_province',$province);
            $this->assign('_page',$data['show']);
            $this->assign('_list',$data['goods']);
            $this->display('Shop/index');
        }else{
            $this->error("异常错误");
        }
    }

    /**
     * 商品详情显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_detail(){
        $id = I('get.id');
        $data = $this->goods->get_goods_detail($id);
        //海报图片
        $data['pic'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['pic'], 's!');
        $data['goods_play'] = pic_replace($data['goods_play'],['path']);

        //区域获取
        $data['province'] = get_province($data['province_id']);
        $data['city']     = get_city($data['city_id']);
        $data['district'] = get_district($data['district_id']);
        //文本转义
        $data['introduce'] = htmlspecialchars_decode($data['introduce']);
        $data['content']   = htmlspecialchars_decode($data['content']);

        if($data == false){
            $this->error("异常错误");
        }else{
            $this->assign('back',0);
            $this->assign('goods',$data);
            $this->display('Shop/details');
        }
    }

    /**
     * 商品添加显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_add_page(){
        //获取省份
        $province  = $this->get_province_all();
        //获取活动类型
        $type_data = M('goods_type')->field('id,name')->select();
        if($type_data === false) {
            $this->error("异常错误");
        }

        $this->assign('_type',$type_data);
        $this->assign('_province',$province);
        $this->display('Shop/add');
    }

    //选择商家弹出框
    public function specification(){
        $this->display('SpecificationManager');
    }

    //查找商家
    public function search_shop(){
        $id = I('post.id');
        $map['id'] = $id;
        $data = M('Shop')->field('id,shop_name,conditions,province_id,city_id,district_id')->where($map)->find();

        //区域获取
        $data['province'] = get_province($data['province_id']);
        $data['city']     = get_city($data['city_id']);
        $data['district'] = get_district($data['district_id']);
        //审核状态
        if($data['conditions'] == 1){
            $data['conditions'] = "已审核";
        }else{
            $data['conditions'] = "未审核";
        }

        if($data === false) {
            $this->error("异常错误");
        }else{
            $this->ajaxReturn($data);
        }
    }

    /**
     * 商品添加页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_add(){
        $params = I('post.');
        //数据检查
        $this->form_check(array($params['shop_name'],$params['rebate'],$params['selling_price'],$params['original_price']));
        $params['original_price'] = trim($params['original_price']);
        $params['selling_price']  = trim($params['selling_price']);
        $params['rebate']         = trim($params['rebate']);
        $params['order']          = trim($params['order']);
        $params['ratio']          = trim($params['ratio']);
        $params['longitude']          = trim($params['order']);
        $params['latitude']          = trim($params['ratio']);
        if(!is_numeric($params['rebate'])){
            $this->error("异常错误");
        }
        if(!is_numeric($params['selling_price'])){
            $this->error("异常错误");
        }
        if(!is_numeric($params['original_price'])){
            $this->error("异常错误");
        }
        if(is_numeric($params['order'])){
            $params['order'] = '50';
        }

        //商家基本信息添加操作
        $ret = $this->goods->goods_add($params);

        if($ret){
            //添加规格
            if(!empty($params['price'])){
                foreach ($params['price'] as $k => $v){
                    $v['goods_id'] = $ret;
                    M('Goods_price')->data($v)->add();
                }
            }

            //图片信息处理,加载图片配置
            $config = C('PIC_CONFIG');
            $config['savePath'] = '/Goods/';
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
                    if($key == 'pic'){
                        //海报
                        $map['pic'] = $path;
                        $where['id'] = $ret;
                        M('Goods')->where($where)->save($map);
                    }else{//轮播图片
                        $i = substr($key,13);
                        $map['path']       = $path;
                        $map['goods_id']   = $ret;
                        $map['play_order'] = $params['order'.$i];
                        M('Goods_play')->data($map)->add();
                    }
                }
            }
        }else{
            $this->error("异常错误");
        }
        $this->redirect('Shop/goods_add_page');
    }

    /**
     * 商品修改显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_edit_page(){
        $id = I('get.id');
        $data = $this->goods->get_goods_detail($id);
        //海报图片
        $data['pic'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['pic'], 's!');
        //商品轮播图
        $data['goods_play'] = pic_replace($data['goods_play'], ['path']);

        //获取活动类型
        $type_data = M('goods_type')->field('id,name')->select();
        if($type_data === false) {
            $this->error("异常错误");
        }

        $this->assign('_type',$type_data);
        $this->assign('goods',$data);
        $this->display('Shop/edit');
    }

    /**
     * 商品修改操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_edit(){
        $params = I('post.');
        $this->form_check(array($params['shop_name'],$params['rebate'],$params['selling_price'],$params['original_price']));
        $params['original_price'] = trim($params['original_price']);
        $params['selling_price']  = trim($params['selling_price']);
        $params['rebate']         = trim($params['rebate']);
        $params['order']          = trim($params['order']);
        $params['ratio']          = trim($params['ratio']);

        if(!is_numeric($params['order'])){
            $params['order'] = 50;
        }
        if(!is_numeric($params['original_price']) || !is_numeric($params['selling_price']) || !is_numeric($params['rebate'])){
            $this->error("价格必须为数字");
        }

        //添加规格
        if(!empty($params['price'])){
            foreach ($params['price'] as $k => $v){
                $v['goods_id'] = $params['id'];
                M('Goods_price')->data($v)->add();
            }
        }

        //修改规格
        if(!empty($params['edit_price'])){
            foreach ($params['edit_price'] as $k => $v){
                M('Goods_price')->data($v)->save();
            }
        }

        //修改轮播图权重
        if(!empty($params['edit_order'])){
            foreach ($params['edit_order'] as $k => $v){
                $play_where['id']       = $v['id'];
                $play_map['play_order'] = $v['play_order'];
                M('goods_play')->where($play_where)->save($play_map);
            }
        }

        //轮播图删除
        if($params['img_delete'] == ''){
            unset($params['img_delete']);
        }else{
            $img_arr = explode(',', $params['img_delete']);
            //删除原图
//            $img_path = $this->goods->goods_play_path($img_arr);
//            $this->delete_file($img_path);

            $img_ret = $this->goods->goods_play_delete($img_arr);
            if($img_ret === false){
                $this->error("异常错误");
            }
            unset($params['img_delete']);
        }

        //更新商家基本信息
        $goods_ret = $this->goods->goods_edit($params);
        if($goods_ret === false){
            $this->error("异常错误");
        }

        //图片更新
        //加载图片配置
        $config = C('PIC_CONFIG');
        $config['savePath'] = '/Goods/';
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
                        //删除原图
                        $pic_path = $this->goods->goods_pic_path($params['id']);
                        $this->delete_file($pic_path);

                        //海报
                        $map['pic'] = $path;
                        $where['id'] = $params['id'];
                        M('Goods')->where($where)->save($map);
                        //$this->delete_file($pic_path);
                    }else{
                        //轮播图片
                        $i = substr($key,13);
                        $map['path']       = $path;
                        $map['goods_id']   = $params['id'];
                        $map['play_order'] = $params['order'.$i];
                        M('Goods_play')->data($map)->add();
                    }
                }
            }
        }
        $this->redirect('Shop/goods_edit_page',array('id'=>$params['id']));
    }

    /**
     * 商品删除操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_del(){
        $id = I('post.id');

        //删除商品图片
        $path = $this->goods->goods_pic_path($id);
        $this->delete_file($path);


        //删除商家基本信息
        $goods_ret = $this->goods->goods_del($id);
        if($goods_ret === false){
            $this->error("异常错误");
        }

        //删除轮播图
        $img_path = $this->goods->goods_play_path($id);
        $this->delete_file($img_path);

        $path = M('Goods_play')->field('path')->where('goods_id = '.$id);
        $this->delete_file($path['path']);
        M('Goods_play')->where('goods_id = '.$id)->delete();
        $this->redirect('Shop/index');
    }

    /**
     * 商品审核状态改变操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function status_change(){
        $id = I('post.id');
        //读取商品的状态
        $this->goods->status_change($id);

    }

    /**
     * 文本编辑器上传图片
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function edit_upload_img(){
        //加载图片配置
        $config = C('PIC_CONFIG');
        $config['savePath'] = '/EditPic/';
        
        //图片更新
        $upload = new \Think\Upload($config);// 实例化上传类

        // 图片上传
        $info   =   $upload->uploadOne($_FILES['file']);
        if(!$info) {
            // 上传错误提示错误信息
            $this->error($upload->getError());
        }else{
            $path = $info['savepath'].$info['savename'];
            $file = C('DOMAIN_NAME').'Uploads'.$path;
//            $file = 'http://localhost/quwanba1/'.'Uploads'.$path;
            echo $file;
        }
    }

    /**
     * 商城商品导出excel
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_export(){
        $type_id = I('get.type');
        if(isset($type_id)){
            $where['type_id'] = $type_id;
        }
        $goods_data = $this->lists($this->goods
            ->join('left join qwb_shop on qwb_goods.shop_id = qwb_shop.id')
            ->join('left join qwb_goods_type on qwb_goods.type_id = qwb_goods_type.id')
            ,$where,'','',['qwb_shop.shop_name,qwb_goods.id,qwb_goods.name,province_id,city_id,district_id,qwb_goods_type.name type_name,original_price,selling_price,qwb_goods.conditions,qwb_goods.order']
        );
        //区域替换
        $goods_data = $this->address_replace($goods_data);

        //获取完成订单数,退订订单数
        foreach ($goods_data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'id'){
                    $value['complete'] = $this->order->get_order_status($v, '1');
                    $value['countermand'] = $this->order->get_order_status($v, '3');
                }
            }
            $goods_data[$key] = $value;
        }

        $fileName = "趣玩商城商品";

        $headArr  = array('商品ID','商品名称','商户名','区域','活动类型','完成订单数','退订订单数','原价','售价','审核状态');

        //数据排序
        foreach ($goods_data as $key => $value){
            foreach ($value as $k => $v){
                switch ($k){
                    case 'id':            $data[$key]['0'] = $v; break;
                    case 'name':          $data[$key]['1'] = $v; break;
                    case 'shop_name':     $data[$key]['2'] = $v; break;
                    case 'province':      $data[$key]['3'] = $v; break;
                    case 'city':          $data[$key]['3'] .= $v; break;
                    case 'district':      $data[$key]['3'] .= $v; break;
                    case 'type_name':     $data[$key]['4'] = $v; break;
                    case 'complete':      $data[$key]['5'] = $v; break;
                    case 'countermand':   $data[$key]['6'] = $v; break;
                    case 'original_price': $data[$key]['7'] = $v; break;
                    case 'selling_price': $data[$key]['8'] = $v; break;
                    case 'conditions':
                        if($v == '1'){
                            $data[$key]['9'] = "已上架";
                        }else{
                            $data[$key]['9'] = "未上架";
                        }
                        break;
                }
            }
            ksort($data[$key]);
        }
        ob_end_clean();

        $this->getExcel($fileName,$headArr,$data);
    }
}