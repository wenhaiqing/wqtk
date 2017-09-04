<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
.table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{white-space:nowrap;}
</style>
<ul class="nav nav-tabs">
	<li <?php  if($do == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo url('mc/fans/display');?>">粉丝列表</a></li>
	<?php  if($do == 'view' && !empty($fanid)) { ?><li class="active"><a href="<?php  echo url('mc/fans/view', array('id' => $fanid));?>">粉丝详情</a></li><?php  } ?>
</ul>
<?php  if($do == 'display') { ?>
<div class="clearfix" ng-controller="ListCtrl" ng-cloak>
	<div class="alert alert-info">
		如果您的公众号类型为："认证订阅号" 或 "认证服务号",您可以使用粉丝标签功能。点击这里 <a href="<?php  echo url('mc/fangroup')?>">"同步粉丝标签"</a>
	</div>
	<div class="alert alert-info">
		粉丝共:<strong class="text-danger"><?php  echo $fans['total'];?></strong>人<br>		<!-- follow=1，必须是关注的粉丝 -->
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">筛选</div>
		<div class="panel-body">
			<form action="./index.php" method="get" class="form-horizontal" role="form">
				<input type="hidden" name="c" value="mc" />
				<input type="hidden" name="a" value="fans" />
				<input type="hidden" name="searchmod" value="{{config.searchMod.value}}" />
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">昵称/openid</label>
					<div class="col-sm-9 col-md-8 col-lg-8 col-xs-12">
						<div class="input-group">
							<div class="input-group-btn">
								<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="false">{{config.searchMod.title}} <span class="caret"></span></button>
								<ul role="menu" class="dropdown-menu">
									<li><a href="javascript:;" ng-click="switchSearchMod(1)">精确</a></li>
									<li><a href="javascript:;" ng-click="switchSearchMod(2)">模糊</a></li>
								</ul>
							</div>
							<input type="text" class="form-control" name="nickname" value="<?php  echo $nickname;?>" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">uid</label>
					<div class="col-sm-9 col-md-8 col-lg-8 col-xs-12">
						<input type="text" class="form-control" name="uid" value="<?php  echo $_GPC['uid'];?>"/>
					</div>
				</div>
				<?php  if(!empty($fans_tag)) { ?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">标签</label>
					<div class="col-sm-9 col-md-8 col-lg-8 col-xs-12">
						<div class="btn-group">
							<button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
								<span id="tag-button">请选择标签</span>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li class="tag-option" id="tag-0" ng-click="switchTag({'id':0, 'name':'请选择标签'})"><a href="javascript:;" title="请选择标签">请选择标签</a></li>
								<li class="tag-option" id="tag-{{tag.id}}" ng-repeat="tag in config.tags" ng-click="switchTag(tag)"><a href="javascript:;" title="{{tag.name}}">{{tag.name}}</a></li>
							</ul>
						</div>
					</div>
				</div>
				<input type="hidden" id="tag-selected-id" name="tag_selected_id" value="<?php  echo $tag_selected_id;?>">
				<?php  } ?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">用户组</label>
					<div class="col-sm-9 col-md-8 col-lg-8 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="type" value="" <?php  if($type == '') { ?>checked="checked"<?php  } ?>/> 不指定
						</label>
						<label class="radio-inline">
							<input type="radio" name="type" value="bind" <?php  if($type == 'bind') { ?>checked="checked"<?php  } ?>/> 已经注册为会员
						</label>
						<label class="radio-inline">
							<input type="radio" name="type" value="unbind" <?php  if($type == 'unbind') { ?>checked="checked"<?php  } ?>/> 未注册为会员
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否关注</label>
					<div class="col-sm-9 col-md-8 col-lg-8 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="follow" value="1" checked="checked"/> 已关注
						</label>
						<label class="radio-inline">
							<input type="radio" name="follow" value="2" <?php  if($follow == '2') { ?>checked="checked"<?php  } ?>/> 取消关注
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">关注/取消关注时间</label>
					<div class="col-sm-9 col-md-8 col-lg-8 col-xs-12">
						<?php echo tpl_form_field_daterange('time', array('starttime'=>($starttime ? date('Y-m-d', $starttime) : false),'endtime'=> ($endtime ? date('Y-m-d', $endtime) : false)));?>
					</div>
					<div class="pull-right col-xs-12 col-sm-3 col-md-2 col-lg-2">
						<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<form action="?<?php  echo $_SERVER['QUERY_STRING'];?>" method="post" id="form1">
	<div class="panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover"  style="width:100%;z-index:-10;" cellspacing="0" cellpadding="0">
			<thead class="navbar-inner">
				<tr>
					<th style="width:30px;">删？</th>
					<th style="width:90px;">头像</th>
					<th style="width:150px;">昵称</th>
					<th style="width:180px;">对应用户</th>
					<th style="width:180px;">用户标签</th>
					<th style="width:80px;">是否关注</th>
					<th style="width:180px;">关注/取消时间</th>
					<th style="min-width:70px;" class="text-right">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
				<tr>
					<td><input class="check-delete tagids-<?php  echo $item['fanid'];?>" type="checkbox" name="delete[]" value="<?php  echo $item['fanid'];?>" data-tagids="<?php  echo $item['groupid'];?>" data-openid="<?php  echo $item['openid'];?>" data-fanid="<?php  echo $item['fanid'];?>"/></td>
					<td><img src="<?php  if(!empty($item['user']['avatar'])) { ?><?php  echo $item['user']['avatar'];?><?php  } else { ?>resource/images/noavatar_middle.gif<?php  } ?>" width="48"></td>
					<td><?php  echo $item['user']['nickname'];?></td>
					<td>
						<?php  if(empty($item['uid'])) { ?>
						<a href="<?php  echo url('mc/member/post', array('uid' => $item['uid'],'openid' => $item['openid'], 'fanid' => $item['fanid']));?>" class="text-danger" title="该用户尚未注册会员，请为其手动注册！">[ 注册为会员 ]</a>
						<?php  } else { ?>
						<a href="<?php  echo url('mc/member/post', array('uid'=>$item['uid']));?>"><span><?php  if($item['user']['niemmo_effective'] == 1) { ?><?php  echo $item['user']['niemmo'];?><?php  } else { ?><?php  echo $item['uid'];?><?php  } ?></span></a>
						<?php  } ?>
					</td>
					<td class="tag-show-<?php  echo $item['fanid'];?>">
						<?php  echo $item['tag_show'];?>
					</td>
					<td>
					<?php  if($item['follow'] == '1') { ?>
						<span class="label label-success">已关注 </span> 
					<?php  } else if($item['unfollowtime'] <> '0') { ?>
						<span class="label label-warning" >取消关注 </span>
					<?php  } else { ?>
						<span class="label label-danger">未关注 </span>
					<?php  } ?>
					</td>
					<td>
						<?php  if($item['follow'] == '1') { ?><?php  echo date('Y-m-d H:i:s', $item['followtime'])?><?php  } else if(!empty($item['unfollowtime'])) { ?><?php  echo date('Y-m-d H:i:s', $item['unfollowtime'])?><?php  } else { ?>&nbsp;<?php  } ?>
					</td>
					<td class="text-right" style="overflow:visible;">
						<div we-batch-tagging modal-class="js-select-tag-<?php  echo $index;?>" btn-class="btn-sm" btn-style="float:right;margin-left:5px;" value="打标签" we-submit="submitTags($event)" fetch-tags="fetchTags('<?php  echo $item['openid'];?>', '<?php  echo $item['fanid'];?>')" select-tags="selectTags"></div>
						<a href="<?php  echo url('mc/notice/tpl', array('id' => $item['fanid']));?>" class="btn btn-success btn-sm sms">发送消息</a>
						<a href="<?php  echo url('mc/fans/view', array('id' => $item['fanid']));?>" class="btn btn-default btn-sm">查看详情</a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<table class="table table-hover">
			<tr>
				<td width="30">
					<input type="checkbox" onclick="var ck = this.checked;$('.check-delete').each(function(){this.checked = ck});" />
				</td>
				<td class="text-left">
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
					<input type="submit" name="submit" class="btn btn-primary span2" value="删除" ng-click="delFans($event)"/> &nbsp; 
					<input type="button" class="btn btn-default" value="同步选中粉丝信息{{config.enabled ? '' : '（需要认证公众号权限）'}}" ng-click="sync();" ng-disabled="!config.enabled || config.running" /> &nbsp;
					<input type="button" class="btn btn-default" value="同步全部粉丝信息{{config.enabled ? (config.running ? config.downloadState : '') : '（需要认证公众号权限）'}}" ng-click="download();" ng-disabled="!config.enabled || adv.running" /> &nbsp; 
					<div style="display:inline;" we-batch-tagging modal-class="js-select-tag-batch" value="批量打标签" we-submit="batchSubmitTags(event)" select-tags="selectTags"></div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span class="help-block">同步粉丝信息: 选定粉丝后, 访问公众平台获取特定粉丝的相关资料, 如果已对应用户, 那么将会把未登记的资料填充至关联用户. 需要为认证微信服务号, 或者高级易信号</span>
					<span class="help-block">下载所有粉丝: 访问公众平台下载所有粉丝列表(这个操作不能获取粉丝资料, 只能获取粉丝标志). 需要为认证微信服务号, 或者高级易信号</span>
				</td>
			</tr>
		</table>
	</div>
	</div>
	<?php  echo $pager;?>
	</form>
</div>
<script type="text/javascript">
	$(function(){
		angular.module('fansApp').value('config', {
			'enabled' : <?php  if($_W['acid'] && $_W['account']['level'] >= ACCOUNT_SUBSCRIPTION_VERIFY) { ?>true<?php  } else { ?>false<?php  } ?>,
			'tags' :  <?php echo !empty($fans_tag) ? json_encode($fans_tag) : 'null'?>,
			'curTagid' : '<?php  echo $tag_selected_id;?>',
			'syncUrl' : "<?php  echo url('mc/fans/initsync', array('acid' => $acid))?>",
			'url' : '<?php  echo url("mc/fans/tag");?>',
			'batchUrl' : "<?php  echo url('mc/fans/tag', array('acid' => $acid))?>",
			'searchMod' : {title : '精确', value : '1'}
		});
		angular.bootstrap(document, ['fansApp']);
	});
</script>
<?php  } ?>
<?php  if($do == 'view') { ?>
<div class="form-horizontal form">
	<div class="panel panel-default">
		<div class="panel-heading">
			粉丝详情
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">对应会员</label>
				<div class="col-sm-10">
					<span class="help-block"><?php  if(empty($row['uid'])) { ?><?php  echo $row['user'];?><?php  } else { ?><a href="<?php  echo url('mc/member/post', array('uid'=>$row['uid']));?>"><?php  echo $row['user'];?></a><?php  } ?></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">粉丝编号</label>
				<div class="col-sm-10">
					<span class="help-block"><?php  echo $row['openid'];?></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">所属公众号</label>
				<div class="col-sm-10">
					<span class="help-block"><?php  echo $row['account'];?></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否关注</label>
				<div class="col-sm-10">
					<span class="help-block"><?php  if($row['follow'] == '1') { ?> <span class="label label-success" >已订阅 </span> <?php  } else if($row['unfollowtime'] <> '0') { ?> <span class="label label-warning"> 取消关注 </span> <?php  } else { ?> <span class="label label-danger" >未订阅 </span><?php  } ?></span>
				</div>
			</div>
			<?php  if($row['follow'] == '1' && $row['followtime'] <> '0') { ?>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">关注时间</label>
				<div class="col-sm-10">
					<span class="help-block"><?php  echo date('Y-m-d H:i:s', $row['followtime'])?></span>
				</div>
			</div>
			<?php  } else if($row['unfollowtime'] <> '0') { ?>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">取消关注时间</label>
				<div class="col-sm-10">
					<span class="help-block"><?php  echo date('Y-m-d H:i:s', $row['unfollowtime'])?></span>
				</div>
			</div>
			<?php  } else { ?>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">关注时间</label>
				<div class="col-sm-10">
					<span class="help-block">未记录</span>
				</div>
			</div>
			<?php  } ?>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
				<div class="col-sm-10">
					<span class="help-block"><a href="javascript:history.go(-1);" class="btn btn-primary">返回</a></span>
				</div>
			</div>
		</div>
	</div>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
