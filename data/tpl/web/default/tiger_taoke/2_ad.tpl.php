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
			        <section class="scrollable padder">
                        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                          <li><a href="index.html"><i class="fa fa-home"></i> 首页 </a></li>
                          <li class="active">广告管理</li>
                        </ul>
                        <!--编辑内容-->
                        <?php  if($operation == 'post') { ?>

                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h3 class="panel-title">
                               编辑广告
                              </h3>
                           </div>
                           <div class="panel-body">
                                <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
                                  <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">名称</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" name="title" value="<?php  echo $item['title'];?>"  placeholder="名称">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 control-label">图片</label>
                                    <div class="col-sm-10">
                                      <?php  echo tpl_form_field_image('pic',$item['pic'])?>
                                    </div>
                                  </div> 
                                  <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">网址</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" name="url" value="<?php  echo $item['url'];?>"  placeholder="http://">
                                    </div>
                                  </div>                   
                                  <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                       <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
                                       <input type="submit" name="submit" class="btn btn-default" value="提交"  class="btn btn-primary"/>
                                       <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                    </div>
                                  </div>
                                </form>
                           </div>
                        </div>


                        <script language='javascript'>
                          require(['jquery', 'util'], function($, u){
                            $(function(){
                              $('.richtext-clone').each( function() {
                                u.editor(this);
                              });
                            });
                          });
                        </script>

                        <?php  } else if($operation == 'display') { ?>
                        <div class="panel panel-default">
                              <table class="table">
                                  <th>名称</th>
                                  <th>图片</th>
                                  <th>网址</th>
                                  <th  style="text-align:right;">操作</th>

                              <?php  if(is_array($list)) { foreach($list as $item) { ?>
                                <tr>
                                  <td><?php  echo $item['title'];?></td>
                                  <td><img src="/attachment/<?php  echo $item['pic'];?>" height='100'/></td>
                                  <td><?php  echo $item['url'];?></td>
                                  <td style="text-align:right;">
                                    <a href="<?php  echo $this->createWebUrl('ad', array('id' => $item['id'], 'op' => 'post'))?>" title="编辑" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>编辑</a>
                                    <a href="<?php  echo $this->createWebUrl('ad', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('此操作不可恢复，确认删除？');return false;" title="删除" class="btn btn-sm btn-default"><i class="fa fa-remove"></i>删除</a>
                                  </td>
                                </tr>
                                <?php  } } ?>
                               </table>
                         
                        </div>
                        <?php  } ?>

  
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