<?php
/**
 * 微信淘宝客模块处理程序
 *
 * @author 老虎
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/TopSdk.php";
class Tiger_taokeModuleProcessor extends WeModuleProcessor {
	public function respond() {
         global $_W;
         load()->model('mc');
         $poster = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_poster')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
         $fans = mc_fetch($this->message['from']);
         file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($this->message),FILE_APPEND);

         

         //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".$this->message['from']['recognition']),FILE_APPEND);
         //return $this->postText($this->message['from'],$this->message['recognition']);      
         if (empty($fans['nickname']) || empty($fans['avatar'])){
                    $openid = $this->message['from'];
					$ACCESS_TOKEN = $this->getAccessToken();
					$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
					load()->func('communication');
					$json = ihttp_get($url);
					$userInfo = @json_decode($json['content'], true);
					$fans['nickname'] = $userInfo['nickname'];
					$fans['avatar'] = $userInfo['headimgurl'];
					$fans['province'] = $userInfo['province'];
					$fans['city'] = $userInfo['city'];
					//mc_update($this->message['from'],array('nickname'=>$fans['nickname'],'avatar'=>$fans['avatar']));
				}
         if(!empty($poster)){
           if($poster['starttime']>time()){
              return $this->postText($this->message['from'],$poster['nostarttips']);              
           }elseif($poster['endtime']<time()){
              return $this->postText($this->message['from'],$poster['endtips']);
           }           
         } 
         $cfg = $this->module['config']; 
         include IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/tb.php"; 

         

         $geturl=$this->geturl($this->message['content']);
         //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($geturl),FILE_APPEND);
         if(!empty($geturl) and $this->message['msgtype'] == 'text'){

             
             $ck = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_ck')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
             $myck=$ck['data'];
             $istao=$this->myisexists($geturl);
             if(!empty($istao)){
                 if($istao==1){//e22a地址
                     $goodsid=$this->hqgoodsid($geturl); 
                     if(empty($goodsid)){
                        return $this->respText($cfg['ermsg']);
                     }
                     $url="https://item.taobao.com/item.htm?id=".$goodsid;
                     //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($goodsid),FILE_APPEND);
                     $res=hqyongjin($url,$myck,$cfg,$this->modulename);  
                     file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode("----------res---------------<Br>"),FILE_APPEND);
                     file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($res),FILE_APPEND);
                     /*
                     $res=array(
                        'num_iid'=>商品ID
                        'title'=>$title,//名称
                        'qhjpric'=>$qhjpric//券后价
                        'commissionRate'=>佣金比例
                        'price'=>$zkPrice,//商品折扣价格
                        'zyhhprice'=>$zyhhprice,//优惠后价格
                        'zyh'=>$zyh,//优惠金额
                        'couponAmount'=>$couponAmount,//优惠券金额
                        'flyj'=>$flyj,//自购佣金
                        'biz30day'=>30天销量
                        'taokouling'=>$taokouling,//淘口令
                        'couponid'=>$youhui['id'],//优惠券ID
                        'couponendtime'=>$youhui['endtime'],//优惠券到期时间
                        'pictUrl'=>$pictUrl
                        'qq'=>1 //1鹊桥 0定向普通
                    );
                     */
                    
                     
                     if(!empty($res['couponid'])){ 
                        if($res['qq']==1){
                          $erylj=$this->rhy($res['couponid'],$res['numid'],$cfg['qqpid']);
                        }else{
                          $erylj=$this->rhydx($res['couponid'],$res['numid'],$cfg['ptpid']);
                        }
                        
                        $taokouling=$this->tkl($erylj,$res['pictUrl'],$res['title']);
                         $taokou=$taokouling->model;
                         settype($taokou, 'string');
                         $res['taokouling']=$taokou;
                     }

                      //入库
                             $data=array(
                                 'weid' => $_W['uniacid'],
                                 'num_iid'=>$res['num_iid'],//商品ID
                                 'title'=>$res['title'],//商品名称
                                 'pic_url'=>$res['pictUrl'],//主图地址
                                 'org_price'=>$res['price'],//'商品原价', 
                                 'price'=>$res['qhjpric'],//商品价格,券后价
                                 'tk_rate'=>$res['commissionRate'],//通用佣金
                                 'quan_id'=>$res['couponid'],//'优惠券ID',  
                                 'coupons_price'=>$res['couponAmount'],//优惠券面额
                                 'goods_sale'=>$res['biz30day'],//月销售
                                 'taokouling'=>$res['taokouling'],//淘口令
                                 'coupons_end'=>strtotime($res['couponendtime']),//优惠券结束
                                 'createtime'=>TIMESTAMP,
                             );
                     //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n123".json_encode($data),FILE_APPEND);
                     $this->addtbgoods($data);
                     //入库结束


                     if(!empty($res['error'])){
                       return $this->respText("Hi,{$fans['nickname']}\n".$res['error']);
                     }else{
                       $msg=str_replace('#昵称#',$fans['nickname'], $cfg['flmsg']);
                       $msg=str_replace('#名称#',$res['title'], $msg);
                       $msg=str_replace('#原价#',$res['price'], $msg);
                       $msg=str_replace('#惠后价#',$res['zyhhprice'], $msg);
                       $msg=str_replace('#券后价#',$res['qhjpric'], $msg);
                       $msg=str_replace('#总优惠#',$res['zyh'], $msg);
                       if(empty($res['couponAmount'])){
                         $res['couponAmount']='0';
                       }
                       $msg=str_replace('#优惠券#',$res['couponAmount'], $msg);
                       if($cfg['fxtype']==1){
                         $res['flyj']=intval($res['flyj']);
                       }
                       $msg=str_replace('#返现金额#',$res['flyj'], $msg);
                       $msg=str_replace('#淘口令#',$res['taokouling'], $msg);

                       //$message="Hi,{$fans['nickname']}\n{$res['title']}\n原价：{$res['price']}元\n总优惠后：{$res['zyhhprice']}元\n\n【优惠详情】\n总优惠约：{$res['zyh']}元\n其中优惠券：{$res['couponAmount']}元\n好评后优惠约：{$res['flyj']}元\n\n【下单】\n长按复制本条消息,打开【手机淘宝客户端】下单：{$res['taokouling']}";
                       //return $this->postText($this->message['from'],$message);
                       file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($msg),FILE_APPEND);
                       return $this->respText($msg);
                     }
                     //return $this->postText($this->message['from'],$goodsid);
                 }elseif($istao==2){//淘宝天猫地址
                     $goodsid=$this->mygetID($geturl);
                     $url="https://item.taobao.com/item.htm?id=".$goodsid;
                     $res=hqyongjin($url,$myck,$cfg,$this->modulename);  
                      file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode("----------res---------------<Br>"),FILE_APPEND);
                     file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($res),FILE_APPEND);
     

                     if(!empty($res['couponid'])){                        
                        if($res['qq']==1){
                          $erylj=$this->rhy($res['couponid'],$res['numid'],$cfg['qqpid']);
                        }else{
                          $erylj=$this->rhydx($res['couponid'],$res['numid'],$cfg['ptpid']);
                        }
                         file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".$erylj,FILE_APPEND);

                        $taokouling=$this->tkl($erylj,$res['pictUrl'],$res['title']);
                         $taokou=$taokouling->model;
                         settype($taokou, 'string');
                         $res['taokouling']=$taokou;
                     }   

                     //入库
                             $data=array(
                                 'weid' => $_W['uniacid'],
                                 'num_iid'=>$res['num_iid'],//商品ID
                                 'title'=>$res['title'],//商品名称
                                 'pic_url'=>$res['pictUrl'],//主图地址
                                 'org_price'=>$res['price'],//'商品原价', 
                                 'price'=>$res['qhjpric'],//商品价格,券后价
                                 'tk_rate'=>$res['commissionRate'],//通用佣金
                                 'quan_id'=>$res['couponid'],//'优惠券ID',  
                                 'coupons_price'=>$res['couponAmount'],//优惠券面额
                                 'goods_sale'=>$res['biz30day'],//月销售
                                 'taokouling'=>$res['taokouling'],//淘口令
                                 'coupons_end'=>strtotime($res['couponendtime']),//优惠券结束
                                 'createtime'=>TIMESTAMP,
                             );
                             $this->addtbgoods($data);
                             //入库结束
                     

                     if(!empty($res['error'])){
                       return $this->respText("Hi,{$fans['nickname']}\n".$res['error']);
                     }else{
                        $msg=str_replace('#昵称#',$fans['nickname'], $cfg['flmsg']);
                       $msg=str_replace('#名称#',$res['title'], $msg);
                       $msg=str_replace('#原价#',$res['price'], $msg);
                       $msg=str_replace('#惠后价#',$res['zyhhprice'], $msg);
                       $msg=str_replace('#券后价#',$res['qhjpric'], $msg);
                       $msg=str_replace('#总优惠#',$res['zyh'], $msg);
                       if(empty($res['couponAmount'])){
                         $res['couponAmount']='0';
                       }
                       $msg=str_replace('#优惠券#',$res['couponAmount'], $msg);
                       if($cfg['fxtype']==1){
                         $res['flyj']=intval($res['flyj']);
                       }
                       $msg=str_replace('#返现金额#',$res['flyj'], $msg);
                       $msg=str_replace('#淘口令#',$res['taokouling'], $msg);
                       //$message="Hi,{$fans['nickname']}\n{$res['title']}\n原价：{$res['price']}元\n总优惠价：{$res['zyhhprice']}元\n\n【优惠详情】\n总优惠约：{$res['zyh']}元\n其中优惠券：{$res['couponAmount']}元\n好评后优惠约：{$res['flyj']}元\n\n【下单】\n长按复制本条消息,打开【手机淘宝客户端】下单：{$res['taokouling']}";
                       return $this->respText($msg);
                     }
                     //return $this->postText($this->message['from'],$goodsid);
                 }
             }else{
                 return $this->respText($cfg['ermsg']);
               // return $this->postText($this->message['from'],"亲！您发送的消息有误哦！请参看这个链接:http://www.baidu.com");
             }
         }



         //淘口令开始
         if($this->message['msgtype'] == 'text'){
                 
                 $kl=$this->getyouhui2($this->message['content']);
                  file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n"."-----------------",FILE_APPEND);
                 file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".$kl,FILE_APPEND);
                 file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n"."-----------------",FILE_APPEND);
                
                 if(!empty($kl)){//口令
                     $ck = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_ck')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
                     $myck=$ck['data'];
                     //$kouling="￥".$kl."￥";
                       $kouling=$kl;
                      file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($kouling),FILE_APPEND);
                     //return $this->respText($kouling);   
                     $res=hqyongjin($url,$myck,$cfg,$this->modulename,$kouling,1);
                           if(!empty($res['couponid'])){ 
                                if($res['qq']==1){
                                  $erylj=$this->rhy($res['couponid'],$res['numid'],$cfg['qqpid']);
                                }else{
                                  $erylj=$this->rhydx($res['couponid'],$res['numid'],$cfg['ptpid']);
                                }
                                
                                $taokouling=$this->tkl($erylj,$res['pictUrl'],$res['title']);
                                 $taokou=$taokouling->model;
                                 settype($taokou, 'string');
                                 $res['taokouling']=$taokou;
                             }

                            //入库
                             $data=array(
                                 'weid' => $_W['uniacid'],
                                 'num_iid'=>$res['num_iid'],//商品ID
                                 'title'=>$res['title'],//商品名称
                                 'pic_url'=>$res['pictUrl'],//主图地址
                                 'org_price'=>$res['price'],//'商品原价', 
                                 'price'=>$res['qhjpric'],//商品价格,券后价
                                 'tk_rate'=>$res['commissionRate'],//通用佣金
                                 'quan_id'=>$res['couponid'],//'优惠券ID',  
                                 'coupons_price'=>$res['couponAmount'],//优惠券面额
                                 'goods_sale'=>$res['biz30day'],//月销售
                                 'taokouling'=>$res['taokouling'],//淘口令
                                 'coupons_end'=>strtotime($res['couponendtime']),//优惠券结束
                                 'createtime'=>TIMESTAMP,
                             );
                             $this->addtbgoods($data);
                             //入库结束


                             if(!empty($res['error'])){
                               return $this->respText("Hi,{$fans['nickname']}\n".$res['error']);
                             }else{
                               $msg=str_replace('#昵称#',$fans['nickname'], $cfg['flmsg']);
                               $msg=str_replace('#名称#',$res['title'], $msg);
                               $msg=str_replace('#原价#',$res['price'], $msg);
                               $msg=str_replace('#惠后价#',$res['zyhhprice'], $msg);
                               $msg=str_replace('#券后价#',$res['qhjpric'], $msg);
                               $msg=str_replace('#总优惠#',$res['zyh'], $msg);
                               if(empty($res['couponAmount'])){
                                 $res['couponAmount']='0';
                               }
                               $msg=str_replace('#优惠券#',$res['couponAmount'], $msg);
                               if($cfg['fxtype']==1){
                                 $res['flyj']=intval($res['flyj']);
                               }
                               $msg=str_replace('#返现金额#',$res['flyj'], $msg);
                               $msg=str_replace('#淘口令#',$res['taokouling'], $msg);

                               //$message="Hi,{$fans['nickname']}\n{$res['title']}\n原价：{$res['price']}元\n总优惠后：{$res['zyhhprice']}元\n\n【优惠详情】\n总优惠约：{$res['zyh']}元\n其中优惠券：{$res['couponAmount']}元\n好评后优惠约：{$res['flyj']}元\n\n【下单】\n长按复制本条消息,打开【手机淘宝客户端】下单：{$res['taokouling']}";
                               //return $this->postText($this->message['from'],$message);
                               file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($msg),FILE_APPEND);
                               return $this->respText($msg);
                             }
                 }
         }

         //淘口令结束

         //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode(88888),FILE_APPEND);




         if($this->message['content']=='肯定好友'){
           
           //地区限制
           $cfg=$this->module['config'];           
           if($cfg['locationtype']==1 || $cfg['locationtype']==2 || $cfg['locationtype']==0){             
                 $user = mc_fetch($this->message['from']);
                 $city=$user['residecity'];
                 $pos = stripos($cfg['city'],$city);
                 if ($pos === false) {
                     $dqurl="<a href='".$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('diqu',array('uid'=>$fans['uid'])))."'>点击这里</a>";
                     $dqmsg="次活动只针对【".$cfg['city']."】微信用户开放\n\n当前地区为【".$city."】\n\n如果你是该地区的用户，".$dqurl."验证\n\n如果不处于此地区，暂时不能参与活动，感谢您的支持！";
                     $this->postText($this->message['from'],$dqmsg);
                     exit; 
                 }else{
                 }
           }

           $from_user=$this->message['content'];
           $credit1=pdo_fetch('select * from '.tablename('mc_credits_record').' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark',array(':uniacid'=>$_W['uniacid'],':uid'=>$fans['uid'],':credittype'=>'credit1',':remark'=>'关注送积分'));
           $credit2=pdo_fetch('select * from '.tablename('mc_credits_record').' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark',array(':uniacid'=>$_W['uniacid'],':uid'=>$fans['uid'],':credittype'=>'credit2',':remark'=>'关注送余额'));
          
           if($_W['account']['level']==4){
               if($poster['kdtype']==0){//开启肯定好友
                   $this->postText($this->message['from'],"商家未开启该功能！");
                   exit;
               }

               if(empty($credit1) || empty($credit1)){
                   //得积分开始                    
                   $share=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$fans['uid']));//当前粉丝  
                    //$this->postText($this->message['from'],$fans['nickname']);
                   // exit;
                    if($poster['score']>0 || $poster['scorehb']>0){
                      $info1=str_replace('#昵称#',$fans['nickname'], $poster['ftips']);
                      $info1=str_replace('#积分#',$poster['score'], $info1);
                      $info1=str_replace('#元#',$poster['scorehb'], $info1);
                      if($poster['score']){mc_credit_update($share['openid'],'credit1',$poster['score'],array($share['openid'],'关注送积分'));}
                      if($poster['scorehb']){mc_credit_update($share['openid'],'credit2',$poster['scorehb'],array($share['openid'],'关注送余额'));}                      
                      $this->postText($this->message['from'],$info1);
                    }
                    if($share['helpid']==0){//没有上级退出
                      exit;
                    }
                    $hmember=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$share['helpid']));
                    if($poster['cscore']>0 || $poster['cscorehb']>0){
                      if($hmember['status']==1){
                        exit;
                      }
                      $info2=str_replace('#昵称#',$fans['nickname'], $poster['utips']);
                      $info2=str_replace('#积分#',$poster['cscore'], $info2);
                      $info2=str_replace('#元#',$poster['cscorehb'], $info2);
                      if($poster['cscore']){mc_credit_update($hmember['openid'],'credit1',$poster['cscore'],array($hmember['openid'],'2级推广奖励'));}
                      if($poster['cscorehb']){mc_credit_update($hmember['openid'],'credit2',$poster['cscorehb'],array($hmember['openid'],'2级推广奖励'));}                      
                      $this->postText($hmember['from_user'],$info2);
                    }
                    if($poster['pscore']>0 || $poster['pscorehb']>0){
                      $fmember=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$hmember['helpid']));
                      if($fmember['status']==1){
                        exit;
                      }
                        if($fmember){
                            $info3=str_replace('#昵称#',$fans['nickname'], $poster['utips2']);
                            $info3=str_replace('#积分#',$poster['pscore'], $info3);
                            $info3=str_replace('#元#',$poster['pscorehb'], $info3);
                            if($poster['pscore']){mc_credit_update($fmember['openid'],'credit1',$poster['pscore'],array($fmember['openid'],'3级推广奖励'));}
                            if($poster['pscorehb']){mc_credit_update($fmember['openid'],'credit2',$poster['pscorehb'],array($fmember['openid'],'3级推广奖励'));}        
                            $this->postText($fmember['from_user'],$info3);   
                        }
                    }
                    //结束
                    exit;
               }else{
                 $kdmsg='尊敬的粉丝：\n\n您已经领取过积分了，不能重复领取，快去生成海报赚取积分吧！';
                 //$this->postText($this->message['from'],$kdmsg);
                 return $this->respText($kdmsg);
                 exit;
               }
              
              
           }
           //订阅号走下面           
           if(empty($credit1) || empty($credit1)){
             $urljq="<a href='".$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('kending',array('uid'=>$fans['uid'])))."'>点击这里</a>";
             $kdmsg="尊敬的粉丝：\n需要点击领取奖励才能获得奖励哦\n".$urljq."领取";
             /*
             $this->respNews(array(
                                    'Title' => '点击领取积分，积分可以换钱',
                                    'Description' => '点击领取积分，积分可以换钱',
                                    'PicUrl' => tomedia('http://i3.dpfile.com/2011-02-19/6822274_b.jpg'),
                                    'Url' => $_W['siteroot'].str_replace('./','app/',$this->createMobileurl('kending',array('uid'=>$fans['uid']))), 
                                ));*/
           }else{
             $kdmsg='尊敬的粉丝：\n\n您已经领取过积分了，不能重复领取，快去生成海报赚取积分吧！';
           }
           //$this->postText($this->message['from'],$kdmsg);
           return $this->respText($kdmsg);
           exit;
         }
        
         //$fans['openid']=$fans['tag']['openid'];
         //$fans['nickname']=$fans['tag']['nickname'];
         //$fans['avatar']=$fans['tag']['avatar'];
         
         //$this->postText($this->message['from'],$scene_id);
         //$this->postText($this->message['from'],$poster['ftips']);
         //exit;
         if ($this->message['msgtype'] == 'event' || $this->message['event'] == 'subscribe' || $this->message['event'] =='SCAN') {
             $scene_id=str_replace('qrscene_','',$this->message['eventkey']);//扫码关注场景ID
             $fans = mc_fetch($this->message['from']);
             if (empty($fans['nickname']) || empty($fans['avatar'])){
                        $openid = $this->message['from'];
                        $ACCESS_TOKEN = $this->getAccessToken();
                        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
                        load()->func('communication');
                        $json = ihttp_get($url);
                        $userInfo = @json_decode($json['content'], true);
                        $fans['nickname'] = $userInfo['nickname'];
                        $fans['avatar'] = $userInfo['headimgurl'];
                        $fans['province'] = $userInfo['province'];
                        $fans['city'] = $userInfo['city'];
                        //mc_update($this->message['from'],array('nickname'=>$mc['nickname'],'avatar'=>$mc['avatar']));
                    }
            if ($this->message['event'] == 'subscribe') {  
              $hmember=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and sceneid=:sceneid", array(':weid' => $_W['uniacid'],':sceneid'=>$scene_id));//事件所有者
              $member=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and from_user=:from_user", array(':weid' => $_W['uniacid'],':from_user'=>$this->message['from']));//当前用户信息
              //if(empty($member)){
               // exit;//用户不存在退出
             // }
           //服务号地区限制
           $cfg=$this->module['config'];    
           /*
           if($cfg['locationtype']==1 || $cfg['locationtype']==2 || $cfg['locationtype']==0){             
                 $user = mc_fetch($this->message['from']);
                 $city=$user['residecity'];
                 $pos = stripos($cfg['city'],$city);
                 if ($pos === false) {
                     $dqurl="<a href='".$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('fwdiqu',array('scene_id'=>$scene_id,'uid'=>$fans['uid'],'from_user'=>$this->message['from'])))."'>点击这里</a>";
                     $dqmsg="次活动只针对【".$cfg['city']."】微信用户开放\n\n当前地区为【".$city."】\n\n如果你是该地区的用户，".$dqurl."验证\n\n如果不处于此地区，暂时不能参与活动，感谢您的支持！";
                     $this->postText($this->message['from'],$dqmsg);
                     exit; 
                 }
           }//地区结束*/


              if (empty($member)){
                    pdo_insert($this->modulename."_share",
                            array(
                                    'openid'=>$fans['uid'],
                                    'nickname'=>$fans['nickname'],
                                    'avatar'=>$fans['avatar'],
                                    'pid'=>$poster['id'],
                                    'createtime'=>time(),
                                    'helpid'=>$hmember['openid'],
                                    'weid'=>$_W['uniacid'],
                                    'score'=>$poster['score'],
                                    'cscore'=>$poster['cscore'],
                                    'pscore'=>$poster['pscore'],
                                    'from_user'=>$this->message['from'],
                                    'follow'=>1
                            ));
                    $share['id'] = pdo_insertid();
                    $share = pdo_fetch('select * from '.tablename($this->modulename."_share")." where id='{$share['id']}'");

                    if($poster['kdtype']==1){//开启肯定好友
                       if(!empty($hmember['from_user'])){
                         $mcsj = mc_fetch($hmember['from_user']);
                         $msgsj="您已通过「".$mcsj['nickname']."」，成功关注，点击下方\n\n「菜单-领取奖励」\n\n为好友加分";
                       }else{
                         $msgsj='您需要点击「领取奖励」才能得到积分哦!';
                       }
                       $this->postText($this->message['from'],$msgsj);
                       exit;
                    }
                    //得积分开始
                    if($poster['score']>0 || $poster['scorehb']>0){
                      $info1=str_replace('#昵称#',$fans['nickname'], $poster['ftips']);
                      $info1=str_replace('#积分#',$poster['score'], $info1);
                      $info1=str_replace('#元#',$poster['scorehb'], $info1);
                      if($poster['score']){mc_credit_update($share['openid'],'credit1',$poster['score'],array($share['openid'],'关注送积分'));}
                      if($poster['scorehb']){mc_credit_update($share['openid'],'credit2',$poster['scorehb'],array($share['openid'],'关注送余额'));}                      
                      $this->postText($this->message['from'],$info1);
                    }
                    
                    if($poster['cscore']>0 || $poster['cscorehb']>0){
                      if($hmember['status']==1){
                        exit;
                      }
                      $info2=str_replace('#昵称#',$fans['nickname'], $poster['utips']);
                      $info2=str_replace('#积分#',$poster['cscore'], $info2);
                      $info2=str_replace('#元#',$poster['cscorehb'], $info2);
                      if($poster['cscore']){mc_credit_update($hmember['openid'],'credit1',$poster['cscore'],array($hmember['openid'],'2级推广奖励'));}
                      if($poster['cscorehb']){mc_credit_update($hmember['openid'],'credit2',$poster['cscorehb'],array($hmember['openid'],'2级推广奖励'));}                      
                      $this->postText($hmember['from_user'],$info2);
                    }
                    if($poster['pscore']>0 || $poster['pscorehb']>0){
                      $fmember=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$hmember['helpid']));
                      if($fmember['status']==1){
                        exit;
                      }
                        if($fmember){
                            $info3=str_replace('#昵称#',$fans['nickname'], $poster['utips2']);
                            $info3=str_replace('#积分#',$poster['pscore'], $info3);
                            $info3=str_replace('#元#',$poster['pscorehb'], $info3);
                            if($poster['pscore']){mc_credit_update($fmember['openid'],'credit1',$poster['pscore'],array($fmember['openid'],'3级推广奖励'));}
                            if($poster['pscorehb']){mc_credit_update($fmember['openid'],'credit2',$poster['pscorehb'],array($fmember['openid'],'3级推广奖励'));}        
                            $this->postText($fmember['from_user'],$info3);   
                        }
                    }
                   
                }else{
                  $this->postText($this->message['from'],'亲，您已经是粉丝了，快去生成海报赚取奖励吧');  
                }
               
              return $this->PostNews($poster,$fans['nickname']);//关注推送图文
            }
            if ($this->message['event'] == 'SCAN' and $this->message['event'] <> 'subscribe') {
                $cfg=$this->module['config'];
                if($cfg['hztype']<>''){
                  $jflx=$cfg['hztype'];
                }else{
                  $jflx="积分";
                }
               $msg1=$fans['nickname']."你已经是【".$_W['account']['name']."】的粉丝了，不用再扫了哦。\n\n你当前有".$fans['credit1']."".$jflx."";
               $this->postText($this->message['from'],$msg1);
               return $this->PostNews($poster,$fans['nickname']);//推送图文
            }
         
         }
         

         //输入关键词查询
         if($this->message['msgtype'] == 'text' and $this->message['event'] <> 'CLICK' and $this->message['event'] <> 'subscribe' and $this->message['event'] <> 'SCAN' and $this->message['content']<>$poster['kword']){


             $arr=strstr($this->message['content'],"找");
             if($arr!==false){
                 $cfg = $this->module['config']; 
                 $str=str_replace("找","",$this->message['content']);             
                 file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($arr),FILE_APPEND);
                 //$where =" and title like '%{$arr}%'";

                 $arr=explode(" ",$str);
                 foreach($arr as $v){
                     if (empty($v)) continue;
                    $where.=" and title like '%{$v}%'";
                 }

                 //$where =" and title like '%杜蕾斯%' and title like '%小%'";
                 if(empty($cfg['ttsum'])){
                    $sum=6;
                 }else{
                    $sum=$cfg['ttsum'];
                 }
                 $zdgoods = pdo_fetchall("SELECT id,title,pic_url,price FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$_W['uniacid']}' {$where}  order by rand() limit {$sum}");
                 
                 return $this->postgoods($zdgoods,$this->message['content']);      
             
               
             }

             
         
         }
         //关注键结束
         
         if($this->message['msgtype'] == 'text' || $this->message['event'] == 'CLICK' and $this->message['event'] <> 'subscribe' and $this->message['event'] <> 'SCAN' and $this->message['content']==$poster['kword']){

             




 
           //地区限制
           $cfg=$this->module['config'];
           
           if($cfg['locationtype']==1 || $cfg['locationtype']==2 || $cfg['locationtype']==0){
                 $user = mc_fetch($this->message['from']);
                 $city=$user['residecity'];
                 $pos = stripos($cfg['city'],$city);
                 if ($pos === false) {
                 $dqurl="<a href='".$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('diqu',array('uid'=>$fans['uid'])))."'>点击这里</a>";
                 $dqmsg="本次活动只针对【".$cfg['city']."】微信用户开放\n\n当前地区为【".$city."】\n\n如果你是该地区的用户，".$dqurl."验证\n\n如果不处于此地区，暂时不能参与活动，感谢您的支持！";
                 $this->postText($this->message['from'],$dqmsg);
                 exit;                   
                 }                 
            }
            $rid = $this->rule;

            $poster = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_poster')." WHERE weid = :weid and rid=:rid", array(':weid' => $_W['uniacid'],':rid'=>$rid)); 
            
            if(!empty($cfg['hbsctime'])){
                $share = pdo_fetch('select * from '.tablename($this->modulename."_share")." where from_user='{$this->message['from']}' and pid='{$poster['id']}' ");
                if ($share['updatetime'] > 0 && (time() - $share['updatetime']) < $cfg['hbsctime']){//一分钟内
                    if(!empty($cfg['hbcsmsg'])){
                       $this->postText($this->message['from'],$cfg['hbcsmsg']);
                    }                       
                        return '';
                        exit();
                } 
            }
            

            $img = $this->createPoster($fans,$poster);
            $media_id = $this->uploadImage($img);   
            
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($img),FILE_APPEND);
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($this->message['time']),FILE_APPEND);
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($this->message['type']),FILE_APPEND);
                      

           if($poster['winfo1']){
            $info=str_replace('#时间#',date('Y-m-d H:i',time()+30*24*3600),$poster['winfo1']);
            //$this->respText($info);
            $this->postText($this->message['from'],$info);
             }
           if ($poster['winfo2']){
                $hbshare = pdo_fetch('select * from '.tablename($this->modulename."_share")." where openid='{$fans['uid']}' "); 
                $url="<a href='".$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('hbshare',array('type' => $poster['type'],'id'=>$hbshare['id'])))."'>查看你的专属二维码</a>";
                $msg2 = $poster['winfo2'];
                $msg2=str_replace('#二维码链接#',$url, $msg2);
                if ($poster['rtype'] && $poster['type'] == 2);
                $this->postText($this->message['from'],$msg2);
            }

            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($this->message),FILE_APPEND);          
            //$this->sendImage($this->message['from'],$media_id);
            if ($this->message['checked'] == 'checked'){
					$this->sendImage($this->message['from'],$media_id);
					return '';
				}else return $this->respImage($media_id);
				exit;  
         }
         
	}

    public function addtbgoods($data) {
        if(empty($data['num_iid'])){
          Return '';
        }
        $go = pdo_fetch("SELECT id FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$data['weid']}' and  num_iid='{$data['num_iid']}'");
        if(empty($go)){
             file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode("aaa"),FILE_APPEND);   
          pdo_insert($this->modulename."_tbgoods",$data);
        }else{
            file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode("bbb"),FILE_APPEND);        
          pdo_update($this->modulename."_tbgoods", $data, array('weid'=>$data['weid'],'num_iid' => $data['num_iid']));
        }          
    }



    public function sendImage($openid, $media_id) {
	    $data = array(
	      "touser"=>$openid,
	      "msgtype"=>"image",
	      "image"=>array("media_id"=>$media_id));
	    $ret = $this->postRes($this->getAccessToken(), json_encode($data));
	    return $ret;
	  }

    

    //根据IP获取城市名
    function GetIpLookup($ip = ''){  
        if(empty($ip)){  
            $ip = GetIp();  
        }  
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
        if(empty($res)){ return false; }  
        $jsonMatches = array();  
        preg_match('#\{.+?\}#', $res, $jsonMatches);  
        if(!isset($jsonMatches[0])){ return false; }  
        $json = json_decode($jsonMatches[0], true);  
        if(isset($json['ret']) && $json['ret'] == 1){  
            $json['ip'] = $ip;  
            unset($json['ret']);  
        }else{  
            return false;  
        }  
        return $json;  
    }

    private function uploadImage($img) {
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->getAccessToken()."&type=image";
		$post = array('media' => '@' . $img);
		load()->func('communication');
		$ret = ihttp_request($url, $post);
		$content = @json_decode($ret['content'], true);
		return $content['media_id'];
	}

    private $sceneid = 0;
	private $Qrcode = "/addons/tiger_taoke/qrcode/mposter#sid#.jpg";
	private function createPoster($fans,$poster){
		global $_W;
		$bg = $poster['bg'];
		$pid = $poster['id'];
		$share = pdo_fetch('select * from '.tablename($this->modulename."_share")." where openid='{$fans['uid']}' limit 1");
        if(empty($fans['uid'])){
               $this->postText($this->message['from'],'对不起，您需要重新关注公众号才能参加活动！');
               exit;
        }

         //有海报就不重复生成
           // $mpimg = IA_ROOT ."/addons/tiger_taoke/qrcode/mposter".$share['id'].".jpg";
         //   if(file_exists($mpimg)){
               //die('存在');
               //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".$mpimg,FILE_APPEND);
         //      return $mpimg;
         //      exit;
         //   }
        //
		if (empty($share)){
			pdo_insert($this->modulename."_share",
					array(
							'openid'=>$fans['uid'],
							'nickname'=>$fans['nickname'],
							'avatar'=>$fans['avatar'],
							'pid'=>$poster['id'],
                            'updatetime'=>time(),
							'createtime'=>time(),
							'parentid'=>0,
							'weid'=>$_W['uniacid'],
							'score'=>$poster['score'],
							'cscore'=>$poster['cscore'],
							'pscore'=>$poster['pscore'],
                            'from_user'=>$this->message['from'],
                            'follow'=>1
					));
			$share['id'] = pdo_insertid();
			$share = pdo_fetch('select * from '.tablename($this->modulename."_share")." where id='{$share['id']}'");
		}else pdo_update($this->modulename."_share",array('updatetime'=>time()),array('id'=>$share['id']));

		$qrcode = str_replace('#sid#',$share['id'],IA_ROOT .$this->Qrcode);
		$data = json_decode(str_replace('&quot;', "'", $poster['data']), true);
		include 'func.php';
		set_time_limit(0);
		@ini_set('memory_limit', '256M');
		$size = getimagesize(tomedia($bg));
		$target = imagecreatetruecolor($size[0], $size[1]);
		$bg = imagecreates(tomedia($bg));
		imagecopy($target, $bg, 0, 0, 0, 0,$size[0], $size[1]);
		imagedestroy($bg);
		
		foreach ($data as $value) {
			$value = trimPx($value);
			if ($value['type'] == 'qr'){
                if($poster['type']==2){
                  $url = $this->getQR($fans,$poster,$share['id']);
                }elseif($poster['type']==3){
                  $url=$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('sharetz',array('weid' =>$_W['uniacid'],'uid'=>$fans['uid'])));
                }
				if (!empty($url)){
					$img = IA_ROOT ."/addons/tiger_taoke/temp_qrcode.png";
					include "phpqrcode.php";
					$errorCorrectionLevel = "L";
					$matrixPointSize = "4";
					QRcode::png($url, $img, $errorCorrectionLevel, $matrixPointSize,2);
					mergeImage($target,$img,array('left'=>$value['left'],'top'=>$value['top'],'width'=>$value['width'],'height'=>$value['height']));
					@unlink($img);
				}
			}elseif ($value['type'] == 'img'){
				$img = saveImage($fans['avatar']);
				mergeImage($target,$img,array('left'=>$value['left'],'top'=>$value['top'],'width'=>$value['width'],'height'=>$value['height']));
				@unlink($img);
			}elseif ($value['type'] == 'name') mergeText($this->modulename,$target,$fans['nickname'],array('size'=>$value['size'],'color'=>$value['color'],'left'=>$value['left'],'top'=>$value['top']),$poster);
		}
		imagejpeg($target, $qrcode);
		imagedestroy($target);
		return $qrcode;
	}


    private function getQR($fans,$poster,$sid){
		global $_W;
		$pid = $poster['id'];
		if (empty($this->sceneid)){
			$share = pdo_fetch('select * from '.tablename($this->modulename."_share")." where id='{$sid}'");
			if (!empty($share['url'])){
				$out = false;  
				if ($poster['rtype']){
					$qrcode = pdo_fetch('select * from '.tablename('qrcode')
						." where uniacid='{$_W['uniacid']}' and qrcid='{$share['sceneid']}' "
						." and name='{$poster['title']}' and ticket='{$share['ticketid']}' and url='{$share['url']}'");
					if($qrcode['createtime'] + $qrcode['expire'] < time()){
						pdo_delete('qrcode',array('id'=>$qrcode['id']));
						$out = true;
					}
				}
				if (!$out){
					$this->sceneid = $share['sceneid'];
					return $share['url'];
				}
			}
			
			$this->sceneid = pdo_fetchcolumn('select sceneid from '.tablename($this->modulename."_share")." where weid='{$_W['uniacid']}' order by sceneid desc limit 1");
			if (empty($this->sceneid)) $this->sceneid = 10000001;
			else $this->sceneid++;
			$barcode['action_info']['scene']['scene_id'] = $this->sceneid;
			
			load()->model('account');
			$acid = pdo_fetchcolumn('select acid from '.tablename('account')." where uniacid={$_W['uniacid']}");
			$uniacccount = WeAccount::create($acid);
			$time = 0;
			if ($poster['rtype']){
				$barcode['action_name'] = 'QR_SCENE';
				$barcode['expire_seconds'] = 30*24*3600;
				$res = $uniacccount->barCodeCreateDisposable($barcode);
				$time = $barcode['expire_seconds'];
			}else{
				$barcode['action_name'] = 'QR_LIMIT_SCENE';
				$res = $uniacccount->barCodeCreateFixed($barcode);
			}
            $rid = $this->rule;
            $sql = "SELECT * FROM " . tablename('rule_keyword') . " WHERE `rid`=:rid LIMIT 1";
            $row = pdo_fetch($sql, array(':rid' => $rid));
			
			pdo_insert('qrcode',
							array('uniacid'=>$_W['uniacid'],
                            'acid'=>$acid,
                            'qrcid'=>$this->sceneid,
                            'name'=>$poster['title'],
                            'keyword'=>$row['content'],
                            'model'=>1,
                            'ticket'=>$res['ticket'],
                            'expire'=>$time,
                            'createtime'=>time(),
                            'status'=>1,
                            'url'=>$res['url']
							)
			);
			
			pdo_update($this->modulename."_share",array('sceneid'=>$this->sceneid,'ticketid'=>$res['ticket'],'url'=>$res['url'],'nickname'=>$fans['nickname'],'avatar'=>$fans['avatar']),array('id'=>$sid));
			return $res['url'];
		}
		
	}

    public function geturl($str) {//获取链接
        $exp = explode('http', $str);
        //$url = 'http' . trim($exp[1]) . '中国';
        $url = 'http' . trim($exp[1]) . ' ';
        //preg_match('/[\x{4e00}-\x{9fa5}]/u', $url, $matches, PREG_OFFSET_CAPTURE);
        preg_match('/[\s]/u', $url, $matches, PREG_OFFSET_CAPTURE); 
        $url = substr($url, 0, $matches[0][1]);
        if($url=='http'){
          Return '';
        }else{
          return $url;
        }        
    }

    public function myisexists($url) {//判断是不是淘宝的地址
//       if (stripos($url,'mashort.cn')!==false) {
//          return 1;
//       }
//       if (stripos($url,'e22a.com')!==false) {
//          return 1;
//       }
//       if (stripos($url,'sjtm.me')!==false) {
//          return 1;
//       }
//       if (stripos($url,'laiwang.com')!==false) {
//          return 1;
//       }
       if (stripos($url,'taobao.com')!==false) {
          return 2;
       }elseif(stripos($url,'tmall.com')!==false) {
          return 2;
       }elseif(stripos($url,'tmall.hk')!==false) {
          return 2;
       }else{
          return 1;
       }
       return 0;
    }

    public function mygetID($url) {//获取链接商品ID
       if (preg_match("/[\?&]id=(\d+)/",$url,$match)) {
          return $match[1];
       } else {
          return '';
       }
    }

    public function hqgoodsid($url) {//e22a获取ID
        //http://item.taobao.com/item.htm?id=540728402188&from=tbkfenxiangyoushang&fromScene=100&publishUserId
        //'http://item.taobao.com/item.htm?ut_sk=1.V5/73bfSri4DABBUs3mInifZ_21380790_1482201165164.Copy.1&id=23246340317&sourceType=item&
        //如果是e22a的域名就用这个获取商品ID
        //$str = $this->utf8_gbk(file_get_contents($url));
        $str = file_get_contents($url);     
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n ssss:".json_encode($str),FILE_APPEND);
        //preg_match_all('|url.*&id=([\d]+)&|', $fd,$str);
		$str=str_replace("\"", "", $str);        
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n goodsid:".json_encode($str),FILE_APPEND);
		$goodsid=$this->Text_qzj($str,"?id=","&");
        if(empty($goodsid)){
          $goodsid=$this->Text_qzj($str,"&id=","&");
        }
        if(empty($goodsid)){
           $goodsid=$this->Text_qzj($str,"itemId:",",");
        }
        if(empty($goodsid)){
            $url=$this->Text_qzj($str,"url = '","';");
            $goodsid=$this->Text_qzj($str,"com/i",".htm");
            file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($goodsid),FILE_APPEND);
        }
        
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n goodsid:".json_encode("--------------"),FILE_APPEND);
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n goodsid:".json_encode($goodsid),FILE_APPEND);
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n goodsid:".json_encode("--------------"),FILE_APPEND);
        Return $goodsid;
        

    }

    public function utf8_gbk($Text) {
					return iconv("UTF-8","gbk//TRANSLIT",$Text);
				}

    public  function getyouhui($str){
        preg_match_all('|￥([^￥]+)￥|ism', $str, $matches);
        return $matches[1][0];
    }

    public function getyouhui2($str){
        preg_match_all('|(￥[^￥]+￥)|ism', $str, $matches);
        return $matches[1][0];
    }



    public function Text_qzj($Text,$Front,$behind) {
				//语法：strpos(string,find,start)
				//函数返回字符串在另一个字符串中第一次出现的位置，如果没有找到该字符串，则返回 false。
				//参数描述：
				//string 必需。规定被搜索的字符串。
				//find   必需。规定要查找的字符。
				//start  可选。规定开始搜索的位置。
				
				//语法：string mb_substr($str,$start,$length,$encoding)
				//参数描述：
				//str      被截取的母字符串。
				//start    开始位置。
				//length   返回的字符串的最大长度,如果省略，则截取到str末尾。
				//encoding 参数为字符编码。如果省略，则使用内部字符编码。
					
					$t1 = mb_strpos(".".$Text,$Front);
					if($t1==FALSE){
						return "";
					}else{
						$t1 = $t1-1+strlen($Front);
					}
					$temp = mb_substr($Text,$t1,strlen($Text)-$t1);
					$t2 = mb_strpos($temp,$behind);
					if($t2==FALSE){
						return "";
					}
					return mb_substr($temp,0,$t2);
				}


    public function postText($openid, $text) {
		$post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
		$ret = $this->postRes($this->getAccessToken(), $post);
		return $ret;
	}

    private function postRes($access_token, $data) {
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
		load()->func('communication');
		$ret = ihttp_request($url, $data);
		$content = @json_decode($ret['content'], true);
		return $content['errcode'];
	}

    private function PostNews($poster,$name){
		$stitle = unserialize($poster['stitle']);
		if (!empty($stitle)){
			$thumbs = unserialize($poster['sthumb']);
			$sdesc = unserialize($poster['sdesc']);
			$surl = unserialize($poster['surl']);
			foreach ($stitle as $key => $value) {
				if (empty($value)) continue;
				$response[] = array(
					'title' => str_replace('#昵称#',$name,$value),
					'description' => $sdesc[$key],
					'picurl' => tomedia( $thumbs[$key] ),
					'url' => $this->buildSiteUrl($surl[$key])
				);
			}
			if ($response) return $this->respNews($response);
		}
		return '';
	}


    private function postgoods($goods,$str){
        $cfg = $this->module['config']; 
        $str=str_replace("找","",$str);
        if(!empty($cfg['ttpicurl'])){
            $response[]=array(
                'title' => $cfg['tttitle'],
                'description' =>$cfg['tttitle'],
                'picurl' => tomedia($cfg['ttpicurl']),
                'url' => $this->buildSiteUrl($cfg['tturl'])
            );              
        }
        foreach ($goods as $key => $value) {
            $viewurl=$this->createMobileurl('view',array('id'=>$value['id']));
            
            $response[] = array(
                'title' => "【券后价:".$value['price']."】".$value['title'],
                'description' => $value['title'],
                'picurl' => tomedia($value['pic_url']."_100x100.jpg"),
                'url' => $this->buildSiteUrl($viewurl)
            );
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($response),FILE_APPEND);
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode("-------------------------"),FILE_APPEND);
        }

        $tturl=$this->createMobileurl('index',array('key'=>$str));

        $response[]=array(
                'title' =>'点击查看【更多相关“'.$str.'”的优惠商品】',
                'description' =>'点击查看【更多相关“'.$str.'”优惠商品】',
                'picurl' =>'',
                'url' => $this->buildSiteUrl($tturl)
            ); 
        if ($response) return $this->respNews($response);
		return '';
	}
    


    private function getAccessToken() {
		global $_W;
		load()->model('account');
		$acid = $_W['acid'];
		if (empty($acid)) {
			$acid = $_W['uniacid'];
		}
		$account = WeAccount::create($acid);
		//$token = $account->fetch_available_token();
        $token = $account->getAccessToken();
		return $token;
	}

    public function rhy($quan_id,$num_iid,$pid) {//二合一 鹊桥
        $url="http://uland.taobao.com/coupon/edetail?activityId=".$quan_id."&itemId=".$num_iid."&src=iws_iwstkl&pid=".$pid."&tj1=1";
        Return $url;        
    }
    public function rhydx($quan_id,$num_iid,$pid) {//二合一 定向
        $url="http://uland.taobao.com/coupon/edetail?activityId=".$quan_id."&itemId=".$num_iid."&src=iws_iwstkl&pid=".$pid."&dx=1&tj1=1";
        Return $url;        
    }

    public function tkl($url,$img,$tjcontent) {//淘口令转换
        global $_W, $_GPC;
        
        $cfg = $this->module['config'];
        $appkey=$cfg['tkAppKey'];
        $secret=$cfg['tksecretKey'];
        $c = new TopClient;
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new WirelessShareTpwdCreateRequest;
        $tpwd_param = new IsvTpwdInfo;
        $tpwd_param->ext="{\"xx\":\"xx\"}";
        $tpwd_param->logo=$img;
        $tpwd_param->text=$tjcontent;
        $tpwd_param->url=$url;
        $tpwd_param->user_id=$cfg['tbid'];
        $req->setTpwdParam(json_encode($tpwd_param));
        $resp = $c->execute($req);     
        Return $resp;
    }








}