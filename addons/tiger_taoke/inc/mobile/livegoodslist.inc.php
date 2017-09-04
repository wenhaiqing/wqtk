<?php
        global $_W, $_GPC;
        $cfg = $this->module['config'];
        $typeid=$_GPC['typeid'];
        $dluid=$_GPC['dluid'];//share id
        $from_id=$_GPC['from_id'];
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\nfrom_id:".json_encode($from_id),FILE_APPEND);
        if($cfg['zblive']==1){
          $qf="and qf=1";
        }else{
          $qf='';
        }

        $weid=$_W['uniacid'];
        if(!empty($cfg['gyspsj'])){
          $weid=$cfg['gyspsj'];
        }
        
        $goodsArr = pdo_fetchall("select * from ".tablename($this->modulename."_tbgoods")." where id < {$from_id} and weid={$weid} {$qf} order by id desc LIMIT 1");
        $time = date('H:i');
        $goods = '';

        for ($i = 0; $i < count($goodsArr); $i++) {
            $goods.='<div class="goods-box">';
                $goods.='<div class="publishTime">'.$time.'</div>';
                $goods.='<div class="contentImg">';
                    if(!empty($cfg['zbtouxiang'])){
                        $goods.='<img class="headPic" src="'.tomedia($cfg['zbtouxiang']).'" />';    
                   }else{
                        $goods.='<img class="headPic" src="../addons/tiger_taoke/template/mobile/live/images/touxiang-1.jpg" />'; 
                    }
                    $goods.='<a href="'.$this->createMobileUrl("view",array("id"=>$goodsArr[$i]["id"],"dluid"=>$dluid)).'"><img class="conPic" src="'.$goodsArr[$i]["pic_url"].'" /></a>';
                $goods.='</div>';
                $goods.='<div class="contents">';
                    $goods.='<img class="triangle" src="../addons/tiger_taoke/template/mobile/live/images/triangle.png" />';
                    if(!empty($cfg['zbtouxiang'])){
                        $goods.='<img class="headPic" src="'.tomedia($cfg['zbtouxiang']).'" />';    
                   }else{
                        $goods.='<img class="headPic" src="../addons/tiger_taoke/template/mobile/live/images/touxiang-1.jpg" />'; 
                    }
                    $goods.='<p>'.$goodsArr[$i]["title"].'原价：'.$goodsArr[$i]["org_price"].'元<span style="text-decoration:underline">【券后仅需'.$goodsArr[$i]["price"].'元】</span><br/><span style="font-weight:bold">推荐理由：</span>'.$goodsArr[$i]["tjcontent"].'</p>';
                    $goods.='<div class="purchase">';
                        $goods.='<a href="'.$this->createMobileUrl("view",array("id"=>$goodsArr[$i]["id"],"dluid"=>$dluid)).'">';
                            $goods.='<div class="buy">领券购买</div>';
                        $goods.='</a>';
                        $goods.='<div class="num"><span>'.$goodsArr[$i]["id"].'</span>号</div>';
                    $goods.='</div>';
                $goods.='</div>';
            $goods.='</div>';
            $arr[]=$goods;
            $goods='';
        }


        exit(json_encode($arr,JSON_UNESCAPED_UNICODE));

