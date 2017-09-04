<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no" />
<title><?php  if($share['nickname']) { ?><?php  echo $share['nickname'];?>的分站 - <?php  } ?><?php  if($key) { ?><?php  echo $key;?><?php  } else { ?><?php  echo $fzview['title'];?><?php  } ?><?php  if($tj==1) { ?>9.9专区<?php  } ?><?php  if($tj==2) { ?>19.9专区<?php  } ?><?php  if($sort1=='hot') { ?>销量最高<?php  } else if($sort1=='price') { ?>价格最低<?php  } else if($sort1=='id') { ?>默认排序<?php  } ?><?php  if($ztview['title']) { ?><?php  echo $ztview['title'];?><?php  } ?><?php  if($tj==3) { ?>秒杀专区<?php  } ?></title>
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/style.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/swipper.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/preload.css" rel="stylesheet" />
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/loading.css" rel="stylesheet" />
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/jquery-1.7.2.min.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/clipboard.min.js"></script>
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/js/jweixin-1.0.0.js"></script>
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
</script>
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
    <div class="list_sel goods_topsel" >
        <div class="swiper3 list_sel2">
            <div class="index_navbar swiper-wrapper">
            <?php  if(is_array($fzarr)) { foreach($fzarr as $v) { ?>
            <a href="<?php  echo $this->createMobileUrl('catlist',array('key'=>$v,'typeid'=>$typeid,'dluid'=>$dluid))?>" data-id="" class="swiper-slide"><span><?php  echo $v;?></span></a>
            <?php  } } ?>

            </div>
        </div>
        <div class="topnavlistbtn">
        <img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/images/down@3x.png" style="width:14px;">
        </div>
        <div class="alltopnavbar">
        <?php  if(is_array($fzarr)) { foreach($fzarr as $v) { ?>
        <a href="<?php  echo $this->createMobileUrl('catlist',array('key'=>$v,'typeid'=>$typeid,'dluid'=>$dluid))?>" data-id="" class="swiper-slide"><span><?php  echo $v;?></span></a>
        <?php  } } ?>
        </div>
        <div class="blackbg">
        </div>
    </div>
    <?php  if($cfg['mmtype']<>1) { ?>
    <div class="list_sel listfk">
        <div class="list_sel2">
            <div class="index_navbar swiper-wrapper">
            <a href="<?php  echo $this->createMobileUrl('catlist',array('typeid'=>$typeid,'key'=>$key,'typeid'=>$typeid,'dluid'=>$dluid))?>" class="swiper-slide"><span <?php  if($sort1=='') { ?> class="cur" <?php  } ?>>默认</span></a>
            <a href="<?php  echo $this->createMobileUrl('catlist',array('typeid'=>$typeid,'key'=>$key,'sort'=>'hot','typeid'=>$typeid,'dluid'=>$dluid))?>" class="swiper-slide"><span <?php  if($sort1=='hot') { ?> class="cur" <?php  } ?>>销量</span></a>
            <a href="<?php  echo $this->createMobileUrl('catlist',array('typeid'=>$typeid,'key'=>$key,'sort'=>'price','typeid'=>$typeid,'dluid'=>$dluid))?>" class="swiper-slide"><span <?php  if($sort1=='price') { ?> class="cur" <?php  } ?>>价格</span></a>
            <a href="<?php  echo $this->createMobileUrl('catlist',array('typeid'=>$typeid,'key'=>$key,'sort'=>'hit','typeid'=>$typeid,'dluid'=>$dluid))?>" class="swiper-slide"><span <?php  if($sort1=='hit') { ?> class="cur" <?php  } ?>>人气</span></a>
            </div>
        </div>
    </div>
    <?php  } ?>
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
                <input type="hidden" name="dluid" value="<?php  echo $dluid;?>">
               <input type="text" id="key" name="key"  value="<?php  echo $key;?>" class="tige_sear" />
               <button id="tiger_search-submit" type="submit" onclick="searchan()"><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style5/images/search.png" /></button>
            </form>
          </div>          
          <div class="seach_3" onclick="javascript:window.location.href='<?php  echo $this->createMobileUrl('index',array('dluid'=>$dluid))?>';"></div>
   </div>
</div>
<?php  } else { ?>
<div  id="head_seach" style="height:1px;width:100%"></div>
<?php  } ?>

<!--固定的结束-->

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('tbgoods/nav', TEMPLATE_INCLUDEPATH)) : (include template('tbgoods/nav', TEMPLATE_INCLUDEPATH));?>

