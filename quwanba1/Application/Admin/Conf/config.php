<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'               => 'mysql',                                      // 数据库类型
    'DB_HOST'               => '114.55.25.170',                              // 服务器地址
    'DB_NAME'               => 'quwanba',                                    // 数据库名
    'DB_USER'               => 'quwanba',                                    // 用户名
    'DB_PWD'                => 'qwb2016',                                    // 密码
    'DB_PORT'               => '3306',                                       // 端口
    'DB_PREFIX'             => 'qwb_',                                       // 数据库表前缀

    'TMPL_PARSE_STRING' => array(                                            //模板配置
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/Images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/Css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/Js',
    ),
                                                                             //模板配置
    'TMPL_PARSE_STRING' => array(
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/Images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/Css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/Js',
    ),
    'DOMAIN_NAME'     =>'http://www.qwb.2015tt.net/',                    //TPACE调试模式
    'SHOW_PAGE_TRACE' => false,
                                                                             //后台错误页面模板
    'TMPL_ACTION_ERROR'     =>  MODULE_PATH.'View/Public/error.html',        // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  MODULE_PATH.'View/Public/success.html',      // 默认成功跳转对应的模板文件

    'PIC_CONFIG' => array(
        'mimes'    => '', //允许上传的文件MiMe类型
        'maxSize'  => 20*1024*1024, //上传的文件大小限制 (0-不做限制)
        'exts'     => 'jpg,gif,png,jpeg,application/octet-stream', //允许上传的文件后缀
        'autoSub'  => true, //自动子目录保存文件
        'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => 'Uploads',
        'savePath' => '',
        'saveName' => array('imgSaveName', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', //文件保存后缀，空则使用原后缀
        'replace'  => false, //存在同名是否覆盖
        'hash'     => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    )
);