<?php defined('IN_IA') or exit('Access Denied');?>
		<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_head', TEMPLATE_INCLUDEPATH)) : (include template('public_head', TEMPLATE_INCLUDEPATH));?>
		<!--中间内容开始-->
		<section>
		    <section class="hbox stretch">
		    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_left', TEMPLATE_INCLUDEPATH)) : (include template('public_left', TEMPLATE_INCLUDEPATH));?>
		    <!--右边框架-->
			  <section id="content">
			    <section class="vbox">
			        <section class="scrollable padder"  style="padding-bottom:50px;">
                        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                          <li><a href="<?php  echo $this->createWebUrl('index')?>"><i class="fa fa-home"></i> 首页  </a></li>
                          <li class="active">积分商城</li>
                        </ul>
			            <!--编辑内容-->
                        <ul class="nav nav-tabs">
                            <li <?php  if(!$status) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('share',array('pid'=>$pid))?>">推广记录</a></li>
                            <li <?php  if($status) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('share',array('pid'=>$pid,'status'=>1))?>">黑名单</a></li>
                        </ul>
                        <div class="panel panel-info">
                        <div class="panel-heading">筛选</div>
                        <div class="panel-body">
                            <form action="<?php  echo $this->createWebUrl('share',array('pid'=>$pid,'status'=>$status))?>" method="post" class="form-horizontal">
                                <div class="form-group">
                                   <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 200px;">昵称或UID</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <input type="text" name="name" value="<?php  echo $name;?>" class="form-control" style="display: inline-block;">
                                    </div>
                                   <button class="btn btn-default">搜索</button>
                                </div>
                            </form>
                        </div>
                </div>
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
                <div class="panel panel-default">

                    <div class="panel-body" style="text-align: center;">

                        <table class="table table-hover">

                            <thead class="navbar-inner">
                                <tr>
                                    <th>昵称</th>		
                                    <th>下级</th>
                                    <th>地区</th>
                                    <th>推荐人</th>
                                    <th>关注时间</th>
                                    <th style="width: 200px;">操作</th>
                                </tr>
                            </thead>

                            <tbody id="table_content">

                                <?php  if(is_array($mlist)) { foreach($mlist as $l) { ?>

                <tr rel="pop" data-title="UID: <?php  echo $l['uid'];?> " data-content="推荐人 <br/> 				
                                              [<?php  echo $l['tjrid'];?>]<?php  if($l['tjrname']!='') { ?><?php  echo $l['tjrname'];?><?php  } else { ?>平台<?php  } ?>" data-original-title="" title="" aria-describedby="popover215830">
                    <td><img src="<?php  echo $l['avatar'];?>" style="width: 30px;height: 30px;"> <?php  echo $l['nickname'];?></td>
                    
                    <td>
                        <div class="btn btn-info btn-sm"><?php  if($l['l1']<>0) { ?><a style="color:#ffffff;" href="<?php  echo $this->createWebUrl('share',array('pid'=>$pid,'sid'=>$l['uid']))?>" class="col">一级 : <?php  echo $l['l1'];?></a><?php  } else { ?> 一级：0<?php  } ?></div><br>
                        <div style="clear:both;height:4px;"></div>

                        <div class="btn btn-info btn-sm"><?php  if($l['l2']<>0) { ?><a style="color:#ffffff"  href="<?php  echo $this->createWebUrl('share',array('pid'=>$pid,'cid'=>$l['uid']))?>" class="col">二级: <?php  echo $l['l2'];?></a><?php  } else { ?>二级: 0<?php  } ?></div><br>

                        <!--label class="label label-info"><?php  if($l['lv3']<>0) { ?><a style="color:#ffffff"  href="<?php  echo $this->createWebUrl('hymember',array('id'=>$l['id'],'pid'=>$pid,op=>'3'));?>" class="col">三级: <?php  echo $l['lv3'];?></a><?php  } else { ?>三级: 0<?php  } ?></label><br-->
                    </td>
                    <td><?php  echo $l['province'];?><?php  echo $l['city'];?><Br><?php  echo $l['district'];?></td>
                    <td><?php  echo $l['tjrname'];?></td>
                    
                    <td><?php  echo date('Y-m-d',$l['createtime'])?><Br><?php  echo date('H:i:s',$l['createtime'])?></td>
                    <!--td><?php  if($l['follow']==1) { ?><label class="label label-success">已关注</label><?php  } else { ?><label class="label label-warning">取消关注</label><?php  } ?></td-->


                    <td>
                    	<a href='<?php  echo $this->createWebUrl("memberedit",array("id"=>$l["id"]))?>' class='btn btn-info btn-sm'>会员信息</a>
                        <!--a href='<?php  echo $this->createWebUrl("memberedit",array("id"=>$l["id"]))?>' class='btn btn-info btn-sm'>分销订单</a-->
                    	<a onclick="return confirm('删除后将无法恢复，确定删除吗？')" href='<?php  echo $this->createWebUrl("delete",array("sceneid"=>$l["sceneid"],"sid"=>$l["id"],"status"=>$status))?>' class='btn btn-danger btn-sm'>删除</a>
                    </td>
                </tr>
                <?php  } } ?>

                            </tbody>

                        </table>

                        <?php  echo $pager;?>

                    </div>

                </div>
                        <!--编辑内容结束-->
			        </section>
			        <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_bottom', TEMPLATE_INCLUDEPATH)) : (include template('public_bottom', TEMPLATE_INCLUDEPATH));?>
			    </section>
			    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
			  </section>
			  <aside class="bg-light lter b-l aside-md hide" id="notes">
			       <div class="wrapper">不知道放什么</div>
			  </aside>
			<!--右边框架结束-->
			</section>
		  </section>
		<!--中间内容结束-->
	</section>

<script language="javascript">
		require(['bootstrap'],function(){
        $("[rel=pop]").popover({
            trigger:'manual',
            placement : 'top', 
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
</body>
</html>