<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="user-scalable=no,width=device-width, initial-scale=0.5"/>
    <title>我是旅行家</title>
    <link rel="stylesheet" type="text/css" href="/quwanba1/Public/Wechat/Css/css.css">
    
</head>
<script>
    var img = '/quwanba1/Public/Wechat/Images';
</script>

<body style="background-color: #f5f5f5">

	<div id="content">
		<div class="buycardTop">
			<p class="buycard_01"><img src="<?php echo ($user["headimgurl"]); ?>"></p>
			<p class="buycard_02"><?php echo ($user["nickname"]); ?></p>
			<p class="buycard_03">ID：<?php echo ($user["qwb_id"]); ?></p>
			<p class="buycard_04 currentVip">
				<?php if($rank == vip_letter): ?>您还不是旅行家
					<?php else: ?>当前等级<img src="/quwanba1/Public/Wechat/Images/<?php echo ($rank); ?>.png"><?php endif; ?>
			</p>
		</div>
		<div class="myincomeMain">
			<p class="myincomeMain_01">我的收入</p>
			<p class="myincomeMain_02">历史积累饭票</p>
			<p class="myincomeMain_03"><?php if(empty($total_income)): ?>F0.00<?php else: ?>F<?php echo ($total_income); endif; ?></p>
		</div>
		<div class="myincomeIntro">
			<table>
				<tr>
					<th>时间</th>
					<th>会员</th>
					<th>饭票</th>
				</tr>
				<?php if(is_array($income)): foreach($income as $key=>$v): ?><tr>
						<td><?php echo ($v["time"]); ?></td>
						<td><img src="<?php echo ($v["headimgurl"]); ?>"><?php echo ($v["nickname"]); ?></td>
						<td>+F<?php echo ($v["income"]); ?></td>
					</tr><?php endforeach; endif; ?>
			</table>
		</div>
		<div class="myincomeHind"><p>您的推荐人是<?php echo ($nickname); ?></p></div>
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
			debugger
			$.ajax({
				url: "<?php echo U('Spokesman/my_income_more');?>",
				type: 'post',
				data: {'page': page,'count':count},
				success: function (data) {
					debugger
					var html = "";
					$.each(data, function (k, v) {
						html += '<tr><td>'+v.time+'</td><td><img src="'+v.headimgurl+'">'+v.nickname+'</td><td>+F'+v.income+'</td></tr>';
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
		var $arrUl = $(".myincomeIntro table");
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