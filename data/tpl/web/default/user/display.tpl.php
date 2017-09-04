<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/header-gw', TEMPLATE_INCLUDEPATH));?>
<ol class="breadcrumb">
	<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
	<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
	<li class="active"><?php  if(intval($_GPC['status']) == 1) { ?>审核用户<?php  } else { ?>用户列表<?php  } ?></li>
</ol>
<script type="text/javascript">
	var u ={};
	u.deny = function(uid){
		var uid = parseInt(uid);
		if(isNaN(uid)) {
			return;
		}
		if(!confirm('确认要禁用/解禁此用户吗? ')) {
			return;
		}
		$.post('<?php  echo url('user/permission');?>', {'do': 'deny', uid: uid}, function(dat){
			if(dat == 'success') {
				location.href = location.href;
			} else {
				util.message('操作失败, 请稍后重试. ' + dat);
			}
		});
	};
	u.del = function(uid){
		var uid = parseInt(uid);
		if(isNaN(uid)) {
			return;
		}
		if(!confirm('确认要删除此用户吗? ')) {
			return;
		}
		$.post('<?php  echo url('user/edit');?>', {'do': 'delete', uid: uid}, function(dat){
			if(dat == 'success') {
				location.href = location.href;
			} else {
				util.message('操作失败, 请稍后重试. ' + dat);
			}
		});
	};
</script>
<ul class="nav nav-tabs">
	<li <?php  if(intval($_GPC['status']) != 1) { ?>class="active"<?php  } ?>><a href="<?php  echo url('user/display');?>">用户列表</a></li>
	<?php  if(!empty($settings['verify'])) { ?>
	<li <?php  if(intval($_GPC['status']) == 1) { ?>class="active"<?php  } ?>><a href="<?php  echo url('user/display', array('status' => 1));?>">审核用户</a></li>
	<?php  } ?>
	<li><a href="<?php  echo url('user/create');?>">添加用户</a></li>
