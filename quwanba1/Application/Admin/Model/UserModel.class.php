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

class UserModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'card_history'=>array(
            'mapping_type'  =>self::HAS_ONE,
            'foreign_key'   =>'uid',
            'mapping_name'  =>'card_history',
            'mapping_fields'=>'contact,name,price',
            'as_fields'     =>'contact,name,price'
        ),
        'order'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'uid',
            'mapping_name'  =>'order',
            'mapping_fields'=>'id,name,price,qr_code,pay_status,buy_time',
            'as_fields'     =>'id,name,price,qr_code,pay_status,buy_time',
            'condition'     =>'pay_status = 1 or pay_status = 2',

        )
    );
    /**
     * 获取用户列表
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_user($map = array()){
        //分页
        $user  = M('User');
        $count = $user
            ->join('left join qwb_card_history on qwb_card_history.uid = qwb_user.id')
            ->where($map)->group('qwb_user.id')->select();
        $count = count($count);

        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出
        $user_data = $user->field('qwb_user.id,rank,nickname,openid,subscribe_time,login_time,contact,name,price')
            ->join('left join qwb_card_history on qwb_card_history.uid = qwb_user.id')
            ->where($map)->limit($limit)->group('qwb_user.id')->select();


        $data = array(
            'show' => $show,
            'user'=> $user_data
        );

        if($user_data === false){
            return -1;
        }else {
            return $data;
        }
    }

    /**
     * 获取指定层级的所有用户
     * @return where 条件
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_family($where = array()){
        $user  = D('User');
        $count = $user->where($where)->count();
        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出
        $user_data  = $user->relation('card_history')->where($where)->limit($limit)->select();

        $data = array(
            'show' => $show,
            'user' => $user_data
        );

        return $data;
    }

    /**
     * 获取族谱详情
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_family_detail($id){
        $map['id'] = $id;
        return D('User')->relation('card_history')->where($map)->find();
    }

    /**
     * 获取用户详情
     * @param $id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_user_detail($id){
        $map['id'] = $id;
        return M('User')->where($map)->find();
    }

    /**
     * 获取用户饭票数量
     * @param $id      用户uid
     * @param $require 提现等级要求0S级,1V级
     * @param $status  提现状态0未体现,1已提现
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_draw_income($id, $require = null, $status){
        if(!empty($require)){
            $map['require'] = $require;
        }
        $map['status'] = $status;
        $map['uid']    = $id;
        return M('Income')->where($map)->sum('income');
    }

    /**
     * 获取用户消费金额
     * @param $uid 用户id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_user_consume($uid){
        $map['uid'] = $uid;
        $map['pay_status'] = 1;
        return M('Order')->where($map)->sum('price');
    }

    /**
     * 获取用户饭票余额(未提取)
     * @param $uid 用户id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function user_f_surplus($uid){
        $map['status'] = 0;
        $map['uid']    = $uid;
        $income_data  = M('Income')->where($map)->sum('income');

        $account_map['uid'] = $uid;
        $account_data = M('Account')->field('account')->where($account_map)->find();

        return $account_data['account']+$income_data;
    }
}