<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>管理报表</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="" rel="stylesheet">
</head>
<body>
    <ul class="nav nav-tabs">
		<li class="active"><a href="javascript:void(0);">管理报表</a></li>
	</ul>
<!--<a href="<?php  echo $send;?>">操作</a>-->
	<form action="" method="POST" >
		<div class="panel panel-info">
    		<div class="panel-heading">
    			<h3 class="panel-title">报表列表</h3>
    		</div>
    		<div class="panel-body">
    			<table class="table">
				  <thead>
				    <tr>
				      <th width="70">序号</th>
				      <th>报表名称</th>
				      <th width="200">操作</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php  if(is_array($pageData)) { foreach($pageData as $key => $item) { ?>
				    <tr class="success">
				      <td><?php  echo $key;?></td>
				      <td><?php  if($item['status'] == 0) { ?><span style="color:#999;"><?php  echo $item['name'];?></span><?php  } else { ?><span style="font-weight:bold;"><?php  echo $item['name'];?></span><?php  } ?></td>
				      <td><a class="deltb" href="<?php  echo $local;?>deltb=<?php  echo $item['id'];?>&key=<?php  echo $key;?>">删除</a>&nbsp;|&nbsp;<a href="<?php  echo $local;?>info=<?php  echo $item['id'];?>&key=<?php  echo $key;?>">详情</a>&nbsp;|&nbsp;<a href="<?php  echo $local;?>st_status=<?php  echo $item['id'];?>&key=<?php  echo $key;?>"><?php  if($item['status'] == 1) { ?>禁用<?php  } else { ?>启用<?php  } ?></a></td>
				    </tr>
				    <?php  } } ?>
				  </tbody>
				</table>
    		</div>
    	</div>
	</form>
	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-6">
			<span class="totalpage">共<?php  echo $pageNum;?>页</span>
			<?php  echo $pageStr;?>
			<span class="curpage">第<?php  echo $curPage;?>页</span>
		</div>
	</div>
	<script>
		(function(){
			$("form a[class='deltb']").on("click",function(){
				if(!confirm("此操作将删除该数据表，建议先备份好数据！你确定要删除吗？")){
					return false;
				}
			});
		})();
	</script>
</body>
</html>