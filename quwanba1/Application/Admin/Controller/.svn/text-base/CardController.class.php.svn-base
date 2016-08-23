<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\CardModel;
class CardController extends AdminController{
    protected $goods;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->card = new CardModel();
    }

    /**
     * 周游卡显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index() {
        $data = $this->card->get_card();
        $this->assign('card',$data);
        $this->display('Card/edit');
    }

    /**
     * 周游卡交易记录显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_transaction(){
        $params = I('get.');
        $where = array();
        if($params['filter_start_time']){
            $where['time'] = array('GT', $params['filter_start_time']);
            $this->assign('filter_start_time',$params['filter_start_time']);
        }
        if($params['filter_end_time']){
            $where['time'] = array('LT', $params['filter_end_time']);
            $this->assign('filter_end_time',$params['filter_end_time']);
        }
        if($params['name']){
            $where['name'] = $params['name'];
            $this->assign('name',$params['name']);
        }
        if($params['contact'] != ''){
            $where['contact'] = trim($params['contact']);
            $this->assign('contact',$params['contact']);
        }
        if($params['out_trade_no'] != ''){
            $where['out_trade_no'] = trim($params['out_trade_no']);
            $this->assign('id',$params['id']);
        }
        if($params['card_id'] != ''){
            $where['card_id'] = $params['card_id'];
            $this->assign('card_id',$params['card_id']);
        }

        //获取订单数据
        $data = $this->card->card_transaction($where);

        if($data){
            $this->assign('_page',$data['show']);
            $this->assign('_list',$data['card']);
            $this->display('Card/index');
        }else{
            $this->error("异常错误");
        }
    }

    /**
     * 周游卡物流单号修改
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function express_edit(){
        $id = I('post.id');
        $express_num = I('post.express_num');
        $send_status = I('post.send_status');
        $where['id'] = $id;
        $data['express_num'] = $express_num;
        $data['send_status'] = $send_status;
        $ret = M('Card_history')->where($where)->save($data);

        if($ret === false){
            $this->error("异常错误");
        }else{
            $this->redirect('Card/card_transaction_detail',array('id'=>$id));
        }
    }

    /**
     * 周游卡交易详情
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_transaction_detail(){
        $id = I('get.id');
        $map['id'] = $id;
        $data = M('Card_history')->where($map)->find();

        $this->assign('card', $data);
        $this->display('Card/details');
    }

    /**
     * 周游卡交易导出
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_transaction_export(){
        //获取订单数据
        $map['status'] = '1';
        $data = M('Card_history')->field('id,time,name,contact,card_id,price,address,send_status,express_num')
            ->where($map)->select();

        $fileName = "用户列表";

        $headArr  = array('交易ID','时间','买家姓名','买家手机号','等级','价格','买家地址','状态','物流单号');

        //数据排序
        foreach ($data as $key => $value){
            foreach ($value as $k => $v){
                switch ($k){
                    case 'send_status':
                        if($v == '1'){
                            $data[$key]['send_status'] = "已发货";
                        }else{
                            $data[$key]['send_status'] = "未发货";
                        }
                        break;
                    case 'card_id':
                        if($v == '1'){
                            $data[$key]['card_id'] = "S级";
                        }else{
                            $data[$key]['card_id'] = "V级";
                        }
                        break;
                }
            }
        }
        ob_end_clean();
        $this->getExcel($fileName,$headArr,$data);
    }
}