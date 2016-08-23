<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="/quwanba1/Public/Admin/Js/jquery2.1.1.min.js"></script>
    <script type="text/javascript" src="/quwanba1/Public/Admin/Js/ajaxfileupload.js"></script>
</head>
<script>
    function aa(){
        $.ajaxFileUpload({
            url:'<?php echo U("Ad/aa");?>',
            secureuri: false,
            fileElementId:'file',
            dataType: 'json',//返回数据类型
            success: function (data, status){
                debugger
                alert(data);
            },
            error: function (data, status, e)//服务器响应失败处理函数
            {
                debugger
                alert(data.responseText);
            }
        });
    }
</script>
<body>
<input id="file" type="file" size="20" name="file" class="input">
<button onclick="aa()">上传</button>
</body>
</html>