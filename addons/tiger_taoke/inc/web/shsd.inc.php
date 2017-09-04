<?php
 global $_W, $_GPC;
        $cfg = $this->module['config'];
        $id=$_GPC['id'];
        $jljf=$_GPC['jljf'];
        $sjjl=$_GPC['sjjl'];
        load()->model('mc');
    
        if(pdo_update($this->modulename . "_sdorder",array('jljf'=>$jljf,'sjjl'=>$sjjl,'status'=>1), array ('id' => $_GPC['id']))){
            
             $views=pdo_fetch("select * from".tablename($this->modulename."_sdorder")." where weid='{$_W['uniacid']}' and id='{$id}'");
              // file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($views['openid']),FILE_APPEND);
             if(!empty($views['openid'])){
               $uid=mc_openid2uid($views['openid']);
               //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($uid),FILE_APPEND);
               mc_credit_update($uid,'credit1',$jljf,array($uid,'晒单奖励'));
               //晒单成功!\n奖励您#积分#积分
               $msg=str_replace('#积分#',$jljf,$cfg['sdjltx']);
               $this->sendNews($views['openid'],$msg);

               //查找本级
               $member=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$uid));
               //查找上级
               $hmember=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$member['helpid']));
               if(!empty($hmember)){
                  mc_credit_update($hmember['openid'],'credit1',$sjjl,array($hmember['openid'],'1级晒单奖励'));
                  //您的朋友#昵称#晒单成功!\n奖励您#积分#积分
                  $hmsg=str_replace('#积分#',$sjjl,$cfg['sjjltx']);
                  $hmsg=str_replace('#昵称#',$member['nickname'],$hmsg);
                  $this->sendNews($hmember['from_user'],$hmsg);
                }
             }
             die(json_encode(array("status"=>1,'info'=>'奖励成功')));
        }else{
             die(json_encode(array("status"=>10,'info'=>'奖励失败')));
        }