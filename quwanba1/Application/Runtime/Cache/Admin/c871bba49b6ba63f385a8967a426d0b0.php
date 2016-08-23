<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="/quwanba1/Public/Admin/Images/favicon.png" type="image/png">

  <title>Bracket Responsive Bootstrap3 Admin</title>

  <link href="/quwanba1/Public/Admin/Css/style.default.css" rel ="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="notfound">
<section>
  
  <div class="notfoundpanel">
    <h2>恭喜你!</h2>
    <h3><?php echo ($message); ?></h3>
    <p class="jump">
        <b id="wait"><?php echo ($waitSecond); ?></b> 秒后页面将自动跳转
    </p>
    <div>
    <a id="href" id="btn-now" class="btn btn-success" href="<?php echo ($jumpUrl); ?>">立即跳转</a> 
    <button id="btn-stop" class="btn btn-success" type="button" onclick="stop()">停止跳转</button> 
    <a id="href" id="btn-now" class="btn btn-success" href="<?php echo U('Public/logout');?> >">重新登录</a> 
</div>

  </div><!-- notfoundpanel -->
  
</section>


<script src="/quwanba1/Public/Admin/Js/jquery-1.11.1.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/jquery-migrate-1.2.1.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/bootstrap.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/modernizr.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/jquery.sparkline.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/jquery.cookies.js"></script>

<script src="/quwanba1/Public/Admin/Js/toggles.min.js"></script>
<script src="/quwanba1/Public/Admin/Js/retina.min.js"></script>

<script>
    (function(){
 var wait = document.getElementById('wait'),href = document.getElementById('href').href;
 var interval = setInterval(function(){
        var time = --wait.innerHTML;
        if(time <= 0) {
            location.href = href;
            clearInterval(interval);
        };
     }, 1000);
  window.stop = function (){
         console.log(111);
            clearInterval(interval);
 }
 })();
</script>

</body>
</html>