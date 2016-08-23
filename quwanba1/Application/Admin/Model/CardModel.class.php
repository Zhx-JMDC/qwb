<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 16/7/1
 * Time: 上午10:58
 */
namespace Admin\Model;
use Think\Model;
use Think\Model\RelationModel;
class CardModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
//        'shop'=>array(
//            'mapping_type'  =>self::BELONGS_TO,
//            'foreign_key'   =>'shop_id',
//            'mapping_name'  =>'shop',
//            'mapping_fields'=>'id as shopId,shop_name,wechat_num,province_id,city_id,district_id',
//            'as_fields'     =>'shopId,shop_name,wechat_num,province_id,city_id,district_id',
//        ),
    );

    /**
     * 获取趣玩商城商品列表
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_card(){
        return M('Card')->field('type,value,name,keep_ratio,rebate')->select();
    }

    /**
     * 获取订单列表
     * @param $map 查询条件
     * @return array|int
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function card_transaction($map){
        $map['status'] = '1';
        $card  = M('Card_history');
        $count = $card->where($map)->count();
        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出
        $card_data = $card->field('id,out_trade_no,card_id,send_status,contact,name,postcode,address,time,express_num,price')
            ->where($map)->limit($limit)->select();

        $data = array(
            'show' => $show,
            'card'=> $card_data
        );

        if($card_data === false){
            return -1;
        }else {
            return $data;
        }
    }

}