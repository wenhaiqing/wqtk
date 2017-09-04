<?php defined('IN_IA') or exit('Access Denied');?><?php  define('MUI', true);?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php  if($op == 'receive_card') { ?>
<style>
	.membership-card-receive .card-panel .card-grade{position: absolute; right: 10px; top: 10px;}
	.membership-card-receive .card-panel .card-logo{left: 10px; top: 10px;}
	.membership-card-receive .card-panel .card-logo img{margin: 0; vertical-align: top;}
	.membership-card-receive .card-panel .card-rank{font-size:20px; padding: 0 10px;}
</style>
<div class="mui-content membership-card-receive">
	<?php  if(!empty($setting['grant']) && ($setting['grant']['credit1'] || $setting['grant']['credit2'] || !empty($coupon))) { ?>
	<div class="mui-bg-warning mui-text-center tips">
		领卡赠送:
		<?php  if($setting['grant']['credit2'] > 0) { ?>
		￥<span class="mui-big"><?php  echo $setting['grant']['credit2'];?></span>
		<?php  } ?>
		<?php  if($setting['grant']['credit1'] > 0) { ?>
		+ <span class="mui-big"><?php  echo $setting['grant']['credit1'];?></span>积分
		<?php  } ?>
		<?php  if(!empty($coupon_title)) { ?>
		卡券:<?php  echo $coupon_title;?>
		<?php  } ?>
	</div>
	<?php  } ?>
	<div class="card-panel" style="background-image: url(
			<?php  if(empty($setting['background']['image'])) { ?>
			<?php  echo tomedia('images/global/card/1.png')?>
			<?php  } else if($setting['background']['background'] == 'system') { ?>
			<?php  echo tomedia('images/global/card/' . $setting['background']['image'] . '.png')?>
			<?php  } else { ?>
			<?php  echo tomedia($setting['background']['image']);?>
			<?php  } ?>
		);">
		<div class="card-logo mui-text-center">
			<?php  if(!empty($setting['logo'])) { ?><img src="<?php  echo tomedia($setting['logo'])?>" class="mui-img-rounded"/><?php  } ?>
		</div>
		<div class="card-grade" style="color:<?php  if(!empty($setting['color']['rank'])) { ?><?php  echo $setting['color']['rank'];?><?php  } ?>"></div>
		<div class="card-info">
			<div class="mui-text-center" style="height:48px">
				<span class="card-rank" style="color:<?php  if(!empty($setting['color']['title'])) { ?><?php  echo $setting['color']['title'];?><?php  } ?>"><?php  if($basic_info['params']['card_label']['type'] == 1) { ?><?php  echo $basic_info['params']['card_label']['title'];?><?php  } ?></span>
			</div>
			<?php  if(!$setting['format_type']) { ?>
			<div class="card-no mui-text-right">会员卡号:<span style="color: #fff;"><?php  echo $setting['format'];?></span></div>
			<?php  } ?>
		</div>
	</div>
	<form class="tab-content clearfix js-card-form" action="<?php  echo $this->createMobileurl('card', array('op' => 'receive_card'))?>" method="post" enctype="multipart/form-data">
		<div class="mui-input-group">
			<div class="mui-input-row">
				<label>
					姓名<span title="必填项" style="color:red">*</span>
				</label>
				<?php  echo tpl_app_fans_form('realname', $member_info['realname'], '姓名');?>
			</div>
			<div class="mui-input-row">
				<label>
					手机<span title="必填项" style="color:red">*</span>
				</label>
				<?php  echo tpl_app_fans_form('mobile', $member_info['mobile'], '手机号码');?>
			</div>
			<?php  if(is_array($setting['fields'])) { foreach($setting['fields'] as $item) { ?>
			<?php  if($item['bind'] != 'realname' &&  $item['bind'] != 'mobile') { ?>
			<div class="mui-input-row">
				<label>
					<?php  echo $item['title'];?> <?php  if($item['require'] == 1) { ?><span title="必填项" style="color:red">*</span><?php  } else { ?> &nbsp;<?php  } ?>
				</label>
				<?php  if($item['bind'] == 'reside' || $item['bind'] == 'resideprovince' || $item['bind'] == 'residecity' || $item['bind'] == 'residedist') { ?>
				<?php  echo tpl_app_fans_form('reside', array('province' => $member_info['resideprovince'],'city' => $member_info['residecity'],'district' => $member_info['residedist']));?>
				<?php  } else if($item['bind'] == 'birth' || $item['bind'] == 'birthyear' || $item['bind'] == 'birthmonth' || $item['bind'] == 'birthday') { ?>
				<?php  echo tpl_app_fans_form('birth', array('year' => $member_info['birthyear'], 'month' => $member_info['birthmonth'], 'day' => $member_info['birthday']))?>
				<?php  } else { ?>
				<?php  echo tpl_app_fans_form($item['bind'],$member_info[$item['bind']],$item['title'])?>
				<?php  } ?>
			</div>
			<?php  } ?>
			<?php  } } ?>
		</div>
		<div class="mui-content-padded">
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		<button class="mui-btn mui-btn-success mui-btn-block" id="receive-card" type="submit" name="submit" value="提交">领取</button>
	</div>
	</form>
