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
class GoodsModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'goods_detail'=>array(
            'mapping_type'  =>self::HAS_ONE,
            'foreign_key'   =>'goods_id',
            'mapping_name'  =>'goods_detail',
            'mapping_fields'=>'address,contact,introduce,content',
            'as_fields'     =>'address,contact,introduce,content',
        ),
        'goods_price'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'goods_id',
            'mapping_name'  =>'goods_price',
            'mapping_fields'=>'id,original_value,selling_value,name,inventory',
            'as_fields'     =>'id,original_value,selling_value,name,inventory'
        ),
        'goods_play'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'goods_id',
            'mapping_name'  =>'goods_play',
            'mapping_fields'=>'path,id,play_order',
            'as_fields'     =>'path,id,play_order'
        ),
        'goods_type'=>array(
            'mapping_type'  =>self::BELONGS_TO,
            'foreign_key'   =>'type_id',
            'mapping_name'  =>'goods_type',
            'mapping_fields'=>'name as type_name',
            'as_fields'     =>'type_name'
        ),
        'shop'=>array(
            'mapping_type'  =>self::BELONGS_TO,
            'foreign_key'   =>'shop_id',
            'mapping_name'  =>'shop',
            'mapping_fields'=>'id as shopId,shop_name,wechat_num,province_id,city_id,district_id',
            'as_fields'     =>'shopId,shop_name,wechat_num,province_id,city_id,district_id',
        ),
    );

    /**
     * 获取趣玩商城商品列表
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_goods($map = array(),$order = ''){
        //分页
        $goods = D('Goods');
        $count = $goods
            ->join('left join qwb_shop on qwb_goods.shop_id = qwb_shop.id')
            ->join('left join qwb_goods_type on qwb_goods.type_id = qwb_goods_type.id')
            ->relation('goods_price')
            ->where($map)->count();
        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出

        $goods_data = $goods
            ->field('qwb_shop.shop_name,qwb_goods.id,qwb_goods.name,province_id,city_id,district_id,qwb_goods_type.name type_name,original_price,selling_price,qwb_goods.conditions,qwb_goods.order')
            ->join('left join qwb_shop on qwb_goods.shop_id = qwb_shop.id')
            ->join('left join qwb_goods_type on qwb_goods.type_id = qwb_goods_type.id')
            ->relation('goods_price')
            ->where($map)
            ->limit($limit)
            ->order($order)
            ->select();

        $data = array(
            'show' => $show,
            'goods'=> $goods_data
        );

        if($goods_data === false){
            return -1;
        }else {
            return $data;
        }
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
     * 商家基本信息添加
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_add($data){
        $goods_data = array(
            'name'           => $data['name'],
            'conditions'     => $data['conditions'],
            'type_id'        => $data['type_id'],
            'order'          => $data['order'],
            'original_price' => $data['original_price'],
            'selling_price'  => $data['selling_price'],
            'shop_id'        => $data['shopId'],
            'rebate'         => $data['rebate'],
            'status'         => $data['status'],
            'ratio'          => $data['ratio'],
            'longitude'      => $data['longitude'],
            'latitude'       => $data['latitude']
        );

        //关联写入(去除多余数据)
        $goods_data['goods_detail'] = array(
            'goods_id'=> '',
            'contact'  => $data['contact'],
            'address'  => $data['address'],
            'introduce'=> $data['introduce'],
            'content'  => $data['content']
        );
        return D('Goods')->relation('goods_detail')->add($goods_data);
    }

    /**
     * 删除商品轮播图
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_play_delete($data){
        $map['id'] = array('in', $data);
        return M('Goods_play')->where($map)->delete();
    }

    /**
     * 修改商品基本信息
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_edit($data){
        $where['id'] = $data['id'];
        $goods_data = array(
            'name'           => $data['name'],
            'conditions'     => $data['conditions'],
            'type_id'        => $data['type_id'],
            'order'          => $data['order'],
            'original_price' => $data['original_price'],
            'selling_price'  => $data['selling_price'],
            'shop_id'        => $data['shopId'],
            'rebate'         => $data['rebate'],
            'status'         => $data['status'],
            'ratio'          => $data['ratio'],
            'longitude'      => $data['longitude'],
            'latitude'       => $data['latitude']
        );

        //关联写入(去除多余数据)
        $goods_data['goods_detail'] = array(
            'contact'  => $data['contact'],
            'address'  => $data['address'],
            'introduce'=> $data['introduce'],
            'content'  => $data['content']
        );
        return D('Goods')->relation('goods_detail')->where($where)->save($goods_data);
    }

    /**
     * 删除商品基本信息
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_del($id){
        return D('Goods')->relation('goods_detail')->delete($id);
    }

    /**
     * 改变商品状态
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function status_change($id){
        $where['id'] = $id;

        $data = M('Goods')->where($where)->select();
        //修改商品状态
        foreach ($data as $key => $value){
            foreach ($value as $k => $v){
                if ($k == 'conditions'){
                    //如果是下架状态则修改为上架状态,如果是上架状态就改为下架
                    if ($value[$k] == 0){
                        $value[$k] = 1;
                    }else{
                        $value[$k] = 0;
                    }
                }
            }
            $data[$key] = $value;
            return M('Goods')->where($where)->save($data[$key]);
        }

    }

    /**
     * 获得商品图片地址
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_pic_path($id){
        $where['id'] = $id;
        $data = M('Goods')->field('pic')->where($where)->find();
        return $data['pic'];
    }

    /**
     * 获取轮播图片地址
     * @return string
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function goods_play_path($data){
        if (!is_array($data)){
            $where['shop_id'] = $data;
        }else{
            $where['id'] = array('in', $data);
        }
        $result = M('Goods_play')->field('path')->where($where)->select();
        foreach ($result as $key => $value){
            $result[$key] = $value['path'];
        }
        return $result;
    }

}