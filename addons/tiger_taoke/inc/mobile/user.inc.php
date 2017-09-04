<?php
global $_W, $_GPC;
       $cfg = $this->module['config'];
       $dluid=$_GPC['dluid'];//share id

       $fans=$_W['fans'];
       $mc = mc_credit_fetch($fans['uid']);
       $fans=$_W['fans'];
       if(empty($fans['openid'])){
         echo '请从微信浏览器中打开！';
         exit;
       }
       //echo '<pre>';
       //print_r($fans);
       //exit;
       $fans['credit1']=$mc['credit1'];
       $fans['avatar']=$fans['tag']['avatar'];
       $fans['nickname'] =$fans['tag']['nickname'];


       include $this->template ( 'user/index' );