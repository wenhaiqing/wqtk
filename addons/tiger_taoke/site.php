<?php
/**
 * 微信淘宝客模块微站定义
 *
 * @author 老虎
 * @url http://bbs.we7.cc/
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
require_once IA_ROOT.'/addons/tiger_taoke/lib/excel.php';
require_once IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/TopSdk.php";
class Tiger_taokeModuleSite extends WeModuleSite {
    public $table_request = "tiger_taoke_request";
    public $table_goods = "tiger_taoke_goods";
    public $table_ad = "tiger_taoke_ad";
    private static $t_sys_member = 'mc_members';



   public function doWebCs2() {
       global $_W, $_GPC;

        load()->classs('cloudapi');
        $api = new CloudApi();
        $result = $api->get('site', 'module');

        print_r($result);
        exit;

   }



   
    //测试

    public function getfc ($string, $len=2) {
      $string=str_replace(' ','',$string);
      $start = 0;
      $strlen = mb_strlen($string);
      while ($strlen) {
        $array[] = mb_substr($string,$start,$len,"utf8");
        $string = mb_substr($string, $len, $strlen,"utf8");
        $strlen = mb_strlen($string);
      }
      return $array;
   }

    public function curl_request($url,$post='',$cookie='', $returnCookie=0){
        //参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$Cookies,参数4：是否返回$cookies
            $curl = curl_init();//初始化curl会话
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; 	Trident/6.0)');
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
            curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
            if($post) {
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
            }
            if($cookie) {
                curl_setopt($curl, CURLOPT_COOKIE, $cookie);
            }
            curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);//执行curl会话
            if (curl_errno($curl)) {
                return curl_error($curl);
            }
            curl_close($curl);//关闭curl会话
            if($returnCookie){
                list($header, $body) = explode("\r\n\r\n", $data, 2);
                preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
                $info['cookie']  = substr($matches[1][0], 1);
                $info['content'] = $body;
                return $info;
            }else{
                return $data;
            }
        }


   public function httpPost($url,$postData) 
    { 
      $ch = curl_init(); 
      curl_setopt($ch,CURLOPT_URL,$url); 
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch,CURLOPT_HEADER, false); 
      curl_setopt($ch, CURLOPT_POST, count($postData));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      $output=curl_exec($ch);
      curl_close($ch); 
      return $output;
    }

   
    public function strurl($coupons_url) {//获取优惠券ID
        //$a="http://shop.m.taobao.com/shop/coupon.htm?activity_id=b20277f095a940f99db74b36123e4870&seller_id=1761644935";
        //http:\/\/shop.m.taobao.com\/shop\/coupon.htm?seller_id=2267264737&activity_id=11254459ce974f879d27968fc463c2d4
        //http:\/\/shop.m.taobao.com\/shop\/coupon.htm?sellerId=839765554&activityId=9a27c2aa95b1471c8ff219b18c6592ee
        $url=strtolower($coupons_url);//转小写
        //Return $url;
        $activity_id="activity_id=";
        $wz=strpos($url,$activity_id);
        
        if(empty($wz)){
          $activity_id="activityid=";
          $wz=strpos($url,$activity_id);
           Return  substr($url,$wz+11,32);
        }else{
           Return  substr($url,$wz+12,32);
        }
        
    }



    public function tkl($url,$img,$tjcontent) {//淘口令转换
        global $_W, $_GPC;
        
        $cfg = $this->module['config'];
        $appkey=$cfg['tkAppKey'];
        $secret=$cfg['tksecretKey'];
        $c = new TopClient;
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new WirelessShareTpwdCreateRequest;
        $tpwd_param = new GenPwdIsvParamDto;
        $tpwd_param->ext="{\"\":\"\"}";
        $tpwd_param->logo=$img;
        $tpwd_param->text=$tjcontent;
        $tpwd_param->url=$url;
        //$tpwd_param->user_id=$cfg['tbid'];
        $req->setTpwdParam(json_encode($tpwd_param));
        $resp = $c->execute($req);     
        Return $resp;
    }



    public function hlinorder($userInfo,$_W) {
        global $_W, $_GPC;
        
        $cfg = $this->module['config'];
        foreach($userInfo as $v){
                   $fztype=pdo_fetch("select * from ".tablename($this->modulename."_fztype")." where weid='{$_W['uniacid']}' and hlcid='{$v['fqcat']}' order by px desc");
                  
                   $yjbl=$v['tkrates'];//佣金比例
                   $Quan_id=$this->strurl($v['couponurl']);
                   $erylj=$this->rhy($Quan_id,$v['itemid'],$cfg['qqpid']); 
                   if($v['shoptype']=="B"){
                     $IsTmall=1;
                   }else{
                     $IsTmall=0;
                   } 
                   if($v['tktype']=='定向计划'){
                     $tktype=1;
                   }elseif($v['tktype']=='鹊桥活动'){
                     $tktype=2;
                   }else{
                     $tktype=0;
                   }
//                 echo '<pre>';
//                 print_r($userInfo);
//                  exit;
                $item = array(
                         'weid' => $_W['uniacid'],
                         'type'=>$fztype['id'],
                         'yjtype'=>2,
                          'zy'=>2,
                         'lxtype'=>$tktype,
                         'num_iid'=>$v['itemid'],//商品ID
                          'videoid'=>$v['videoid'],//视频ID http://cloud.video.taobao.com/play/u/1/p/1/e/6/t/1/视频ID.mp4
                         'title'=>$v['itemtitle'],//商品名称
                         'tjcontent'=>$v['itemdesc'],//推荐内容
                         'pic_url'=>$v['itempic'],//主图地址
                         'item_url'=>"",//详情页地址
                         'shop_title'=>'',//店铺名称
                         'price'=>$v['itemendprice'],//商品价格,券后价
                         'goods_sale'=>$v['itemsale'],//月销售
                         'tk_rate'=>$yjbl,//通用佣金比例
                         'yongjin'=>$v['tkmoney'],//通用佣金
                         'event_zt'=>'',//活动状态event_zt
                         'event_yjbl'=>'',//活动收入比event_yjbl
                         'event_yj'=>'',//活动佣金event_yj
                         'event_start_time'=>'',//活动开始event_start_time
                         'event_end_time'=>'',//活动结束event_end_time
                          'nick'=>'',//卖家旺旺
                          'tk_durl'=>'',//淘客短链接
                          'click_url'=>'',//淘客长链接
                          //'taokouling'=>$taokou,//淘口令--------------
                          'coupons_total'=>$v['couponsurplus'],//优惠券总量已领取数量
                          'coupons_take'=>$v['couponreceive'],//优惠券剩余
                          'coupons_price'=>$v['couponmoney'],//优惠券面额
                          'coupons_start'=>'',//优惠券开始
                          'coupons_end'=>$v['couponendtime'],//优惠券结束
                          'coupons_url'=>$v['couponurl'],//优惠券链接
                          'coupons_tkl'=>'',//优惠淘口令
                          'istmall'=>$IsTmall,//'0不是  1是天猫',
                          'dsr'=>"",//'dsr评分',  
                          'quan_id'=>$Quan_id,//'优惠券ID',  
                          'quan_condition'=>$v['couponexplain'],//'优惠券使用条件',  
                          'org_price'=>$v['itemprice'],//'商品原价', 
                          'dingxianurl'=>$v['tkurl'],
                          'createtime'=>TIMESTAMP,
                        );
                          
                       $go = pdo_fetch("SELECT id FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$_W['uniacid']}' and  num_iid='{$v['itemid']}' ORDER BY px desc");
                        if(empty($go)){
//                          $taokouling=$this->tkl($erylj,$v['itempic'],$v['itemdesc']);
//                          $taokou=$taokouling->model;
//                          settype($taokou, 'string');
//                          $item['taokouling']=$taokou;
                          pdo_insert($this->modulename."_tbgoods",$item);
                        }else{                          
                          pdo_update($this->modulename."_tbgoods", $item, array('weid'=>$_W['uniacid'],'num_iid' => $v['itemid']));
                        }  
                       
            }
        
    }



    public function indtkgoods($dtklist) {//大淘客入库
        global $_W, $_GPC;
        $page=$_GPC['page'];
        $cfg = $this->module['config'];
        foreach($dtklist as $v){
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
                 
                // var_dump($taokou);
                // exit;
                if($v['Commission_queqiao']!='0.00'){//鹊桥
                   $lxtype=2;
                }elseif($v['Commission_jihua']!='0.00'){//定向
                  $lxtype=1;
                }else{
                  $lxtype=0;
                }

                $item = array(
                         'weid' => $_W['uniacid'],
                         'lxtype'=>$lxtype,
                         'type'=>$fztype['id'],
                         'zy'=>1,
                         'yjtype'=>$yjtype,
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
                           
                          
                       $go = pdo_fetch("SELECT num_iid FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$_W['uniacid']}' and  num_iid={$v['GoodsID']} ");
                       //echo print_r($go);
                       //exit;
                       file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode("01:".$go['num_iid']),FILE_APPEND);
                        if(empty($go)){
                            file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode("in02:".$go['num_iid']),FILE_APPEND);
//                          $taokouling=$this->tkl($erylj,$v['Pic'],$v['Introduce']);
//                          $taokou=$taokouling->model;
//                          settype($taokou, 'string');
//                          $item['taokouling']=$taokou;
                          pdo_insert($this->modulename."_tbgoods",$item);
                        }else{
                            file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode("up02:".$go['num_iid']),FILE_APPEND);
                          pdo_update($this->modulename."_tbgoods", $item, array('weid'=>$_W['uniacid'],'num_iid' => $v['GoodsID']));
                        }  
                       
            }
        
    }

    public function doWebIndex() {     
		global $_W, $_GPC;
        $goods = pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename($this->modulename.'_tbgoods')." where weid='{$_W['uniacid']}'");
        $fans = pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename($this->modulename.'_share')." where weid='{$_W['uniacid']}'");
        $qgfans = pdo_fetchcolumn("SELECT COUNT(fanid) FROM " . tablename('mc_mapping_fans')." where uniacid='{$_W['uniacid']}' and unfollowtime<>0 ");//取关
        $sdsum = pdo_fetchcolumn("SELECT COUNT(id) FROM " . tablename($this->modulename.'_sdorder')." where weid='{$_W['uniacid']}'");//晒单数

		include $this->template ( 'index' );
	}

    public function doMobileCqlist(){
       global $_W, $_GPC;
       include IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/tb.php"; 
       $key=$_GPC['key'];
       $lm=$_GPC['lm'];
       $pid=$_GPC['pid'];
       $pic_url=$_GPC['pic_url'];
       $goods=getgoodslist($_GPC['key'],'',$_W,$page);
       if(empty($pid)){
            $fans=$_W['fans'];
            if(empty($fans)){
              $fans=mc_oauth_userinfo();
            }
            $openid=$fans['openid'];
            $share=pdo_fetch("select * from ".tablename('tiger_taoke_share')." where weid='{$_W['uniacid']}' and from_user='{$openid}'");
            if($share['dlptpid']){
              $pid=$share['dlptpid'];
            }else{
                  if(!empty($share['helpid'])){//查询有没有上级
                     $sjshare=pdo_fetch("select * from ".tablename('tiger_taoke_share')." where weid='{$_W['uniacid']}' and dltype=1 and openid='{$share['helpid']}'");
                     if(!empty($sjshare['dlptpid'])){
                        $pid=$sjshare['dlptpid'];
                     }
                  }
            }
            if(empty($pid)){
              $cfg = $this->module['config']; 
              $pid=$cfg['ptpid'];
            }
       }

       foreach($goods as $k=>$v){
             $list[$k]['title']=$v->title;  
             $list[$k]['istmall']=$v->userType;  
             $list[$k]['num_iid']=$v->auctionId;
             $list[$k]['org_price']=$v->zkPrice;
             $list[$k]['price']=$v->zkPrice-$v->couponAmount;
             $list[$k]['coupons_price']=$v->couponAmount;
             $list[$k]['goods_sale']=$v->biz30day;
             $list[$k]['url']=$v->auctionUrl;
             $list[$k]['pic_url']='http:'.$v->pictUrl;
       }
      
       include $this->template ( 'cqlist' );
    }

    public function doMobileCqlistajax(){
       global $_W, $_GPC;
       $cfg = $this->module['config'];
       include IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/tb.php"; 
       $page=$_GPC['limit'];
       $lm=$_GPC['lm'];
       $pid=$_GPC['pid'];
       if(empty($pid)){
         $pid=$cfg['ptpid'];
       }

       $goods=getgoodslist($_GPC['key'],'',$_W,$page);
       $key=$_GPC['key'];
      if(empty($goods)){
         $status=2;
      }else{
            foreach($goods as $k=>$v){
                $title=str_replace("<span class=H>","",$v->title);
                $title=str_replace("</span>","",$title);
                 $list[$k]['title']=$title;  
                 $list[$k]['istmall']=$v->userType;  
                 $list[$k]['num_iid']=$v->auctionId;
                 $list[$k]['org_price']=$v->zkPrice;
                 $list[$k]['price']=$v->zkPrice-$v->couponAmount;
                 $list[$k]['coupons_price']=$v->couponAmount;
                 $list[$k]['goods_sale']=$v->biz30day;
                 $list[$k]['url']=$v->auctionUrl;
                 $list[$k]['pic_url']='http:'.$v->pictUrl;
                 $list[$k]['pid']=$pid;
           }
           $status=1;
      }

       file_put_contents(IA_ROOT."/addons/tiger_taoke/log--aaa.txt","\n".$goods,FILE_APPEND);
       file_put_contents(IA_ROOT."/addons/tiger_taoke/log--aaa.txt","\n".json_encode($status),FILE_APPEND);


       exit(json_encode(array('status' => $status, 'content' => $list,'lm'=>1)));
    }



    public function apUpload($media_id){
        global $_W,$_GPC;
		load()->classs('weixin.account');
        $accObj= WeixinAccount::create($_W['uniacid']);
        $access_token = $accObj->fetch_token();

        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($access_token),FILE_APPEND);
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($media_id),FILE_APPEND);

        $newfolder= ATTACHMENT_ROOT . 'images' . '/tiger_taoke_photos'."/";//文件夹名称
        if (!is_dir($newfolder)) {
            mkdir($newfolder, 7777);
        } 
        $picurl = 'images'.'/tiger_taoke_photos'."/".date('YmdHis').rand(1000,9999).'.jpg';
        $targetName = ATTACHMENT_ROOT.$picurl;
        $ch = curl_init($url); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);       
        return $picurl;
    } 

    public function doMobileTksign(){
       global $_W, $_GPC;


       if($_GPC['op']=='post'){
           $data=array(
               'weid'=>$_W['uniacid'],
               'tbuid'=>$_GPC['tbuid'],
               'sign'=>$_GPC['sign'],
               'endtime'=>$_GPC['endtime'],
               'createtime' => TIMESTAMP
           );
           $go = pdo_fetch("SELECT id FROM " . tablename($this->modulename."_tksign") . " WHERE  tbuid='{$_GPC['tbuid']}'");
            if(empty($go)){
                  $res=pdo_insert($this->modulename."_tksign",$data);
                  if($res=== false){
                    echo '授权失败';
                  }else{
                    //echo '授权成功:'.$_GPC['sign'];
                    $url=$_W['siteroot']."web/index.php?c=profile&a=module&do=setting&m=tiger_taoke";
                    message('授权成功！',$url, 'success');
                  }
            }else{                          
                  $res=pdo_update($this->modulename."_tksign", $data, array('tbuid' =>$_GPC['tbuid']));
                  if($res=== false){
                    echo '授权失败';
                  }else{
                    $url=$_W['siteroot']."web/index.php?c=profile&a=module&do=setting&m=tiger_taoke";
                    message('授权成功！',$url, 'success');
                  }
            }
       }

    }



    public function doMobileDingxiang(){
       global $_W, $_GPC;
        $cfg = $this->module['config'];
        if($cfg['miyao']!=$_GPC['miyao']){
          exit(json_encode(array('error' =>2)));
        }

        $pindex = max(1, intval($_GPC['page']));
	    $psize = 200;
		$list = pdo_fetchall("select title,price,pic_url,weid,num_iid,tjcontent,coupons_url,coupons_price,tk_rate,org_price from ".tablename($this->modulename."_tbgoods")." where weid='{$_W['uniacid']}' and dingxianurl<>'' order by id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_tbgoods')." where weid='{$_W['uniacid']}' and dingxianurl<>''");
		$pager = pagination($total, $pindex, $psize);
        //yongjin 佣金
        //echo "<pre>";
        //print_r($list);
        //exit;
        

        foreach ($list as $key => $value) {
            if($cfg['fxkg']==1){//开启返现
              $yongjin=$value['price']*$value['tk_rate']/100;
              $fanyong=$cfg['zgf']*$yongjin/100;
              $fanyong=number_format($fanyong, 2, '.', '');
            }
            

			$mc = mc_fetch($value['openid']);
			$list1[$key]['title'] = urlencode($value['title']);
			$list1[$key]['price'] = $value['price'];
            $list1[$key]['pic_url'] = $value['pic_url'];
            $list1[$key]['weid'] = $value['weid'];
            $list1[$key]['num_iid'] = $value['num_iid'];
            $list1[$key]['tjcontent'] = urlencode($value['tjcontent']);
            $list1[$key]['coupons_url'] = $value['coupons_url'];
            $list1[$key]['coupons_price'] = $value['coupons_price'];//优惠券面额
            $list1[$key]['tk_rate'] = $value['tk_rate'];//优惠券面额            
            $list1[$key]['yongjin'] = $yongjin;
            $list1[$key]['fanyong'] = $fanyong;
            $list1[$key]['org_price'] = $value['org_price'];
            //$list1[$key]['quan_id'] = $value['quan_id'];
		}
        exit(urldecode(json_encode(array('total' => $total, 'content' => $list1))));
    }


    public function doMobileDelpro(){//删除优惠券
        global $_W, $_GPC;
        $cfg = $this->module['config'];
        if($cfg['miyao']!=$_GPC['miyao']){
          exit(json_encode(array('error' =>2)));
        }
       
        $num_iid=$_GPC['num_iid'];
        if(empty($num_iid)){
          $msg=urlencode('商品已经被删除');
          exit(urldecode(json_encode(array('msg' => $msg))));
        }else{
          pdo_delete($this->modulename."_tbgoods", array('num_iid' => $num_iid));
          $msg=urlencode('删除商品成功，商品ID：'.$num_iid);
          exit(urldecode(json_encode(array('msg' => $msg))));
        }    
    }


    public function doMobileDingxiangnew(){//API
       global $_W, $_GPC;
        $cfg = $this->module['config'];
        if($cfg['miyao']!=$_GPC['miyao']){
          exit(json_encode(array('error' =>2)));
        }

        if(empty($_GPC['del'])){
            if($cfg['ljcjk']==1){
              $qf=" and qf=1";
            }
        }       
        

        $pindex = max(1, intval($_GPC['page']));
	    $psize = 200;
        if (($pindex - 1) * $psize>30000){ //一次3万条的总数都不用求了 直接返回空白
            return null;
        }
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename.'_tbgoods')." where weid='{$_W['uniacid']}' {$qf}");
        $pager = pagination($total, $pindex, $psize);
        if (($pindex - 1) * $psize>$total){ //3万内的求出总数 如果需要的数量比总数还多的 也退出已经没数据了
            return null;
        }
		$list = pdo_fetchall("select title,price,pic_url,weid,num_iid,tjcontent,coupons_url,coupons_price,tk_rate,org_price,quan_id,istmall from ".tablename($this->modulename."_tbgoods")." where weid='{$_W['uniacid']}' {$qf} order by id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		
		
        //yongjin 佣金
        //echo "<pre>";
        //print_r($list);
        //exit;
        

        foreach ($list as $key => $value) {
            if($cfg['fxkg']==1){//开启返现
              $yongjin=$value['price']*$value['tk_rate']/100;
              $fanyong=$cfg['zgf']*$yongjin/100;
              $fanyong=number_format($fanyong, 2, '.', '');
            }
            

			$mc = mc_fetch($value['openid']);
			$list1[$key]['title'] = urlencode($value['title']);//商品名称
			$list1[$key]['price'] = $value['price'];//价格
            $list1[$key]['pic_url'] = $value['pic_url'];//图片地址
            $list1[$key]['weid'] = $value['weid'];//公众号ID
            $list1[$key]['num_iid'] = $value['num_iid'];//商品ID
            $list1[$key]['tjcontent'] = urlencode($value['tjcontent']);//推荐内容
            $list1[$key]['coupons_url'] = $value['coupons_url'];//优惠券链接
            $list1[$key]['coupons_price'] = $value['coupons_price'];//优惠券面额
            $list1[$key]['tk_rate'] = $value['tk_rate'];//佣金比例          
            $list1[$key]['org_price'] = $value['org_price'];//商品原价
            $list1[$key]['quan_id'] = $value['quan_id'];//优惠券ID
            $list1[$key]['zgf'] = $cfg['zgf'];//自购返比例
            $list1[$key]['fxtype'] = $cfg['fxtype'];//0 不返  1积分  2余额
		}
        exit(urldecode(json_encode(array('total' => $total, 'content' => $list1))));
    }


    public function doMobileTklapi(){//淘口令API
       global $_W, $_GPC;
       $url=urldecode($_GPC['url']);//链接
       $img=urldecode($_GPC['img']);//图片地址
       $tjcontent=urldecode($_GPC['tjcontent']);//推荐内容    
      $taokouling=$this->tkl($url,$img,$tjcontent);
      $taokou=$taokouling->model;
      settype($taokou, 'string');
      exit($taokou);
    }

    public function dwz($url) {//短网址API
        global $_W;
        $url=urlencode($url);
        $turl=$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('openlink',array('link'=>$url)));
        $turl2=urlencode($turl);        
        
        $result='{"action":"long2short","long_url":"'.$turl.'"}';
        $access_token=$this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$access_token}";
        $ret = ihttp_request($url, $result);
        $content = @json_decode($ret['content'], true);
        Return $content['short_url'];
    }


    public function zjdwz($url) {//短网址API
        global $_W;
        //$url=urlencode($url);
        
        $result='{"action":"long2short","long_url":"'.$url.'"}';
        $access_token=$this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$access_token}";
        $ret = ihttp_request($url, $result);
        $content = @json_decode($ret['content'], true);
        Return $content['short_url'];
    }

   


    public function doMobileWxjqr() {//微信机器人
        global $_W, $_GPC;
         $cfg = $this->module['config'];
         include IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/tb.php"; 
         $str=urldecode($_GPC['str']);
         $ftype=$_GPC['type'];//  1 群   2个人机器人
         //【简约法莱绒四件套加厚保暖水晶绒秋冬款法兰绒床上三件套珊瑚绒】http://c.b1yq.com/h.czGTSy?cv=VBpSgxRnA0&sm=a52f27 点击链接，再选择浏览器打开；或复制这条信息，打开[emoji=1f449]手机淘宝[emoji=1f448][emoji=EFBFA5]VBpSgxRnA0[emoji=EFBFA5]

         $str=str_replace("[emoji=EFBFA5]", "￥", $str);
         //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".$str,FILE_APPEND);
         //exit($str);     
      


         //处理信息
         $ck = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_ck')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
         $myck=$ck['data'];
         $tksign = pdo_fetch("SELECT * FROM " . tablename("tiger_taoke_tksign") . " WHERE  tbuid='{$cfg['tbuid']}'");
         
         
         $geturl=$this->geturl($str);
         //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".$geturl,FILE_APPEND);         
         if(!empty($geturl)){
             $istao=$this->myisexists($geturl);
             if($istao==1){
                $goodid=$this->hqgoodsid($geturl);
                $turl="https://item.taobao.com/item.htm?id=".$goodid;
             }elseif($istao==2){
               $goodid=$this->mygetID($geturl);
               $turl="https://item.taobao.com/item.htm?id=".$goodid;
             }
             
            
             //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".$turl,FILE_APPEND);
             //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".$goodid,FILE_APPEND);
             if(!empty($goodid)){
               //$res=hqyongjin($turl,$myck,$cfg,$this->modulename);//链接  
               $res=hqyongjin($turl,$myck,$cfg,'tiger_taoke','','',$tksign['sign'],$tksign['tbuid'],$_W);  
             }    
         }

         if(empty($goodid)){//淘口令
           $tkl=$this->getyouhui2($str);
           //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".$str,FILE_APPEND);
           if(!empty($tkl)){
             //$res=hqyongjin($turl,$myck,$cfg,$this->modulename,$tkl,1); //淘口令
              $res=hqyongjin($turl,$myck,$cfg,'tiger_taoke',$tkl,1,$tksign['sign'],$tksign['tbuid'],$_W); 
           }
         }
       
         //处理信息结束

         
         
         if($cfg['yktype']==1){
                $rhyurl=$res['dcouponLink'];
                if(empty($rhyurl)){
                   if($res['qq']==1){
                       $rhyurl=$this->rhy($res['couponid'],$res['numid'],$cfg['qqpid']);
                     }else{
                       $rhyurl=$this->rhydx($res['couponid'],$res['numid'],$cfg['ptpid']);
                     }
                }
             }else{
                   if($res['qq']==1){
                       $rhyurl=$this->rhy($res['couponid'],$res['numid'],$cfg['qqpid']);
                     }else{
                       $rhyurl=$this->rhydx($res['couponid'],$res['numid'],$cfg['ptpid']);
                     }
             }
         

          $taokouling=$this->tkl($rhyurl,$res['pictUrl'],$res['title']);
          $taokou=$taokouling->model;
          settype($taokou, 'string');
          $taokouling=$taokou;   
           if(!empty($res['dtkl'])){
              if(empty($res['couponAmount'])){
                $erylj=$res['dshortLinkUrl'];
                $res['taokouling']=$res['dtkl'];
              }
            }
            $durl=$this->dwz($rhyurl);//短网址
          
           //入库
             $data=array(
                 'weid' => $_W['uniacid'],
                 'num_iid'=>$res['num_iid'],//商品ID
                 'title'=>$res['title'],//商品名称
                 'pic_url'=>$res['pictUrl'],//主图地址
                 'org_price'=>$res['price'],//'商品原价', 
                 'price'=>$res['qhjpric'],//商品价格,券后价
                 'tk_rate'=>$res['commissionRate'],//通用佣金
                 'quan_id'=>$res['couponid'],//'优惠券ID',  
                 'coupons_price'=>$res['couponAmount'],//优惠券面额
                 'goods_sale'=>$res['biz30day'],//月销售
                 'taokouling'=>$res['taokouling'],//淘口令
                 'coupons_end'=>strtotime($res['couponendtime']),//优惠券结束
                 'createtime'=>TIMESTAMP,
             );
             $this->addtbgoods($data);
             //入库结束
          
          
             $content=array(
                'title'=>$res['title'],//名称
                'price'=>$res['price'],//商品折扣价格
                'zyhhprice'=>$res['zyhhprice'],//优惠后价格
                'zyh'=>$res['zyh'],//优惠金额
                'ehyurl'=>$rhyurl,//二合一链接
                'couponAmount'=>$res['couponAmount'],//优惠券金额
                'flyj'=>number_format($res['flyj'],2),//自购佣金
                'taokouling'=>$taokouling,//淘口令
                'couponid'=>$res['couponid'],//优惠券ID
                'couponendtime'=>$res['couponendtime'],//优惠券到期时间
                'numid'=>$res['numid'],//商品ID
                'pictUrl'=>$res['pictUrl'],
                'qq'=>$res['qq'], //1鹊桥 0定向普通
            );

            if($cfg['fxtype']==1){
              $fxje=round($content['flyj']);
            }else{
              $fxje=$content['flyj'];
            }

            $picurl=$res['pictUrl'];
            $msg=str_replace('#名称#',$content['title'],  $cfg['jqrflmsg']);
            $msg=str_replace('#原价#',$content['price'], $msg);
            $msg=str_replace('#图片#',$picurl, $msg);
            $msg=str_replace('#惠后价#',$content['zyhhprice'], $msg);
            $msg=str_replace('#券后价#',$res['qhjpric'], $msg);

            $msg=str_replace('#总优惠#',$content['zyh'], $msg);
            if(empty($content['couponAmount'])){
              $content['couponAmount']='该商品暂无优惠券';
            }
            $msg=str_replace('#优惠券#',$content['couponAmount'], $msg);
            $msg=str_replace('#返现金额#',$fxje, $msg);
            if($ftype==1){
              $msg.="[lj1]".$rhyurl."[lj2]";
              $msg.="[bt1]".$content['title']."[bt2]";
            }else{
               $msg=str_replace('#淘口令#',$content['taokouling'], $msg);//这里放到软件上面执行
               $msg=str_replace('#短网址#',$durl, $msg);
            }
            
            

            //exit('aaa');
            if(empty($taokouling)){
               //exit(urldecode(json_encode(array('error' =>1, 'content' =>urlencode($res['error'])))));
               exit($res['error']);
            }else{
            //上报日志
            $arr=array(
               'pid'=>$cfg['ptpid'],
               'account'=>"无",
               'mediumType'=>"微信群",
               'mediumName'=>"老虎内部券".rand(10,100),
               'itemId'=>$res['numid'],
               'originUrl'=>"https://item.taobao.com/item.htm?id=".$res['numid'],
               'tbkUrl'=>$rhyurl,
               'itemTitle'=>$content['title'],
               'itemDescription'=>$content['title'],
               'tbCommand'=>$content['taokouling'],
               'extraInfo'=>"无",
            );
            include IA_ROOT . "/addons/tiger_taoke/inc/sdk/taoapi.php"; 
            $resp=getapi($arr);
            //日志结束
               //exit(urldecode(json_encode(array('error' =>0, 'content' => urlencode($msg)))));
               exit($msg);
            }
    }

    public function addtbgoods($data) {
         $cfg = $this->module['config']; 
        if($cfg['cxrk']==1){//选择入库才会入数据库
           if(empty($data['num_iid'])){
              Return '';
            }
            $go = pdo_fetch("SELECT id FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$data['weid']}' and  num_iid='{$data['num_iid']}'");
            if(empty($go)){
              pdo_insert($this->modulename."_tbgoods",$data);
            }else{
              pdo_update($this->modulename."_tbgoods", $data, array('weid'=>$data['weid'],'num_iid' => $data['num_iid']));
            }
        }
                
    }

    public function mygetID($url) {//获取链接商品ID
       if (preg_match("/[\?&]id=(\d+)/",$url,$match)) {
          return $match[1];
       } else {
          return '';
       }
    }

    public function getyouhui2($str){
        preg_match_all('|(￥[^￥]+￥)|ism', $str, $matches);
        return $matches[1][0];
    }

    public function geturl($str) {//获取链接
        $exp = explode('http', $str);
        //$url = 'http' . trim($exp[1]) . '中国';
        $url = 'http' . trim($exp[1]) . ' ';
        //preg_match('/[\x{4e00}-\x{9fa5}]/u', $url, $matches, PREG_OFFSET_CAPTURE);
        preg_match('/[\s]/u', $url, $matches, PREG_OFFSET_CAPTURE); 
        $url = substr($url, 0, $matches[0][1]);
        if($url=='http'){
          Return '';
        }else{
          return $url;
        }        
    }


    public function myisexists($url) {//判断是不是淘宝的地址
//       if (stripos($url,'mashort.cn')!==false) {
//          return 1;
//       }
//       if (stripos($url,'e22a.com')!==false) {
//          return 1;
//       }
//       if (stripos($url,'sjtm.me')!==false) {
//          return 1;
//       }
//       if (stripos($url,'laiwang.com')!==false) {
//          return 1;
//       }
       if (stripos($url,'taobao.com')!==false) {
          return 2;
       }elseif(stripos($url,'tmall.com')!==false) {
          return 2;
       }elseif(stripos($url,'tmall.hk')!==false) {
          return 2;
       }else{
          return 1;
       }
       return 0;
    }

    public function hqgoodsid($url) {//e22a获取ID
        //http://item.taobao.com/item.htm?id=540728402188&from=tbkfenxiangyoushang&fromScene=100&publishUserId
        //'http://item.taobao.com/item.htm?ut_sk=1.V5/73bfSri4DABBUs3mInifZ_21380790_1482201165164.Copy.1&id=23246340317&sourceType=item&
        //如果是e22a的域名就用这个获取商品ID
        //$str = $this->utf8_gbk(file_get_contents($url));
        $str = file_get_contents($url);        
		$str=str_replace("\"", "", $str);
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".$str,FILE_APPEND);
		$goodsid=$this->Text_qzj($str,"?id=","&");
        if(empty($goodsid)){
          $goodsid=$this->Text_qzj($str,"&id=","&");
        }
        if(empty($goodsid)){
           $goodsid=$this->Text_qzj($str,"itemId:",",");
        }
        if(empty($goodsid)){
            $url=$this->Text_qzj($str,"url = '","';");
            $goodsid=$this->Text_qzj($str,"com/i",".htm");
            file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($goodsid),FILE_APPEND);
        }
        
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n goodsid:".json_encode("--------------"),FILE_APPEND);
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n goodsid:".json_encode($goodsid),FILE_APPEND);
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n goodsid:".json_encode("--------------"),FILE_APPEND);
        Return $goodsid;
    }

    public function Text_qzj($Text,$Front,$behind) {
				//语法：strpos(string,find,start)
				//函数返回字符串在另一个字符串中第一次出现的位置，如果没有找到该字符串，则返回 false。
				//参数描述：
				//string 必需。规定被搜索的字符串。
				//find   必需。规定要查找的字符。
				//start  可选。规定开始搜索的位置。
				
				//语法：string mb_substr($str,$start,$length,$encoding)
				//参数描述：
				//str      被截取的母字符串。
				//start    开始位置。
				//length   返回的字符串的最大长度,如果省略，则截取到str末尾。
				//encoding 参数为字符编码。如果省略，则使用内部字符编码。
					
					$t1 = mb_strpos(".".$Text,$Front);
					if($t1==FALSE){
						return "";
					}else{
						$t1 = $t1-1+strlen($Front);
					}
					$temp = mb_substr($Text,$t1,strlen($Text)-$t1);
					$t2 = mb_strpos($temp,$behind);
					if($t2==FALSE){
						return "";
					}
					return mb_substr($temp,0,$t2);
				}


    function gstr($str)
    {   
    $encode = mb_detect_encoding( $str, array('ASCII','UTF-8','GB2312','GBK'));
    if ( !$encode =='UTF-8' ){
    $str = iconv('UTF-8',$encode,$str);
    }
    return $str;
    }


    public function doMobileHelp(){
       global $_W, $_GPC;
       $cfg = $this->module['config'];

       include $this->template ( 'tbgoods/style1/help' );
    }


    public function doMobileGetCoupon(){
        global $_W, $_GPC;
        
            $cfg = $this->module['config'];
            $tksign = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_tksign") . " WHERE  tbuid='{$cfg['tbuid']}'");
            $id=$_GPC['id'];
            $openid=$_GPC['openid'];
            $dluid=$_GPC['dluid'];
             if(!empty($dluid)){
                  $share=pdo_fetch("select * from ".tablename('tiger_taoke_share')." where weid='{$_W['uniacid']}' and id='{$dluid}'");
                }else{
                  $fans=mc_oauth_userinfo();
                  $openid=$fans['openid'];
                  $zxshare=pdo_fetch("select * from ".tablename('tiger_taoke_share')." where weid='{$_W['uniacid']}' and from_user='{$openid}'");
                }
                if($zxshare['dltype']==1){
                    if(!empty($zxshare['dlptpid'])){
                      $cfg['ptpid']=$zxshare['dlptpid'];
                      $cfg['qqpid']=$zxshare['dlqqpid'];
                    }
                }else{
                   if(!empty($zxshare['helpid'])){//查询有没有上级
                         $sjshare=pdo_fetch("select * from ".tablename('tiger_taoke_share')." where weid='{$_W['uniacid']}' and dltype=1 and openid='{$zxshare['helpid']}'");
                    }
                }
                

                if(!empty($sjshare['dlptpid'])){
                    if(!empty($sjshare['dlptpid'])){
                      $cfg['ptpid']=$sjshare['dlptpid'];
                      $cfg['qqpid']=$sjshare['dlqqpid'];
                    }                    
                }else{
                   if($share['dlptpid']){
                       if(!empty($share['dlptpid'])){
                         $cfg['ptpid']=$share['dlptpid'];
                         $cfg['qqpid']=$share['dlqqpid'];
                       }
                    }
                }
            
            include IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/tb.php"; 

            if(empty($id) || $id=='undefined'){//联盟产品

                $ck = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_ck')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
                $myck=$ck['data'];
                $turl="https://item.taobao.com/item.htm?id=".$_GPC['num_iid'];
                $res=hqyongjin($turl,$myck,$cfg,$this->modulename,'','',$tksign['sign'],$tksign['tbuid'],$_W);
                $rhyurl=$res['dclickUrl'];
                
                $num_iid=$_GPC['num_iid'];
                $views['coupons_price']=$_GPC['coupons_price'];
                $views['price']=$_GPC['price'];
                $views['org_price']=$_GPC['org_price'];
                $views['title']=$_GPC['title'];
                $views['pic_url']=$_GPC['pict_url'];
                $views['tk_rate']=$_GPC['tk_rate'];

                if($cfg['tkltype']==1){
                   $views['taokouling']=gettkl($rhyurl,$_GPC['title'],$_GPC['pict_url']);
                }else{
                   $taokouling=$this->tkl($rhyurl,$_GPC['pict_url'],$_GPC['title']);
                  $taokou=$taokouling->model;
                  settype($taokou, 'string');
                  $views['taokouling']=$taokou;  
                }
                  $yongjin=$views['price']*$views['tk_rate']/100;//佣金
                   if($cfg['fxtype']==1){//积分           
                        $flyj=intval($yongjin*$cfg['zgf']/100*$cfg['jfbl']);//自购佣金
                        $flyj=intval($flyj);

                        $lx=$cfg["hztype"];
                    }else{//余额
                        $yongjin=number_format($yongjin, 2, '.', ''); 
                        $flyj=$yongjin*$cfg['zgf']/100;//自购佣金
                        $flyj=number_format($flyj, 2, '.', ''); 
                        $lx=$cfg["yetype"];
                        if($cfg['txtype']==3){
                            $flyj=$flyj*100;
                            $lx='集分宝';            
                        }
                    }
                  if(empty($views['org_price'])){
                      $iosmsg="【商品】".$_GPC['title']."<br/>【优惠券】".$views['coupons_price']."元<br/>【券后价】".$views['price']."元<br>-------------<br/>【商品领券下单】长按复制这条信息，打开【手机淘宝】可领券并下单".$views['taokouling'];
                      $msga="【商品】".$_GPC['title']."\r\n【优惠券】".$views['coupons_price']."元\r\n【券后价】".$views['price']."元\r\n-------------\r\n【商品领券下单】长按复制这条信息，打开【手机淘宝】可领券并下单".$views['taokouling'];
                   }else{
                      $iosmsg="【商品】".$_GPC['title']."<br/>【原价】".$views['org_price']."元<br/>【优惠券】".$views['coupons_price']."元<br/>【券后价】".$views['price']."元<br/>-------------<br/>【商品领券下单】长按复制这条信息，打开【手机淘宝】可领券并下单".$views['taokouling'];
                      $msga="【商品】".$_GPC['title']."\r\n【原价】".$views['org_price']."元\r\n【优惠券】".$views['coupons_price']."元\r\n【券后价】".$views['price']."元\r\n-------------\r\n【商品领券下单】长按复制这条信息，打开【手机淘宝】可领券并下单".$views['taokouling'];
                   }
            }else{
            
           if(empty($id)){
             $id=$_GPC['commodityID'];
           }
           
           if(!empty($id)){
              $views=pdo_fetch("select * from".tablename($this->modulename."_tbgoods")." where weid='{$_W['uniacid']}' and id='{$id}'");
              $fzlist4 = pdo_fetchall("select * from ".tablename($this->modulename."_tbgoods")." where weid='{$_W['uniacid']}' and type='{$views['type']}' order by px desc limit 4");
              $ck = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_ck')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
               $myck=$ck['data'];
               $turl="https://item.taobao.com/item.htm?id=".$views['num_iid'];
               $res=hqyongjin($turl,$myck,$cfg,$this->modulename,'','',$tksign['sign'],$tksign['tbuid'],$_W);
               $erylj=$res['dclickUrl']."&activityId=".$views['quan_id'];
            }

    
              $tjcontent=$views['title'];
               
               if($cfg['tkltype']==1){
                  $views['taokouling']=gettkl($erylj,$tjcontent,$res['pictUrl']);
                }else{
                    $taokouling=$this->tkl($erylj,$res['pictUrl'],$tjcontent);
                    $taokou=$taokouling->model;
                    settype($taokou, 'string');
                    $views['taokouling']=$taokou;
                }
              

           $yongjin=$views['price']*$views['tk_rate']/100;//佣金
           if($cfg['fxtype']==1){//积分           
                $flyj=intval($yongjin*$cfg['zgf']/100*$cfg['jfbl']);//自购佣金
                $flyj=intval($flyj);

                $lx=$cfg["hztype"];
            }else{//余额
                $yongjin=number_format($yongjin, 2, '.', ''); 
                $flyj=$yongjin*$cfg['zgf']/100;//自购佣金
                $flyj=number_format($flyj, 2, '.', ''); 
                $lx=$cfg["yetype"];
                if($cfg['txtype']==3){
                    $flyj=$flyj*100;
                    $lx='集分宝';            
                }
            }
           //
           if(empty($views['org_price'])){
              $iosmsg="【商品】".$tjcontent."<br/>【优惠券】".$views['coupons_price']."元<br/>【券后价】".$views['price']."元<br>-------------<br/>【商品领券下单】长按复制这条信息，打开【手机淘宝】可领券并下单".$views['taokouling'];
              $msga="【商品】".$tjcontent."\r\n【优惠券】".$views['coupons_price']."元\r\n【券后价】".$views['price']."元\r\n-------------\r\n【商品领券下单】长按复制这条信息，打开【手机淘宝】可领券并下单".$views['taokouling'];
           }else{
              $iosmsg="【商品】".$tjcontent."<br/>【原价】".$views['org_price']."元<br/>【优惠券】".$views['coupons_price']."元<br/>【券后价】".$views['price']."元<br/>-------------<br/>【商品领券下单】长按复制这条信息，打开【手机淘宝】可领券并下单".$views['taokouling'];
              $msga="【商品】".$tjcontent."\r\n【原价】".$views['org_price']."元\r\n【优惠券】".$views['coupons_price']."元\r\n【券后价】".$views['price']."元\r\n-------------\r\n【商品领券下单】长按复制这条信息，打开【手机淘宝】可领券并下单".$views['taokouling'];
           }
      }


           

           //上报日志
            $arr=array(
               'pid'=>$cfg['ptpid'],
               'account'=>"无",
               'mediumType'=>"微信群",
               'mediumName'=>"老虎内部券".rand(10,1000),
               'itemId'=>$views['num_iid'],
               'originUrl'=>"https://item.taobao.com/item.htm?id=".$views['numid'],
               'tbkUrl'=>$rhyurl,
               'itemTitle'=>$views['title'],
               'itemDescription'=>$views['title'],
               'tbCommand'=>$views['taokouling'],
               'extraInfo'=>"无",
            );
            include IA_ROOT . "/addons/tiger_taoke/inc/sdk/taoapi.php"; 
            $resp=getapi($arr);
            //日志结束


           exit(json_encode(array('code'=>10000,'msg' =>'申请成功','commission'=>$flyj.$lx, 'url' => $msga,'iosmsg'=>$iosmsg))); 
           //{"code":10000,"msg":"申请成功","commission":0.00,"url":"复制框内整段文字，打开【手机淘宝】即可【领取优惠券】并购买￥M96Nhv7VEt￥"}
    }


    public function doMobileEwmin(){
       global $_W, $_GPC;
       $url=urldecode($_GPC['url']);
       return 'http://pan.baidu.com/share/qrcode?w=150&h=150&url='.$url;
        //
       //$this->ewm($url);
    }


    public function ewm($url){
        include "phpqrcode.php";
        $value=$url;
        $errorCorrectionLevel = "L";
        $matrixPointSize = "4";
        QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize);
        exit;  

    }


    public function sendNews($openid,$text) {
        global $_W, $_GPC;
       $url=$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('index'));
        $custom = array(
                'touser' => $openid,
				'msgtype' => 'news',
				'news' => array(
                              'articles'=>array(
                                              array(
                                               'title' => urlencode('晒单奖励提醒'),
                                               'description' => urlencode($text),
                                               'url' => $url,
                                               'picurl' => '',
                                              )
                                          )
                               ),
				
			);
        $result =urldecode(json_encode($custom));
        //$result='{"touser":"'.$openid.'","msgtype":"news","news":{"articles":[{"title":"'.$news['title'].'","description":"'.$news['description'].'","url":"'.$news['url'].'","picurl":"'.$news['picurl'].'"}]}}';
        $access_token=$this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        $ret = ihttp_request($url, $result);
		return $ret;
	}





    public function postText($openid, $text) {
        //$text1=addslashes($text);
		$post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
		$ret = $this->postRes($this->getAccessToken(), $post);
		return $ret;
	}

    private function postRes($access_token, $data) {
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
		load()->func('communication');
		$ret = ihttp_request($url, $data);
		$content = @json_decode($ret['content'], true);
		return $content['errcode'];
	}

    private function getAccessToken() {
		global $_W;
		load()->model('account');
		$acid = $_W['acid'];
		if (empty($acid)) {
			$acid = $_W['uniacid'];
		}
		$account = WeAccount::create($acid);
		//$token = $account->fetch_available_token();
        $token = $account->getAccessToken();
		return $token;
	}

    public function doWebYjjc(){
        global $_W, $_GPC;
        $cfg = $this->module['config'];
        $tksign = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_tksign") . " WHERE  tbuid='{$cfg['tbuid']}'");
        include IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/tb.php"; 
        $num_iid=$_GPC['key'];
        if(empty($num_iid)){
           $msg='请输入商品ID';
        }
        if($_GPC['op']=='seach'){
           $ck = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_ck')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
           $myck=$ck['data'];
           $turl="https://item.taobao.com/item.htm?id=".$num_iid;
           $res=hqyongjin($turl,$myck,$cfg,$this->modulename,'','',$tksign['sign'],$tksign['tbuid'],$_W); 
           //echo '<pre>';
           //print_r($res);
        }                 
       
       
       include $this->template ( 'yjjc' );   
    }

    public function doWebMCreate() {
		// 这个操作被定义用来呈现 管理中心导航菜单
		global $_W, $_GPC;
		$do = 'mcreate';
		$op = $_GPC ['op'];
		$id = $_GPC ['id'];
		$item = pdo_fetch ( 'select * from ' . tablename ( $this->modulename . "_poster" ) . " where id='{$id}'" );
        
		if (checksubmit ()) {
			$ques = $_GPC['ques'];
			$answer = $_GPC['answer'];
			$questions = '';
			foreach ($ques as $key => $value) {
				if (empty($value)) continue;
				$questions[] = array('question'=>$value,'answer'=>$answer[$key]);
			}
            //echo '<pre>';
        //print_r($_GPC);
        //exit;
			
			$data = array (
			'weid'=> $_W['uniacid'],
            'rtype'=> $_GPC ['rtype'],
            'title' => $_GPC ['title'],
            'type' => $_GPC ['type'],
            'bg' => $_GPC ['bg'],
            'data' => htmlspecialchars_decode($_GPC ['data']),
            'weid' => $_W ['uniacid'],
            'score' => $_GPC ['score'],
            'cscore' => $_GPC ['cscore'],
            'pscore' => $_GPC ['pscore'],
            'scorehb' => $_GPC ['scorehb'],
            'cscorehb' => $_GPC ['cscorehb'],
            'pscorehb' => $_GPC ['pscorehb'],
            'rscore' => $_GPC ['rscore'],
            'gid' => $_GPC ['gid'],
            'kdtype' => $_GPC ['kdtype'],
            'winfo1' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['winfo1']),ENT_QUOTES),
            'winfo2' =>htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['winfo2']),ENT_QUOTES),
            'winfo3' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['winfo3']),ENT_QUOTES),
            'stitle' => serialize($_GPC ['stitle']),
            'sthumb' => serialize($_GPC ['sthumb']),
            'sdesc' => serialize($_GPC ['sdesc']),
            'rtips' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['rtips']),ENT_QUOTES),
            'ftips' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['ftips']),ENT_QUOTES),
            'utips' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['utips']),ENT_QUOTES),
            'utips2' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['utips2']),ENT_QUOTES),
            'wtips' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['wtips']),ENT_QUOTES),
            'nostarttips' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['nostarttips']),ENT_QUOTES),
            'endtips' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['endtips']),ENT_QUOTES),
            'starttime' => strtotime($_GPC['starttime']),
            'endtime' => strtotime($_GPC['endtime']),
            'surl' => serialize($_GPC ['surl']),
            'kword' => $_GPC ['kword'],
            'credit' => $_GPC ['credit'],
            'doneurl' => $_GPC ['doneurl'],
            'tztype' => $_GPC ['tztype'],            
            'slideH' => $_GPC ['slideH'],
            'mbcolor' => $_GPC ['mbcolor'],

            

            'mbstyle' => $_GPC ['mbstyle'],
            'mbfont' => $_GPC ['mbfont'],
            'sliders' => $_GPC ['sliders'],
            'mtips' => $_GPC ['mtips'],            
            'sharetitle' => $_GPC ['sharetitle'],
            'sharethumb' => $_GPC ['sharethumb'],
            'sharedesc' => $_GPC ['sharedesc'],
            'sharegzurl' => $_GPC ['sharegzurl'],
            'tzurl' => $_GPC ['tzurl'],
            'questions' => serialize($questions),
            'createtime' =>time(),
			);
			if ($id) {
				if (pdo_update ( $this->modulename . "_poster", $data, array (
						'id' => $id
				) ) === false) {
					message ( '更新海报失败！1' );
				} else{
					if (empty($item['rid'])){
						$this->createRule($_GPC['kword'],$id);
					}elseif ($item['kword'] != $data['kword']){
						//修改生成二维码和扫码的关键字
						pdo_update('rule_keyword',array('content'=>$data['kword']),array('rid'=>$item['rid']));
						pdo_update('qrcode',array('keyword'=>$data['kword']),array('name'=>$this->modulename,'keyword'=>$item['kword']));
					}
					message ( '更新海报成功！2', $this->createWebUrl ( 'mposter' ) );
				}
			} else {
				$data['rtype'] = $_GPC['rtype'];
				$data ['createtime'] = time ();
				if (pdo_insert ( $this->modulename . "_poster", $data ) === false) {
					message ( '生成海报失败！3' );
				} else{
					$this->createRule($_GPC['kword'],pdo_insertid());
					message ( '生成海报成功！4', $this->createWebUrl ( 'mposter' ) );
				}
					
			}
		}
		load ()->func ( 'tpl' );
		if ($item){
			$data = json_decode(str_replace('&quot;', "'", $item['data']), true);
			$size = getimagesize(toimage($item['bg']));
			$size = array($size[0]/2,$size[1]/2);
			$date = array('start'=>date('Y-m-d H:i:s',$item['starttime']),'end'=>date('Y-m-d H:i:s',$item['endtime']));
			$titles = unserialize($item['stitle']);
			$thumbs = unserialize($item['sthumb']);
			$sdesc = unserialize($item['sdesc']);
			$surl = unserialize($item['surl']);
			foreach ($titles as $key => $value) {
				if (empty($value)) continue;
				$slist[] = array('stitle'=>$value,'sdesc'=>$sdesc[$key],'sthumb'=>$thumbs[$key],'surl'=>$surl[$key]);
			}
		}else $date = array('start'=>date('Y-m-d H:i:s',time()),'end'=>date('Y-m-d H:i:s',time()+7*24*3600));
		//$groups = pdo_fetchall('select * from '.tablename('mc_groups')." where uniacid='{$_W['uniacid']}' order by isdefault desc");
		include $this->template ( 'mcreate' );
	}




    public function createRule($kword,$pid){
		global $_W;
		$rule = array(
				'uniacid' => $_W['uniacid'],
				'name' => $this->modulename,
				'module' => $this->modulename,
				'status' => 1,
				'displayorder' => 254,
		);
		pdo_insert('rule',$rule);
		unset($rule['name']);
		$rule['type'] = 1;
		$rule['rid'] = pdo_insertid();
		$rule['content'] = $kword;
		pdo_insert('rule_keyword',$rule);
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($pid.'----'.$rule['rid']),FILE_APPEND);
		pdo_update($this->modulename."_poster",array('rid'=>$rule['rid']),array('id'=>$pid));
	}

    


    public function doMobileAjaxrank(){
		global $_W, $_GPC;     
        $weid = $_GPC['weid'];
        $last = $_GPC['last'];
        $amount = $_GPC['amount'];
        $shares=pdo_fetchall("select m.nickname,m.avatar,m.credit1 FROM ".tablename('mc_members')." m LEFT JOIN ".tablename('mc_mapping_fans')." f ON m.uid=f.uid where f.follow=1 and f.uniacid='{$weid}' and m.credit1<>0 order by credit1 desc limit $last,$amount");	
        //print_r($shares);
                
		echo json_encode($shares);
	}

    public function doMobileOpenview(){
       global $_W, $_GPC;
       $url=$_GPC['link'];
       $cfg = $this->module['config'];
       include $this->template ('openview');
    }


    public function doMobileOpenlink(){
       global $_W, $_GPC;
       $url=urldecode($_GPC['link']);
       $cfg = $this->module['config'];
       include $this->template ('openlink');
    }

    public function doMobileTzview(){
       global $_W, $_GPC;
       $url=urldecode($_GPC['link']);
       $goodsid=$_GPC['goodsid'];
       $price=$_GPC['price'];
       $man=$_GPC['man'];

       if(!empty($goodsid)){
         $views=pdo_fetch("select * from".tablename($this->modulename."_tbgoods")." where weid='{$_W['uniacid']}' and id='{$goodsid}'");
       }else{
         echo '商品不存在，已删除！';
         exit;
       }
       $cfg = $this->module['config'];
       $taokouling=$this->tkl($url,$views['pictUrl'],$views['title']);
       $taokou=$taokouling->model;
       settype($taokou, 'string');
       $taokouling=$taokou;
       
       include $this->template ('/tbgoods/style9/tzview');
    }


    public function doMobileView(){
       global $_W, $_GPC;
       $cfg = $this->module['config'];
       $dluid=$_GPC['dluid'];//share id
       $id=$_GPC['id'];
       $pc=$_GPC['pc'];
       $tksign = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_tksign") . " WHERE  tbuid='{$cfg['tbuid']}'");
       //print_r($tksign);
       //exit;



       if(pdo_tableexists('tiger_wxdaili_set')){
          $bl=pdo_fetch("select * from ".tablename('tiger_wxdaili_set')." where weid='{$_W['uniacid']}'");
       }
       //print_r($bl);

        if(!empty($dluid)){
          $share=pdo_fetch("select * from ".tablename('tiger_taoke_share')." where weid='{$_W['uniacid']}' and id='{$dluid}'");
        }else{
          $fans=mc_oauth_userinfo();
          $openid=$fans['openid'];
          $zxshare=pdo_fetch("select * from ".tablename('tiger_taoke_share')." where weid='{$_W['uniacid']}' and from_user='{$openid}'");
        }
        if($zxshare['dltype']==1){
            if(!empty($zxshare['dlptpid'])){
               $cfg['ptpid']=$zxshare['dlptpid'];
               $cfg['qqpid']=$zxshare['dlqqpid'];
            }
            
        }else{
           if(!empty($zxshare['helpid'])){//查询有没有上级
                 $sjshare=pdo_fetch("select * from ".tablename('tiger_taoke_share')." where weid='{$_W['uniacid']}' and dltype=1 and openid='{$zxshare['helpid']}'");           
            }
        }
        

        if(!empty($sjshare['dlptpid'])){
            if(!empty($sjshare['dlptpid'])){
              $cfg['ptpid']=$sjshare['dlptpid'];
              $cfg['qqpid']=$sjshare['dlqqpid'];
            }            
            $dlewm="http://pan.baidu.com/share/qrcode?w=150&h=150&url=".$sjshare['url'];
        }else{
           if($share['dlptpid']){
               if(!empty($share['dlptpid'])){
                 $cfg['ptpid']=$share['dlptpid'];
                 $cfg['qqpid']=$share['dlqqpid'];
               }               
               $dlewm="http://pan.baidu.com/share/qrcode?w=150&h=150&url=".$share['url'];
            }
        }

         include IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/tb.php"; 


          if(empty($id) || $id=='undefined'){//联盟产品
             $views['num_iid']=$_GPC['num_iid'];
             $views['org_price']=$_GPC['org_price'];
             $views['price']=$_GPC['price'];
             $views['coupons_price']=$_GPC['coupons_price'];
             $views['goods_sale']=$_GPC['goods_sale'];             
             $views['title']=$_GPC['title'];
             $views['pic_url']=$_GPC['pic_url'];
             $pid=$_GPC['pid'];
             if(!empty($pid) and $pid!=='undefined'){
               $cfg['ptpid']=$pid;
               $views['pid']=$pid;
               $pidSplit=explode('_',$cfg['ptpid']);
               $cfg['siteid']=$pidSplit[2];
               $cfg['adzoneid']=$pidSplit[3];
             }
             //echo $cfg['ptpid'];
             //exit;
             //echo '<pre>';
             //echo $cfg['ptpid'];
             //print_r($cfg);
             //exit;


             $ck = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_ck')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
             $myck=$ck['data'];
             $turl="https://item.taobao.com/item.htm?id=".$_GPC['num_iid'];
             $res=hqyongjin($turl,$myck,$cfg,$this->modulename,'','',$tksign['sign'],$tksign['tbuid'],$_W); 
             //echo '<pre>';
             //print_r($res);
             //exit;
             $views['tk_rate']=$res['commissionRate'];
             $_GPC['url']=$res['dclickUrl'];
             $views['url']=$res['dclickUrl'];
          }else{
               if(!empty($id)){
                  $views=pdo_fetch("select * from".tablename($this->modulename."_tbgoods")." where weid='{$_W['uniacid']}' and id='{$id}'");
                  $fzlist3 = pdo_fetchall("select * from ".tablename($this->modulename."_tbgoods")." where weid='{$_W['uniacid']}' and type='{$views['type']}' order by px desc limit 3");
                  $ck = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_ck')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
                   $myck=$ck['data'];
                   $turl="https://item.taobao.com/item.htm?id=".$views['num_iid'];
                   $res=hqyongjin($turl,$myck,$cfg,$this->modulename,'','',$tksign['sign'],$tksign['tbuid'],$_W);
                   $rhyurl=$res['dclickUrl']."&activityId=".$views['quan_id'];
                }
          }


          //echo '<pre>';
          //print_r($res);
          //exit;

           //申请定向-----------------
           
//           $daytime=strtotime('today');//今天时间戳
//           if($views['dxtime']>$daytime){//当天已经申请过，就不在申请
//               $res['couponid']=$views['quan_id'];
//               $res['pictUrl']=$views['pic_url'];
//               $lxtype=$views['lxtype'];
//           }else{               
//               $ck = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_ck')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
//               $myck=$ck['data'];
//               $turl="https://item.taobao.com/item.htm?id=".$views['num_iid'];
//               //$res=hqyongjin($turl,$myck,$cfg,$this->modulename'');
//               $lxtype=$views['lxtype'];
//               //if($views['lxtype']==1){
//                  $res=hqyongjin($turl,$myck,$cfg,$this->modulename,'','',$tksign['sign'],$tksign['tbuid'],$_W); 
//                   if(empty($res['error'])){
//                      if(!empty($id)){
//                         pdo_update ($this->modulename . "_tbgoods", array('lxtype'=>$res['qq'],'dxtime'=>time()), array ('id' => $id)); 
//                      }
//                   }
//                 //}
//           }
           
           
           //echo '<pre>';
           //print_r($res);
           
           //echo $rhyurl ;
           //exit;
           


           

            //PC网站-----------------------------------
            if($pc==1){
              header("location:".$rhyurl);
            }
            //PC结束

            //echo $lxtype;
            //exit;
            

       $tjcontent=$views['title'];
       if($cfg['tkltype']==1){
              $views['taokouling']=gettkl($rhyurl,$tjcontent,$res['pictUrl']);
       }else{
              $taokouling=$this->tkl($rhyurl,$views['pic_url'],$tjcontent);
              $taokou=$taokouling->model;
              settype($taokou, 'string');
              $views['taokouling']=$taokou;
       }

       $fans=$_W['fans'];
       if(!empty($fans['openid'])){
         $scgoods = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_shoucang") . " WHERE weid = '{$_W['uniacid']}' and goodsid='{$views['id']}' and openid='{$fans['openid']}'");
       }

       //
       $yongjin=$views['price']*$views['tk_rate']/100;//佣金
       if($cfg['fxtype']==1){//积分           
            $flyj=intval($yongjin*$cfg['zgf']/100*$cfg['jfbl']);//自购佣金
            $lx=$cfg["hztype"];
        }else{//余额
            $yongjin=number_format($yongjin, 2, '.', ''); 
            $flyj=$yongjin*$cfg['zgf']/100;//自购佣金
            $flyj=number_format($flyj, 2, '.', ''); 
            $lx=$cfg["yetype"];
            if($cfg['txtype']==3){
                $flyj=$flyj*100;
                $lx='集分宝';            
            }
        }


          //上报日志-----------------------
            $arr=array(
               'pid'=>$cfg['ptpid'],
               'account'=>"无",
               'mediumType'=>"微信群",
               'mediumName'=>"老虎内部券".rand(10,100),
               'itemId'=>$views['num_iid'],
               'originUrl'=>"https://item.taobao.com/item.htm?id=".$views['numid'],
               'tbkUrl'=>$rhyurl,
               'itemTitle'=>$views['title'],
               'itemDescription'=>$views['title'],
               'tbCommand'=>$views['taokouling'],
               'extraInfo'=>"无",
            );
            include IA_ROOT . "/addons/tiger_taoke/inc/sdk/taoapi.php"; 
            $resp=getapi($arr);
//            echo '<pre>';
//            print_r($arr);
//            print_r($resp);
//            exit;
            //日志结束

       $msg = pdo_fetchall("SELECT * FROM " . tablename($this->modulename."_msg") . " WHERE weid = '{$_W['uniacid']}' order by rand() desc limit 50");
       $url=$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('view',array('id'=>$views['id'],'dluid'=>$dluid,'num_iid'=>$views['num_iid'],'org_price'=>$views['org_price'],'price'=>$views['price'],'coupons_price'=>$views['coupons_price'],'goods_sale'=>$views['goods_sale'],'url'=>$rhyurl,'title'=>$views['title'],'pic_url'=>$views['pic_url'],'pid'=>$cfg['ptpid'])));
       $dwzurl=$this->zjdwz($url);
       
       $emw="http://pan.baidu.com/share/qrcode?w=150&h=150&url=".$dwzurl;
       //$emw=$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('ewmin',array('url'=>$url)));
       //echo '<pre>';
       //print_r($res);
      // exit;
       


       $style=$cfg['qtstyle'];
        if(empty($style)){
            $style='style1';        
        }

        if(empty($id) || $id=='undefined'){//联盟产品
          $rhyurl=urlencode($_GPC['url']);
        }else{
          $rhyurl=urlencode($rhyurl);
        }
        //echo '<pre>';
        //print_r($views);
        //exit;
     

 

       //include $this->template ( 'tbgoods/'.$style.'/view' );
        include $this->template ( 'tbgoods/style9/view' );
    }

    public function gettaogoods($numid,$api){
         $c = new TopClient;
         $c->appkey = $api['appkey'];
         $c->secretKey =$api['secretKey'];
         $req = new TbkItemInfoGetRequest;
         $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
         $req->setPlatform("1");
         $req->setNumIids($numid);
         $resp = $c->execute($req);
         $resp=json_decode(json_encode($resp),TRUE);
         $arr=$resp['results']['n_tbk_item'];  
         return $arr;
    }

    public function goodlist($key,$pid,$page){
       require_once IA_ROOT . "/addons/tiger_taoke/inc/sdk/getpic.php";
       $api=taobaopp($tiger);
       $c = new TopClient;
       $c->appkey = $api['appkey'];
       $c->secretKey =$api['secretKey'];
       $req = new TbkItemCouponGetRequest;
       $req->setPlatform("2");
       //$req->setCat("16,18");
       $req->setPageSize("20");//每页几条
       $req->setQ($key);
       $req->setPageNo($page);//第几页
       $req->setPid($pid);
       $resp = $c->execute($req);
       $resp=json_decode(json_encode($resp),TRUE);
       $goods=$resp['results']['tbk_coupon'];
       foreach($goods as $k=>$v){
//          $tkyj=intval($v['commission_rate']);
//          if($tkyj<10){
//            continue;
//          }
         
         $list[$k]['title']=$v['title'];
         $list[$k]['istmall']=$v['user_type'];
         $list[$k]['num_iid']=$v['num_iid'];
         $list[$k]['url']=$v['coupon_click_url'];
         $list[$k]['coupons_end']=$v['coupon_end_time'];
         preg_match_all('|满([\d\.]+).*元减([\d\.]+).*元|ism',$v['coupon_info'], $matches);
         $list[$k]['coupons_price']=$matches[2][0];
         $list[$k]['goods_sale']=$v['volume'];
         $list[$k]['price']=$v['zk_final_price']-$matches[2][0];
         $list[$k]['org_price']=$v['zk_final_price'];
         $list[$k]['pic_url']=$v['pict_url'];
         $list[$k]['shop_title']=$v['shop_title'];
         $list[$k]['tk_rate']=$v['commission_rate'];//佣金比例
         $list[$k]['nick']=$v['nick'];
         $list[$k]['coupons_take']=$v['coupon_remain_count'];
         $list[$k]['coupons_total']=$v['coupon_total_count'];
         $list[$k]['item_url']=$v['item_url'];
         $list[$k]['small_images']=$v['small_images']['string'];
         $list[$k]['pic_url']=$v['pict_url'];
       }
       return $list;
   }

    public function rhy($quan_id,$num_iid,$pid) {//二合一 鹊桥
        //$url="https://uland.taobao.com/coupon/edetail?activityId=".$quan_id."&itemId=".$num_iid."&src=tiger_tiger&pid=".$pid."&tj1=1";
        $url="https://uland.taobao.com/coupon/edetail?activityId=".$quan_id."&itemId=".$num_iid."&src=tiger_tiger&pid=".$pid."";
        Return $url;        
    }
    public function rhydx($quan_id,$num_iid,$pid) {//二合一 定向
        //$url="https://uland.taobao.com/coupon/edetail?activityId=".$quan_id."&itemId=".$num_iid."&src=tiger_tiger&pid=".$pid."&dx=1&tj1=1";
        $url="https://uland.taobao.com/coupon/edetail?activityId=".$quan_id."&itemId=".$num_iid."&src=tiger_tiger&pid=".$pid."&dx=1";
        Return $url;        
    }



    public function doMobileHbshare(){
        global $_W, $_GPC;
        $pid = $_GPC['pid'];
        $weid =$_W['uniacid'];
        $cfg=$this->module['config']; 
		$poster = pdo_fetch ( 'select * from ' . tablename ( $this->modulename . "_poster" ) . " where weid='{$weid}'" );
        $type=$_GPC['type'];
        $id=$_GPC['id'];
        //if($type==2){
          $img=$_W['siteroot'] .'addons/tiger_taoke/qrcode/mposter'.$id.'.jpg';
        //}else{
        //  $img=$_W['siteroot'] .'addons/tiger_taoke/qrcode/iposter'.$id.'.jpg';
        //}
        $mbstyle='style2';
        include $this->template (  $mbstyle.'/hbshare' );
    
    }



   /*
	*鉴权
	*/
	public function doMobileOauth(){
		global $_W,$_GPC;
 		$code = $_GPC['code'];       
		load()->func('communication');
		$weid=intval($_GPC['weid']);
        $uid=intval($_GPC['uid']);
        $do=$_GPC['dw'];
        $reply=pdo_fetch('select * from '.tablename('tiger_taoke_poster').' where weid=:weid order by id asc limit 1',array(':weid'=>$weid));
		load()->model('account');
        $cfg=$this->module['config'];     
		if(!empty($code)) {
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$cfg['appid']."&secret=".$cfg['secret']."&code={$code}&grant_type=authorization_code";
			$ret = ihttp_get($url);
			if(!is_error($ret)) {
				$auth = @json_decode($ret['content'], true);               
				if(is_array($auth) && !empty($auth['openid'])) {
					$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$auth['access_token'].'&openid='.$auth['openid'].'&lang=zh_CN';
					$ret = ihttp_get($url);//获取的粉丝信息
					$auth = @json_decode($ret['content'], true);//转成数组
                    //echo '<pre>';
                    //print_r($ret);
                   // exit;
                    /**
                    Array
                        (
                            [openid] => oozm3t8q7pk9LB2gn7iOLUl8E73U
                            [nickname] => 胡跃结
                            [sex] => 1
                            [language] => zh_CN
                            [city] => 金华
                            [province] => 浙江
                            [country] => 中国
                            [headimgurl] => http://wx.qlogo.cn/mmopen/ajNVdqHZLLDEXibUAOY2wxI2W4waic22H9r162vtYs0W75hXIX5Lr3hCVRSKnBYxYRwkWbps9BdpnIWr5BT2epRw/0
                            [privilege] => Array
                                (
                                )
                        )
                    **/
					$insert=array(
						'weid'=>$_W['uniacid'],
						'openid'=>$auth['openid'],
                        'helpid'=>$uid,
						'nickname'=>$auth['nickname'],
						'sex'=>$auth['sex'],
                        'city'=>$auth['city'],
                        'province'=>$auth['province'],
                        'country'=>$auth['country'],
						'headimgurl'=>$auth['headimgurl'],
						'unionid'=>$auth['unionid'],
					);
         
                    
					$from_user=$_W['fans']['from_user']; 
                  
					isetcookie('tiger_taoke_openid'.$weid, $auth['openid'], 1 * 86400);

					$sql='select * from '.tablename('tiger_taoke_member').' where weid=:weid AND openid=:openid ';
					$where="  ";	
					$fans=pdo_fetch($sql.$where." order by id asc limit 1 " ,array(':weid'=>$weid,':openid'=>$auth['openid']));
					if(empty($fans)){
						$insert['from_user']=$from_user;
                        $insert['time']=time();
                        //echo '<pre>';
                        //print_r($insert);
                        //exit;
						if($_W['account']['key']==$reply['appid'])$insert['from_user']=$auth['openid'];
						pdo_insert('tiger_taoke_member',$insert);
					}
                    if($do=='Goods'){
                      $forward = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=Goods&m=tiger_taoke&openid=".$auth['openid']."&wxref=mp.weixin.qq.com#wechat_redirect";
                    }
                    if($do=='tixian'){
                      $forward = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=Tixian&m=tiger_taoke&openid=".$auth['openid']."&wxref=mp.weixin.qq.com#wechat_redirect";
                    }
                    if($do=='sharetz'){
                      //$forward=$reply['tzurl'];
                      $forward = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=Sharetz&uid=".$uid."&m=tiger_taoke&wxref=mp.weixin.qq.com#wechat_redirect";
                    }   
					header('location:'.$forward);
					exit;
				}else{
					die('微信授权失败');
				}
			}else{
				die('微信授权失败');
			}
		}else{
            
			if($do=='Goods'){
               $forward = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=Goods&m=tiger_taoke&wxref=mp.weixin.qq.com#wechat_redirect";
            }
            if($do=='tixian'){
               $forward = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=Tixian&m=tiger_taoke&wxref=mp.weixin.qq.com#wechat_redirect";
            }
            
            if($do=='sharetz'){
              //$forward=$reply['tzurl'];
              $forward = $_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&do=Sharetz&uid=".$uid."&m=tiger_taoke&wxref=mp.weixin.qq.com#wechat_redirect";
            }  
			header('location: ' .$forward);
			exit;
		}
	}



     /*
	*鉴权
	*/
	public function doMobileOauthkd(){
		global $_W,$_GPC;
 		$code = $_GPC['code'];       
        $weid=$_GPC['weid'];
		load()->model('account');
        $cfg=$this->module['config'];     
		if(!empty($code)) {
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$cfg['appid']."&secret=".$cfg['secret']."&code={$code}&grant_type=authorization_code";
			$ret = ihttp_get($url);
			if(!is_error($ret)) {
				$auth = @json_decode($ret['content'], true);      
				if(is_array($auth) && !empty($auth['openid'])) {
					$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$auth['access_token'].'&openid='.$auth['openid'].'&lang=zh_CN';
					$ret = ihttp_get($url);//获取的粉丝信息
					$auth = @json_decode($ret['content'], true);//转成数组
					isetcookie('tiger_taoke_openid'.$weid, $auth['openid'], 1 * 86400);
                    $forward=$this->createMobileurl('kending',array('weid'=>$_GPC['weid'],'uid'=>$_GPC['uid']));
					header('location:'.$forward);
					exit;
				}else{
					die('微信授权失败');
				}
			}else{
				die('微信授权失败');
			}
		}else{
            $forward=$this->createMobileurl('kending',array('weid'=>$_GPC['weid'],'uid'=>$_GPC['uid']));
			header('location: ' .$forward);
			exit;
		}
	}

    public function doMobileKending(){

      global $_W, $_GPC;
      $weid=$_W['uniacid'];
      $uid=$_GPC['uid'];
      load()->model('mc');
      load()->model('account');
      $cfg=$this->module['config']; 
      if(empty($_GPC['tiger_taoke_openid'.$weid])){
            $callback = urlencode($_W['siteroot'] .'app'.str_replace("./","/",$this->createMobileurl('oauthkd',array('weid'=>$weid,'uid'=>$uid))));
            $forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$cfg['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
            //$forward=  "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$cfg['appid']."&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
            header('location:'.$forward);
            exit();
      }else{
        $openid=$_GPC['tiger_taoke_openid'.$weid];
      }
      $fans=pdo_fetch('select * from '.tablename('mc_mapping_fans').' where uniacid=:uniacid and uid=:uid order by fanid desc limit 1',array(':uniacid'=>$_W['uniacid'],':uid'=>$_GPC['uid']));//当前粉丝信息

      

      $member=pdo_fetch('select * from '.tablename('tiger_taoke_member').' where weid=:weid and openid=:openid order by id desc limit 1',array(':weid'=>$_W['uniacid'],':openid'=>$openid));//借权的当前粉丝信息
      //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($member),FILE_APPEND);
      //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($openid),FILE_APPEND);
      //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($fans),FILE_APPEND);


      if(!empty($member)){
         $data = array('from_user'=>$fans['openid']);
         pdo_update('tiger_taoke_member', $data, array('weid' =>$weid,'openid' =>$openid));//更新借权表的真实openid的from_user表
         $share=pdo_fetch('select * from '.tablename('tiger_taoke_share').' where weid=:weid and from_user=:from_user order by id asc limit 1',array(':weid'=>$_W['uniacid'],':from_user'=>$fans['openid']));//查找分享表当前用户有没有
         
         if(!empty($share)){
             $data = array('jqfrom_user'=>$openid,'nickname'=>$member['nickname'],'avatar'=>$member['headimgurl']);
             pdo_update('tiger_taoke_share', $data, array('weid' =>$weid,'from_user' =>$fans['openid']));//更新当前分享表数据
             $this->sendtext('亲，您已经领取过奖励了，不能重复领取，快去生成海报赚取奖励吧！',$fans['openid']); 
             include $this -> template('kending');//推广记录里面有了，就退出
             exit;
         }else{
           if(empty($fans['uid'])){
             include $this -> template('kending');
             exit;
           }
           pdo_insert($this->modulename."_share",
					array(
							'openid'=>$fans['uid'],
							'nickname'=>$member['nickname'],
							'avatar'=>$member['headimgurl'],
							'createtime'=>time(),
							'parentid'=>$member['helpid'],
                            'helpid'=>$member['helpid'],
							'weid'=>$_W['uniacid'],
                            'from_user'=>$fans['openid'],
                            'jqfrom_user'=>$openid,
                            'follow'=>1
					));
         }
         /*
           查找积分奖励情况
           ims_mc_credits_record 表
           uid用户ID
           uniacid公众号ID
           credittype  credit1 credit2
           remark 关注送积分
         */
         $credit1=pdo_fetch('select * from '.tablename('mc_credits_record').' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark',array(':uniacid'=>$_W['uniacid'],':uid'=>$fans['uid'],':credittype'=>'credit1',':remark'=>'关注送积分'));
         $credit2=pdo_fetch('select * from '.tablename('mc_credits_record').' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark',array(':uniacid'=>$_W['uniacid'],':uid'=>$fans['uid'],':credittype'=>'credit2',':remark'=>'关注送余额'));
         if(empty($credit1) || empty($credit1)){
            $share=pdo_fetch('select * from '.tablename('tiger_taoke_share').' where weid=:weid and from_user=:from_user order by id asc limit 1',array(':weid'=>$_W['uniacid'],':from_user'=>$fans['openid']));//重新查找当前粉丝分享表信息
            $poster = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_poster')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
            if($poster['score']>0 || $poster['scorehb']>0){
              $info1=str_replace('#昵称#',$share['nickname'], $poster['ftips']);
              $info1=str_replace('#积分#',$poster['score'], $info1);
              $info1=str_replace('#元#',$poster['scorehb'], $info1);
              if(!empty($poster['score'])){mc_credit_update($share['openid'],'credit1',$poster['score'],array($share['openid'],'关注送积分'));}
              if(!empty($poster['scorehb'])){mc_credit_update($share['openid'],'credit2',$poster['scorehb'],array($share['openid'],'关注送余额'));}
              $this->sendtext($info1,$fans['openid']);
                
                if($share['helpid']>0){
                   if($poster['cscore']>0 || $poster['cscorehb']>0){
                      $hmember = pdo_fetch('select * from '.tablename($this->modulename."_share")." where openid='{$share['helpid']}'");
                      if($hmember['status']==1){
                        include $this -> template('kending');
                        exit;
                      }
                      //if($share['helpid']==$hmember['openid']){
                       //  include $this -> template('kending');
                      //   exit;
                      //}
                      $info2=str_replace('#昵称#',$share['nickname'], $poster['utips']);
                      $info2=str_replace('#积分#',$poster['cscore'], $info2);
                      $info2=str_replace('#元#',$poster['cscorehb'], $info2);
                      if(!empty($poster['cscore'])){mc_credit_update($hmember['openid'],'credit1',$poster['cscore'],array($hmember['openid'],'2级推广奖励'));}
                      if(!empty($poster['cscorehb'])){mc_credit_update($hmember['openid'],'credit2',$poster['cscorehb'],array($hmember['openid'],'2级推广奖励'));} 
                      $this->sendtext($info2,$hmember['from_user']);
                   }
                   if($poster['pscore']>0 || $poster['pscorehb']>0){
                      $fmember=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$hmember['helpid']));
                      if($fmember['status']==1){
                        include $this -> template('kending');
                        exit;
                      }
                        //if(!empty($fmember)){
                            $info3=str_replace('#昵称#',$share['nickname'], $poster['utips2']);
                            $info3=str_replace('#积分#',$poster['pscore'], $info3);
                            $info3=str_replace('#元#',$poster['pscorehb'], $info3);
                            if($poster['pscore']){mc_credit_update($fmember['openid'],'credit1',$poster['pscore'],array($fmember['openid'],'3级推广奖励'));}
                            if($poster['pscorehb']){mc_credit_update($fmember['openid'],'credit2',$poster['pscorehb'],array($fmember['openid'],'3级推广奖励'));}        
                            $this->sendtext($info3,$fmember['from_user']);   
                       // }
                    }
                }
                
            }
            include $this -> template('kending');
            exit;

         }else{
           $this->sendtext('尊敬的粉丝：\n\n您已经领取过奖励了，不能重复领取，快去生成海报赚取奖励吧！',$fans['openid']);
           include $this -> template('kending');
           exit;
         }
         
      }

      $this->sendtext('尊敬的粉丝：\n\n您不能领取奖励哦，只有通过扫海报进来的，才能领取奖励！快去生成海报赚取奖励吧！',$fans['openid']);
      include $this -> template('kending');
    }

   /**
	* 获取客户资料
	* $access_token= account_weixin_token($_W['account']);
	* 当用户接到到一条模板消息，会给公共平台api发送一个xml文件【待处理】
	*/	
    private function sendtext($txt,$openid){
		global $_W;
		$acid=$_W['account']['acid'];
		if(!$acid){
			$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		}
		$acc = WeAccount::create($acid);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
		return $data;
	}

    //根据IP获取城市名
    function GetIpLookup($ip = ''){  
        if(empty($ip)){  
            $ip = GetIp();  
        }  
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
        if(empty($res)){ return false; }  
        $jsonMatches = array();  
        preg_match('#\{.+?\}#', $res, $jsonMatches);  
        if(!isset($jsonMatches[0])){ return false; }  
        $json = json_decode($jsonMatches[0], true);  
        if(isset($json['ret']) && $json['ret'] == 1){  
            $json['ip'] = $ip;  
            unset($json['ret']);  
        }else{  
            return false;  
        }  
        return $json;  
    }

    public function doMobileDiqu(){
       global $_W, $_GPC;
         $uid=$_GPC['uid'];
       //位置开始
         $ip = $this->getIp();
         $settings=$this->module['config'];
         $ip=$this->GetIpLookup($ip);
         $province=$ip['province'];//省
         $city=$ip['city'];//市
         $district=$ip['district'];//县
         //echo '<pre>';
         //print_r($ip);
         //exit;
         //print_r (explode(",",$settings['city']));
       //exit;

       include $this->template('diqu');
    }

    public function doMobileFwdiqu(){
       global $_W, $_GPC;
         $uid=$_GPC['uid'];
         $scene_id=$_GPC['scene_id'];//上级
         $from_user=$_GPC['from_user'];//当前用户openid
         //echo '<pre>';
         //print_r($_GPC);
         //exit;
       //位置开始
         $ip = $this->getIp();
         $settings=$this->module['config'];
         $ip=$this->GetIpLookup($ip);
         $province=$ip['province'];//省
         $city=$ip['city'];//市
         $district=$ip['district'];//县
         //echo '<pre>';
         //print_r($settings);
         //exit;
         //print_r (explode(",",$settings['city']));
       //exit;

       include $this->template('fwdiqu');
    }

    function getIp(){ 
		$onlineip=''; 
		if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){ 
			$onlineip=getenv('HTTP_CLIENT_IP'); 
		} elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){ 
			$onlineip=getenv('HTTP_X_FORWARDED_FOR'); 
		} elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){ 
			$onlineip=getenv('REMOTE_ADDR'); 
		} elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){ 
			$onlineip=$_SERVER['REMOTE_ADDR']; 
		} 
		return $onlineip; 
	}

    public function doMobileAjxdiqu(){
       global $_W, $_GPC;
       $diqu=$_GPC['city'];
       $province=$_GPC['province'];
       $district=$_GPC['district'];
       $uid=$_GPC['uid'];
       $scene_id=$_GPC['scene_id'];//上级
       $from_user=$_GPC['from_user'];//当前用户openid
       $ddtype=$_GPC['ddtype'];
       $cfg=$this->module['config'];
       load()->model('mc');
       $fans=pdo_fetch('select * from '.tablename('mc_mapping_fans').' where uniacid=:uniacid and uid=:uid order by fanid asc limit 1',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));
       $user=mc_fetch($uid);
       $pos = stripos($cfg['city'],$diqu);


       if($ddtype==1){
          $nzmsg="抱歉!\n\n核对位置失败，请先开启共享位置功能！";
          $this->sendtext($nzmsg,$fans['openid']);
          exit;
       }
       if ($pos === false) {
         $nzmsg="抱歉!\n\n本次活动只针对【".$cfg['city']."】微信用户开放\n\n您所在的位置【".$diqu."】未开启活动，您不能参与本次活动，感谢您的支持!";
         mc_update($uid, array('resideprovince'=>$province,'residecity' =>$diqu,'residedist'=>$district));
       }else{
         mc_update($uid, array('resideprovince'=>$province,'residecity' =>$diqu,'residedist'=>$district));
         $nzmsg='位置核对成功，请点击菜单【生成海报】参加活动!';
       }

       $this->sendtext($nzmsg,$fans['openid']);
    }

    public function doMobileFwajxdiqu(){
       global $_W, $_GPC;
       $diqu=$_GPC['city'];
       $province=$_GPC['province'];
       $district=$_GPC['district'];
       $uid=$_GPC['uid'];
       $scene_id=$_GPC['scene_id'];//上级
       $from_user=$_GPC['from_user'];//当前用户openid
       $ddtype=$_GPC['ddtype'];
       $cfg=$this->module['config'];
       load()->model('mc');
       $fans=pdo_fetch('select * from '.tablename('mc_mapping_fans').' where uniacid=:uniacid and uid=:uid order by fanid asc limit 1',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));
       $user=mc_fetch($uid);
       $pos = stripos($cfg['city'],$diqu);      


       if($ddtype==1){
          $nzmsg="抱歉!\n\n核对位置失败，请先开启共享位置功能！";
          $this->sendtext($nzmsg,$fans['openid']);
          exit;
       }
       if ($pos === false) {
         $nzmsg="抱歉!\n\n本次活动只针对【".$cfg['city']."】微信用户开放\n\n您所在的位置【".$diqu."】未开启活动，您不能参与本次活动，感谢您的支持!";
         mc_update($uid, array('resideprovince'=>$province,'residecity' =>$diqu,'residedist'=>$district));
         $this->sendtext($nzmsg,$fans['openid']);
       }else{
         mc_update($uid, array('resideprovince'=>$province,'residecity' =>$diqu,'residedist'=>$district));
         
         $nzmsg='位置核对成功，请点击菜单【生成海报】参加活动!';
         $this->sendtext($nzmsg,$fans['openid']);
         $this->postjiangli($scene_id,$from_user);
       }

       
    }


    public function postjiangli($scene_id,$from_user){
       global $_W, $_GPC;
       load()->model('mc');
       $fans = mc_fetch($from_user);
       $poster = pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_poster')." WHERE weid = :weid", array(':weid' => $_W['uniacid']));
       if (empty($fans['nickname']) || empty($fans['avatar'])){
                        $openid = $this->message['from'];
                        $ACCESS_TOKEN = $this->getAccessToken();
                        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
                        load()->func('communication');
                        $json = ihttp_get($url);
                        $userInfo = @json_decode($json['content'], true);
                        $fans['nickname'] = $userInfo['nickname'];
                        $fans['avatar'] = $userInfo['headimgurl'];
                        $fans['province'] = $userInfo['province'];
                        $fans['city'] = $userInfo['city'];
                        $fans['unionid']=$userInfo['unionid'];
                        mc_update($this->message['from'],array('nickname'=>$mc['nickname'],'avatar'=>$mc['avatar']));
                    }
       $hmember=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and sceneid=:sceneid", array(':weid' => $_W['uniacid'],':sceneid'=>$scene_id));//事件所有者
              $member=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and from_user=:from_user", array(':weid' => $_W['uniacid'],':from_user'=>$from_user));//当前用户信息
              //if(empty($member)){
               // exit;//用户不存在退出
             // }

              if (empty($member)){
                    pdo_insert($this->modulename."_share",
                            array(
                                    'openid'=>$fans['uid'],
                                    'nickname'=>$fans['nickname'],
                                    'avatar'=>$fans['avatar'],
                                    'pid'=>$poster['id'],
                                    'createtime'=>time(),
                                    'helpid'=>$hmember['openid'],
                                    'weid'=>$_W['uniacid'],
                                    'score'=>$poster['score'],
                                    'cscore'=>$poster['cscore'],
                                    'pscore'=>$poster['pscore'],
                                    'from_user'=>$this->message['from'],
                                    'follow'=>1
                            ));
                    $share['id'] = pdo_insertid();
                    $share = pdo_fetch('select * from '.tablename($this->modulename."_share")." where id='{$share['id']}'");

                    if($poster['kdtype']==1){//开启肯定好友
                       if(!empty($hmember['from_user'])){
                         $mcsj = mc_fetch($hmember['from_user']);
                         $msgsj="您已通过「".$mcsj['nickname']."」，成功关注，点击下方\n\n「菜单-领取奖励」\n\n为好友加分";
                       }else{
                         $msgsj='您需要点击「领取奖励」才能得到积分哦!';
                       }
                       $this->sendtext($msgsj,$from_user);
                       //$this->postText($this->message['from'],$msgsj);
                       exit;
                    }
                    //得积分开始
                    if($poster['score']>0 || $poster['scorehb']>0){
                      $info1=str_replace('#昵称#',$fans['nickname'], $poster['ftips']);
                      $info1=str_replace('#积分#',$poster['score'], $info1);
                      $info1=str_replace('#元#',$poster['scorehb'], $info1);
                      if($poster['score']){mc_credit_update($share['openid'],'credit1',$poster['score'],array($share['openid'],'关注送积分'));}
                      if($poster['scorehb']){mc_credit_update($share['openid'],'credit2',$poster['scorehb'],array($share['openid'],'关注送余额'));}                      
                      $this->sendtext($info1,$from_user);
                      //$this->postText($this->message['from'],$info1);
                    }
                    
                    if($poster['cscore']>0 || $poster['cscorehb']>0){
                      if($hmember['status']==1){
                        exit;
                      }
                      $info2=str_replace('#昵称#',$fans['nickname'], $poster['utips']);
                      $info2=str_replace('#积分#',$poster['cscore'], $info2);
                      $info2=str_replace('#元#',$poster['cscorehb'], $info2);
                      if($poster['cscore']){mc_credit_update($hmember['openid'],'credit1',$poster['cscore'],array($hmember['openid'],'2级推广奖励'));}
                      if($poster['cscorehb']){mc_credit_update($hmember['openid'],'credit2',$poster['cscorehb'],array($hmember['openid'],'2级推广奖励'));}      
                      $this->sendtext($info2,$hmember['from_user']);
                      //$this->postText($hmember['from_user'],$info2);
                    }
                    if($poster['pscore']>0 || $poster['pscorehb']>0){
                      $fmember=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$hmember['helpid']));
                      if($fmember['status']==1){
                        exit;
                      }
                        if($fmember){
                            $info3=str_replace('#昵称#',$fans['nickname'], $poster['utips2']);
                            $info3=str_replace('#积分#',$poster['pscore'], $info3);
                            $info3=str_replace('#元#',$poster['pscorehb'], $info3);
                            if($poster['pscore']){mc_credit_update($fmember['openid'],'credit1',$poster['pscore'],array($hmember['openid'],'3级推广奖励'));}
                            if($poster['pscorehb']){mc_credit_update($fmember['openid'],'credit2',$poster['pscorehb'],array($hmember['openid'],'3级推广奖励'));}        
                            $this->sendtext($info3,$fmember['from_user']);
                            //$this->postText($fmember['from_user'],$info3);   
                        }
                    }
                   
                }else{
                  $this->sendtext('亲，您已经是粉丝了，快去生成海报赚取奖励吧',$from_user);
                  //$this->postText($this->message['from'],'亲，您已经是会员了，快去生成海报赚取奖励吧');  
                }
               
              //return $this->PostNews($poster,$fans['nickname']);//关注推送图文
    }

    /**
	 * @name 	单发模式
	 * @param 	openid 		粉丝编号
	 * @param 	tplmsgid	模版消息id
	 * @param 	data 		数据包
     * @param 	data1 		客服消息信息
	 * @param 	url 		跳转地址
	 */
	public function sendMsg($openid, $tplmsgid, $data = array(),$data1,$url ="") {
		global $_W;
        $cfg = $this->module['config'];
		if (!empty($data)) {
			//记录存在 | 发送接口
			$account = WeAccount::create($_W['account']['acid']);
			//公号类型
			if (empty($tplmsgid)) {
				//订阅号 | 客服消息
				$this->postText($this->message['from'],$data1);
			} elseif ($_W['account']['level'] == 4) {
				//服务号 | 模板消息
                return $account->sendTplNotice($openid, $tplmsgid, $data, $url);
			}
		}
	}

    /**
        $openid 通知OPENID
        $mb 模版消息信息
        $mbid  模版ID
        $url  模版消息链接
        $fans  粉丝信息
        $orderid 订单号        
    **/
    public function mbmsg($openid,$mb,$mbid,$url='',$fans,$orderid){//发送模版消息
       global $_W;   
       $tp_value1 = unserialize($mb['zjvalue']);
       $tp_value1=str_replace('#时间#',date('Y-m-d H:i:s',time()),$tp_value1);
       $tp_value1=str_replace('#昵称#',$fans['nickname'],$tp_value1);
       $tp_value1=str_replace('#订单号#',$orderid,$tp_value1);
       $tp_color1 = unserialize($mb['zjcolor']);
       //file_put_contents(IA_ROOT."/addons/tiger_renwubao/log.txt","\n 2old:".json_encode($orderid),FILE_APPEND);
       $mb['first']=str_replace('#时间#',date('Y-m-d H:i:s',time()),$mb['first']);
       $mb['first']=str_replace('#昵称#',$fans['nickname'],$mb['first']);
       $mb['first']=str_replace('#订单号#',$orderid,$mb['first']);

       $tplist1=array(
            'first' => array(
            'value' => $mb['first'],
            "color" => $mb['firstcolor']
          )
        );
       foreach ($tp_value1 as $key => $value) {  
            if(empty($value)){
              continue;
            }
            $tplist1['keyword'.$key] = array('value'=>$value,'color'=>$tp_color1[$key]);
        }
        $mb['remark']=str_replace('#时间#',date('Y-m-d H:i:s',time()),$mb['remark']);
        $mb['remark']=str_replace('#昵称#',$fans['nickname'],$mb['remark']);
        $mb['remark']=str_replace('#订单号#',$orderid,$mb['remark']);

        $tplist1['remark']=array(
            'value' => $mb['remark'],
            "color" => $mb['remarkcolor']
        );
       $msg=$this->sendMsg($openid,$mbid,$tplist1,'',$url);
       return $msg;
   }



    public function doMobileReg() {//注册
        global $_W,$_GPC;
        $cfg = $this->module['config'];        
        $helpid=$_GPC['hid'];
        $fans=mc_oauth_userinfo();
        if(empty($fans['openid'])){
          echo '只能在微信浏览器中打开！';
        }

        $fans = mc_fetch($_W['fans']['from_user']);
        $share=pdo_fetch("SELECT * FROM ".tablename('tiger_taoke_share')." WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'],':openid'=>$fans['uid']));

        if(!empty($share['tel'])){
            $url=$this->createMobileurl('goods');
            header("location:".$url);
            exit;
        }


        if (checksubmit('submit')){
            $config = $this->module['config'];
            $openid = $_W['openid'];
            $mobile = trim($_GPC['mobile']);
            $verify = trim($_GPC['smsCode']);
            //$realname = $_GPC['realname'];
            //$password = random(6);
            load()->model('utility');
            if(!code_verify($_W['uniacid'], $mobile, $verify)) {
                //exit('验证码错误.');
                message('验证码错误', referer(), 'error');
            }
            $user = pdo_fetch("SELECT * FROM ".tablename($this->modulename."_share")." WHERE tel=:tel AND id<>:id",array(':tel'=>$mobile,':id'=>$share['id']));
            if (!empty($user)) {
                //exit('该手机号已注册其他微信，请先解绑后重试.');
                message('该手机号已注册其他微信，请先解绑后重试', referer(), 'error');
            }
            //echo $mobile;
            //exit;
            $result = pdo_update($this->modulename."_share", array('tel' => $mobile), array('id' =>$share['id'], 'weid' => $_W['uniacid']));
            if($result){              
               message('验证成功', $this -> createMobileurl('goods'), 'success');
            }else{
              message('异常错误', referer(), 'error');
            }

        }
        
		include $this -> template('reg');
	}


    

    public function doMobileDoneExchange(){
        global $_W, $_GPC;
        $data = array('status' => 'done');
        $id = intval($_GPC['id']);
        $row = pdo_fetch("SELECT id FROM " . tablename($this -> table_request) . " WHERE id = :id", array(':id' => $id));
        if (empty($row)){
            message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
        }
        pdo_update($this -> table_request, $data, array('id' => $id));
        message('兑换成功！！', referer(), 'success');
    }

    //现金红包接口
   function post_txhb($cfg,$openid,$dtotal_amount,$desc,$dmch_billno) {
       global $_W;
       load()->model('mc');
       

       //提现金额限制开始
       if(!empty($desc)){
         $fans = mc_fetch($_W['openid']);
         $dtotal=$dtotal_amount/100;
         //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($dtotal."||||".$desc."||||".$fans['credit2']),FILE_APPEND);
         
         if($dtotal>$fans['credit2']){
            $ret['code']=-1;
            $ret['dissuccess']=0;
            $ret['message']='余额不足';
            return $ret; 
            exit;
         }
       }
       if(empty($dmch_billno)){
         $dmch_billno=random(10). date('Ymd') . random(3);
       }
       
       //提现金额限制结束
       $root=IA_ROOT . '/attachment/tiger_taoke/cert/'.$_W['uniacid'].'/';
   	   $ret=array();
       $ret['code']=0;
       $ret['message']="success";     
   //  return $ret;  	
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] =$dmch_billno;
        $pars['mch_id'] = $cfg['mchid'];
        $pars['wxappid'] = $cfg['appid'];
        $pars['nick_name'] =   $_W['account']['name'];
        $pars['send_name'] = $_W['account']['name'];
        $pars['re_openid'] = $openid;
        $pars['total_amount'] = $dtotal_amount;
        $pars['min_value'] = $dtotal_amount;
        $pars['max_value'] = $dtotal_amount;
        $pars['total_num'] = 1;
        $pars['wishing'] = '提现红包成功!';
        $pars['client_ip'] = $cfg['client_ip'];
        $pars['act_name'] =  '兑换红包';
        $pars['remark'] = "来自".$_W['account']['name']."的红包";

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$cfg['apikey']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        //$cert=json_decode($cfg['nbfwxpaypath']);

        $extras['CURLOPT_CAINFO']= $root.'rootca.pem';
        $extras['CURLOPT_SSLCERT'] =$root.'apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY'] =$root.'apiclient_key.pem';
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($extras['CURLOPT_CAINFO']),FILE_APPEND);

        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp["message"];
            $ret['code']=-1;
            $ret['dissuccess']=0;
            $ret['message']=$procResult;
            return $ret;     
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
             if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=0;
                    $ret['dissuccess']=1;
                    $ret['message']="success";
                    return $ret;
                  
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']=-2;
                    $ret['dissuccess']=0;
                    $ret['message']=$error;
                    return $ret;
                 }
            } else {
                $ret['code']=-3;
                $ret['dissuccess']=0;
                $ret['message']="3error3";
                return $ret;
            }
            
        }     
    }


    //企业零钱付款接口
  public function post_qyfk($cfg,$openid,$amount,$desc,$dmch_billno){
    global $_W;
    load()->model('mc');
    //提现金额限制开始
       if(!empty($desc)){
         $fans = mc_fetch($_W['openid']);
         $dtotal=$amount/100;
         //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($dtotal."||||".$desc."||||".$fans['credit2']),FILE_APPEND);         
         if($dtotal>$fans['credit2']){
            $ret['code']=-1;
            $ret['dissuccess']=0;
            $ret['message']='余额不足';
            return $ret; 
            exit;
         }
       }
      if(empty($dmch_billno)){
         $dmch_billno=random(10). date('Ymd') . random(3);
       }
       //提现金额限制结束
    $root=IA_ROOT . '/attachment/tiger_taoke/cert/'.$_W['uniacid'].'/';
    $ret=array();
  	$ret['code']=0;
    $ret['message']="success";     
  
    $ret['amount']=$amount;
    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    $pars = array();
    $pars['mch_appid'] =$cfg['appid'];
    $pars['mchid'] = $cfg['mchid'];
    $pars['nonce_str'] = random(32);
    $pars['partner_trade_no'] =$dmch_billno;
    $pars['openid'] =$openid;
    $pars['check_name'] = "NO_CHECK";
    $pars['amount'] =$amount;
    $pars['desc'] = "来自".$_W['account']['name']."的提现";
    $pars['spbill_create_ip'] =$cfg['client_ip']; 
    ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$cfg['apikey']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        //$cert=json_decode($cfg['nbfwxpaypath']);
        $extras = array();
        $extras['CURLOPT_CAINFO']= $root.'rootca.pem';
        $extras['CURLOPT_SSLCERT'] =$root.'apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY'] =$root.'apiclient_key.pem';
 
     
        load()->func('communication');
        $procResult = null; 
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp['message'];
            $ret['code']=-1;
            $ret['dissuccess']=0;
            $ret['message']="-1:".$procResult;
            return $ret;            
         } else {        	
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=0;
                    $ret['dissuccess']=1;
                    $ret['message']="success";
                    return $ret;
                  
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']=-2;
                    $ret['dissuccess']=0;
                    $ret['message']="-2:".$error;
                    return $ret;
                 }
            } else {
                $ret['code']=-3;
                $ret['dissuccess']=0;
                $ret['message']="error response";
                return $ret;
            }
        }
    
   }

	
	public function getAccountLevel(){
		global $_W;
		load()->classs('weixin.account');
		$accObj = WeixinAccount::create($_W['uniacid']);
		$account = $accObj->account;
		return $account['level'];
	}


    public function doWebQingkong(){
		global $_W,$_GPC;   
		$weid = $_W['uniacid'];
        $pid=$_GPC['pid'];
        pdo_delete('qrcode', array('uniacid' => $weid));
        pdo_update($this->modulename . "_share", array('ticketid' =>'', 'url' =>'','updatetime'=>'','sceneid'=>''), array('weid' =>$weid));
		//if ($pid){
			$shares = pdo_fetchall('select id from '.tablename($this->modulename."_share")." where weid='{$weid}'");
			foreach ($shares as $value) {
				@unlink("../addons/tiger_taoke/qrcode/mposter{$value['id']}.jpg");
			}
			message ( '海报缓存清空成功！', $this->createWebUrl ( 'mposter' ) );
		//}
	}

    public function doWebPic(){
		global $_W, $_GPC;
		$sid = $_GPC['sid'];
		$url = pdo_fetchcolumn('select url from '.tablename($this->modulename."_share")." where id='{$sid}'");
		//$img = "temp_qrcode.png";
        $img = IA_ROOT.'/attachment/images/temp_qrcode.png';
		include "phpqrcode.php";/*引入PHP QR库文件*/
		$errorCorrectionLevel = "L";
		$matrixPointSize = "110";
		QRcode::png($url, $img, $errorCorrectionLevel, $matrixPointSize);
		header('Content-type: image/jpeg');
		header("Content-Disposition: attachment; filename='推广二维码.jpg'");
		readfile($img);
		@unlink($img);
	}


    //require_once IA_ROOT.'/addons/tiger_taoke/lib/duiba.php';
    
