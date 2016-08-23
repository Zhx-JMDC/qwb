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
			<h2>最新商家列表</h2>

		</div><!--		panel-heading		-->
		<div class="panel-body">
			<div class="well">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label" for="input-shopname">商户名</label>
							<input type="text" name="shop_name" value="<?php echo ($shop_name); ?>" placeholder="商户名" id="input-shopname" class="form-control">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">省</label>
							<select name="province_id" class="form-control" id="province" onchange="getCity()">
								<option value=""></option>
								<?php if(is_array($_province)): foreach($_province as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
							</select>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">市</label>
							<select name="city_id" class="form-control" id="city" onchange="getDistrict()">
								<option value=""></option>
							</select>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">区</label>
							<select name="district_id" id="district" class="form-control">
								<option value=""></option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<a><button class="btn btn-primary pull-right" style="margin-right: 10px;" onclick='window.location.href="<?php echo U('AllianceShop/shop_export');?>?type=1"'>导出</button></a>
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
						<th>商户名</th>
						<th>区域</th>
						<th>类型</th>
						<th>商品名称</th>
						<th>操作</th>
						<th>位置</th>
					</tr>
					</thead>
					<tbody>
					<?php if(!empty($_list)): if(is_array($_list)): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
								<td id="user_id"><?php echo ($vo["id"]); ?> </td>
								<td><?php echo ($vo["shop_name"]); ?></td>
								<td><?php echo ($vo["province"]); ?>&nbsp;<?php echo ($vo["city"]); ?>&nbsp;<?php echo ($vo["district"]); ?></td>
								<td><?php if($vo["shop_type"] == 1): ?>最新商家<?php else: ?> 热门商家<?php endif; ?></td>
								<td>
									<?php if(is_array($vo["goods"])): $i = 0; $__LIST__ = $vo["goods"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; echo ($v["goods_name"]); ?></br><?php endforeach; endif; else: echo "" ;endif; ?>
								</td>
								<td>
									<a href="<?php echo U('NewShop/new_shop_detail',array('id'=>$vo['id']));?>">查看</a>
									<a onclick="shopDel('<?php echo ($vo["id"]); ?>')">移除</a>
								</td>
								<td><?php echo ($vo["order"]); ?></td>
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
			var url = Think.APP+'/Admin/AllianceShop/index?token=lalala';

			var shop_name = $('input[name=\'shop_name\']').val();
			if (shop_name) {
				url += '&shop_name=' + encodeURIComponent(shop_name);
			}

			var province_id = $('#province option:selected') .val();
			if (province_id) {
				url += '&province_id=' + encodeURIComponent(province_id);
			}

			var city_id = $('#city option:selected') .val();
			if (city_id) {
				url += '&city_id=' + encodeURIComponent(city_id);
			}

			var district_id = $('#district option:selected') .val();
			if (district_id) {
				url += '&district_id=' + encodeURIComponent(district_id);
			}
			location = url;
		});

		$("#shop_type  option[value='<?php echo ($shop_type); ?>']").attr("selected",true);

		function shopDel(id){
			if (confirm("你确定删除吗？")) {
				$.ajax({
					url: "<?php echo U('AllianceShop/shop_del');?>",
					type: 'post',
					data: {'id': id},
					success: function (data) {
						window.location.href = "<?php echo U('NewShop/index');?>";
					}
				});
			}
		}
	</script>

</body>
</html>