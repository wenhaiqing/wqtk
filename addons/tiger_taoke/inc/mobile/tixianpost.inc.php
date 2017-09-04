<?php
global $_W, $_GPC;
       $cfg = $this->module['config'];
       $fans = mc_oauth_userinfo();
	   $fans = $_W['fans'];
       $mc=mc_fetch($fans['openid']);

       
       
       if($mc['credit2']>=$cfg['yjtype']){
           $pice=intval($mc['credit2']);
           if(empty($pice)){
             die(json_encode(array("statusCode"=>2000,"message"=>"佣金提现最少".$cfg['yjtype']."元起")));
           }
            mc_credit_update($mc['uid'],'credit2',-$pice,array($mc['uid'],'淘客佣金提现'));  

            $data=array(
                'weid'=>$_W['uniacid'],
                'nickname'=>$fans['nickname'],
                'openid'=>$fans['openid'],
                'avatar'=>$fans['avatar'],
                'createtime'=>TIMESTAMP,
                'credit2'=>$pice,
                'zfbuid'=>$_GPC['zfbuid'],
                'sh'=>0,
                'dmch_billno'=>$fans['dmch_billno']          
            );
            if (pdo_insert ( $this->modulename . "_txlog", $data ) === false) {
                die(json_encode(array("statusCode"=>100,'msg'=>'系统繁忙！')));  
            } else{
                if(!empty($cfg['khgettx'])){//管理员提现提醒
                    $mbid=$cfg['khgettx'];
                    $mb=pdo_fetch("select * from ".tablename($this->modulename."_mobanmsg")." where weid='{$_W['uniacid']}' and id='{$mbid}'");
                    $msg=$this->mbmsg($cfg['glyopenid'],$mb,$mb['mbid'],$mb['turl'],$fans,'');                  
                }             

                die(json_encode(array("statusCode"=>200,'msg'=>'')));  
            }
       }else{
          die(json_encode(array("statusCode"=>2000,"message"=>"佣金提现最少".$cfg['yjtype']."元起")));
       }
