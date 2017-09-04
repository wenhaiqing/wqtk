<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no" />
<title><?php  if($share['nickname']) { ?><?php  echo $share['nickname'];?>的<?php  if($bl['fzname']) { ?><?php  echo $bl['fzname'];?><?php  } else { ?>分站<?php  } ?> - <?php  } ?><?php  echo $cfg['copyright'];?> </title>
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/style.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/swipper.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/preload.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/loading.css" rel="stylesheet" />
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/jquery-1.7.2.min.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/clipboard.min.js"></script>
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
<?php  if($share['id']=='') { ?>
<!--固定的-->
<?php  if($typeid=='') { ?>
<div class="tiger_nav1" id="head_seach" >
   <div class="seach_nav" >
          <div class="seach_1" onclick="javascript:history.go(-1);return false;"></div>
          <div class="seach_2">
          <form id="search-form" action="<?php  echo $this->createMobileUrl('catlist')?>" method="get">
               <input type="hidden" name="i" value="<?php  echo $_W['uniacid'];?>">
                <input type="hidden" name="c" value="entry">
                <input type="hidden" name="dluid" value="<?php  echo $dluid;?>">
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
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('tbgoods/nav', TEMPLATE_INCLUDEPATH)) : (include template('tbgoods/nav', TEMPLATE_INCLUDEPATH));?>

<?php  if($share['id']) { ?>
<style>
.nibox{width: 7.5rem;padding:0.1rem;height:2.5rem;background: url(http://cs.youqi18.com/attachment/images/3/2017/02/TX4Uxu50z8615Wx19CZXu4c5z18nU5.jpg) no-repeat center/cover;}
.nileft{width:7.3rem;float:left;height:1rem;padding-top:0.3rem;text-align:center;color:#fff}
.nileft img {width:90%;border-radius:50%;width:1.2rem;}
</style>
<div class="nibox">
   <div class="nileft"><img src="<?php  echo preg_replace('/\/0$/', '/96', stripslashes($share['avatar']));?>"><br><?php  echo $share['nickname'];?>的<?php  if($bl['fzname']) { ?><?php  echo $bl['fzname'];?><?php  } else { ?>分站<?php  } ?> </div>
</div>
<?php  } ?>



<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/cj.css" rel="stylesheet" type="text/css" />

<?php  if($tj=='') { ?>
<?php  if($share['id']=='') { ?>
<?php  if($ad) { ?>
<div id="banner">
    <div class="swiper-container">
        <ul class="swiper-wrapper">
        
        <?php  if(is_array($ad)) { foreach($ad as $a) { ?>
        <li class="swiper-slide">
        <a href="<?php  echo $a['url'];?>">
            <div class="allpreContainer">
            <div class="inoutbg" style="background-image: url(<?php  echo tomedia($a['pic'])?>); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
            </div>
            <img class="preloadbg" src-data="<?php  echo tomedia($a['pic'])?>" src="" loaded="false">
            <div class="DSbg" style="background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
            </div>
            </div>
        </a>
        </li>
        <?php  } } ?>
        
        </ul>
    <div class="swiper-pagination"></div>
    </div>
</div>
<?php  } ?>
<?php  } ?>

<div class="indexlabaslide">
<div class="indexlaba">每天10点更新特价商品</div>
<div class="topsearch">
<img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/images/search.png">
</div>
</div>
<div class="indexnavlist">
<?php  if(is_array($fzlist10)) { foreach($fzlist10 as $k=>$f) { ?>
<a href="<?php  echo $this->createMobileUrl('catlist',array('typeid'=>$f['id'],'dluid'=>$dluid))?>" class="indexnaveach indexnaveach1" style="background-image: url(<?php  echo tomedia($f['picurl'])?>);"><span><?php  echo $f['title'];?></span></a>
<?php  } } ?>  
</div>
<div id="containter index_goods">
<div class="contain-product relative">
<?php  if($zdgoods) { ?>
<div class="index_list_title">
<span class="intro_title"><i>今日专享</i> <em>疯狂抢购</em></span>
<span class="index_list_eachall"><!--a href="">全部</a--></span>
</div>
<div class="contain--product-list swiper2">
<ul class="swiper-wrapper">
 <?php  if(is_array($zdgoods)) { foreach($zdgoods as $v) { ?>
    <li class="relative swiper-slide">
        <div class="contain-prolist-in">
        <a href="<?php  echo $this->createMobileUrl('view',array('id'=>$v['id'],'dluid'=>$dluid))?>" class="ImgOut">
        <div class="allpreContainer">
        <div class="inoutbg" style="background-image: url(<?php  echo tomedia($v['pic_url'])?>_240x240.jpg); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
        </div>
        <img class="preloadbg" src-data="<?php  echo tomedia($v['pic_url'])?>_240x240.jpg" src="<?php  echo tomedia($v['pic_url'])?>_240x240.jpg" loaded="false">
        <div class="DSbg" style="background-image: url(<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/images/smallbg.png); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
        </div>
        </div>
        </a>
        <div class="contain-prolist-text">
        <h1><a href="<?php  echo $this->createMobileUrl('view',array('id'=>$v['id'],'dluid'=>$dluid))?>"><?php  echo $v['title'];?></a></h1>
        <h2>
        <span>&yen;<?php  echo $v['price'];?></span><font>&yen;<?php  echo $v['org_price'];?></font>
        </h2>
        </div>
        <a href="javascript:;" class="new-coupon" data-img="<?php  echo tomedia($v['pic_url'])?>_240x240.jpg" data-openid="<?php  echo $openid;?>" data-dluid="<?php  echo $dluid;?>"  data-rxyjxs="<?php  echo $cfg['rxyjxs'];?>" data-id="<?php  echo $v['id'];?>" data-url="<?php  echo $v['url'];?>"  data-numiid="<?php  echo $v['num_iid'];?>" data-orgprice="<?php  echo $v['org_price'];?>" data-price="<?php  echo $v['price'];?>" coupons_price="<?php  echo $v['coupons_price'];?>"><span>领劵</span><span>立减<em class="ljmoney"><?php  echo $v['coupons_price'];?></em>元</span></a>
        </div>
    </li>
  <?php  } } ?>
</ul>
</div>
<?php  } ?>
<div class="index_list_title">
<span class="intro_title"><i>9.9元包邮</i> <em>跳楼都没有的价格</em></span>
<span class="index_list_eachall"><a href="<?php  echo $this->createMobileUrl('catlist',array('tj'=>1))?>">全部</a></span>
</div>
<div class="contain--product-list swiper2">
<ul class="swiper-wrapper">
    <?php  if(is_array($list99)) { foreach($list99 as $v) { ?>
    <li class="relative swiper-slide">
        <div class="contain-prolist-in">
        <a href="<?php  echo $this->createMobileUrl('view',array('id'=>$v['id'],'dluid'=>$dluid))?>" class="ImgOut">
        <div class="allpreContainer">
        <div class="inoutbg" style="background-image: url(<?php  echo tomedia($v['pic_url'])?>_240x240.jpg); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
        </div>
        <img class="preloadbg" src-data="<?php  echo tomedia($v['pic_url'])?>_240x240.jpg" src="<?php  echo tomedia($v['pic_url'])?>_240x240.jpg" loaded="false">
        <div class="DSbg" style="background-image: url(<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/images/smallbg.png); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">
        </div>
        </div>
        </a>
        <div class="contain-prolist-text">
        <h1><a href="<?php  echo $this->createMobileUrl('view',array('id'=>$v['id'],'dluid'=>$dluid))?>"><?php  echo $v['title'];?></a></h1>
        <h2>
        <span>&yen;<?php  echo $v['price'];?></span><font>&yen;<?php  echo $v['org_price'];?></font>
        </h2>
        </div>
        <a href="javascript:;" class="new-coupon" data-img="<?php  echo tomedia($v['pic_url'])?>_240x240.jpg" data-openid="<?php  echo $openid;?>" data-dluid="<?php  echo $dluid;?>"  data-rxyjxs="<?php  echo $cfg['rxyjxs'];?>" data-id="<?php  echo $v['id'];?>" data-url="<?php  echo $v['url'];?>"  data-numiid="<?php  echo $v['num_iid'];?>" data-orgprice="<?php  echo $v['org_price'];?>" data-price="<?php  echo $v['price'];?>" coupons_price="<?php  echo $v['coupons_price'];?>"><span>领劵</span><span>立减<em class="ljmoney"><?php  echo $v['coupons_price'];?></em>元</span></a>
        </div>
    </li>
  <?php  } } ?>
</ul>
</div>
</div>
</div>
<?php  } ?>



<div class="goods_list index_goodslist">
<div class="index_list_title">
<span class="intro_title"><i><?php  if($tj==1) { ?>9.9专区<?php  } else if($tj==2) { ?>19.9专区<?php  } else { ?>爆品推荐<?php  } ?></i> <em>总有一款属于你</em></span>
</div>
    <section class="goods" id="pageCon">
    <ul id="list_box" class="list_box">
    </ul>
    </section>
    <div id="list_more" class="loading1" style="margin-top:10px;text-align:center">
	   <span onclick="get_list(0);">查看更多</span>
	 </div>
</div>

<link rel="stylesheet" href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style5/css/dropload.css">
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style5/js/dropload.min.js"></script>
     <script type="text/javascript">

var limit = 1;
function get_list(ty){
    
    if(ty==1){
	   $("#pageCon .list_box").html('');
	}else{
	   $("#list_more").html('<div class="loading1"><span > 卖命加载中...</span></div>');	   
	}
	
	$.ajax({
	    type : "post",
	    //url : "<?php  echo $this->createMobileUrl('getlist')?>"+"&tj=<?php  echo $tj;?>&key=<?php  echo $key;?>&typeid=<?php  echo $typeid;?>&dluid=<?php  echo $dluid;?>&'pid'=>$cfg['ptpid'])",
        url : "<?php  echo $this->createMobileUrl('getlist',array('typeid'=>$typeid,'key'=>$key,'sort'=>$sort1,'tj'=>$tj,'strprice'=>$strprice,'endprice'=>$endprice,'dluid'=>$dluid,'pid'=>$cfg['ptpid']))?>",
	    data : {
	    	limit:limit,
	    },
        dataType : "json",		
	    success : function(data) {
	    	if(data.status==1){
						var list = data.content;
						var content = '';
						for(var i=0; i<list.length; i++){


            content +='<li class="relative">';
            content +='<div class="goods_pic">';
            content +='<a href="<?php  echo $this->createMobileUrl("view")?>'+'&id='+list[i]['id']+'&dluid=<?php  echo $dluid;?>&lm='+data.lm+'&num_iid='+list[i]['num_iid']+'&org_price='+list[i]['org_price']+'&price='+list[i]['price']+'&coupons_price='+list[i]['coupons_price']+'&goods_sale='+list[i]['goods_sale']+'&url='+encodeURIComponent(list[i]['url'])+'" >';
            content +='<div class="allpreContainer">';
            content +='<div class="inoutbg" style="background-image: url('+list[i]['pic_url']+'_240x240.jpg); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">';
            content +='</div>';
            content +='<img class="preloadbg" src-data="'+list[i]['pic_url']+'_240x240.jpg" src="" loaded="false">';
            content +='<div class="DSbg" style="background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;">';
            content +='</div>';
            content +='</div>';
            content +='</a>';
            content +='</div>';
            content +='<div class="goods_bottom">';
            content +='<div>';
            content +='<a class="goods_text" href="<?php  echo $this->createMobileUrl("view")?>'+'&id='+list[i]['id']+'&dluid=<?php  echo $dluid;?>&lm='+data.lm+'&num_iid='+list[i]['num_iid']+'&org_price='+list[i]['org_price']+'&price='+list[i]['price']+'&coupons_price='+list[i]['coupons_price']+'&goods_sale='+list[i]['goods_sale']+'&url='+encodeURIComponent(list[i]['url'])+'">'+list[i]['title']+'</a>';
            content +='</div>';
            if(list[i]['istmall']==1){
               content +='<div class="comefrom">去天猫</div>';
            }else{
               content +='<div class="comefrom" style="    background: url(<?php  echo $_W["siteroot"];?>addons/tiger_taoke/template/mobile/tbgoods/style9/images/taobao.png) no-repeat left center/15px;">去淘宝</div>';
            }
            content +='<div style=" position: absolute;top:60px;left: 10px;font-size:12px;">销量：'+list[i]['goods_sale']+'</div>';
            content +='<div style=" position: absolute;top:73px;left: 10px;font-size:12px;">原价：&yen;'+list[i]['org_price']+'</div>';
            content +='<div class="goodspc">';
            content +='<div class="goods_price">';
            content +='<span style="font-size:12px;">券后价:</span><span>'+list[i]['price']+'</span>';
            content +='</div>';
            content +='<a href="javascript:;" class="new-coupon" data-img="'+list[i]['pic_url']+'_240x240.jpg" data-openid="<?php  echo $openid;?>" data-dluid="<?php  echo $dluid;?>"  data-rxyjxs="<?php  echo $cfg["rxyjxs"];?>" data-id="'+list[i]['id']+'" data-url="'+list[i]['url']+'"  data-numiid="'+list[i]['num_iid']+'" data-orgprice="'+list[i]['org_price']+'" data-price="'+list[i]['price']+'" coupons_price="'+list[i]['coupons_price']+'"><span>马上领劵</span><span>立减<em class="ljmoney">'+list[i]['coupons_price']+'</em>元</span></a>';
            content +='</div>';
            content +='</div>';
        content +='</li>';



						}
                        $("#pageCon .list_box").append(content);
						var aa = $(".goods-list li").innerWidth();
                        $(".goods-list li img").css('height',aa);
						if(list.length>1){
							$("#list_more").html('<span onclick="get_list(0);">点击查看更多</span>');
						}else{
							$("#list_more").html('<span></span>');
							$("#list_more").fadeOut(500);
						}		
	                    limit++;

				
	

//waterfall();
//lazy_img();
		    }else if(data.status==2){
	    		$("#list_more").html('<span>没有更多记录！</span>');
				//dialog("没有更多记录！");
				$("#list_more").fadeOut(500);

	    	}else{
			    $("#list_more").html('<span>没有更多记录！</span>');
				//dialog("没有更多记录！！");
				$("#list_more").fadeOut(500);
			}    	
	    },
	    error : function(xhr, type) {

	    }
	});
	    

}
get_list(0);


//==============自动加载=============
//获取滚动条当前的位置 
function getScrollTop() { 
var scrollTop = 0; 
if (document.documentElement && document.documentElement.scrollTop) { 
scrollTop = document.documentElement.scrollTop; 
} 
else if (document.body) { 
scrollTop = document.body.scrollTop; 
} 
return scrollTop; 
} 

//获取当前可是范围的高度 
function getClientHeight() { 
var clientHeight = 0; 
if (document.body.clientHeight && document.documentElement.clientHeight) { 
clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight); 
} 
else { 
clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight); 
} 
return clientHeight; 
} 

//获取文档完整的高度 
function getScrollHeight() { 
return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight); 
} 


$(window).scroll(function () { 
if (getScrollTop() + getClientHeight() == getScrollHeight()) { 
//alert("到达底部"); 
get_list(0);
} 
});

//==============自动加载=============  


function waterfall(limit){
$container = $('#list_box');
$container.masonry('reload');
	$container.imagesLoaded(function() {
		lazy_img();	
		$container.masonry({
			itemSelector: '.picCon',
			isFitWidth: false,//是否根据浏览器窗口大小自动适应默认false
			//gutter: 20,
			isAnimated: false,//是否采用jquery动画进行重拍版
			isRTL:false,//设置布局的排列方式，即：定位砖块时，是从左向右排列还是从右向左排列。默认值为false，即从左向右
            isResizable: true,//是否自动布局默认true
			});
		});
}



</script>

<?php  if($cfg['cjpicurl']) { ?>
<input type="hidden" value="<?php  echo tomedia($cfg['cjpicurl'])?>" id="cjpicurl" />
<?php  } else { ?>
<input type="hidden" value="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/images/indexhb.png" id="cjpicurl" />
<?php  } ?>
<?php  if($cfg['hongbaourl']) { ?>
<input type="hidden" value="<?php  echo $cfg['hongbaourl'];?>" id="cj_url" />
<?php  } else { ?>
<input type="hidden" value="<?php  echo $this->createMobileUrl('huodong',array('dluid'=>$dluid))?>" id="cj_url" />
<?php  } ?>
<iframe src="<?php  echo $this->createMobileUrl('search',array('dluid'=>$dluid))?>" class="searchpage iframew" name="iframew" data-url="<?php  echo $this->createMobileUrl('search')?>"></iframe>
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
<script>
var weid="<?php  echo $_W['uniacid'];?>";
var fxtitle="<?php  echo $cfg['fxtitle'];?>";
var desc="<?php  echo $cfg['fxcontent'];?>";
var imgUrl="<?php  echo tomedia($cfg['fxpicurl'])?>";
var fxlink=window.location.href;
var fxdata={"title":fxtitle,"imgUrl":imgUrl,"desc":desc,"link":fxlink};
</script>

<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/idangerous.swiper.min.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/common_phone.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/fun.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/jweixin-1.0.0.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/dataload.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/cj.js"></script>
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
        var appid = "<?php  echo $_W['account']['jssdkconfig']['appId'];?>";
        var timestamp = "<?php  echo $_W['account']['jssdkconfig']['timestamp'];?>";
        var nonceStr = "<?php  echo $_W['account']['jssdkconfig']['nonceStr'];?>";
        var signature = "<?php  echo $_W['account']['jssdkconfig']['signature'];?>";
        wx.config({
            debug: false,
            appId: appid,
            timestamp: timestamp,
            nonceStr: nonceStr,
            signature: signature,
            jsApiList: [
                "onMenuShareAppMessage",
                "onMenuShareTimeline",
                "chooseImage",
                "uploadImage",
                "downloadImage"
            ]
        });

	wx.ready(function(){
		wx.onMenuShareAppMessage({
			title: fxdata['title'],
			desc: fxdata['desc'],
			link: fxdata['fxlink'],
			imgUrl: fxdata['imgUrl']
		}); 
		wx.onMenuShareTimeline({
			title: fxdata['title'],
			desc: fxdata['desc'],
			link: fxdata['fxlink'],
			imgUrl: fxdata['imgUrl']
		});
	});

        $(window).load(function () {
            var hongbaoykg="<?php  echo $cfg['hongbaoykg'];?>";
            if(hongbaoykg==1){
              indexHB();
            }            
            var swiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                loop: true,
                onSlideChangeEnd: function () {
                    imgScrollIndex = 0;
                    scrollLoadingImg(null, document.documentElement.clientHeight);
                }
            });
            var swiper2 = new Swiper('.swiper2', {
                slidesPerView: 2.93,
                paginationClickable: true,
                freeMode: true,
                onTouchEnd: function () {
                    setTimeout(function () {
                        imgScrollIndex = 0;
                        scrollLoadingImg(null, document.documentElement.clientHeight);
                    }, 1500)

                }
            });
            var swiper3 = new Swiper('.swiper3', {
                slidesPerView: 4.2,
                paginationClickable: true,
                freeMode: true
            });
        });
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