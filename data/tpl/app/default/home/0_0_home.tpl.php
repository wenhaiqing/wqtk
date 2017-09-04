<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
body{
	font:<?php  echo $_W['styles']['fontsize'];?> <?php  echo $_W['styles']['fontfamily'];?>;
	color:<?php  if(empty($_W['styles']['fontcolor'])) { ?>#555<?php  } else { ?><?php  echo $_W['styles']['fontcolor'];?><?php  } ?>;
	padding:0;
	margin:0;
	background-image:url('<?php  if(empty($_W['styles']['indexbgimg'])) { ?>./themes/default/images/bg_index.jpg<?php  } else { ?><?php  echo $_W['styles']['indexbgimg'];?><?php  } ?>');
	background-size:cover;
	background-color:<?php  if(empty($_W['styles']['indexbgcolor'])) { ?>#fbf5df<?php  } else { ?><?php  echo $_W['styles']['indexbgcolor'];?><?php  } ?>;
	<?php  echo $_W['styles']['indexbgextra'];?>
}
a{color:<?php  echo $_W['styles']['linkcolor'];?>; text-decoration:none;}
<?php  echo $_W['styles']['css'];?>
.home-container{width:58%;overflow:hidden;margin:.6em .3em;}
.home-container .box-item{float:left;display:block;text-decoration:none;outline:none;width:5em;height:6em;margin:.1em;background:rgba(0, 0, 0, 0.3);text-align:center;color:#ccc;}
.home-container i{display:block;height:45px; margin: 5px auto; font-size:35px; padding-top:10px; width:45px;}
.home-container span{color:<?php  echo $_W['styles']['fontnavcolor'];?>;display:block; width:90%; margin:0 5%;  overflow:hidden; height:20px; line-height:20px;}
.footer{color:#dddddd;}
.home-container ul li{background-color:rgba(0, 0, 0, 0.3);padding:0 10px;margin:1%;display: inline-block;height:45px;width:100%;}
.home-container ul li a{text-decoration: none;}
.home-container .title{color:#ccc;}
.home-container .createtime{color:#999;font-size:12px}
</style>
<div class="home-container clearfix">
	<?php  $site_navs = modulefunc('site', 'site_navs', array (
  'func' => 'site_navs',
  'item' => 'row',
  'limit' => 10,
  'index' => 'iteration',
  'multiid' => 0,
  'uniacid' => 0,
  'acid' => 0,
)); if(is_array($site_navs)) { $i=0; foreach($site_navs as $i => $row) { $i++; $row['iteration'] = $i; ?>
		<?php  echo $row['html'];?>
	<?php  } } ?>
	
	<!-- 该分类下文章-start -->
	<?php  if($_GPC['c'] == 'site' && $_GPC['a'] == 'site') { ?>
	<ul class="list list-unstyled">
		<?php  $result = modulefunc('site', 'site_article', array (
  'func' => 'site_article',
  'cid' => $cid,
  'return' => 'true',
  'assign' => 'result',
  'limit' => 10,
  'index' => 'iteration',
  'multiid' => 0,
  'uniacid' => 0,
  'acid' => 0,
)); ?>
			<?php  if(is_array($result['list'])) { foreach($result['list'] as $row) { ?>
			<li>
				<a href="<?php  echo $row['linkurl'];?>">
					<div class="title"><?php  echo cutstr($row['title'],10,1);?></div>
					<div class="createtime"><?php  echo date('Y-m-d H:i:s', $row['createtime'])?></div>
				</a>
			</li>
			<?php  } } ?>
	</ul>
	<?php  } ?>
	<!-- 该分类下文章-start -->
</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>