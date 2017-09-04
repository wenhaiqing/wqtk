<?php defined('IN_IA') or exit('Access Denied');?>
		<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_head', TEMPLATE_INCLUDEPATH)) : (include template('public_head', TEMPLATE_INCLUDEPATH));?>
		<!--中间内容开始-->
		<section>
		    <section class="hbox stretch">
		    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_left', TEMPLATE_INCLUDEPATH)) : (include template('public_left', TEMPLATE_INCLUDEPATH));?>
		    <!--右边框架-->
			  <section id="content">
			    <section class="vbox">
			        <section class="scrollable padder" style="padding-bottom:50px;">
                        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                          <li><a href="<?php  echo $this->createWebUrl('index')?>"><i class="fa fa-home"></i> 首页  / 商品管理</a></li>
                          <li class="active">专题管理</li>
                        </ul>
                        <ul class="nav nav-tabs">
                            <li <?php  if($_GPC['op']=='post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('zttype', array('op' => 'post'));?>">添加</a></li>
                            <li <?php  if($_GPC['op']=='display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('zttype', array('op' => 'display'));?>">管理</a></li>
                        </ul>
			            <!--编辑内容-->
                        <?php  if($operation == 'post') { ?>
                        <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h3 class="panel-title">
                               编辑专题
                              </h3>
                           </div>
                           <div class="panel-body">

                                  <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">专题名称</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" name="title" value="<?php  echo $item['title'];?>"  placeholder="">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">排序</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" name="px" value="<?php  echo $item['px'];?>"  placeholder="请输入数字">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 control-label">图片</label>
                                    <div class="col-sm-9">
                                      <?php  echo tpl_form_field_image('picurl',$item['picurl'])?>
                                      <span class="help-block">尺寸785*305</span>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">外链</label>
                                    <div class="col-sm-9">
                                      <input type="text" class="form-control" name="wlurl" value="<?php  echo $item['wlurl'];?>"  placeholder="如：http:// 不是外链，可以不填">
                                    </div>    
                                  </div>

                                  <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-9">
                                       <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
                                       <input type="submit" name="submit" class="btn btn-primary" value="提交"  class="btn btn-primary"/>
                                       <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                    </div>
                                  </div>
                           </div>
                        </div>
                                 
                        </form>
                        <?php  } else if($operation == 'display') { ?>
                        <div class="panel panel-default">
                              <table class="table">
                                  <th >排序</th>
                                  <th>名称</th>
                                  <th>图片</th>
                                  <th  style="text-align:right;">操作</th>

                              <?php  if(is_array($list)) { foreach($list as $item) { ?>
                                <tr>
                                  <td><?php  echo $item['px'];?></td>
                                  <td><?php  echo $item['title'];?></td>
                                  <td><img src="<?php  echo tomedia($item['picurl'])?>" width="50"></td>

                                  <td style="text-align:right;">
                                    <a href="<?php  echo $this->createWebUrl('zttype', array('id' => $item['id'], 'op' => 'post'))?>" title="编辑" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>编辑</a>
                                    <a href="<?php  echo $this->createWebUrl('zttype', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('此操作不可恢复，确认删除？');return false;" title="删除" class="btn btn-sm btn-default"><i class="fa fa-remove"></i>删除</a>
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


</body>
</html>