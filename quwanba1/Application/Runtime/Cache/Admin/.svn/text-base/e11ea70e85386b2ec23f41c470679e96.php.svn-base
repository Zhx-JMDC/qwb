<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/common/Public/Admin/Images/favicon.png" type=  "image/png">
    <link rel="stylesheet" type="text/css" href="/common/Public/Admin/Css/style.default.css">
    <title><?php echo ($APP_NAME); ?>后台管理系统</title>
    <style type="text/css">
        body{
            background: #1d2939;
        }
    </style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body >
<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>
<section>
    <div class="leftpanel" id="leftpanel">
        <div class="logopanel">
            <h1><span>  <img src="" /> [</span> <?php echo ($APP_NAME); ?> <span>]</span></h1>
        </div><!-- logopanel -->

        <div class="leftpanelinner">

            <!-- This is only visible to small devices -->
            <!-- <div class="visible-xs hidden-sm hidden-md hidden-lg">
                <div class="media userlogged">
                    <img alt="" src="images/photos/loggeduser.png" class="media-object">
                    <div class="media-body">
                        <h4>John Doe</h4>
                        <span>"Life is so..."</span>
                    </div>
                </div>

                <h5 class="sidebartitle actitle">Account</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket mb30">
                  <li><a href="profile.html"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                  <li><a href=""><i class="fa fa-cog"></i> <span>Account Settings</span></a></li>
                  <li><a href=""><i class="fa fa-question-circle"></i> <span>Help</span></a></li>
                  <li><a href="signout.html"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
                </ul>
            </div> -->

            <h5 class="sidebartitle">Navigation</h5>
            <ul id="menu"class="nav nav-pills nav-stacked nav-bracket">
                <?php if(is_array($__MENU__["main"])): $k = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($k % 2 );++$k; if(!empty($menu["child"])): ?><li class="nav-parent">
                            <a href="<?php echo (U($menu["url"])); ?>" > <i class="<?php echo ($menu["icon_class"]); ?>"></i> <span><?php echo ($menu["title"]); ?> </span></a>
                            <!-- 子导航 -->
                            <?php if(!empty($menu["child"])): ?><ul  class="children" >
                                    <?php if(is_array($menu["child"])): $kc = 0; $__LIST__ = $menu["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($kc % 2 );++$kc;?><li> <a  href="<?php echo (U($vo["url"])); ?>"  data-id="<?php echo ($k); ?>_<?php echo ($kc); ?>" data-name="<?php echo ($vo["title"]); ?>" onclick="request('<?php echo (U($vo["url"])); ?>');return false;"><i class="fa fa-caret-right"></i><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                </ul><?php endif; ?>
                            <!-- /子导航 -->
                        </li>
                        <?php else: ?>
                        <li class="">
                            <a href="<?php echo (U($menu["url"])); ?>" > <i class="<?php echo ($menu["icon_class"]); ?>"></i> <span><?php echo ($menu["title"]); ?> </span></a>
                        </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>

            </ul>

        </div><!-- leftpanelinner -->
    </div><!-- leftpanel -->
    <div class="mainpanel">

        <div class="headerbar" id="headerbar">

            <a class="menutoggle"><i class="fa fa-bars"></i></a>


            <div class="header-right">
                <ul class="headermenu">

                    <li>
                    </li>
                    <li>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <?php echo session('user_auth.username');?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                                <li><a href="<?php echo U('User/updatePassword');?>" onclick="request('<?php echo U("User/updatePassword");?>');return false;"><i class="glyphicon glyphicon-cog"></i>修改密码</a></li>
                                <li><a href="<?php echo U('Public/logout');?>"><i class="glyphicon glyphicon-log-out"></i>退出</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div><!-- header-right -->
        </div><!-- headerbar -->
        <div class="contentpanel">
            <div class="m-tab-list">
                <ul><!--测试用
                      <li class="f-r3 on" data-id="1003"><a>caonima</a><a class="close">×</a></li>
                      <li class="f-r3 " data-id="1001"><a>caonima</a><a class="close">×</a></li>
                      <li class="f-r3 " data-id="1002"><a>caonima</a><a class="close">×</a></li>-->
                </ul>
            </div>
            <div id="top-alert" class="alert alert-danger" style="display: none;">
                <button class="close fixed" style="margin-top: 4px;">&times;</button>
                <div class="  alert-content">这是内容</div>
            </div>
            <div class="body">
                <div id="indexCon" >
                    <iframe id="desktop" scrolling="no" marginheight="0" marginwidth="0" frameborder="0" name="desktop"
                            onreadystatechange="init.resize()" onload="init.iFrameHeight(this)" src="<?php echo U('Index/main');?>"
                            style="width:100%"></iframe>
                </div>
            </div>
        </div>
    </div><!-- mainpanel -->


</section>
<script type="text/javascript">
    function iFrameHeight() {
        var ifm= document.getElementById("desktop");
        var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;
        if(ifm != null && subWeb != null) {
            ifm.height = subWeb.body.scrollHeight;
            ifm.width = subWeb.body.scrollWidth;
        }
    }
</script>
<script type="text/javascript" src="/common/Public/Admin/Js/jquery2.1.1.min.js"></script>
<script src="/common/Public/Admin/Js/bootstrap.min.js"></script>
<script src="/common/Public/Admin/Js/jquery.sparkline.min.js"></script>
<script src="/common/Public/Admin/Js/jquery-migrate-1.2.1.min.js"></script>     <!-- 导航 -->
<script src="/common/Public/Admin/Js/toggles.min.js"></script>
<script src="/common/Public/Admin/Js/jquery.cookies.js"></script>
<script src="/common/Public/Admin/Js/custom.js"></script>
<script src="/common/Public/Admin/Js/sidebar/sidebar.js"></script>
<script type="text/javascript">

    /* 全局变量 */
    function request(url){
        $.ajax({
            url: '<?php echo U("Index/check_login");?>',
            success:function(data){
                if(data.status==1){
                    document.getElementById('desktop').src = url;
                }else {
                    //window.location.href="<?php echo U('Public/login');?>";
                }
            }
        })
    }
    var oTime = null;
    var init= {
        //设置框架宽度和高度

        iFrameHeight: function (that) {

            //var ifm = document.getElementById("desktop");
            var ifm = that;
            //var subWeb = document.frames ? document.frames["desktop"].document :ifm.contentDocument;
            var subWeb = ifm.contentDocument;
            if (ifm != null && subWeb != null) {

                ifm.height = subWeb.body.scrollHeight;

            }
        }
    }
    //window.parent.XXX()来调用父页面js
    function doResizeIFrame(){
        init.iFrameHeight();
    }

    $(function(){
        //左侧菜单按钮触发事件
        $("ul.children li a").on('click',function(){
            console.log("heheda");
            var id = $(this).attr("data-id");
            var url = $(this).attr("href");
            var name = $(this).attr("data-name");
            if($(".m-tab-list li[data-id=\'"+id+"\']").size() == 0 ){
                var html = '<li class="f-r3 on" data-id="'+ id +'"><a>'+name+'</a><a class="close">×</a></li>';
                $(".m-tab-list ul").append(html);
                var html = '<div id="'+id+'Con" style="display: none"> <iframe scrolling="no" onload="init.iFrameHeight(this)" class="m-frameCon"scrolling="no" marginheight="0" marginwidth="0" frameborder="0" src="'+url+'"></iframe></div>';
                $(".body").append(html);
            }
            setTimeout(function(){
                $(".m-tab-list li[data-id=\'"+id+"\']").click();
            },100)
            return false;
        })

        $(".m-tab-list").delegate(".f-r3","click",function(){
            console.log("test");
            $('.body > div').hide();
            var id = $(this).attr("data-id")+'Con';
            $('.body > #'+id).show();
            $('.m-tab-list li').removeClass("on");
            $(this).addClass("on");
        })
        $(".m-tab-list").delegate(".f-r3","click",function(){
            console.log("test");
            $('.body > div').hide();
            var id = $(this).attr("data-id")+'Con';
            $('.body > #'+id).show();
            $('.m-tab-list li').removeClass("on");
            $(this).addClass("on");
        })
        $(".m-tab-list").delegate(".close","click",function(event){
            console.log("close");
            var parent = $(this).parent();
            var prev = $(parent).prev();
            var id = $(parent).attr("data-id")+'Con';
            $(this).parent().remove();
            $(".body > #"+id).remove();
            $(prev).click();
            event.stopPropagation();
        })
    })
</script>




</body>
</html>