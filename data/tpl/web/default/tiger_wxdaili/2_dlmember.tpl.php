<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li <?php  if($dl=='') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('member',array('pid'=>$pid))?>">会员管理</a></li>
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
            <form action="<?php  echo $this->createWebUrl('dlmember')?>" method="post" class="form-horizontal">
                <div class="form-group">
                   <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 200px;">输入关键词</label>
                    <div class="col-sm-2 col-lg-3">
	                    <input type="text" name="name" value="<?php  echo $name;?>" class="form-control" style="display: inline-block;">
                        <span class="help-block" style="color:#ff0000">支持：昵称/姓名/手机号/微信号/代理推广位/群名称 搜索</span>
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
                    <th>代理</th>                    
                    <th>注册时间</th>
					<th>操作</th>
                </tr>
            </thead>

            <tbody >
            

                <?php  if(is_array($list)) { foreach($list as $l) { ?>

                <tr>
                    <td style="line-height:25px;"><img src="<?php  echo $l['avatar'];?>" style="width: 30px;height: 30px;border-radius:50%"> <?php  echo $l['nickname'];?><Br>UID：<?php  echo $l['openid'];?><br>OPENID：<?php  echo $l['from_user'];?><?php  if($l['tgwid']) { ?><br>代理姓名：<?php  echo $l['tname'];?><br>联系电话：<?php  echo $l['tel'];?><br>专属群：<b style="color:#Ff0000"><?php  echo $l['qunname'];?></b><Br>推广位：<?php  echo $l['tgwid'];?><?php  } ?><Br>申请理由：<?php  echo $l['dlmsg'];?></td>
                    <td><?php  echo $l['province'];?><?php  echo $l['city'];?></td>
                    <td><?php  if($l['dltype']==1) { ?>是<?php  } else { ?>否<?php  } ?></td>
                    <td><?php  echo date('Y-m-d',$l['createtime'])?><Br><?php  echo date('H:i:s',$l['createtime'])?></td>
                    <td>
                    	<a href='<?php  echo $this->createWebUrl("memberedit",array("openid"=>$l["from_user"]))?>' class='btn btn-info btn-sm'>设置代理</a>
                        <a href='<?php  echo $this->createWebUrl("memberorder",array("openid"=>$l["from_user"]))?>' class='btn btn-info btn-sm'>代理订单</a>
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