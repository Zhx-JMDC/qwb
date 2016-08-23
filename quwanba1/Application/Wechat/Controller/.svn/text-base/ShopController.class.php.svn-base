<?php
namespace Wechat\Controller;
use Think\Controller;
use Wechat\Model\GoodsModel;
use Wechat\Model\ShopModel;
use Wechat\Model\GoodsTypeModel;
use Wechat\Model\UserModel;
use Wechat\Model\CollectModel;
class ShopController extends CommonController {
    protected $goods;
    protected $type;
    protected $user;
    protected $collect;
    protected $shop;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct() {
        parent::__construct();
        $this->goods    = new GoodsModel();
        $this->type     = new GoodsTypeModel();
        $this->shop     = new ShopModel();
        $this->user     = new UserModel();
        $this->collect  = new CollectModel();
    }

    /**
     * 首页商城显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_mall(){
        //所属栏目
        $data       = $this->type->get_goods_type();

        //非活动商品详情
        $goods_data = $this->goods->get_goods('0');

        //广告位
        $ad_data    = $this->goods->get_ad();
        $ad_bottom  = $this->goods->get_ad_all('3');
        $card_data  = $this->goods->get_ad_all('2');

        if($data === false){
            $this->error('异常错误');
        }else{

            //计算当前用户的返利金额
            foreach ($goods_data as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'rebate'){
                        $value[$k] = sprintf("%.2f",$v*$value['ratio']/100);
                    }elseif($k == 'pic'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                    }
                }
                $goods_data[$key] = $value;
            }

            //组合栏目图标地址
            foreach ($data as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'icon'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$value[$k];
                    }elseif($k == 'goods'){
                        foreach ($v as $ke => $va){
                            foreach ($va as $a => $b){
                                if($a == 'rebate'){
                                    $va[$a] = sprintf("%.2f",$b*$va['ratio']/100);
                                }elseif($a == 'pic'){
                                    $va[$a] = C('DOMAIN_NAME').'Uploads'.$b;
                                }
                            }
                            $v[$ke] = $va;
                         }
                      $value[$k] = $v;
                    }
                }
                $data[$key] = $value;
            }

            //组合海报跳转地址
            foreach ($ad_data as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'skip_url'){
                        $value[$k]  = U('Shop/shop_mall_intro').'?id='.$value['skip_url'];
                    }elseif($k == 'path'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                    }
                }
                $ad_data[$key] = $value;
            }

            //组合周游卡跳转地址
            foreach ($card_data as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'skip_url'){
                        //跳转地址

                    }elseif($k == 'path'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                    }
                }
                $card_data[$key] = $value;
            }

            //组合商城首页底部跳转地址
            foreach ($ad_bottom as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'skip_url'){
                        //跳转地址

                    }elseif($k == 'path'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                    }
                }
                $ad_bottom[$key] = $value;
            }

            //不同类型商品根据权重排序
            foreach ($data as $keys=>$values){
                $list = $values['goods'];
                $temp = array();
                foreach ($list as $key => $row) {
                    $temp[$key]  = $row['order'];
                }
                array_multisort($temp,SORT_DESC, $list);//二位数组排序
                $data[$keys]['goods'] = $list;
            }

            //定位
            $sign_package = $this->get_sign_package();

            $this->assign('signPackage', $sign_package);
            $this->assign('footer_goods','footer_bottom-Clicked');
            $this->assign('ad',$ad_data);
            $this->assign('ad_bottom',$ad_bottom);
            $this->assign('ad_card',$card_data);
            $this->assign('goods',$data);
            $this->assign('goods_data',$goods_data);
            $this->display('Shop/shopMall');
        }
    }

    /**
     * 首页商城商品详情页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function shop_mall_intro(){
        $id        = I('get.id');
        $uid       = get_uid();
        $latitude  = session('latitude');
        $longitude = session('longitude');

        $data = $this->goods->get_goods_detail($id);
        $play_count = count($data['goods_play']);
        //轮播图地址
        foreach ($data['goods_play'] as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'path'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $data['goods_play'][$key] = $value;
        }
        //文本转义
        $data['introduce'] = htmlspecialchars_decode($data['introduce']);
        $data['content'] = htmlspecialchars_decode($data['content']);

        //计算距离
        $data['distance'] = (int)(getDistance($latitude,$longitude,$data['latitude'],$data['longitude'])/1000);

        //判断商品是否收藏过
        $collect_ret = $this->collect->can_collect($uid,$data['id']);

        $this->assign('collect',$collect_ret);
        $this->assign('count',$play_count);
        $this->assign('goods',$data);
        $this->display('Shop/shopMallIntro');
    }


    /**
     * 购买资料填写显示页
     * @param price_type 用户选择价格规格类型id
     * @param id         用户选择商品的id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function buy_ticket(){
        $id         = I('post.id');
        $price_type = I('post.shoplltroSelect-kind');

        $data = $this->goods->get_goods_detail($id);

        if($data == false){
            $this->error("异常错误");
        }else{
            foreach ($data['goods_price'] as $key=>$value){
                foreach ($value as $k=>$v){
                    if ($k == 'id'){
                        if($v == $price_type){
                            $choose_value = $value['selling_value'];
                        }
                    }
                }
                $data['goods_price'][$key] = $value;
            }

            $this->assign('goods',$data);
            $this->assign('choose_value',$choose_value);
            $this->display('Shop/buyTicket');
        }
    }


    /******************************************************************************************
     * app-首页商城显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_shop_mall(){
        $latitude  = I('get.latitude');
        $longitude = I('get.longitude');
        session('latitude', $latitude);
        session('longitude', $longitude);

        //所属栏目
        $data       = $this->type->get_goods_type();

        //非活动商品详情
        $goods_data = $this->goods->get_goods('0');

        //广告位
        $ad_data    = $this->goods->get_ad();
        $ad_bottom  = $this->goods->get_ad_all('3');
        $card_data  = $this->goods->get_ad_all('2');

        if($data === false){
            $this->error('异常错误');
        }else{

            //计算当前用户的返利金额
            foreach ($goods_data as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'rebate'){
                        $value[$k] = sprintf("%.2f",$v*$value['ratio']/100);
                    }elseif($k == 'pic'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                    }
                }
                $goods_data[$key] = $value;
            }

            //组合栏目图标地址
            foreach ($data as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'icon'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$value[$k];
                    }elseif($k == 'goods'){
                        foreach ($v as $ke => $va){
                            foreach ($va as $a => $b){
                                if($a == 'rebate'){
                                    $va[$a] = sprintf("%.2f",$b*$va['ratio']/100);
                                }elseif($a == 'pic'){
                                    $va[$a] = C('DOMAIN_NAME').'Uploads'.$b;
                                }
                            }
                            $v[$ke] = $va;
                        }
                        $value[$k] = $v;
                    }
                }
                $data[$key] = $value;
            }

            //组合海报跳转地址
            foreach ($ad_data as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'skip_url'){
                        $value[$k]  = U('Shop/app_shop_mall_intro').'?id='.$value['skip_url'];
                    }elseif($k == 'path'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                    }
                }
                $ad_data[$key] = $value;
            }

            //组合周游卡跳转地址
            foreach ($card_data as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'skip_url'){
                        //跳转地址

                    }elseif($k == 'path'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                    }
                }
                $card_data[$key] = $value;
            }

            //组合商城首页底部跳转地址
            foreach ($ad_bottom as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'skip_url'){
                        //跳转地址

                    }elseif($k == 'path'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$value['path'];
                    }
                }
                $ad_bottom[$key] = $value;
            }

            //不同类型商品根据权重排序
            foreach ($data as $keys=>$values){
                $list = $values['goods'];
                $temp = array();
                foreach ($list as $key => $row) {
                    $temp[$key]  = $row['order'];
                }
                array_multisort($temp,SORT_DESC, $list);//二位数组排序
                $data[$keys]['goods'] = $list;
            }

            $this->assign('ad',$ad_data);
            $this->assign('footer_goods','footer_bottom-Clicked');
            $this->assign('ad_bottom',$ad_bottom);
            $this->assign('ad_card',$card_data);
            $this->assign('goods',$data);
            $this->assign('goods_data',$goods_data);
            $this->display('Shop/app_shopMall');
        }
    }

    /**
     * 首页商城商品详情页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_shop_mall_intro(){
        $id        = I('get.id');
        $uid       = get_uid();
        $latitude  = session('latitude');
        $longitude = session('longitude');

        $data = $this->goods->get_goods_detail($id);
        $play_count = count($data['goods_play']);
        //轮播图地址
        foreach ($data['goods_play'] as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'path'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $data['goods_play'][$key] = $value;
        }
        //文本转义
        $data['introduce'] = htmlspecialchars_decode($data['introduce']);
        $data['content'] = htmlspecialchars_decode($data['content']);

        //判断商品是否收藏过
        $collect_ret = $this->collect->can_collect($uid,$data['id']);

        //计算距离
        $data['distance'] = (int)(getDistance($latitude,$longitude,$data['latitude'],$data['longitude'])/1000);

        $this->assign('collect',$collect_ret);
        $this->assign('count',$play_count);
        $this->assign('goods',$data);
        $this->display('Shop/app_shopMallIntro');
    }

    /**
     * 购买资料填写显示页
     * @param price_type 用户选择价格规格类型id
     * @param id         用户选择商品的id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_buy_ticket(){
        $id         = I('post.id');
        $price_type = I('post.shoplltroSelect-kind');

        $data = $this->goods->get_goods_detail($id);

        if($data == false){
            $this->error("异常错误");
        }else{
            foreach ($data['goods_price'] as $key=>$value){
                foreach ($value as $k=>$v){
                    if ($k == 'id'){
                        if($v == $price_type){
                            $choose_value = $value['selling_value'];
                            $choose_id    = $value['id'];
                        }
                    }
                }
                $data['goods_price'][$key] = $value;
            }

            $this->assign('goods',$data);
            $this->assign('choose_value',$choose_value);
            $this->assign('choose_id',$choose_id);
            $this->display('Shop/app_buyTicket');
        }
    }

    /**
     * app-搜索
     * @param keyword 搜索关键字
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_search(){
        $uid       = get_uid();
        $params    = I('get.');
        $keyword   = $params['keyword'];
        $latitude  = $params['latitude'];
        $longitude = $params['longitude'];
        session('longitude', $latitude);
        session('latitude', $longitude);

        //添加搜索记录
        if(!empty($keyword)){
            $search_map['uid'] = $uid;
            $search_data['keyword'] = $keyword;
            $search_data['time']    = date('Y-m-d H:i:s');
            $search_data['uid']     = $uid;
            M('Search')->where($search_map)->add($search_data);
        }

        //判断关键字
        if($keyword != ''){
            $map['name'] = array('like', '%'.$keyword.'%');
            $count = 3;
            $goods_data = $this->goods->get_goods_page(1, $count, $map);

            //计算当前用户的返利金额
            $ratio = $this->user->get_ration();
            foreach ($goods_data as $key => $value){
                foreach ($value as $k => $v){
                    if($k == 'rebate'){
                        $value[$k] = sprintf("%.2f",$v*$ratio['ratio']/100);
                    }elseif($k == 'pic'){
                        $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                    }
                }
                $goods_data[$key] = $value;
            }

            $this->assign('goods', $goods_data);

            //没有商品或者商品少于3,则加载商家
            $goods_count = M('Goods')->where($map)->count();
            $shop_map['shop_name']  = array('like', '%'.$keyword.'%');
            $shop_map['conditions'] = 1;
            $shop_count = M('Shop')->where($shop_map)->count();

            $this->assign('_count', $shop_count+$goods_count);
            if(empty($goods_data) || $goods_count <= $count){

                $shop_data = $this->shop->get_shop_page(1, 3, $shop_map);
                $shop_data = pic_replace($shop_data, ['discount_img','pic']);

                //用户所在地理位置
                foreach($shop_data as $key=>$value){
                    foreach ($value as $k=>$v){
                        $value['distance'] = (int)(getDistance($latitude,$longitude,$value['latitude'],$value['longitude'])/1000);
                    }
                    $shop_data[$key] = $value;
                }
                $this->assign('shop', $shop_data);
            }
        }

        //获取商家分类
        $class_data = $this->shop->get_shop_class();
        $class_data = pic_replace($class_data, ['icon']);

        //获取搜索关键字
        $map['uid'] = $uid;
        $search_data = M('Search')->field('keyword')->where($map)->order('time desc')->select();

        //获取海报
        $ad_map['position'] = 5;
        $ad_data = M('Advertisement')->field('path,skip_url')->where($ad_map)->find();
        $ad_data['path'] = C('DOMAIN_NAME').'Uploads'.$ad_data['path'];

        $this->assign('keyword', $keyword);
        $this->assign('id', $uid);
        $this->assign('ad', $ad_data);
        $this->assign('search', $search_data);
        $this->assign('class', $class_data);
        $this->display('Shop/app_shopSelect');
    }

    /**
     * 下拉加载  app-搜索
     * @param keyword 搜索关键字
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_search_more(){
        $keyword   = I('post.keyword');
        $count     = I('post.count');
        $page      = I('post.page');
        $latitude  = session('longitude');
        $longitude = session('latitude');

        $map['name']       = array('like', '%'.$keyword.'%');
        $goods_data = $this->goods->get_goods_page($page, $count, $map);

        //计算当前用户的返利金额
        foreach ($goods_data as $key => $value){
            foreach ($value as $k => $v){
                if($k == 'rebate'){
                    $value[$k] = sprintf("%.2f",$v*$value['ratio']/100);
                }elseif($k == 'pic'){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
            $goods_data[$key] = $value;
        }

        //没有商品或者商品少于3,则加载商家
        $count_map['conditions'] = 1;
        $count_map['name']       = array('like', '%'.$keyword.'%');
        $shop_count = M('Goods')->where($count_map)->count();
        //初始加载三条
        $shop_count -= 3;
        if(empty($goods_data) || $shop_count <= $count){
            $shop_map['shop_name'] = array('like', '%'.$keyword.'%');
            $shop_data = $this->shop->get_shop_page($page, 3, $shop_map);
            $shop_data = pic_replace($shop_data, ['discount_img','pic']);

            //用户所在地理位置
            foreach($shop_data as $key=>$value){
                foreach ($value as $k=>$v){
                    $value['distance'] = (int)(getDistance($latitude,$longitude,$value['latitude'],$value['longitude'])/1000);
                }
                $shop_data[$key] = $value;
            }
        }

        $data['goods'] = $goods_data;
        $data['shop'] = $shop_data;

        $this->ajaxReturn($data);
    }

    /**
     * 清除搜索记录
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function app_search_clear(){
        $uid = get_uid();

        $map['uid'] = $uid;
        $ret = M('Search')->where($map)->delete();

        if($ret === false){
            $this->error("异常错误");
        }else{
            $this->ajaxReturn(1);
        }
    }
}