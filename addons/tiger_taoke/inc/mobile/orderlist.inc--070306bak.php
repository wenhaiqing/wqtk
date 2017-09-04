<?php
 global $_W, $_GPC;
        $cfg = $this->module['config'];
        load()->model('mc');
        $fans = mc_oauth_userinfo();
        $fans = $_W['fans'];
        $op=$_GPC['op'];
        $dluid=$_GPC['dluid'];//share id
        if($op=='qb'){//全部

            $order = pdo_fetchall("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and openid='{$fans['openid']}' order by id desc");
//
//            $tkorder = pdo_fetchall("select * from ".tablename($this->modulename."_tkorder")." where weid='{$_W['uniacid']}'");
//            //orderzt  订单失效   订单结算   订单付款
//            //srbl   45.5% 佣金比例
//            //fcbl   95%   分成比例 只有 鹊桥的95%其它的100%
//            //xgyg   18.15  效果预估
//            $uid=mc_openid2uid($order['openid']);
//            echo $uid;
//            echo '<pre>';
//            print_r($order);
//            exit;
            
            foreach($order as $k=>$v){
                $tkorder = pdo_fetch("select * from ".tablename($this->modulename."_tkorder")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}'");
                //echo "<br>{$tkorder['orderzt']}";
                if(!empty($tkorder)){
                   if($v['sh']==0 || $v['sh']==3 and $tkorder['orderzt']=='订单付款'){
                      pdo_update ( $this->modulename . "_order", array('sh'=>3,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                   }
                   if($tkorder['orderzt']=='订单结算' and $v['sh']==3){
                       echo $v['orderid'];
                     pdo_update ( $this->modulename . "_order", array('sh'=>1,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                   }
                   if($tkorder['orderzt']=='订单失效'){
                     pdo_update ( $this->modulename . "_order", array('sh'=>4,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                   }
                   if($tkorder['orderzt']=='订单结算'){
                       if($v['sh']<>2){
                          //$jstime=strtotime($tkorder['jstime'])+86400*$cfg['yongjinjs'];//阿里妈妈结算时间+可结算天数
                          $jstime=$tkorder['jstime']+86400*$cfg['yongjinjs'];//阿里妈妈结算时间+可结算天数
                          if($jstime<time()){
                             //如果达到结算要求，就奖励
                             //pdo_update ($this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                             //奖励
                                  if($cfg['fxtype']==1){//积分
                                       $credit1_zg=intval($tkorder['xgyg']*$cfg['zgf']/100*$cfg['jfbl']);
                                       $zgorder = pdo_fetch("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}' and type=0");
                                       if(!empty($zgorder)){
                                           if(!empty($credit1_zg)){
                                               $zgorder = pdo_fetch("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}' and type=0");
                                               $uid=mc_openid2uid($zgorder['openid']);
                                               if(!empty($uid)){
                                                 mc_credit_update($uid,'credit1',$credit1_zg,array($uid,'自购订单返积分:'.$v['orderid']));
                                                 pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $zgorder['id'],'type'=>0));
                                                 //订单到帐提醒自购
                                                 if(!empty($cfg['fsjldsz'])){
                                                      $mbid=$cfg['fsjldsz'];
                                                      $mb=pdo_fetch("select * from ".tablename($this->modulename."_mobanmsg")." where weid='{$_W['uniacid']}' and id='{$mbid}'");
                                                      //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n 1old:".json_encode($orderid),FILE_APPEND);
                                                      $msg=$this->mbmsg($zgorder['openid'],$mb,$mb['mbid'],$mb['turl'],$zgorder,$orderid['orderid']);                  
                                                  }
                                                  //结束
                                               }                                           
                                           }                                         
                                       }
                                       
                                       //找一级订单
                                       $yjorder = pdo_fetch("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}' and type=1");
                                       if(!empty($yjorder)){
                                         $credit1_yj=intval($tkorder['xgyg']*$cfg['yjf']/100*$cfg['jfbl']);
                                         if(!empty($credit1_yj)){
                                           $yjuid=mc_openid2uid($yjorder['openid']);
                                           if(!empty($yjuid)){
                                             mc_credit_update($yjuid,'credit1',$credit1_yj,array($yjuid,'一级订单返积分:'.$v['orderid']));
                                             pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $yjorder['id'],'type'=>1));
                                             //订单到帐提醒一级
                                             if(!empty($cfg['fsjldsz'])){
                                                  $mbid=$cfg['fsjldsz'];
                                                  $mb=pdo_fetch("select * from ".tablename($this->modulename."_mobanmsg")." where weid='{$_W['uniacid']}' and id='{$mbid}'");
                                                  //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n 1old:".json_encode($orderid),FILE_APPEND);
                                                  $msg=$this->mbmsg($yjorder['openid'],$mb,$mb['mbid'],$mb['turl'],$yjorder,$orderid['orderid']);                  
                                              }
                                              //结束

                                           }                                           
                                         }
                                         //找二级
                                         $ejorder = pdo_fetch("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}' and type=2");
                                         $credit1_ejf=intval($tkorder['xgyg']*$cfg['ejf']/100*$cfg['jfbl']);
                                         if(!empty($credit1_ejf)){
                                           $ejuid=mc_openid2uid($ejorder['openid']);
                                           if(!empty($ejuid)){
                                             mc_credit_update($ejuid,'credit1',$credit1_ejf,array($ejuid,'二级订单返积分:'.$order['orderid']));
                                             pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $ejorder['id'],'type'=>2));
                                             //订单到帐提醒二级
                                                 if(!empty($cfg['fsjldsz'])){
                                                      $mbid=$cfg['fsjldsz'];
                                                      $mb=pdo_fetch("select * from ".tablename($this->modulename."_mobanmsg")." where weid='{$_W['uniacid']}' and id='{$mbid}'");
                                                      //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n 1old:".json_encode($orderid),FILE_APPEND);
                                                      $msg=$this->mbmsg($ejorder['openid'],$mb,$mb['mbid'],$mb['turl'],$ejorder,$ejorder['orderid']);                  
                                                  }
                                             //结束
                                           }                                           
                                         }
                                       }

                                  
                                  }elseif($cfg['fxtype']==2){//余额
                                      $credit1_zg=$tkorder['xgyg']*$cfg['zgf']/100;  
                                      $zgorder = pdo_fetch("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}' and type=0");
                                      if(!empty($zgorder)){
                                          if(!empty($credit1_zg)){
                                               $zgorder = pdo_fetch("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}' and type=0");
                                               $uid=mc_openid2uid($zgorder['openid']);
                                               if(!empty($uid)){
                                                 mc_credit_update($uid,'credit2',$credit1_zg,array($uid,'自购订单返余额:'.$v['orderid']));
                                                 pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $zgorder['id'],'type'=>0));
                                                 //订单到帐提醒自购
                                                 if(!empty($cfg['fsjldsz'])){
                                                      $mbid=$cfg['fsjldsz'];
                                                      $mb=pdo_fetch("select * from ".tablename($this->modulename."_mobanmsg")." where weid='{$_W['uniacid']}' and id='{$mbid}'");
                                                      //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n 1old:".json_encode($orderid),FILE_APPEND);
                                                      $msg=$this->mbmsg($zgorder['openid'],$mb,$mb['mbid'],$mb['turl'],$zgorder,$orderid['orderid']);                  
                                                  }
                                                  //结束
                                               }
                                           }                                        
                                      }
                                       
                                       //找一级订单
                                       $yjorder = pdo_fetch("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}' and type=1");
                                       if(!empty($yjorder)){
                                         $credit1_yj=$tkorder['xgyg']*$cfg['yjf']/100;
                                         if(!empty($credit1_yj)){
                                           $yjuid=mc_openid2uid($yjorder['openid']);
                                           if(!empty($yjuid)){
                                              mc_credit_update($yjuid,'credit2',$credit1_yj,array($yjuid,'一级订单返余额:'.$v['orderid']));
                                              pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $yjorder['id'],'type'=>1));
                                              //订单到帐提醒一级
                                             if(!empty($cfg['fsjldsz'])){
                                                  $mbid=$cfg['fsjldsz'];
                                                  $mb=pdo_fetch("select * from ".tablename($this->modulename."_mobanmsg")." where weid='{$_W['uniacid']}' and id='{$mbid}'");
                                                  //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n 1old:".json_encode($orderid),FILE_APPEND);
                                                  $msg=$this->mbmsg($yjorder['openid'],$mb,$mb['mbid'],$mb['turl'],$yjorder,$orderid['orderid']);                  
                                              }
                                              //结束
                                           }                                           
                                         }
                                         //找二级
                                         $ejorder = pdo_fetch("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}' and type=2");
                                         $credit1_ejf=$tkorder['xgyg']*$cfg['ejf']/100;
                                         if(!empty($credit1_ejf)){
                                           $ejuid=mc_openid2uid($ejorder['openid']);
                                           if(!empty($ejuid)){
                                             mc_credit_update($ejuid,'credit2',$credit1_ejf,array($ejuid,'二级订单返余额:'.$order['orderid']));
                                             pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $ejorder['id'],'type'=>2));
                                             //订单到帐提醒二级
                                                 if(!empty($cfg['fsjldsz'])){
                                                      $mbid=$cfg['fsjldsz'];
                                                      $mb=pdo_fetch("select * from ".tablename($this->modulename."_mobanmsg")." where weid='{$_W['uniacid']}' and id='{$mbid}'");
                                                      //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n 1old:".json_encode($orderid),FILE_APPEND);
                                                      $msg=$this->mbmsg($ejorder['openid'],$mb,$mb['mbid'],$mb['turl'],$ejorder,$ejorder['orderid']);                  
                                                  }
                                             //结束
                                           }
                                           
                                         }
                                       }
                                  
                                  }
                             //奖励结束
                          }
                       }
                   }
                }else{
                   pdo_update ( $this->modulename . "_order", array('sh'=>0,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                }
            }








        }elseif($op=='df'){//待返
            $order = pdo_fetchall("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and openid='{$fans['openid']}' and sh=1 order by id desc");
        }elseif($op=='yf'){//已返
            $order = pdo_fetchall("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and openid='{$fans['openid']}' and sh=2  order by id desc");
        }
        $dblist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=1  order by px desc");//底部菜单

        include $this->template ( 'user/orderlist' ); 