</div>
<script type="text/javascript">
	$('.js-card-form').submit(function(event) {
		$(this).data('submit', 1);
		$('button[type="submit"]').prop('disabled', true);
		$.ajax(this.action, {
			method : this.method.toUpperCase(),
			data : new FormData(this),
			cache : false,
			processData : false,
			contentType : false,
			dataType : 'json'
		}).done(function(result) {
			$(this).data('submit', 0);
			if (result.type == 'success') {
				location.href = result.redirect;
			} else {
				util.toast(result.message, '', 'error');
				$('button[type="submit"]').prop('disabled', false);
				util.toast(result.message, result.redirect, 'error');
			}
			
		}).fail(function(res) {
			$(this).data('submit', 0);
			util.toast('操作失败，请重试', '', 'error');
		});
		event.preventDefault();
		return false;
	});
</script>
<?php  } ?>
<!-- 我的会员卡 -->
<?php  if($op == 'mycard') { ?>
<style>
	.back pre{
		display: block;
		font-size: 13px;
		line-height: 1.42857143;
		color: #333;
		word-break: break-all;
		word-wrap: break-word;
		border-radius: 4px;
		padding: 0;
		margin: 0;
		border: 0;
		background: 0 0;
		white-space: pre-line;
		height: 84px;
		overflow: hidden;
	}
	.membership-card-home .card-panel.prev .card-grade{position: absolute; right: 10px; top: 10px;}
	.membership-card-home .card-panel.prev .card-logo{left: 10px; top: 10px;}
	.membership-card-home .card-panel.prev .card-logo img{margin: 0; vertical-align: top;}
	.membership-card-home .card-panel.prev .card-rank{font-size:20px; padding: 0 10px;}
</style>
<?php  if(empty($mcard['status'])) { ?>
<div class="alert alert-warning" role="alert">
	您的会员卡已被禁用，如有疑问，请联系<?php  echo $_W['account']['name'];?>。
</div>
<?php  } else { ?>
<div class="mui-content membership-card-home mc-we7-home" style="padding-bottom:55px;">
	<div class="card-panel prev" onclick="$(this).hide();$('.back').show()" style="background-image:url(
			<?php  if(empty($setting['background']['image'])) { ?>
			<?php  echo tomedia('images/global/card/1.png')?>
			<?php  } else if($setting['background']['background'] == 'system') { ?>
			<?php  echo tomedia('images/global/card/' . $setting['background']['image'] . '.png')?>
			<?php  } else { ?>
			<?php  echo tomedia($setting['background']['image']);?>
			<?php  } ?>
		)">
		<a href="javascript:;">
			<div class="card-logo mui-text-center">
				<?php  if(!empty($setting['logo'])) { ?><img src="<?php  echo tomedia($setting['logo'])?>" class="mui-img-rounded"/><?php  } ?>
			</div>
			<div class="card-grade" style="color:<?php  if(!empty($setting['color']['rank'])) { ?><?php  echo $setting['color']['rank'];?><?php  } ?>"><?php  if($basic_info['params']['card_level']['type'] == 1) { ?><?php  echo $_W['member']['groupname'];?><?php  } ?></div>
			<div class="card-info">
				<div class="mui-text-center" style="height:48px">
					<span class="card-rank" style="color:<?php  if(!empty($setting['color']['title'])) { ?><?php  echo $setting['color']['title'];?><?php  } ?>"><?php  if($basic_info['params']['card_label']['type'] == 1) { ?><?php  echo $basic_info['params']['card_label']['title'];?><?php  } ?></span>
				</div>
				<div class="card-no mui-text-right">会员卡号:<span><?php  echo $mcard['cardsn'];?></span></div>
			</div>
		</a>
	</div>
	<div class="card-panel back" style="display:none;background-image:url(
			<?php  if(empty($setting['background']['image'])) { ?>
			<?php  echo tomedia('images/global/card/1.png')?>
			<?php  } else if($setting['background']['background'] == 'system') { ?>
			<?php  echo tomedia('images/global/card/' . $setting['background']['image'] . '.png')?>
			<?php  } else { ?>
			<?php  echo tomedia($setting['background']['image']);?>
			<?php  } ?>
		)" onclick="$(this).hide();$('.prev').show()">
			<span>
				<h3 style="font-size:14px;font-weight:100;margin:10px 0;">使用说明：</h3>
				<pre>
				<?php  if(empty($setting['description'])) { ?>
				1、本卡采取记名消费方式
				2、持卡人可享受会员专属优惠
				3、本卡不能与其他优惠活动同时使用
				4、持卡人可用卡内余额进行消费
				<?php  } else { ?>
				<?php  echo $setting['description'];?>
				<?php  } ?>
				</pre>
			</span>
	</div>
	<div class="mui-table mui-table-inline mui-mt15 nav-action">
		<div class="mui-table-cell">
			<a href="<?php  echo url('entry', array('m' => 'recharge', 'do' => 'pay'));?>" class="mui-block">
				<img src="resource/images/sum-recharge.png" alt="" class="mui-mr5"/>充值
			</a>
		</div>
		<div class="mui-table-cell">
			<a href="<?php  echo $this->createMobileurl('card', array('op' => 'consume')) . '&wxref=mp.weixin.qq.com#wechat_redirect'?>">
				<img src="resource/images/scan-pay.png" alt=""/>付款
			</a>
		</div>
	</div>
	<ul class="mui-table-view mui-table-view-chevron">
		<li class="mui-table-view-cell">
			<a href="<?php  echo url('entry', array('m' => 'recharge', 'do' => 'pay'));?>" class="mui-navigate-right">
				我的余额
				<span class="mui-pull-right"><?php  echo $_W['member']['credit2'];?></span>
			</a>
		</li>
		<li class="mui-table-view-cell">
			<a href="<?php  echo $this->createMobileurl('creditrecord', array('credittype' => 'credit1', 'type' => 'record', 'period' => '1'))?>" class="mui-navigate-right">
				我的积分
				<span class="mui-pull-right"><?php  echo $_W['member']['credit1'];?></span>
			</a>
		</li>
		<?php  if($setting['nums_status'] == 1) { ?>
		<li class="mui-table-view-cell">
			<a href="<?php  echo $this->createMobileurl('card', array('op' => 'recharge_record', 'type' => 'nums'))?>" class="mui-navigate-right">
				<?php  echo $setting['nums_text'];?>
				<span class="mui-pull-right"><?php  echo $mcard['nums'];?>次</span>
			</a>
		</li>
		<?php  } ?>
		<?php  if($setting['times_status'] == 1) { ?>
		<li class="mui-table-view-cell">
			<a href="<?php  echo $this->createMobileurl('card', array('op' => 'recharge_record', 'type' => 'times'))?>" class="mui-navigate-right">
				<?php  echo $setting['times_text'];?>
				<span class="mui-pull-right"><?php  if($mcard['endtime'] < time()) { ?>已过期<?php  } else { ?><?php  echo date('Y-m-d', $mcard['endtime']);?><?php  } ?></span>
			</a>
		</li>
		<?php  } ?>
		<li class="mui-table-view-cell">
			<a href="<?php  echo $this->createMobileurl('activity', array('op' => 'mine', 'type' => 'coupon'))?>" class="mui-navigate-right">
				我的卡券
				<span class="mui-pull-right"><?php  echo $total;?>张</span>
			</a>
		</li>
	</ul>
	<ul class="mui-table-view mui-table-view-chevron">
		<?php  if(!empty($setting['sign_status'])) { ?>
		<li class="mui-table-view-cell">
			<a href="<?php  echo $this->createMobileurl('card', array('op' => 'sign_display')) . '&wxref=mp.weixin.qq.com#wechat_redirect'?>" class="mui-navigate-right">
				签到
			</a>
		</li>
		<?php  } ?>
		<li class="mui-table-view-cell">
			<a href="<?php  echo $this->createMobileurl('card', array('op' => 'notice')) . '&wxref=mp.weixin.qq.com#wechat_redirect'?>" class="mui-navigate-right">
				消息
				<span class="mui-pull-right"><span class="mui-badge <?php  if($new_notice_total > 0) { ?>mui-badge-primary<?php  } ?>"><?php  echo $new_notice_total;?></span></span>
			</a>
		</li>
	</ul>
	<ul class="mui-table-view mui-table-view-chevron">
		<li class="mui-table-view-cell">
			<a href="<?php  echo $this->createMobileurl('card', array('op' => 'personal_info')) . '&wxref=mp.weixin.qq.com#wechat_redirect';?>" class="mui-navigate-right">
				个人信息
			</a>
		</li>
		<li class="mui-table-view-cell">
			<a href="<?php  echo $this->createMobileurl('creditrecord', array('credittype' => 'credit2', 'type' => 'record', 'period' => '1'))?>" class="mui-navigate-right">
				账单
			</a>
		</li>
	</ul>
	<?php  if(!empty($activity_description_show)) { ?>
	<ul class="mui-table-view mui-table-view-chevron">
		<li class="mui-table-view-cell">
			<a href="<?php  echo $this->createMobileurl('card', array('op' => 'activity_description')) . '&wxref=mp.weixin.qq.com#wechat_redirect';?>" class="mui-navigate-right">
				优惠说明
			</a>
		</li>
	</ul>
	<?php  } ?>
