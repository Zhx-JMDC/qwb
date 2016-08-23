<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends AdminController{
    public function index() {
        $this->display('Index/index');
    }

    /**
     * 登录状态检测
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function check_login(){
        $login_status = is_login();

        if($login_status){
            $this->success();
        }else {
            $this->error();
        }
    }
}