<?php  if($ztview['picurl']) { ?><div><img src="<?php  echo tomedia($ztview['picurl'])?>"></div><?php  } ?>
<?php  if($tj==3) { ?>
<div >
    <?php  if($cfg['mspicurl']) { ?><img src="<?php  echo tomedia($cfg['mspicurl'])?>"><?php  } ?>
</div>
<div id="wrap">
    <ul>
        <li class="t_info">倒计时：</li>
        <li id="d" class="time">0</li>
        <li class="info">天</li>
        <li id="h" class="time">0</li>
        <li class="info">时</li>
        <li id="i" class="time">00</li>
        <li class="info">分</li>
        <li id="s" class="time">00</li>
        <li class="info">秒</li>
    </ul>
</div>
<script> 
        test()
		function test(){
			// 当前时间
			var nowTime = new Date().getTime();
			// 2016/12/22 hh:mm:ee
			// 结束时间
			var endTime = new Date("<?php  echo $cfg['mstime'];?>");
			// 相差的时间	
			var t = endTime - nowTime;
			var d = Math.floor(t/1000/60/60/24);			
			var h = Math.floor(t/1000/60/60%24);
			var i = Math.floor(t/1000/60%60);
			var s = Math.floor(t/1000%60);
			document.getElementById('d').innerHTML = d;
			document.getElementById('h').innerHTML = h;
			document.getElementById('i').innerHTML = i;
			document.getElementById('s').innerHTML = s;
			setTimeout(test, 1000);
		}
	</script>
<style>
		#wrap{margin:0 auto; width:100%;height:50px;color:#000000;}
		#wrap ul{list-style: none;padding-left:0.7rem;}
		#wrap ul li.time{height: 50px; line-height:50px; float: left; width:0.7rem; text-align: center;font-size:18px;color:red;}
		#wrap ul li.info{height: 50px; line-height: 50px;float: left; width:0.3rem; text-align: center;}
		#wrap .t_info{height: 50px;line-height: 50px;float:left}
</style>
<?php  } ?>


<div class="goods_list" <?php  if($typeid<>'') { ?><?php  if($cfg['mmtype']<>1) { ?>style="margin-top:35px;"<?php  } ?><?php  } ?> <?php  if($typeid=='' || $cfg['mmtype']==1) { ?>style="margin-top:0;"<?php  } ?> >
    <section class="goods" id="pageCon">
    <ul id="list_box" class="list_box">
    </ul>
    </section>
    <div id="list_more" class="loading1" style="margin-top:10px;text-align:center;padding-bottom:60px;">
	   <span onclick="get_list(0);">查看更多</span>
	 </div>
</div>
</div>

<link rel="stylesheet" href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style5/css/dropload.css">
<script src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style5/js/dropload.min.js"></script>
     <script type="text/javascript">

var limit = 1;
function get_list(ty){
    var tj='<?php  echo $tj;?>';
    if(ty==1){
	   $("#pageCon .list_box").html('');
	}else{
	   $("#list_more").html('<div class="loading1"><span > 卖命加载中...</span></div>');	   
	}
	
	$.ajax({
	    type : "post",
	    url : "<?php  echo $this->createMobileUrl('getlist',array('typeid'=>$typeid,'key'=>$key,'sort'=>$sort1,'tj'=>$tj,'strprice'=>$strprice,'endprice'=>$endprice,'dluid'=>$dluid,'pid'=>$cfg['ptpid'],'zt'=>$zt))?>",
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
            if(tj==3){
               content +='<a href="javascript:;" class="new-coupon" data-img="'+list[i]['pic_url']+'_240x240.jpg" data-openid="<?php  echo $openid;?>" data-dluid="<?php  echo $dluid;?>"  data-rxyjxs="<?php  echo $cfg["rxyjxs"];?>" data-id="'+list[i]['id']+'" data-url="'+list[i]['url']+'"  data-numiid="'+list[i]['num_iid']+'" data-orgprice="'+list[i]['org_price']+'" data-price="'+list[i]['price']+'" coupons_price="'+list[i]['coupons_price']+'"><span>秒杀领券</span><span>立减<em class="ljmoney">'+list[i]['coupons_price']+'</em>元</span></a>';
            }else{
               content +='<a href="javascript:;" class="new-coupon" data-img="'+list[i]['pic_url']+'_240x240.jpg" data-openid="<?php  echo $openid;?>" data-dluid="<?php  echo $dluid;?>"  data-rxyjxs="<?php  echo $cfg["rxyjxs"];?>" data-id="'+list[i]['id']+'" data-url="'+list[i]['url']+'"  data-numiid="'+list[i]['num_iid']+'" data-orgprice="'+list[i]['org_price']+'" data-price="'+list[i]['price']+'" coupons_price="'+list[i]['coupons_price']+'"><span>马上领劵</span><span>立减<em class="ljmoney">'+list[i]['coupons_price']+'</em>元</span></a>';
            }
            
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
							//$("#list_more").fadeOut(500);
						}		
	                    limit++;

				
	

//waterfall();
//lazy_img();
		    }else if(data.status==2){
	    		$("#list_more").html('<span>没有更多记录！</span>');
				//dialog("没有更多记录！");
				//$("#list_more").fadeOut(500);

	    	}else{
			    $("#list_more").html('<span>没有更多记录！</span>');
				//dialog("没有更多记录！！");
				//$("#list_more").fadeOut(500);
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
