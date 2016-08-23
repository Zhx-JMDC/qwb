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
class CollectModel extends RelationModel{
    //频道模型关联模型
    protected $_link = array(
        'goods'=>array(
            'mapping_type'  =>self::BELONGS_TO,
            'foreign_key'   =>'collect_id',
            'mapping_name'  =>'goods',
            'mapping_fields'=>'pic,name,original_price,selling_price,status,rebate,ratio',
            'as_fields'     =>'pic,name,original_price,selling_price,status,rebate,ratio'
        ),
        'shop'=>array(
            'mapping_type'  =>self::BELONGS_TO,
            'foreign_key'   =>'collect_id',
            'mapping_name'  =>'shop',
            'mapping_fields'=>'pic,shop_name,discount_img',
            'as_fields'     =>'pic,shop_name,discount_img'
        )
    );

    /**
     * 获取用户商品收藏
     * @param type 商品类型（0商品，1商家）
     * @param $uid 用户uid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_collect_goods($uid){
        $map['type']   = '0';
        $map['uid']    = $uid;
        return D('Collect')->relation('goods')->where($map)->select();
    }

    /**
     * 获取用户商家收藏
     * @param type 商品类型（0商品，1商家）
     * @param $uid 用户uid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_collect_shop($uid){
        $map['type']   = '1';
        $map['uid']    = $uid;
        return D('Collect')->relation('shop')->where($map)->select();
    }

    /**
     * 用户收藏操作
     * @param $uid
     * @param $id
     * @param $type
     * @return 已经收藏过了2,收藏成功1,收藏失败0
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function collect($uid, $id, $type){
        //用户是否已经收藏过了
        $map['uid']        = $uid;
        $map['collect_id'] = $id;
        $map['type']       = $type;
        $collect_ret = M('Collect')->where($map)->find();
        if($collect_ret){
            //已经收藏过了
            return 2;
        }else{
            //收藏
            $data = array(
                'type'       => $type,
                'collect_id' => $id,
                'uid'        => $uid,
                'time'       => date('Y-m-d H:i:s',time())
            );
            $ret = M('Collect')->data($data)->add();
            if($ret){
                return 1;
            }else{
                return 0;
            }
        }
    }

    /**
     * 用户取消收藏
     * @param $uid        用户uid
     * @param $collect_id 收藏商品id
     * @param $type       0商品,1商家
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function collect_del($uid, $collect_id, $type){
        $where['uid']  = $uid;
        $where['collect_id'] = $collect_id;
        $where['type'] = $type;
        
        return M('Collect')->where($where)->delete();
    }

    /**
     * 判断用户是否收藏过
     * @param $uid 用户uid
     * @param $id  商品id
     * @return boolean 1已经收藏过,0未收藏过
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function can_collect($uid, $id){
        $map['uid']         = $uid;
        $map['collect_id']  = $id;
        $ret =  M('Collect')->where($map)->find();
        if($ret){
            return 1;
        }else{
            return 0;
        }
    }
}