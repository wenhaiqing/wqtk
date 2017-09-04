<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>注册</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<link type="text/css" rel="stylesheet" href="<?php echo RES;?>js/style.css?v=<?php echo TIMESTAMP;?>" />
<link type="text/css" rel="stylesheet" href="<?php echo RES;?>js/weui.css" />
<script src="../app/resource/js/lib/jquery-1.11.1.min.js"></script>
</head>
<body>
<?php  if(empty($fans['follow'])) { ?>
<div id="subscribe">
	<div class='sub_bg'>
		<div class="sub_step">第一步：长按二维码并识别</div>
		<p>请长按下图并选择</p>
		<p>识别图中二维码参与活动</p>
		<img src="<?php  echo $_W['account']['qrcode'];?>">
		<?php  if($cfg['describeurl']) { ?>
		<p>无法识别二维码请点击下面按钮参与活动</p>
		<a href="<?php  echo $cfg['describeurl']?>">立即关注</a>
		<?php  } ?>
		<div class="sub_step">第二步：进入公众号聊天框</div>
		<p>请输入关键字参与</p>
		<div>【<font><?php  echo $keyword;?></font>】</div>
	</div>
</div>
<?php  } ?>
<div class="container js_container" style="overflow-x: hidden;">
	<div class="page">
	<?php  $adv = $cfg['reg_adv'];?>
		<?php  if($adv) { ?><img src="<?php  echo toimage($adv)?>" style="width: 100%;"><?php  } ?>
	    <div class="hd">
	        <h1 class="page_title"><?php  echo $_W['account']['name'];?></h1>
	    </div>
	    <div class="bd">
	        <form action="" method="post" onsubmit="return onCheck()">
	        	<div class="weui_cells weui_cells_form">
	        	<?php  if(empty($cfg['acc_rule'])) { ?>
		            <div class="weui_cell">
		                <div class="weui_cell_hd"><label class="weui_label">工号</label></div>
		                <div class="weui_cell_bd weui_cell_primary">
		                <?php  $len = $cfg['alength'];?>
		                    <input required="required" class="weui_input" <?php  if($len) { ?>maxlength="<?php  echo $len;?>"<?php  } ?> id="account" name="account" type="text" placeholder="请输入工号"/>
		                </div>
		            </div>
				<?php  } ?>
		            <div class="weui_cell">
		                <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
		                <div class="weui_cell_bd weui_cell_primary">
		                    <input required="required" class="weui_input" type="text" id="realname" name="realname" value="<?php  echo $fans['nickname'];?>" placeholder="请输入姓名"/>
		                </div>
		            </div>
		            <div class="weui_cell">
		                <div class="weui_cell_hd"><label class="weui_label">手机号码 </label></div>
		                <div class="weui_cell_bd weui_cell_primary">
		                    <input required="required" class="weui_input" type="tel" name='mobile' id="mobile" maxlength="11" placeholder="请输入手机号码"/>
		                </div>
		            </div>
		        </div>
		        <button type="submit" name="submit" value='1' class="weui_btn weui_btn_primary" style="width:90%;margin: 20px 5%">提交申请</button>
		        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
	        </form>
		</div>
	</div>
	<?php  if($cfg['copyright']) { ?><div style="text-align: center;font-size: 15px;margin: 10px auto;"><?php  echo $cfg['copyright'];?></div><?php  } ?>
</div>
<div class="weui_dialog_confirm" style="display: none">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">温馨提示</strong></div>
        <div style="text-align: center;" class="weui_dialog_bd" id="content"></div>
        <div class="weui_dialog_ft">
            <a href="#" class="weui_btn_dialog primary" onclick="$('.weui_dialog_confirm').hide()">确定</a>
        </div>
    </div>
</div>
<script>
function onCheck(){
	<?php  if(empty($cfg['acc_rule'])) { ?>
	var account = $.trim($('#account').val());
	if(account == '' <?php  if($len) { ?> || account.length != <?php  echo $len;?><?php  } ?>){
		$('#content').text('请输入正确的工号！');
		$('.weui_dialog_confirm').show();
		return false;
	}
	<?php  } ?>
	if($.trim($('#realname').val()) == ''){
		$('#content').text('请输入姓名！');
		$('.weui_dialog_confirm').show();
		return false;
	}
	if($.trim($('#mobile').val()) == '' || !$("#mobile").val().match(/^1[34578]\d{9}$/)){
		$('#content').text('请输入正确的手机号码！');
		$('.weui_dialog_confirm').show();
		return false;
	}
}
</script>
</body>
</html>