<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php  echo $this->createWebUrl('member',array('pid'=>$pid))?>">会员管理</a></li>
    <li <?php  if($dl==1) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('dlmember',array('dl'=>1,'status'=>1))?>">代理管理</a></li>
    <li <?php  if($dl==2) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('dlmember',array('dl'=>2,'status'=>1))?>">审核中代理管理</a></li>
</ul>
<style>
th{
	text-align: left !important;
}
.table-responsive .label { display:inline-block;margin:0;margin-bottom:2px;}
td{
	text-align: left !important;
	/*white-space: normal !important;
	word-break: break-all !important;*/
}
</style>
<div class="panel panel-info">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="<?php  echo $this->createWebUrl('member')?>" method="post" class="form-horizontal">
                <div class="form-group">
                   <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 200px;">昵称/UID</label>
                    <div class="col-sm-2 col-lg-3">
	                    <input type="text" name="name" value="<?php  echo $name;?>" class="form-control" style="display: inline-block;">
                    </div>
                   <button class="btn btn-default">搜索</button>
                </div>
            </form>
        </div>
</div>

<div class="panel panel-default">

	<div class="panel-body" style="text-align: center;">


        <table class="table table-hover table-responsive">

            <thead class="navbar-inner">
                <tr>
                    <th width='350'>昵称</th>		
                    <th>地区</th>
                    <th>性别</th>                    
                    <th>注册时间</th>
                    <th>关注</th>
					<th>操作</th>
                </tr>
            </thead>

            <tbody >
            

                <?php  if(is_array($list)) { foreach($list as $l) { ?>

                <tr>
                    <td><img src="<?php  echo $l['avatar'];?>" style="width: 30px;height: 30px;border-radius:50%"> <?php  echo $l['nickname'];?><Br><label class="label label-success">UID：<?php  echo $l['uid'];?></label><br><label class="label label-success">OPENID：<?php  echo $l['openid'];?></label></td>
                    <td><?php  echo $l['resideprovince'];?><?php  echo $l['residecity'];?></td>
                    <td><?php  if($l['gender']==1) { ?>男<?php  } else { ?>女<?php  } ?></td>
                    <td><?php  echo date('Y-m-d',$l['followtime'])?><Br><?php  echo date('H:i:s',$l['followtime'])?></td>
                    <td><?php  if($l['follow']==1) { ?><label class="label label-success">已关注</label><?php  } else { ?><label class="label label-warning">取消关注</label><?php  } ?></td>


                    <td>
                    	<a href='<?php  echo $this->createWebUrl("memberedit",array("openid"=>$l["openid"]))?>' class='btn btn-info btn-sm'>设置代理</a>
                        <!--a href='<?php  echo $this->createWebUrl("memberedit",array("id"=>$l["id"]))?>' class='btn btn-info btn-sm'>分销订单</a-->
                    	<!--a onclick="return confirm('删除后将无法恢复，确定删除吗？')" href='<?php  echo $this->createWebUrl("delete",array("sceneid"=>$l["sceneid"],"sid"=>$l["id"],"status"=>$status))?>' class='btn btn-danger btn-sm'>删除</a-->
                    </td>
                </tr>
                <?php  } } ?>

            </tbody>

        </table>

        <?php  echo $pager;?>

    </div>

</div>
<script language="javascript">
			     			require(['bootstrap'],function(){
        $("[rel=pop]").popover({
            trigger:'manual',
            placement : 'left', 
            title : $(this).data('title'),
            html: 'true', 
            content : $(this).data('content'),
            animation: false
        }).on("mouseenter", function () {
                    var _this = this;
                    $(this).popover("show"); 
                    $(this).siblings(".popover").on("mouseleave", function () {
                        $(_this).popover('hide');
                    });
                }).on("mouseleave", function () {
                    var _this = this;
                    setTimeout(function () {
                        if (!$(".popover:hover").length) {
                            $(_this).popover("hide")
                        }
                    }, 100);
                });
 
	 
	   });
				   
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>