</div>
<?php  } ?>
<?php  } ?>

<?php  if($op == 'activity_description') { ?>
<div class="mui-content">
	<ul class="mui-table-view mui-table-view-chevron">
		<?php  if($activity_info['params']['discount_type'] != 0) { ?>
		<li class="mui-table-view-cell js-activity-descrpition">
			消费优惠信息
			<span class="mui-pull-right">
				<span class="fa fa-angle-down">
			</span>
		</li>
		<div style="display:none;">
			<ul class="mui-table-view mui-table-view-chevron">
				<?php  if(is_array($activity_description)) { foreach($activity_description as $description) { ?>
				<li class="mui-table-view-cell mui-content-padded">
					<?php  echo $description['0'];?>
					<span style="margin-left:50px;" class="mui-text-center"><?php  echo $description['1'];?></span>
				</li>
				<?php  } } ?>
			</ul>
			<?php  if($activity_info['params']['discount_style'] == 2) { ?>
			<div style="background-color:<?php  echo $activity_info['params']['bgColor'];?>"><?php  echo $activity_info['params']['content'];?></div>
			<?php  } ?>
		</div>
		<?php  } ?>
		<?php  if($recharge_info['params']['recharge_type'] != 0) { ?>
		<li class="mui-table-view-cell js-activity-descrpition">
			充值优惠信息
			<span class="mui-pull-right">
				<span class="fa fa-angle-down">
			</span>
		</li>
		<div style="display:none;">
			<ul class="mui-table-view mui-table-view-chevron">
				<?php  if(is_array($recharge_description)) { foreach($recharge_description as $description) { ?>
				<li class="mui-table-view-cell mui-content-padded mui-text-center">
					<span style="margin-left:50px;">
					<?php  echo $description;?>
					</span>
				</li>
				<?php  } } ?>
			</ul>
		</div>
		<?php  } ?>
		<?php  if($nums_info['params']['nums_status'] != 0) { ?>
		<li class="mui-table-view-cell js-activity-descrpition">
			<?php  echo $nums_info['params']['nums_text'];?>信息
			<span class="mui-pull-right">
				<span class="fa fa-angle-down">
			</span>
		</li>
		<div style="display:none;">
			<ul class="mui-table-view mui-table-view-chevron">
				<?php  if(is_array($nums_description)) { foreach($nums_description as $description) { ?>
				<li class="mui-table-view-cell mui-content-padded mui-text-center">
					<span>
					<?php  echo $description;?>
					</span>
				</li>
				<?php  } } ?>
			</ul>
			<?php  if($nums_info['params']['nums_style'] == 2) { ?>
			<div style="background-color:<?php  echo $nums_info['params']['bgColor'];?>"><?php  echo $nums_info['params']['content'];?></div>
			<?php  } ?>
		</div>
		<?php  } ?>
		<?php  if($times_info['params']['times_status'] != 0) { ?>
		<li class="mui-table-view-cell js-activity-descrpition">
			<?php  echo $times_info['params']['times_text'];?>信息
			<span class="mui-pull-right">
				<span class="fa fa-angle-down">
			</span>
		</li>
		<div style="display:none;">
			<ul class="mui-table-view mui-table-view-chevron">
				<?php  if(is_array($times_description)) { foreach($times_description as $description) { ?>
				<li class="mui-table-view-cell mui-content-padded mui-text-center">
					<span>
					<?php  echo $description;?>
					</span>
				</li>
				<?php  } } ?>
			</ul>
			<?php  if($times_info['params']['times_style'] == 2) { ?>
			<div style="background-color:<?php  echo $times_info['params']['bgColor'];?>"><?php  echo $times_info['params']['content'];?></div>
			<?php  } ?>
		</div>
		<?php  } ?>
	</ul>
</div>
<script>
	$('.js-activity-descrpition').click(function() {
		$(this).next().toggle();
		$(this).find('span.fa').toggleClass('fa-angle-up');
		$(this).find('span.fa').toggleClass('fa-angle-down');
	})
</script>
<?php  } ?>

