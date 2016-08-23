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
			<h2>商家列表</h2>

		</div><!--		panel-heading		-->
		<div class="panel-body">
			<div class="well">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-name">姓名</label>
							<input type="text" name="name" value="<?php echo ($name); ?>" placeholder="姓名" id="input-name" class="form-control">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-contact">手机号</label>
							<input type="text" name="contact" value="<?php echo ($contact); ?>" placeholder="手机号" id="input-contact" class="form-control">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">周游卡等级</label>
							<select id="rank" name="rank" class="form-control">
								<option value=""></option>
								<option value="0">S级代理</option>
								<option value="1">V级代理</option>
								<option value="2">非旅行家</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<a><button class="btn btn-primary pull-right" style="margin-right: 10px;" onclick='window.location.href="<?php echo U('User/user_export');?>"'>导出</button></a>
						<button type="submit" id="button-filter" class="btn btn-primary pull-right" style="margin-right: 10px;"><i class="fa fa-search"></i>筛选</button>
					</div>
				</div>
			</div>
			<!-- 数据列表 -->
			<div class="data-table table-striped">
				<table class="table">
					<thead>
					<tr>
						<th>序号</th>
						<th>姓名</th>
						<th>手机号</th>
						<th>周游卡等级</th>
						<th>微信昵称</th>
						<th>用户ID</th>
						<th>消费金额</th>
						<th>注册时间</th>
						<th>最后登录时间</th>
						<th>饭票余额</th>
						<th>操作</th>
					</tr>
					</thead>
					<tbody>
					<?php if(!empty($_list)): if(is_array($_list)): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
								<td id="user_id"><?php echo ($vo["id"]); ?> </td>
								<td><?php echo ($vo["name"]); ?></td>
								<td><?php echo ($vo["contact"]); ?></td>
								<td>
									<?php switch($vo["rank"]): case "0": ?>S级代理<?php break;?>
										<?php case "1": ?>V级代理<?php break;?>
										<?php default: ?>非旅行家<?php endswitch;?>
								</td>
								<td><?php echo ($vo["nickname"]); ?></td>
								<td><?php echo ($vo["openid"]); ?></td>
								<td><?php if(empty($vo["consume"])): ?>0.00<?php else: echo ($vo["consume"]); endif; ?></td>
								<td><?php echo date('Y/m/d',$vo['subscribe_time']);?></td>
								<td><?php echo ($vo["login_time"]); ?></td>
								<td><?php if(empty($vo["income_surplus"])): ?>0.00<?php else: echo ($vo["income_surplus"]); endif; ?></td>
								<td>
									<a href="<?php echo U('User/get_user_detail',array('id'=>$vo['id']));?>">查看</a>
									<!--<a href="<?php echo U('AllianceShop/shop_edit_page',array('id'=>$vo['id']));?>">修改</a>-->
								</td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						<?php else: ?>
						<td colspan="10" class="text-center"> aOh! 暂时还没有内容! </td><?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="page">
				<?php echo ($_page); ?>
			</div>
		</div>
	</div><!--panel-->

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

	<script src="/quwanba1/Public/Admin/Js/thinkbox/jquery.thinkbox.js"></script>
	<script>
		$('#button-filter').on('click', function() {
			var url = Think.APP+'/Admin/User/index?token=lalala';

			var name = $('input[name=\'name\']').val();
			if (name) {
				url += '&name=' + encodeURIComponent(name);
			}

			var contact = $('input[name=\'contact\']').val();
			if (contact) {
				url += '&contact=' + encodeURIComponent(contact);
			}

			var rank = $('#rank option:selected') .val();
			if (rank) {
				url += '&rank=' + encodeURIComponent(rank);
			}
			location = url;
		});

		$("#rank  option[value='<?php echo ($rank); ?>']").attr("selected",true);
	</script>

</body>
</html>