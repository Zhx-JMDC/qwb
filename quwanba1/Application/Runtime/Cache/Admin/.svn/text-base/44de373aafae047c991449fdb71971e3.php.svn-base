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
			<h4>用户详情</h4>
		</div>
		<div class="panel-body table">
			<table border=0 class="">
				<?php if(!empty($user)): ?><tr>
						<td align='right'>ID：</td>
						<td><?php echo ($user['id']); ?></td>
					</tr>
					<tr>
						<td align='right'>头像：</td>
						<td><img style="width:100px;" src="<?php echo ($user['headimgurl']); ?>"></td>
					</tr>
					<tr>
						<td align='right'>姓名：</td>
						<td><?php echo ($user['name']); ?></td>
					</tr>
					<tr>
						<td align='right'>手机号：</td>
						<td><?php echo ($user['contact']); ?></td>
					</tr>
					<tr>
						<td align='right'>周游卡等级：</td>
						<td>
							<?php switch($user["rank"]): case "1": ?>V级<?php break;?>
								<?php case "2": ?>非旅行家<?php break;?>
								<?php default: ?>S级<?php endswitch;?>
						</td>
					</tr>
					<tr>
						<td align='right'>状态：</td>
						<td><?php echo ($user['status']); ?></td>
					</tr>
					<tr>
						<td align='right'>注册时间：</td>
						<td><?php echo date('Y-m-d H:i:s',$user['subscribe_time'])?></td>
					</tr>
					<tr>
						<td align='right'>推广二维码：</td>
						<td><img style="width:100px;" src="<?php echo ($user['qr_code']); ?>"></td>
					</tr>
					<tr>
						<td align='right'>可提现饭票金额：</td>
						<td>
							<?php if(empty($can_draw)): ?>0
								<?php else: echo ($can_draw); endif; ?>
						</td>
					</tr>
					<tr>
						<td align='right'>待提现饭票金额：</td>
						<td><?php echo ($stop_draw); ?></td>
					</tr>
					<tr>
						<td align='right'>个人商品消费记录：</td>
						<td>
							<table class="table">
								<thead>
								<tr>
									<th>记录id</th>
									<th>用户名</th>
									<th>价格</th>
									<th>支付状态</th>
									<th>消费二维码</th>
									<th>时间</th>
								</tr>
								</thead>
								<tbody>
								<?php if(!empty($goods)): if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
											<td><?php echo ($vo["id"]); ?> </td>
											<td><?php echo ($vo["name"]); ?></td>
											<td><?php echo ($vo["price"]); ?></td>
											<td>
												<?php if($vo["pay_status"] == 1): ?>已支付
													<?php else: ?>已退款<?php endif; ?>
											</td>
											<td><img style="width: 50px;" src="<?php echo ($vo["qr_code"]); ?>"></td>
											<td><?php echo ($vo["buy_time"]); ?></td>
										</tr><?php endforeach; endif; else: echo "" ;endif; ?>
									<?php else: ?>
									<td colspan="10" class="text-center"> aOh! 暂时还没有内容! </td><?php endif; ?>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td align='right'>个人购卡消费记录：</td>
						<td>
							<table class="table">
								<thead>
								<tr>
									<th>记录id</th>
									<th>卡种</th>
									<th>价格</th>
									<th>物流号</th>
									<th>发货状态</th>
									<th>时间</th>
								</tr>
								</thead>
								<tbody>
								<?php if(!empty($card)): if(is_array($card)): $i = 0; $__LIST__ = $card;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
											<td><?php echo ($vo["id"]); ?> </td>
											<td>
												<?php if($vo["card_id"] == 1): ?>S级
													<?php else: ?>V级<?php endif; ?>
											</td>
											<td><?php echo ($vo["price"]); ?></td>
											<td><?php echo ($vo["express_num"]); ?></td>
											<td>
												<?php if($vo["status"] == 1): ?>已发货
													<?php else: ?>未发货<?php endif; ?>
											</td>
											<td><?php echo ($vo["time"]); ?></td>
										</tr><?php endforeach; endif; else: echo "" ;endif; ?>
									<?php else: ?>
									<td colspan="10" class="text-center"> aOh! 暂时还没有内容! </td><?php endif; ?>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td align='right'>提现记录：</td>
						<td>
							APP待定
						</td>
					</tr>
					<?php else: ?>
					<thead><tr><th colspan="14" style="text-align:center;">该用户不存在!</th></tr></thead><?php endif; ?>
			</table>
			<!--<button  class="btn btn-success" onclick="window.location.href = '<?php echo U("Admin/AllianceShop/shop_edit_page");?>'">修改</button>-->
			<button  class="btn btn-return" onclick="window.location.href = '<?php echo U("Admin/AllianceShop/index");?>'">返回</button>
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