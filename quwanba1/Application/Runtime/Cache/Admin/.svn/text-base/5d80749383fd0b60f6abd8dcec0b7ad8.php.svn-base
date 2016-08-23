<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content=""> 
  <link rel="shortcut icon" href="/quwanba1/Public/Admin/Images/favicon.png" type=  "image/png">
  <link rel="stylesheet" type="text/css" href="/quwanba1/Public/Admin/Css/style.default.css" >
  <title>趣玩吧后台管理系统</title>


  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>
<div id="top-alert" class="alert alert-danger" style="display: none;">
    <button class="close fixed" style="margin-top: 4px;">&times;</button>
    <div class="  alert-content">这是内容</div>
</div>
<div class="body" style="width:100%">
    
    <!-- 标题栏 -->
    <div class="panel panel-default">
        <div class="panel-heading main-title">
            <h2>编辑管理员</h2>
        </div><!--		panel-heading		-->
        <div class="panel-body">
            <form class="form-horizontal form-bordered" action="<?php echo U('Admin/Admin/edit');?>" method="post">
                <div class="form-group">
                    <label class="col-sm-3 control-label">ID</label>
                    <div class="col-sm-6">
                        <input type="text" name="id"  class="form-control" value="<?php echo ($_admin["id"]); ?>"  readonly="true"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">用户名</label>
                    <div class="col-sm-6">
                        <input type="text" name="username"  class="form-control" value="<?php echo ($_admin["username"]); ?>" readonly="true"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">姓名</label>
                    <div class="col-sm-6">
                        <input type="text" name="realname"  class="form-control" value="<?php echo ($_admin["realname"]); ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">级别</label>
                    <div class="col-sm-6">
                        <select class="form-control mb15" name="level">
                            <option value="<?php echo ($_admin["level"]); ?>"><?php echo ($_admin["level_name"]); ?></option>
                            <option value="1">超级管理员</option>
                            <option value="2">管理员</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">状态</label>
                    <div class="col-sm-6">
                        <input type="text" name=""  class="form-control" value="<?php echo ($_admin["status"]); ?>"  readonly="true"/>
                    </div>
                </div>
                <button  class="btn btn-success">保存</button>
            </form>
            <button  class="btn btn-primary" onclick="window.location.href = '<?php echo U('Admin/Admin/index');?>'">返回</button>
        </div>
    </div>

</div>

</section>

<script type="text/javascript" src="/quwanba1/Public/Admin/Js/jquery2.1.1.min.js"></script>
<script type="text/javascript" src="/quwanba1/Public/Admin/Js/jquery.cookies.2.2.0.min.js"></script>
<script type="text/javascript" src="/quwanba1/Public/Admin/Js/common.js"></script>
<script src="/quwanba1/Public/Admin/Js/bootstrap.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/jquery-migrate-1.2.1.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/jquery.sparkline.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/toggles.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/jquery.cookies.js"></script>
<script src="/quwanba1/Public/Admin/Js/custom.js"></script>

<script type="text/javascript">
    /* 全局变量 */
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "/quwanba1", //当前网站地址
            "APP"    : "/quwanba1/index.php", //当前项目地址
            "PUBLIC" : "/quwanba1/Public", //项目公共目录地址
            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
            "HOST"   : "http://"+"<?php echo ($_SERVER['HTTP_HOST']); ?>",
        }
         $.ajax({
          url: '<?php echo U("Index/check_login");?>',
          success:function(data){
            if(data.status==0){
               top.document.location.href = "<?php echo U('Public/login');?>";
            }
          }
        })

    })();
    /*  ThinkPhp page样式动态改为Bootstrap样式*/
    function initPagination(selector) {
        selector = selector || '.page';
        $(selector).each(function (i, o) {
            var html = '<ul class="pagination">';
            $(o).find('a,span').each(function (i2, o2) {
                var linkHtml = '';
                if ($(o2).is('a')) {
                    linkHtml = '<a href="' + ($(o2).attr('href') || '#') + '">' + $(o2).text() + '</a>';
                }else if ($(o2).is('span')) {linkHtml = '<a>' + $(o2).text() + '</a>';
                }
                var css = '';
                if ($(o2).hasClass('current')) {
                  css = ' class="active" ';
                }
                html += '<li' + css + '>' + linkHtml + '</li>';
            });
            html += '</ul>';
            $(o).html(html).fadeIn();
        });
    }
    //获取市
    function getCity(){
        var id = $('#province option:selected').val();
        $.ajax({
            url: "<?php echo U('Admin/get_city_all');?>",
            type: 'post',
            data: {'id': id},
            success: function (data) {
                var html = "<option value=''></option>";
                $.each(data, function (k, v) {
                    html += "<option value='"+v.id+"'>"+v.name+"</option>";
                });
                $('#city').html(html);
            }
        });
    }

    //获取地区
    function getDistrict(){
        var id = $('#city option:selected').val();
        $.ajax({
            url: "<?php echo U('Admin/get_district_all');?>",
            type: 'post',
            data: {'id': id},
            success: function (data) {
                var html = "<option value=''></option>";
                $.each(data, function (k, v) {
                    html += "<option value='"+v.id+"'>"+v.name+"</option>";
                });
                $('#district').html(html);
            }
        });
    }
</script>

</body>
</html>