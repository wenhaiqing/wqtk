<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no" />
<title><?php  if($key) { ?><?php  echo $key;?><?php  } else { ?><?php  echo $fzview['title'];?><?php  } ?><?php  if($tj==1) { ?>9.9专区<?php  } ?><?php  if($tj==2) { ?>19.9专区<?php  } ?><?php  if($sort1=='hot') { ?>销量最高<?php  } else if($sort1=='price') { ?>价格最低<?php  } else if($sort1=='id') { ?>默认排序<?php  } ?></title>
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/style.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/swipper.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/preload.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/loading.css" rel="stylesheet" />
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/jquery-1.7.2.min.js"></script>
<script>
        var deviceWidth = document.documentElement.clientWidth;
        if (deviceWidth > 750) deviceWidth = 750;
        document.documentElement.style.fontSize = deviceWidth / 7.5 + "px";
        document.documentElement.style.width = "100%";
    </script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/htool.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/asynloading.js"></script>
</head>
<body>
<div id="containter" class="container">
<?php  if($typeid<>'') { ?>
    <div class="list_sel goods_topsel">
        <div class="swiper3 list_sel2">
            <div class="index_navbar swiper-wrapper">
            <?php  if(is_array($fzarr)) { foreach($fzarr as $v) { ?>
            <a href="<?php  echo $this->createMobileUrl('catlist',array('key'=>$v,'typeid'=>$typeid))?>" data-id="" class="swiper-slide"><span><?php  echo $v;?></span></a>
            <?php  } } ?>

            </div>
        </div>
        <div class="topnavlistbtn">
        <img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/images/down@3x.png" style="width:14px;">
        </div>
        <div class="alltopnavbar">
        <?php  if(is_array($fzarr)) { foreach($fzarr as $v) { ?>
        <a href="<?php  echo $this->createMobileUrl('catlist',array('key'=>$v,'typeid'=>$typeid))?>" data-id="" class="swiper-slide"><span><?php  echo $v;?></span></a>
        <?php  } } ?>
        </div>
        <div class="blackbg">
        </div>
    </div>
    <div class="list_sel listfk">
        <div class="list_sel2">
            <div class="index_navbar swiper-wrapper">
            <a href="<?php  echo $this->createMobileUrl('catlist',array('typeid'=>$typeid,'key'=>$key,'typeid'=>$typeid))?>" class="swiper-slide"><span <?php  if($sort1=='') { ?> class="cur" <?php  } ?>>默认</span></a>
            <a href="<?php  echo $this->createMobileUrl('catlist',array('typeid'=>$typeid,'key'=>$key,'sort'=>'hot','typeid'=>$typeid))?>" class="swiper-slide"><span <?php  if($sort1=='hot') { ?> class="cur" <?php  } ?>>销量</span></a>
            <a href="<?php  echo $this->createMobileUrl('catlist',array('typeid'=>$typeid,'key'=>$key,'sort'=>'price','typeid'=>$typeid))?>" class="swiper-slide"><span <?php  if($sort1=='price') { ?> class="cur" <?php  } ?>>价格</span></a>
            <a href="<?php  echo $this->createMobileUrl('catlist',array('typeid'=>$typeid,'key'=>$key,'sort'=>'hit','typeid'=>$typeid))?>" class="swiper-slide"><span <?php  if($sort1=='hit') { ?> class="cur" <?php  } ?>>人气</span></a>
            </div>
        </div>
    </div>
<?php  } ?>

<!--固定的-->
<?php  if($typeid=='' or $sort<>'') { ?>
<div class="tiger_nav1" id="head_seach">
   <div class="seach_nav" >
          <div class="seach_1" onclick="javascript:history.go(-1);return false;"></div>
          <div class="seach_2">
           <form id="search-form" action="<?php  echo $this->createMobileUrl('catlist')?>" method="get">
                <input type="hidden" name="i" value="<?php  echo $_W['uniacid'];?>">
                <input type="hidden" name="c" value="entry">
                <input type="hidden" name="m" value="tiger_taoke">
                <input type="hidden" name="do" value="catlist">
               <input type="text" id="key" name="key"  value="<?php  echo $key;?>" class="tige_sear" />
               <button id="tiger_search-submit" type="submit" onclick="searchan()"><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style5/images/search.png" /></button>
            </form>
          </div>          
          <div class="seach_3" onclick="javascript:window.location.href='<?php  echo $this->createMobileUrl('index')?>';"></div>
   </div>
</div>
<?php  } else { ?>
<div  id="head_seach" style="height:1px;width:100%"></div>
<?php  } ?>

