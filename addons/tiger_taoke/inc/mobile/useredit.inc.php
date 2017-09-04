<?php
 global $_W, $_GPC;
       $cfg = $this->module['config'];
//       $fans=$this->checkoauth();
//       echo '<pre>';
//       print_r($fans);
//       exit;
      $id=$_GPC['id'];
      $dluid=$_GPC['dluid'];//share id
       $fans = mc_oauth_userinfo();
       $fans=$_W['fans'];
       $mc = mc_credit_fetch($fans['uid']);
       if(empty($fans['openid'])){
         echo '请从微信浏览器中打开！';
         exit;
       }
//       echo '<pre>';
//       print_r($fans);
//       exit;
       $member = pdo_fetch("select * from ".tablename($this->modulename."_share")." where weid='{$_W['uniacid']}' and id='{$id}'");
       if(empty($member)){
           if(!empty($fans['uid'])){
             pdo_insert($this->modulename."_share",
					array(
							'openid'=>$fans['uid'],
							'nickname'=>$fans['nickname'],
							'avatar'=>$fans['avatar'],
							'pid'=>'',
                            'updatetime'=>time(),
							'createtime'=>time(),
							'parentid'=>0,
							'weid'=>$_W['uniacid'],
							'score'=>'',
							'cscore'=>'',
							'pscore'=>'',
                            'from_user'=>$fans['openid'],
                            'follow'=>1
					));
			  $member['id'] = pdo_insertid();
           }
           
			$member = pdo_fetch('select * from '.tablename($this->modulename."_share")." where id='{$member['id']}'");         
       }
       if($_W['isajax']){
           $data=array(
               'weixin'=>$_GPC['weixin'],
               'tel'=>$_GPC['tel']
           );
          if(!empty($id)){
            pdo_update($this->modulename."_share", $data, array('id' => $id));
          }
          
          exit(json_encode(array('statusCode' => 200)));
       }
       

       include $this->template ( 'user/useredit' );