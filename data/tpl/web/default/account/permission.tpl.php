<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/header-gw', TEMPLATE_INCLUDEPATH));?>
<ol class="breadcrumb">
	<li><a href="./?refresh"><i class="fa fa-home"></i></a></li>
	<li><a href="<?php  echo url('system/welcome');?>">系统</a></li>
	<li><a href="<?php  echo url('account/display');?>">公众号列表</a></li>
	<li class="active">账号操作员列表</li>
</ol>
<?php  if($_GPC['reference'] != 'solution') { ?>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo url('account/permission', array('uniacid' => $uniacid));?>">账号操作员列表</a></li>
</ul>
<?php  } ?>
<div class="clearfix">
	<h5 class="page-header">设置可操作用户</h5>
	<div class="alert alert-info">
		<i class="fa fa-exclamation-circle"></i> 操作员不允许删除公众号和编辑公众号资料，管理员无此限制
	</div>
	<div class="panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="width:50px;">选择</th>
					<th style="width:80px;">用户ID</th>
					<th style="width:150px;">用户名</th>
					<th style="width:200px;">角色</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			<?php  if(is_array($permission)) { foreach($permission as $row) { ?>
				<tr <?php  if(!empty($_GPC['fromuid']) && $_GPC['fromuid']== $row['uid']) { ?>style="background:#dddddd;"<?php  } ?>>
					<td class="row-first"><?php  if(!in_array($member[$row['uid']]['uid'], $founders) && $row['role'] != 'owner') { ?><input class="member" autocomplete="off" type="checkbox" value="<?php  echo $row['id'];?>" /><?php  } ?></td>
					<td><?php  echo $row['uid'];?></td>
					<td><?php  echo $member[$row['uid']]['username'];?></td>
					<td>
						<?php  if(in_array($member[$row['uid']]['uid'], $founders)) { ?>
						<span class="label label-warning">创始人</span>
						<?php  } else if($row['role'] == 'owner') { ?>
						<span class="label label-warning">主管理员</span>
						<?php  } else { ?>
						<label for="radio_<?php  echo $row['uid'];?>_1" class="radio-inline" style="padding-top:0; float:left; width:70px;"><input type="radio" name="role[<?php  echo $row['uid'];?>]" targetid="<?php  echo $row['uid'];?>" id="radio_<?php  echo $row['uid'];?>_1" value="operator" <?php  if(empty($row['role']) || $row['role'] == 'operator') { ?> checked<?php  } ?> /> 操作员</label>
						<label for="radio_<?php  echo $row['uid'];?>_2" class="radio-inline" style="padding-top:0; float:left; width:70px;"><input type="radio" name="role[<?php  echo $row['uid'];?>]" targetid="<?php  echo $row['uid'];?>" id="radio_<?php  echo $row['uid'];?>_2" value="manager" <?php  if($row['role'] == 'manager') { ?> checked<?php  } ?> /> 管理员</label>
						<?php  } ?>
					</td>
					<td>
						<?php  if(in_array($member[$row['uid']]['uid'], $founders)) { ?>
						创始人拥有系统最高权限
						<?php  } else if($row['role'] == 'owner') { ?>
						主管理员拥有公众号的所有权限，并且公众号的权限（模块、模板）根据主管理员来获取
						<?php  } else { ?>
						<?php  if($_W['isfounder']) { ?><a href="<?php  echo url('user/edit', array('uid' => $member[$row['uid']]['uid']));?>">编辑用户</a>&nbsp;|&nbsp;<?php  } ?>
						<a href="<?php  echo url('user/permission/menu', array('uid' => $member[$row['uid']]['uid'], 'uniacid' => $uniacid));?>">设置权限</a>&nbsp;|&nbsp;
						<a href="<?php  echo url('user/permission', array('uid' => $row['uid']))?>" target="_blank">查看操作权限</a>
						<?php  } ?>
					</td>
				</tr>
			<?php  } } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5">
						<?php  if(!empty($_W['isfounder'])) { ?>
							<input id="btn-add" class="btn btn-default" type="button" value="选择账号操作员">
						<?php  } ?>
						<a class="btn btn-default" href="javascript:;" id="add-user">添加账号操作员</a>
						<input id="btn-revo" class="btn btn-default" type="button" value="删除选定操作">
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	</div>
</div>

<!-- 添加用户模态框 -->
<div class="modal fade" id="user-modal"  tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<form action="<?php  echo url('account/permission/user')?>" method="post" class="form-horizontal" role="form" id="form1">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">添加账号操作员</h3>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">用户名</label>
						<div class="col-sm-10 col-lg-9 col-xs-12">
							<input id="" name="username" type="text" class="form-control" value="<?php  echo $user['username'];?>" />
							<span class="help-block">请输入完整的用户名。你需要让新管理员先去注册一个”新账号“，再把他添加进来。</span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					<input type="submit" class="btn btn-primary" name="submit" value="确认" />
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
var seletedUserIds = <?php  echo json_encode($uids);?>;
require(['biz', 'bootstrap'], function(biz){
	$(function(){
		$('#add-user').click(function(){
			$('#user-modal').modal('show');

			$('#form1').submit(function(){
				var username = $.trim($('#form1 :text[name="username"]').val());
				if(!username) {
					util.message('没有输入用户名.', '', 'error');
					return false;
				}
				$.post("<?php  echo url('account/permission/user', array('uniacid' => $uniacid))?>", {'username':username}, function(data){
					if(data != 'success') {
						util.message(data, '', 'error');
					} else {
						util.message('添加账号操作员成功', "<?php  echo url('account/permission/', array('uniacid' => $uniacid))?>", 'success');
					}
				});
				return false;
			});
		});

		$('#btn-add').click(function(){
			biz.user.browser(seletedUserIds, function(us){
				$.post('<?php  echo url('account/permission', array('uniacid' => $uniacid, 'reference' => $_GPC['reference']));?>', {'do': 'auth', uid: us}, function(dat){
					if(dat == 'success') {
						location.reload();
					} else {
						alert('操作失败, 请稍后重试, 服务器返回信息为: ' + dat);
					}
				});
			},{mode:'invisible'});
		});

		$('#btn-revo').click(function(){
			$chks = $(':checkbox.member:checked');
			if($chks.length >0){
				if(!confirm('确认删除当前选择的用户?')){
					return;
				}
				var ids = [];
				$chks.each(function(){
					ids.push(this.value);
				});
				$.post('<?php  echo url('account/permission', array('uniacid' => $uniacid));?>',{'do':'revos', 'ids': ids},function(dat){
					if(dat == 'success') {
						location.reload();
					} else {
						alert('操作失败, 请稍后重试, 服务器返回信息为: ' + dat);
					}
				});
			}
		});

		$("input[name^='role[']").click(function(){
			$.post('<?php  echo url('account/permission/role', array('uniacid' => $uniacid));?>', {'uid' : $(this).attr('targetid'), 'role' : $(this).val()}, function(dat){
				if(dat != 'success') {
					u.message('设置管理员角色失败', "<?php  echo url('account/permission', array('uniacid' => $uniacid))?>", 'error');
				}
			});
		});
	});
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-gw', TEMPLATE_INCLUDEPATH));?>
