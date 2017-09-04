<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
		<title>个人中心</title>
		<link type="text/css" rel="stylesheet" href="<?php echo RES;?>js/base.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RES;?>js/style.css" />
		<link href="./resource/css/font-awesome.min.css" rel="stylesheet">
		<script src="../app/resource/js/lib/jquery-1.11.1.min.js"></script>
	</head>
	<body>
		<!--header-->
		<div class="businessCenterHead">
			<span class="business_img">
				<img src="<?php  echo $staff['avatar'];?>" />
			</span>
			<dl>
				<dt>
					<span><?php  echo $staff['realname'];?></span>
				</dt>
				<dd>
					<span><?php  echo $staff['account'];?></span>
					<em><?php  echo $staff['mobile'];?></em>
					<?php  if($creditname) { ?><em style="float: right;color: white;"><?php  echo $creditname;?>：<?php  echo $credit;?></em><?php  } ?>
				</dd>
			</dl>
		</div>
		<div class="businessCenterOuter">
		<?php  if($cfg['notice']) { ?>
			<div class="businessCenterTips">
				<span class="tips_img">
					<img src="<?php echo RES;?>images/notice.png" />
				</span>
				<p>
					<marquee direction="left" scrollamount="5"><?php  echo $cfg['notice'];?></marquee>
				</p>
			</div>
		<?php  } ?>
			<div class="businessCenterTabs">
				<ul>
					<li>
						<a href="<?php  echo $this->createMobileUrl('record',array('type'=>1))?>" class="businessCenterTabs_1">
							<dl>
								<dt><span><?php  echo $today;?></span></dt>
								<dd><span>今日推广</span></dd>
							</dl>
						</a>
					</li>
					<li>
						<a href="<?php  echo $this->createMobileUrl('record')?>" class="businessCenterTabs_2">
							<dl>
								<dt><span><?php  echo $all;?></span></dt>
								<dd><span>总推广数</span></dd>
							</dl>
						</a>
					</li>
					<li>
						<a href="<?php  echo $this->createMobileUrl('rank')?>" class="businessCenterTabs_3">
							<dl>
								<dt><span><img src="<?php echo RES;?>/images/rank.png"></span></dt>
								<dd><span>推广排行</span></dd>
							</dl>
						</a>
					</li>
					<li>
						<a onclick="<?php  if($qrcode) { ?>$('.qrcode_poster').show();<?php  } else if($_W['account']['level']==4) { ?>onQrcode()<?php  } else { ?>$('.qrcode_bg').show();<?php  } ?>" class="businessCenterTabs_4">
							<dl>
								<dt><span><img src="<?php echo RES;?>/images/qrcode.png"></span></dt>
								<dd><span>专属二维码</span></dd>
							</dl>
						</a>
					</li>
					<?php  if(is_array($cfg['btns'])) { foreach($cfg['btns'] as $b) { ?>
					<li>
						<a href="<?php  echo $b['link'];?>" class="businessCenterTabs_4" style="background: <?php  echo $b['color'];?>">
							<dl>
								<dt><span><i class="<?php  echo $b['icon'];?>" style="font-size: 32px;"></i></span></dt>
								<dd><span><?php  echo $b['title'];?></span></dd>
							</dl>
						</a>
					</li>
					<?php  } } ?>
				</ul>
			</div>
		</div>
<?php  if($cfg['copyright']) { ?><div style="text-align: center;font-size: 15px;margin-top: 10px;margin-bottom: 10px;"><?php  echo $cfg['copyright'];?></div><?php  } ?>
<div class="qrcode_bg" onclick="$(this).hide();">
	<div class='qrcode_con'>
	<?php  if($_W['account']['level']==4) { ?>
		<img>
		<p style="text-align: center;font-size: 18px">专属二维码</p>
	<?php  } else { ?>
	<img src="<?php  echo $_W['account']['qrcode'];?>">
	<p style="text-align: center;font-size: 18px">粉丝关注后输入你的<?php  echo $acc_name;?></p>
	<p style="text-align: center;font-size: 18px">【<?php  echo $staff['account'];?>】</p>
	<p style="text-align: center;font-size: 18px">即可完成推广</p>
	<?php  } ?>
	</div>
</div>
<img class="qrcode_poster" onclick="$(this).hide();" src="<?php  echo $qrcode;?>">
<script>
function onQrcode(){
	var img = $('.qrcode_bg .qrcode_con img');
	$.ajax({
		url:"<?php  echo $this->createMobileUrl('getqrcode',array('openid'=>$staff['openid']))?>",
		type:'post',
		success:function(url){
			img.attr('src',url);
			$('.qrcode_bg').show();
		}
	});
}
</script>
	</body>
</html>
