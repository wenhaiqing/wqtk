<?php
require_once IA_ROOT . "/addons/tiger_taoke/inc/sdk/tbk/lib.php";
   function tkl($taokouling) {
       $tkl="http://www.phj168.com/tao.php?appkey=120785&appsecret=b9ca9a1b9498f5d32ac79e75d74d8e33&taokl=".$taokouling;
       $str=curl_request($tkl);
       $arr=json_decode($str);
       //$goodsid=$arr->tao_id;
       $turl=$arr->tao_url;
       //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\naaaaa1".$turl,FILE_APPEND);
       $goodsid=hqgoodsid($turl);
       return $goodsid;    
   }


   function hqgoodsid($url) {//e22a获取ID

        $czss=strstr($url,"click");
        if($czss!==false){
          $str=getclick($url);
        }else{
          $str = curl_request($url); 
        }
     
            
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n aaaaassss2:".$str,FILE_APPEND);
        //preg_match_all('|url.*&id=([\d]+)&|', $fd,$str);
		$str=str_replace("\"", "", $str);        
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n goodsid:".json_encode($str),FILE_APPEND);
        $title=Text_qzj($str,"<title>","</title>");
        if($title=='亲，访问受限了'){
          Return array('error'=>'亲，访问受限了'); 
        }

		$goodsid=Text_qzj($str,"?id=","&");
        if(empty($goodsid)){
          $goodsid=Text_qzj($str,"&id=","&");
        }
        if(empty($goodsid)){
           $goodsid=Text_qzj($str,"itemId:",",");
        }
        if(empty($goodsid)){
            $url=Text_qzj($str,"url = '","';");
            $goodsid=Text_qzj($str,"com/i",".htm");
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($goodsid),FILE_APPEND);
        }
        if(empty($goodsid)){
           $goodsid=Text_qzj($str,"itemid=","&");
        }
        if(empty($goodsid)){
           $goodsid=Text_qzj($str,"itemId=","&");
        }
        if(empty($goodsid)){
           $goodsid=Text_qzj($str,"itemId%3D","%26");
        }
        Return $goodsid;
    }

   function getgoodslist($str,$ck,$wi,$page,$cfg) {//联盟爬取关键词搜索
       if(empty($wi['siteroot']) || empty($wi['clientip'])){
           return '';
       }
       $key=$str;
       //file_put_contents(IA_ROOT."/addons/tiger_taoke/log--cs.txt","\n----".urlencode($str)."---".$str,FILE_APPEND);
       if(empty($page)){
          $page=1;
       }
       if($cfg['cjss']==1){
         $GetUrl="http://pub.alimama.com/items/search.json?q=".urlencode($str)."&_t=".getMillisecond()."&toPage=".$page."&startTkRate=10&dpyhq=1&auctionTag=&perPageSize=40&shopTag=dpyhq&t=".getMillisecond()."&_tb_token_=test&pvid=";//有券
       }else{
          $GetUrl="http://pub.alimama.com/items/search.json?q=".urlencode($str)."&_t=".getMillisecond()."&toPage=".$page."&auctionTag=&perPageSize=40&shopTag=&t=".getMillisecond()."&_tb_token_=CSW2SyFLNcq&pvid=&dpyhq=1";//有无券
       }

        $str=curl_request($GetUrl);
        $title=Text_qzj($str,"<title>","</title>");
        if($title=='亲，访问受限了'){
          Return array('error'=>'亲，访问受限了'); 
        }
        $arr=json_decode($str);
        $gooodarr=$arr->data->pageList;

        if(empty($gooodarr)){
            if($cfg['cjss']==1){
              $url="http://pub.alimama.com/items/channel/qqhd.json?q=".urlencode($key)."&channel=qqhd&_t=".getMillisecond()."&toPage=1&dpyhq=1&perPageSize=40&shopTag=dpyhq&t=".getMillisecond()."&_tb_token_=test&pvid=";//有券
            }else{
              $url="http://pub.alimama.com/items/channel/qqhd.json?q=".urlencode($key)."&channel=qqhd&_t=".getMillisecond()."&perPageSize=40&shopTag=&t=".getMillisecond()."&_tb_token_=test&pvid=&dpyhq=1";//鹊桥链接
            }
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_HEADER, 0);
//            $str = curl_exec($ch);
//            curl_close($ch);
//            print_r($output);
             $str=curl_request($GetUrl);
             $title=Text_qzj($str,"<title>","</title>");
             if($title=='亲，访问受限了'){
               Return array('error'=>'亲，访问受限了'); 
             }        
             $arr=json_decode($str);
             $gooodarr=$arr->data->pageList;
        }
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log--cs.txt","\nhq--".getMillisecond(),FILE_APPEND);
        
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log--cs.txt","\nhqyurl".json_encode($arr),FILE_APPEND);
        return $gooodarr;     
   }

   function getgyapigoods($str,$wi,$pid,$page,$cfg) {//API关键词搜索
       if(empty($wi['siteroot']) || empty($wi['clientip'])){
           return '';
       }
       $tkurl=urlencode($wi['setting']['site']['url']);//urlencode($wi['siteroot']);
       $tkip=$_SERVER["SERVER_ADDR"];
       if(empty($page)){
          $page=1;
       }
        $url="http://cs.youqi18.com/app/index.php?i=3&c=entry&do=goods&m=tiger_shouquan&key=".$str."&pid=".$pid."&page=".$page."&tkurl=".$tkurl."&tkip=".$tkip."&sq=".$cfg['cxsqtype']."";
        $arr=curl_request($url);
        $arr= @json_decode($arr, true);
        return $arr;     
   }

   function getgyapigoodsindex($str,$wi,$pid,$page,$cfg) {//API首页关键词搜索
       if(empty($wi['siteroot']) || empty($wi['clientip'])){
           return '';
       }
       $tkurl=urlencode($wi['setting']['site']['url']);//urlencode($wi['siteroot']);
       $tkip=$_SERVER["SERVER_ADDR"];
       if(empty($page)){
          $page=1;
       }
        $url="http://cs.youqi18.com/app/index.php?i=3&c=entry&do=goodsindex&m=tiger_shouquan&key=".$str."&pid=".$pid."&page=".$page."&tkurl=".$tkurl."&tkip=".$tkip."&sq=".$cfg['cxsqtype']."";
        $arr=curl_request($url);
        $arr= @json_decode($arr, true);
        return $arr;     
   }

   function hqyongjin($Url,$ck,$cfg,$tiger_taoke,$kouling,$type,$sign,$tbuid,$wi,$q) {//Q=1自己采集商品 Q=2需要查卷的
		//global $_W, $_GPC;
         $pidSplit=explode('_',$cfg['ptpid']);
         $cfg['siteid']=$pidSplit[2];
         $cfg['adzoneid']=$pidSplit[3];

        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\nhqyurl".json_encode($Url),FILE_APPEND);
        
        if($tiger_taoke!='tiger_taoke'){
          exit;
          return;
        }
        if(empty($wi['siteroot']) || empty($wi['clientip'])){
           exit;
           return;
        }
               
        

        if($type==1){
            //$urljk="http://yue5ya.6655.la/tb/api.php?key=4b806c858985d6e01924904b462cf31a&word=".$kouling;
            //$srt=curl_request($urljk);
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode($srt),FILE_APPEND);
            //$numid=Text_qzj($srt,'&id=','&');
            //if(empty($numid)){
            //  $numid=Text_qzj($srt,'com/i','.htm');
            //}
            //if(empty($numid)){
            //  $numid=Text_qzj($srt,'&itemId=','&src');
            //}
            $numid=tkl($kouling);
            $Url="https://item.taobao.com/item.htm?id=".$numid;
            //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\ntb--".json_encode($Url),FILE_APPEND);
            if(empty($numid)){
                if(!empty($cfg['error1'])){
                   Return array('error'=>$cfg['error1']); 
                }else{
                  Return array('error'=>'系统繁忙！请稍后在试！'); 
                }                        
            }

        }

               

        $Cookies=$ck['data'];//阿里妈妈cookie
        $tbtk=$ck['taodata'];//淘宝COOKEI
        $_tb_tok=Text_qzj($Cookies,'_tb_token_=',';');
        //查询链接-获取商品信息佣金
		$GetUrl="http://pub.alimama.com/items/search.json?queryType=2&q=".urlencode($Url)."&auctionTag=&perPageSize=40&shopTag=&t=".getMillisecond()."&_tb_token_=".$_tb_tok."&pvid=";
        //$str = utf8_gbk(file_get_contents($GetUrl));
        //$str =file_get_contents($GetUrl);   
        $str=curl_request($GetUrl);
        $title=Text_qzj($str,"<title>","</title>");
        if($title=='亲，访问受限了'){
          Return array('error'=>'亲，访问受限了'); 
        }
        $arr=json_decode($str);
        //echo "<pre>";
        //print_r($arr);
        //exit;
        $gooodarr=$arr->data->pageList[0];
        
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\nchaxunshangpingxingxi--".json_encode($gooodarr),FILE_APPEND);
        
         
         //print_r($arr->data->pageList);
        $userNumberId=$gooodarr->sellerId;//卖家ID 数字
        $auctionId=$gooodarr->auctionId;//商品ID
        $pictUrl=$gooodarr->pictUrl;//图片链接
        $biz30day=$gooodarr->biz30day;//30销量
        $shopTitle=$gooodarr->shopTitle;//店铺名称
        $nick=$gooodarr->nick;//掌柜旺旺
        $zkPrice=$gooodarr->zkPrice;//商品金额
        $tkRate=$gooodarr->tkRate;//普通佣金
        $tkCommFee=$gooodarr->tkCommFee;//效果预估//佣金
        $eventRate=$gooodarr->eventRate;//鹊桥高佣金55.5
        $title=$gooodarr->title;//
        if(empty($eventRate)){
          $eventRate=1;
        }
        $eventRate=$eventRate*0.95;
        $pictUrl=$gooodarr->pictUrl;//商品图片
        $tkRate=number_format($gooodarr->tkRate, 2, '.', '');//普通佣金 1.3
        if(empty($auctionId)){
               if(!empty($cfg['error2'])){
                   Return array('error'=>$cfg['error2']); 
                }else{
                  Return array('error'=>'该商品暂无优惠,请查看其他商品');
                }
        }        


        if($q==2){
          $youhui=yksGetCoupons($userNumberId,$auctionId,$zkPrice,$tbtk);
         // file_put_contents(IA_ROOT."/addons/tiger_taoke/log--youhui.txt","\n--".json_encode($youhui),FILE_APPEND);
        }
        


        //联盟API高佣金----------------------------
        
        if($cfg['yktype']==1){
                
              $tkurl=urlencode($wi['setting']['site']['url']);//urlencode($wi['siteroot']);
              $tkip=$_SERVER["SERVER_ADDR"];
              $gyj=taokejh($auctionId,$sign,$tbuid,$cfg['adzoneid'],$cfg['siteid'],$tkurl,$tkip,$cfg);
                if(!empty($gyj['error'])){
                   Return array('error'=>$gyj['error']); 
                }
              //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n--gyj--".json_encode($gyj),FILE_APPEND);

              
              if($youhui['xemoney']>$gyj['money']){
                 $couponAmount=$youhui['money'];   //查的券
                 $youhui['endtime']=$youhui['endtime'];//优惠券到期时间
                 if($q==2){
                   $gyj['coupon_click_url']=$gyj['coupon_click_url']."&activityId=".$youhui['id'];
                 }
              }else{
                $couponAmount=$gyj['money'];   //用联盟优惠券
                $youhui['endtime']=$gyj['coupon_end_time'];//优惠券到期时间
              }
              

              $commissionRate=$gyj['max_commission_rate'];     
              if($tkRate>=$commissionRate and $tkRate>=$eventRate){
                  $dx=0;//普通
              }elseif($commissionRate>$tkRate and $commissionRate>=$eventRate){
                  $dx=1;//定向
              }elseif($eventRate>$tkRate and $eventRate>$commissionRate){
                  $dx=2;//鹊桥
              }

                //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n---yj--".json_encode("--dx:".$commissionRate."----pt:".$tkRate."---qq:".$eventRate."---"),FILE_APPEND);
                if(empty($couponAmount)){//如果优惠券有门槛的，就不计算优惠券
                  $yongjin=$zkPrice*$commissionRate/100;//佣金
                  $couponAmount=0;//优惠券初始为，就
                }else{
                  $yongjin=($zkPrice-$couponAmount)*$commissionRate/100;//佣金
                }
                
                if($cfg['fxtype']==1){//积分
                    $zyh=$couponAmount;//积分只要计算优惠券金额就可以了
                    $zyhhprice=$zkPrice-$zyh;//优惠后价格  
                    $flyj=$yongjin*$cfg['zgf']/100*$cfg['jfbl'];//自购佣金
                }else{//余额            
                    $yongjin=number_format($yongjin, 2, '.', ''); 
                    $flyj=$yongjin*$cfg['zgf']/100;//自购佣金
                    $flyj=number_format($flyj, 2, '.', ''); 
                    $zyh=$couponAmount+$flyj;//优惠金额
                    $zyhhprice=$zkPrice-$zyh;//优惠后价格
                }
                if(!empty($couponLinkTaoToken)){
                  $taokouling=$couponLinkTaoToken;
                }else{
                  $taokouling=$taoToken;
                }
                //券后价
                $qhjpric=$zkPrice-$couponAmount;

                $data=array(
                    'num_iid'=>$auctionId,//商品ID
                    'title'=>$title,//名称
                    'commissionRate'=>$commissionRate,//佣金比例
                    'qhjpric'=>$qhjpric,//券后价
                    'price'=>$zkPrice,//商品折扣价格
                    'zyhhprice'=>$zyhhprice,//优惠后价格
                    'zyh'=>$zyh,//优惠金额
                    'biz30day'=>$biz30day,//30天销量
                    'couponAmount'=>$couponAmount,//优惠券金额
                    'commissionRate'=>$commissionRate,//佣金比例
                    'flyj'=>$flyj,//自购佣金
                    'taokouling'=>$taokouling,//淘口令
                    'couponid'=>$youhui['id'],//优惠券ID
                    'couponendtime'=>$youhui['endtime'],//优惠券到期时间
                    'numid'=>$auctionId,//商品ID
                    'pictUrl'=>$pictUrl,
                    'qq'=>$dx,
                    'dtkl'=>$taoToken, //联盟 商品淘口令
                    'dqrCodeUrl'=>$qrCodeUrl, //联盟 商品二维码链接
                    'dclickUrl'=>$gyj['coupon_click_url'], //联盟 商品长链接
                    'dcouponLinkTaoToken'=>$couponLinkTaoToken, //联盟 优惠券淘口令
                    'dcouponLink'=>$gyj['coupon_click_url'], //联盟 优惠券长链接
                    'dshortLinkUrl'=>$shortLinkUrl, //联盟 短链接
                );
                //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n---res---".json_encode($data),FILE_APPEND);
                Return $data;
        }
        
        //联盟API高佣金结束


        $GetUrl="http://pub.alimama.com/common/getUnionPubContextInfo.json";
		$str = curl_request($GetUrl,"",$Cookies,0);
        $title=Text_qzj($str,"<title>","</title>");
        if($title=='亲，访问受限了'){
          Return array('error'=>'亲，访问受限了'); 
        }
        $arr=json_decode($str);
        $marr=$arr->data;
        $memberid=$marr->memberid;
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n mid:".json_encode($memberid),FILE_APPEND);
        
         
        //echo "<pre>";
        //print_r($arr);
        if(substr_count($str,"暂时无法处理您的请求")>0){
            //echo '抱歉，暂时无法处理您的请求！';
               if(!empty($cfg['error3'])){
                   Return array('error'=>$cfg['error3']); 
                }else{
                  Return array('error'=>'抱歉，暂时无法处理您的请求！');
                }
        }
        if($memberid<1){
            //echo '链接查询失败。超时，请联系管理员！';
               if(!empty($cfg['error4'])){
                   Return array('error'=>$cfg['error4']); 
                }else{
                  Return array('error'=>'链接查询失败。超时，请联系管理员！');
                }
        }

        //查询计划
        $jhurl="http://pub.alimama.com/pubauc/getCommonCampaignByItemId.json?itemId=".$auctionId."&t=".getMillisecond()."&_tb_token_=".$_tb_tok."&pvid=";
        $cxjharr =curl_request($jhurl,"",$Cookies,0);
        $title=Text_qzj($cxjharr,"<title>","</title>");
        if($title=='亲，访问受限了'){
          Return array('error'=>'亲，访问受限了'); 
        }
        $jharr = json_decode($cxjharr);
        $maxarr = getMaxArr($jharr->data);//最大计划数组
        $ysqjh=getshjh($jharr->data);//查询已申请计划
        $CampaignID=$maxarr['CampaignID'];
        $ShopKeeperID=$maxarr['ShopKeeperID'];
        $commissionRate=$maxarr['commissionRate'];//定向高佣金比例
                     
              if(!empty($ysqjh['CampaignID'])){//如果有申请计划，就退出计划
                 //$shop=urlencode(mb_convert_encoding($shopTitle, 'utf-8', 'gb2312'));
                  //查询店铺申请的计划
                    $cxurl="http://pub.alimama.com/campaign/joinedCampaigns.json?toPage=1&nickname=".$nick."&perPageSize=40&t=".getMillisecond()."&pvid=&_tb_token_=".$_tb_tok."&_input_charset=utf-8";
                    $cxjharr =curl_request($cxurl,"",$Cookies,0);
                    $cx = json_decode($cxjharr);
                    //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\njhcxh00  ".$shopTitle,FILE_APPEND);
                    file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\njhcxh00  ".json_encode($cx),FILE_APPEND);
                    
                    $pubCamp=scdgjh($cx->data->pagelist,$ysqjh['CampaignID']);
                    file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\njhcxh0  ".json_encode($pubCamp),FILE_APPEND);
                    $pubCampaignid=$pubCamp['id'];
                    file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\njhcxh1  ".json_encode($pubCampaignid),FILE_APPEND);
                    if(!empty($pubCampaignid)){
                        $tuic_url = 'http://pub.alimama.com/campaign/exitCampaign.json';
                        $build_query = array('pubCampaignid' => $pubCampaignid,'t' =>getMillisecond(),'pvid'=>'','_tb_token_' => $_tb_tok );
                        //$arr=curl_request($tuic_url,$build_query,$Cookies, $returnCookie=0);
                        $build_query = http_build_query($build_query);
                        $ch2 = curl_init();
                        $tiem_out = 500;
                        curl_setopt($ch2, CURLOPT_URL, $tuic_url);
                        curl_setopt($ch2, CURLOPT_REFERER, 'http://www.alimama.com/index.htm');
                        curl_setopt($ch2, CURLOPT_POST, true);
                        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Cookie:{' . $Cookies . '}'));
                        curl_setopt($ch2, CURLOPT_POSTFIELDS, $build_query);
                        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, $tiem_out);
                        $campaign_data = curl_exec($ch2);
                        curl_close($ch2);  
                        file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\njhcxh003".$campaign_data,FILE_APPEND);
                        if(empty($campaign_data)){
                            $build_query = array('pubCampaignid' => $pubCampaignid,'t' =>getMillisecond(),'pvid'=>'','_tb_token_' => $_tb_tok );
                            $arr=curl_request($tuic_url,$build_query,$Cookies, $returnCookie=0);   
                            file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\njhcxh004".$arr,FILE_APPEND);
                        }
                    }
              }
           //结束    

          if($tkRate>=$commissionRate and $tkRate>=$eventRate){
              $dx=0;//普通
          }elseif($commissionRate>$tkRate and $commissionRate>=$eventRate){
              $dx=1;//定向
          }elseif($eventRate>$tkRate and $eventRate>$commissionRate){
              $dx=2;//鹊桥
          }          
          //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode("dingxiang:".$dx),FILE_APPEND);
        
        
        //echo "<pre>";
        //print_r($maxarr);
        //echo '----------------------------------------------';
        //print_r($jharr->data);
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode("".$dx."--".$tkRate."*******".$commissionRate."*****".$eventRate."***"),FILE_APPEND);

         if($dx==1){
             //申请高佣金定向计划 POST
                $applyreason="鹊桥库联盟万人齐推,长期合作,请速过!";//推广理由
                $campaign_url = 'http://pub.alimama.com/pubauc/applyForCommonCampaign.json';
                $build_query = array('_tb_token_' => $_tb_tok, 'applyreason' => $applyreason, 'campId' => $CampaignID, 'keeperid' => $ShopKeeperID, 't' =>getMillisecond());
                $arr=curl_request($campaign_url,$build_query,$Cookies, $returnCookie=0);
                $title=Text_qzj($arr,"<title>","</title>");
                if($title=='亲，访问受限了'){
                  Return array('error'=>'亲，访问受限了'); 
                }
                $sjj = json_decode($arr);
                //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n--cxjh=".$sjj,FILE_APPEND);
                //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","sjc\n".getMillisecond(),FILE_APPEND);
                
                if($sjj->ok==false){
                    $build_query = http_build_query($build_query);
                    $ch2 = curl_init();
                    $tiem_out = 500;
                    curl_setopt($ch2, CURLOPT_URL, $campaign_url);
                    curl_setopt($ch2, CURLOPT_REFERER, 'http://www.alimama.com/index.htm');
                    curl_setopt($ch2, CURLOPT_POST, true);
                    curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Cookie:{' . $Cookies . '}'));
                    curl_setopt($ch2, CURLOPT_POSTFIELDS, $build_query);
                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, $tiem_out);
                    $campaign_data = curl_exec($ch2);
                    curl_close($ch2);
                }
               file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode("--------sqjihuaaaa-----------"),FILE_APPEND);
               file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\nshenqing01".json_encode($sjj->ok),FILE_APPEND);
               file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\nshenqing02".$campaign_data,FILE_APPEND); 
                
                //申请高佣金定向计划结束
         }
        
        //获取取返现链接
        //http://pub.alimama.com/common/code/getAuctionCode.json?auctionid=539419057160&adzoneid=63558203&siteid=17524774&scenes=3&channel=tk_qqhd&t=1482250697054&_tb_token_=sCcMcHjcMAq&pvid=19_101.70.210.7_721_1482249727195
        //mm_13157221_17524774_63558203
        //mm_13157221_17524774_63558203
        if($dx==2){//鹊桥
           $GetUrl="http://pub.alimama.com/common/code/getAuctionCode.json?auctionid=".$auctionId."&adzoneid=".$cfg['adzoneid']."&siteid=".$cfg['siteid']."&scenes=3&channel=tk_qqhd&t=".getMillisecond()."&_tb_token_=".$_tb_tok."";           
        }else{
            $GetUrl="http://pub.alimama.com/common/code/getAuctionCode.json?auctionid=".$auctionId."&adzoneid=".$cfg['adzoneid']."&siteid=".$cfg['siteid']."&scenes=1&ua=&t=".getMillisecond()."&_tb_token_=".$_tb_tok."&_input_charset=utf-8";          
           //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode("--------------zhouputao---------------"),FILE_APPEND);
        }
        
        $str = curl_request($GetUrl,"",$Cookies,0);
        $title=Text_qzj($str,"<title>","</title>");
        if($title=='亲，访问受限了'){
          Return array('error'=>'亲，访问受限了'); 
        }
        $arr=json_decode($str);
        //echo "<pre>";
        //print_r($arr);
        $tbgoods=$arr->data;
        if(empty($arr->info->ok)){
             $message=$arr->info->message."ad,st";
             Return array('error'=>$message);
           //echo $arr->info->message."adzoneid，siteid的值";
        }
        $taoToken=$tbgoods->taoToken;//商品淘口令
        $qrCodeUrl=$tbgoods->qrCodeUrl;//商品二维码链接
        $clickUrl=$tbgoods->clickUrl;//商品长链接
        $couponLinkTaoToken=$tbgoods->couponLinkTaoToken;//优惠券淘口令
        $couponLink=$tbgoods->couponLink;//优惠券长链接        
        $shortLinkUrl=$tbgoods->shortLinkUrl;//商品短链接
        $title=$gooodarr->title;//商品名称
        
        $pictUrl=$gooodarr->pictUrl;//图片
        $auctionId=$gooodarr->auctionId;//商品ID
        $zkPrice=$gooodarr->zkPrice;//商品原价
        if(!empty($youhui['id'])){
          $couponAmount=$youhui['money'];//淘客助手查出来的最优优惠券
        }else{
          $couponAmount=$gooodarr->couponAmount;//商品优惠券金额
        }

        


        if($dx==2){//鹊桥
           $commissionRate=$eventRate;        
        }elseif($dx==1){//定向
            $commissionRate=$commissionRate;
        }else{//普通
           $commissionRate=$tkRate;
        }
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\n".json_encode("yj:".$commissionRate),FILE_APPEND);
        

        if(empty($youhui['id'])){//如果ID为空优惠券有门槛的，就不计算优惠券
          $yongjin=$zkPrice*$commissionRate/100;//佣金
          $couponAmount=0;//优惠券初始为，就
        }else{
          $yongjin=($zkPrice-$couponAmount)*$commissionRate/100;//佣金
        }
        
        if($cfg['fxtype']==1){//积分
            $zyh=$couponAmount;//积分只要计算优惠券金额就可以了
            $zyhhprice=$zkPrice-$zyh;//优惠后价格  
            $flyj=$yongjin*$cfg['zgf']/100*$cfg['jfbl'];//自购佣金
        }else{//余额            
            $yongjin=number_format($yongjin, 2, '.', ''); 
            $flyj=$yongjin*$cfg['zgf']/100;//自购佣金
            $flyj=number_format($flyj, 2, '.', ''); 
            $zyh=$couponAmount+$flyj;//优惠金额
            $zyhhprice=$zkPrice-$zyh;//优惠后价格
        }
        if(!empty($couponLinkTaoToken)){
          $taokouling=$couponLinkTaoToken;
        }else{
          $taokouling=$taoToken;
        }
        //券后价
        $qhjpric=$zkPrice-$couponAmount;

        $data=array(
            'num_iid'=>$auctionId,//商品ID
            'title'=>$title,//名称
            'commissionRate'=>$commissionRate,//佣金比例
            'qhjpric'=>$qhjpric,//券后价
            'price'=>$zkPrice,//商品折扣价格
            'zyhhprice'=>$zyhhprice,//优惠后价格
            'zyh'=>$zyh,//优惠金额
            'biz30day'=>$biz30day,//30天销量
            'couponAmount'=>$couponAmount,//优惠券金额
            'commissionRate'=>$commissionRate,//佣金比例
            'flyj'=>$flyj,//自购佣金
            'taokouling'=>$taokouling,//淘口令
            'couponid'=>$youhui['id'],//优惠券ID
            'couponendtime'=>$youhui['endtime'],//优惠券到期时间
            'numid'=>$auctionId,//商品ID
            'pictUrl'=>$pictUrl,
            'qq'=>$dx,
            'dtkl'=>$taoToken, //联盟 商品淘口令
            'dqrCodeUrl'=>$qrCodeUrl, //联盟 商品二维码链接
            'dclickUrl'=>$clickUrl, //联盟 商品长链接
            'dcouponLinkTaoToken'=>$couponLinkTaoToken, //联盟 优惠券淘口令
            'dcouponLink'=>$couponLink, //联盟 优惠券长链接
            'dshortLinkUrl'=>$shortLinkUrl, //联盟 短链接
        );
       

     file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\ndata--".json_encode($data),FILE_APPEND);
     
        Return $data;
	}


    //优惠券20170523
    function yksGetCoupons($sellerId,$itemId,$xmoney,$tbtk)
	{

		@$tbcookie =$tbtk;
		$success = false;
		$slNeter = new slNeter();
		$couponArray = array();
		$urlObjsArray = array(
							'shop'=>array(
									'url'=>'https://cart.taobao.com/json/GetPriceVolume.do?sellerId='.$sellerId,
									'cookieString'=>$tbcookie,
									'timeout'=>6
									),
//							'tkzs'=>array(
//									'url'=>"http://zhushou3.taokezhushou.com/api/v1/coupons_base/".$sellerId."?item_id=".$itemId,
//									'timeout'=>6
//								  ),
//							'ctk'=>array(
//										 	'url'=>"http://vip.taoqueqiao.com/?mod=inc&act=plugin&do=quan&iid=".$itemId,
//											'timeout'=>6
//										 ),
							'qtk'=>array(
									'url'=>'http://www.qingtaoke.com/api/UserPlan/UserCouponList',
									'postArray'=>array(
														'gid'=>$itemId,
														'sid'=>$sellerId
													   ),
									'headerArray'=>array('version'=>'17.2.21',
															'versionCode'=>'36',
															'vc'=>'3348997'
															),
									'timeout'=>6
								  )
						);
		$results=$slNeter->batRequest($urlObjsArray);
		#店铺优惠券
		$shopCouponsArray = json_decode(iconv("GB2312","UTF-8//IGNORE",$results['shop']),true);
		if(isset($shopCouponsArray['priceVolumes']))
		{
			$success=true;
			foreach($shopCouponsArray['priceVolumes'] as $o)
			{
				if(isset($couponArray[$o['id']])) continue;
				else 
				{
					$timeRange = $o['timeRange'];
					$timeRangeR = explode("-",$timeRange);
					$start = str_replace('.','-',trim($timeRangeR[0]));
					$end = str_replace('.','-',trim($timeRangeR[1]));
					preg_match("/([\d.]+)/",$o['condition'],$match);
					$condition = $match[1]; 
					$couponArray[$o['id']] = array(
												   	'success'=>true,
													'money'=>$o['price'],
													'stime'=>$start,
													'endtime'=>$end,
													'xemoney'=>$condition,
													'id'=>$o['id'],
													'type'=>'shop'
												   );
				}
			}
		}
		#淘客助手
		$tkzsCouponsArray = json_decode($results['tkzs'],true);
		if(isset($tkzsCouponsArray['data']))
		{
			$success=true;
			foreach($tkzsCouponsArray['data'] as $o)
			{
				if(isset($couponArray[$o['activity_id']])) continue;
				else $couponArray[$o['activity_id']] = array();
			}
		}
		#查淘客
		$ctkCouponsArray = json_decode($results['ctk'],true);
		if(!empty($ctkCouponsArray['r']))
		{
			$success=true;
			if(!isset($couponArray[$ctkCouponsArray['r']])) 
			{
				$couponArray[$ctkCouponsArray['r']] = array();
			}
		}
		#轻淘客
		$qtkCouponsArray = json_decode($results['qtk'],true);
		if(isset($qtkCouponsArray['data']))
		{
			$success=true;
			foreach($qtkCouponsArray['data'] as $o)
			{
				if(isset($couponArray[$o['activityId']])) continue;
				else $couponArray[$o['activityId']] = array();
			}
		}
		
		if(!$success) return false;
		
		#把那些没有不知道情况的优惠券拿去查
		$urlObjsArray = array();
		foreach($couponArray as $aid=>$info)
		{
			if(empty($info['success']))
			{
				$urlObjsArray[$aid] = array(
												#'url'=>'http://shop.m.taobao.com/shop/coupon.htm?seller_id='.$sellerId.'&activity_id='.$aid
												'url'=>'https://uland.taobao.com/cp/coupon?activityId='.$aid.'&itemId='.$itemId
											);
			}
		}
		if(sizeof($urlObjsArray))
		{
			$results=$slNeter->batRequest($urlObjsArray);
			foreach($results as $aid=>$content)
			{
				$couponinfo = json_decode($content,true);
				if(empty($couponinfo['success']) || empty($couponinfo['result']['amount']))
				{
					$couponArray[$aid]['success']=false;
					continue;
				}
				else
				{
					$couponArray[$aid]= array(
											  'success'=>true,
											  'money'=>$couponinfo['result']['amount'],
											  'stime'=>date('Y-m-d',strtotime($couponinfo['result']['effectiveStartTime'])),
											  'endtime'=>date('Y-m-d',strtotime($couponinfo['result']['effectiveEndTime'])),
											  'xemoney'=>$couponinfo['result']['startFee'],
											  'id'=>$aid
											 );
				}
			}
		}

		

		$theActiveId = couponChoose($couponArray,$xmoney);
		if($theActiveId)
		{
			$youhui = $couponArray[$theActiveId];
		}
		else
		{
			$youhui = array();
		}
		
		return $youhui;
	}
    function couponChoose($couponArray,$price)
	{
		$available = array();
		foreach($couponArray as $activityId=>$couponObj)
		{
			if(!$couponObj['success'])continue;
			if(strtotime($couponObj['stime'])>time() || strtotime($couponObj['endtime'].' 23:59:59')<time()) continue;
			if($couponObj['xemoney']<=$price)	$available[$activityId] = $couponObj['money'];
		}
		if($available)
		{
			arsort($available);
			$k = array_keys($available);
			return $k[0];
		}
		else return false;
	}
    //优惠券结束

    function taokejh($numid,$sign,$tbuid,$adzoneid,$siteId,$tkurl,$tkip,$cfg) {
        //$pid=explode("_",$pid);
        //$adzoneid=$pid[3];
        //$siteId=$pid[2];
        //file_put_contents(IA_ROOT."/addons/tiger_taoke/log.txt","\ntaokejh--".json_encode($numid."-".$sign."-".$tbuid."-".$adzoneid."-".$siteId),FILE_APPEND);

        if(empty($numid) || empty($adzoneid) || empty($sign)){
          $arr=json_decode(array('error'=>1),TRUE);
          Return $arr;
        }
        $url="http://cs.youqi18.com/app/index.php?i=3&c=entry&do=tkapi&m=tiger_shouquan&numid=".$numid."&adzoneid=".$adzoneid."&tbuid=".$tbuid."&siteId=".$siteId."&sign=".$sign."&tkurl=".$tkurl."&tkip=".$tkip."&sq=".$cfg['cxsqtype']."";
        $arr=curl_request($url);
         

        $arr=json_decode($arr,TRUE);
        Return $arr;
   }

   function getfc($str,$wi){//分词
       if(empty($str)){
           $arr=json_decode(array('error'=>'分词标题必须要填写！'),TRUE);
         return $arr;
       }
       $tkurl=urlencode($wi['setting']['site']['url']);//urlencode($wi['siteroot']);
       $tkip=$_SERVER["SERVER_ADDR"];
       $url="http://cs.youqi18.com/app/index.php?i=3&c=entry&do=fc&m=tiger_shouquan&str=".$str."&tkurl=".$tkurl."&tkip=".$tkip."&sq=".$cfg['cxsqtype'];
       $str=curl_request($url);
       $arr = explode(' ',trim($str));
       foreach($arr as $k=>$v){
           if(empty($v)){
             continue;
           }
           if($k >4){
             continue;
           }
          $a[$k]=$v;
       }
       return $a;
   }


    function utf8_gbk($Text) {
        return iconv("UTF-8","gbk//TRANSLIT",$Text);
    }

   function getclick($url){//click链接

    $cookie_file = dirname(__FILE__) . '/my.cookie';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); //是否抓取跳转后的页面
    curl_setopt($ch, CURLOPT_MAXREDIRS, 0);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Host: s.click.taobao.com",
        "Connection: keep-alive",
        "Upgrade-Insecure-Requests: 1",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Encoding: gzip, deflate, sdch, br",
        "Accept-Language: zh-CN,zh;q=0.8"
    ));
    $output = curl_exec($ch);
    $curlinfo = curl_getinfo($ch);
    curl_close($ch);

    if (isset($curlinfo["redirect_url"])) {
        $url = $curlinfo["redirect_url"];
        $cookie_file = dirname(__FILE__) . '/my.cookie';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); //是否抓取跳转后的页面
        curl_setopt($ch, CURLOPT_MAXREDIRS, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Host: s.click.taobao.com",
            "Connection: keep-alive",
            "Upgrade-Insecure-Requests: 1",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Accept-Encoding: deflate, sdch, br",
            "Accept-Language: zh-CN,zh;q=0.8"
        ));
        $output = curl_exec($ch);
        $curlinfo = curl_getinfo($ch);
        curl_close($ch);

        //解析页面生成新的url
        $oulurl = $url;
        $tuurl = explode('?tu=', $url);
        $url = urldecode($tuurl[1]);
        $cookie_file = dirname(__FILE__) . '/my.cookie';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); //是否抓取跳转后的页面
        curl_setopt($ch, CURLOPT_MAXREDIRS, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Host: s.click.taobao.com",
            "Connection: keep-alive",
            "Upgrade-Insecure-Requests: 1",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Referer: " . $oulurl,
            "Accept-Encoding: deflate, sdch, br",
            "Accept-Language: zh-CN,zh;q=0.8"
        ));
        $output = curl_exec($ch);
        $curlinfo = curl_getinfo($ch);
        curl_close($ch);
        $url = $curlinfo["redirect_url"];
        return $url;     
     }
   }

   function curl_request($url,$post='',$cookie='', $returnCookie=0){
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

    function Text_qzj($Text,$Front,$behind) {
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

    function getMillisecond() {
        //list($t1, $t2) = explode(' ', microtime());
        $time=time();
        $ran=rand(100,300);
        $t=$time.$ran;
        return $t;
        //return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

     function getMaxArr($obj) {
            $max = 0;
            $data = '';
            $reArr = array();
            foreach ($obj as $key => $value) {
               if($value->Properties == '否'){
                  $matches = (float) trim($value->commissionRate, ' %');
                  
                   if ($max < $matches) {                       
                       $max = $matches;
                       $data = $value;                       
                   }
               }
            }
            foreach ($data as $key => $value) {
                $reArr[$key] = $value;
            }
            return $reArr;
    }
    
    //已申请计划
    function getshjh($obj) {
            $max = 0;
            $data = '';
            $reArr = array();
            foreach ($obj as $key => $value) {
               if($value->Exist == 'true'){
                  $data = $value;
               }
            }
            foreach ($data as $key => $value) {
                $reArr[$key] = $value;
            }
            return $reArr;
    }
    //查询申请过的计划
    function scdgjh($data,$CampaignID) {
            $datastr = '';
            $reArr = array();
            foreach ($data as $key => $value) {
               if($value->campaignId == $CampaignID){
                  $datastr = $value;
               }
            }
            foreach ($datastr as $key => $value) {
                $reArr[$key] = $value;
            }
            return $reArr;        
    }


    function gettkl($tturl,$title,$pic) {
       $turl=dwz($tturl);//短网址
       $ttitle=urlencode($title);
       $pic=urlencode($pic);
       $time=getMillisecond();
       $url="https://mf.alibaba-inc.com/app-s/createTK?url=".$turl."&title=".$ttitle."&pic=".$pic."&_ksTS=".$time."_11&callback=jsonp12 ";
       $tklcon=curl_request($url);
       $tkl=Text_qzj($tklcon,'￥','￥');
       $tkl="￥".$tkl."￥";
       return $tkl;        
   }

   function dwz($url) {//短网址API
        $url=urlencode($url);
        $sinaurl="http://api.t.sina.com.cn/short_url/shorten.json?source=3271760578&url_long={$url}";
        load()->func('communication');
        $json = ihttp_get($sinaurl);
        $result = @json_decode($json['content'], true);
        return $result[0]['url_short'];  
    }

    function getyhjxx($activity_id, $couponsid) {//单个优惠券返回信息
        $url = 'http://shop.m.taobao.com/shop/coupon.htm?activityId=' . $activity_id . '&sellerId=' . $couponsid;
        $curlobj = curl_init();   // 初始化
        curl_setopt($curlobj, CURLOPT_URL, $url);  // 设置访问网页的URL
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);   // 执行之后不直接打印出来
        curl_setopt($curlobj, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookiefile.txt');
        curl_setopt($curlobj, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookiefile2.txt');
    //curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
    //curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  

        curl_setopt($curlobj, CURLOPT_HEADER, 0);
        curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1); // 这样能够让cURL支持页面链接跳转

        curl_setopt($curlobj, CURLOPT_POST, 0);
    //curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlobj, CURLOPT_HTTPHEADER, array(
            "Host: shop.m.taobao.com",
            "Connection: keep-alive",
            "Cache-Control: max-age=0",
            "Upgrade-Insecure-Requests: 1",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Accept-Encoding:  deflate, sdch",
            "Accept-Language: zh-CN,zh;q=0.8"
        ));
        $output = curl_exec($curlobj); // 执行
        curl_close($curlobj);   // 关闭cURL

        $returndata = array();
        if (strpos($output, '已经过期') !== FALSE) {
            
        } else {
            $output = explode('"coupon-info">', $output, 2);
            $output = substr($output[1], 0, 500);
            preg_match_all('|<dt>([\d\.]+).*单笔满([\d\.]+).*有效期:(.*)至(.*)<\/dd|ism', $output, $returnArr);

            $returndata['id'] = $activity_id;
            $returndata['money'] = $returnArr[1][0];
            $returndata['xemoney'] = $returnArr[2][0];
            $returndata['stime'] = $returnArr[3][0];
            $returndata['endtime'] = $returnArr[4][0];


            $substr = $returnArr[0][0];

            preg_match_all('|rest">(\d*)</span>|', $substr, $returnArr);
            $returndata['shengyu'] = $returnArr[1][0]; //剩余

            preg_match_all('|count">(\d*)</span>|', $substr, $returnArr);
            $returndata['yiling'] = $returnArr[1][0]; //已经领取

            preg_match_all('|每人限领(\d*)|', $substr, $returnArr);
            $returndata['xianling'] = $returnArr[1][0]; //限制领取
        }
        return $returndata;
    }


    function getyouhui($itemid, $couponsid, $price) {
        $returndata = array();

        $url = 'http://zhushou3.taokezhushou.com/api/v1/getdata?itemid=' . $itemid . '&version=3.5.2';
        $curlobj = curl_init();   // 初始化
        curl_setopt($curlobj, CURLOPT_URL, $url);  // 设置访问网页的URL
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);   // 执行之后不直接打印出来
        curl_setopt($curlobj, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookiefile.txt');
    //curl_setopt($curlobj, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookiefile.txt');
    //curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
    //curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  

        curl_setopt($curlobj, CURLOPT_HEADER, 0);
        curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1); // 这样能够让cURL支持页面链接跳转

        curl_setopt($curlobj, CURLOPT_POST, 0);
    //curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlobj, CURLOPT_HTTPHEADER, array(
            "Host: zhushou3.taokezhushou.com",
            "Connection: keep-alive",
            "Cache-Control: max-age=0",
            "Upgrade-Insecure-Requests: 1",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Accept-Encoding: gzip, deflate, sdch",
            "Accept-Language: zh-CN,zh;q=0.8"
        ));
        $output = curl_exec($curlobj); // 执行
        curl_close($curlobj);   // 关闭cURL
    //file_put_contents('1.php', $output);




        $url = 'http://zhushou3.taokezhushou.com/api/v1/coupons_base/' . $couponsid . '?item_id=' . $itemid;
        $curlobj = curl_init();   // 初始化
        curl_setopt($curlobj, CURLOPT_URL, $url);  // 设置访问网页的URL
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);   // 执行之后不直接打印出来
    //curl_setopt($curlobj, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookiefile.txt');
        curl_setopt($curlobj, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookiefile.txt');
    //curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
    //curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  

        curl_setopt($curlobj, CURLOPT_HEADER, 0);
        curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1); // 这样能够让cURL支持页面链接跳转

        curl_setopt($curlobj, CURLOPT_POST, 0);
    //curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlobj, CURLOPT_HTTPHEADER, array(
            "Host: zhushou3.taokezhushou.com",
            "Connection: keep-alive",
            "Cache-Control: max-age=0",
            "Upgrade-Insecure-Requests: 1",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Accept-Encoding: gzip, deflate, sdch",
            "Accept-Language: zh-CN,zh;q=0.8",
        ));
        $output = curl_exec($curlobj); // 执行
        curl_close($curlobj);   // 关闭cURL
    //    echo $output; //输出字符

        $json = json_decode($output); //输出json对象

        if ($json->status == 200) {
            if (is_array($json->data) && count($json->data) > 0) {
                $conn = array();
                $mh = curl_multi_init();

                foreach ($json->data as $key => $value) {
                    $activity_id = $value->activity_id;
                    $status = $value->status;
                    $available = $value->available;
    //            var_dump($value);
    //            var_dump($url);
                    $url = 'http://shop.m.taobao.com/shop/coupon.htm?activityId=' . $activity_id . '&sellerId=' . $couponsid;
                    $curlobj = curl_init();   // 初始化
                    curl_setopt($curlobj, CURLOPT_URL, $url);  // 设置访问网页的URL
                    curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);   // 执行之后不直接打印出来
    //curl_setopt($curlobj, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookiefile.txt');
                    curl_setopt($curlobj, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookiefile2.txt');
    //curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
    //curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  

                    curl_setopt($curlobj, CURLOPT_HEADER, 0);
                    curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1); // 这样能够让cURL支持页面链接跳转

                    curl_setopt($curlobj, CURLOPT_POST, 0);
    //curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($curlobj, CURLOPT_HTTPHEADER, array(
                        "Host: shop.m.taobao.com",
                        "Connection: keep-alive",
                        "Cache-Control: max-age=0",
                        "Upgrade-Insecure-Requests: 1",
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36",
                        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                        "Accept-Encoding:  deflate, sdch",
                        "Accept-Language: zh-CN,zh;q=0.8"
                    ));
                    $conn[$key] = array();
                    $conn[$key]['conn'] = $curlobj;
                    curl_multi_add_handle($mh, $conn[$key]['conn']);
                }


                do {
                    curl_multi_exec($mh, $active);
                } while ($active);
                $youhuijuan = array();
                foreach ($json->data as $key => $value) {
                    $output = curl_multi_getcontent($conn[$key]['conn']);
                    if (strpos($output, '已经过期') !== FALSE) {
                        
                    } else {
                        $output = explode('"coupon-info">', $output, 2);
                        $output = substr($output[1], 0, 500);
                        preg_match_all('|<dt>([\d\.]+).*单笔满([\d\.]+).*有效期:(.*)至(.*)<\/dd|ism', $output, $matches);
                        $matches[0][0] = $value;
                        $youhuijuan[] = $matches;
                    }
                }

                $maxpay = 0;
                $returnArr = array();
                foreach ($youhuijuan as $key => $value) {
                    if ($price >= $value[2][0] && $value[2][0] > 0) {
                        //比较金额大小
                        $returnArr[] = $value;
                    }
                }
                $maxreturnArr = array();
                foreach ($returnArr as $key => $value) {
                    if ($maxpay < $value[1][0]) {
                        $maxpay = $value[1][0];
                        $maxreturnArr = $value;
                    }
                }
                $returnArr = $maxreturnArr;
    //          var_dump($returnArr);

                foreach ($json->data as $key => $value) {
                    curl_multi_remove_handle($mh, $conn[$key]['conn']);
                    curl_close($conn[$key]['conn']);
                }
                curl_multi_close($mh);
                if (count($returnArr) > 0) {
                    $returndata['id'] = $returnArr[0][0]->activity_id;
                    $returndata['money'] = $returnArr[1][0];
                    $returndata['xemoney'] = $returnArr[2][0];
                    $returndata['stime'] = $returnArr[3][0];
                    $returndata['endtime'] = $returnArr[4][0];
                }
            }
        }
        return $returndata;
    }


    function getqtk($itemid, $couponsid, $price) {//轻淘客
        $returndata = array();
        $data = "gid=" . $itemid . "&sid=" . $couponsid;
        $url = 'http://www.qingtaoke.com/api/UserPlan/UserCouponList';
        $curlobj = curl_init();   // 初始化
        curl_setopt($curlobj, CURLOPT_URL, $url);  // 设置访问网页的URL
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);   // 执行之后不直接打印出来
        curl_setopt($curlobj, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookiefile.txt');
        curl_setopt($curlobj, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookiefile.txt');
        curl_setopt($curlobj, CURLOPT_HEADER, 0);
        curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1); // 这样能够让cURL支持页面链接跳转
        curl_setopt($curlobj, CURLOPT_POST, 1);
        curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlobj, CURLOPT_HTTPHEADER, array(
            "Connection: keep-alive",
            "Cache-Control: max-age=0",
            "Upgrade-Insecure-Requests: 1",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Accept-Encoding: gzip, deflate, sdch",
            "Accept-Language: zh-CN,zh;q=0.8"
        ));
        $output = curl_exec($curlobj); // 执行
        curl_close($curlobj);   // 关闭cURL

        if (!$output) {
            $curlobj = curl_init();   // 初始化
            curl_setopt($curlobj, CURLOPT_URL, $url);  // 设置访问网页的URL
            curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);   // 执行之后不直接打印出来
            curl_setopt($curlobj, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookiefile.txt');
            curl_setopt($curlobj, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookiefile.txt');
            curl_setopt($curlobj, CURLOPT_HEADER, 0);
            curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1); // 这样能够让cURL支持页面链接跳转
            curl_setopt($curlobj, CURLOPT_POST, 1);
            curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curlobj, CURLOPT_HTTPHEADER, array(
                "Connection: keep-alive",
                "Cache-Control: max-age=0",
                "Upgrade-Insecure-Requests: 1",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36",
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                "Accept-Encoding: gzip, deflate, sdch",
                "Accept-Language: zh-CN,zh;q=0.8"
            ));
            $output = curl_exec($curlobj); // 执行
            curl_close($curlobj);   // 关闭cURL
        }

        $json = json_decode($output); //输出json对象
        if (is_array($json->data) && count($json->data) > 0) {
            $conn = array();
            $mh = curl_multi_init();
            foreach ($json->data as $key => $value) {
                $activity_id = $value->activityId;
                $url = 'http://shop.m.taobao.com/shop/coupon.htm?activityId=' . $activity_id . '&sellerId=' . $couponsid;
                $curlobj = curl_init();   // 初始化
                curl_setopt($curlobj, CURLOPT_URL, $url);  // 设置访问网页的URL
                curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);   // 执行之后不直接打印出来
                curl_setopt($curlobj, CURLOPT_HEADER, 0);
                curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1); // 这样能够让cURL支持页面链接跳转
                curl_setopt($curlobj, CURLOPT_HTTPHEADER, array(
                    "Host: shop.m.taobao.com",
                    "Connection: keep-alive",
                    "Cache-Control: max-age=0",
                    "Upgrade-Insecure-Requests: 1",
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36",
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                    "Accept-Encoding:  deflate, sdch",
                    "Accept-Language: zh-CN,zh;q=0.8"
                ));
                $conn[$key] = array();
                $conn[$key]['conn'] = $curlobj;
                curl_multi_add_handle($mh, $conn[$key]['conn']);
            }
            do {
                curl_multi_exec($mh, $active);
            } while ($active);
            
            $youhuijuan = array();
            foreach ($json->data as $key => $value) {
                $output = curl_multi_getcontent($conn[$key]['conn']);
                if (strpos($output, '已经过期') !== FALSE) {
                } else {
                    $output = explode('"coupon-info">', $output, 2);
                    $output = substr($output[1], 0, 500);
                    preg_match_all('|<dt>([\d\.]+).*单笔满([\d\.]+).*有效期:(.*)至(.*)<\/dd|ism', $output, $matches);
                    $matches[0][0] = $value;
                    $youhuijuan[] = $matches;
                }
            }
            $maxpay = 0;
            $returnArr = array();
            foreach ($youhuijuan as $key => $value) {
                if ($price >= $value[2][0] && $value[2][0] > 0) {
                    //比较金额大小
                    $returnArr[] = $value;
                }
            }
            $maxreturnArr = array();
            foreach ($returnArr as $key => $value) {
                if ($maxpay < $value[1][0]) {
                    $maxpay = $value[1][0];
                    $maxreturnArr = $value;
                }
            }
            $returnArr = $maxreturnArr;
            foreach ($json->data as $key => $value) {
                curl_multi_remove_handle($mh, $conn[$key]['conn']);
                curl_close($conn[$key]['conn']);
            }
            curl_multi_close($mh);
            if (count($returnArr) > 0) {
                $returndata['id'] = $returnArr[0][0]->activityId;
                $returndata['money'] = $returnArr[1][0];
                $returndata['xemoney'] = $returnArr[2][0];
                $returndata['stime'] = $returnArr[3][0];
                $returndata['endtime'] = $returnArr[4][0];
            }
        }
        return $returndata;
    }
?>