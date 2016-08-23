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
class ShopModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'shop_detail'=>array(
            'mapping_type'  =>self::HAS_ONE,
            'foreign_key'   =>'shop_id',
            'mapping_name'  =>'shop_detail',
            'mapping_fields'=>'address,contact,introduce,detail,card_pic',
            'as_fields'     =>'address,contact,introduce,detail,card_pic'
        ),
        'shop_price'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'shop_id',
            'mapping_name'  =>'shop_price',
            'mapping_fields'=>'original_value,selling_value,name',
            'as_fields'     =>'original_value,selling_value,name'
        ),
        'shop_play'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'shop_id',
            'mapping_name'  =>'shop_play',
            'mapping_fields'=>'path',
            'as_fields'     =>'path'
        )
    );

    /**
     * 获取联盟商家种类
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_class(){
        return M('shop_class')->field('id,name,icon')->select();
    }

    /**
     * 获取不通类型商家总数(热门商家，最新商家)
     * @param $type 0热门商家，1最新商家
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_count($type){
        $map['shop_type'] = $type;
        $map['conditions'] = '1';
        return D('Shop')->where($map)->count();
    }

    /**
     * 获取联盟商家商品
     * @param $type  0热门商家，1最新商家
     * @param $page  页数
     * @param $count 每页显示条数
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop($type, $page, $count, $city, $search){
        $map['shop_name']  = array('like','%'.$search.'%');
        $map['shop_type']  = $type;
        $map['city_id']    = get_city_id($city);
        $map['conditions'] = '1';
        return M('Shop')->field('id,discount_img,shop_name,pic,longitude,latitude')
            ->where($map)->order('shop_order desc')->limit(($page-1)*$count,$count)->select();
    }

    /**
     * 获取指定分类所有商品总数
     * @param $id 分类id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_class_count($id){
        $map['shop_class_id'] = $id;
        $map['conditions']    = '1';
        return M('Shop_to_class')->where($map)->count();
    }

    /**
     * 获取指定分类所有商家
     * @param $id
     * @param $page  页数
     * @param $count 每页显示条数
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_class_all($id, $page, $count){
        $map['shop_class_id'] = $id;
        $map['conditions'] = '1';
        return M('Shop_to_class')->field('qwb_shop.id,longitude,latitude,discount_img,shop_name,pic')
            ->join('left join qwb_shop on qwb_shop_to_class.shop_id = qwb_shop.id')
            ->where($map)->order('shop_order desc')->limit(($page-1)*$count,$count)->select();
    }

    /**
     * 获取指定商品详情
     * @param $id 商品id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_detail($id){
        $map['id'] = $id;
        return D('Shop')->relation(true)->where($map)->find();
    }

    /**
     * 根据省份获取城市
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_city($province_name){
        $map['name'] = $province_name;
        $province = M('Province')->field('id')->where($map)->find();
        $where['province_id'] = $province['id'];
        return M('City')->field('id,name')->where($where)->select();
    }

    /**
     * 联盟商家广告位(5轮播)(position 4)
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_ad(){
        return M('advertisement')->field('path,skip_url')->where('position = 4')->select();
    }

    /**
     * 分页获取联盟商家
     * @param $page   当前页数
     * @param $count  每页显示数量
     * @param $map    array 查询条件
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_page($page, $count, $map = array()){
        $map['conditions'] = '1';
        return M('Shop')->field('id,shop_name,pic,discount_img,latitude,longitude')->where($map)
            ->limit(($page-1)*$count, $count)->select();
    }
}