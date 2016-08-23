<?php
namespace  Admin\Controller;
use Think\Controller;
class ResponseController extends AdminController{
    /**
     * 微信自动回复显示页
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function index(){
        $response_data = M('Response')->field('content')->where('id = 1')->find();

        $this->assign('content', $response_data['content']);
        $this->display('Response/edit');
    }

    /**
     * 内容更新
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function response_update(){
        $content = I('post.content');
        $data['content'] = $content;
        M('Response')->where('id = 1')->save($data);
        $this->index();
    }
}