<?php  if($op == 'add_recharge') { ?>
<div class="mui-content membership-card-add-times">
	<div class="mui-section mui-text-center">
		请选择您要增加的<?php  if($type == 'nums') { ?>次数<?php  } else if($type == 'times') { ?>时间<?php  } ?>
	</div>
	<div class="mui-row mui-mr5 mui-ml5">
		<?php  $i = 0;?>
		<?php  if(is_array($setting)) { foreach($setting as $nums) { ?>
		<div class="mui-col-xs-4 mui-pa5 mui-mt5">
			<div class="mui-thumbnail mui-text-center mui-text-info <?php  if($i == '0') { ?>selected<?php  } ?>" data-nums="<?php  if($type == 'nums') { ?><?php  echo $nums['num'];?><?php  } else if($type == 'times') { ?><?php  echo $nums['time'];?><?php  } ?>" data-recharge="<?php  echo $nums['recharge'];?>">
				<div class="mui-big"><?php  if($type == 'nums') { ?><?php  echo $nums['num'] . '次'?><?php  } else if($type == 'times') { ?><?php  echo $nums['time'] . '天'?><?php  } ?></div>
				<div class="mui-small"><?php  echo $nums['recharge'];?>元</div>
				<div class="selected-status"></div>
			</div>
		</div>
		<?php  $i++;?>
		<?php  } } ?>
	</div>
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			实际增加的<?php  if($type == 'nums') { ?>次数<?php  } else if($type == 'times') { ?>时间<?php  } ?> <span class="mui-pull-right add-nums"></span>
		</li>
		<li class="mui-table-view-cell">
			需要支付的金额 <span class="mui-pull-right mui-text-success mui-big add-pay"></span>
		</li>
	</ul>
	<div class="mui-content-padded">
		<a class="mui-btn mui-btn-success mui-btn-block dopay" href="javascript:;">确认支付</a>
	</div>
</div>
<script>
	$(function(){
		$('.mui-thumbnail').click(function(){
			$('.mui-thumbnail').removeClass('selected');
			$(this).addClass('selected');
			var type = '<?php  echo $type;?>';
			var addnums = $(this).data('nums');
			var addpay = $(this).data('recharge');
			var status = '<div class="selected-status"></div>';

			if (type == 'nums') {
				$('.add-nums').text(addnums + '次');
				url = "<?php  echo url('entry', array('m' => 'recharge', 'do' => 'pay', 'type' => 'card_nums'), true)?>&fee=" + addpay;
			} else if(type == 'times') {
				$('.add-nums').text(addnums + '天');
				url = "<?php  echo url('entry', array('m' => 'recharge', 'do' => 'pay', 'type' => 'card_times'), true)?>&fee=" + addpay;
			}
			$('.add-pay').text(addpay + '元');
			$('.dopay').attr('href', url);
		})
		$('.mui-thumbnail:first').click();
	})
</script>
<?php  } ?>

