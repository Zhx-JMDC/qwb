<?php
namespace Wechat\Model;
use Think\Model;

class IncomeModel extends Model{
    /**
     * 获取饭票收入记录
     * @param $uid
     * @param $page  页数
     * @param $count 每页显示条数
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_income_all($uid, $page, $count){
        $map['uid'] = $uid;
        return M('Income')->field('time,type,income,nickname,headimgurl')
            ->join('left join qwb_user on qwb_income.provid_uid = qwb_user.id')
            ->where($map)->limit(($page-1)*$count,$count)->select();
    }

    /**
     * 获取饭票提现记录
     * @param $uid
     * @param $page  页数
     * @param $count 每页显示条数
     * @param $type  消息类型
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_withdraw_history($uid, $type ,$page, $count){
        $map['uid']    = $uid;
        $map['status'] = 1;
        $map['type']   = $type;
        return M('Withdraw_history')->field('time,value')->where($map)->limit(($page-1)*$count,$count)->select();
    }

    /**
     * 消息通知
     * @param $uid
     * @param $page  页数
     * @param $count 每页显示条数
     * @param $map   array 条件
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_message($uid, $map = array(), $page, $count){
        $map['uid']  = $uid;
        return M('Message')->field('time,type,message,nickname,headimgurl,value')
            ->join('left join qwb_user on qwb_user.id = qwb_message.provide_id')
            ->where($map)->limit(($page-1)*$count,$count)->order('time desc')->select();
    }
}