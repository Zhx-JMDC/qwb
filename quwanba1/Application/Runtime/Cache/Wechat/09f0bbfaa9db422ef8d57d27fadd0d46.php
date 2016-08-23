<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="user-scalable=no,width=device-width, initial-scale=0.5"/>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/quwanba1/Public/Wechat/Css/css.css">
    
</head>
<script>
    var img = '/quwanba1/Public/Wechat/Images';
</script>

<body style="background-color: #f5f5f5">

	<div id="content">
		<div class="cashgetMain">
			<p class="cashgetMain_p1"><img src="/quwanba1/Public/Wechat/Images/cash_get_logo.png"></p>
			<p class="cashgetMain_p2">暂无饭票可提取</p>
			<p class="cashgetMain_p3"><span style="border:0">F0.00</span></p>
		</div>
		<div class="cashgetdeTail">
			<ul>
				<li>提现历史<img src="/quwanba1/Public/Wechat/Images/jiantou_xia.png"></li>
				<?php if(is_array($history)): foreach($history as $key=>$v): ?><li><p><?php echo ($v["time"]); ?><span>+F<?php echo ($v["value"]); ?></span></p></li><?php endforeach; endif; ?>
			</ul>
		</div>
	</div>

<div id="footer">
    <ul>
        <li class="<?php echo ($footer_goods); ?>"><a href="<?php echo U('Shop/shop_mall');?>">
            <p class="footer_botton_p1"><img src="/quwanba1/Public/Wechat/Images/bottom_01.png" class="footer_bottom_img_01"><img src="/quwanba1/Public/Wechat/Images/bottom_01_bc.png" class="footer_bottom_img_02"></p>
            <p class="footer_botton_p2">商城首页</p></a>
        </li>
        <li class="<?php echo ($footer_shop); ?>"><a href="<?php echo U('AllianceShop/shop_index_hot');?>">
            <p class="footer_botton_p1"><img src="/quwanba1/Public/Wechat/Images/bottom_02.png" class="footer_bottom_img_01"><img src="/quwanba1/Public/Wechat/Images/bottom_02_bc.png" class="footer_bottom_img_02"></p>
            <p class="footer_botton_p2">联盟商家</p></a>
        </li>
        <li class="<?php echo ($footer_collect); ?>"><a href="<?php echo U('Collect/my_collect_goods');?>">
            <p class="footer_botton_p1"><img src="/quwanba1/Public/Wechat/Images/bottom_03.png" class="footer_bottom_img_01"><img src="/quwanba1/Public/Wechat/Images/bottom_03_bc.png" class="footer_bottom_img_02"></p>
            <p class="footer_botton_p2">我的收藏</p></a>
        </li>
        <li class="<?php echo ($footer_order); ?>"><a href="<?php echo U('Order/my_order');?>">
            <p class="footer_botton_p1"><img src="/quwanba1/Public/Wechat/Images/bottom_04.png" class="footer_bottom_img_01"><img src="/quwanba1/Public/Wechat/Images/bottom_04_bc.png" class="footer_bottom_img_02"></p>
            <p class="footer_botton_p2">我的订单</p></a>
        </li>
        <li class="<?php echo ($footer_represent); ?>"><a href="<?php echo U('Spokesman/represent_v');?>">
            <p class="footer_botton_p1"><img src="/quwanba1/Public/Wechat/Images/bottom_05.png" class="footer_bottom_img_01"><img src="/quwanba1/Public/Wechat/Images/bottom_05_bc.png" class="footer_bottom_img_02"></p>
            <p class="footer_botton_p2">我是旅行家</p></a>
        </li>
    </ul>
</div>
</body>
<script type="text/javascript" src="/quwanba1/Public/Wechat/Js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="/quwanba1/Public/Wechat/Js/jquery.touchSlider.js"></script>
<script type="text/javascript" src="/quwanba1/Public/Wechat/Js/jquery.lazyload.js"></script>
<script type="text/javascript" src="/quwanba1/Public/Wechat/Js/iscroll.js" ></script>
<script type="text/javascript" src="/quwanba1/Public/Wechat/Js/finall.js" ></script>
<script type="text/javascript" src="/quwanba1/Public/Wechat/Js/js.js"></script>

<script type="text/javascript">
$(function(){
	var page = 2;
	function loadMeinv(){
		var count = 3;
		if((<?php echo ($_count); ?>+count-1)/count >= page){
			$.ajax({
				url: "<?php echo U('Spokesman/withdraw_history_more');?>",
				type: 'post',
				data: {'page': page,'count':count},
				success: function (data) {
					var html = "";
					$.each(data, function (k, v) {
						html += '<li><p>'+v.time+'<span>+F'+v.value+'</span></p></li>';
					})
					$minUl = getMinUl();
					$minUl.append(html);
				}
			});
		}
		page++;
	}
	loadMeinv();
	$(window).on("scroll",function(){
		$minUl = getMinUl();
		if($minUl.height() <= $(window).scrollTop()+$(window).height()){
			loadMeinv();
		}
	})
	function getMinUl(){
		var $arrUl = $(".cashgetdeTail ul");
		var $minUl =$arrUl.eq(0);
		$arrUl.each(function(index,elem){
			if($(elem).height()<$minUl.height()){
				$minUl = $(elem);
			}
		});
		return $minUl;
	}
})
</script>

</html>