//兑吧开始

    public function doMobileDuibagoods(){//直达页面接口
        //http://wx.youqi18.com/app/index.php?i=3&c=entry&do=duibagoods&m=tiger_taoke
		global $_W,$_GPC;  
        include 'duiba.php';
        $cfg=$this->module['config'];
        if(empty($cfg['AppKey'])){
          exit;
        }
        checkauth();
        load()->model('mc');
        $uid = mc_openid2uid($_W['openid']);
        $credit=mc_credit_fetch($uid);
        //echo '<pre>';
        //print_r($credit);
        //exit;
        $crdeidt=strval(intval($credit['credit1']));
        //var_dump($crdeidt);
        //exit;
        $url=buildCreditAutoLoginRequest($cfg['AppKey'],$cfg['appSecret'],$uid,$crdeidt);
        //echo $url;
        //exit;
        header('location: ' .$url);
	}



    public function doMobileDuibaxf()
    {
        global $_W, $_GPC;
        include 'duiba.php';
        $cfg=$this->module['config'];
        $settings = $this->module['config'];
        $request_array = $_GPC;
        $uid = $request_array['uid'];
        foreach ($request_array as $key => $val) {
            $unsetkeyarr = array('i', 'do', 'm', 'c');
            if (in_array($key, $unsetkeyarr) || strstr($key, '__')) {
                unset($request_array[$key]);
            }
        }
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($request_array),FILE_APPEND);
        $ret = parseCreditConsume($settings['AppKey'], $settings['appSecret'], $request_array);
        //$res=  parseCreditConsume($cfg['AppKey'],$cfg['appSecret'],$request_array);
        

        if (is_array($ret)) {
            $insert = array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'bizId' => date('YmdHi') . random(8, 1), 'orderNum' => $request_array["orderNum"], 'credits' => $request_array["credits"], 'params' => $request_array["params"], 'type' => $request_array["type"], 'ip' => $request_array["ip"], 'starttimestamp' => $request_array["timestamp"], 'waitAudit' => $request_array["waitAudit"], 'actualPrice' => $request_array["actualPrice"], 'description' => $request_array["description"], 'facePrice' => $request_array["facePrice"], 'Audituser' => $request_array["Audituser"], 'itemCode' => $request_array["itemCode"], 'status' => 0, 'createtime' => time());
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($insert),FILE_APPEND);
            pdo_insert($this->modulename."_dborder", $insert);
            if (pdo_insertid()) {
                load()->model('mc');
                $usercredits = mc_credit_fetch($uid, $types = array('credit1'));
                $yue = intval($usercredits['credit1']) - $request_array["credits"];
                if ($yue > 0) {
                    $updatecredit = mc_credit_update($uid, 'credit1', -abs($request_array["credits"]), array("积分宝", "兑吧兑换" . $request_array["description"], 'tiger_taoke'));
                    if ($updatecredit) {
                        exit(json_encode(array('status' => 'ok', 'errorMessage' => "", 'bizId' => $insert['bizId'], 'credits' => $yue)));
                    } else {
                        exit(json_encode(array('status' => 'fail', 'errorMessage' => "扣除{$cfg['hztype']}错误", 'credits' => $request_array["credits"])));
                    }
                } else {
                    exit(json_encode(array('status' => 'fail', 'errorMessage' => "积分不足", 'credits' => $request_array["credits"])));
                }
            } else {
                exit(json_encode(array('status' => 'fail', 'errorMessage' => "系统错误，请重试！", 'credits' => $request_array["credits"])));
            }
        } else {
            exit(json_encode(array('status' => 'fail', 'errorMessage' => $ret, 'credits' => $request_array["credits"])));
        }
    }


    public function doMobileDuibatz()//结果通知
    {
        global $_W, $_GPC;
        include 'duiba.php';
        $settings = $this->module['config'];
        $request_array = $_GPC;
        foreach ($request_array as $key => $val) {
            $unsetkeyarr = array('i', 'do', 'm', 'c');
            if (in_array($key, $unsetkeyarr) || strstr($key, '__')) {
                unset($request_array[$key]);
            }
        }
        $ret = parseCreditNotify($settings['AppKey'], $settings['appSecret'], $request_array);
        if (is_array($ret) && $ret['success'] == "true") {
            $order = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_dborder") . " WHERE  uniacid = :uniacid AND orderNum = :orderNum ", array(':uniacid' => $_W['uniacid'], ':orderNum' => $ret['orderNum']));
            if ($order['status'] == 0) {
                $result = pdo_update($this->modulename."_dborder", array('status' => 1, 'endtimestamp' => $request_array['timestamp']), array('id' => $order['id']));
                if (!empty($result)) {
                    exit('ok');
                }
            } elseif ($order['status'] == 1) {
                exit('ok');
            }
        } elseif (is_array($ret) && $ret['success'] == "false") {
            $order = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_dborder") . " WHERE  uniacid = :uniacid  AND ordernum = :ordernum ", array(':uniacid' => $_W['uniacid'], ':orderNum' => $ret['orderNum']));
            if ($order['status'] != 2) {
                $result = pdo_update($this->modulename."_dborder", array('status' => 2, 'endtimestamp' => $request_array['timestamp']), array('id' => $order['id']));
                if (!empty($result)) {
                    $updatecredit = mc_credit_update($request_array["uid"], 'credit1', abs($request_array["credits"]), array("积分宝", "兑吧兑换失败，退还积分"));
                    if (!empty($updatecredit)) {
                        exit('ok');
                    }
                }
            } elseif ($order['status'] == 2) {
                exit('ok');
            }
        }
    }


