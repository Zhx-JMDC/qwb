<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\ShopModel;
class NewShopController extends AdminController {
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
        $params = I('get.');
        $where = array();
        if($params['province_id']){
            $where['province_id'] = $params['province_id'];
            $this->assign('province_id',$params['province_id']);
            $this->assign('province',get_province($params['province_id']));
        }
        if($params['city_id']){
            $where['city_id'] = $params['city_id'];
            $this->assign('city_id',$params['city_id']);
            $this->assign('city',get_city($params['city_id']));
        }
        if($params['district_id']){
            $where['district_id'] = $params['district_id'];
            $this->assign('district_id',$params['district_id']);
            $this->assign('district',get_district($params['district_id']));
        }
        if($params['shop_name'] != ''){
            $where['shop_name'] = $params['shop_name'];
            $this->assign('shop_name',$params['shop_name']);
        }

        $where['shop_type'] = '1';
        $data = $this->shop->get_shop($where);

        //地区获取
        $data['goods'] = $this->address_replace($data['goods']);

        //获取省
        $province = $this->get_province_all();

        if($data === false){
            $this->error('异常错误');
        }else {
            $this->assign('_province',$province);
            $this->assign('_page',$data['show']);
            $this->assign('_list',$data['goods']);
            $this->display('NewShop/index');
        }
    }

    /**
 * 获取指定商家详情
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
    public function new_shop_detail(){
        $id = I('get.id');
        $data = $this->shop->get_shop_detail($id);
        //区域组装
        $data['province'] = get_province($data['province_id']);
        $data['city']     = get_city($data['city_id']);
        $data['district'] = get_district($data['district_id']);

        //海报,折扣图片
        $data['pic'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['pic'], 's!');
        $data['discount_img'] = C('DOMAIN_NAME').'Uploads'.get_thumb($data['discount_img'], 's!');
        $data['shop_play'] = pic_replace($data['shop_play'], ['path']);

        //文本转义
        $data['introduce'] = htmlspecialchars_decode($data['introduce']);

        if($data == false){
            $this->error("异常错误");
        }else{
            $this->assign('shop',$data);
            $this->display('NewShop/details');
        }
    }
}