<?php  if($op == 'recharge_record') { ?>
<!-- 查看剩余次数或天数 -->
<div class="mui-content membership-card-times">
	<div class="mui-section">
		<div class="mui-text-success mui-text-center times"><?php  if($type == 'nums') { ?><?php  echo $card['nums'];?><?php  } else if($type == 'times') { ?><?php  echo date('Y-m-d', $card['endtime'])?><?php  } ?></div>
		<div class="mui-text-center mui-text-muted"><?php  if($type == 'nums') { ?><?php  echo $setting['nums_text'];?><?php  } else if($type == 'times') { ?><?php  echo $setting['times_text'];?><?php  } ?></div>
		<?php  if($type == 'nums') { ?>
		<a class="mui-btn mui-btn-success mui-btn-outlined mui-btn-block" href="<?php  echo $this->createMobileurl('card', array('op' => 'add_recharge', 'type' => 'nums'));?>">立即充值</a>
		<?php  } else if($type == 'times') { ?>
		<a class="mui-btn mui-btn-success mui-btn-outlined mui-btn-block" href="<?php  echo $this->createMobileurl('card', array('op' => 'add_recharge', 'type' => 'times'));?>">立即充值</a>
		<?php  } ?>
	</div>
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			<div class="mui-row">
				<div class="mui-col-xs-3">
					<a href="#times-date">
						<span class="date-filter"><?php  echo $period_date;?></span>
						<span class="mui-text-muted"><i class="fa fa-angle-down"></i></span>
					</a>
				</div>
			</div>
		</li>
		<?php  if(is_array($data)) { foreach($data as $da) { ?>
		<li class="mui-table-view-cell">
			<div class="mui-row">
				<div class="mui-col-xs-6">
					<?php  if($type == 'nums') { ?>
					<?php  if($da['model'] == '1') { ?>
					次数充值
					<?php  } else { ?>
					次数消费
					<?php  } ?>
					<?php  } else { ?>
					<?php  if($da['model'] == '1') { ?>
					服务延长
					<?php  } else { ?>
					服务减少
					<?php  } ?>
					<?php  } ?>
					<span class="mui-block mui-text-muted mui-small"> <span class="mui-ml5 mui-rmb"><?php  echo $da['fee'];?></span></span>
				</div>
				<div class="mui-col-xs-6 mui-text-right">
					<span class="mui-big"><?php  if($da['model'] == '1') { ?>+<?php  } else { ?>-<?php  } ?><?php  echo $da['tag'];?><?php  if($type == 'nums') { ?>次<?php  } else if($type == 'times') { ?>天<?php  } ?></span>
					<span class="mui-block mui-text-muted mui-small"><?php  echo date('Y-m-d', $da['addtime'])?></span>
				</div>
			</div>
		</li>
		<?php  } } ?>
	</ul>
	<div id="times-date" class="mui-popover mui-popover-top">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell">
				<a href="<?php  echo $this->createMobileurl('card', array('op' => 'recharge_record', 'period' => '1', 'type' => $type));?>"><?php  echo date('Y.m', strtotime('now'))?></a>
			</li>
			<li class="mui-table-view-cell">
				<a href="<?php  echo $this->createMobileurl('card', array('op' => 'recharge_record', 'period' => '-1', 'type' => $type));?>"><?php  echo date('Y.m', strtotime('now - 1 month'))?></a>
			</li>
			<li class="mui-table-view-cell">
				<a href="<?php  echo $this->createMobileurl('card', array('op' => 'recharge_record', 'period' => '-2', 'type' => $type));?>"><?php  echo date('Y.m', strtotime('now - 2 month'))?></a>
			</li>
			<li class="mui-table-view-cell">
				<a href="<?php  echo $this->createMobileurl('card', array('op' => 'recharge_record', 'period' => '-3', 'type' => $type));?>"><?php  echo date('Y.m', strtotime('now - 3 month'))?></a>
			</li>
			<li class="mui-table-view-cell">
				<a href="<?php  echo $this->createMobileurl('card', array('op' => 'recharge_record', 'period' => '-4', 'type' => $type));?>"><?php  echo date('Y.m', strtotime('now - 4 month'))?></a>
			</li>
		</ul>
	</div>
</div>

<?php  } ?>

