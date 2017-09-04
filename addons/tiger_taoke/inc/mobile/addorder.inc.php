<?php
global $_W, $_GPC;
        $cfg = $this->module['config'];
        $fans = mc_oauth_userinfo();
        $ajax=$_GPC['ajax'];
        $op=$_GPC['op'];
        $fans = $_W['fans'];
        $orderid=trim($_GPC['code']);
        $dluid=$_GPC['dluid'];//share id

        $member=pdo_fetch("select * from ".tablename($this->modulename."_share")." where weid='{$_W['uniacid']}' and from_user='{$fans['openid']}'");//当前粉丝信息
        

        if($ajax=='ajax'){      
            if(pdo_tableexists('tiger_wxdaili_set')){
               $bl=pdo_fetch("select * from ".tablename('tiger_wxdaili_set')." where weid='{$_W['uniacid']}'");
               if($bl['dlfxtype']==1){
                   if($member['dltype']==1){
                     die(json_encode(array("statusCode"=>100,'msg'=>'对不起!代理不能提交订单!')));  
                   }                  
                }
            }
            
            $order = pdo_fetch("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and orderid='{$orderid}'");
            
            if(empty($order)){
                //查询淘客订单库
                $tkorder = pdo_fetch("select * from ".tablename($this->modulename."_tkorder")." where weid='{$_W['uniacid']}' and orderid='{$orderid}'");
                if(!empty($tkorder)){
                   if($tkorder['orderzt']=='订单失效'){
                      $sh=4;//失效
                      die(json_encode(array("statusCode"=>100,'msg'=>'您提交的订单已退款！')));  
                   }elseif($tkorder['orderzt']=='订单付款'){
                      $sh=3;//已审核
                   }elseif($tkorder['orderzt']=='订单结算'){
                      $sh=1;//待返
                   }
                   //$credit2_zg=$tkorder['xgyg']*$cfg['zgf']/100;

                }else{
                  die(json_encode(array("statusCode"=>100,'msg'=>'您提交的订单暂未更新，请过15分钟后在提交，感谢您的支持！')));  
                  $sh=0;//待审核
                }
                $data=array(
                    'weid'=>$_W['uniacid'],
                    'openid'=>$fans['openid'],
                    'memberid'=>$fans['uid'],
                    'nickname'=>$fans['nickname'],
                    'avatar'=>$fans['avatar'],
                    'orderid'=>$orderid,
                    'sh'=>$sh,
                    'yongjin'=>$tkorder['xgyg'],//佣金
                    'type'=>0,
                    'createtime'=>TIMESTAMP
                );

                if (pdo_insert ( $this->modulename . "_order", $data ) === false) {
					die(json_encode(array("statusCode"=>100,'msg'=>'系统繁忙！')));  
				} else{
                   $member=pdo_fetch("select * from ".tablename($this->modulename."_share")." where weid='{$_W['uniacid']}' and from_user='{$fans['openid']}'");//当前粉丝信息
                   $zgtxmsg=str_replace('#昵称#',$member['nickname'], $cfg['zgtxmsg']);
                   $zgtxmsg=str_replace('#订单号#',$orderid, $zgtxmsg);
                   $this->postText($member['from_user'],$zgtxmsg);//自购提示
                   if(!empty($member['helpid'])){//一级
                      
                      if(pdo_tableexists('tiger_wxdaili_set')){//是否开启代理订单不返给二级
                          $bl=pdo_fetch("select * from ".tablename('tiger_wxdaili_set')." where weid='{$_W['uniacid']}'");
                          if(empty($bl['dlyjfltype'])){
                             $tgw=pdo_fetch("select * from ".tablename($this->modulename."_share")." where weid='{$_W['uniacid']}' and tgwid='{$tkorder['tgwid']}'");//有没有代理推广位
                                   if(!empty($tgw)){
                                         if(!empty($cfg['khgetorder'])){//管理员订单提交提醒
                                            $mbid=$cfg['khgetorder'];
                                            $mb=pdo_fetch("select * from ".tablename($this->modulename."_mobanmsg")." where weid='{$_W['uniacid']}' and id='{$mbid}'");
                                            //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n 1old:".json_encode($orderid),FILE_APPEND);
                                            $msg=$this->mbmsg($cfg['glyopenid'],$mb,$mb['mbid'],$mb['turl'],$fans,$orderid);                  
                                         }
                                         die(json_encode(array("statusCode"=>200,'msg'=>'')));//有代理的推广位就提交成功
                                   }
                          }
                      }
                       



                       $yjmember=pdo_fetch("select * from ".tablename($this->modulename."_share")." where weid='{$_W['uniacid']}' and openid='{$member['helpid']}' order by id desc");
                       $yjtxmsg=str_replace('#昵称#',$member['nickname'], $cfg['yjtxmsg']);
                       $yjtxmsg=str_replace('#订单号#',$orderid, $yjtxmsg);
                       $this->postText($yjmember['from_user'],$yjtxmsg);//一级提示
                       //插入一级订单
                           if(!empty($cfg['yjf'])){
                               //$credit2_yj=$tkorder['xgyg']*$cfg['yjf']/100;
                               //$ejprice=$cfg['yjf']*$credit2_yj/100;
                                   $data2=array(
                                        'weid'=>$_W['uniacid'],
                                        'openid'=>$yjmember['from_user'],
                                        'memberid'=>$yjmember['openid'],//用户UID
                                        'nickname'=>$yjmember['nickname'],
                                        'avatar'=>$yjmember['avatar'],
                                            'jlnickname'=>$member['nickname'],
                                            'jlavatar'=>$member['avatar'],
                                        'orderid'=>$orderid,
                                        'yongjin'=>$tkorder['xgyg'],
                                        'type'=>1,
                                        'sh'=>$sh,
                                        'createtime'=>TIMESTAMP
                                    );
                                    $order = pdo_fetchall("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and type=1 and orderid={$orderid}");
                                    if(empty($order)){
                                        pdo_insert ( $this->modulename . "_order", $data2 );//添加一级订单
                                    }
                                   
                             }
                       //一级订单结束

                       if(!empty($yjmember['helpid'])){//二级
                           $rjmember=pdo_fetch("select * from ".tablename($this->modulename."_share")." where weid='{$_W['uniacid']}' and openid='{$yjmember['helpid']}' order by id desc");
                           $ejtxmsg=str_replace('#昵称#',$member['nickname'], $cfg['ejtxmsg']);
                           $ejtxmsg=str_replace('#订单号#',$orderid, $ejtxmsg);
                           $this->postText($rjmember['from_user'],$ejtxmsg);//二级提示
                           //二级订单添加
                                 if(!empty($cfg['ejf'])){
                                     //$ejfprice=$tkorder['xgyg']*$cfg['ejf']/100;
                                     $data3=array(
                                        'weid'=>$_W['uniacid'],
                                        'openid'=>$rjmember['from_user'],
                                        'memberid'=>$rjmember['openid'],//用户UID
                                        'nickname'=>$rjmember['nickname'],
                                        'avatar'=>$rjmember['avatar'],
                                            'jlnickname'=>$member['nickname'],
                                            'jlavatar'=>$member['avatar'],
                                        'orderid'=>$orderid,
                                        'yongjin'=>$tkorder['xgyg'],
                                        'type'=>2,
                                         'sh'=>$sh,
                                        'createtime'=>TIMESTAMP
                                    );
                                    $order = pdo_fetchall("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and type=2 and orderid={$orderid}");
                                    if(empty($order)){
                                        pdo_insert ( $this->modulename . "_order", $data3 );//添加二级订单
                                    }
                                 }
                           //二级订单结束


                       }
                   }
                   if(!empty($cfg['khgetorder'])){//管理员订单提交提醒
                        $mbid=$cfg['khgetorder'];
                        $mb=pdo_fetch("select * from ".tablename($this->modulename."_mobanmsg")." where weid='{$_W['uniacid']}' and id='{$mbid}'");
                        //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n 1old:".json_encode($orderid),FILE_APPEND);
                        $msg=$this->mbmsg($cfg['glyopenid'],$mb,$mb['mbid'],$mb['turl'],$fans,$orderid);                  
                    }

					die(json_encode(array("statusCode"=>200,'msg'=>'')));  
				}
            }else{
              die(json_encode(array("statusCode"=>100,'msg'=>'您提交的订单已经存在')));  
            }    
        }
         $dblist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=1  order by px desc");//底部菜单

        include $this->template ( 'user/addorder' );      