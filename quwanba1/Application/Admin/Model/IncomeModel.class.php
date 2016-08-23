<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/7
 * Time: 17:38
 */

namespace Admin\Model;
use Think\Model;

class IncomeModel extends Model{
    /**
     * 用户购卡佣金流水记录
     * @param  int   type  0周游卡,1商品
     * @param  array $map  条件
     * @return array $data 数据
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_cash_flow($map = array()){
        $map['qwb_income.type'] = '0';
        $cash = D('Income');
        $count = $cash->join('qwb_card_history on qwb_card_history.id = qwb_income.detail_id')->where($map)->count();
        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出

        $cash_data = $cash->field('qwb_income.id,income,qwb_income.time,card_id,qwb_card_history.name,qwb_income.status')
            ->join('qwb_card_history on qwb_card_history.id = qwb_income.detail_id')
            ->where($map)->limit($limit)->select();

        $data = array(
            'show' => $show,
            'cash' => $cash_data
        );

        return $data;
    }

    /**
     * 商品佣金流水记录
     * @param  int   type  0周游卡,1商品
     * @param  array $map  条件
     * @return array $data 数据
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_cash_flow($map = array()){
        $map['qwb_income.type'] = '1';
        $cash = D('Income');
        $count = $cash->join('qwb_order on qwb_order.id = qwb_income.detail_id')->where($map)->count();
        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出

        $cash_data = $cash->field('qwb_income.id,income,qwb_income.time,qwb_income.status,name,buyer,order_num')
            ->join('qwb_order on qwb_order.id = qwb_income.detail_id')
            ->join('qwb_order_detail on qwb_order.id = qwb_order_detail.order_id')
            ->where($map)->limit($limit)->select();

        $data = array(
            'show' => $show,
            'cash' => $cash_data
        );

        return $data;
    }

    /**
     * 用户提款流水记录
     * @param  int   type  0周游卡,1商品
     * @param  array $map  条件
     * @return array $data 数据
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function withdraw_cash_flow($map = array()){
        $cash = D('withdraw_history');
        $count = $cash->where($map)->count();
        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出

        $cash_data = $cash->field('qwb_withdraw_history.id,nickname,time,value,trade_no,type')
            ->join('qwb_user on qwb_user.id = qwb_withdraw_history.uid')
            ->where($map)->order('time desc')->limit($limit)->select();

        $data = array(
            'show' => $show,
            'cash' => $cash_data
        );

        return $data;
    }
}