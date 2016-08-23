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
			<h4>热门商家详情</h4>
		</div>
		<form method="post" action="<?php echo U('Card/express_edit');?>">
		<div class="panel-body table">
			<table border=0 class="">
				<?php if(!empty($card)): ?><tr>
						<td align='right'>交易ID：</td>
						<td><?php echo ($card['id']); ?></td>
					</tr>
					<tr>
						<td align='right'>卡种：</td>
						<td>
							<?php if($card["card_id"] == 1): ?>S级<?php else: ?>V级<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td align='right'>买家姓名：</td>
						<td><?php echo ($card['name']); ?></td>
					</tr>
					<tr>
						<td align='right'>买家手机号：</td>
						<td><?php echo ($card['contact']); ?></td>
					</tr>
					<tr>
						<td align='right'>邮政编码：</td>
						<td><?php echo ($card['postcode']); ?></td>
					</tr>
					<tr>
						<td align='right'>收货地址：</td>
						<td><?php echo ($card['address']); ?></td>
					</tr>
					<tr>
						<td align='right'>交易金额：</td>
						<td><?php echo ($card['price']); ?></td>
						</td>
					</tr>
					<tr>
						<td align='right'>买家留言：</td>
						<td><?php echo ($card['message']); ?></td>
						</td>
					</tr>
					<tr>
						<td align='right'>购买时间：</td>
						<td><?php echo ($card['time']); ?></td>
						</td>
					</tr>
					<tr>
						<td align='right'>物流单号：</td>
						<td>
							<input type="text" name="express_num" value="<?php echo ($card['express_num']); ?>" placeholder="物流单号" id="input-express_num" class="form-control">
							<input type="hidden" name="id" value="<?php echo ($card['id']); ?>">
						</td>
					</tr>
					<tr>
						<td align='right'>状态：</td>
						<td>
							<input type="radio" name="send_status" value="1">已发货
							<input type="radio" name="send_status" value="0">未发货
						</td>
					</tr>
					<?php else: ?>
					<thead><tr><th colspan="14" style="text-align:center;">该用户不存在!</th></tr></thead><?php endif; ?>
			</table>
			<button  class="btn btn-success" type="submit">保存</button>
			<button  class="btn btn-return" onclick="window.location.href = '<?php echo U("Admin/Card/index");?>'">返回</button>
		</div>
		</form>
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
	<script>
		$("input[type='radio'][name=send_status][value='<?php echo ($card["send_status"]); ?>']").attr("checked",true);
	</script>

</body>
</html>