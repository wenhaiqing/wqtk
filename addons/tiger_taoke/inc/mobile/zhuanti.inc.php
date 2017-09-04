<?php
global $_W, $_GPC;
$dluid=$_GPC['dluid'];//share id
       $cfg = $this->module['config'];
       $weid=$_W['uniacid'];
        if(!empty($cfg['gyspsj'])){
          $weid=$cfg['gyspsj'];
        }
       
        $list = pdo_fetchall("select * from ".tablename($this->modulename."_zttype")." where weid='{$weid}'  order by px desc");
       $dblist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=1  order by px desc");//底部菜单
       $fzlist = pdo_fetchall("select * from ".tablename($this->modulename."_fztype")." where weid='{$weid}'  order by px desc");


       include $this->template ( 'tbgoods/zhuanti' );