<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 16/7/19
 * Time: 下午4:16
 */

namespace Admin\Controller;


use Admin\Model\OrderModel;

class QrcodeController extends AdminController{
    protected $order;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct(){
        parent::__construct();
        $this->order = new OrderModel();
    }


    /**
     * 消费二维码列表显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index(){
        $params = I('get.');
        $where = array();
        if($params['shop_name']){
            $where['shop_name'] = $params['shop_name'];
            $this->assign('shop_name',$params['shop_name']);
        }
        if($params['mobile']){
            $where['mobile'] = $params['mobile'];
            $this->assign('mobile',$params['mobile']);
        }
        if($params['buyer']){
            $where['buyer'] = $params['buyer'];
            $this->assign('buyer',$params['buyer']);
        }

        $data = $this->order->get_qrcode($where);

        foreach ($data['order'] as $key =>$value){
            foreach ($value as $k => $v){
                if($k == 'Qrcode'){
                    $value[$k] = C('DOMAIN_NAME').$v;
                }
            }
            $data['order'][$key] = $value;
        }

        $this->assign('_list',$data['order']);
        $this->assign('_page',$data['show']);
        $this->display("Qrcode/index");
    }

    public function qrcode_export(){
        //获取订单数据
        $map['_string']   = ' (pay_status = 1)  OR ( pay_status = 2) ';
        $data  =  M('Order')->field('qwb_order.id,buyer,mobile,experience_time,shop_name')
            ->join('qwb_order_detail on qwb_order_detail.order_id = qwb_order.id')
            ->join('qwb_shop on qwb_shop.id = qwb_order.shop_id')
            ->where($map)->select();

        $fileName = "二维码消费";

        $headArr  = array('序号','用户名','用户手机号','消费时间','消费商家');
        ob_end_clean();
        $this->getExcel($fileName,$headArr,$data);
    }
}