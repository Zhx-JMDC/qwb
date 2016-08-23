<?php
namespace Wechat\Controller;
use Think\Controller;
use Wechat\Model\ShopModel;
class AllianceShopController extends CommonController {
    protected $shop;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->shop = new ShopModel();
    }

    /**
     * 联盟商家主页显示页(最新商家)
     * @param type 0热门商品，1最新商家
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_index_new(){
        $search = I('get.search');
        if($search != ''){
            $this->assign('search',$search);
        }

        $city = I('get.city');
        if(!empty($city)){
            //GET存在,用户切换的地理位置,重置session
            session('city',$city);
        }else{
            $city  = session('city');
        }
        $class_data  = $this->shop->get_shop_class();    //分类
        $goods_count = $this->shop->get_shop_count('1'); //总条数
        $ad_data = $this->shop->get_shop_ad();           //轮播

        //组合海报跳转地址
        foreach ($ad_data as $key => $value){
            foreach($value as $k => $v){
                if($k == 'skip_url'){
                    $value[$k]  = U('AllianceShop/shop_intro').'?id='.$value['skip_url'];
                }elseif($k == 'path'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                }
            }
            $ad_data[$key] = $value;
        }
        //分类图标
        $class_data = pic_replace($class_data, ['icon']);

        //获取城市
        $province = session('province');
        $data = $this->shop->get_city($province);

        if($class_data === false){
            $this->error('异常错误');
        }else{
            $this->assign('footer_shop','footer_bottom-Clicked');
            $this->assign('ad',$ad_data);
            $this->assign('check_city',$city);
            $this->assign('city',$data);
            $this->assign('goods_count',$goods_count);
            $this->assign('class',$class_data);
            $this->display('AllianceShop/shopIndexNew');
        }
    }

    /**
     * 联盟商家主页显示页(最新商品)下拉加载
     * @param type  0热门商品，1最新商家
     * @param page  页数
     * @param count 每页总数
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_new_more(){
        $search = I('post.search');
        $city   = session('city');
        $page   = I('post.page');
        $count  = I('post.count');
        $goods_data = $this->shop->get_shop('1', $page, $count, $city, $search);

        //商家海报,折扣
        $goods_data = pic_replace($goods_data, ['pic','discount_img']);

        //用户所在地理位置
        $longitude = session('longitude');
        $latitude  = session('latitude');
        foreach($goods_data as $key=>$value){
            foreach ($value as $k=>$v){
                $value['distance'] = (int)(getDistance($latitude,$longitude,$value['latitude'],$value['longitude'])/1000);
            }
            $goods_data[$key] = $value;
        }

        //距离排序
//        $goods_data = array_sort($goods_data, SORT_ASC, 'distance');

        if($goods_data === false){
            $this->error('异常错误');
        }else{
            $this->ajaxReturn($goods_data);
        }
    }

    /**
     * 联盟商家主页显示页(热门商品)
     * @param type 0热门商品，1最新商家
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_index_hot(){
        $search = I('get.search');
        if($search != ''){
            $this->assign('search',$search);
        }

        $flag = 1;
        $city = I('get.city');
        if(!empty($city)){
            //城市修改
            $flag = 0;
            session('city',$city);
        }
        //定位
        $session_city = session('city');
        if($session_city){
            //用户切换了位置,关闭微信定位功能,flag = 0,重置城市
            $flag = 0;
        }

        $class_data   = $this->shop->get_shop_class();
        $goods_count  =  $this->shop->get_shop_count('0'); //总条数
        $ad_data = $this->shop->get_shop_ad();           //轮播

        //组合海报跳转地址
        foreach ($ad_data as $key => $value){
            foreach($value as $k => $v){
                if($k == 'skip_url'){
                    $value[$k]  = U('AllianceShop/shop_intro').'?id='.$value['skip_url'];
                }elseif($k == 'path'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                }
            }
            $ad_data[$key] = $value;
        }
        //分类图标
        $class_data = pic_replace($class_data, ['icon']);

        //定位
        $sign_package = $this->get_sign_package();

        if($class_data === false){
            $this->error('异常错误');
        }else{
            $this->assign('footer_shop','footer_bottom-Clicked');
            $this->assign('ad',$ad_data);
            $this->assign('city', session('city'));
            $this->assign('flag', $flag);
            $this->assign('signPackage', $sign_package);
            $this->assign('goods_count', $goods_count);
            $this->assign('class', $class_data);
            $this->display('AllianceShop/shopIndexHot');
        }
    }

    /**
     * 联盟商家主页显示页(热门商品)下拉加载
     * @param type  0热门商品，1最新商家
     * @param page  页数
     * @param count 每页总数
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_hot_more(){
        $search    = I('post.search');
        $post_city = I('post.city');
        //用户所在地理位置
        $longitude = session('longitude');
        $latitude  = session('latitude');
        if(!empty($post_city)){
            $city = $post_city;
            session('city',$post_city);
        }elseif(session('city')){
            $city = session('city');
        }else{
            $city = '温州市';
        }

        $page  = I('post.page');
        $count = I('post.count');
        $goods_data = $this->shop->get_shop('0', $page, $count, $city,$search);

        foreach ($goods_data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'pic'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }elseif($k == 'discount_img'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $goods_data[$key] = $value;
        }

        //距离排序
        foreach($goods_data as $key=>$value){
            foreach ($value as $k=>$v){
                $value['distance'] = (int)(getDistance($latitude,$longitude,$value['latitude'],$value['longitude'])/1000);
            }
            $goods_data[$key] = $value;
        }
        $goods_data = array_sort($goods_data, SORT_ASC, 'distance');

        if($goods_data === false){
            $this->error('异常错误');
        }else{
            $this->ajaxReturn($goods_data);
        }
    }

    /**
     * 联盟商家分类显示页
     * @param id 商品分类id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_class(){
        $id = I('get.id');
        $class_data = $this->shop->get_shop_class();
        $goods_count = $this->shop->get_class_count($id); //总条数

        foreach($class_data as $key=>$value){
            foreach ($value as $k=>$v){
                if($k == 'icon'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $class_data[$key] = $value;
        }

        if($class_data === false){
            $this->error('异常错误');
        }else{
            $this->assign('footer_shop','footer_bottom-Clicked');
            $this->assign('id',$id);
            $this->assign('goods_count',$goods_count);
            $this->assign('class',$class_data);
            $this->display('AllianceShop/shopClass');
        }
    }

    /**
     * 联盟商家分类商品显示页(下拉加载)
     * @param id    商品分类id
     * @param page  页数
     * @param count 每页总数
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_class_more(){
        $id    = I('post.id');
        $page  = I('post.page');
        $count = I('post.count');
        $goods_data = $this->shop->get_class_all($id, $page, $count);

        $latitude   = session('latitude');
        $longitude  = session('longitude');
        foreach($goods_data as $key=>$value){
            foreach ($value as $k=>$v){
                $value['distance'] = (int)(getDistance($latitude,$longitude,$value['latitude'],$value['longitude'])/1000);
            }
            $goods_data[$key] = $value;
        }
          //距离排序
//        $goods_data = array_sort($goods_data, SORT_ASC, 'distance');

        //海报,折扣图片组装
        foreach($goods_data as $key=>$value){
            foreach ($value as $k=>$v){
                if($k == 'pic'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }elseif($k == 'discount_img'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $goods_data[$key] = $value;
        }

        if($goods_data === false){
            $this->error('异常错误');
        }else {
            $this->ajaxReturn($goods_data);
        }
    }

    /**
     * 联盟商家详情显示页
     * @param id 商家id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_intro(){
        $id        = I('get.id');
        $uid       = get_uid();
        $latitude  = session('latitude');
        $longitude = session('longitude');


        $data  = $this->shop->get_shop_detail($id);

        //海报和折扣地址
        $data['pic'] = C('DOMAIN_NAME').'Uploads'.$data['pic'];
        $data['discount_img'] = C('DOMAIN_NAME').'Uploads'.$data['discount_img'];
        $data['card_pic'] = C('DOMAIN_NAME').'Uploads'.$data['card_pic'];

        //富文本转义
        $data['introduce'] = htmlspecialchars_decode($data['introduce']);
        $data['detail']    = htmlspecialchars_decode($data['detail']);

        //组合栏目图标地址
        foreach ($data['shop_play'] as $key => $value){
            $data['shop_play'][$key]['path'] = C('DOMAIN_NAME').'Uploads'.$value['path'];
        }

        //跳转地址
        $data['buy_url'] = U('Shop/shop_intro').'?id='.$data['buy_url'];

        //距离计算
        $data['distance'] = (int)(getDistance($latitude,$longitude,$data['latitude'],$data['longitude'])/1000);

        //查询是否收藏过
        $map['uid']        = $uid;
        $map['collect_id'] = $id;
        $map['type']       = 1;
        $collect = M('Collect')->where($map)->find();
        if($collect){
            $collect_flag = 1;
        }else{
            $collect_flag = 0;
        }

        if($data === false && $collect === false){
            $this->error('异常错误');
        }else{
            $count = count($data['shop_play']);
            $this->assign('collect_flag', $collect_flag);
            $this->assign('count', $count);
            $this->assign('goods', $data);
            $this->display('AllianceShop/shopIntro');
        }
    }

    /**
     * 根据省份获取城市
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_city(){
        $longitude = I('post.longitude');
        $latitude  = I('post.latitude');
        session('longitude', $longitude);
        session('latitude', $latitude);
        $province  = I('post.province');
        session('province',$province);
        $data = $this->shop->get_city($province);
        $this->ajaxReturn($data);
    }

    /****************************************************************************************************
     * 联盟商家主页显示页(热门商品)
     * @param type 0热门商品，1最新商家
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_shop_index_hot(){
        $latitude  = I('get.latitude');
        $longitude = I('get.longitude');
        session('latitude', $latitude);
        session('longitude', $longitude);
        session('city','温州市');

        $class_data   = $this->shop->get_shop_class();
        $goods_count  =  $this->shop->get_shop_count('0'); //总条数
        $ad_data = $this->shop->get_shop_ad();           //轮播

        //组合海报跳转地址
        foreach ($ad_data as $key => $value){
            foreach($value as $k => $v){
                if($k == 'skip_url'){
                    $value[$k]  = U('AllianceShop/app_shop_intro').'?id='.$value['skip_url'];
                }elseif($k == 'path'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                }
            }
            $ad_data[$key] = $value;
        }

        //分类图标
        $class_data = pic_replace($class_data, ['icon']);

        $sign_package = $this->get_sign_package();

        if($class_data === false){
            $this->error('异常错误');
        }else{

            $this->assign('footer_shop','footer_bottom-Clicked');
            $this->assign('ad',$ad_data);
            $this->assign('city', session('city'));
            $this->assign('signPackage', $sign_package);
            $this->assign('goods_count', $goods_count);
            $this->assign('class', $class_data);
            $this->display('AllianceShop/app_shopIndexHot');
        }
    }

    /**
     * 联盟商家主页显示页(最新商品)
     * @param type 0热门商品，1最新商家
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_shop_index_new(){

        $city = "温州市";
        $class_data  = $this->shop->get_shop_class();    //分类
        $goods_count = $this->shop->get_shop_count('1'); //总条数
        $ad_data = $this->shop->get_shop_ad();           //轮播

        //组合海报跳转地址
        foreach ($ad_data as $key => $value){
            foreach($value as $k => $v){
                if($k == 'skip_url'){
                    $value[$k]  = U('AllianceShop/app_shop_intro').'?id='.$value['skip_url'];
                }elseif($k == 'path'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                }
            }
            $ad_data[$key] = $value;
        }

        //分类图标
        $class_data = pic_replace($class_data, ['icon']);

        //获取城市
        $province = session('province');
        $data = $this->shop->get_city($province);

        if($class_data === false){
            $this->error('异常错误');
        }else{
            $this->assign('footer_shop','footer_bottom-Clicked');
            $this->assign('ad',$ad_data);
            $this->assign('check_city',$city);
            $this->assign('city',$data);
            $this->assign('goods_count',$goods_count);
            $this->assign('class',$class_data);
            $this->display('AllianceShop/app_shopIndexNew');
        }
    }

    /**
     * 联盟商家分类显示页
     * @param id 商品分类id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_shop_class(){
        $id = I('get.id');
        $class_data = $this->shop->get_shop_class();
        $goods_count = $this->shop->get_class_count($id); //总条数

        foreach($class_data as $key=>$value){
            foreach ($value as $k=>$v){
                if($k == 'icon'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $class_data[$key] = $value;
        }

        if($class_data === false){
            $this->error('异常错误');
        }else{
            $this->assign('footer_shop','footer_bottom-Clicked');
            $this->assign('id',$id);
            $this->assign('goods_count',$goods_count);
            $this->assign('class',$class_data);
            $this->display('AllianceShop/app_shopClass');
        }
    }

    /**
     * 联盟商家详情显示页
     * @param id 商家id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_shop_intro(){
        $latitude  = session('latitude');
        $longitude = session('longitude');
        $id        = I('get.id');
        $uid       = get_uid();
        $data      = $this->shop->get_shop_detail($id);

        //海报和折扣地址
        $data['pic'] = C('DOMAIN_NAME').'Uploads'.$data['pic'];
        $data['discount_img'] = C('DOMAIN_NAME').'Uploads'.$data['discount_img'];
        $data['card_pic'] = C('DOMAIN_NAME').'Uploads'.$data['card_pic'];

        //富文本转义
        $data['introduce'] = htmlspecialchars_decode($data['introduce']);
        $data['detail']    = htmlspecialchars_decode($data['detail']);

        //组合栏目图标地址
        foreach ($data['shop_play'] as $key => $value){
            $data['shop_play'][$key]['path'] = C('DOMAIN_NAME').'Uploads'.$value['path'];
        }

        //跳转地址
        $data['buy_url'] = U('Shop/app_shop_intro').'?id='.$data['buy_url'];

        //距离计算
        $data['distance'] = (int)(getDistance($latitude,$longitude,$data['latitude'],$data['longitude'])/1000);

        //查询是否收藏过
        $map['uid']        = $uid;
        $map['collect_id'] = $id;
        $map['type']       = 1;
        $collect = M('Collect')->where($map)->find();
        if($collect){
            $collect_flag = 1;
        }else{
            $collect_flag = 0;
        }

        if($data === false && $collect === false){
            $this->error('异常错误');
        }else{
            $count = count($data['shop_play']);
            $this->assign('collect_flag', $collect_flag);
            $this->assign('count', $count);
            $this->assign('goods', $data);
            $this->display('AllianceShop/app_shopIntro');
        }
    }
}