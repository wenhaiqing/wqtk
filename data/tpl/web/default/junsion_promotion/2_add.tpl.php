<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li class="active"><a>添加员工</a></li>
	<li><a href="<?php  echo $this->createWebUrl('staff');?>">员工管理</a></li>
	<li><a href="<?php  echo $this->createWebUrl('fans');?>">粉丝管理</a></li>
	<li><a href="<?php  echo $this->createWebUrl('stat');?>">数据统计</a></li>
</ul>
<script src="../addons/junsion_promotion/template/js/SEARCH.js?v=<?php echo TIMESTAMP;?>"></script>
<form action="" method="post" class="form-horizontal" role="form" onsubmit="onCheck()">
<div class="panel panel-info stores base">
		<div class="panel-body">
			<input type="hidden" name='sid' value='<?php  echo $item["id"];?>' >
			<?php  if(!$cfg['acc_rule'] && ($_W['account']['level'] == 4 || $_W['account']['level'] < 4 && !$item)) { ?>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">员工工号</label>
				<div class="col-sm-9">
					<input class="form-control" name="account" required="required" value="<?php  echo $item['account'];?>">
					<?php  if($_W['account']['level'] < 4) { ?><div class='help-block'>保存后，工号将不可修改，请谨慎设置！</div><?php  } ?>
				</div>
			</div>
			<?php  } ?>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">对应粉丝</label>
				<div class="col-sm-9">
					<div class="input-group">
						<input class="form-control" name="fans" id="fans" type="text" readonly="readonly" value="<?php  echo $item['nickname'];?><?php  if($item['openid']) { ?>(<?php  echo $item['openid'];?>)<?php  } ?>">
						<input class="form-control" name="openid" id="openid" type="hidden" value="<?php  echo $item['openid'];?>">
						<div class="input-group-btn" onclick="onFans()"><a class="btn btn-default">选择粉丝</a></div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">员工姓名</label>
				<div class="col-sm-9">
					<input class="form-control" name="realname" required="required" type="text" value="<?php  echo $item['realname'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">手机号码</label>
				<div class="col-sm-9">
					<input class="form-control" name="mobile" required="required" type="text" value="<?php  echo $item['mobile'];?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">状态</label>
				<div class="col-sm-9">
					<label><input type="radio" name="status" checked="checked" value="0"> 待审核</label>
					<label style="margin-left: 10px;"><input type="radio" name="status" <?php  if($item['status']) { ?>checked="checked"<?php  } ?> value="1"> 审核</label>
				</div>
			</div>
	</div>
</div>
<button type="submit" class="btn btn-primary" name="submit" value="提交">保存</button>
<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
</form>
<script>
function onFans(){
	SEARCH(1,'选择粉丝','粉丝昵称',[{title:'粉丝昵称',width:"30%"},{title:'openid'}],[],function(keyword,createdata){
		if(keyword == '') return [];
		$.ajax({
			url:'<?php  echo $this->createWebUrl("getfans")?>',
			type:'post',
			data:{keyword:keyword},
			success:function(data){
				data = JSON.parse(data);
				var mdata = new Array();
				$.each(data,function(k,v){
					var arr = new Array();
					arr['id'] = v['uid'];
					arr['list'] = [v['nickname'],v['openid']];
					arr['status'] = v['status'];
					mdata.push(arr);
				});
				createdata(mdata);
			}			
		});	
	},function(data){
		$('#fans').val(data['list'][0] + "("+data['list'][1]+")");
		$('#openid').val(data['list'][1]);
	});
}

function onCheck(){
	if($('#openid').val() == ''){
		if(!confirm('未选择粉丝，确定添加嘛？')) return false; 
	}
}
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
