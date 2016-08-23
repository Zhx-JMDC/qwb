$(document).ready(function(){
	// $("#footer li").bind("click",function(){
	// 	var addr = $(this).find(".footer_botton_p1 img").attr("src");
	// 	var addrLength = addr.length;
	// 	// console.log(addr[addrLength-5]);
	// 	var liLength = $("#footer li").length;
	// 	var changePic = addr.replace(/[^0-9]+/g, '');
	// 	for(var i = 0,j=1; i < liLength; i++,j++){
	// 		$("#footer li:eq("+i+") img" ).attr("src",img+"/bottom_0"+j+".png")
	// 		// $("#footer li:eq("+i+") img" ).attr("src","images/bottom_0"+j+".png")
	// 		// console.log(i);
	// 	}
	// 	$("#footer li .footer_botton_p2").removeClass("footer_botton_p2-Clicked");
		
	// 	if($(this).find(".footer_botton_p2").hasClass("footer_botton_p2-Clicked")){
	// 		// console.log("1");
	// 	}else{
	// 		$(this).find(".footer_botton_p1 img").attr("src",img+"/bottom_"+changePic+"_bc.png")
	// 		// $(this).find(".footer_botton_p1 img").attr("src","images/bottom_"+changePic+"_bc.png")
	// 	}
	// 	$(this).find(".footer_botton_p2").addClass("footer_botton_p2-Clicked");
	// });
		// 新版本底部
	$("#footer li").bind("click",function(){
		$("#footer li").removeClass("footer_bottom-Clicked");
		$(this).addClass("footer_bottom-Clicked");
	});
	$(".footerColect").bind("click",function(){
		$(".footerColect .footer_botton_p1 img").attr("src",img+"/bottom_collect_bc.png");
	});
	$(".friendsBar li").bind("click",function(){
		$(".friendsBar li").removeClass("friendsBar-clicked");
		$(this).addClass("friendsBar-clicked");
	});
	$(".shopllBar li").bind("click",function(){
		$(".shopllBar li").removeClass("friendsBar-clicked");
		$(this).addClass("friendsBar-clicked");
	});
	// $(".myorderPic_img img").hover(function() {
	// 	$(".myorder_code_big").show();
	// }, function() {
	// 	$(".myorder_code_big").hide();
	// });
	// $(".orderPic_02 img").hover(function() {
	// 	$(".myorder_code_big").show();
	// }, function() {
	// 	$(".myorder_code_big").hide();
	// });
	$(".shopinBar li").bind("click",function(){
		$(".shopinBar li").removeClass("shopinBar_Clicked");
		$(this).addClass("shopinBar_Clicked");
	});
	$(".shopinClass li").bind("click",function(){
		$(this).toggleClass("shopinClass_Clicked");
	});
	$(".buyticDetail_num_up").bind("click",function(){
		var cutnum = $(".buyticDetail_num_change").val().replace(/[^0-9]+/g, '');
		// console.log(cutnum);
		if(cutnum<=1){
			cutnum=1;
		}else{
			cutnum --;
		// console.log(cutnum);
		}
		$(".buyticDetail_num_change").val(cutnum);
	});
	$(".buyticDetail_num_down").bind("click",function(){
		var upnum = $(".buyticDetail_num_change").val().replace(/[^0-9]+/g, '');
		// var number2 = number;
		// number -= 1;
		// cutnum ++ ;
		// var gettype=Object.prototype.toString;
		// console.log(gettype.call(cutnum));
		upnum ++;
		// console.log(nn);
		$(".buyticDetail_num_change").val(upnum);
	});
	$("#daywrapper_ul").on("click","li",function(){
		$("#daywrapper_ul li").removeClass("daywrapper_clicked");
		$(this).addClass("daywrapper_clicked");
	});

	$(".lazy").lazyload({effect: "fadeIn"});
});