</ul>
<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form action="" method="get" class="form-horizontal" role="form">
			<input type="hidden" name="c" value="user">
			<input type="hidden" name="a" value="display">
			<input type="hidden" name="status" value="<?php  echo $_GPC['status'];?>">
			<input type="hidden" name="endtime" value="<?php  echo $endtime;?>">
			<input type="hidden" name="group" value="<?php  echo $_GPC['group'];?>">
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">状态</label>
				<div class="col-sm-8 col-lg-9 col-xs-12">
					<div class="btn-group">
						<a href="<?php  echo filter_url('status:-1');?>" class="btn <?php  if($_GPC['status'] == -1 || $_GPC['status'] == '') { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">不限</a>
						<a href="<?php  echo filter_url('status:2');?>" class="btn <?php  if($_GPC['status'] == 2) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">启用</a>
						<a href="<?php  echo filter_url('status:1');?>" class="btn <?php  if($_GPC['status'] == 1) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">禁用</a>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">套餐结束时间</label>
				<div class="col-sm-8 col-lg-9 col-xs-12">
					<div class="btn-group">
						<a href="<?php  echo filter_url('endtime:0');?>" class="btn <?php  if($endtime == 0) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">不限</a>
						<a href="<?php  echo filter_url('endtime:-1');?>" class="btn <?php  if($endtime == -1) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">已到期</a>
						<a href="<?php  echo filter_url('endtime:3');?>" class="btn <?php  if($endtime == 3) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">三天内</a>
						<a href="<?php  echo filter_url('endtime:15');?>" class="btn <?php  if($endtime == 15) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">半月内</a>
						<a href="<?php  echo filter_url('endtime:30');?>" class="btn <?php  if($endtime == 30) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">一月内</a>
						<a href="<?php  echo filter_url('endtime:90');?>" class="btn <?php  if($endtime == 90) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">三月内</a>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">用户组</label>
				<div class="col-sm-8 col-lg-9 col-xs-12">
					<div class="btn-group">
						<a href="<?php  echo filter_url('group:0');?>" class="btn <?php  if($_GPC['group'] == 0) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">不限</a>
						<?php  if(is_array($usergroups)) { foreach($usergroups as $group) { ?>
						<a href="<?php  echo filter_url("group:" . $group['id']);?>" class="btn <?php  if($_GPC['group'] == $group['id']) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>"><?php  echo $group['name'];?></a>
						<?php  } } ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">用户名</label>
				<div class="col-sm-8 col-lg-9 col-xs-12">
					<input class="form-control" name="username" id="" type="text" value="<?php  echo $_GPC['username'];?>">
				</div>
				<div class="pull-right col-xs-12 col-sm-2 col-lg-2">
					<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="rule panel panel-default">
	<div class="table-responsive panel-body">
	<table class="table table-hover">
		<thead class="navbar-inner">
			<tr>
				<th style="width:150px;">用户名</th>
				<th style="width:200px;">身份</th>
				<th style="width:100px;">状态</th>
				<th style="min-width:180px;">注册时间</th>
				<th style="min-width:180px;">服务开始时间 ~~ 结束时间</th>
				<th></th>
				<th style="width:50px;">操作</th>
				<th style="width:100px;"></th>
				<th style="width:70px;"></th>
				<th style="width:100px;"></th>
			</tr>
		</thead>
		<tbody>
			<?php  if(is_array($users)) { foreach($users as $user) { ?>
			<tr>
				<td><?php  if(!$user['founder']) { ?><a href="<?php  echo url('user/edit', array('uid' => $user['uid']))?>"><?php  echo $user['username'];?></a><?php  } else { ?><?php  echo $user['username'];?><?php  } ?></td>
				<td>
				<?php  if($user['founder']) { ?>
					<span class="label label-success">管理员</span>
				<?php  } else if(isset($usergroups[$user['groupid']])) { ?>
					<span class="label label-info"><?php  echo $usergroups[$user['groupid']]['name'];?></span>
				<?php  } else { ?>
					<span class="label label-error">未分配</span>
				<?php  } ?>
				</td>
				<td>
					<?php  if(intval($user['status']) != 2) { ?>
						<span class="label label-danger">被禁止</span>
					<?php  } else { ?>
						<span class="label label-success">正常状态</span>
					<?php  } ?>
				</td>
				<td><?php  echo date('Y-m-d H:i:s', $user['joindate'])?></td>
				<td>
					<?php  echo date('Y-m-d', $user['starttime'])?>
					~~
					<?php  if(empty($user['endtime'])) { ?>
						永久有效
					<?php  } else { ?>
						<?php  echo date('Y-m-d', $user['endtime'])?>
					<?php  } ?>
				</td>
				<td>
					<?php  if($user['endtime'] != 0 && $user['endtime'] <= TIMESTAMP) { ?>
						<span class="label label-danger">服务已到期</span>
					<?php  } ?>
				</td>
				<td>
					<div>
						<a href="<?php  echo url('user/edit', array('uid' => $user['uid']))?>">编辑</a>&nbsp;&nbsp;
					</div>
				</td>
				<?php  if(empty($user['founder'])) { ?>
				<td>
					<div>
						<a href="<?php  echo url('user/permission', array('uid' => $user['uid']))?>">查看操作权限</a>
					</div>
				</td>
				<td>
					<div>
						<a href="javascript:;" onclick="u.deny('<?php  echo $user['uid'];?>');"><?php  if(intval($user['status']) == 2) { ?>禁止<?php  } else { ?>启用<?php  } ?>用户</a>&nbsp;&nbsp;
					</div>
				</td>
				<td>
					<div>
						<a href="javascript:;" onclick="u.del('<?php  echo $user['uid'];?>');">删除用户</a>
					</div>
				</td>
				<?php  } ?>
			</tr>
			<?php  } } ?>
		</tbody>
	</table>
	</div>
</div>
<?php  echo $pager;?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-gw', TEMPLATE_INCLUDEPATH));?>
