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
    
	<input type="hidden" name="id" value="<?php echo ($_GET['id']); ?>">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>系统统计</h4>
		</div>
		<div class="panel-body table">
			<table border=0 class="">
					<tr>
						<td align='right'>用户总数：</td>
						<td><?php echo ($user_count); ?>个</td>
					</tr>
					<tr>
						<td align='right'>今日新增用户数：</td>
						<td><?php echo ($today_user_grow); ?>个</td>
					</tr>
					<tr>
						<td align='right'>总商品数：</td>
						<td><?php echo ($goods_count); ?></td>
					</tr>
					<tr>
						<td align='right'>总联盟商家数：</td>
						<td><?php echo ($shop_count); ?>个</td>
					</tr>
					<tr>
						<td align='right'>总订单数：</td>
						<td><?php echo ($order_count); ?>单</td>
					</tr>
					<tr>
						<td align='right'>今日订单数：</td>
						<td><?php echo ($today_order_grow); ?>单</td>
					</tr>
					<tr>
						<td align='right'>订单总额：</td>
						<td><?php echo ($order_price); ?>元</td>
					</tr>
					<tr>
						<td align='right'>今日订单总额：</td>
						<td><?php echo ($today_order_price); ?>元</td>
					</tr>
					<tr>
						<td align='right'>V级代理数：</td>
						<td><?php echo ($v_user_count); ?>个</td>
					</tr>
					<tr>
						<td align='right'>S级代理数：</td>
						<td><?php echo ($s_user_count); ?>个</td>
					</tr>
					<tr>
						<td align='right'>今日新增V级代理数：</td>
						<td><?php echo ($today_v_count); ?>个</td>
					</tr>
					<tr>
						<td align='right'>今日新增S级代理数：</td>
						<td><?php echo ($today_s_count); ?>个</td>
					</tr>
					<tr>
						<td align='right'>层级数量：</td>
						<td><?php echo ($tier); ?>层</td>
					</tr>
					<!--<tr>-->
						<!--<td align='right'>单体分支最多数量：</td>-->
						<!--<td><?php echo ($user['contact']); ?></td>-->
					<!--</tr>-->
					<!--<tr>-->
						<!--<td align='right'>单体分支平均数量：</td>-->
						<!--<td><?php echo ($user['contact']); ?></td>-->
					<!--</tr>-->

			</table>
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

	<script src="/quwanba1/Public/Admin/Js/common.js"></script>

</body>
</html>