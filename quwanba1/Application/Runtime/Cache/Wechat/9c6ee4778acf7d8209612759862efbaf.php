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

	<script type="text/javascript">
//		//调用微信JS api 支付
//		function jsApiCall()
//		{
//			WeixinJSBridge.invoke(
//					'getBrandWCPayRequest',
//					<?php echo ($jsApiParameters); ?>,
//					function(res){
//						WeixinJSBridge.log(res.err_msg);
//						switch (res.err_msg){
//							case 'get_brand_wcpay_request:cancel':break;
//							case 'get_brand_wcpay_request:fail':break;
//							case 'get_brand_wcpay_request:ok':location.href="<?php echo U('Spokesman/represent_v');?>";break;
//						}
//					}
//			);
//		}
//
//		function callpay()
//		{
//			if (typeof WeixinJSBridge == "undefined"){
//				if( document.addEventListener ){
//					document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
//				}else if (document.attachEvent){
//					document.attachEvent('WeixinJSBridgeReady', jsApiCall);
//					document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
//				}
//			}else{
//				jsApiCall();
//			}
//		}
	</script>

<body style="background-color: #f5f5f5">

	<div id="content">
		<div class="cardforTop">
			<?php switch($type): case "0": ?><p><img src="/quwanba1/Public/Wechat/Images/buycard_S.png"></p><?php break;?>
				<?php case "1": ?><p><img src="/quwanba1/Public/Wechat/Images/buycard_V.png"></p><?php break;?>
				<?php default: endswitch;?>
		</div>
		<div class="cardforMain">
			<div class="cardforMain_buttom" style="padding-top:450px;color:#fff;"><p onclick="callpay()">确认并支付</p></div>
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