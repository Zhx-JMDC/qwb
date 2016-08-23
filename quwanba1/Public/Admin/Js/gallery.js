/**
 * Created by Administrator on 2015/7/13.
 */

/*html代码示例
* */

/*
 <div class="col-lg-6">
 <div class="gallery ">
 <div class="main-pic">
 <a>
 <span>
 <img src="{$goods['goods_albums'][0]['origin']}" id="show-origin">
 </span>
 </a>
 </div>
 <div style="display: block">
 <div class="tab-pic">
 <ul class="list-inline" id="tab-pic">
 <volist name="goods.goods_albums" id="vo">
 <li>
 <div class="pic-50-50">
 <a src="a.jpg" data-src="{$vo.origin}">
 <img src="{$vo.thumb}">
 </a>
 </div>
 </li>
 </volist>
 </ul>
 </div>
 <a class="tag-right pull-right"><span class="fa fa-angle-right "></span></a>
 <a class="tag-left pull-left"><span class="fa fa-angle-left"></span></a>
 </div>
 </div>
 <div class="social-info">
 <ol class="list-inline">
 <li><span class="fa fa-star"></span>销量（{$goods['num_sell']}）</li>
 </ol>
 </div>
 </div>
 <div class="col-lg-6">
 <h4>{$goods.goods_detail.content}</h4>
 <table class="table">
 <tr><td>名称</td><td>{$goods.name}</td></tr>
 <tr><td>价格</td><td class="text-right">{$goods.price}</td></tr>
 <tr><td>库存信息</td></tr>
 <volist name="goods.goods_repertory" id="vo">
 <eq name="vo.sku" value="$repertory.sku">
 <tr>
 <td class="text-danger">{$vo.spec_style}</td>
 <td class="text-right text-danger">{$vo.num}</td>
 </tr>
 <else/>
 <tr>
 <td>{$vo.spec_style}</td>
 <td class="text-right">{$vo.num}</td>
 </tr>
 </eq>
 </volist>
 </table>
 </div>
*/
 $('.gallery .tab-pic a').on('click',function(){
    var temp = $(this).attr('data-src');
    $('#show-origin').attr('src',temp);
});

var length = 0;
var gallery_min_length = 0;
var gallery_max_length = $('#tab-pic').children().length*50;
gallery_max_length = - gallery_max_length;
console.log("gallery max length : "+gallery_max_length);
$('.tag-right').click(function(){

    if(length > gallery_max_length) length -= 25;
    $(this).prop('disabled',true);
    $('#tab-pic').animate({
        left:''+length+'px',
    },'fast',function(){
        $(this).prop('disabled',false);
    });
})
$('.tag-left').click(function(){
    if(length < gallery_min_length) length += 25;
    $(this).prop('disabled',true);
    $('#tab-pic').animate({
        left:''+length+'px',
    },'fast',function(){
        $(this).prop('disabled',false);
    });
})