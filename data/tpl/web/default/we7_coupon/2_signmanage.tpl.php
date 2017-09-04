<?php defined('IN_IA') or exit('Access Denied');?><?php  $newUI = true;?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div style="margin-bottom:15px;">
	<div class="btn-group">
		<a href="<?php  echo $this->createWeburl('signmanage', array('op' => 'sign-credit'))?>" class="btn <?php  if($op == 'sign-credit') { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">积分策略</a>
		<a href="<?php  echo $this->createWeburl('signmanage', array('op' => 'record-list'))?>" class="btn <?php  if($op == 'record-list') { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">签到列表</a>
	</div>
</div>
<?php  if($op == 'record-list') { ?>
<div class="clearfix">
	<div class="form-group">
		<div class="panel panel-default">
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<thead>
					<tr>
						<th>会员姓名</th>
						<th>积分</th>
						<th>签到时间</th>
					</tr>
					</thead>
					<tbody>
					<?php  if(is_array($list)) { foreach($list as $sign) { ?>
					<tr>
						<td><?php  echo $sign['realname'];?></td>
						<td><?php  echo $sign['credit'];?></td>
						<td><?php  echo $sign['addtime'];?></td>
					</tr>
					<?php  } } ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php  echo $pager;?>
	</div>
</div>
<?php  } ?>

<?php  if($op == 'sign-credit') { ?>
<div class="clearfix">
	<div style="margin-bottom:20px">
		是否开启签到功能:
		<input type="checkbox" name="sign_status" value="1" <?php  if(intval($setting['sign_status'])==1) { ?> checked="checked" <?php  } ?>/>
	</div>
	<?php  if($setting['sign_status'] == '1') { ?>
	<form action="" class="form-horizontal form" method="post" enctype="multipart/form-data" id="form1">
		<div class="panel panel-default">
			<div class="panel-heading">积分策略</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">签到奖励</label>
					<div class="col-sm-9 col-xs-12">
						<div class="input-group">
							<span class="input-group-addon">每天签到奖励</span>
							<input type="text" class="form-control" name="sign[everydaynum]" value="<?php  echo $set['sign']['everydaynum'];?>"/>
							<span class="input-group-addon">积分</span>
						</div>
						<br/>
						<div class="input-group">
							<span class="input-group-addon">每月累计</span>
							<input type="text" class="form-control" name="sign[first_group_day]" value="<?php  echo $set['sign']['first_group_day'];?>"/>
							<span class="input-group-addon">天签到奖励</span>
							<input type="text" class="form-control" name="sign[first_group_num]" value="<?php  echo $set['sign']['first_group_num'];?>"/>
							<span class="input-group-addon">积分</span>
						</div>
						<br/>
						<div class="input-group">
							<span class="input-group-addon">每月累计</span>
							<input type="text" class="form-control" name="sign[second_group_day]" value="<?php  echo $set['sign']['second_group_day'];?>"/>
							<span class="input-group-addon">天签到奖励</span>
							<input type="text" class="form-control" name="sign[second_group_num]" value="<?php  echo $set['sign']['second_group_num'];?>"/>
							<span class="input-group-addon">积分</span>
						</div>
						<br/>
						<div class="input-group">
							<span class="input-group-addon">每月累计</span>
							<input type="text" class="form-control" name="sign[third_group_day]" value="<?php  echo $set['sign']['third_group_day'];?>"/>
							<span class="input-group-addon">天签到奖励</span>
							<input type="text" class="form-control" name="sign[third_group_num]" value="<?php  echo $set['sign']['third_group_num'];?>"/>
							<span class="input-group-addon">积分</span>
						</div>
						<br/>
						<div class="input-group">
							<span class="input-group-addon">每月满签奖励</span>
							<input type="text" class="form-control" name="sign[full_sign_num]" value="<?php  echo $set['sign']['full_sign_num'];?>"/>
							<span class="input-group-addon">积分</span>
						</div>
						<span class="help-block">连续奖励的天数必须大于1天。</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">积分攻略</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_ueditor('content', $set['content']);?>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group" style="margin-left:0px">
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
			<input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"/>
		</div>
	</form>
	<?php  } ?>
</div>
<script type="text/javascript">
	require(['jquery.ui', 'bootstrap.switch'], function(){
		$('#form1').submit(function(){
			var everydaynum = parseInt($(':text[name="sign[everydaynum]"]').val());
			if(isNaN(everydaynum) || !everydaynum) {
				util.message('每天签到奖励积分必须大于0', '', 'error');
				return false;
			}
		});

		$(":checkbox[name='sign_status']").bootstrapSwitch();
		$(":checkbox[name='sign_status']").on('switchChange.bootstrapSwitch', function(e, state){
			$this = $(this);
			var status = this.checked ? 1 : 0;
			$.post("<?php  echo $this->createWeburl('signmanage', array('op' => 'sign-status', 'field' => 'sign_status'));?>", {status:status}, function(data){
				data = $.parseJSON(data);
				if(data.message.errno != 0) {
					util.message(data.message.message, '', 'error');
					return false;
				} else {
					util.message('操作成功', location.href, 'success');
				}
			});
		});
	});
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
