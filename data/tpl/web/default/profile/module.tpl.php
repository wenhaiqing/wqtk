<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
	<div class="module">
			<ul class="thumbnails">
			<?php  if(is_array($modulelist)) { foreach($modulelist as $row) { ?>
			<li style="width:262px;" class="list-unstyled">
				<div class="thumbnail">
					<div class="module-pic">
						<img src="<?php  echo $row['preview'];?>" onerror="this.src='../web/resource/images/nopic.jpg'" <?php  if(!$row['enabled']) { ?>class="gray"<?php  } ?>>
						<div class="module-detail">
							<h5 class="module-title"><?php  echo $row['title'];?></h5>
							<p class="module-brief"><?php  echo $row['ability'];?></p>
							<p class="module-description"><?php  if($row['isrulefields']) { ?><a href="<?php  echo url('platform/reply/post', array('m' => $row['name']))?>" class="text-info">添加规则</a><?php  } ?></p>
						</div>
						<?php  if($row['official']) { ?>
						<span class="official"><img src="resource/images/module/official.png"/></span>
						<?php  } ?>
					</div>
					<div class="module-button">
						<?php  if($row['issystem']) { ?>
							<a href="javascript:;" class="pull-right"><span>此模块为系统模块</span></a>
						<?php  } else { ?>
						<?php  if($row['enabled']) { ?>
							<a id="enabled_<?php  echo $row['mid'];?>_0" href="<?php  echo url('profile/module/enable', array('m' => $row['name'], 'enabled' => 0))?>" onclick="return ajaxopen(this.href)" class="btn btn-primary module-button-switch">禁用</a>
						<?php  } else { ?>
							<a id="enabled_<?php  echo $row['mid'];?>_1" href="<?php  echo url('profile/module/enable', array('m' => $row['name'], 'enabled' => 1))?>" onclick="return ajaxopen(this.href);" class="btn btn-danger module-button-switch">启用</a>
						<?php  } ?>
						<?php  } ?>
						<?php  if($row['enabled'] && !$row['issystem']) { ?>
							<?php  if($row['shortcut']) { ?>
							<a href="<?php  echo url('profile/module/shortcut', array('m' => $row['name'], 'shortcut' => 0))?>" onclick="return ajaxopen(this.href);" class="btn btn-danger">移出快捷操作</a>
							<?php  } else { ?>
							<a href="<?php  echo url('profile/module/shortcut', array('m' => $row['name'], 'shortcut' => 1))?>" onclick="return ajaxopen(this.href);" class="btn btn-default">加入快捷操作</a>
							<?php  } ?>
						<?php  } ?>
					</div>
				</div>
			</li>
			<?php  } } ?>
		</ul>
		<div>
			<?php  echo $pager;?>
		</div>
	</div>
	<script type="text/javascript">
		function toggle_description(id) {
			var container = $('#'+id).parent().parent().parent();
			var status = $('#'+id).attr("status");
			if(status == 1) {
				$('#'+id).attr("status", "0")
				container.find(".module_description").show();
			} else {
				$('#'+id).attr("status", "1")
				container.find(".module_description").hide();
			}
		}
		$('.module .thumbnails').delegate('li .module-button-switch', 'click', function(){ //控制模块开关
			if($(this).hasClass('btn-primary')) { //禁用模块
				$(this).removeClass('btn-primary').addClass('btn-danger').html('开启');
			} else if($(this).hasClass('btn-danger')) { //开启模块
				$(this).removeClass('btn-danger').addClass('btn-primary').html('禁用');
			}
			$(this).parent().parent().find('.module-pic img').toggleClass('gray');
		});
	</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
