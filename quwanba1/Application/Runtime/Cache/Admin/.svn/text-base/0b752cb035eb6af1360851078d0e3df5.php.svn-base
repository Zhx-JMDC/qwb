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
    
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-product" target-form="target-form" data-toggle="tooltip" title="保存" class="btn btn-primary " ><i class="fa fa-save"></i></button>
                <a href="#"  onclick="location.reload();" data-toggel="tooltip" title="刷新" class="btn btn-success"> <i class="fa fa-refresh"></i></a>
                <a href="#" onclick="history.back(-1);return false;" data-toggle="tooltip" title="取消" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h2>修改商品</h2>
            <ul class="">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo U('Ad/ad_edit');?>" method="post" enctype="multipart/form-data" id="form-product" class="target-form form-horizontal">
                    <div class="tab-content">
                        <!--常规-->
                        <div class="tab-pane active" id="tab-general">
                            <div class="tab-pane" id="language">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo ($ad["id"]); ?>">
                                    <label class="control-label">广告名称</label></br>
                                    <input name="discribe" placeholder="广告名称" class="form-control" value="<?php echo ($ad["discribe"]); ?>" required/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">图片</label></br>
                                    <img src="<?php echo ($ad["path"]); ?>">
                                    <input type="file" name="pic" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">链接</label>
                                    <input name="skip_url" placeholder="链接"  class="form-control" value="<?php echo ($ad["skip_url"]); ?>" required/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">展示位:</label>&nbsp;&nbsp;&nbsp;
                                    <label class="radio-inline">
                                        <input type="radio" name="positions" value="2"> 商城首页顶部广告位
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="positions" value="3"> 商城首页底部广告位
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">排序权重</label>
                                    <input name="order" placeholder="排序权重"  class="form-control" value="<?php echo ($ad["order"]); ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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

    <link href="/quwanba1/Public/Admin/Js/summernote/summernote.css" rel="stylesheet" />
    <script type="text/javascript" src="/quwanba1/Public/Admin/Js/summernote/summernote.js"></script>
    <!--规格分类-->
    <script>
        var current_spec = "";
        function selectSpec(row) {
            $('#modal-spec').remove();
            current_spec = "spec-row"+row;
            $.ajax({
                url: Think.APP+'/Admin/Shop/Specification',
                dataType: 'html',
                beforeSend: function() {
                    $('#button-spec i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                    $('#button-spec').prop('disabled', true);
                },
                complete: function() {
                    $('#button-spec i').replaceWith('<i class="fa fa-pencil"></i>');
                    $('#button-spec').prop('disabled', false);
                },
                success: function(html) {
                    $('body').append('<div id="modal-spec" class="modal">' + html + '</div>');

                    $('#modal-spec').modal('show');
                }
            });
        }
    </script>
    <script>
        $("input[type='radio'][name=positions][value='<?php echo ($ad["position"]); ?>']").attr("checked",true);
        $('#input-detailpage').summernote({
            height: 300,
            onImageUpload: function(files, editor, welEditable) {
                data = new FormData();
                data.append("file", files['0']);
                $.ajax({
                    url     : "<?php echo U('Shop/edit_upload_img');?>",
                    type    : 'post',
                    data    : data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function(url){
                        editor.insertImage(welEditable, url);
                    }
                });
            }
        });
        $('#input-detail').summernote({
            height: 300,
            onImageUpload: function(files, editor, welEditable) {
                data = new FormData();
                data.append("file", files['0']);
                $.ajax({
                    url     : "<?php echo U('Shop/edit_upload_img');?>",
                    type    : 'post',
                    data    : data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function(url){
                        editor.insertImage(welEditable, url);
                    }
                });
            }
        });
        var image_row = 0;
        function addImage() {
            html  = '<tr id="image-row' + image_row + '">';
            html += '<td class="text-left"><input type="file" name="product_image'+image_row+'" value="" class="form-control" /></td>';
            html += '<td class="text-right"><input type="text" value="" class="form-control" value="qweq"/></td>';
            html += '<td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
            html += '</tr>';

            image_row++;
            $('#images tbody').append(html);
        }

        function removeImage(id,row){
            $('#image-rows'+row+'').remove();
            var imgDelete = $('#imgDelete').val();
            if(imgDelete == ''){
                imgDelete = id;
            }else{
                imgDelete += ','+ id;
            }
            $('#imgDelete').val(imgDelete);
        }
    </script>

</body>
</html>