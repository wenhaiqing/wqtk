<?php defined('IN_IA') or exit('Access Denied');?><?php  $newUI = true;?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
.table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{white-space:nowrap;}
</style>
<ul class="nav nav-tabs">
	<li <?php  if($do == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo url('mc/fangroup/display');?>">粉丝标签</a></li>
</ul>
<?php  if($do == 'display') { ?>
<div class="clearfix" ng-controller="GroupListCtrl" ng-cloak>
<form action="<?php  echo url('mc/fangroup/post');?>" method="post" id="form">
	<input type="hidden" name="acid" value="<?php  echo $acid;?>">
	<div class="panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover" style="width:100%;" cellspacing="0" cellpadding="0">
			<thead class="navbar-inner">
				<tr>
					<th width="20%">标签名称</th>
					<th width="20%"></th>
					<th width="20%">标签id</th>
					<th width="20%">标签内用户数量</th>
					<th width="20%">操作</th>
				</tr>
			</thead>
			<tbody>
					<tr ng-repeat="tag in tags">
						<input type="hidden" name="tagid[]" value="{{tag.id}}">
						<input type="hidden" name="origin_name[]" value="{{tag.name}}">
						<td ng-if="tag.id == 1 || tag.id == 2">
							<input type="text" class="form-control" style="width:250px;" name="tagname[]" value="{{tag.name}}" readonly>
						</td>
						<td ng-if="tag.id != 1 && tag.id != 2">
							<input type="text" class="form-control" style="width:250px;" name="tagname[]" value="{{tag.name}}">
						</td>
						<td class="text-left">
							<span ng-if="tag.id == 1 || tag.id == 2">
								<span class="label label-danger">系统标签,不能修改</span>
							</span>
						</td>
						<td>{{tag.id}}</td>
						<td>{{tag.count}}</td>
						<td>
							<span ng-if="tag.id != 1 && tag.id != 2">
								<a ng-click="deltag(tag.id)" href="javascript:;" class="btn btn-danger tag-{{tag.id}}">删除标签</a>	
							</span>
						</td>
					</tr>
					<tr id="position">
						<td colspan="5"><a href="javascript:;" ng-click="addtag()"><i class="fa fa-plus-circle"></i> 添加新标签</a></td>
					</tr>
					<tr>
						<td colspan="5">
							<button ng-click="submit()" type="button" class="btn btn-primary span2">保存</button>
						</td>
					</tr>
			</tbody>
		</table>
	</div>
	</div>
</form>
</div>
<script type="text/javascript">
	$(function(){
		angular.module('fansApp').value('config', {
			'tags' :  <?php echo !empty($tags) ? json_encode($tags) : 'null'?>,
			'delurl' : '<?php  echo url('mc/fangroup/del');?>'
		});
		angular.bootstrap(document, ['fansApp']);
	});
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
