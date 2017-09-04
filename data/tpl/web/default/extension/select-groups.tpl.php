<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/header-gw', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template("extension/$action-tabs", TEMPLATE_INCLUDEPATH)) : (include template("extension/$action-tabs", TEMPLATE_INCLUDEPATH));?>
<div class="clearfix">
	<h5 class="page-header">安装 <?php  if($action == 'module') { ?><?php  echo $module['title'];?><?php  } else { ?>模板<?php  } ?></h5>
	<div class="alert alert-info">
		您正在安装 <?php  if($action == 'module') { ?><?php  echo $module['title'];?> 模块<?php  } else { ?>模板<?php  } ?>. 请选择哪些公众号服务套餐组可使用 
		<?php  if($action == 'module') { ?><?php  echo $module['title'];?> 功能<?php  } else { ?>该模板<?php  } ?> .
	</div>
	<form class="form-horizontal form" action="" method="post" id="form1">
		<h5 class="page-header">可用的公众号服务套餐组 <small>这里来定义哪些公众号服务套餐组可使用 <?php  if($action == 'module') { ?><?php  echo $module['title'];?> 功能<?php  } else { ?>该模板<?php  } ?></small></h5>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">公众号服务套餐组</label>
			<div class="col-sm-10 col-xs-12">
				<div class="checkbox disabled">
					<label><input type="checkbox" name="" value="" disabled>基础服务</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">系统</span>
				</div>
				<div class="checkbox disabled">
					<label><input type="checkbox" name="" value="" checked disabled>所有服务</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">系统</span>
				</div>
				<?php  if(is_array($groups)) { foreach($groups as $group) { ?>
					<div class="checkbox">
						<label><input type="checkbox" name="group[]" value="<?php  echo $group['id'];?>"><?php  echo $group['name'];?></label> 
					</div>
				<?php  } } ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
			<div class="col-sm-10 col-xs-12">
				<input type="submit" class="btn btn-primary" name="submit" value="确定继续安装 <?php  echo $module['title'];?>">
			</div>
		</div>
		<input type="hidden" name="flag" value="1">
		<input type="hidden" name="tid" value="<?php  echo $tid;?>">
	</form>
	<script>
		$('#form1').submit(function(){
			var num = $("input[name='group[]']:checked").length;
			if(num == 0) {
				return confirm("您没有选择可使用该模块/模板的公众号服务套餐组,模块/模板安装成功后可在 公众号服务套餐 编辑");
			}
			return true;
		});
	</script>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-gw', TEMPLATE_INCLUDEPATH));?>
