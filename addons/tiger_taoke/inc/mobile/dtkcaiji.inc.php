<?php
global $_W, $_GPC;
        $page=$_GPC['page'];
        $cfg = $this->module['config'];
        $dtkAppKey=$cfg['dtkAppKey'];
        if(empty($page)){
          $page=1;
        }

        if(!empty($cfg['gyspsj'])){
          exit;
        }

        $op=$_GPC['op'];
        if($op=='dtkcj'){//软件采集
            $url = "http://api.dataoke.com/index.php?r=Port/index&type=total&appkey={$dtkAppKey}&v=2&page={$page}";
            $content=$this->curl_request($url);     
            $userInfo = @json_decode($content, true);
            $dtklist=$userInfo['result'];

            //print_r($dtklist);
            if($userInfo['data']['total_num']==0){
               //message ( '本页暂无商品可同步', $this->createWebUrl ( 'dtkcaiji' ),'error');
               echo "本页暂无商品可同步";
            }
            
            foreach($dtklist as $k=>$v){
                $fztype=pdo_fetch("select * from ".tablename($this->modulename."_fztype")." where weid='{$_W['uniacid']}' and dtkcid='{$v['Cid']}' order by px desc");
                  //echo '<pre>';
                 // print_r($fztype);
                  //exit;
                if($v['Commission_queqiao']>$v['Commission_jihua']){
                   $yjtype=2;//鹊桥高佣金
                   $yjbl=$v['Commission_queqiao'];//佣金比例
                   $erylj=$this->rhy($v['Quan_id'],$v['GoodsID'],$cfg['qqpid']);              
                }else{
                   $yjtype=1;//普通佣金
                   $yjbl=$v['Commission_jihua'];//佣金比例
                   $erylj=$this->rhy($v['Quan_id'],$v['GoodsID'],$cfg['ptpid']);
                }
                if($v['Commission_queqiao']!='0.00'){//鹊桥
                   $lxtype=2;
                }elseif($v['Commission_jihua']!='0.00'){//定向
                  $lxtype=1;
                }else{
                  $lxtype=0;
                }
                
                // var_dump($taokou);
                // exit;

                $item = array(
                         'weid' => $_W['uniacid'],
                         'type'=>$fztype['id'],
                         'yjtype'=>$yjtype,
                         'lxtype'=>$lxtype,
                         'num_iid'=>$v['GoodsID'],//商品ID
                         'title'=>$v['Title'],//商品名称
                         'tjcontent'=>$v['Introduce'],//推荐内容
                         'pic_url'=>$v['Pic'],//主图地址
                         'item_url'=>$v['ali_click'],//详情页地址
                         'shop_title'=>'',//店铺名称
                         'price'=>$v['Price'],//商品价格,券后价
                         'goods_sale'=>$v['Sales_num'],//月销售
                         'tk_rate'=>$yjbl,//通用佣金比例
                         'yongjin'=>'',//通用佣金
                         'event_zt'=>'',//活动状态event_zt
                         'event_yjbl'=>'',//活动收入比event_yjbl
                         'event_yj'=>'',//活动佣金event_yj
                         'event_start_time'=>'',//活动开始event_start_time
                         'event_end_time'=>'',//活动结束event_end_time
                          'nick'=>'',//卖家旺旺
                          'tk_durl'=>'',//淘客短链接
                          'click_url'=>$v['ali_click'],//淘客长链接
                          //'taokouling'=>$taokou,//淘口令--------------
                          'coupons_total'=>$v['Quan_receive'],//优惠券总量已领取数量
                          'coupons_take'=>$v['Quan_surplus'],//优惠券剩余
                          'coupons_price'=>$v['Quan_price'],//优惠券面额
                          'coupons_start'=>'',//优惠券开始
                          'coupons_end'=>strtotime($v['Quan_time']),//优惠券结束
                          'coupons_url'=>$v['Quan_link'],//优惠券链接
                          'coupons_tkl'=>'',//优惠淘口令
                          'istmall'=>$v['IsTmall'],//'0不是  1是天猫',
                          'dsr'=>$v['Dsr'],//'dsr评分',  
                          'quan_id'=>$v['Quan_id'],//'优惠券ID',  
                          'quan_condition'=>$v['Quan_condition'],//'优惠券使用条件',  
                          'org_price'=>$v['Org_Price'],//'商品原价', 
                          'dingxianurl'=>$v['Jihua_link'],
                          'createtime'=>TIMESTAMP,
                        );
                          
                       $go = pdo_fetch("SELECT id FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$_W['uniacid']}' and  num_iid='{$v['GoodsID']}' ORDER BY px desc");
                        if(empty($go)){
//                          $taokouling=$this->tkl($erylj,$v['Pic'],$v['Introduce']);
//                          $taokou=$taokouling->model;
//                          settype($taokou, 'string');
//                          $item['taokouling']=$taokou;
                          pdo_insert($this->modulename."_tbgoods",$item);
                        }else{
                          pdo_update($this->modulename."_tbgoods", $item, array('weid'=>$_W['uniacid'],'num_iid' => $v['GoodsID']));
                        }  
                         echo "大淘客--第".++$k."条数据采集成功<br>";
                       
            }
            echo "采集成功";
         // message ( '采集成功，查看商品', $this->createWebUrl ( 'tbgoods' ) );
        
        }