<?php
global $_W, $_GPC;
       $cfg = $this->module['config'];
       $dluid=$_GPC['dluid'];//share id
       $fans=$_W['fans'];
       $fans['city']=$fans['tag']['city'];
       if(empty($fans['openid'])){
         $fans = mc_oauth_userinfo();
       }
       $mc = mc_credit_fetch($fans['uid']);
//       echo '<pre>';
//       print_r($fans['avatar']);
//       exit;
        
       if(empty($fans['openid'])){
         echo '请从微信浏览器中打开！';
         exit;
       }
       if(!empty($dluid)){
          $share=pdo_fetch("select * from ".tablename('tiger_taoke_share')." where weid='{$_W['uniacid']}' and id='{$dluid}'");
        }
        $appid=$_W['uniaccount']['key'];
        setcookie('user_token', $appid);
        setcookie('user_nickname', urlencode($fans['nickname']));
        setcookie('user_openid', $fans['openid']);
       
       $fans['credit1']=$mc['credit1'];
       $fans['avatar']=$fans['tag']['avatar'];
       $fans['nickname'] =$fans['tag']['nickname'];

       if($cfg['zblive']==1){
          $qf="and qf=1";
        }else{
          $qf='';
        }

        $weid=$_W['uniacid'];
        if(!empty($cfg['gyspsj'])){
          $weid=$cfg['gyspsj'];
        }


        $goodsArr = pdo_fetchall("select * from ".tablename($this->modulename."_tbgoods")." where weid='{$weid}' {$qf} order by id desc LIMIT 6");
        $time = date('H:i');
        $goods = '';

        foreach($goodsArr as $k=>$v){
            $goods.='<div class="goods-box">';
                $goods.='<div class="publishTime">'.$time.'</div>';
                $goods.='<div class="contentImg">';
                   
                   if(!empty($cfg['zbtouxiang'])){
                        $goods.='<img class="headPic" src="'.tomedia($cfg['zbtouxiang']).'" />';    
                   }else{
                        $goods.='<img class="headPic" src="../addons/tiger_taoke/template/mobile/live/images/touxiang-1.jpg" />'; 
                    }
                    $goods.='<a href="'.$this->createMobileUrl('view',array('id'=>$v['id'],'dluid'=>$dluid)).'"><img class="conPic" src="'.$v["pic_url"].'" /></a>';
                $goods.='</div>';
                $goods.='<div class="contents">';
                    $goods.='<img class="triangle" src="../addons/tiger_taoke/template/mobile/live/images/triangle.png" />';
                    if(!empty($cfg['zbtouxiang'])){
                        $goods.='<img class="headPic" src="'.tomedia($cfg['zbtouxiang']).'" />';    
                   }else{
                        $goods.='<img class="headPic" src="../addons/tiger_taoke/template/mobile/live/images/touxiang-1.jpg" />'; 
                    }
                    $goods.='<p>'.$v["title"].'原价：'.$v["org_price"].'元<span style="text-decoration:underline">【券后仅需'.$v["price"].'元】</span><br/><span style="font-weight:bold">推荐理由：</span>'.$v["tjcontent"].'</p>';
                    $goods.='<div class="purchase">';
                        $goods.='<a href="'.$this->createMobileUrl('view',array('id'=>$v['id'],'dluid'=>$dluid)).'">';
                            $goods.='<div class="buy">领券购买</div>';
                        $goods.='</a>';
                        $goods.='<div class="num"><span>'.$v["id"].'</span>号</div>';
                    $goods.='</div>';
                $goods.='</div>';
            $goods.='</div>';
        }
//        echo '<pre>';
//       print_r($fans);
//       exit;

       include $this->template ( 'live/index' );