<?php defined('IN_IA') or exit('Access Denied');?><!--左边框架 -->
		      <aside class="bg-dark  lter aside-md hidden-print" id="nav">
		        <section class="vbox">
		        <!--左边菜单头部-->
		          <header class="header bg-primary lter text-center clearfix">
		            <div class="btn-group">
		              <button type="button" class="btn btn-sm btn-dark btn-icon" title="New project"><i class="fa fa-plus"></i></button>
		              <div class="btn-group hidden-nav-xs">
		                <a href="<?php  echo url('account/display');?>" class="btn btn-sm btn-primary" target="_blank">返回系统</a>
		                
		              </div>
		            </div>
		          </header>
		        <!--左边菜单头部结束-->
		          <section class="w-f scrollable">
		            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333"> 
			            <!-- 左边菜单导航 -->
			            <nav class="nav-primary hidden-xs">
			                <ul class="nav">
		                        <li  <?php  if($_GPC['do']=='' || $_GPC['do']=='index' || $_GPC['do']=='setting' || ($_GPC['op']=='post' && $_GPC['do']=='cdtype') || ($_GPC['op']=='display' && $_GPC['do']=='cdtype') || ($_GPC['op']=='post' && $_GPC['do']=='xnmsg') || ($_GPC['op']=='display' && $_GPC['do']=='xnmsg') || ($_GPC['op']=='display' && $_GPC['do']=='txsh') || ($_GPC['op']=='display' && $_GPC['do']=='moban') || ($_GPC['op']=='post' && $_GPC['do']=='moban')) { ?>class="active"<?php  } ?>> <a href="#pages" > <i class="fa fa-file-text icon"> <b class="bg-primary"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>基础设置</span> </a>
			                    <ul class="nav lt">
                                  <li <?php  if($_GPC['do']=='setting') { ?> class="active"<?php  } ?>> <a href="<?php  echo $_W['siteroot'];?>/web/index.php?c=profile&a=module&do=setting&m=tiger_taoke" > <i class="fa fa-angle-right"></i> <span>基础设置</span> </a> </li>
                                  <li <?php  if(($_GPC['op']=='display' && $_GPC['do']=='moban') || ($_GPC['op']=='post' && $_GPC['do']=='moban')) { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('moban', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>模版消息管理</span> </a> </li>

			                       <li <?php  if($_GPC['op']=='post' && $_GPC['do']=='cdtype') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('cdtype', array('op' => 'post'));?>" > <i class="fa fa-angle-right"></i> <span>增加个人中心菜单</span> </a> </li>

                                  <li <?php  if($_GPC['op']=='display' && $_GPC['do']=='cdtype') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('cdtype', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>管理个人中心菜单</span> </a> </li>
                                  <li <?php  if($_GPC['op']=='display' && $_GPC['do']=='txsh') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('txsh', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>佣金提现审核</span> </a> </li>

                                  <li <?php  if($_GPC['op']=='post' && $_GPC['do']=='xnmsg') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('xnmsg', array('op' => 'post'));?>" > <i class="fa fa-angle-right"></i> <span>添加领券提醒</span> </a> </li>

                                  <li <?php  if($_GPC['op']=='display' && $_GPC['do']=='xnmsg') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('xnmsg', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>管理领券提醒</span> </a> </li>
                                   <li <?php  if($_GPC['do']=='yjjc') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('yjjc');?>" > <i class="fa fa-angle-right"></i> <span>佣金查询</span> </a> </li>

			                    </ul>
			                  </li>


			                  <li <?php  if(($_GPC['op']=='post' && $_GPC['do']=='tbgoods') || ($_GPC['op']=='display' && $_GPC['do']=='tbgoods') || ($_GPC['op']=='qf' && $_GPC['do']=='tbgoods') || ($_GPC['op']=='qf' && $_GPC['do']=='tbgoods') || ($_GPC['op']=='post' && $_GPC['do']=='fztype') || ($_GPC['op']=='display' && $_GPC['do']=='fztype') || $_GPC['do']=='tbgoodform' || $_GPC['do']=='tkorder' || $_GPC['do']=='order' ||  $_GPC['do']=='zttype') { ?>class="active"<?php  } ?>> <a href="#layout" class="active"> <i class="fa fa-columns icon"> <b class="bg-warning"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>淘客商品</span> </a>
			                    <ul class="nav lt">
			                       <li <?php  if($_GPC['op']=='post' && $_GPC['do']=='tbgoods') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('tbgoods', array('op' => 'post'));?>" > <i class="fa fa-angle-right"></i> <span>添加商品</span> </a> </li>
			                      <li <?php  if($_GPC['op']=='display' && $_GPC['do']=='tbgoods') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('tbgoods', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>管理商品</span> </a> </li>
                                  <li <?php  if($_GPC['op']=='qf' && $_GPC['do']=='tbgoods') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('tbgoods', array('op' => 'qf'));?>" > <i class="fa fa-angle-right"></i> <span>群发商品</span> </a> </li>

                                  <li <?php  if($_GPC['op']=='display' && $_GPC['do']=='fztype') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('fztype', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>分类管理</span> </a> </li>
                                  <li <?php  if($_GPC['do']=='tbgoodform') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('tbgoodform', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>导入商品</span> </a> </li>
                                  <li <?php  if($_GPC['do']=='tkorder') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('tkorder', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>淘客订单</span> </a> </li>
                                  <li <?php  if($_GPC['do']=='order') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>淘客订单审核</span> </a> </li>

                                  <li <?php  if($_GPC['op']=='display' && $_GPC['do']=='zttype') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('zttype', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>专题管理</span> </a> </li>
                                  
			                    </ul>
			                  </li>

                              <li <?php  if($_GPC['do']=='caijiset' || $_GPC['do']=='dtkcaiji' || $_GPC['do']=='hlcaijiset') { ?> class="active"<?php  } ?>> <a href="#pages" > <i class="fa fa-file-text icon"> <b class="bg-primary"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>数据采集</span> </a>
			                    <ul class="nav lt">
                                  <li <?php  if($_GPC['do']=='caijiset') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('caijiset', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>大淘客采集设置</span> </a> </li>
			                      <li <?php  if($_GPC['do']=='dtkcaiji') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('dtkcaiji', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>大淘客采集</span> </a> </li>
                                  <li <?php  if($_GPC['do']=='hlcaijiset') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('hlcaijiset', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>互力采集</span> </a> </li>
			                    </ul>
			                  </li>

			                  <li <?php  if($_GPC['do']=='mposter') { ?>class="active"<?php  } ?>> <a href="#uikit" > <i class="fa fa-flask icon"> <b class="bg-success"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>公众号管理</span> </a>
			                    <ul class="nav lt">
			                      <li <?php  if($_GPC['do']=='mposter') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('mposter')?>" > <i class="fa fa-angle-right"></i> <span>海报管理</span> </a> </li>	
                                  <li  <?php  if($_GPC['do']=='dhlist') { ?> class="active"<?php  } ?>> <a href="<?php  echo wurl('platform/menu');?>" target="_blank" > <i class="fa fa-angle-right"></i> <span>自定义菜单</span> </a> </li>
                                 	
			                    </ul>
			                  </li>

			                  <li <?php  if($_GPC['do']=='share') { ?>class="active"<?php  } ?> <?php  if($_GPC['do']=='memberedit') { ?>class="active"<?php  } ?>> <a href="#pages" > <i class="fa fa-file-text icon"> <b class="bg-primary"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>会员管理</span> </a>
			                    <ul class="nav lt">
			                      <li <?php  if($_GPC['do']=='share') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('share');?>" > <i class="fa fa-angle-right"></i> <span>微信会员</span> </a> </li>
			                    </ul>
			                  </li>

			                  <li  <?php  if(( $_GPC['op']=='post' && $_GPC['do']=='Goods') || ($_GPC['op']=='display' && $_GPC['do']=='Goods') || $_GPC['do']=='request' || $_GPC['do']=='dhlist' || $_GPC['do']=='sdgl') { ?>class="active"<?php  } ?>> <a href="#pages" > <i class="fa fa-file-text icon"> <b class="bg-primary"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>互动营销</span> </a>
			                    <ul class="nav lt">
			                      <li <?php  if($_GPC['op']=='post' && $_GPC['do']=='Goods') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('goods', array('op' => 'post'));?>" > <i class="fa fa-angle-right"></i> <span>添加礼品</span> </a> </li>
			                      <li <?php  if($_GPC['op']=='display' && $_GPC['do']=='Goods') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('goods', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>管理礼品</span> </a> </li>
			                      <li <?php  if($_GPC['do']=='request') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('request');?>" > <i class="fa fa-angle-right"></i> <span>兑换记录</span> </a> </li>
                                  <li  <?php  if($_GPC['do']=='dhlist') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('dhlist');?>" > <i class="fa fa-angle-right"></i> <span>红包兑换记录</span> </a> </li>
                                   <li  <?php  if($_GPC['do']=='dhlist') { ?> class="active"<?php  } ?>> <a href="../index.php?c=site&a=entry&op=sign-credit&do=signmanage&m=we7_coupon" > <i class="fa fa-angle-right"></i> <span>积分签到</span> </a> </li>
                                   <li <?php  if($_GPC['do']=='sdgl') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('sdgl')?>" > <i class="fa fa-angle-right"></i> <span>晒单管理</span> </a> </li>
			                    </ul>
			                  </li>
			                  <li <?php  if(($_GPC['do']=='ad' && $_GPC['op']=='post') || ($_GPC['do']=='ad' && $_GPC['op']=='display')) { ?>class="active"<?php  } ?>> <a href="#pages" > <i class="fa fa-file-text icon"> <b class="bg-primary"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>广告管理</span> </a>
			                    <ul class="nav lt">
			                      <li <?php  if($_GPC['do']=='ad' && $_GPC['op']=='post') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('ad', array('op' => 'post'));?>" > <i class="fa fa-angle-right"></i> <span>添加广告</span> </a> </li>
                                  <li <?php  if($_GPC['do']=='ad' && $_GPC['op']=='display') { ?> class="active"<?php  } ?>> <a href="<?php  echo $this->createWebUrl('ad', array('op' => 'display'));?>" > <i class="fa fa-angle-right"></i> <span>管理广告</span> </a> </li>
			                    </ul>
			                  </li>

                              <li <?php  if($_GPC['do']=='kfqf' ||  $_GPC['do']=='mbxxqf') { ?>class="active"<?php  } ?> <?php  if($_GPC['do']=='memberedit') { ?>class="active"<?php  } ?>> <a href="#pages" > <i class="fa fa-file-text icon"> <b class="bg-primary"></b> </i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>群发管理</span> </a>
			                    <ul class="nav lt">
			                      <li <?php  if($_GPC['do']=='kfqf') { ?> class="active"<?php  } ?>> <a onclick="return confirm('确定要群发？群发的24小时和公众号有互动过的粉丝');return false;"  href="<?php  echo $this->createWebUrl('kfqf', array('op' => 'kfqf'))?>" > <i class="fa fa-angle-right"></i> <span>客服图文群发</span> </a> </li>
                                  <li <?php  if($_GPC['do']=='mbxxqf') { ?> class="active"<?php  } ?>> <a  onclick="return confirm('确定要群发？一天最多发一次，为了防止被封模版消息,群发的是粉丝营销—粉丝表里面的全部关注的粉丝');return false;"  href="<?php  echo $this->createWebUrl('mbxxqf', array('op' => 'qf'))?>" > <i class="fa fa-angle-right"></i> <span>模版消息群发</span> </a> </li>
			                    </ul>
			                  </li>
			                </ul>
			            </nav>
			            <!-- 左边菜单导航结束 --> 
		            </div>
		          </section>
		          <footer class="footer lt hidden-xs b-t b-black">
		            <div id="invite" class="dropup">
		              <section class="dropdown-menu on aside-md m-l-n">
		                <section class="panel bg-white">
		                  <header class="panel-heading b-b b-light"> 小淘 <i class="fa fa-circle text-success"></i> </header>
		                  <div class="panel-body animated fadeInRight">
		                    <p class="text-sm">微信号：xiao-360</p>
		                    <p><a href="#" class="btn btn-sm btn-facebook"> QQ：1640226229</a></p>
		                  </div>
		                </section>
		              </section>
		            </div>
		            <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-dark btn-icon"> <i class="fa fa-angle-left text"></i> <i class="fa fa-angle-right text-active"></i> </a>
		            <div class="btn-group hidden-nav-xs">
		              <!--button type="button" title="Chats" class="btn btn-icon btn-md btn-dark" data-toggle="dropdown" data-target="#chat"><i class="fa fa-comment-o"></i></button>
		              <button type="button" title="Contacts" class="btn btn-md btn-dark" data-toggle="dropdown" data-target="#invite">联系客服</button-->
		            </div>
		          </footer>
		        </section>
		      </aside>
		    <!--左边框架结束 -->