//兑吧结束


 //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>微信卡券开始
   
   public function doMobileCard() {//案例
      //http://wx.youqi18.com/app/index.php?i=3&c=entry&do=card&m=tiger_youzan
      global $_W;
      $this->sendcardpost($_W['openid'],'pozm3txI6W-Fcxndth6AlSONkZqE');
   }

   public function sendcardpost($openid,$cardid){
      global $_W;
      $getticket=$this->getticket();
      $createNonceStr=$this->createNonceStr();
      $signature=$this->signature($getticket,$createNonceStr);
      $account = WeAccount::create();
      $card_ext=array(
                   'openid' => $openid,
                   'timestamp' => strval(TIMESTAMP),
                   'signature' => $signature,
                  );
      $custom = array(
            'touser' => $_W['openid'],
            'msgtype' => 'wxcard',
            'wxcard' => array(
                          'card_id'=>$cardid,
                          'card_ext'=>$card_ext
                           ),
            
       );
      $account->sendCustomNotice($custom);     
   }

   public function doMobileCardd(){//二维码领取卡券有效
      $data11 = array(
               'action_name' => "QR_CARD",
			   'expire_seconds' => 1800,
			   'action_info' => array('card' => array('card_id' => "pozm3txI6W-Fcxndth6AlSONkZqE",
													 // // 'code' => "198374613512",
													 // // 'openid' => "oFS7Fjl0WsZ9AMZqrI80nbIq8xrA",
													  'is_unique_code' => false,
													  'outer_id' => 100),
								 ),
			  );
      $result = $this->create_card_qrcode($data11);
      echo '<pre>';
      print_r($result);
      echo "<img src='{$result['show_qrcode_url']}'>";
   }

   //创建二维码接口
    public function create_card_qrcode($data){   
        $access_token=$this->getAccessToken();
        $url = "https://api.weixin.qq.com/card/qrcode/create?access_token=".$access_token;
        $res = $this->http_web_request($url, json_encode($data));
        return json_decode($res, true);
    }

    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    protected function http_web_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
   

   //生成ticket
   public function getticket() {
       global $_W;
       $data=pdo_fetch("SELECT * FROM " . tablename($this->modulename."_ticket") . " WHERE weid = '{$_W['weid']}'");
       if(empty($data)){
             $access_token=$this->getAccessToken();
             //$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$access_token}";
               $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
             $json = ihttp_get($url);
			 $res = @json_decode($json['content'], true);
             if(empty($ticket)){
               $kjdata=array(
                   'weid'=>$_W['uniacid'],
                   'ticket'=>$res['ticket'],
                   'createtime'=>TIMESTAMP + 7000,
               );
               pdo_insert($this->modulename."_ticket",$kjdata);
             } 
            Return $res['ticket'];
       }else{
         if($data['createtime']<time()){
             $access_token=$this->getAccessToken();
             $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
             $json = ihttp_get($url);
			 $res = @json_decode($json['content'], true);
             if(empty($ticket)){
               $kjdata=array(
                   'ticket'=>$ticket,
                   'createtime'=>TIMESTAMP + 7000,
               );
               pdo_update($this->modulename."_ticket",$kjdata,array('weid'=>$_W['uniacid']));
             }
             Return $res['ticket'];
           }else{
             Return $data['ticket'];
           }
       }
       
    
   }

   //生成nonce_str
   private function createNonceStr($length = 16) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $str = "";
      for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
      }
      return $str;
    }

    //生成signature
   public function signature($api_ticket,$nonce_str) {
       $obj['api_ticket']          = $api_ticket; 
       $obj['timestamp']           = TIMESTAMP;
       $obj['nonce_str']           = $nonce_str; 
       $signature  = $this->get_card_sign($obj);
       Return $signature;
   }

    //生成签名
    public function get_card_sign($bizObj){
        //字典序排序
        asort($bizObj);
        //URL键值对拼成字符串
        $buff = "";
        foreach ($bizObj as $k => $v){
            $buff .= $v;
        }
        //sha1签名
        return sha1($buff);
    }

   //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>微信卡券结束


     /*************************短信验证*****************************/