<?php  if($op == 'sign_display') { ?>
<!--签到界面-->
<div class="mui-content membership-card-sign mui-text-center">
	<div class="mui-table mui-table-inline nav-action">
		<div class="mui-table-cell">
			<a href="<?php  echo $this->createMobileurl('card', array('op' => 'sign_record', 'period' => '1'))?>" class="mui-block">
				<img src="resource/images/icon-sign.png" alt="" />
				签到记录
			</a>
		</div>
		<div class="mui-table-cell">
			<a href="<?php  echo $this->createMobileurl('card', array('op' => 'sign_strategy'))?>" class="mui-block">
				<img src="resource/images/icon-integral-strategy.png" alt="" />
				积分攻略
			</a>
		</div>
	</div>
	<div class="sign-table">
		<div class="mui-bg-white sign-table-con mui-clearfix">
			<?php  for ($i = 1; $i <= $current_month_days; $i++) {?>
			<?php  if($i == $sign_set['first_group_day']) { ?>
			<div class="sign-table-cell <?php  if($i <= $total) { ?>active<?php  } ?>">
				<span><img src="resource/images/icon-signed-5.png" alt="" class="<?php  if($i > $total) { ?>gray-img<?php  } ?> signed-5"/></span>
				+<?php  echo $sign_set['first_group_num'];?>
			</div>
			<?php  } else if($i == $sign_set['second_group_day']) { ?>
			<div class="sign-table-cell <?php  if($i <= $total) { ?>active<?php  } ?>">
				<span><img src="resource/images/icon-signed-5.png" alt="" class="<?php  if($i > $total) { ?>gray-img<?php  } ?> signed-5"/></span>
				+<?php  echo $sign_set['second_group_num'];?>
			</div>
			<?php  } else if($i == $sign_set['third_group_day']) { ?>
			<div class="sign-table-cell <?php  if($i <= $total) { ?>active<?php  } ?>">
				<span><img src="resource/images/icon-signed-5.png" alt="" class="<?php  if($i > $total) { ?>gray-img<?php  } ?> signed-5"/></span>
				+<?php  echo $sign_set['third_group_num'];?>
			</div>
			<?php  } else if($i == $current_month_days) { ?>
			<div class="sign-table-cell <?php  if($i <= $total) { ?>active<?php  } ?>">
				<span><img src="resource/images/icon-signed-5.png" alt="" class="<?php  if($i > $total) { ?>gray-img<?php  } ?> signed-5"/></span>
				+<?php  echo $sign_set['full_sign_num'];?>
			</div>
			<?php  } else { ?>
			<div class="sign-table-cell <?php  if($i <= $total) { ?>active<?php  } ?>">
				<span><img src="resource/images/icon-signed.png" alt="" class="<?php  if($i > $total) { ?>gray-img<?php  } ?>"/></span>
				+<?php  echo $sign_set['everydaynum'];?>
			</div>
			<?php  } ?>
			<?php  }?>
		</div>
	</div>
	<?php  if(!empty($today_signed['id'])) { ?>
	<div class="sign-record">今日已签到<span class="mui-big">+<?php  echo $today_sign_credit;?></span>积分</div>
	<div class="mui-text-muted sign-tips mui-mt10">明日可得 <span>+<?php  echo $tomorrow_sign_credit;?></span>积分</div>
	<?php  } ?>
</div>
<script>
	$(function() {
		today_signed = "<?php  echo $today_signed['id'];?>";
		if (!today_signed) {
			util.toast('签到成功');
			setTimeout(function(){
				location.reload();
			},2500);
		}
	})
</script>
<?php  } ?>

