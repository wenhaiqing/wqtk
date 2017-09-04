<?php
 global $_W,$_GPC;

        load ()->func ( 'tpl' );
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $fzlist = pdo_fetchall("SELECT * FROM " . tablename($this->modulename."_fztype") . " WHERE weid = '{$_W['uniacid']}'  ORDER BY px desc");
        $ztlist = pdo_fetchall("SELECT * FROM " . tablename($this->modulename."_zttype") . " WHERE weid = '{$_W['uniacid']}'  ORDER BY px desc");
        $time24=time()-86400;   
        $qfsum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('stat_msg_history')." where uniacid='{$_W['uniacid']}' and createtime>{$time24} order by id desc");
        if ($operation == 'post'){
            $id = intval($_GPC['id']);
            if (!empty($id)){
                $item = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_tbgoods") . " WHERE id = :id" , array(':id' => $id));
                if (empty($item)){
                    message('抱歉，兑换商品不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')){
                if (empty($_GPC['title'])){
                    message('请输入商品名称！');
                }
                if (empty($_GPC['num_iid'])){
                    message('请输入商品ID！');
                }
                if (empty($_GPC['quan_id'])){
                    message('请输入优惠券ID！');
                }

                //echo strtotime($_GPC['coupons_end']);
                //echo "<pre>";
                //print_r($_GPC);
                //exit;
                $data = array(
                    'weid' => $_W['weid'], 
                    'title' => $_GPC['title'], 
                    'px' => $_GPC['px'], 
                    'tjcontent' => $_GPC['tjcontent'], 
                    'tj' => $_GPC['tj'], 
                    'zt' => $_GPC['zt'], 
                    'zd' => $_GPC['zd'],                    
                    'yjtype'=> $_GPC['yjtype'], 
                    'type' => $_GPC['type'], 
                    'num_iid' => $_GPC['num_iid'], 
                    'quan_id' => $_GPC['quan_id'],                     
                    'pic_url' => $_GPC['pic_url'], 
                    //'small_images' => $_GPC['small_images'], 
                    //'item_url' => $_GPC['item_url'], 
                    ///'shop_title' => $_GPC['shop_title'], 
                    'org_price' => $_GPC['org_price'], 
                    'price' => $_GPC['price'], 
                    'tk_rate' => $_GPC['tk_rate'], 
                    'yongjin' => $_GPC['yongjin'], 
                    'goods_sale' => $_GPC['goods_sale'], 
                    //'nick' => $_GPC['nick'], 
                    //'tk_durl' => $_GPC['tk_durl'], 
                    'click_url' => $_GPC['click_url'], 
                    'taokouling' => $_GPC['taokouling'], 
                    //'coupons_total' => $_GPC['coupons_total'], 
                    //'coupons_take' => $_GPC['coupons_take'], 
                    'coupons_price' => $_GPC['coupons_price'], 
                    //'coupons_start' => strtotime($_GPC['coupons_start']), 
                    'coupons_end' => strtotime($_GPC['coupons_end']), 
                    'coupons_url' => $_GPC['coupons_url'], 
                    'coupons_tkl' => $_GPC['coupons_tkl'], 
                    //'provcity' => $_GPC['provcity'], 
                    //'event_end_time' => $_GPC['event_end_time'], 
                    //'event_start_time' => $_GPC['event_start_time'], 
                    'uptime' => TIMESTAMP, 
                    //'hot' => $_GPC['hot'], 
                    //'hotcolor' => $_GPC['hotcolor'], 
                    //'starttime' => strtotime($_GPC ['starttime']),
                    //'endtime' => strtotime($_GPC ['endtime']),
                    'createtime' => TIMESTAMP);               
                if (!empty($id)){
                    pdo_update($this->modulename."_tbgoods", $data, array('id' => $id));
                }else{
                    pdo_insert($this->modulename."_tbgoods", $data);
                }
                message('商品更新成功！', $this -> createWebUrl('tbgoods', array('op' => 'display')), 'success');
            }
        }else if ($operation == 'delete'){
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename."_tbgoods") . " WHERE id = :id", array(':id' => $id));
            if (empty($row)){
                message('抱歉，商品' . $id . '不存在或是已经被删除！');
            }
            pdo_delete($this->modulename."_tbgoods", array('id' => $id));
            message('删除成功！', referer(), 'success');
        }else if ($operation == 'display'){
           
            if (checksubmit('submit')){
               foreach ($_GPC['id'] as $id){
                    $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                     pdo_delete($this->modulename."_tbgoods", array('id' => $id));
                }
              message('批量删除成功', referer(), 'success');
            }
            if(checksubmit('submitzd')){//设置置顶
              if(!$_GPC['id']){
                message('请选择秒杀商品', referer(), 'error');
              }
              foreach ($_GPC['id'] as $id){
                    $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                    pdo_update($this->modulename."_tbgoods",array('zd'=>1), array('id' => $id));
                }
                message('批量秒杀设置成功', referer(), 'success');
            }
            if(checksubmit('submitrq')){//设置人气
              if(!$_GPC['id']){
                message('请选择人气商品', referer(), 'error');
              }
              foreach ($_GPC['id'] as $id){
                    $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                    pdo_update($this->modulename."_tbgoods",array('tj'=>2), array('id' => $id));
                }
                message('批量人气设置成功', referer(), 'success');
            }
            if(checksubmit('submitqxzd')){//取消置顶
              if(!$_GPC['id']){
                message('请选择商品', referer(), 'error');
              }
              foreach ($_GPC['id'] as $id){
                    $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                    pdo_update($this->modulename."_tbgoods",array('zd'=>0), array('id' => $id));
                }
                message('批量【取消】成功', referer(), 'success');
            }
            if(checksubmit('submitqxfl')){//批量分类
              if(!$_GPC['id']){
                message('请选择商品', referer(), 'error');
              }
              foreach ($_GPC['id'] as $id){
                    $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                    pdo_update($this->modulename."_tbgoods",array('type'=>$_GPC['type']), array('id' => $id));
                }
                message('批量【分类】成功', referer(), 'success');
            }
            if(checksubmit('submitqxzt')){//批量专题
              if(!$_GPC['id']){
                message('请选择商品', referer(), 'error');
              }
              foreach ($_GPC['id'] as $id){
                    $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                    pdo_update($this->modulename."_tbgoods",array('zt'=>$_GPC['zt']), array('id' => $id));
                }
                message('批量【专题分组】成功', referer(), 'success');
            }
            if(checksubmit('submitms')){//设置秒杀
              if(!$_GPC['id']){
                message('请选择秒杀商品', referer(), 'error');
              }
              foreach ($_GPC['id'] as $id){
                    $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                    pdo_update($this->modulename."_tbgoods",array('tj'=>3), array('id' => $id));
                }
                message('批量秒杀设置成功', referer(), 'success');
            }
            if(checksubmit('submitmsqx')){//取消秒杀
              if(!$_GPC['id']){
                message('请选择秒杀商品', referer(), 'error');
              }
              foreach ($_GPC['id'] as $id){
                    $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                    pdo_update($this->modulename."_tbgoods",array('tj'=>0), array('id' => $id));
                }
                message('批量取消秒杀成功', referer(), 'success');
            }

            if(checksubmit('qf')){//群发库
                if(!$_GPC['id']){
                    message('请选择入库商品', referer(), 'error');
                  }
                  foreach ($_GPC['id'] as $id){
                        $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                        if (empty($row)){
                            continue;
                        }
                        pdo_update($this->modulename."_tbgoods",array('qf'=>1), array('id' => $id));
                    }
                message('批量设置入库成功', referer(), 'success');  
            }

            if(checksubmit('scqf')){
                if(!$_GPC['id']){
                    message('请选择取消入库商品', referer(), 'error');
                  }
                  foreach ($_GPC['id'] as $id){
                        $row = pdo_fetch("SELECT id FROM " . tablename($this->modulename.'_tbgoods') . " WHERE id = :id", array(':id' => $id));
                        if (empty($row)){
                            continue;
                        }
                        $a=pdo_update($this->modulename."_tbgoods",array('qf'=>0), array('id' => $id));
                    }
                message('批量取消入库成功', referer(), 'success');  
            }

            $condition = '';
            $pindex = max(1, intval($_GPC['page']));
		    $psize = 20;  

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$_W['weid']}'  ORDER BY id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_tbgoods')." where weid='{$_W['uniacid']}'");
		    $pager = pagination($total, $pindex, $psize);           
        }else if($operation == 'seach'){
            $key=$_GPC['key'];
            
            $yjtype=$_GPC['yjtype'];
            $type=$_GPC['type'];
            //$event_end_time=strtotime($_GPC['event_end_time']);//活动结束时间
            //$coupons_end=strtotime($_GPC['coupons_end']);//优惠券结束时间
            $tj=$_GPC['tj'];
            $istmall=$_GPC['istmall'];
            $tk_rate=$_GPC['tk_rate'];
            $px=$_GPC['px'];
            $zd=$_GPC['zd'];
            $limit=$_GPC['limit'];
            if(empty($limit)){
               $limit=20;
            }

            if(!empty($yjtype)){
              $where.=" and yjtype='{$yjtype}'";
            }
            if(!empty($zd)){
              $where.=" and zd=1";
            }
            if(!empty($type)){
              $where.=" and type='{$type}'";
            }


            if(!empty($_GPC['key'])){
                include IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/tb.php"; 
                $arr=getfc($_GPC['key'],$_W);
                 foreach($arr as $v){
                     if (empty($v)) continue;
                    $where.=" and title like '%{$v}%'";
                 }
            }
            $num_iid=$_GPC['num_iid'];
            if(!empty($num_iid)){
              $where.=" and num_iid={$num_iid}";
            }
            if(!empty($tj)){
                if($tj==1){
                   $where.=" and price<10";
                }elseif($tj==2){
                   $where.=" and price>10 and price<20";
                }else{
                  $where.=" and tj={$tj}";
                }          
            }
            if(!empty($istmall)){
              if($istmall==1){
                   $where.=" and istmall=0";
                }elseif($istmall==2){
                   $where.=" and istmall=1";
                }   
            }
            if(!empty($tk_rate)){
               $where.=" and tk_rate>{$tk_rate}";
            }
            if($px==1){
              $px=" tk_rate desc";
            }elseif($px==2){
              $px=" tk_rate asc";
            }elseif($px==3){
              $px=" yongjin desc";
            }elseif($px==4){
              $px=" yongjin asc";
            }elseif($px==5){
              $px=" price desc";
            }elseif($px==6){
              $px=" price asc";            
            }elseif($px==7){
              $px=" coupons_take desc";
            }elseif($px==8){
              $px=" coupons_take asc";            
            }elseif($px==12){
              $px=" coupons_price desc";            
            }elseif($px==13){
              $px=" coupons_price asc";            
            }else{
              $px=" id desc";
            }
           //echo $where;

            $pindex = max(1, intval($_GPC['page']));
		    $psize = $limit;  
            $list = pdo_fetchall("SELECT * FROM " . tablename($this->modulename."_tbgoods") . " s WHERE weid = '{$_W['uniacid']}' {$where} ORDER BY {$px} LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_tbgoods')." where weid='{$_W['uniacid']}' {$where}");
		    $pager = pagination($total, $pindex, $psize);    
            //echo '<pre>';
            //print_r($list);
            //exit;
            
        }else if($operation == 'qf'){//群发库
            $pindex = max(1, intval($_GPC['page']));
		    $psize = 20;  

            $list = pdo_fetchall("SELECT * FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$_W['weid']}' and qf=1  ORDER BY id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_tbgoods')." where weid='{$_W['uniacid']}' and qf=1");
            $pager = pagination($total, $pindex, $psize);    
        }
        

        include $this -> template('tbgoods');