<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link type="text/css" rel="stylesheet" href="./addons/tiger_jifenbao/css/base.css" />
<ul class="nav nav-tabs">
	<li <?php  if($operation == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('qungl', array('op' => 'post'));?>">添加</a></li>
	<li <?php  if($operation == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('qungl', array('op' => 'display'));?>">管理</a></li>
</ul>
<?php  if($operation == 'post') { ?>

<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title">
       编辑群
      </h3>
   </div>
   <div class="panel-body">
        <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">群名称</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="title" value="<?php  echo $item['title'];?>"  placeholder="群名称">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">排序</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="px" value="<?php  echo $item['px'];?>"  placeholder="请输入数字">
            </div>
          </div>
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">关键词</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="keyw" value="<?php  echo $item['keyw'];?>"  placeholder="关键词">
              <span class="help-block">这里设置的关键词要和回复规则里面的关键词一样</span>
            </div>
          </div>

          <!-- 是否自动审核-->
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">状态</label>
					<div class="col-sm-9">
						<label class="radio-inline">
							<input type="radio" name="type" value="1" <?php  if($item['type'] == '1') { ?>checked="true"<?php  } ?>>开启
						</label>
						<label class="radio-inline">
							<input type="radio" name="type" value="0" <?php  if($item['type'] == '0') { ?>checked="true"<?php  } ?>>不开启
						</label>
						<span class="help-block"></span>
					</div>
				</div>
          <!---->
          <!-- 是否自动审核-->
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">群类型</label>
					<div class="col-sm-9">
                        <label class="radio-inline">
							<input type="radio" name="qtype" value="1" <?php  if($item['qtype'] == '1') { ?>checked="true"<?php  } ?>>微信群
						</label>
						<label class="radio-inline">
							<input type="radio" name="qtype" value="2" <?php  if($item['qtype'] == '2') { ?>checked="true"<?php  } ?>>QQ群
						</label>
						
						<span class="help-block"></span>
					</div>
				</div>
          <!---->
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">限制人数</label>
            <div class="col-sm-10">
             <div class="input-group">
                 <input type="text" class="form-control" name="xzrs" value="<?php  echo $item['xzrs'];?>"  placeholder="填写数字">
                <span class="input-group-addon">人</span>
              </div>               
            </div>
          </div>
          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">群二维码</label>
            <div class="col-sm-10">
              
              <?php  echo tpl_form_field_wechat_image('picurl', $item['picurl'], '');?>

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
共：<?php  echo $total;?>个群
<div class="panel panel-default">
      <table class="table">
          <th  width='50'>排序</th>
          <th>群名称</th>
          <th width='80'>关键词</th>
          <th>加入人数</th>
          <th>类型</th>
          <th>状态</th>
          <th width='250' >操作</th>

      <?php  if(is_array($list)) { foreach($list as $item) { ?>
        <tr>
          <td><?php  echo $item['px'];?></td>
          <td><?php  echo $item['title'];?></td>
          <td><?php  echo $item['keyw'];?></td>
          <td><a href=""><span class="label  label-info">当前人数:<?php  echo $item['tjsum'];?>人</span><div style="height:5px;"></div><span class="label  label-info" >上线人数:<?php  echo $item['xzrs'];?>人</span></a></td>
          <td><?php  if($item['qtype'] ==1) { ?><span class="label  label-success" >微信群</span><?php  } else if($item['qtype']==2) { ?><span class="label label-danger"  data-original-title="正常接受兑换中" >QQ群</span><?php  } ?></td>
          <td><?php  if($item['type'] ==1) { ?><span class="label  label-success">已启用</span><?php  } else { ?><span class="label label-danger">未启用</span><?php  } ?></td>
          <td>
            <a href="<?php  echo $this->createWebUrl('qunmember', array('id' => $item['id']))?>" title="查看用户" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> 用户</a>
            <a href="<?php  echo $this->createWebUrl('qungl', array('id' => $item['id'], 'op' => 'post'))?>" title="编辑" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>编辑</a>
            <a href="<?php  echo $this->createWebUrl('qungl', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('此操作不可恢复，确认删除？');return false;" title="删除" class="btn btn-sm btn-danger"><i class="fa fa-remove"></i>删除</a>
          </td>
        </tr>
        <?php  } } ?>
       </table>
 <?php  echo $page;?>
</div>
<?php  } ?>

<script>
require(['jquery', 'util'], function($, u){
	$(function(){ $('.richtext-clone').each( function() { u.editor(this); });		});
  $('.btn').hover(function(){$(this).tooltip('show');},function(){$(this).tooltip('hide');});
  $('.full').hover(function(){$(this).tooltip('show');},function(){$(this).tooltip('hide');});
});
</script>


<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
