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
class ShopModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'goods'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'shop_id',
            'mapping_name'  =>'goods',
            'mapping_fields'=>'name as goods_name',
            'as_fields'     =>'goods_name'
        ),
        'shop_play'=>array(
            'mapping_type'  =>self::HAS_MANY,
            'foreign_key'   =>'shop_id',
            'mapping_name'  =>'shop_play',
            'mapping_fields'=>'id,path,play_order',
            'as_fields'     =>'id,path,play_order'
        ),
        'shop_detail'=>array(
            'mapping_type'  => self::HAS_ONE,
            'foreign_key'   =>'shop_id',
            'mapping_name'  =>'shop_detail',
            'mapping_fields'=>'shop_id,address,contact,introduce,detail,card_pic',
            'as_fields'     =>'shop_id,address,contact,introduce,detail,card_pic'
        )
    );

    /**
     * 获取趣玩商城商家列表
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop($map = array()){
        //分页
        $shop = D('Shop');
        $count = $shop->where($map)->count();
        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出
        $shop_data  = $shop->relation('goods')->where($map)->limit($limit)->select();

        $data = array(
            'show' => $show,
            'goods'=> $shop_data
        );

        if($shop_data){
            return $data;
        }else {
            return -1;
        }
    }

    /**
     * 获取指定商家详情
     * @param $id 商家id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_detail($id){
        $map['id'] = $id;
        return D('Shop')->relation(true)->where($map)->find();
    }

    /**
     * 获取所有商家分类
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_type(){
        return M('Shop_class')->field('id,name')->select();
    }

    /**
     * 商家基本信息添加
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_add($data){
        $shop_data = array(
            'shop_name'       => $data['name'],
            'discount'   => $data['discount'],
            'conditions' => $data['conditions'],
            'shop_type'  => $data['shop_type'],
            'province_id'=> $data['province_id'],
            'district_id'=> $data['district_id'],
            'buy_url'    => $data['buy_url'],
            'wechat_num' => $data['wechat_num'],
            'longitude'  => $data['longitude'],
            'latitude'   => $data['latitude'],
            'city_id'    => $data['city_id'],
            'shop_order' => $data['shop_order']
        );

        //关联写入(去除多余数据)
        $shop_data['shop_detail'] = array(
            'shop_id'  => '',
            'contact'  => $data['contact'],
            'address'  => $data['address'],
            'introduce'=> $data['introduce'],
            'detail'   => $data['detail']
        );
        return D('Shop')->relation('shop_detail')->add($shop_data);
    }

    /**
     * 删除商家轮播图
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_play_delete($data){
        $map['id'] = array('in', $data);
        return M('Shop_play')->where($map)->delete();
    }

    /**
     * 修改商家基本信息
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_edit($data){
        $where['id'] = $data['id'];
        $shop_data = array(
            'shop_name'  => $data['shop_name'],
            'discount'   => $data['discount'],
            'conditions' => $data['conditions'],
            'shop_type'  => $data['shop_type'],
            'buy_url'    => $data['buy_url'],
            'wechat_num' => $data['wechat_num'],
            'longitude'  => $data['longitude'],
            'latitude'   => $data['latitude']
        );

        if($data['province_id'] != ''){
            $shop_data['province_id'] = $data['province_id'];
        }
        if($data['city_id'] != ''){
            $shop_data['city_id'] = $data['city_id'];
        }
        if($data['district_id'] != ''){
            $shop_data['district_id'] = $data['district_id'];
        }

        //关联更新(去除多余数据)
        $shop_data['shop_detail'] = array(
            'contact'  => $data['contact'],
            'address'  => $data['address'],
            'introduce'=> $data['introduce'],
            'detail'   => $data['detail']
        );
        return D('Shop')->relation('shop_detail')->where($where)->save($shop_data);
    }

    /**
     * 删除商家基本信息
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_del($id){
        return D('Shop')->relation('shop_detail')->delete($id);
    }

    /**
     * 获取联盟商家种类
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_shop_class(){
        return M('shop_class')->field('id,name,icon')->select();
    }

    /**
     * 获取指定分类详情
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_class_detail($id){
        $map['id'] = $id;
        return M('shop_class')->field('id,name,icon')->where($map)->find();
    }

    /**
     * 获取指定分类所有商家
     * @param $id 商家分类id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_class_all($id){
        $map['shop_class_id'] = $id;
        return M('shop_to_class')->field('qwb_shop.id,shop_name,shop_order')
            ->join('left join qwb_shop on qwb_shop_to_class.shop_id = qwb_shop.id')
            ->where($map)->select();
    }

    /**
     * 获取折扣图片地址
     * @return string
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_dis_path($id){
        $where['id'] = $id;
        $data = M('Shop')->field('discount_img')->where($where)->find();
        return $data['discount_img'];
    }

    /**
     * 获取海报图片地址
     * @return string
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_pic_path($id){
        $where['id'] = $id;
        $data = M('Shop')->field('pic')->where($where)->find();
        return $data['pic'];
    }

    /**
     * 获取轮播图片地址
     * @return string
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_play_path($data){
        if (!is_array($data)){
            $where['shop_id'] = $data;
        }else{
            $where['id'] = array('in', $data);
        }
        $result = M('Shop_play')->field('path')->where($where)->select();
        foreach ($result as $key => $value){
            $result[$key] = $value['path'];
        }
        return $result;
    }

    /**
     * 获取指定商家拥有的标签
     * @param $shop_id 商家id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_has_class($shop_id){
        $map['shop_id'] = $shop_id;
        return M('Shop_to_class')->field('qwb_shop_class.id,name')->where($map)
            ->join('left join qwb_shop_class on qwb_shop_class.id = qwb_shop_to_class.shop_class_id')
            ->select();
    }

    /**
     * 商家excel导出
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_export($map){
        return D('Shop')->relation('goods')->where($map)->select();
    }

    /**
     * 获取分类拥有商家数量
     * @param $id 分类id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function class_shop_count($id){
        $map['shop_class_id'] = $id;
        return M('shop_to_class')->join('left join qwb_shop on qwb_shop_to_class.shop_id = qwb_shop.id')
            ->where($map)->count();
    }
}