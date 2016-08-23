<?php
namespace  Admin\Controller;
use Think\Controller;
use Admin\Model\UserModel;
class UserController extends AdminController{
    protected $user;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->user = new UserModel();
    }

    /**
     * 用户管理显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index(){
        $params = I('get.');
        $where = array();
        if($params['rank'] != ''){
            $where['rank'] = $params['rank'];
            $this->assign('rank',$params['rank']);
        }
        if($params['name'] != ''){
            $where['name'] = $params['name'];
            $this->assign('name',$params['name']);
        }
        if($params['contact'] != ''){
            $where['contact'] = $params['contact'];
            $this->assign('contact',$params['contact']);
        }

        //用户基本信息
        $data = $this->user->get_user($where);

        //用户商品消费金额
        foreach ($data['user'] as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'id'){
                    $value['consume'] = $this->user->get_user_consume($v);
                    $value['income_surplus'] = $this->user->user_f_surplus($v);
                }
            }
            $data['user'][$key] = $value;
        }

        if($data == -1){
            $this->error("异常错误"); exit();
        }

        $this->assign('_page',$data['show']);
        $this->assign('_list',$data['user']);
        $this->display('User/index');
    }

    /**
     * 查看用户详情
     * @param id 用户id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_user_detail(){
        $id = I('get.id');
        //获取用户基本信息
        $user_data = $this->user->get_user_detail($id);
        if($user_data === false){
            $this->error('异常错误');
        }
        $user_data['qr_code'] = C('DOMAIN_NAME').$user_data['qr_code'];

        //获取用户可提现饭票和带提现饭票
        if($user_data['0'] == 0){
            //S级都可提现
            $can_draw_income  = $this->user->get_draw_income($user_data['id'], '',0);
            $stop_draw_income = '0';
        }else{
            //V级,require为1可提现,为0待提现
            $stop_draw_income = $this->user->get_draw_income($user_data['id'], 0, 0);
            $can_draw_income  = $this->user->get_draw_income($user_data['id'], 1, 0);
        }

        //用户购卡消费记录
        $card_where['status'] = '1';
        $buy_card_data = M('Card_history')->field('id,card_id,price,express_num,status,time')
            ->where($card_where)->order('time desc')->select();

        //用户购买商品消费记录
        $goods_where['_string'] = 'pay_status = 1 or pay_status = 2';
        $buy_goods_data = M('Order')->field('id,name,price,qr_code,pay_status,buy_time')
            ->where($goods_where)->order('buy_time desc')->select();

        //用户消费二维码
        foreach ($buy_goods_data as $key=>$value){
            foreach ($value as $k => $v){
                if($k == 'qr_code'){
                    $value[$k] = C('DOMAIN_NAME').$v;
                }
            }
            $buy_goods_data[$key] = $value;
        }

        $this->assign('goods', $buy_goods_data);
        $this->assign('card', $buy_card_data);
        $this->assign('can_draw', $can_draw_income);
        $this->assign('stop_draw', $stop_draw_income);
        $this->assign('user', $user_data);

        $this->display('User/detail');
    }

    /**
     * 用户导出excel
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function user_export(){
        $user_data = M('User')->field('qwb_user.id,rank,nickname,openid,subscribe_time,login_time,contact,name,price')
            ->join('left join qwb_card_history on qwb_card_history.uid = qwb_user.id')
            ->group('qwb_user.id')->select();

        //用户商品消费金额
        foreach ($user_data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'id'){
                    $value['consume'] = $this->user->get_user_consume($v);
                    $value['income_surplus'] = $this->user->user_f_surplus($v);
                }
            }
            $user_data[$key] = $value;
        }

        $fileName = "用户列表";

        $headArr  = array('用户ID','姓名','手机号','周游卡等级','微信昵称','用户ID','消费金额','注册时间','最后登录时间','饭票余额');

        //数据排序
        foreach ($user_data as $key => $value){
            foreach ($value as $k => $v){
                switch ($k){
                    case 'id':            $data[$key]['0'] = $v; break;
                    case 'name':          $data[$key]['1'] = $v; break;
                    case 'contact':       $data[$key]['2'] = $v; break;
                    case 'rank':
                        if($v == '0'){
                            $data[$key]['3'] = "S级代言人";
                        }elseif($v == '1'){
                            $data[$key]['3'] = "V级代言人";
                        }else{
                            $data[$key]['3'] = "非代言人";
                        }
                        break;
                    case 'nickname':      $data[$key]['4'] = $v; break;
                    case 'openid':        $data[$key]['5'] = $v; break;
                    case 'consume':
                        if(!empty($v)){
                            $data[$key]['6'] = $v;
                        }else{
                            $data[$key]['6'] = 0.00;
                        }
                        break;
                    case 'subscribe_time':$data[$key]['7'] = $v; break;
                    case 'login_time':    $data[$key]['8'] = $v; break;
                    case 'income_surplus':
                        if(!empty($v)){
                            $data[$key]['9'] = $v;
                        }else{
                            $data[$key]['9'] = 0.00;
                        }
                        break;
                }
            }
            ksort($data[$key]);
        }
        ob_end_clean();

        $this->getExcel($fileName,$headArr,$data);
    }
}