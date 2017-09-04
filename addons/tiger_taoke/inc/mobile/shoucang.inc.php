<?php
 global $_W, $_GPC;
$dluid=$_GPC['dluid'];//share id
       $fans=$_W['fans'];
       $goodsid=$_GPC['commodityID'];
       file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($goodsid),FILE_APPEND);
       $goods = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$_W['uniacid']}' and id='{$goodsid}'");

        $data=array(
            'weid'=>$_W['uniacid'],
            'title'=>$goods['title'],
            'goodsid'=>$goods['id'],
            'picurl'=>$goods['pic_url'],
             'openid'=>$fans['openid'],
             'uid'=>$fans['uid'],          
             'createtime'=>TIMESTAMP            
        );
        
        $scgoods = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_shoucang") . " WHERE weid = '{$_W['uniacid']}' and goodsid='{$goods['id']}'");
        if(empty($scgoods)){
            if(pdo_insert ($this->modulename . "_shoucang", $data)){
                 die(json_encode(array("statusCode"=>200,'info'=>'成功')));
            }        
        }else{
           pdo_delete($this->modulename."_shoucang", array('id' => $scgoods['id']));
            die(json_encode(array("statusCode"=>200,'info'=>'成功')));
        }

        