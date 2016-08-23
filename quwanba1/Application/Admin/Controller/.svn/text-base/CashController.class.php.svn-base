<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 16/7/19
 * Time: 下午4:16
 */

namespace Admin\Controller;


use Admin\Model\OrderModel;
use Admin\Model\IncomeModel;

class CashController extends AdminController{
    protected $order;
    protected $income;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct(){
        parent::__construct();
        $this->order = new OrderModel();
        $this->income = new IncomeModel();
    }


    /**
     * 购卡佣金流水显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_index(){
        $params = I('get.');
        $where = array();
        if($params['filter_start_time']){
            $where['qwb_income.time'] = array('GT', $params['filter_start_time']);
            $this->assign('filter_start_time',$params['filter_start_time']);
        }
        if($params['filter_end_time']){
            $where['qwb_income.time'] = array('LT', $params['filter_end_time']);
            $this->assign('filter_end_time',$params['filter_end_time']);
        }
        if($params['filter_start_time'] && $params['filter_end_time']){
            $where['qwb_income.time']  = array('between',array($params['filter_start_time'],$params['filter_end_time']));
            $this->assign('filter_end_time',$params['filter_end_time']);
            $this->assign('filter_start_time',$params['filter_start_time']);
        }

        $data = $this->income->card_cash_flow($where);

        $this->assign('_list',$data['cash']);
        $this->assign('_page',$data['show']);
        $this->display("Cash/index");
    }

    /**
     * 商品佣金流水显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_index(){
        $params = I('get.');
        $where = array();
        if($params['filter_start_time']){
            $where['qwb_income.time'] = array('GT', $params['filter_start_time']);
            $this->assign('filter_start_time',$params['filter_start_time']);
        }
        if($params['filter_end_time']){
            $where['qwb_income.time'] = array('LT', $params['filter_end_time']);
            $this->assign('filter_end_time',$params['filter_end_time']);
        }
        if($params['filter_start_time'] && $params['filter_end_time']){
            $where['qwb_income.time']  = array('between',array($params['filter_start_time'],$params['filter_end_time']));
            $this->assign('filter_end_time',$params['filter_end_time']);
            $this->assign('filter_start_time',$params['filter_start_time']);
        }

        $data = $this->income->goods_cash_flow($where);

        $this->assign('_list',$data['cash']);
        $this->assign('_page',$data['show']);
        $this->display("Cash/goods_index");
    }

    /**
     * 用户提款流水显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function withdraw_index(){
        $params = I('get.');
        $where = array();
        if($params['filter_start_time']){
            $where['qwb_withdraw_history.time'] = array('GT', $params['filter_start_time']);
            $this->assign('filter_start_time',$params['filter_start_time']);
        }
        if($params['filter_end_time']){
            $where['qwb_withdraw_history.time'] = array('LT', $params['filter_end_time']);
            $this->assign('filter_end_time',$params['filter_end_time']);
        }
        if($params['filter_start_time'] && $params['filter_end_time']){
            $where['qwb_withdraw_history.time']  = array('between',array($params['filter_start_time'],$params['filter_end_time']));
            $this->assign('filter_end_time',$params['filter_end_time']);
            $this->assign('filter_start_time',$params['filter_start_time']);
        }

        $data = $this->income->withdraw_cash_flow($where);

        $this->assign('_list',$data['cash']);
        $this->assign('_page',$data['show']);
        $this->display("Cash/withdraw_index");
    }
}