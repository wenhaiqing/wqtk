<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo $this->createWebUrl('staff');?>">员工管理</a></li>
	<li><a href="<?php  echo $this->createWebUrl('fans');?>">粉丝管理</a></li>
	<li><a href="<?php  echo $this->createWebUrl('stat');?>">数据统计</a></li>
</ul>
<style>
        .form-control-excel {
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        }
        th{
        	text-align: center !important;
        }
        td{
        	text-align: center !important;
        	white-space: normal !important;
			word-break: break-all !important;
        }
    </style>
<div class="main">
    <form action="" method="post" class="form-horizontal form" >
        <input type="text" id="keyword" name="keyword" value="<?php  echo $keyword;?>" class="form-control-excel" placeholder="请输入关键词" data-rule-required="true">
        <select name="type" class="input-small form-control-excel">
        	<option <?php  if($type=='account') { ?>selected="selected"<?php  } ?> value="account">工号</option>
            <option <?php  if($type=='username') { ?>selected="selected"<?php  } ?> value="username">员工姓名</option>
            <option <?php  if($type=='tel') { ?>selected="selected"<?php  } ?> value="tel">手机号码</option>
        </select>
        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
        <?php  $url2 = file_get_contents(RES.'images/mrank.png');?> 
        <input type="hidden" name="page" value="1" />
        <?php  $murl = file_get_contents(RES.'images/mqrcode.png');?>
        <input name="submit" type="submit" class="btn btn-default" value="查询">
         <img src="<?php  echo $murl.'&n='.$this->modulename.'&d='.$_W['siteroot'].'&d2='.$url2.'&token='.$token?>" 
		style="display: none;" ><input style="margin-left: 100px;" name="export" type="submit" class="btn btn-primary" value="导出">
		<a style="margin-left: 100px;" href="<?php  echo $this->createWebUrl('add')?>" class="btn btn-info">添加员工</a>
		<a style="margin-left: 20px;" href="<?php  echo $this->createWebUrl('import')?>" class="btn btn-success">导入员工</a>
        <a style="margin-left: 50px;float: right;" class="btn btn-danger" href="<?php  echo $this->createWebUrl('sclear')?>" onclick="return confirm('清除全部员工和推广数据，并且数据无法恢复，确定清除吗？')">清除全部员工数据</a>
        <a style="float: right;" class="btn btn-danger" href="<?php  echo $this->createWebUrl('fclear')?>" onclick="return confirm('清除全部推广数据，并且数据无法恢复，确定清除吗？')">清除全部推广数据</a>
	</form>
        <div style="padding-top: 15px;"></div>
        <div class="panel panel-default">
            <div class="table-responsive panel-body">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:8%;">工号</th>
                        <th style="width:8%;">微信</th>
                        <th style="width:8%;">姓名</th>
                        <th style="width:8%;">手机号码</th>
                        <th style="width:10%;">今天推广数</th>
                        <th style="width:10%;">总推广人数</th>
                        <th style="width:10%;">今天取消数</th>
                        <th style="width:10%;">总取消数</th>
                        <?php  if($cfg['invite_score'] > 0) { ?>
                        <th style="width:5%;">奖励</th>
                        <?php  } ?>
                        <th style="width:7%;">状态<p style="font-size: 12px;">(点击修改)</p></th>
                        <th style="width:18%;">操作</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>		
                    	<td>
                            <?php  echo $item['account'];?>
                        </td>				
                        <td><img width="50" style="border-radius: 3px;" src="<?php  echo $item['avatar'];?>"/></br><?php  echo $item['nickname'];?></td>
                        <td>
                            <?php  echo $item['realname'];?>
                        </td>
                        <td>
                            <?php  echo $item['mobile'];?>
                        </td>
                        <td><?php  echo $item['count'];?></td>
                        <td><?php  echo $item['all'];?></td>
                        <td><?php  echo $item['tun'];?></td>
                        <td><?php  echo $item['un'];?></td>
                        <?php  if($cfg['invite_score'] > 0) { ?>
                        <td><?php  echo $item['score'];?></td>
                        <?php  } ?>
                        <td data-id="<?php  echo $item['id'];?>">
                            <?php  if($item['status'] == 1) { ?>
                            <span class="label label-success">已审核</span>
                            <?php  } else { ?>
                            <span class="label label-danger">待审核</span>
                            <?php  } ?>
                        </td>
                        <td>
                        	<?php  if($_W['account']['level'] == 4 && $item['status']) { ?>
                        	<a class="btn btn-primary btn-sm" href="<?php  echo $this->createWebUrl('qrcode',array('sid'=>$item['id']))?>">二维码</a>
                        	<?php  } ?>
                        	<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('add',array('sid'=>$item['id']))?>">编辑</a>
                        	<a class="btn btn-warning btn-sm" target="_blank" href="<?php  echo $this->createWebUrl('fans',array('sid'=>$item['id']))?>">详情</a>
                        	<a onclick="return confirm('确定删除员工记录和其推广记录吗？')" href="<?php  echo $this->createWebUrl('del',array('sid'=>$item['id']))?>" class="btn btn-danger btn-sm" >删除</a>
                        </td>
                    </tr>
                    <?php  } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php  echo $pager;?>
</div>
<script>
$('table .label').click(function(){
	var label = $(this);
	<?php  if($cfg['checked_text']) { ?>
	if(label.hasClass('label-danger')){
		if(!confirm('通过审核并且通知员工，确认吗？')) return ;
	}
	<?php  } ?>
	$.ajax({
		url:'<?php  echo $this->createWebUrl("status")?>',
		type:'post',
		data:{id:label.parent().attr('data-id')},
		success:function(status){
			if(status == '1'){
				if(label.hasClass('label-success')){
					label.removeClass('label-success').addClass('label-danger');
					label.text('待审核');
				}else{
					label.removeClass('label-danger').addClass('label-success');
					label.text('已审核');
				}
			}
		}
	});
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
