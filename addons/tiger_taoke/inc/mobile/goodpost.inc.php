<?php
global $_W, $_GPC;
        load()->model('mc');
        //echo 'aa';
        //echo $_W['fans']['from_user'];
        //exit;
        $cfg=$this->module['config'];   
        if(!$_W['isajax'])die(json_encode(array('success'=>false,'msg'=>'非法提交,只能通过网站提交')));
        //if (checksubmit('submit')){  
        $goods_id = intval($_GPC['goods_id']);
        $type=intval($_GPC['typea']);  
        if (!empty($_GPC['goods_id'])){

            //$fans = fans_search($_W['fans']['from_user'], array('realname', 'mobile', 'residedist', 'alipay','credit1','credit2', 'vip','uniacid','uid'));
            $fans = mc_fetch($_W['fans']['from_user']);

            $share=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$fans['uid']));
            if($share['status']==1){
              die(json_encode(array('success'=>false,'msg'=>"您的帐号怀疑有作弊嫌疑已被系统拉黑，如没有作弊请联系管理帮您解除操作！")));
            }

//            if($cfg['dxtype']==1){//开启短信验证
//                if(empty($share['tel'])){
//                  die(json_encode(array('success'=>false,'msg'=>"您需要先验证通过才能兑换哦！")));
//                  //message('您需要先验证通过才能兑换哦！', $this -> createMobileUrl('reg'), 'error');
//                }              
//            }

            $goods_info = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $daysum= mktime(0,0,0,$m,$d,$y);
            //$daysum=time()-86400;
            $goods_request = pdo_fetch("SELECT count(*) sn FROM " . tablename($this -> table_request) . " WHERE goods_id = $goods_id AND createtime>".$daysum." and weid = '{$_W['weid']}' and from_user = '{$_W['fans']['from_user']}'");
             //die(json_encode(array('success'=>false,'msg'=>$goods_request['sn'])));
            if(!empty($goods_info['day_sum'])){
                if($goods_request['sn']>=$goods_info['day_sum']){
                  die(json_encode(array('success'=>false,'msg'=>"每个用户1天只能兑换".$goods_info['day_sum']."次,\n明天在来兑换吧！")));
                }
            }


            if ($goods_info['amount'] <= 0){
                die(json_encode(array('success'=>false,'msg'=>"商品已经兑空，请重新选择商品！")));
            }


            if (intval($goods_info['vip_require']) > $fans['vip']){
                die(json_encode(array('success'=>false,'msg'=>"您的VIP级别不够，无法参与本项兑换，试试其它的吧。")));
            }
            $min_idle_time = empty($goods_info['min_idle_time']) ? 0 : $goods_info['min_idle_time'] * 60;
            $replicated = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}' AND " . TIMESTAMP . " - createtime < {$min_idle_time}");
            if (!empty($replicated)){
                $last_time = date('H:i:s', $replicated['createtime']);
                 die(json_encode(array('success'=>false,'msg'=>"{$goods_info['min_idle_time']}分钟内不能重复兑换相同物品")));
            }
            if ($goods_info['per_user_limit'] > 0){
                $goods_limit = pdo_fetch("SELECT count(*) as per_user_limit FROM " . tablename($this -> table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}'");

                /*定制功能*/
                    $cfg=$this->module['config']; 
                    if(!empty($cfg['towurl'])){
                      if ($goods_limit['per_user_limit'] >= 1){
                        die(json_encode(array('success'=>8,'towurl'=>$cfg['towurl'],'msg'=>"每个用户只可以兑换一次红包 联系客服获取更多兑换机会!")));
                      }
                    }
                /*定制功能结束*/

                if ($goods_limit['per_user_limit'] >= $goods_info['per_user_limit']){
                    die(json_encode(array('success'=>false,'msg'=>"本商品每个用户最多可兑换" . $goods_info['per_user_limit'] . "件,试试兑换其他奖品吧！")));
                }
            }

            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($fans),FILE_APPEND);

            if ($fans['credit1'] < $goods_info['cost']){
                die(json_encode(array('success'=>false,'msg'=>"积分不足, 请重新选择商品")));
            }
            if (true){

                $data = array('amount' => $goods_info['amount'] - 1);
                pdo_update($this -> table_goods, $data, array('weid' => $_W['weid'], 'goods_id' => $goods_id));
                $data = array('realname' => ("" == $fans['realname'])?$_GPC['realname']:$_W['fans']['nickname'], 'mobile' => ("" == $fans['mobile'])?$_GPC['mobile']:$fans['mobile'], 'residedist' => ("" == $fans['residedist'])?$_GPC['residedist']:$fans['residedist'], 'alipay' => ("" == $fans['alipay'])?$_GPC['alipay']:$fans['alipay'],);
                fans_update($_W['fans']['from_user'], $data);

                $data = array('weid' => $_W['weid'], 'from_user' => $_W['fans']['from_user'], 'from_user_realname' => $_W['fans']['nickname'], 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'residedist' => $_GPC['residedist'], 'alipay' => $_GPC['alipay'], 'note' => $_GPC['note'], 'goods_id' => $goods_id, 'price' => $goods_info['price'], 'cost' => $goods_info['cost'],'image'=>$_GPC['image'], 'createtime' => TIMESTAMP);

                $data['tborder']=$_GPC['tborder'];
                $data['fxprice']=$goods_info['fxprice'];


                if ($goods_info['cost'] > $fans['credit1']){
                    die(json_encode(array('success'=>false,'msg'=>"系统出现未知错误，请重试或与管理员联系")));
                }                
                $kjfabc=$data['cost'];//兑换红包积分
                $kjfabc1=$data['price']*100;//红包金额1元钱

                if($type==7){//微信卡券
                    
                  if(empty($goods_info['cardid'])){
                      die(json_encode(array('success'=>false,'msg'=>"领取卡券失败")));
                  }else{
                     $cardinfo=$this->sendcardpost($_W['openid'],$goods_info['cardid']);
                     if($type==7){
                          $data['status']='done';
                      }
                      pdo_insert($this -> table_request, $data);
                      mc_credit_update($fans['uid'],'credit1',-$kjfabc,array($fans['uid'],'礼品兑换:'.$goods_info['title']));
                     die(json_encode(array('success'=>true,'msg'=>"领取成功，请到公众号界面领取!")));
                  }
                  exit;
                }

                if($type==5 || $type==8){
                    if(($goods_info['price']*100)<100){
                        die(json_encode(array("success"=>4,"msg"=>"最少1元起兑换")));
                    }
                    if(($goods_info['price']*100)>20000){
                        die(json_encode(array("success"=>4,"msg"=>"单次最多不能超过200元红包")));
                    }
                    load()->model('mc');
                    load()->func('logging');
                    load()->model('account');
                                      
                    //$member=pdo_fetch('select * from '.tablename('tiger_taoke_member').' where weid=:weid and id=:id order BY id DESC LIMIT 1',array(':weid'=>$_W['weid'],'id'=>$_GPC['memberid']));

                    $member=pdo_fetch ( 'select * from ' . tablename ( $this->modulename . "_member" ) . " where weid='{$_W['weid']}' and id='{$_GPC['memberid']}' order by id desc" );



                    $set=pdo_fetch('select * from '.tablename('tiger_taoke_set').' where weid=:weid order BY id DESC LIMIT 1',array(':weid'=>$_W['weid']));
                    if(($goods_info['price']*100)>($set['hbsum']*100)){
                      if(!empty($set['hbtext'])){
                        die(json_encode(array("success"=>4,"msg"=>$set['hbtext'])));
                      }                      
                    }
                    //die(json_encode(array("success"=>true,"msg"=>$member['openid'])));
//                    if(!$cfg['mchid']){
//                        die(json_encode(array("success"=>4,"msg"=>"商家未开启微信支付功能,请联系商家开启后申请兑换")));
//                    }    
                    //die(json_encode(array('success'=>true,'msg'=>"在这里了森要要")));
                    //include "wxpay.php";  
                    //exit;
                    if($_W['account']['level']==4){
                       $member['openid']=$_W['openid'];
                    }

                    $dtotal_amount=$kjfabc*1;  
                    $msgs='兑换成功，我们会在24小时之内给你审核发红包的哦，请耐心等待！';//手机动发货
                    if($goods_info['shtype']==1){ //1为自动发送
                        $dmch_billno=random(10). date('Ymd') . random(3);//订单号
                        if($cfg['txtype']==0){
                          $msg=$this->post_txhb($cfg,$member['openid'],$kjfabc1,$desc,$dmch_billno);//现金红包
                        }elseif($cfg['txtype']==1){
                          $msg=$this->post_qyfk($cfg,$member['openid'],$kjfabc1,$desc,$dmch_billno);//企业零钱付款
                        }        
                        $msgs='兑换成功,请到微信窗口查收！';
                        $data['status']='done';//兑换成功
                        if($msg['message']=='success'){
                          pdo_insert($this -> table_request, $data);
                          mc_credit_update($fans['uid'],'credit1',-$kjfabc,array($fans['uid'],'兑换:'.$goods_info['title']));//提现记录

                          $dhdata=array(
                              "uniacid"=>$_W["uniacid"],
                              "dwnick"=>$_W['fans']['nickname'],
                              "dopenid"=>$member['openid'],
                              "dtime"=>time(),
                              "dcredit"=>$kjfabc,
                              "dtotal_amount"=>$kjfabc1,
                              "dmch_billno"=>$dmch_billno,
                              "dissuccess"=>$msg['dissuccess'],
                              "dresult"=>$msg['message']                                
                          );
                          //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($dhdata),FILE_APPEND);
                          pdo_insert($this->modulename."_paylog",$dhdata);  

                          //die(json_encode(array("success"=>1,"msg"=>$msgs)));
                          message($msgs, $this -> createMobileUrl('request')); 
                        }else{
                          //die(json_encode(array("success"=>4,"msg"=>$msg['message'])));
                          message($msg['message'], $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
                        }
                    }else{
                      $msgs='亲！我们会在24小时之内给你审核发红包的哦，请耐心等待！';//手机动发货
                      $data['openid']=$member['openid'];
                      pdo_insert($this -> table_request, $data);
                      mc_credit_update($fans['uid'],'credit1',-$kjfabc,array($fans['uid'],'兑换:'.$goods_info['title']));//提现记录
                      die(json_encode(array("success"=>1,"msg"=>$msgs)));
                    }
                    
                    
                    exit;
                }
                if($type==4){
                  $data['status']='done';
                }                


                $cfg=$this->module['config'];
                pdo_insert($this -> table_request, $data);
                $a=mc_credit_update($fans['uid'],'credit1',-$kjfabc,array($fans['uid'],'礼品兑换:'.$goods_info['title']));
                //die(json_encode(array('success'=>false,'msg'=>$a)));


                die(json_encode(array('success'=>true,'msg'=>"扣除您{$goods_info['cost']}积分。")));
               
            }
        }else{
             die(json_encode(array('success'=>false,'msg'=>"请选择要兑换的商品！")));
        }