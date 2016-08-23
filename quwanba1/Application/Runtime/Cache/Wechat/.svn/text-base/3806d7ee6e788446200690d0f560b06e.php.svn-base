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

	<form action="<?php echo U('WechatPay/buy_card_pay');?>" method="post" id="buySubmit">
	<input type="hidden" name="type" value="<?php echo ($type); ?>">
	<div id="content">
		<div class="cardforTop">
			<?php switch($type): case "0": ?><p><img src="/quwanba1/Public/Wechat/Images/buycard_S.png"></p><?php break;?>
				<?php case "1": ?><p><img src="/quwanba1/Public/Wechat/Images/buycard_V.png"></p><?php break;?>
				<?php default: endswitch;?>
		</div>
		<div class="cardforMain">
			<p class="cardforMainBar"><img src="/quwanba1/Public/Wechat/Images/card_pen.png">详细资料填写</p>
			<ul>
				<li><span>联系人：</span><input name="name" id="name" type="text" placeholder="填写您的姓名"></li>
				<li><span>手机号：</span><input name="contact" id="contact" type="text" placeholder="用于商家联系您的方式"></li>
				<li><span>邮编：</span><input name="postcode" id="postcode" type="text" placeholder="325000"></li>
				<li><span>收货地址：</span><input name="address" id="address" type="text" placeholder="请填写您可以正常收货地址"></li>
				<li><span>卖家留言：</span><input name="message" id="message" type="text" placeholder="如有特殊情况，可以在此留言"></li>
			</ul>
			<div class="cardforMain_buttom"><p><a onclick="buySubmit()">确认并支付</a></p></div>
			<div class="cardforMainHind"><p>您的推荐人是<?php echo ($nickname); ?></p></div>
		</div>
	</div>
	</form>

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

	<script>
		var flag = <?php echo ($flag); ?>;
		function buySubmit(){
			var name     = $("#name").val();
			var contact  = $("#contact").val();
			var postcode = $("#postcode").val();
			var address  = $("#address").val();
			var message  = $("#message").val();
			if(name !="" && contact!="" || postcode!="" || address!="" || message!=""){
				if(flag == 1){
					$("#buySubmit").submit();
					flag = 0;
				}
			}else{
				alert("请将资料填写完整");
			}
		}
	</script>

</html>