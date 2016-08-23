<?php
namespace  Admin\Controller;

use Think\Controller;
class PublicController extends Controller{
    /**
     * 用户登录
     * @param null $username  用户名
     * @param null $password  登录密码
     * @param null $verify    验证码
     */
    public function login($username = null, $password = null, $verify = null){
        if(IS_POST){
            $params = $_POST;
            $params['password'] = think_md5($params['password']);
            $params['data_status'] = '0';
            $admin = M('Admin');
            if($ret = $admin->where($params)->find()){
                session('name','1');
                session('admin_id',$ret['id']);
                session('username',$params['username']);
                session('level',$ret['level']);
            }else{
                $this->error('用户名/密码错误');
            }
            $this->success('登录成功！',U('Index/index'));
        }else{
            $this->display('Public/login');
        }
    }
    public function updatePassword(){
        $this->display('Admin/update_password');
    }
    public function update(){
        $params = $_POST;
        $admin = M('Admin');
        $username = $params['username'];
        $o_password = $params['o_password'];
        $n_password = $params['n_password'];
        if(!isset($o_password)||!isset($n_password)){
            $this->error('原密码和新密码不可为空');
        }
        $where['username'] = $username;
        $where['password'] = think_md5($o_password);
        if($admin->where($where)->find()){
            $admin->where(['username'=>$username])->save(['password'=>think_md5($n_password)]);
        }else{
            $this->error('原密码错误');
        }
        $this->success('修改成功');
    }
    /**
     * 退出登录
     */
    public function logout(){
        if(is_login()){
            session('[destroy]');
            $this->success('退出成功',U('Public/login'));
        }else{
            $this->redirect('Public/login');
        }
    }
}