<!--固定的结束-->

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('tbgoods/nav', TEMPLATE_INCLUDEPATH)) : (include template('tbgoods/nav', TEMPLATE_INCLUDEPATH));?>


<div class="goods_list" <?php  if($typeid=='') { ?>style="margin-top:0;"<?php  } ?> <?php  if($typeid<>'') { ?>style="margin-top:35px;"<?php  } ?>>
    <ul id="lists" data-url="<?php  echo $this->createMobileUrl('getlist',array('typeid'=>$typeid,'key'=>$key,'sort'=>$sort1,'tj'=>$tj,'strprice'=>$strprice,'endprice'=>$endprice))?>">
      <?php  if(is_array($list10)) { foreach($list10 as $v) { ?>
        <li class="relative">
            <div class="goods_pic">
            <a href="<?php  echo $this->createMobileUrl('view',array('id'=>$v['id']))?>">
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
            <a class="goods_text" href="<?php  echo $this->createMobileUrl('view',array('id'=>$v['id']))?>"><?php  echo $v['title'];?></a>
            </div>
            <div class="comefrom"><?php  if($v['istmall']==1) { ?>去天猫<?php  } else { ?>去淘宝<?php  } ?></div>
            <div style=" position: absolute;top:65px;left: 10px;font-size:12px;">销量：<?php  if($v['goods_sale']) { ?><?php  echo $v['goods_sale'];?><?php  } else { ?><?php  echo rand(2000,2500)?><?php  } ?></div>
            <div class="goodspc">
            <div class="goods_price">
            <span style="font-size:12px;">券后价:</span><span><?php  echo $v['price'];?></span>
            </div>
            <a href="javascript:;" class="new-coupon" data-img="<?php  echo tomedia($v['pic_url'])?>_240x240.jpg"  data-openid="<?php  echo $openid;?>"   data-rxyjxs="<?php  echo $cfg['rxyjxs'];?>" data-id="<?php  echo $v['id'];?>"><span>马上领劵</span><span>立减<em class="ljmoney"><?php  echo $v['coupons_price'];?></em>元</span></a>
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
                <a href="<?php  echo $v['wlurl'];?>" class="link-hover"></a>
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
                <a href="<?php  echo $this->createMobileUrl('index')?>" class="link-hover"></a>
                <div class="menu-inside">
                <span class="icon_n1"></span>
                <font>首页</font>
                </div>
            </li>
            <li class="relative">
            <a href="<?php  echo $this->createMobileUrl('catlist',array('tj'=>1))?>" class="link-hover"></a>
            <div class="menu-inside">
            <span class="icon_n2"></span>
            <font>9.9</font>
            </div>
            </li>
            <li class="relative">
            <a href="<?php  echo $this->createMobileUrl('shoucanglist')?>" class="link-hover"></a>
            <div class="menu-inside">
            <span class="icon_n3"></span>
            <font>收藏</font>
            </div>
            </li>
            <li class="relative">
            <a href="<?php  echo $this->createMobileUrl('member')?>" class="link-hover"></a>
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
<script>
var weid="<?php  echo $_W['uniacid'];?>";
</script>

<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/idangerous.swiper.min.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/common_phone.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/fun.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/dataload.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/js/layer_mobile/layer.js"></script>
<script>
function gzrwm(){

layer.open({
  type: 1,
  title: '- 长按识别关注 -',
  skin: 'layui-layer-demo', 
  closeBtn: 0, 
  anim: 2,
  shadeClose: true, 
  content: "<img src='<?php  echo tomedia($cfg['gzewm'])?>' style='width:300px;height:300px;'>"
});

}
</script>
<script>
        $(document).ready(function () {
            var swiper3;
            if ($(window).width() < 375) {
                swiper3 = new Swiper('.swiper3', {
                    slidesPerView: 3.3,
                    paginationClickable: true,
                    freeMode: true,
                    initialSlide: $(".swiper3").find(".cur").index()
                });
            } else {
                swiper3 = new Swiper('.swiper3', {
                    slidesPerView: 4,
                    paginationClickable: true,
                    freeMode: true,
                    initialSlide: $(".swiper3").find(".cur").index()
                });
            }
        });
    </script>
