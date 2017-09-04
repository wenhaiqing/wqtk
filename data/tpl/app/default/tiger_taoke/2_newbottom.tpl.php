<?php defined('IN_IA') or exit('Access Denied');?><!--底部菜单开始-->
<link href="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/style9/css/style.css" rel="stylesheet" />
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