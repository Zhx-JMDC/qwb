<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 16/7/1
 * Time: 上午10:58
 */
namespace Wechat\Model;
use Think\Model;
class UserModel extends Model{
    /**
     * 用户openid获取用户uid
     * @param $openid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_uid($openid){
        $map['openid'] = $openid;
        $ret = M('User')->field('id')->where($map)->find();
        return $ret['id'];
    }

    /**
     * 用户uid获取用户openid
     * @param $uid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_openid($uid){
        $map['id'] = $uid;
        $ret = M('User')->field('openid')->where($map)->find();
        return $ret['openid'];
    }

    /**
     * 添加用户信息(默认非代言人)
     * @param open_id        openid
     * @param subscribe_time 关注时间
     * @param subscribe      是否订阅0未关注,1关注
     * @param nickname       用户昵称
     * @param groupid        在所在分组id
     * @param province       省份
     * @param city           城市
     * @param country        国家
     * @param sex            性别
     * @param headimgurl     头像地址
     * @param unionid        union机制
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function add_user_message($data){
        $unionid = $data['unionid'];

        $user   = M('User');
        $map['unionid'] = $unionid;
        $user_ret = $user->where($map)->find();

        if($user_ret){
            //用户已存在,更新登录时间
            $data['login_time'] = date('Y-m-d H:i:s',time());
            $where['unionid'] = (string)$unionid;
            $user->where($where)->save($data);
            return $user_ret['id'];
        }else{

            $user->startTrans();

            //用户不存在,添加用户
            $data['login_time'] = date('Y-m-d H:i:s',time());
            $ret  = $user->data($data)->add();

            //创建账户
            $account_data['uid'] = $ret;
            $account_ret = M('Account')->add($account_data);

            //创建唯一ID
            $data['qwb_id']   = 8008000+$ret;
            $where['unionid'] = (string)$unionid;
            $user->where($where)->save($data);

            if($ret !== false && $account_ret !== false){
                $user->commit();
                return $ret;
            }else {
                $user->rollback();
                $this->error('异常错误');
            }
        }
    }

    /**
     * 用户唯一二维码是否存在
     * @param $openid 用户openid
     * @return boolean false存在二维码,无需生成
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function exit_QRcode($openid){
        $map['openid'] = (string)$openid;
        $ret = M('User')->field('id,qr_code')->where($map)->find();
        if($ret['qr_code'] == ''){
            return $ret['id'];
        }else{
            return false;
        }
    }

    /**
     * 保存用户二维码
     * @param $id
     * @param $path
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function update_QRcode($id, $path){
        $map['qr_code'] = $path;
        $where['id']    = $id;
        M('User')->where($where)->save($map);
    }

    /**
     * 扫描参数二维码成为下线(用户无关注公众号)
     * @param $id     参数值(被扫者uid)
     * @param $openid 扫描者openid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function add_user($id, $openid){
        $user      = M('User');
        //查询用户是否有父级
        $user_map['openid'] = (string)$openid;
        $user_ret = $user->field('id,fid,nickname')->where($user_map)->find();

        //查询用户是否有子级
        $user_where['fid'] = $user_ret['id'];
        $son_user = $user->where($user_where)->select();

        if((string)$id != $user_ret['id']){
            //自己不能发展为自己的下线
            if($user_ret !== false && $user_ret['fid'] == 0 && $son_user !== false && !$son_user){
                //用户不存在,创建用户,已经存在不作操作
                $map['id'] = $id;
                $ret = $user->field('float,fid,tier')->where($map)->find();

                $where['openid'] = (string)$openid;
                $data['fid']  = (int)$id;
                $data['gid']  = $ret['fid'];
                $data['tier'] = $ret['tier']+1;
                $user->where($where)->data($data)->save();

                //修改被扫者浮动关系
                if($ret['float'] == 0){
                    $float_data['float'] = 1;
                    $user->where($map)->data($float_data)->save();
                }

                //储存下线发展消息
                if((int)$id != 0){
                    //当前用户上线,消息通知
                    $fid_content['uid']        = (int)$id;
                    $fid_content['message']    = $user_ret['nickname'].'成为你的饭友';
                    $fid_content['time']       = date('Y-m-d H:i:s', time());
                    $fid_content['provide_id'] = $user_ret['id'];
                    $fid_content['type']       = 1;
                    M('Message')->data($fid_content)->add();
                }
                if($ret['fid'] != 0){
                    //当前用户上上线,消息通知
                    $gid_content['uid']        = $ret['fid'];
                    $gid_content['message']    = $user_ret['nickname'].'成为你的饭圈';
                    $gid_content['time']       = date('Y-m-d H:i:s', time());
                    $gid_content['provide_id'] = $user_ret['id'];
                    $gid_content['type']       = 1;
                    M('Message')->data($gid_content)->add();
                }
            }
        }
    }

    /**
     * 扫描参数二维码成为下线(用户已关注公众号)
     * @param $id     参数值(被扫者uid)
     * @param $openid 扫描者openid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function edit_user($id, $openid){
        $user      = M('User');
        //查询用户是否有父级
        $user_map['openid'] = (string)$openid;
        $user_ret = $user->field('id,fid,nickname')->where($user_map)->find();

        //查询用户是否有子级
        $user_where['fid'] = $user_ret['id'];
        $son_user = $user->where($user_where)->select();

        if((string)$id != $user_ret['id']) {
            if ($user_ret !== false && $user_ret['fid'] == 0 && $son_user !== false && !$son_user) {
                //用户不存在,创建用户,已经存在不作操作
                $map['id'] = $id;
                $ret = $user->field('float,fid,tier')->where($map)->find();

                $where['openid'] = (string)$openid;
                $data['fid'] = (int)$id;
                $data['gid'] = $ret['fid'];
                $data['tier'] = $ret['tier'] + 1;
                $user->where($where)->data($data)->save();

                //修改被扫者浮动关系
                if($ret['float'] == 0){
                    $float_data['float'] = 1;
                    $user->where($map)->data($float_data)->save();
                }

                //储存下线发展消息
                if((int)$id != 0){
                    //当前用户上线,消息通知
                    $fid_content['uid']        = (int)$id;
                    $fid_content['message']    = $user_ret['nickname'].'成为你的饭友';
                    $fid_content['time']       = date('Y-m-d H:i:s', time());
                    $fid_content['provide_id'] = $user_ret['id'];
                    $fid_content['type']       = 1;
                    M('Message')->data($fid_content)->add();
                }
                if($ret['fid'] != 0){
                    //当前用户上上线,消息通知
                    $gid_content['uid']        = $ret['fid'];
                    $gid_content['message']    = $user_ret['nickname'].'成为你的饭圈';
                    $gid_content['time']       = date('Y-m-d H:i:s', time());
                    $gid_content['provide_id'] = $user_ret['id'];
                    $gid_content['type']       = 1;
                    M('Message')->data($gid_content)->add();
                }
            }
        }
    }

    /**
     * 获取用户的参数二维码
     * @param $openid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_user_QRcode($openid){
        $map['openid'] = $openid;
        $ret = M('User')->field('id,qr_code,headimgurl,nickname')->where($map)->find();
        return $ret;
    }

    /**
     * 获取饭友,饭圈
     * @param $uid   当前用户uid
     * @param $where fid饭友(下线),gid饭圈(上线)
     * @param $page  页数
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_firends($uid, $where, $page){
        $count = 3;
        $map[$where] = $uid;
        $ret = M('User')->field('headimgurl,nickname,subscribe_time,qwb_id')->where($map)->limit(($page-1)*$count,$count)->select();
        return $ret;
    }

    /**
     *获取饭友,饭圈总数
     * @param $uid   当前用户uid
     * @param $where fid饭友(下线),gid饭圈(上线)
     * @return mixed
     * @author zhangxiang <zhxjmdc@gmail.com>]
     */
    public function get_firends_count($uid, $where){
        $map[$where] = $uid;
        return M('User')->where($map)->count();
    }

    /**
     * 获取用户基本信息
     * @param $uid 用户uid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_user_message($uid){
        $map['id'] = $uid;
        return M('User')->field('gid,fid,headimgurl,nickname,qwb_id,rank')->where($map)->find();
    }

    /**
     * 获取购买者佣金返佣比例[1-30%(上上级)-10%(上级)] = 购买者返佣比例
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_ration(){
        $map['type'] = '0';
        return M('Ratio')->field('ratio')->where($map)->find();
    }

    /**
     * 升级用户会员等级
     * @param $uid  用户uid
     * @param $type 会员等级0S级,1V级
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function change_rank($uid, $type){
        $map['id']    = $uid;
        $data['rank'] = $type;
        return M('User')->where($map)->save($data);
    }

    /**
     * 用户添加饭票佣金
     * @param $data 佣金
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function add_income($data){
        return M('Income')->data($data)->add();
    }
}