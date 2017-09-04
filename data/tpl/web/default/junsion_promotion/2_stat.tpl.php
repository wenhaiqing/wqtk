<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo $this->createWebUrl('staff');?>">员工管理</a></li>
	<li><a href="<?php  echo $this->createWebUrl('fans');?>">粉丝管理</a></li>
	<li class="active"><a href="<?php  echo $this->createWebUrl('stat')?>">数据统计</a></li>
</ul>
<style>
	.account-stat-num > div{width:25%; float:left; font-size:16px; text-align:center;}
	.account-stat-num > div span{display:block; font-size:30px; font-weight:bold;}
</style>
<div style="margin-bottom: 5px;">
	<a class="btn btn-<?php  if(!$op) { ?>primary<?php  } else { ?>default<?php  } ?>" href="<?php  echo $this->createWebUrl('stat')?>">推广统计</a>
	<a class="btn btn-<?php  if($op) { ?>primary<?php  } else { ?>default<?php  } ?>" href="<?php  echo $this->createWebUrl('stat',array('op'=>1))?>">推广排行</a>
</div>
<?php  if(!$op) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		推广统计
	</div>
		<div class="panel-body">
			<div class="account-stat-num row">
				<div>今日推广总数<span><?php  echo $all_num;?></span></div>
				<div><?php  echo date('Y-m-d', $starttime);?>~<?php  echo date('Y-m-d', $endtime);?><br>总推广数<span><?php  echo $total_num;?></span></div>
				<div>今日取消总数<span><?php  echo $today_un;?></span></div>
				<div><?php  echo date('Y-m-d', $starttime);?>~<?php  echo date('Y-m-d', $endtime);?><br>总取消数<span><?php  echo $all_un;?></span></div>
			</div>
		</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		推广总数统计
	</div>
	<div class="panel-body" id="scroll">
		<div class="pull-left">
			<form action="" id="form1">
				<input name="c" value="site" type="hidden" />
				<input name="a" value="entry" type="hidden" />
				<input name="do" value="<?php  echo $_GPC['do'];?>" type="hidden" />
				<input name="m" value="<?php  echo $this->modulename?>" type="hidden" />
				<?php  echo tpl_form_field_daterange('time', array('start' => date('Y-m-d', $starttime),'end' => date('Y-m-d', $endtime)), '')?>
			</form>
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
			all: {
				label: '全部',
				fillColor : "rgba(149,192,0,0.1)",
				strokeColor : "rgba(149,192,0,1)",
				pointColor : "rgba(149,192,0,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(149,192,0,1)"
			},
		};

		function refreshData() {
			if(!chart || !chartDatasets) {
				return;
			}
			var visables = [];
			visables.push(0);
			var ds = [];
			$.each(visables, function(){
				var o = chartDatasets[this];
				ds.push(o);
			});
			chart.datasets = ds;
			chart.update();
		}

		$.post("<?php  echo $this->createWebUrl('data',array('time'=>$_GPC['time']))?>",function(data){
			var data = $.parseJSON(data);
			var datasets = data.datasets;

			if(!chart) {
				var label = data.label;
				var ds = $.extend(true, {}, templates);
				ds.all.data = datasets.all;
				var lineChartData = {
					labels : label,
					datasets : [ds.all]
				};

				var ctx = document.getElementById("myChart").getContext("2d");
				chart = new Chart(ctx).Line(lineChartData, {
					responsive: true
				});
				chartDatasets = $.extend(true, {}, chart.datasets);
			}
			refreshData();
		});
	});
</script>
<?php  } else if($op==1) { ?>
<style>
th{
	text-align: center !important;
}
td{
	text-align: center !important;
}
</style>
<div class="main">
	<div class="panel panel-info">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="./index.php" class="form-horizontal">
            	<input type="hidden" name="c" value="site" />
                <input type="hidden" name="a" value="entry" />
                <input type="hidden" name="m" value="<?php  echo $this->modulename?>" />
                <input type="hidden" name="do" value="stat" />
                <input type="hidden" name="op" value="<?php  echo $op;?>" />
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 100px;">时间</label>
                    <div class="col-sm-2 col-lg-3">
	                    <?php  echo tpl_form_field_daterange('time', array('start' => date('Y-m-d', $starttime),'end' => date('Y-m-d', $endtime)), '')?>
                    </div>
                    <div class="col-sm-2 col-lg-2">
	                     <button class="btn btn-default" type="submit"><i class="fa fa-search"></i> 搜索</button>
	                     <button style="margin-left: 20px;" class="btn btn-primary" name="export" value="1" type="submit">导出</button>
                    </div>
                </div>
            </form>
        </div>
</div>
	<div style="padding:15px;background: white;">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th>排名</th>
					<th>工号</th>
					<th>姓名</th>
					<th>推广数</th>
				</tr>
			</thead>
			<tbody>
					<?php  if(is_array($list)) { foreach($list as $k => $item) { ?>
				<tr>
					<td><?php  echo $k+1+($pindex-1)*$psize?></td>
					<td><?php  echo $item['account'];?></td>
					<td><?php  echo $item['realname'];?></td>
					<td><?php  echo $item['num'];?></td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<?php  echo $pager;?>
	</div>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
