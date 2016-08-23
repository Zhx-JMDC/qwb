<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\GoodsModel;
use Admin\Model\OrderModel;
class ShopRobController extends AdminController{
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
     * 限时抢购商品列表显示页
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

        //限时抢购
        $where['type_id'] = '1';
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
            $this->display('ShopRob/index');
        }else{
            $this->error("异常错误");
        }
    }
}