<?php
namespace Admin\Controller;
use Admin\Model\AdminModel;
use Think\Controller;
class AdminController extends Controller{
    public function __construct(){
        parent::__construct();
        $this->admin = new AdminModel();
    }
    /**
     * 初始化控制器
     */
    protected function _initialize(){
        //检测是否已经登录
        define('UID',session('name'));
        if(!UID){
            //未登录,跳转登录页
            $this->redirect('Public/login');
        }
    }
    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  unreviewed_index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: unreviewed_index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: unreviewed_index.html?r=5
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param array        $base    基本的查询条件
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author 朱亚杰 <xcoolcc@gmail.com>
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(),$order='',$base = array(),$field=true){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        if(is_string($model)){
            $model  =   M($model);
        }

        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);

        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = $REQUEST['_field'].' '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        $options['where'] = array_filter(array_merge( (array)$base, /*$REQUEST,*/ (array)$where ),function($val){
            if($val===''||$val===null){
                return false;
            }else{
                return true;
            }
        });
        if( empty($options['where'])){
            unset($options['where']);
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
//        if($pages){
//            $total        =   $model->where($options['where'])->count();
//            if( isset($REQUEST['r']) ){
//                $listRows = (int)$REQUEST['r'];
//            }else{
//                $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
//            }
//            $page = new \Think\Page($total, $listRows, $REQUEST);
//            if($total>$listRows){
//                $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
//            }
//            $p =$page->show();
//            $this->assign('_page', $p? $p: '');
//            $this->assign('_total',$total);
//            $options['limit'] = $page->firstRow.','.$page->listRows;
//        }
        $model->setProperty('options',$options);
        //echo $model->_sql();
        return $model->field($field)->select();
    }
    /*
     * 管理员列表
     */
    public function index(){
        $id = I('get.id');
        $level = $_SESSION['level'];
        $admin_id = $_SESSION['admin_id'];
        $this->check_level($level,'Admin/index');
        if(!empty($user_id)){
            $where['id'] = $id;
        }
        $list = $this->lists($this->admin,$where,'id',[]);
        foreach($list as $key=>$value){
            switch($value['level']){
                case '1':$list[$key]['level_name'] = '超级管理员';break;
                case '2':$list[$key]['level_name'] = '管理员';break;
                default:break;
            }
        }
        $this->assign('admin_id',$admin_id);
        $this->assign('_list',$list);
        $this->display();
    }
    /*
     * 添加管理员
     */
    public function add(){
        $params = $_POST;
        $password = $params['password'];
        $repassword = $params['repassword'];
        if($password != $repassword){
            $this->error('两次密码输入不一致');exit();
        }
        $params['password'] = think_md5($params['password']);
        $this->admin->create($params,1);
        $this->admin->add();
        $this->redirect('Admin/Admin/index');
    }
    /*
     * 添加管理员界面
     */
    public function add_view(){
        $admin_id = I('get.admin_id');
        $this->assign('admin_id',$admin_id);
        $this->display();
    }
    /*
    * 编辑管理员界面
     */
    public function edit_view(){
        $id = I('get.id');
        $username = I('get.username');
        $level = I('get.level');
        $level_name = I('get.level_name');
        $realname = I('get.realname');
        $status = I('get.status');
        if($status == '1'){
            $data['status']  = '正常';
        }else{
            $data['status'] = '禁用';
        }
        $data['id'] = $id;
        $data['username'] = $username;
        $data['level'] = $level;
        $data['level_name'] = $level_name;
        $data['realname'] = $realname;
        $this->assign('_admin',$data);
        $this->display();
    }
    /*
     * 编辑管理员
    */
    public function edit(){
        $params = $_POST;
        $this->admin->save($params);
        $this->redirect('Admin/Admin/index');
    }
    /**
     * 启用/禁用
     */
    public function disable(){
        $id = I('get.id');
        $admin_id = $_SESSION['admin_id'];
        if($id == $admin_id){
            $this->error('不能将自己禁用');
        }
        $data_status = I('data_status');
        if($data_status == '0'){
            $data['data_status'] = '1';
        }else{
            $data['data_status'] = '0';
        }
        $this->admin->where('id = '.$id)->save($data);

        $this->redirect('index');
    }
    /**
     * 判断权限
     */
    public function check_level($level,$url){
        if($level == 1){
            return 1;
        }
        $level_list = C('level-'.$level);
        $flag = 0;
        foreach($level_list as $key=>$value){
            if($value == $url){
                return 1;
            }
        }
        if($flag == 0){
            $this->error('权限不足');
        }
    }

    /**
     * 获取所有省
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    function get_province_all(){
        return $ret = M('Province')->field('id,name')->select();
    }

    /**
     * 获取所有市
     * @param id 省id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    function get_city_all(){
        $map['province_id'] = I('post.id');
        $ret = M('City')->field('id,name')->where($map)->select();
        $this->ajaxReturn($ret);
    }

    /**
     * 获取所有区
     * @param id
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    function get_district_all(){
        $map['city_id'] = I('post.id');
        $ret = M('District')->field('id,name')->where($map)->select();
        $this->ajaxReturn($ret);
    }

    /**
     * 检测表单提交字段
     * @param $fields
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    function form_check($fields){
        foreach ($fields as $key=>$value){
            if($value == ""){
                $this->error('参数错误');exit();
            }
        }
    }

    /**
     * 地区id获取
     * @param  array 二维数组(包含省市区id)
     * @return array 二维数组
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function address_replace($data){
        foreach ($data as $key=>$value){
            foreach ($value as $k=>$v){
                if($k == 'province_id'){
                    $value['province'] = get_province($v);
                }elseif($k == 'city_id'){
                    $value['city'] = get_city($v);
                }elseif($k == 'district_id'){
                    $value['district'] = get_district($v);
                }
            }
            $data[$key] = $value;
        }
        return $data;
    }


    /**
     * 删除文件
     * @param  string 文件的地址如: /Advertisement/shop_top_bc.png
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function delete_file($path){
        if (!is_array($path)){
            $path = './Uploads'.$path;
            unlink($path);
        }
        foreach ($path as $key => $value){
            $value = './Uploads'.$value;
            if (!unlink($value)){
                $this->error('无法删除');
            }
        }
    }

    /**
     * 获取需要生成在Excel中的数据，配置参数
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function outErxcel(){
        $filename="test_excel"; /*默认Excel文件名称*/
        $data=array( /*Excel表格内容数据*/
            array('username'=>'zhangsan','password'=>"123456"),
            array('username'=>'lisi',    'password'=>"abcdef"),
            array('username'=>'wangwu',  'password'=>"111111"),
        );
        $headArr=array("用户名","密码"); /*Excel表格内容头部*/
        $this->getExcel($filename,$headArr,$data);
    }

    /**
     * 导出excel
     * @param $fileName  文件名称
     * @param $headArr   头部文字
     * @param $data      数据
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @author zhangxiang <zhxjmdc@gmail.com>
     */
    public function getExcel($fileName,$headArr,$data){
        /*对数据判空进行检验*/
        if(empty($data) || !is_array($data)){
            die("data must be a array");
        }
        /*检查文件名并将日期融入文件名*/
        if(empty($fileName)){
            exit;
        }
        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls";

        /*创建PHPExcel对象，注意，不能少了\*/
        /*导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入*/
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        $objPHPExcel = new \PHPExcel();
        $objProps    = $objPHPExcel->getProperties();

        /*设置表头*/
        $key = ord("A");
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }

        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach($data as $key => $rows){        /*行写入*/
            $span = ord("A");
            foreach($rows as $keyName=>$value){ /*列写入*/
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }

//        $fileName = iconv("utf-8", "gb2312", $fileName);
        /*重命名表*/
        /*$objPHPExcel->getActiveSheet()->setTitle('test');*/
        /*设置活动单指数到第一个表,所以Excel打开这是第一个表*/
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); /*文件通过浏览器下载*/
        exit;
    }

    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  unreviewed_index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: unreviewed_index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: unreviewed_index.html?r=5
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param array        $base    基本的查询条件
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author 朱亚杰 <xcoolcc@gmail.com>
     *
     * @return array|false
     * 返回数据集
     */
    protected function data_lists ($model,$where=array(),$order='',$base = array(),$field=true){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        if(is_string($model)){
            $model  =   M($model);
        }

        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);

        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = $REQUEST['_field'].' '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        $options['where'] = array_filter(array_merge( (array)$base, /*$REQUEST,*/ (array)$where ),function($val){
            if($val===''||$val===null){
                return false;
            }else{
                return true;
            }
        });
        if( empty($options['where'])){
            unset($options['where']);
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        if($pages){
            $total        =   $model->where($options['where'])->count();
            if( isset($REQUEST['r']) ){
                $listRows = (int)$REQUEST['r'];
            }else{
                $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
            }
            $page = new \Think\Page($total, $listRows, $REQUEST);
            if($total>$listRows){
                $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            }
            $p =$page->show();
            $this->assign('_page', $p? $p: '');
            $this->assign('_total',$total);
            $options['limit'] = $page->firstRow.','.$page->listRows;
        }
        $model->setProperty('options',$options);
        //echo $model->_sql();
        return $model->field($field)->select();
    }
}