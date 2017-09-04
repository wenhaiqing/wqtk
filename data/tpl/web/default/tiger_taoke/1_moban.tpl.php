<?php defined('IN_IA') or exit('Access Denied');?>
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
                          <li><a href="/"><i class="fa fa-home"></i> 首页  </a></li>
                          <li class="active">模版消息</li>
                        </ul>

                        <ul class="nav nav-tabs">
                            <li <?php  if($_GPC['op']=='post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('moban', array('op' => 'post'));?>">添加</a></li>
                            <li <?php  if($_GPC['op']=='display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('moban', array('op' => 'display'));?>">管理</a></li>
                        </ul>
			            <!--编辑内容-->
                        <?php  if($operation == 'post') { ?>
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h3 class="panel-title">
                               添加/编辑模版消息
                              </h3>
                           </div>
                           <div class="panel-body">
                                <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">

                                <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">模版名称</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <input type="text" name="title" value="<?php  echo $item['title'];?>" class="form-control" placeholder="" >
                                            <span class="help-block"></span>
                                        </div>
                                </div>

                                <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">提醒消息模版ID</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <input type="text" name="mbid" value="<?php  echo $item['mbid'];?>" class="form-control" placeholder="如：fCfGChknZxAVu2Ev_ZADMxZq553EdHvKSefMjQ36J_8" >
                                            <span class="help-block"></span>
                                        </div>
                                   </div>

                                   <div class="form-group">
                                        <label class="col-sm-2 control-label must">头部标题</label>  
                                        <div class="col-sm-9 title" style="padding-right:0">
                                            <textarea name="first" class="form-control" value="" data-rule-required="true" placeholder="{{first.DATA}}" aria-required="true"><?php  echo $item['first'];?></textarea>
                                            <span class="help-block">对填充模板 {{first.DATA}} 的值 </span>
                                        </div>
                                        <div class="col-sm-1" style="padding-left:0;">
                                               <input type="color" name="firstcolor" value="<?php  echo $item['firstcolor'];?>" style="width:32px;height:32px;">
                                        </div>
                                   </div>

                                   <!--增加ADD-->
                                   <div id="type-items">
                                       <div class="form-group"  id="mbid1" style="display: none;">
                                            <div class="form-group key_item">
                                                <label class="col-sm-2 control-label">keyword.DATA</label>
                                                <div class="col-sm-7" style="padding:0;padding-left:15px;">
                                                    <textarea name="zjvalue[]" class="form-control" placeholder="{{keyword.DATA}}"><?php  echo $tp['zjvalue'];?></textarea>
                                                </div>
                                                <div class="col-sm-1" style="padding:0">
                                                   <input type="color" name="zjcolor[]" value="<?php  echo $tp['zjcolor'];?>" style="width:32px;height:32px;">
                                                </div>
                                                 <a class="btn btn-default" href="javascript:;" onclick="$(this).closest('.key_item').remove()"><i class="fa fa-remove"></i> 删除</a>
                                            </div>
                                       </div>
                                   </div>
                                   <!--ADD结束-->

                                   <div class="form-group">
                                       <?php  if(is_array($tplist)) { foreach($tplist as $k=>$tp) { ?>
                                            <div class="form-group key_item">
                                                <label class="col-sm-2 control-label">keyword<?php  echo ++$k?>.DATA</label>
                                                <div class="col-sm-7" style="padding:0;padding-left:15px;">
                                                    <textarea name="zjvalue[]" class="form-control" placeholder="{{keyword.DATA}}"><?php  echo $tp['zjvalue'];?></textarea>
                                                </div>
                                                <div class="col-sm-1" style="padding:0">
                                                   <input type="color" name="zjcolor[]" value="<?php  echo $tp['zjcolor'];?>" style="width:32px;height:32px;">
                                                </div>
                                                 <a class="btn btn-default" href="javascript:;" onclick="$(this).closest('.key_item').remove()"><i class="fa fa-remove"></i> 删除</a>
                                            </div>
                                      <?php  } } ?>
                                  </div>


                                   <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-9 col-xs-12">

                                            <a class="btn btn-default btn-add-type" onclick="onAdd1(this)"><i class="fa fa-plus" title=""></i> 增加一条键</a>
                                            <!--span class="help-block"> 变量：#时间# #昵称# #粉丝编号#</span-->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <label class="col-sm-2 control-label">尾部描述</label>
                                           <div class="col-sm-9 title" style="padding-right:0">
                                                <textarea name="remark" class="form-control" placeholder="{{remark.DATA}}"><?php  echo $item['remark'];?></textarea>
                                              <span class="help-block">填充模板 {{remark.DATA}} 的值。</span>
                                            </div>
                                            <div class="col-sm-1" style="padding-left:0">
                                                    <input type="color" name="remarkcolor" value="<?php  echo $item['remarkcolor'];?>" style="width:32px;height:32px;">
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">消息链接</label>
                                        <div class="col-xs-12 col-sm-9">
                                            <input type="text" name="turl" value="<?php  echo $item['turl'];?>" class="form-control" placeholder="http://" >
                                            <span class="help-block"></span>
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
function onAdd1(obj){
	$(obj).parent().parent().before('<div class="type-items">'+$('#mbid1').html()+'</div>');
}

</script>

<?php  } else if($operation == 'display') { ?>
<div class="panel panel-default">
      <table class="table">
          <th>id</th>
          <th>名称</th>
          <th>模版ID</th>
          <th  style="text-align:right;">操作</th>

      <?php  if(is_array($list)) { foreach($list as $item) { ?>
        <tr>
          <td><?php  echo $item['id'];?></td>
          <td><?php  echo $item['title'];?></td>
          <td><?php  echo $item['mbid'];?></td>
          <td style="text-align:right;">
            <a href="<?php  echo $this->createWebUrl('moban', array('id' => $item['id'], 'op' => 'post'))?>" title="编辑" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>编辑</a>
            <a href="<?php  echo $this->createWebUrl('moban', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('此操作不可恢复，确认删除？');return false;" title="删除" class="btn btn-sm btn-default"><i class="fa fa-remove"></i>删除</a>
          </td>
        </tr>
        <?php  } } ?>
       </table>
 
</div>
<?php  } ?>

<script>
require(['jquery', 'util'], function($, u){
	$(function(){ $('.richtext-clone').each( function() { u.editor(this); });		});
  $('.btn').hover(function(){$(this).tooltip('show');},function(){$(this).tooltip('hide');});
  $('.full').hover(function(){$(this).tooltip('show');},function(){$(this).tooltip('hide');});
});
</script>

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