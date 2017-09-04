<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en" class="app">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>后台管理</title>
	<link rel="stylesheet" href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/public/css/app.v2.css" type="text/css" />
	<!--[if lt IE 9]> 
	<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/public/js/ie/html5shiv.js" cache="false"></script> 
	<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/public/js/ie/respond.min.js" cache="false"></script> 
	<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/public/js/ie/excanvas.js" cache="false"></script> 
	<![endif]-->
</head>
<body>
	<section class="vbox">
		<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_head', TEMPLATE_INCLUDEPATH)) : (include template('public_head', TEMPLATE_INCLUDEPATH));?>
		<!--中间内容开始-->
		<section>
		    <section class="hbox stretch">
		    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_left', TEMPLATE_INCLUDEPATH)) : (include template('public_left', TEMPLATE_INCLUDEPATH));?>
		    <!--右边框架-->
			  <section id="content">
			    <section class="vbox">
			        <section class="scrollable padder"  style="padding-bottom:50px;">
                        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                          <li><a href="index.html"><i class="fa fa-home"></i> 首页  / 淘客商品</a></li>
                          <li class="active">导入商品</li>
                        </ul>
                        <!--编辑内容-->
                        


                        <form method="post" enctype="multipart/form-data">
                            <div class="panel panel-default">
                               <div class="panel-heading">
                                  <h3 class="panel-title">
                                   淘客商品导入
                                  </h3>
                               </div>
                               <div class="panel-body">
                                   <div class="form-group" style="height:40px;">
                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">佣金类型</label>
                                            <div class="col-xs-12 col-sm-9">
                                               <label class="checkbox-inline">
                                                  <input type="radio" name="yjtype"  value="1"> 普通淘客商品
                                               </label>
                                               <label class="checkbox-inline">
                                                  <input type="radio" name="yjtype"  value="2"> 鹊桥高佣金商品
                                               </label>
                                                <span class="help-block" style="color:#ff0000">这是必选的，普通和鹊桥高佣金的是不一样的，不要选择错了</span>
                                            </div>
                                   </div>

                                   <div class="form-group" style="height:40px;">
                                        <label for="type" class="col-sm-2 control-label">商品类别</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                            <select class="form-control" name="type" id="type">
                                               <?php  if(is_array($fzlist)) { foreach($fzlist as $v) { ?>
                                                <option <?php  if(!empty($v) && $v['id'] == $item['type']) { ?>selected<?php  } ?> value="<?php  echo $v['id'];?>"><?php  echo $v['title'];?></option>
                                               <?php  } } ?>
                                            </select>                
                                            </div>                                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="type" class="col-sm-2 control-label">导入商品</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="excelfile" class="form-control" />
                                            <div class="help-block">请上传 xlsx 格式的Excel文件</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <input name="submit" type="submit" value="提交" class="btn btn-primary col-lg-1">
                                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                    </div>
                               </div>
                            </div>
                        </form>
                        <!--编辑内容结束-->			            
			        </section>
			        <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_bottom', TEMPLATE_INCLUDEPATH)) : (include template('public_bottom', TEMPLATE_INCLUDEPATH));?>
			    </section>
			    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
			  </section>
			  <aside class="bg-light lter b-l aside-md hide" id="notes">
			       <div class="wrapper">不知道放什么</div>
			  </aside>
			<!--右边框架结束-->
			</section>
		  </section>
		<!--中间内容结束-->
	</section>

<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/public/js/app.v2.js"></script>
</body>
</html>