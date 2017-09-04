<?php
global $_W, $_GPC;
$dluid=$_GPC['dluid'];//share id
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		if (!strpos($userAgent, 'MicroMessenger')) {
			message('请使用微信浏览器打开！');
		}else{
			load()->model('mc');
			//$info = mc_oauth_userinfo();
			$fans = $_W['fans'];
			$fans['avatar'] = $fans['tag']['avatar'];
			$fans['nickname'] =$fans['tag']['nickname'];
		}
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
		$fans1 = pdo_fetchall("select s.openid from ".tablename($this->modulename."_share")." s left join ".tablename('mc_mapping_fans')." f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''",array(),'openid');
		$count = count($fans1);
		if ($fans1){
			$count2 = pdo_fetchcolumn("select count(*) from ".tablename($this->modulename."_share")." s left join ".tablename('mc_mapping_fans')." f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (".implode(',',array_keys($fans1)).") and f.follow=1");
			if (empty($count2)) $count2 = 0;
		}

        //统计1级好友和2级好友总和
        $sumcount=$count;
		$rank = $poster['slideH'];  
        if(empty($rank)){
          $rank=20;
        }
        $cfg=$this->module['config'];
        $shares=pdo_fetchall("select m.nickname,m.avatar,m.credit1,m.uid from".tablename('mc_members')." m inner join ".tablename('mc_mapping_fans')." f on m.uid=f.uid and m.nickname<>'' and f.follow=1 and f.uniacid='{$weid}' order by m.credit1 desc limit {$rank}");
        
        foreach($shares as $k=>$v){
           $txsum=pdo_fetch('select SUM(num) tx from '.tablename('mc_credits_record').' where uniacid=:uniacid and uid=:uid and credittype=:credittype and num<:num',array(':uniacid'=>$_W['uniacid'],':uid'=>$shares[$k]['uid'],':credittype'=>'credit1',':num'=>0));
           if(empty($txsum['tx'])){
             $shares[$k]['credit3']=0;
           }else{
             $shares[$k]['credit3']=$txsum['tx']*-1;
           }
        }
        $cfg=$this->module['config'];  
        if($cfg['paihang']==1){
            foreach ($shares as $key=>$value){
                $nickname[$key] = $value['nickname'];
                $avatar[$key] = $value['avatar'];
                $credit2[$key] = $value['credit2'];
                $uid[$key] = $value['uid'];
                $credit3[$key] = $value['credit3'];
            }
            array_multisort($credit3,SORT_NUMERIC,SORT_DESC,$uid,SORT_STRING,SORT_ASC,$shares);
        }
        $dblist = pdo_fetchall("select * from ".tablename($this->modulename."_cdtype")." where weid='{$_W['uniacid']}' and fftype=1  order by px desc");//底部菜单
		include $this->template ('user/ranking' );