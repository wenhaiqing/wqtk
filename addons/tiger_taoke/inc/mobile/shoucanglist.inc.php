<?php
global $_W, $_GPC;
       $cfg = $this->module['config'];
       $fans=$_W['fans'];
       $dluid=$_GPC['dluid'];//share id
      
            
        $sclist = pdo_fetchall("select * from ".tablename($this->modulename."_shoucang")." where weid='{$_W['uniacid']}' and openid='{$fans['openid']}'");

        
        $list=array();
        foreach($sclist as $k=>$v){
            $s = pdo_fetch("select * from ".tablename($this->modulename."_tbgoods")." where weid='{$_W['uniacid']}' and id='{$v['goodsid']}'");
            if(empty($s)){
              continue;
            }
            $list[$k]=$s;          
        }

        

        $style=$cfg['qtstyle'];
        if(empty($style)){
            $style='style1';        
        }
        $dblist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=1  order by px desc");//底部菜单

       include $this->template ( 'tbgoods/'.$style.'/shoucanglist' );