<?php
namespace  Admin\Controller;
use Think\Controller;
use Admin\Model\UserModel;
class FamilyController extends AdminController
{
    protected $user;

    /**
     * 构造方法，实例化操作模型
     */
    public function __construct()
    {
        parent::__construct();
        $this->user = new UserModel();
    }

    /**
     * 族谱一级显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index(){
        $params = I('get.');

        if(!empty($params['type']) && $params['type'] == 2){
            $where['fid']   = 0;
            $where['float'] = 0;
            $this->assign('type',2);
        }
        if(!empty($params['nickname'])){
            $where['nickname'] = $params['nickname'];
            $this->assign('nickname',1);
        }

        $data = $this->user->get_family($where);


        $this->assign('_page',$data['show']);
        $this->assign('_list',$data['user']);
        $this->display('Family/index');
    }

    /**
     * 族谱详情
     * @param id 用户uid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_family_detail(){
        $id   = I('get.id');
        $data = $this->user->get_family_detail($id);
        if($data === false){
            $this->error('异常错误');
        }else{
            //获取上级代理
            $father_data  = $this->user->get_family_detail($data['fid']);
            //获取上二级代理
            $grandpa_data = $this->user->get_family_detail($father_data['fid']);
            //获取下一级代理的数量
            $son_data  = M('User')->where('fid = '.$data['id'])->select();
            $son_count = count(array_filter($son_data));
            //获取下二级代理数量
            $grandson_data  = M('User')->where('gid = '.$data['id'])->select();
            $grandson_count = count(array_filter($grandson_data));
        }

        //是否为浮动用户
        if($data['fid'] == 0 && $data['float'] == 0){
            //浮动用户
            $data['float_flag'] = 0;
        }else{
            //普通用户
            $data['float_flag'] = 1;
        }

        $this->assign('son_count', $son_count);
        $this->assign('grandson_count', $grandson_count);
        $this->assign('user', $data);
        $this->assign('grandpa',$grandpa_data);
        $this->assign('father', $father_data);
        $this->display('Family/detail');
    }

    /**
     * 获取子一级列表
     * @param id 用户id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_son_list(){
        $id = I('get.id');
        $where['fid'] = $id;
        $data = $this->user->get_family($where);
        $this->assign('_page',$data['show']);
        $this->assign('_list',$data['user']);
        $this->display('Family/index');
    }

    /**
     * 获取子二级列表
     * @param id 用户id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function get_grandson_list(){
        $id = I('get.id');
        $where['gid'] = $id;
        $data = $this->user->get_family($where);
        $this->assign('_page',$data['show']);
        $this->assign('_list',$data['user']);
        $this->display('Family/index');
    }

    /**
     * 用户归类
     * @param id  归到用户id下
     * @param uid 当前用户uid
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function user_edit(){
        $id   = I('post.id');
        $uid  = I('post.uid');
        $rank = I('post.rank');

        //修改用户等级
        $rank_map['id']    = $uid;
        $rank_data['rank'] = $rank;
        M('User')->where($rank_map)->save($rank_data);

        if(empty($id)){
            $this->index(); exit();
        }

        if(!is_numeric(trim($id))){
            $this->error("参数错误"); exit();
        }

        $user = M('User');

        //获取归类用户信息
        $map['id'] = $id;
        $user_data = $user->field('id,fid,tier')->where($map)->find();


        $user->startTrans();

        //修改归类用户浮动关系
        $id_data['float'] = 1;
        $id_ret = $user->where($map)->save($id_data);

        //当前用户修改
        $user_map['id'] = $uid;
        $data['fid']    = $user_data['id'];
        $data['gid']    = $user_data['fid'];
        $data['tier']   = $user_data['tier']+1;
        $data['float']  = 1;
        $uid_ret = $user->where($user_map)->save($data);

        if($uid_ret !== false && $id_ret !== false){
            $user->commit();
            $this->index();
        }else{
            $user->rollback();
            $this->error("修改失败");
        }
    }
}