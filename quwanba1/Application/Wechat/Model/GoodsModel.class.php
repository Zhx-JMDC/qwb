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
class GoodsModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'goods_detail'=>array(
            'mapping_type'  =>self::HAS_ONE,
            'foreign_key'   =>'goods_id',
            'mapping_name'  =>'goods_detail',
            'mapping_fields'=>'address,contact,introduce,content',
            'as_fields'     =>'address,contact,introduce,content'
        ),
        'goods_price'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'goods_id',
            'mapping_name'  =>'goods_price',
            'mapping_fields'=>'id,original_value,original_value,selling_value,name',
            'as_fields'     =>'id,original_value,original_value,selling_value,name'
        ),
        'goods_play'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'goods_id',
            'mapping_name'  =>'goods_play',
            'mapping_fields'=>'path',
            'as_fields'     =>'path'
        ),
        'goods_type'=>array(
            'mapping_type'  =>self::BELONGS_TO,
            'foreign_key'   =>'type_id',
            'mapping_name'  =>'goods_type',
            'mapping_fields'=>'name as type_name,hint',
            'as_fields'     =>'type_name,hint'
        ),
        'shop'=>array(
            'mapping_type'  =>self::BELONGS_TO,
            'foreign_key'   =>'shop_id',
            'mapping_name'  =>'shop',
            'mapping_fields'=>'shop_name,wechat_num',
            'as_fields'     =>'shop_name,wechat_num'
        ),
    );

    /**
     * 获取联盟商家商品
     * @param $type 0普通，疯抢1，热门3，推荐2
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_goods($type = null){
        $map['conditions'] = '1';
        $map['type_id']    = $type;
        return M('Goods')->field('id,ratio,original_price,selling_price,name,pic,rebate')->where($map)->select();
    }

    /**
     * 分页获取联盟商家商品
     * @param $type   0普通，疯抢1，热门3，推荐2
     * @param $page   当前页数
     * @param $count  每页显示数量
     * @param $map    array 查询条件
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_goods_page($page, $count, $map = array()){
        $map['conditions'] = '1';
        return M('Goods')->field('id,status,type_id,original_price,selling_price,name,pic,rebate')->where($map)
            ->limit(($page-1)*$count, $count)->select();
    }

    /**
     * 获取指定商品详情
     * @param $id 商品id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_goods_detail($id){
        $map['id'] = $id;
        return D('Goods')->relation(true)->where($map)->find();
    }

    /**
     * 获取指定商品佣金比例
     * @param $id 商品id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_goods_ratio($id){
        $map['id'] = $id;
        return M('Goods')->field('ratio')->where($map)->find();
    }

    /**
     * 获取商城首页广告位(5张顶部广告)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_ad(){
        return M('advertisement')->field('path,skip_url')->where('position = 1')->select();
    }

    /**
     * 获取商城单张广告位
     * @param $position 广告位置
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_ad_all($position){
        $map['position'] = $position;
        return M('advertisement')->field('path')->where($map)->select();
    }

    /**
     * 获取指定商品的所有价格规格
     * @param $id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_goods_price($id){
        $map['goods_id'] = $id;
        $map['conditions'] = '1';
        return M('Goods_price')->field('id,goods_id,selling_value,name')->where($map)->select();
    }

    /**
     * 获取商品提供商家的openid
     * @param $id 商品id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_openid($id){
        $map['id'] = $id;
        $ret =  D('Goods')->relation('shop')->find();
        return $ret['wechat_num'];
    }

    /**
     * 获取商品返佣金额
     * @param $goods_id 商品id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_goods_rebate($goods_id){
        $map['id'] = $goods_id;
        return M('Goods')->field('rebate')->where($map)->find();
    }
}