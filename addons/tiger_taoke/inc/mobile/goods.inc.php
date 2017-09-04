<?php
global $_W, $_GPC;
        $dluid=$_GPC['dluid'];//share id
        $now=time();
        $weid=$_W['weid'];
        $type= $_GPC['type'];
        if($type=='sw'){
          $where=" and type=1";
        }
        if($type=='xn'){
          $where=" and type=5";
        }

        $cfg=$this->module['config']; 
        $goods_list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " WHERE weid = '{$_W['weid']}' and $now < endtime and amount >= 0 {$where} order by px ASC");
        foreach($goods_list as $k=>$v){
            $requestsum = pdo_fetchcolumn("SELECT count(id) FROM " . tablename($this->modulename . "_request") . " WHERE weid = '{$_W['weid']}' and goods_id='{$v['goods_id']}'");
            $good[$k]=$v;
            $good[$k]['requestsum']=$requestsum;
        }
        $goods_list=$good;
//        echo '<pre>';
//        print_r($good);
//        exit;

        


        $my_goods_list = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . " WHERE  from_user='{$_W['fans']['from_user']}' AND weid = '{$_W['weid']}'");
        $ad = pdo_fetchall("SELECT * FROM " . tablename($this -> table_ad) . " WHERE weid = '{$_W['weid']}' order by id desc");

        load()->model('account');
        $cfg=$this->module['config'];
        if($cfg['jiequan']==1){            
           load()->model('account');
           $cfg=$this->module['config'];  
           //开启借权使用登录授权
           if(empty($_GPC['tiger_taoke_openid'.$weid])){
             if(empty($_GPC['openid'])){
                    $callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauth',array('weid'=>$weid,'dw'=>'Goods'))));
                    $forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$cfg['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
                    header('location:'.$forward);
                    exit();
                }else{
                    $openid=$_GPC['tiger_taoke_openid'.$weid];
                }
          }
        }
        if(!empty($_GPC['tiger_taoke_openid'.$weid])){
           $openid=$_GPC['tiger_taoke_openid'.$weid];
        }elseif(!empty($_GPC['openid'])){
           $openid=$_GPC['openid'];
        }
        
        $sql='select * from '.tablename('tiger_taoke_member').' where weid=:weid AND openid=:openid order by id asc limit 1';
        $member=pdo_fetch($sql,array(':weid'=>$_W['weid'],':openid'=>$openid));
       // echo '<pre>';
       // print_r($member);
       // exit;
        

        //统计我的积分和我的团队
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
		if (!strpos($userAgent, 'MicroMessenger')) {
			message('请使用微信浏览器打开！');
			$openid = 'oUvXSsv6hNi7wdmd5uWQUTS4vJTY';
			$fans = pdo_fetch("select * from ims_mc_mapping_fans where openid='{$openid}'");
		}else{
			load()->model('mc');
			//$info = mc_oauth_userinfo();
			$fans = $_W['fans'];
			$mc = mc_fetch($fans['uid']);
            $fans['credit1']=$mc['credit1'];
            $fans['avatar']=$fans['tag']['avatar'];
            $fans['nickname'] =$fans['tag']['nickname'];
		}
		$pid = $_GPC['pid'];
        $weid = $_GPC['i'];
		$poster = pdo_fetch ( 'select * from ' . tablename ( $this->modulename . "_poster" ) . " where weid='{$weid}'" );
		$credit = 0;
		$creditname = '积分';
		$credittype = 'credit1';
		if ($poster['credit']){
			$creditname = '余额';
			$credittype = 'credit2';
		}
		if ($fans){
			$mc = mc_credit_fetch($fans['uid'],array($credittype));
			$credit = $mc[$credittype];
		}
		$fans1 = pdo_fetchall("select s.openid from ".tablename($this->modulename."_share")." s join ".tablename('mc_mapping_fans')." f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1  and s.openid<>''",array(),'openid');
		$count = count($fans1);
        if ($fans1){
			$count2 = pdo_fetchcolumn("select count(*) from ".tablename($this->modulename."_share")." s  join ".tablename('mc_mapping_fans')." f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (".implode(',',array_keys($fans1)).") and f.follow=1");
		}    
        if (empty($count2)){ $count2 = 0;}
        //统计1级好友和2级好友总和
        $sumcount=$count;
        //结束       

        $is_follow = true; 
        $setting=$this->module['config']; 
        if(empty($setting['style'])){
           $mbstyle='style1';
        }else{
           $mbstyle=$setting['style'];
        }     
         $mbstyle='style1';

         $dblist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=1  order by px desc");//底部菜单
       
        include $this -> template('goods/'.$mbstyle.'/goods');