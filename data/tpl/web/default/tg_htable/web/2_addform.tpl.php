<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>添加报表</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="" rel="stylesheet">
</head>
<body>
	<ul class="nav nav-tabs">
		<li class="active"><a href="javascript:void(0);">添加报表</a></li>
	</ul>
    <form action="" method="POST">
    	<div class="panel panel-info">
    		<div class="panel-heading">
    			<h3 class="panel-title">报表类别</h3>
    		</div>
    		<div class="panel-body">
    			<input type="text" class="form-control" id="firstname" placeholder="请输入名称" name="formname" />
    		</div>
    	</div>
    	<div class="panel panel-info">
    		<div class="panel-heading">
    			<h3 class="panel-title">报表内容</h3>
    		</div>
    		<div class="panel-body">
    			<table class="table">
    				<thead>
    					<tr>
							<th width="10%">项目</th>
							<th >名称</th>
							<th width="70" align="center">长度</th>
							<th>类型</th>
						</tr>
    				</thead>
    				<tbody>
    					<tr id="tr">
    						<td>内容<span class="key">1</span></td>
    						<td><input type="text" name="name[]"  class="form-control" placeholder="请输入项目名称，如姓名、年龄等"/><input type="hidden" name="ex[]" value="" class="ex" /></td>
    						<td><input type="text" name="len[]"  class="form-control"/></td>
    						<td>
    							<select name="type[]" class="btn btn-primary dropdown-toggle">
    								<option value="number" _for="10">数值</option>
    								<option value="string" _for="50">文本</option>
    								<option value="image" _for="150">图片</option>
    								<option value="time" _for="50">时间</option>
    								<option value="text" _for="">段落</option>
    								<option value="radio" _for="10">单选</option>
    								<option value="select" _for="10">列表</option>
    								<option value="checkbox" _for="10">多选</option>
    							</select>&nbsp;&nbsp;&nbsp;
    							<input type="button" class="btn btn-primary add" value="添加项目" />
    							<input type="button" class="btn btn-primary del" value="删除项目" style="display:none;" />
    							<input type="button" class="btn btn-primary editex" value="修改" style="display:none;" title="" />
    						</td>
    					</tr>
    				</tbody>
    			</table>
    		</div>
    	</div>
    	<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
    	<input type="submit" class="btn btn-default" name="submit" value="提交" />
    </form>

<script>
	$(document).ready(function(){
		var oTr = $("form table tbody tr").eq(0);
		var titles = ['一','二','三','四','五','六','七','八','九','十'];
		var num = 1;
		
		function showDel(obj){
			if( $("form table tbody tr").length > 1){
				$("form table tbody tr input[class*='del']").show();
			}else{
				$("form table tbody tr input[class*='del']").hide();
			}
		}

		showDel();
		$("form table tbody input[name^='len']").val($("form table tbody select").find("option:selected").attr('_for'));

		var jqTr = $("form table tbody tr").eq(0).clone(true);
		$("form table tbody").on("click",function(e){
			var tmpTr = jqTr.clone();
			if(e.target.className.indexOf("add") > -1){
				num ++;
				tmpTr.find('span[class="key"]').text(num);
				$(e.target).parent().parent().after(tmpTr);
				showDel();
			}
			if(e.target.className.indexOf("del") > -1){
				e.target.parentNode.parentNode.remove();
				showDel();
			}
			if(e.target.className.indexOf("editex") > -1){
				var ex = $(e.target).parent().parent().find("input[name^='ex']");
				var editex = $(e.target).parent().parent().find("input[class*='editex']");
				var type = {
					"radio" : "单选项",
					"checkbox" : "多选项",
					"select" : "列表"
				};
				var sel = $(e.target).parent().find("select").val();
				var val = prompt("请输入列表的内容,用'|'符号分隔",ex.val());
				if(val){
					ex.val(val);
					editex.attr('title',val);
				}
			}
			if(e.target.tagName == 'SELECT'){
				var ex = $(e.target).parent().parent().find("input[name^='ex']");
				var editex = $(e.target).parent().parent().find("input[class*='editex']");
				
				$(e.target).parent().parent().find("input[name^='len']").val($(e.target).find("option:selected").attr("_for"));
				if($(e.target).val() == 'radio' && ex.val() == ''){
					var val = prompt("请输入单选项的内容,用'|'符号分隔",ex.val() || '是|否');
					ex.val(val);
					editex.attr('title',ex.val());
					editex.show();
				}
				else if($(e.target).val() == 'select' && ex.val() == ''){
					var val = prompt("请输入列表的内容,用'|'符号分隔",ex.val() || '北京|上海|广州|成都');
					ex.val(val);
					editex.attr('title',ex.val());
					editex.show();
				}
				else if($(e.target).val() == 'checkbox' && ex.val() == ''){
					var val = prompt("请输入多选项的内容,用'|'符号分隔",ex.val() || '因灾|因病|缺劳力|缺技术');
					ex.val(val);
					editex.attr('title',ex.val());
					editex.show();
					$(e.target).parent().parent().parent().find("input[name^='len']").val((val.length+6));
				}else{
					ex.val("");
					editex.attr('title','');
					editex.hide();
				}
			}
		});

	});
	
</script>
</body>
</html>