<?php
 global $_W, $_GPC;
        $cfg = $this->module['config'];
        load()->model('mc');
        $fans = mc_oauth_userinfo();
        $fans = $_W['fans'];
        if(empty($fans['openid'])){
            echo '请从微信客户端打开！';
            exit;
         }
        $uid=mc_openid2uid($fans['openid']);
        $op=$_GPC['op'];
        $dluid=$_GPC['dluid'];//share id
        if($op=='qb'){//全部
            $order = pdo_fetchall("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and openid='{$fans['openid']}' order by id desc");
            //echo "<pre>";
            //print_r($order);
            //exit;
            foreach($order as $k=>$v){
                if(empty($uid)){
                  continue;
                }
                
                $tkorder = pdo_fetch("select * from ".tablename($this->modulename."_tkorder")." where weid='{$_W['uniacid']}' and orderid='{$v['orderid']}'");
                
                 
                 if(!empty($tkorder)){
 
                      if($v['sh']==0 || $v['sh']==3 and $tkorder['orderzt']=='订单付款'){
                          pdo_update ( $this->modulename . "_order", array('sh'=>3,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                       }
                       if($tkorder['orderzt']=='订单结算' and $v['sh']==3){
                         //echo $v['orderid'];
                         pdo_update ( $this->modulename . "_order", array('sh'=>1,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                       }
                       if($tkorder['orderzt']=='订单失效'){
                         pdo_update ( $this->modulename . "_order", array('sh'=>4,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                       }

                        if($tkorder['orderzt']=='订单结算'){
                            if($v['sh']==2){
                              continue;
                            }
                            //echo $v['sh'];
                            if($v['sh']<>2){
                                
                               $jstime=$tkorder['jstime']+86400*$cfg['yongjinjs'];//阿里妈妈结算时间+可结算天数
                               if($jstime<time()){//如果达到结算时间，就自动结算                                   
                                   if($cfg['fxtype']==1){//积分
                                       if($v['type']==0){//自购
                                         $credit1_zg=intval($tkorder['xgyg']*$cfg['zgf']/100*$cfg['jfbl']);
                                         if(!empty($credit1_zg)){
                                             if($v['sh']<>2){
                                               mc_credit_update($uid,'credit1',$credit1_zg,array($uid,'自购订单返积分:'.$v['orderid']));
                                             }
                                           pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                                         }
                                       }elseif($v['type']==1){//一级返
                                         $credit1_zg=intval($tkorder['xgyg']*$cfg['yjf']/100*$cfg['jfbl']);
                                         if(!empty($credit1_zg)){
                                           if($v['sh']<>2){
                                               mc_credit_update($uid,'credit1',$credit1_zg,array($uid,'一级订单返积分:'.$v['orderid']));
                                           }
                                           pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                                         }
                                       }elseif($v['type']==2){//二级返
                                         $credit1_zg=intval($tkorder['xgyg']*$cfg['ejf']/100*$cfg['jfbl']);
                                         if(!empty($credit1_zg)){
                                             if($v['sh']<>2){
                                                mc_credit_update($uid,'credit1',$credit1_zg,array($uid,'二级订单返积分:'.$v['orderid']));
                                             }
                                           pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                                         }
                                       }                                   
                                   }elseif($cfg['fxtype']==2){//余额

                                       
                                       if($v['type']==0){//自购
                                           $credit1_zg=$tkorder['xgyg']*$cfg['zgf']/100;  
                                           $credit1_zg=number_format($credit1_zg, 2, '.', '');
                                           if(!empty($credit1_zg)){
                                               if($v['sh']<>2){
                                                 mc_credit_update($uid,'credit2',$credit1_zg,array($uid,'自购订单返余额:'.$v['orderid']));
                                               }                                
                                               pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                                            }                                         
                                       }elseif($v['type']==1){//一级返
                                           $credit1_zg=$tkorder['xgyg']*$cfg['yjf']/100;  
                                           $credit1_zg=number_format($credit1_zg, 2, '.', '');
                                           if(!empty($credit1_zg)){
                                               if($v['sh']<>2){
                                                 mc_credit_update($uid,'credit2',$credit1_zg,array($uid,'一级订单返余额:'.$v['orderid']));
                                               }
                                               pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                                           } 
                                          
                                       
                                       }elseif($v['type']==2){//二级返
                                           $credit1_zg=$tkorder['xgyg']*$cfg['ejf']/100;  
                                           $credit1_zg=number_format($credit1_zg, 2, '.', '');
                                           if(!empty($credit1_zg)){
                                               if($v['sh']<>2){
                                                 mc_credit_update($uid,'credit2',$credit1_zg,array($uid,'二级订单返余额:'.$v['orderid']));
                                               }
                                               pdo_update ( $this->modulename . "_order", array('sh'=>2,'yongjin'=>$tkorder['xgyg']), array ('id' => $v['id']));
                                           }
                                       }

                                     
                                   }
                                 
                               }
                            }
                          
                        }
                   
                 }
            
            }



        }elseif($op=='df'){//待返
            $order = pdo_fetchall("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and openid='{$fans['openid']}' and sh=1 order by id desc");
        }elseif($op=='yf'){//已返
            $order = pdo_fetchall("select * from ".tablename($this->modulename."_order")." where weid='{$_W['uniacid']}' and openid='{$fans['openid']}' and sh=2  order by id desc");
        }
        $dblist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=1  order by px desc");//底部菜单

        include $this->template ( 'user/orderlist' ); 