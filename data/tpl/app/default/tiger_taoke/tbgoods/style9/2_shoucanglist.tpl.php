<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no" />
<title>我的收藏</title>
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/style.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/swipper.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/preload.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/loading.css" rel="stylesheet" />
<script>
        var deviceWidth = document.documentElement.clientWidth;
        if (deviceWidth > 750) deviceWidth = 750;
        document.documentElement.style.fontSize = deviceWidth / 7.5 + "px";
        document.documentElement.style.width = "100%";
    </script>
    <script>
var weid="<?php  echo $_W['uniacid'];?>";

</script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/htool.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/asynloading.js"></script>
</head>
<body>
<div id="containter" class="container">
<div class="goods_list collection_list">
<ul id="lists" data-url="">
<?php  if(is_array($list)) { foreach($list as $v) { ?>
        <li class="relative">
            <div class="goods_pic">
            <a href="<?php  echo $this->createMobileUrl('view',array('id'=>$v['id'],'dluid'=>$dluid))?>">
            <div class="allpreContainer">
            <div class="inoutbg" style="background-image: url(<?php  echo tomedia($v['pic_url'])?>_240x240.jpg); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
            </div>
            <img class="preloadbg" src-data="<?php  echo tomedia($v['pic_url'])?>_240x240.jpg" src="" loaded="false">
            <div class="DSbg" style="background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
            </div>
            </div>
            </a>
            </div>
            <div class="goods_bottom">
            <div>
            <a class="goods_text" href="<?php  echo $this->createMobileUrl('view',array('id'=>$v['id'],'dluid'=>$dluid))?>"><?php  echo $v['title'];?></a>
            </div>
            <?php  if($v['istmall']==1) { ?>
            <div class="comefrom">去天猫</div>
            <?php  } else { ?>
            <div class="comefrom" style="background: url(<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/images/taobao.png) no-repeat left center/15px;">去淘宝</div>
            <?php  } ?>
            <div style=" position: absolute;top:65px;left: 10px;font-size:12px;">销量：<?php  if($v['goods_sale']) { ?><?php  echo $v['goods_sale'];?><?php  } else { ?><?php  echo rand(2000,2500)?><?php  } ?></div>
            <div class="goodspc">
            <div class="goods_price">
            <span style="font-size:12px;">券后价:</span><span><?php  echo $v['price'];?></span>
            </div>
            <a href="javascript:;" class="new-coupon" data-img="<?php  echo tomedia($v['pic_url'])?>_240x240.jpg"  data-dluid="<?php  echo $dluid;?>" data-rxyjxs="<?php  echo $cfg['rxyjxs'];?>" data-id="<?php  echo $v['id'];?>"><span>马上领劵</span><span>立减<em class="ljmoney"><?php  echo $v['coupons_price'];?></em>元</span></a>
            </div>
            </div>
        </li>
      <?php  } } ?>
</ul>
</div>
</div>
<!--底部菜单开始-->
<?php  if($dblist) { ?>
    <div id="menu">
        <ul>
        <?php  if(is_array($dblist)) { foreach($dblist as $v) { ?>
            <li class="relative ">
                <a href="<?php  echo $v['wlurl'];?>&dluid=<?php  echo $dluid;?>" class="link-hover"></a>
                <div class="menu-inside">
                <span class="icon_n1" style="background: url(<?php  echo tomedia($v['picurl'])?>) no-repeat;border-radius:50%"></span>
                <font><?php  echo $v['title'];?></font>
                </div>
            </li>
         <?php  } } ?>
        </ul>
    </div>
<?php  } else { ?>
    <div id="menu">
        <ul>
            <li class="relative active">
                <a href="<?php  echo $this->createMobileUrl('index',array('dluid'=>$dluid))?>" class="link-hover"></a>
                <div class="menu-inside">
                <span class="icon_n1"></span>
                <font>首页</font>
                </div>
            </li>
            <li class="relative">
            <a href="<?php  echo $this->createMobileUrl('catlist',array('tj'=>1,'dluid'=>$dluid))?>" class="link-hover"></a>
            <div class="menu-inside">
            <span class="icon_n2"></span>
            <font>9.9</font>
            </div>
            </li>
            <li class="relative">
            <a href="<?php  echo $this->createMobileUrl('shoucanglist',array('dluid'=>$dluid))?>" class="link-hover"></a>
            <div class="menu-inside">
            <span class="icon_n3"></span>
            <font>收藏</font>
            </div>
            </li>
            <li class="relative">
            <a href="<?php  echo $this->createMobileUrl('member',array('dluid'=>$dluid))?>" class="link-hover"></a>
            <div class="menu-inside">
            <span class="icon_n4"></span>
            <font>我的</font>
            </div>
            </li>
        </ul>
    </div>
 <?php  } ?>
 <!--底部菜单结束-->
    
    </body>
</html>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/jquery-1.7.2.min.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/clipboard.min.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/idangerous.swiper.min.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/common_phone.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/fun.js"></script>
<script>
        $(function () {
            if ($("#lists li").length < 1) {
                var html = "";
                html += '<div class="nocoll">';
                html += '<img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/images/hu.png">';
                html += '<p>您的收藏夹还没有商品<br>快去收藏商品！</p>';
                html += '<div class="miaotui"><a href="<?php  echo $this->createMobileUrl('index',array('dluid'=>$dluid))?>">首页</a></div>';
                html += '</div>';
                document.querySelector(".container").innerHTML = html;
                $(".nocoll").height(document.documentElement.clientHeight + "px");
            }
        })
    </script>
    <script type="text/javascript">
    $(function () {
      var clipboard = new Clipboard(".taokaocopy", {
        text: function () {
          return $(".copybox1").val();
        }
      });

      clipboard.on('success', function (e) {
        //alert("链接已复制到剪贴板");
        $(".taokaocopy").html("<img src='../addons/tiger_taoke/template/mobile/tbgoods/style9/images/copy1.png'>");
      });

      clipboard.on('error', function (e) {
        //console.log(e);
        $(".taokaocopy").html("<img src='../addons/tiger_taoke/template/mobile/tbgoods/style9/images/copy2.png'>");
      })
    });
  </script>
