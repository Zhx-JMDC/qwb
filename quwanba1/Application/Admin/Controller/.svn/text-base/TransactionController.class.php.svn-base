<?php
/**
 * Created by PhpStorm.
 * User: lan
 * Date: 16/7/19
 * Time: 下午4:16
 */

namespace Admin\Controller;
use Admin\Model\OrderModel;

class TransactionController extends AdminController{
    protected $order;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct(){
        parent::__construct();
        $this->order = new OrderModel();
    }


    /**
     * 商品交易统计列表
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index(){
        $params = I('get.');
        $where = array();
        if($params['filter_start_time']){
            $where['buy_time'] = array('GT', $params['filter_start_time']);
            $this->assign('filter_start_time',$params['filter_start_time']);
        }
        if($params['filter_end_time']){
            $where['buy_time'] = array('LT', $params['filter_end_time']);
            $this->assign('filter_end_time',$params['filter_end_time']);
        }
        if($params['filter_start_time'] && $params['filter_end_time']){
            $where['buy_time']  = array('between',array($params['filter_start_time'],$params['filter_end_time']));
            $this->assign('filter_end_time',$params['filter_end_time']);
            $this->assign('filter_start_time',$params['filter_start_time']);
        }
        if($params['name']){
            $where['name'] = $params['name'];
            $this->assign('name',$params['name']);
        }
        if($params['mobile'] != ''){
            $where['mobile'] = trim($params['mobile']);
            $this->assign('mobile',$params['mobile']);
        }
        if($params['status'] != ''){
            $where['status'] = trim($params['status']);
            $this->assign('status',$params['status']);
        }
        if($params['out_trade_no'] != ''){
            $where['out_trade_no'] = $params['out_trade_no'];
            $this->assign('out_trade_no',$params['out_trade_no']);
        }
        if($params['buyer'] != ''){
            $where['buyer'] = $params['buyer'];
            $this->assign('buyer',$params['buyer']);
        }

        $data = $this->order->get_order($where);

        //图片地址替换
        foreach ($data['order'] as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'pic'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.get_thumb($v, 's!');
                }elseif($k == 'qr_code'){
                    $value[$k] = C('DOMAIN_NAME').$v;
                }
            }
            $data['order'][$key] = $value;
        }

        $this->assign('_list', $data['order']);
        $this->assign('_page', $data['show']);
        $this->display('TransactionCount/index');
    }

    /**
     * 商品交易导出
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_transaction_export(){
        $map['pay_status'] = array('NEQ', 0);
        $data = M('Order')->field('qwb_order.id,buy_time,order_num,buyer,shop_name,mobile,name,count,price,qr_code,status,pay_status')
            ->join('left join qwb_order_detail on qwb_order.id = qwb_order_detail.order_id')
            ->join('left join qwb_shop on qwb_order.shop_id = qwb_shop.id')
            ->where($map)->select();

        $fileName = "商品交易";

        $headArr  = array('交易ID','时间','订单号','买家','卖家','买家手机号','商品名','订单数量','总价','订单二维码','状态','退款状态');

        //数据排序
        foreach ($data as $key => $value){
            foreach ($value as $k => $v){
                switch ($k){
                    case 'status':
                        if($v == '0'){
                            $data[$key]['status'] = "已预订";
                        }elseif($v == '1'){
                            $data[$key]['status'] = "已完成";
                        }elseif($v == '2'){
                            $data[$key]['status'] = "等待退订";
                        }else{
                            $data[$key]['status'] = "已退订";
                        }
                        break;
                    case 'pay_status':
                        if($v == '0'){
                            $data[$key]['pay_status'] = "未支付";
                        }elseif($v == '1'){
                            $data[$key]['pay_status'] = "已支付";
                        }else{
                            $data[$key]['pay_status'] = "已退款";
                        }
                        break;
                    case 'qr_code': $data[$key]['qr_code'] = C('DOMAIN_NAME').$v; break;
                }
            }
        }
        ob_end_clean();

        $this->getExcel($fileName,$headArr,$data);
    }

    /**
     * 退款操作
     * @param id 退款订单id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function refund(){
        $id = I('get.id');

        $data['status']     = 3;
        $data['pay_status'] = 2;
        $map['id']          = $id;
        $ret = M('Order')->where($map)->save($data);

        if($ret === false){
            $this->error("异常错误");
        }else{
            $this->redirect('Transaction/index');
        }
    }
}