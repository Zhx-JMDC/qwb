<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 16/7/1
 * Time: 上午10:58
 */
namespace Wechat\Model;
use Think\Model;
use Think\Model\RelationModel;
class OrderModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'Order_detail'=>array(
            'mapping_type'  =>self::HAS_ONE,
            'foreign_key'   =>'order_id',
            'mapping_name'  =>'Order_detail',
            'mapping_fields'=>'order_id,order_num,buyer,count,mobile,experience_time,message',
            'as_fields'     =>'order_id,order_num,buyer,count,mobile,experience_time,message'
        )
    );

    /**
     * 获取用户订单列表
     * @param $uid 用户uid
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_order($uid){
       $map['uid'] = $uid;
       $map['pay_status'] = '1';
       return M('Order')->where($map)->order('buy_time desc')->select();
    }

    /**
     * 获取订单详情
     * @param $order_id 订单详情id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_order_detail($order_id){
        $map['id'] = $order_id;
        return D('Order')->relation('Order_detail')->where($map)->find();
    }

    /**
     * 订单退订操作(0预定，1完成，2退款,-1删除)
     * @param $id 订单id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function unsubscribe($id){
        $where['id'] = $id;
        $map['status'] = 2;
        return M('Order')->where($where)->save($map);
    }

    /**
     * 订单删除操作(0预定，1完成，2退款,-1删除)
     * @param $id 订单id
     * @return result
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function delete_order($id){
        $where['id'] = $id;
        $map['status'] = -1;
        $result = M('Order')->where($where)->save($map);
        if ($result == false){
            return 0;
        }else{
            return $result;
        }
        
    }
    
    
    /**
     * 创建订单
     * @param $data 创建订单数据
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function order_add($data){
        $order_data = array(
            'uid'      => $data['uid'],
            'name'     => $data['name'],
            'price'    => $data['price'],
            'status'   => $data['status'],
            'buy_time' => $data['buy_time'],
            'qr_code'  => $data['qr_code'],
            'goods_id' => $data['goods_id'],
            'pic'      => $data['pic'],
            'shop_id'  => $data['shop_id']
        );

        //关联写入(去除多余数据)
        $order_data['Order_detail'] = array(
            'order_id' => '',
            'order_num'=> $data['order_num'],
            'buyer'    => $data['buyer'],
            'count'    => $data['count'],
            'mobile'   => $data['mobile'],
            'message'  => $data['message'],
            'experience_time' => $data['experience_time']
        );
        return D('Order')->relation('Order_detail')->add($order_data);
    }

    /**
     * 修改订单支付状态
     * @param $id           订单id
     * @param $out_trade_no 微信商户订单号
     * @param $pay_way      用户支付方式1公众号，2app微信，3支付宝
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function order_pay_update($id, $out_trade_no, $pay_way){
        $map['id']            = $id;
        $data['out_trade_no'] = $out_trade_no;
        $data['pay_status']   = 1;
        $data['pay_way']      = $pay_way;
        return M('Order')->where($map)->save($data);
    }

    /**
     * 更具订单号获取商家绑定openid
     * @param $order_num 订单号
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_openid($order_num){
        $map['order_num'] = $order_num;
        return M('order')->field('status,wechat_num,experience_time,price,name,pay_status')
            ->join('left join qwb_order_detail on qwb_order_detail.order_id = qwb_order.id')
            ->join('left join qwb_shop on qwb_order.shop_id = qwb_shop.id')
            ->where($map)->find();
    }

    /**
     * 更具订单号获取订单详情
     * @param $order_num
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function num_order_detail($order_num){
        $map['order_num']  = $order_num;
        return $order_data = M('Order_detail')
            ->field('pay_status,status,qwb_order.id,goods_id,uid,price,count,buyer,mobile,buy_time,experience_time,message,pic')
            ->join('left join qwb_order on qwb_order_detail.order_id = qwb_order.id')->where($map)->find();
    }

    /**
     * 修改订单状态
     * @param $order_id  订单id
     * @param $status    0预定，1完成，2等待退订，3已退订，-1已删除的订单
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function order_update($order_id, $status){
        $map['id']      = $order_id;
        $data['status'] = $status;
        return M('Order')->where($map)->save($data);
    }
}