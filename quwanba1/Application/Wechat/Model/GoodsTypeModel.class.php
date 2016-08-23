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
class GoodsTypeModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'goods'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'type_id',
            'mapping_name'  =>'goods',
            'mapping_fields'=>'id,rebate,pic,name,original_price,selling_price,status,order,ratio',
            'as_fields'     =>'id,rebate,pic,name,original_price,selling_price,status,order,ratio',
            'condition'     =>'conditions = 1',
        )
    );

    /**
     * 首页商城
     * @param type 1普通联盟商品,2疯抢,3推荐,4热门
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_goods_type(){
        $map['id'] = array(array('eq',1),array('eq',2),array('eq',3), 'or') ;
        return D('Goods_type')->relation('goods')->where($map)->select();
    }
}