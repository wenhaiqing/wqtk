<?php
global $_W, $_GPC;
$dluid=$_GPC['dluid'];//share id
        $cfg=$this->module['config']; 
        $ad = pdo_fetchall("SELECT * FROM " . tablename($this -> table_ad) . " WHERE weid = '{$_W['weid']}' order by id desc");
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
		$fans1 = pdo_fetchall("select s.openid from ".tablename($this->modulename."_share")." s join ".tablename('mc_mapping_fans')." f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''",array(),'openid');
		$count = count($fans1);
        if ($fans1){
			$count2 = pdo_fetchcolumn("select count(*) from ".tablename($this->modulename."_share")." s  join ".tablename('mc_mapping_fans')." f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (".implode(',',array_keys($fans1)).") and f.follow=1");
		}    
        if (empty($count2)){ $count2 = 0;}
        //统计1级好友和2级好友总和
        $sumcount=$count;
        //结束     

        $goods_list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " as t1," . tablename($this -> table_request) . "as t2 WHERE t1.goods_id=t2.goods_id AND from_user='{$_W['fans']['from_user']}' AND t1.weid = '{$_W['weid']}' ORDER BY t2.createtime DESC");
        if(empty($goods_list)){
          $olist=1;
        }
        $dblist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=1  order by px desc");//底部菜单
        $mbstyle='style1';
        include $this -> template('goods/'.$mbstyle.'/request');