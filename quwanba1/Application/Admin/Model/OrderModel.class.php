<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/7
 * Time: 17:38
 */

namespace Admin\Model;
use Think\Model;
use Think\Model\RelationModel;

class OrderModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'order_detail'=>array(
            'mapping_type'  =>self::HAS_ONE,
            'foreign_key'   =>'order_id',
            'mapping_name'  =>'order_detail',
            'mapping_fields'=>'order_num,experience_time,count,buyer,mobile',
            'as_fields'     =>'order_num,experience_time,count,buyer,mobile'
        ),
        'shop'=>array(
            'mapping_type'  =>self::BELONGS_TO,
            'foreign_key'   =>'shop_id',
            'mapping_name'  =>'shop',
            'mapping_fields'=>'shop_name',
            'as_fields'     =>'shop_name'
        )
    );

    /**
     * 获取交易订单详情
     * @param array $map 查询条件
     * @return array
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_order($map = array()){
        $map['pay_status'] = array('NEQ', 0);
        $order = D('Order');
        $count = $order
            ->join('left join qwb_order_detail on qwb_order.id = qwb_order_detail.order_id')
            ->join('left join qwb_shop on qwb_order.shop_id = qwb_shop.id')
            ->where($map)->count();
        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出

        $order_data  = $order
            ->field('pay_way,out_trade_no,qwb_order.id,buy_time,order_num,buyer,shop_name,mobile,name,count,price,qr_code,status,pay_status')
            ->join('left join qwb_order_detail on qwb_order.id = qwb_order_detail.order_id')
            ->join('left join qwb_shop on qwb_order.shop_id = qwb_shop.id')
            ->where($map)->limit($limit)->select();

        $data = array(
            'show' => $show,
            'order'=> $order_data
        );

        return $data;
    }

    /**
     * 获取消费二维码列表
     * @param  array $map  条件
     * @return array $data 数据
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_qrcode($map = array()){
        $map['_string']   = ' (pay_status = 1)  OR ( pay_status = 2) ';

        $order = D('Order');
        $count = $order
            ->join('qwb_order_detail on qwb_order_detail.order_id = qwb_order.id')
            ->join('qwb_shop on qwb_shop.id = qwb_order.shop_id')
            ->where($map)->count();
        $Page  = new \Think\Page($count,3);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出

        $order_data  = $order->field('qwb_order.id,shop_name,buyer,mobile,experience_time')
            ->join('qwb_order_detail on qwb_order_detail.order_id = qwb_order.id')
            ->join('qwb_shop on qwb_shop.id = qwb_order.shop_id')
            ->where($map)->limit($limit)->select();

        $data = array(
            'show' => $show,
            'order'=> $order_data
        );
        return $data;
    }

    /**
     * 获取指定商品的,消费状态单数[0预定，1完成，2等待退订，3已退订，-1已删除的订单]
     * @param $goods_id 商品id
     * @param $status   消费状态
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_order_status($goods_id, $status){
        $map['goods_id']   = $goods_id;
        $map['status']     = $status;
        $map['pay_status'] = array('NEQ', 0);
        return M('Order')->where($map)->count();
    }
}