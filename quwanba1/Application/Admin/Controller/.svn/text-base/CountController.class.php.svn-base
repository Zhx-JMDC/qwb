<?php
namespace  Admin\Controller;
use Think\Controller;
class CountController extends AdminController{
    /**
     * 系统统计
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index(){
        //总用户数
        $user_count = M('User')->count();
        $this->assign('user_count',$user_count);

        //今日用户增长数
        $user_map['subscribe_time'] = array('GT',strtotime(date('Y-m-d',time())));
        $today_user_grow = M('User')->where($user_map)->count();
        $this->assign('today_user_grow',$today_user_grow);

        //获取总商品数
        $goods_count = M('Goods')->count();
        $this->assign('goods_count',$goods_count);

        //总联盟商家数
        $shop_count = M('Shop')->count();
        $this->assign('shop_count',$shop_count);

        //总订单数
        $order_map['pay_status'] = array('NEQ',0);
        $order_count = M('Order')->where($order_map)->count();
        $this->assign('order_count',$order_count);

        //今日订单数
        $order_map['pay_status'] = array('NEQ',0);
        $order_map['buy_time']   = array('GT',date('Y-m-d 00:00:00',time()));
        $today_order_grow = M('Order')->where($order_map)->count();
        $this->assign('today_order_grow',$today_order_grow);

        //订单总额
        $price_map['pay_status'] = array('EQ',1);
        $order_price = M('Order')->where($price_map)->sum('price');
        $this->assign('order_price',$order_price);

        //订单总额
        $today_price_map['pay_status'] = array('EQ',1);
        $today_price_map['buy_time']   = array('GT',date('Y-m-d 00:00:00',time()));
        $today_order_price = M('Order')->where($today_price_map)->sum('price');
        $this->assign('today_order_price',$today_order_price);

        //V级代理数
        $v_map['rank'] = 1;
        $v_user_count = M('User')->where($v_map)->count();
        $this->assign('v_user_count',$v_user_count);

        //S级代理数
        $s_map['rank'] = 0;
        $s_user_count = M('User')->where($s_map)->count();
        $this->assign('s_user_count',$s_user_count);

        //今日新增V级代理数
        $today_v_map['rank'] = 1;
        $today_v_map['subscribe_time'] = array('GT',strtotime(date('Y-m-d',time())));
        $today_v_count = M('User')->where($today_v_map)->count();
        $this->assign('today_v_count',$today_v_count);

        //今日新增S级代理数
        $today_s_map['rank'] = 0;
        $today_s_map['subscribe_time'] = array('GT',strtotime(date('Y-m-d',time())));
        $today_s_count = M('User')->where($today_s_map)->count();
        $this->assign('today_s_count',$today_s_count);

        //层级数量
        $tier = M('User')->Max('tier');
        $this->assign('tier',$tier);

        $this->display("Count/detail");
    }
}