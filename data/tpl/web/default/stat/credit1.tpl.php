<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
	.account-stat-num > div{width:25%; float:left; font-size:16px; text-align:center;}
	.account-stat-num > div span{display:block; font-size:30px; font-weight:bold;}
</style>
<ul class="nav nav-tabs">
	<li <?php  if($do == 'index') { ?>class="active"<?php  } ?>><a href="<?php  echo url('stat/credit1')?>">积分日志</a></li>
	<li <?php  if($do == 'chart') { ?>class="active"<?php  } ?>><a href="<?php  echo url('stat/credit1/chart')?>">积分统计</a></li>
</ul>
<?php  if($do == 'chart') { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		积分统计
	</div>
	<div class="panel-body">
		<div class="account-stat-num row">
			<div>今日充值总额<span><?php  echo $today_recharge;?></span></div>
			<div>今日消费总额<span><?php  echo abs($today_consume);?></span></div>
			<div><?php  echo date('Y-m-d', $starttime);?>~<?php  echo date('Y-m-d', $endtime);?><br>充值总额<span><?php  echo $total_recharge;?></span></div>
			<div><?php  echo date('Y-m-d', $starttime);?>~<?php  echo date('Y-m-d', $endtime);?><br>消费总额<span><?php  echo abs($total_consume)?></span></div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		积分统计
	</div>
	<div class="panel-body" id="scroll">
		<div class="pull-left">
			<form action="" id="form1">
				<input name="c" value="stat" type="hidden" />
				<input name="a" value="credit1" type="hidden" />
				<input name="do" value="chart" type="hidden" />
				<?php  echo tpl_form_field_daterange('time', array('start' => date('Y-m-d', $starttime),'end' => date('Y-m-d', $endtime)), '')?>
				<input type="hidden" value="" name="scroll">
			</form>
		</div>
		<div class="pull-right">
			<div class="checkbox">
				<label style="color:rgba(149,192,0,1);;"><input checked type="checkbox"> 充值统计</label>&nbsp;
				<label style="color:rgba(203,48,48,1)"><input checked type="checkbox"> 消费统计</label>&nbsp;
			</div>
		</div>
		<div style="margin-top:20px">
			<canvas id="myChart" width="1200" height="300"></canvas>
		</div>
	</div>
</div>
<script>
	require(['chart', 'daterangepicker'], function(c) {
		$('.daterange').on('apply.daterangepicker', function(ev, picker) {
			$('#form1')[0].submit();
		});
		var chart = null;
		var chartDatasets = null;
		var templates = {
			consume: {
				label: '消费',
				fillColor : "rgba(203,48,48,0.1)",
				strokeColor : "rgba(203,48,48,1)",
				pointColor : "rgba(203,48,48,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(203,48,48,1)"
			},
			recharge: {
				label: '充值',
				fillColor : "rgba(149,192,0,0.1)",
				strokeColor : "rgba(149,192,0,1)",
				pointColor : "rgba(149,192,0,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(149,192,0,1)"
			}
		};

		function refreshData() {
			if(!chart || !chartDatasets) {
				return;
			}
			var visables = [];
			var i = 0;
			$('.checkbox input[type="checkbox"]').each(function(){
				if($(this).attr('checked')) {
					visables.push(i);
				}
				i++;
			});
			var ds = [];
			$.each(visables, function(){
				var o = chartDatasets[this];
				ds.push(o);
			});
			chart.datasets = ds;
			chart.update();
		}

		var url = location.href + '&#aaaa';
		$.post(url, function(data){
			var data = $.parseJSON(data)
			var datasets = data.datasets;

			if(!chart) {
				var label = data.label;
				var ds = $.extend(true, {}, templates);
				ds.consume.data = datasets.consume;
				ds.recharge.data = datasets.recharge;
				var lineChartData = {
					labels : label,
					datasets : [ds.consume, ds.recharge]
				};

				var ctx = document.getElementById("myChart").getContext("2d");
				chart = new Chart(ctx).Line(lineChartData, {
					responsive: true
				});
				chartDatasets = $.extend(true, {}, chart.datasets);
			}
			refreshData();
		});

		$('.checkbox input[type="checkbox"]').on('click', function(){
			$(this).attr('checked', !$(this).attr('checked'))
			refreshData();
		});
	});
</script>
<?php  } else { ?>
<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form action="./index.php" method="get" class="form-horizontal" role="form">
			<input type="hidden" name="c" value="stat">
			<input type="hidden" name="a" value="credit1">
			<input type="hidden" name="do" value="index">
			<input type="hidden" name="num" value="<?php  echo $num;?>">
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">类型</label>
				<div class="col-sm-8 col-lg-9 col-xs-12">
					<div class="btn-group">
						<a href="<?php  echo filter_url('num:0');?>" class="btn <?php  if(!$_GPC['num']) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">不限</a>
						<a href="<?php  echo filter_url('num:1');?>" class="btn <?php  if($_GPC['num'] == 1) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">充值</a>
						<a href="<?php  echo filter_url('num:2');?>" class="btn <?php  if($_GPC['num'] == 2) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">消费</a>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">消费时间</label>
				<div class="col-sm-6 col-md-8 col-lg-8 col-xs-12">
					<?php  echo tpl_form_field_daterange('time', array('starttime' => date('Y-m-d', $starttime), 'endtime' => date('Y-m-d', $endtime),));?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">姓名/手机号码/UID</label>
				<div class="col-sm-6 col-md-8 col-lg-8 col-xs-12">
					<input type="text" class="form-control" name="user" value="<?php  echo $_GPC['uid'];?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">积分</label>
				<div class="col-sm-6 col-md-8 col-lg-8 col-xs-12">
					<div class="input-group">
						<input type="text" class="form-control" name="min" value="<?php  echo $_GPC['min'];?>" />
						<span class="input-group-addon">至</span>
						<input type="text" class="form-control" name="max" value="<?php  echo $_GPC['max'];?>" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
				<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
					<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>&nbsp;&nbsp;
					<button name="export" value="export" class="btn btn-default"><i class="fa fa-download"></i> 导出数据</button>
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
				</div>
			</div>
		</form>
	</div>
</div>
<form method="post" class="form-horizontal" id="form1">
	<div class="panel panel-default ">
		<div class="table-responsive panel-body">
			<table class="table table-hover">
				<thead>
				<tr>
					<th style="width:80px;">会员编号</th>
					<th>姓名</th>
					<th>手机</th>
					<th>类型</th>
					<th>数量</th>
					<th>操作时间</th>
					<th width="400">备注</th>
				</tr>
				</thead>
				<tbody>
				<?php  if(is_array($data)) { foreach($data as $row) { ?>
				<tr>
					<td><?php  echo $row['uid'];?></td>
					<td><?php  echo $users[$row['uid']]['realname'];?></td>
					<td><?php  echo $users[$row['uid']]['mobile'];?></td>
					<td>
						<?php  if($row['num'] > 0) { ?>
						<span class="label label-success">充值</span>
						<?php  } else { ?>
						<span class="label label-danger">消费</span>
						<?php  } ?>
					</td>
					<td><?php  echo abs($row['num']);?></td>
					<td><?php  echo date('Y-m-d H:i', $row['createtime'])?></td>
					<td style="cursor: pointer"><span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="<?php  echo $row['remark'];?>"><?php  echo cutstr($row['remark'], 30, '...');?></span></td>
				</tr>
				<?php  } } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php  echo $pager;?>
</form>
<script>
	require(['bootstrap'],function($){
		$('[data-toggle="popover"]').popover()
	});
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>