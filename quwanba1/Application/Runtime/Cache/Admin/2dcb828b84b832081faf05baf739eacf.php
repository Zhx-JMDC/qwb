<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <link rel="shortcut icon" href="/quwanba1/Public/Admin/Images/favicon.png" type=  "image/png">
    <link rel="stylesheet" type="text/css" href="/quwanba1/Public/Admin/Css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/quwanba1/Public/Admin/Css/style.default.css" >
</head>
<body>
<style>
    .modal-body ul{
        list-style: none;
        padding: 0;
    }
    .modal-body ul li{
        border: 1px solid #e5e5e5;
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" id="close" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">选择商家</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="text" id="shop_id" name="search" placeholder="输入商家ID(序号)" class="form-control">
                        <span class="input-group-btn">
                            <button type="button" onclick="searchShop()" data-toggle="tooltip" id="button-search" class="btn btn-primary"><i class="fa fa-search"></i>搜索</button>&nbsp;&nbsp;
                            <button type="button" onclick="chooseShop()" class="btn btn-primary">确定</button>
                        </span>
                    </div>
                </div>
            </div>
            <hr />
            <div id="content"></div>
        </div>
        <div class="modal-footer"><?php echo $_page; ?></div>
    </div>
</div>
<script src="/quwanba1/Public/Admin/Js/jquery-1.11.1.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/jquery-migrate-1.2.1.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/jquery-ui-1.10.3.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/bootstrap.min.js"></script>
<script>
    function searchShop(){
        var id = $('#shop_id').val();
        if(id !=""){
            $.ajax({
                url: "<?php echo U('Shop/search_shop');?>",
                type: 'post',
                data: {'id': id},
                success: function (data) {
                    var html="";
                    if(!data.id){
                        html = "您搜索的商家不存在";
                    }else{
                        html = "<table border='0'><tr><td style='width: 20%'>序号</td><td style='width: 20%'>名称</td><td style='width: 30%'>审核状态</td><td style='width: 30%'>区域</td></tr><tr><td id='id'>"+data.id+"</td><td id='shopname'>"+data.shop_name+"</td><td>"+data.conditions+"</td><td>"+data.province+data.city+data.district+"</td></tr></table>";
                    }
                    $('#content').html(html);
                }
            });
        }
    }

    function chooseShop(){
        var id = $('#id').html();
        var shopname = $('#shopname').html();
        $('#shop_name').val(shopname);
        $('#shopId').val(id);
        $('#close').click();
    }
</script>
</body>
</html>