/*
    public function doMobileSendsms() {
        global $_W,$_GPC;
        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode('asss'),FILE_APPEND);
        if(!$_W['isajax'])die(json_encode(array('success'=>false,'msg'=>'非法提交,只能通过网站提交')));

        die(json_encode(array('success'=>true,'info'=>"22222")));
        
	}
    */
	private function SendSMS($mobile,$content) {
		$config = $this->module['config'];
        
		load()->func('communication');
		if ($config['smstype'] == 'juhesj') {
			$jhappkey = $config['jhappkey'];
            $jhcode = $config['jhcode'];
            //http://v.juhe.cn/sms/send?mobile=手机号码&tpl_id=短信模板ID&tpl_value=%23code%23%3D654654&key=
			//$result = ihttp_get("http://api.smsbao.com/sms?u={$user}&p={$pass}&m=".$mobile."&c=".urlencode($content));
            $json = ihttp_get("http://v.juhe.cn/sms/send?mobile={$mobile}&tpl_id={$jhcode}&tpl_value={$content}&key={$jhappkey}");
            $result = @json_decode($json['content'], true);
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($result),FILE_APPEND);
			if ($json['code'] == 200) {
                if($result['error_code']==0){
                  $content=0;
                }else{
                  $content=$result['error_code'].$result['reason'];
                }
			}else{
				$content = '接口调用错误.';
			}
			return $content;
		}else {
			if (empty($config['dyAppKey']) || empty($config['dyAppSecret']) || empty($config['dysms_free_sign_name']) || empty($config['dysms_template_code'])) {
				return '短信参数配置不正确，请联系管理员';
			}else{
				include IA_ROOT . "/addons/tiger_taoke/inc/sdk/dayu/TopSdk.php";
				$c = new TopClient;
				$c->appkey = $config['dyAppKey'];
				$c->secretKey = $config['dyAppSecret'];
				$req = new AlibabaAliqinFcSmsNumSendRequest;
				$req->setSmsType("normal");
				$req->setSmsFreeSignName($config['dysms_free_sign_name']);
				$req->setSmsParam($content);
				$req->setRecNum($mobile);
				$req->setSmsTemplateCode($config['dysms_template_code']);
				$resp = $c->execute($req);
                file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n old:".json_encode($resp),FILE_APPEND);
				if ($resp->result->err_code == 0) {
					return 0;
				}else{
					return $resp->sub_msg;
				}
			}
		}
		
	}
    /*************************短信结束****************************/


    public function postgoods($goods,$openid){//发送图文消息
        global $_W;
        
        foreach ($goods as $key => $value) {
            $viewurl=$_W['siteroot'].str_replace('./','app/',$this->createMobileurl('view',array('id'=>$value['id'])));
            $response[] = array(
                'title' => urlencode("【券后价:".$value['price']."】".$value['title']),
                'description' => urlencode($value['title']),
                'picurl' => tomedia($value['pic_url']."_100x100.jpg"),
                'url' =>$viewurl
            );
        }

        $message = array(
            'touser' => trim($openid),
            'msgtype' => 'news',
            'news' => array('articles'=>$response)
        );

       
       $acid = $_W['acid'];
		if (empty($acid)) {
			$acid = $_W['uniacid'];
		}
       $account_api = WeAccount::create($acid);
       $status = $account_api->sendCustomNotice($message);
       return $status;
	}


	
}