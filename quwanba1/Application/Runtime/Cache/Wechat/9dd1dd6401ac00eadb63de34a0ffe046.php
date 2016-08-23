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
			<p class="cashgetMain_p2">成为V级旅行家可提取饭票</p>
			<p class="cashgetMain_p3"><?php if(empty($income_v)): ?><span>F0.00</span><?php else: ?><span>F<?php echo ($income_v); ?></span><?php endif; ?></p>
			<p class="cashgetMain_p2" style="margin-top:10px;">成为S级旅行家可提取饭票</p>
			<p class="cashgetMain_p3"><?php if(empty($income_s)): ?><span>F0.00</span><?php else: ?><span>F<?php echo ($income_s); ?></span><?php endif; ?></p>
			<div class="representAno cashgetMain_img_two">
				<a href="<?php echo U('Spokesman/buy_card');?>">
					<div class="representAno_left">
						<p class="representAno_p2">成为V级代言人</p>
						<p><img src="/quwanba1/Public/Wechat/Images/vip_letter_v_dark.png"></p>
					</div>
				</a>
			</div>
		</div>
		<div class="cashgetdeTail">
			<ul>
				<li>提现历史<img src="/quwanba1/Public/Wechat/Images/jiantou_xia.png"></li>
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

</html>