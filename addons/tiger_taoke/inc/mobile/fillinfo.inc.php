<?php
global $_W, $_GPC;
        //checkauth();
        load()->model('mc');
        $cfg=$this->module['config']; 
        $memberid=intval($_GPC['memberid']);
        $goods_id = intval($_GPC['goods_id']);
        $fans = mc_oauth_userinfo();
        $mc = mc_fetch($fans['openid']);

        
        $dluid=$_GPC['dluid'];//share id
        
        
        $goods_info = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
         $ips = $this->getIp();
         $ip=$this->GetIpLookup($ips);
         $province=$ip['province'];//省
         $city=$ip['city'];//市
         $district=$ip['district'];//县

         $request1 = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . "_request") . " WHERE weid = '{$_W['weid']}' and goods_id='{$goods_info['goods_id']}' order by id desc limit 20");
         $requestsum = pdo_fetchcolumn("SELECT count(id) FROM " . tablename($this->modulename . "_request") . " WHERE weid = '{$_W['weid']}' and goods_id='{$goods_info['goods_id']}'");
         foreach($request1 as $k=>$v){
             $gx=mc_fetch($v['from_user']);
             $request[$k]['from_user_realname']=$v['from_user_realname'];
             $request[$k]['createtime']=$v['createtime'];
             $request[$k]['avatar']=$gx['avatar'];
         }
//        '<pre>';
//        print_r($request);
//        exit;
         
        

        $mbstyle='style1';
        include $this -> template('goods/'.$mbstyle.'/fillinfo');