<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Examples</title>
<meta name="description" content="">
<script src="<?php echo MODULE_URL;?>CheckSubmit.js"></script>
<meta name="keywords" content="">
<style>
    img{
        width: 240px;
        height: 150px;
        max-width: 100%;
        border: 1px solid #ccc;
    }
</style>
<link href="" rel="stylesheet">
</head>
<body>
	<ul class="nav nav-tabs">
		<li class="active"><a href="javascript:void(0);">填报数据</a></li>
	</ul>

    <form action="" class="form-horizontal" method="POST">
    	<div class="panel panel-primary">
    		<div class="panel-heading">
    			<h3 class="panel-title"><?php  echo $data['name'];?>&nbsp;</h3>
    		</div>
    		<div class="panel-body">
    			<?php  if(is_array($list['0'])) { foreach($list['0'] as $key => $item) { ?>
    			<?php  echo createHtml($item,$list['1'][$key],$list['2'][$key],$key);?>
    			<?php  } } ?>
    		</div>
    	</div>
    	<div class="form-group">
    		<div class="col-sm-offset-2 col-sm-10">
    			<input type="submit" name="submit" class="btn btn-default" value="提交"/>
    			<input type="reset" class="btn btn-default" value="重置" />
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
    		</div>
    	</div>
    </form>
    <script>
    (function(){
        $(".form-horizontal input[name='tgnet_htb_image']").attr("title","图片");
        $(".form-horizontal input[name='tgnet_htb_image']").attr("name","tgnet_htb[]");
        $(".form-horizontal input[name='tgnet_htb_titme']").attr("title","日期或时间");
        $(".form-horizontal input[name='tgnet_htb_time']").eq(0).attr("name","tgnet_htb[]");
        /*$(".form-horizontal img[class*='img-responsive']").css({
                "width":"120px",
                "height":"80px",
            });*/
        $(".form-horizontal img[class*='img-responsive']").attr(
            "width","120");
        $(".form-horizontal img[class*='img-responsive']").attr(
            "height","80");

        $(".form-group input[name='submit']").CheckSubmit("isNull",["tgnet_htb[]"]);
        //$(".form-group input[name='submit']").CheckSubmit("checkMail",["tgnet_htb[]"]);
    })();
    </script>
</body>
</html>