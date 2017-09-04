<?php defined('IN_IA') or exit('Access Denied');?>
		<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_head', TEMPLATE_INCLUDEPATH)) : (include template('public_head', TEMPLATE_INCLUDEPATH));?>
		<!--中间内容开始-->
		<section>
		    <section class="hbox stretch">
		    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_left', TEMPLATE_INCLUDEPATH)) : (include template('public_left', TEMPLATE_INCLUDEPATH));?>
		    <!--右边框架-->
			  <section id="content">
			    <section class="vbox">
			        <section class="scrollable padder" style="padding-bottom:70px;">
                        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                          <li><a href="<?php  echo $this->createWebUrl('index')?>"><i class="fa fa-home"></i> 首页  </a></li>
                          <li class="active">淘客商品</li>
                        </ul>
			          
                        <!--搜索开始-->
                        <div class="panel panel-info">
                            <div class="panel-heading">这里只是检测高佣金能不能申请，如果有查询出来说明可以申请</div>
                            <div class="panel-body">
                                <form action="<?php  echo $this->createWebUrl('tbgoods',array('op'=>'seach'))?>" method="get" class="form-horizontal">
                                <input type="hidden" name="c" value="site">
                                <input type="hidden" name="a" value="entry">
                                <input type="hidden" name="m" value="tiger_taoke">
                                <input type="hidden" name="op" value="seach">
                                <input type="hidden" name="do" value="yjjc">
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">淘宝商品numid</label>
                                        <div class="col-sm-9 col-md-8 col-lg-8 col-xs-12">
                                            <input type="text" class="form-control" name="key" value="<?php  echo $num_iid;?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <button class="btn btn-default"><i class="fa fa-search"></i>查询测试</button>
                                        </div>
                                    </div>
                                </form>
                               </div>
                               <div style="width:300px;padding:10px;">
                               <?php  if($res['error']) { ?>
                                 错误：<?php  echo $res['error'];?>
                               <?php  } else { ?>
                                   <?php  if($res['commissionRate']) { ?>
                                       <img src="<?php  echo $res['pictUrl'];?>" width=200><br>
                                       商品ID：<?php  echo $res['num_iid'];?><br>
                                       标题：<?php  echo $res['title'];?><br>
                                       佣金：<?php  echo $res['commissionRate'];?>%<br>
                                       价格：<?php  echo $res['price'];?>元<br>
                                       <?php  if($cfg['yktype']==1) { ?>
                                         <a href="<?php  echo $res['dclickUrl'];?>" target="_blank">点击查看链接</a>
                                       <?php  } ?>
                                   <?php  } ?>
                               <?php  } ?>
                               </div>
                          </div>
                        <!--搜索结束-->


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


</body>
</html>