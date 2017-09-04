<?php defined('IN_IA') or exit('Access Denied');?>
		<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_head', TEMPLATE_INCLUDEPATH)) : (include template('public_head', TEMPLATE_INCLUDEPATH));?>
		<!--中间内容开始-->
		<section>
		    <section class="hbox stretch">
		    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_left', TEMPLATE_INCLUDEPATH)) : (include template('public_left', TEMPLATE_INCLUDEPATH));?>
		    <!--右边框架-->
			  <section id="content">
			    <section class="vbox">
			       <section class="scrollable padder" style="padding-bottom:50px;">
                        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                          <li><a href="<?php  echo $this->createWebUrl('index')?>"><i class="fa fa-home"></i> 首页  </a></li>
                          <li class="active">淘客订单管理(<?php  echo $total;?>)</li>
                        </ul>

                        <form method="post" enctype="multipart/form-data">
                            <div class="panel panel-default">
                               <div class="panel-heading">
                                  <h3 class="panel-title">
                                   淘客订单导入
                                  </h3>
                               </div>
                               <div class="panel-body">
                                    <div class="form-group">
                                        <label for="type" class="col-sm-2 control-label">导入订单</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="excelfile" class="form-control" />
                                            <div class="help-block">请上传 xlsx 格式的Excel文件（文件大小1M以内）</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <input name="submit" type="submit" value="提交" class="btn btn-primary col-lg-1">
                                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                    </div>
                               </div>
                            </div>
                        </form>


                        <div class="panel panel-info">
                        <div class="panel-heading">搜索</div>
                        <div class="panel-body">
                            <form action="<?php  echo $this->createWebUrl('tkorder',array('op'=>'seach'))?>" method="post" class="form-horizontal">
                                <div class="form-group">
                                   <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 200px;">订单号：</label>
                                    <div class="col-sm-2 col-lg-3">
                                        <input type="text" name="order" value="<?php  echo $order;?>" class="form-control" style="display: inline-block;">
                                    </div>
                                   <button class="btn btn-default">搜索</button>
                                </div>
                            </form>
                        </div>


                        <div class="panel panel-default">

                            <div class="panel-body">

                                <table class="table table-hover">

                                    <thead class="navbar-inner">

                                        <tr>
                                            <th style="width:360px;">商品信息</th>
                                            
                                            <th>订单状态</th>
                                            <th >收入/分成比例</th>	
                                            <th>金额</th>
                                            <th style="width:70px">平台</th>
                                            <th>更新时间</th>
                                        </tr>

                                    </thead>

                                    <tbody id="table_content" >

                                        <?php  if(is_array($list)) { foreach($list as $l) { ?>

                                        <tr  >
                                            <td style="line-height:25px;">店铺名：<?php  echo $l['shopname'];?><br><?php  echo $l['title'];?><br>商品ID：<?php  echo $l['numid'];?><Br><span style="color:red">订单号：<?php  echo $l['orderid'];?></span><Br>创建时间：<?php  echo date('Y-m-d H:i:s',$l['addtime'])?><Br><span style="color:red"><?php  echo $l['tgwtitle'];?>：<?php  echo $l['tgwid'];?></span><BR>结算时间：<?php  echo date('Y-m-d H:i:s',$l['jstime'])?></td>
                                            <td><?php  echo $l['orderzt'];?></td>
                                            <td>收入：<?php  echo $l['srbl'];?><Br>分成：<?php  echo $l['fcbl'];?></td>
                                            <td>付款金额：<?php  echo $l['fkprice'];?><br>效果预估：<?php  echo $l['xgyg'];?></td>
                                            <td><?php  echo $l['pt'];?></td>
                                            <td><?php  echo date('Y-m-d',$l['createtime'])?><Br><?php  echo date('H:i:s',$l['createtime'])?></td>

                                        </tr>

                                        <?php  } } ?>

                                    </tbody>

                                </table>

                                <?php  echo $pager;?>

                            </div>

                        </div>

                        <!---->
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
<script type="text/javascript">
	function sh(id){

		var jljf1="#jljf"+id;
		var jljf=$(jljf1).val();
        var sjjl1="#sjjl"+id;
		var sjjl=$(sjjl1).val();
		if(jljf==''){
			 alert('请填写奖励积分');
			 return false;
		}

        $.ajax({
             type: "GET",
             url: "<?php  echo $this->createWebUrl('shsd')?>",
             data: {id:id, jljf:jljf,sjjl:sjjl},
             dataType: "json",
             success: function(res){
                    if(res.status==1){
                        //window.location.reload();//刷新当前页面.
                        alert('审核奖励成功');       
                        window.location.reload();//刷新当前页面.
                    }else{
                       alert('审核奖励失败');
                    }
             }
         });
		
 
	}
</script>
</body>
</html>