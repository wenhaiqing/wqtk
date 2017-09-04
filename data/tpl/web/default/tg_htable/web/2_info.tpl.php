<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Examples</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="" rel="stylesheet">
</head>
<body>
    <ul class="nav nav-tabs">
		<li class="active"><a href="javascript:void(0);">报表详情</a></li>
	</ul>

	<form action="" method="POST" >
		<input type="hidden" name="tbname" value="<?php  echo $tbname;?>" />
		<div class="panel panel-info">
    		<div class="panel-heading">
    			<h3 class="panel-title"><?php  echo $title;?></h3>
    		</div>
    		<div class="panel-body">
    			<div class="form-group">
    			<label for="firstname" class="col-sm-1 control-label">类别</label>
    				<div class="col-sm-3">
    					<input type="text" name="searchcont"  class="form-control" style="height:25px;"/>
    				</div>
    				<div class="col-sm-2">
    					<select name="searchitem" class="btn btn-primary dropdown-toggle" style="min-width:120px;height:25px;line-height:25px;padding:0px 15px;">
    						<option value="nickname">微信</option>
    						<?php  if(is_array($data['0'])) { foreach($data['0'] as $key => $item) { ?>
    							<option value="col_<?php  echo $key;?>"><?php  echo $item;?></option>
    						<?php  } } ?>
    					</select>
    				</div>
    				<div class="col-sm-1">
    					<input type="button" class="form-control search" id="firstname" value="查询"  style="height:25px;line-height:25px;padding:0px 15px;" _for="<?php  echo $local;?>"/>
    				</div>
    				<div class="col-sm-1">
    					<input type="button" class="form-control" id="firstname" value="导出"  style="height:25px;line-height:25px;padding:0px 15px;" name="output" />
    				</div>
    				<div class="col-sm-1">
    					<input type="button" class="form-control" id="firstname" value="导出全表"  style="height:25px;width:70px;line-height:25px;padding:0px 5px;" name="outputall" />
    				</div>
    			</div>
    		</div>
    		<table class="table">
    			<thead>
    				<th width="20">
    					<input type="checkbox" name="sectionOn" />
    				</th>
    				<th width="50">序号</th>
    				<th width="100">微信</th>
    				<?php  if(is_array($data['0'])) { foreach($data['0'] as $key => $item) { ?>
    				<th><?php  echo $item;?></th>
    				<?php  } } ?>
    			</thead>
    			<tbody>
					<?php  if(is_array($pageData)) { foreach($pageData as $key => $item) { ?>
						<tr>
							<td>
								<input type="checkbox" name="sections[]" value="<?php  echo $item['id'];?>" />
							</td>
							<?php  if(is_array($item)) { foreach($item as $k => $cell) { ?>
								<?php  if(in_array(strrchr($cell,'.'),$imgtype)) { ?>
									<td><img width="80" height="60" src="<?php  echo $cell;?>" /></td>
								<?php  } else { ?>
									<td><?php  echo $cell;?></td>
								<?php  } ?>
							<?php  } } ?>
						</tr>
					<?php  } } ?>
    			</tbody>
    		</table>
    		<div class="note" style="margin:15px;display:none"></div>
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
	window.diyPageNum = <?php  echo $pageNum;?>;
	(function(){
		//var obj = [{"a":5,"b":3},{"rr":45,"ys":21}];alert(obj.length);
		$("form input[name='sectionOn']").on('click',function(){
			if($(this).get(0).checked){
				$("form input[name^='sections']").each(function(){
					$(this).get(0).checked = true;
				});
			}else{
				$("form input[name^='sections']").each(function(){
					$(this).get(0).checked = false;
				});
			}
		});

		function search(url,param,type){
			window.location.href = url + 'searchcont=' + param + '&searchtype=' + type;
		}

		var toUrl = $("form input[class*='search']").attr("_for");
		var tbname = $("form input[name='tbname']").val();
		var imgtype = '.jpg.png.gif.bmp.svg.ai';
		function dealDom(ret){
			var data = ret['data'];
			if(typeof data != 'object'){
				return false;
			}
			var rowStr = '';
			for(var row in data){
				rowStr += '<tr><td><input type="checkbox" name="sections[]" value="'+data[row]['id']+'"></td>';
				for(var col in data[row]){
					var tmp = data[row][col];
						tmp = tmp.replace('/\s/g','');
					if(tmp != '' && imgtype.indexOf(tmp.substring(tmp.lastIndexOf('.'))) > -1){
						rowStr += '<td><img width="80" height="60" src="'+data[row][col]+'" /></td>';
					}else{
						rowStr += '<td>'+data[row][col]+'</td>';
					}
				}
				rowStr += '</tr>';
			}
			if(data.length == 0){
				var note = '<span style="color:red;">没有找到数据！</span>';
				$("form table tbody").html('');
				$("form .note").html(note);
				$("form .note").show();
			}else{
				$("form table tbody").html(rowStr);
				$("form .note").hide();
			}

			$(".form-group span[class='totalpage']").html('共'+ret['totalpage']+'页');
			$(".form-group span[class='curpage']").html('第'+ret['curpage']+'页');
		}
		function dealData(url,exparam,dealDom){
			$.ajax({
				url : url,
				type : 'POST',
				dataType : 'json',
				data : exparam,
				success : function(ret){
					//ret = eval(ret);
					if(dealDom && typeof dealDom == "function"){
						dealDom(ret);
					}
				},
				error : function(xhr,status,err){
					alert(err);
				}
			});
		}
		$("form input[class*='search']").on('click',function(){
			var type = $("form select option:selected").val();
			var param = $("form input[name='searchcont']").val();

			$.ajax({
				url : toUrl,
				type : 'POST',
				dataType : 'json',
				data : {
					"searchcont":param,"searchtype":type,
					"tbname":tbname,"curpage":1,
				},
				success : function(ret){
					//alert(ret.data.length);
					var data = ret["data"];
					//ret = eval(ret);
					var rowStr = '';
					for(var row in data){
						rowStr += '<tr><td><input type="checkbox" name="sections[]" value="'+data[row]['id']+'"></td>';
						for(var col in data[row]){
							var tmp = data[row][col];
							tmp = tmp.replace(/\s/g,'');
							if(tmp != '' && imgtype.indexOf(tmp.substring(tmp.lastIndexOf('.'))) > -1){
								rowStr += '<td><img width="80" height="60" src="'+data[row][col]+'" /></td>';
							}else{
								rowStr += '<td>'+data[row][col]+'</td>';
							}
						}
						rowStr += '</tr>';
					}
					window.diyPageNum = ret['totalpage'];
					if(data.length == 0){
						var note = '<span style="color:red;">没有找到数据！</span>';
						$("form table tbody").html('');
						$("form .note").html(note);
						$("form .note").show();
					}else{
						$("form table tbody").html(rowStr);
						$("form .note").hide();
					}
					$(".form-group span[class='totalpage']").html('共'+ret['totalpage']+'页');
					$(".form-group span[class='curpage']").html('第'+ret['curpage']+'页');
				},
				error : function(xhr,status,err){
					alert(err);
				}
			});
		});
		var pageclk = 1;
		$(".form-group a[class='prevpage']").on('click',function(){
			pageclk--;
			pageclk = pageclk < 1 ? 1 : pageclk;
			//alert(pageclk);
			var type = $("form select option:selected").val();
			var param = $("form input[name='searchcont']").val();
			var exs = {
					"searchcont":param,"searchtype":type,
					"tbname":tbname,"curpage":pageclk,
				};
			dealData(toUrl,exs,dealDom);
			return false;
		});
		$(".form-group a[class='nextpage']").on('click',function(){
			pageclk++;
			pageclk = pageclk > window.diyPageNum ? window.diyPageNum : pageclk;
			//alert(pageclk);
			var type = $("form select option:selected").val();
			var param = $("form input[name='searchcont']").val();
			var exs = {
					"searchcont":param,"searchtype":type,
					"tbname":tbname,"curpage":pageclk,
				};
			dealData(toUrl,exs,dealDom);
			return false;
		});
		$("form input[name='output']").on('click',function(){
			var data = $("form").serialize();
			if(data.indexOf('sections') > -1){
				window.location.href = window.location.href+'&output=1988&tbname='+tbname+'&'+data;
			}else{
				alert('您还未勾选任何选项！');
				return false;
			}			
		});
		$("form input[name='outputall']").on('click',function(){
			if(confirm("你确定要导出全表吗？")){
				window.location.href = window.location.href+'&outputall=1988&tbname='+tbname;
			}
		});
	})();
	</script>
</body>
</html>