<?php  if($op == 'sign_record') { ?>
<!--签到记录-->
<div class="mui-content">
	<div class="mui-content-padded">
		<a href="#sign-date"  class="mui-text-muted">
			<span><?php  if($_GPC['period'] <= 0) { ?><?php  echo date('Y.m', strtotime($_GPC['period'] . 'month'))?><?php  } else { ?>查看全部<?php  } ?></span>
			<span><i class="fa fa-angle-down"></i></span>
		</a>
	</div>
	<div class="credits-display">
		<ul class="mui-table-view js-card-sign">
			<?php  if(!empty($data)) { ?>
			<?php  if(is_array($data)) { foreach($data as $da) { ?>
			<li class="mui-table-view-cell">
				<div class="mui-row">
					<div class="mui-col-xs-6">
						<?php  echo $da['addtime'];?>
						<span class="mui-block mui-text-muted mui-small">签到送积分</span>
					</div>
					<div class="mui-col-xs-6 mui-text-right mui-big">
						+<?php  echo $da['credit'];?>
					</div>
				</div>
			</li>
			<?php  } } ?>
			<?php  } else { ?>
			<li class="mui-table-view-cell">
				<div class="mui-row">无签到记录</div>
			</li>
			<?php  } ?>
		</ul>
	</div>
	<div id="sign-date" class="mui-popover mui-popover-top">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell">
				<a href="<?php  echo $this->createMobileurl('card', array('op' => 'sign_record', 'period' => '1'))?>">查看全部</a>
			</li>
			<li class="mui-table-view-cell">
				<a href="<?php  echo $this->createMobileurl('card', array('op' => 'sign_record', 'period' => '0'))?>"><?php  echo date('Y.m', strtotime('today'))?></a>
			</li>
			<li class="mui-table-view-cell">
				<a href="<?php  echo $this->createMobileurl('card', array('op' => 'sign_record', 'period' => '-1'))?>"><?php  echo date('Y.m', strtotime('-1month'))?></a>
			</li>
			<li class="mui-table-view-cell">
				<a href="<?php  echo $this->createMobileurl('card', array('op' => 'sign_record', 'period' => '-2'))?>"><?php  echo date('Y.m', strtotime('-2month'))?></a>
			</li>
		</ul>
	</div>
</div>
<script>
	require(['mui.pullrefresh'], function(mui) {
		mui.init();
		mui.ready(function() {
			var page = 2;
			var pagetotal = <?php  echo $pagenums;?> + 1;
			if (page < pagetotal) {
				//循环初始化所有下拉刷新，上拉加载。
				mui.each(document.querySelectorAll('.credits-display'), function(index, pullRefreshEl) {
					mui(pullRefreshEl).pullToRefresh({
						up: {
							callback: function() {
								var self = this;
								setTimeout(function() {
									$('.mui-pull-bottom-tips').hide();
									var ul = self.element.querySelector('.js-card-sign');
									ul.appendChild(createFragment(ul, index, 5));
									if (pagetotal <= page) {
										$('.mui-pull-bottom-tips').hide();
										self.endPullUpToRefresh(true);
									} else {
										self.endPullUpToRefresh(false);
									}
								}, 1000);
							}
						}
					});
				});

				var createFragment = function(ul, index, count, reverse) {
					var length = ul.querySelectorAll('li').length;
					var fragment = document.createDocumentFragment();
					var li;
					var url = "<?php  echo $this->createMobileurl('card', array('op' => 'sign_record', 'period' => $_GPC['period']), true)?>";
					mui.post(url, {'page' : page}, function(data){
						data = $.parseJSON(data);
						if (data.message.errno == '1') {
							return false;
						}
						for (var i in data.message.message) {
							li = document.createElement('li');
							li.className = 'mui-table-view-cell';
							li.innerHTML = '<div class="mui-row"><div class="mui-col-xs-6">' + data.message.message[i].addtime + '<span class="mui-block mui-text-muted mui-small">签到送积分</span></div><div class="mui-col-xs-6 mui-text-right mui-big">+' +data.message.message[i].credit + '</div></div>';
							ul.appendChild(li, ul.firstChild);
						}
						$('.mui-pull-bottom-tips').show();
					});
					page++;
					return fragment;
				};
			}
		});
	});
</script>
<?php  } ?>

<?php  if($op == 'sign_strategy') { ?>
<div class="mui-content membership-card-integral-strategy">
	<div class="mui-big mui-text-info mui-text-center mui-mt15">积分攻略</div>
	<div class="mui-content-padded">
		<?php  echo $content;?>
	</div>
</div>
<?php  } ?>

<?php  if($op == 'notice') { ?>
<div class="mui-content">
	<div class="mui-content-padded">
		<div id="segmentedControl" class="mui-segmented-control mui-segmented-control-info">
			<a class="mui-control-item mui-active" href="#broadcast">广播</a>
			<a class="mui-control-item" href="#system-message">系统消息</a>
		</div>
	</div>
	<div id="broadcast" class="mui-control-content mui-active">
		<ul class="mui-table-view mui-table-view-chevron">
			<?php  if(!empty($data)) { ?>
			<?php  if(is_array($data)) { foreach($data as $da) { ?>
			<?php  if($da['type'] == '1') { ?>
			<li class="mui-table-view-cell mui-media js-read" data-isnew="<?php  echo $da['is_new'];?>" data-id="<?php  echo $da['id'];?>">
				<a href="javascript:;" class="mui-navigate-right">
					<img class="mui-media-object mui-pull-left" src="<?php  echo tomedia($da['thumb']);?>">
					<div class="mui-media-body">
						<p class="mui-ellipsis"><?php  echo $da['title'];?></p>
						<div class="mui-small mui-text-muted"><?php  echo date('Y-m-d H:i', $da['addtime']);?></div>
					</div>
				</a>
				<div style="display:none;" class="js-content">
					<p>
						<?php  echo $da['content'];?>
					</p>
				</div>
			</li>
			<?php  } ?>
			<?php  } } ?>
			<?php  } else { ?>
			<li class="mui-table-view-cell mui-media"><i class="fa fa-info-circle"></i> 暂无消息</li>
			<?php  } ?>
		</ul>
	</div>
	<div id="system-message" class="mui-control-content">
		<ul class="mui-table-view mui-table-view-chevron">
			<li class="mui-table-view-cell mui-media">暂无消息</li>
		</ul>
	</div>
</div>
<script>
	$(document).on('click', '.mui-control-item', function() {
		$('.mui-backdrop').remove();
	})
	$('.js-read').click(function(){
		$(this).find('.js-content').toggle();
		//设置为已读
		if($(this).data('isnew')) {
			var id = $(this).data('id');
			$.post("<?php  echo $this->createMobileurl('card', array('op' => 'notice'))?>", {'id':id}, function(data){
				if(data != 0) {
					$('.nav-group .notice-count em').html(data);
				} else {
					$('.nav-group .notice-count em').remove();
				}
			});
			return false;
		}
	});
</script>
<?php  } ?>

