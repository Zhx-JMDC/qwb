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
class CardHistoryModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'Card'=>array(
            'mapping_type'  =>self::BELONGS_TO,
            'foreign_key'   =>'card_id',
            'mapping_name'  =>'Card',
            'mapping_fields'=>'value,type',
            'as_fields'     =>'value,type'
        )
    );
    /**
     * 周游卡交易记录添加
     * @param $data 买家资料
     * @return heh
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_history_add($data){
        $where['type'] = $data['type'];
        $card_data = M('Card')->field('id,value')->where($where)->find();
        if($card_data){
            unset($data['type']);
            $data['card_id'] = $card_data['id'];
            $data['price']   = $card_data['value'];
            return M('Card_history')->data($data)->add();
        }else{
            return 0;
        }
    }

    /**
     * 获取购卡交易详情
     * @param $id 交易记录id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_card_detail($id){
        $map['id'] = $id;
        return D('Card_history')->relation('Card')->where($map)->find();
    }

    /**
     * 获取购卡交易详情
     * @param $num 交易订单号
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function num_card_detail($num){
        $map['order_num'] = $num;
        return D('Card_history')->relation('Card')->where($map)->find();
    }

    /**
     * 交易支付完成
     * @param $id           购卡id
     * @param $out_trade_no 微信平台商户订单号
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_history_update($id, $out_trade_no){
        $map['status']       = 1;
        $map['out_trade_no'] = $out_trade_no;
        $where['id'] = $id;
        return M('Card_history')->where($where)->save($map);
    }
}