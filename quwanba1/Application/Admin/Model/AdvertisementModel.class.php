<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/7
 * Time: 17:38
 */

namespace Admin\Model;
use Think\Model;

class AdvertisementModel extends Model{

    /**
     * 获取联盟商家轮转页
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function alliance_shop_ad($position){
        $map['position'] = $position;
        return M('Advertisement')->where($map)->select();
    }

//    /**
//     * 轮转页删除
//     * @author zhangxiang <zhxjmdc@gmail.com>
//     */
//    public function ad_del($id){
//        $map['id'] = $id;
//        return M('Advertisement')->where($map)->delete();
//    }

//    /**
//     * 广告修改操作
//     * @author zhangxiang <zhxjmdc@gmail.com>
//     */
//    public function ad_edit($position, $data){
//        $map['position'] = $position;
//        $map['id']       = $data['id'];
//        return M('Advertisement')->where($map)->data($data)->save();
//    }

    /**
     * 广告获取操作
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ads_gets(){
        $map['position'] = array(array('EQ',2),array('EQ',3), 'or') ;
        //分页
        $ads = M('Advertisement');
        $count = $ads->where($map)->count();
        $Page  = new \Think\Page($count,2);
        $limit = $Page->firstRow.','.$Page->listRows;
        $show  = $Page->show();// 分页显示输出
        
        $ads_data = $ads->limit($limit)->where($map)->select();
        
        $data = array(
            'show' => $show,
            'ads' =>$ads_data
        );
        
        if ($ads_data ===false){
            return -1;
        }else{
            return $data;
        }
    }

    /**
     * 广告基本信息添加
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_add($data){
        $ad_data = array(
            'discribe'           => $data['name'],
            'position'     => $data['positions'],
            'order'          => $data['order'],
            'skip_url'       => $data['address'],
        );
        return M('Advertisement')->add($ad_data);
    }

    /**
     * 获取指定广告详情
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_detail($id){
        $map['id'] = $id;
        return M('Advertisement')->where($map)->find();
    }

    /**
     * 修改广告基本信息
     * @param $position 广告位位置(1商城首页顶部轮播,2周游卡广告位，3商城首页底部广告位，4联盟商城顶部轮播)
     * @param $data     广告位基本信息
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_edit($position, $data){
        $where['id'] = $data['id'];
        $ad_data = array(
            'discribe'     => $data['discribe'],
            'order'        => $data['order'],
            'skip_url'     => $data['skip_url'],
            'position'     => $position,
        );
        return M('Advertisement')->where($where)->save($ad_data);
    }

    /**
     * 修改图标信息
     * @param $data Icon基本信息
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function icon_edit($data){
        $where['id'] = $data['id'];
        $icon_data['name'] = $data['name'];
        return M('Goods_type')->where($where)->save($icon_data);
    }
    
    /**
     * 删除广告基本信息
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_del($id){
        return M('Advertisement')->delete($id);
    }

    /**
     * 获得广告图片地址
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function ad_pic_path($id){
        $where['id'] = $id;
        $data = M('Advertisement')->field('path')->where($where)->find();
        return $data['path'];
    }
}