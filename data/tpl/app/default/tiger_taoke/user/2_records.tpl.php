<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no" />
    <title>我的<?php  if($cfg['hztype']) { ?><?php  echo $cfg['hztype'];?><?php  } else { ?>积分<?php  } ?></title>
    <link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/user/css/style.css" rel="stylesheet" />
    <link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/user/css/swipper.css" rel="stylesheet" />
    <link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/user/css/preload.css" rel="stylesheet" />
    <link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/user/css/loading.css" rel="stylesheet" />
    
    <script>
        var deviceWidth = document.documentElement.clientWidth;
        if (deviceWidth > 750) deviceWidth = 750;
        document.documentElement.style.fontSize = deviceWidth / 7.5 + "px";
        document.documentElement.style.width = "100%";
    </script>
</head>
<body>
    <div id="containter" class="container">
        
<div class="ordernav">
        <?php  if($cfg['yjf']) { ?><a href="<?php  echo $this->createMobileUrl('mfan1',array('uid'=>$fans['uid'],'dluid'=>$dluid))?>" ><span>我的朋友</span></a><?php  } ?>
        <?php  if($cfg['ejf']) { ?><a href="<?php  echo $this->createMobileUrl('mfan2',array('level'=>1,'uid'=>$fans['uid'],'dluid'=>$dluid))?>" ><span>朋友的朋友</span></a><?php  } ?>
        <a href="<?php  echo $this->createMobileUrl('records',array('pid'=>$pid,'dluid'=>$dluid));?>" class="cur"><span>我的<?php  if($cfg['hztype']) { ?><?php  echo $cfg['hztype'];?><?php  } else { ?>积分<?php  } ?></span></a>
        <?php  if($cfg['phbtype']==1) { ?>
        <a href="<?php  echo $this->createMobileUrl('ranking',array('pid'=>$pid,'dluid'=>$dluid));?>"><span><?php  if($cfg['hztype']) { ?><?php  echo $cfg['hztype'];?><?php  } else { ?>积分<?php  } ?>排行榜</span></a>
        <?php  } ?>
</div>


<style>
.usertx {
        width: 7.5rem;
        display: -webkit-box;
        height: 45px;
        line-height: 45px;
        -webkit-box-align: center;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding: 0 0.2rem;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        position: relative;
        width:100%;
        

    }
    .usertx span {
        display: block;
        color: #888;
    }
    .usertx .userpointer {
        position: absolute;
        color: #444;
        font-size: 16px;
        left:15px;
    }
     .usergotx {
        position: absolute;
        right:20px;
    }
</style>
<div class="allorder">
    <ul id="lists">
    <?php  if($records) { ?>
        <?php  if(is_array($records)) { foreach($records as $k => $r) { ?>
          <div class="usertx">
          <span class="userpointer" style="font-size:14px;"><?php  echo cutstr($r['remark'],10)?></span>
          <span class="usergotx" ><?php  echo number_format($r['num'])?></span>
          <p style="position: absolute;top:22px;left:15px;"><?php  echo date('Y-m-d H:i',$r['createtime'])?></p>
          </div>
          
        <?php  } } ?>
    <?php  } else { ?>
        <div class="nocoll">
          <p>您还没有<?php  if($cfg['hztype']) { ?><?php  echo $cfg['hztype'];?><?php  } else { ?>积分<?php  } ?>记录哦~</p>
        </div>
    <?php  } ?>
    </ul>
</div>
<style>
.usertx {
    height: 62px;
    line-height: 40px;
    }
</style>


</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('newbottom', TEMPLATE_INCLUDEPATH)) : (include template('newbottom', TEMPLATE_INCLUDEPATH));?>
<style>

</style>
</body>
</html>