<?php  if($op == 'personal_info') { ?>
<form class="tab-content clearfix js-ajax-form <?php  if($_W['container'] !== 'wechat') { ?>profile-form<?php  } ?>" action="<?php  echo url('mc/profile/editprofile');?>" method="post" enctype="multipart/form-data">
	<div class="mui-content">
		<ul class="mui-table-view mui-table-view-chevron">
			<li class="mui-table-view-cell">
				<a href="<?php  echo url('mc/bond/mobile', array('op' => 'index'))?>" class="mui-navigate-right">
					手机
					<span class="mui-pull-right"><span class="mui-mr10"><?php  if(empty($_W['member']['mobile'])) { ?>请绑定手机号<?php  } else { ?><?php  echo $_W['member']['mobile'];?><?php  } ?></span></span>
				</a>
			</li>
		</ul>
		<div class="mui-input-group mui-mt15">
			<?php  if(is_array($mc_fields)) { foreach($mc_fields as $fields) { ?>
			<div class="mui-input-row">
				<label><?php  echo $fields['title']?></label>
				<?php  if($fields['bind'] == 'birth') { ?>
				<?php  echo tpl_app_fans_form('birth', array('year' => $profile['birthyear'], 'month' => $profile['birthmonth'], 'day' => $profile['birthday']), $fields['title']);?>
				<?php  } else if($fields['bind'] == 'reside') { ?>
				<?php  echo tpl_app_fans_form('reside', array('province' => $profile['resideprovince'], 'city' => $profile['residecity'], 'district' => $profile['residedist']), $fields['title']);?>
				<?php  } else { ?>
				<?php  echo tpl_app_fans_form($fields['bind'], $profile[$fields['bind']], $fields['title']);?>
				<?php  } ?>
			</div>
			<?php  } } ?>
		</div>
		<div class="mui-content-padded">
			<div class="mui-text-center mui-mt15 mui-mb15">
				开卡时间:<?php  echo date('Y-m-d', $mcard['createtime']);?>
			</div>
			<button class="mui-btn mui-btn-success mui-btn-block" type="submit" value="提交" name="submit">保存</button>
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</div>
</form>
<?php  } ?>

<?php  if($op == 'consume') { ?>
<div class="mui-content scan-pay">
	<form action="<?php  echo $this->createMobileurl('card', array('op' => 'consume'))?>" method="post" id="pay-form">
		<div class="mui-pa10 mui-bg-white">
			<h5 class="mui-desc-title">请选择门店</h5>
			<div class="mui-input-row">
				 <input class="js-user-options" type="text" placeholder="请选择门店">
				 <input type="hidden" name="store_id">
			</div>
			<h5 class="mui-desc-title">设置付款金额</h5>
			<div class="mui-input-row"><input type="text" value="" name="fee" placeholder="输入金额"/></div>
			<button class="mui-btn mui-btn-success mui-btn-block">付款</button>
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
			<input type="hidden" name="submit" value="<?php  echo $_W['token'];?>"/>
		</div>
	</form>
</div>
<script>
$(function() {
	stores = <?php  echo json_encode($stores_data)?>;
	$('#pay-form').submit(function(){
		var fee = $.trim($('#pay-form :text[name="fee"]').val());
		var store_id = $('input[name="store_id"]').val();
		reg = /^[0-9]+([.]{1}[0-9]{1,2})?$/;
		if (stores.data.length >= 1) {
			if (!store_id) {
				util.toast('请选择门店', '', 'error');
				return false;
			}
		}
		if(!fee) {
			util.toast('付款金额应大于0', '', 'error');
			return false;
		}
		if (!reg.test(fee)) {
			$('#pay-form :text[name="fee"]').val('');
			util.toast('付款金额有误', '', 'error')
			return false;
		}
		return true;
	});
	$(".js-user-options").on("tap", function(){
		if (stores.data.length == 0) {
			util.toast('当前无可用门店');
			return false;
		}
		var $this = $(this);
		util.poppicker(stores, function(items){
			$('input[name="store_id"]').val(items[0].value);
			$this.val(items[0].text);
		});
	});
});
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>