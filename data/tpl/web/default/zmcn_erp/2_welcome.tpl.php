<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
.zmcn_ico_r {padding:5px;float:right;}
.welcome-container .shortcut a{display:block;float:left;text-align:center;margin-right:1.2em;padding:8px 5px;width:7em;height:7em;overflow:hidden;color:#333;}
.welcome-container .shortcut a:hover{text-decoration:none;background:#eee;border-radius:3px;padding:7px 4px;border:1px solid #d5d5d5;}
.welcome-container .shortcut a i{display:block;font-size:3em;margin:.28em .2em;}
.welcome-container .shortcut a img{display:block;height:3em;margin:.85em auto;}
.welcome-container .shortcut a span{display:block;font-size:1em;overflow:hidden;white-space:nowrap;}
.welcome-container .account img{width:6em;height:6em;}
.clearfix:before,.clearfix:after{display:table;content:" "}
.clearfix:after{clear:both}
.page-header{padding-bottom:0;}
</style>
<div class="clearfix welcome-container">
<?php  if(is_array($panel)) { foreach($panel as $mid=>$ms) { ?>
	<div class="page-header">
		<h4><i class="<?php  echo $ms['logo'];?>"></i> <?php  echo $ms['title'];?>
		<?php  if($ms['seturl']) { ?><a class="zmcn_ico_r" rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php  echo $ms['title'];?>参数设置" href="<?php  echo $ms['seturl'];?>"><i class="fa fa-cog"></i></a><?php  } ?>
		<?php  if($ms['entries']['shortcut']) { ?><a class="zmcn_ico_r" rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php  echo $ms['title'];?>快捷菜单" href="<?php  echo $ms['shortcut_url'];?>"><i class="fa fa-plane"></i></a><?php  } ?>
		<?php  if($ms['entries']['profile']) { ?><a class="zmcn_ico_r" rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php  echo $ms['title'];?>个人中心导航" href="<?php  echo $ms['profile_url'];?>"><i class="fa fa-user"></i></a><?php  } ?>
		<?php  if($ms['entries']['home']) { ?><a class="zmcn_ico_r" rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php  echo $ms['title'];?>微站首页导航" href="<?php  echo $ms['home_url'];?>"><i class="fa fa-home"></i></a><?php  } ?>
		<?php  if(is_array($ms['entries']['cover'])) { foreach($ms['entries']['cover'] as $cov) { ?>
			<a class="zmcn_ico_r" rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php  echo $ms['title'];?>-<?php  echo $cov['title'];?>入口设置" href="<?php  echo $cov['url'];?>"><i class="<?php  echo $cov['icon'];?>"></i></a>
		<?php  } } ?>
		</h4>
	</div>
	<div class="shortcut clearfix">
		<?php  if(is_array($ms['menu'])) { foreach($ms['menu'] as $me) { ?>
		<a href="<?php  echo $me['url'];?>" rel="tooltip" data-toggle="tooltip" data-placement="top" title="<?php  echo $me['rem'];?>">
		  	<i class="<?php  echo $me['logo'];?>"></i>
			<span><?php  echo $me['title'];?></span>
		</a>
		<?php  } } ?>
	</div>
<?php  } } ?>
<?php  if($_W['role'] == "founder") { ?>
	<div class="page-header">
		<h4><i class="fa fa-info-circle"></i>  站长专区（公众号用户看不到的） 
		<a class="zmcn_ico_r" href="./index.php?c=extension&amp;a=module&amp;" rel="tooltip" data-original-title="模块管理" data-toggle="tooltip" data-placement="top"><i class="fa fa-cog"></i></a>
		</h4>
	</div>
	<div class="shortcut clearfix">
        <a href="http://s.we7.cc/index.php?c=home&amp;a=author&amp;do=app&amp;uid=74961" target="_black" rel="tooltip" data-original-title="如果你发现有部分功能用不了的，请点这里安装" data-toggle="tooltip" data-placement="top">
      <i class="fa fa-cloud-download"></i>
      <span>应用安装</span></a>
        <a href="http://naotu.baidu.com/file/aa5e51e8980876a1c50b04caf170ad4f?token=346db4897306666d" target="_black" rel="tooltip" data-original-title="功能思维导图" data-toggle="tooltip" data-placement="top">
      <i class="fa fa-code-fork"></i>
      <span>功能一览图</span></a>
        <a href="https://jq.qq.com/?_wv=1027&amp;k=42QEkVK" target="_black" rel="tooltip" data-original-title="QQ群：485696635" data-toggle="tooltip" data-placement="top">
      <i class="fa fa-comments"></i>
      <span>QQ交流群</span></a>
        <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=93211308&amp;site=qq&amp;menu=yes" target="_black" rel="tooltip" data-original-title="服务QQ：93211308" data-toggle="tooltip" data-placement="top">
      <i class="fa fa-qq"></i>
      <span>咨询和服务</span></a>
        <a href="javascript:$('#gzh').modal();" rel="tooltip" data-original-title="演示公众号：掌盟" data-toggle="tooltip" data-placement="top">
      <i class="fa fa-wechat"></i>
      <span>官方演示</span></a>
	</div>
<?php  } ?>
</div>
<div id="gzh"  class="modal fade" tabindex="-1">
	<div class="modal-dialog" style='width:300px;'>
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close two" type="button">×</button><h3>演示公众号：请关注《掌盟》公众号</h3></div>
			<div class="modal-body" >
			<img  src='<?php echo MODULE_URL;?>template/qrcode.jpg' width='200' height='200' class='img-rounded'>
			</div>
			<div class="modal-footer">
			<a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true" id="c2">关闭</a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">require(['bootstrap'],function($){$('[data-toggle="tooltip"]').tooltip();});</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>

























