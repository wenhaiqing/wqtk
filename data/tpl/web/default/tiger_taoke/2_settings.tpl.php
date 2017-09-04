<?php defined('IN_IA') or exit('Access Denied');?>
		<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_head', TEMPLATE_INCLUDEPATH)) : (include template('public_head', TEMPLATE_INCLUDEPATH));?>
		<!--中间内容开始-->
		<section>
		    <section class="hbox stretch">
		    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_left', TEMPLATE_INCLUDEPATH)) : (include template('public_left', TEMPLATE_INCLUDEPATH));?>
		    <!--右边框架-->
			  <section id="content">
			    <section class="vbox">
			       <section class="scrollable padder">
                        <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                          <li><a href="<?php  echo $this->createWebUrl('index')?>"><i class="fa fa-home"></i> 首页  </a></li>
                          <li class="active">基本设置</li>
                        </ul>
			            <!--编辑内容-->
                        <div class="main">
                            <form action="" method="post" class="form-horizontal form" id="setting-form">
                            <input type="hidden" name="id" value="<?php  echo $set['id'];?>">
                                <div class="panel panel-default">
                                    <div class="panel-heading">参数设置</div>
                                    <div class="panel-body">
                                        <ul class="nav nav-tabs" id="myTab">
                                            <li class="active" ><a href="#tab_basic">红包设置</a></li>
                                            <li><a href="#tab_share">商城设置</a></li>
                                            <li><a href="#tab_param">地区设置</a></li>
                                            <li><a href="#tab_param2">兑吧设置</a></li>
                                            <li><a href="#tab_param1">提醒设置</a></li>
                                            <li><a href="#tab_param3">海报生成限制</a></li>
                                            <!--li><a href="#tab_param4">短信验证</a></li-->
                                            <li><a href="#tab_param5">手机端模版</a></li>
                                            <li><a href="#tab_param6">API设置</a></li>
                                            <li><a href="#tab_param7">订单返现设置</a></li>
                                            <li><a href="#tab_param8">直播设置</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane  active" id="tab_basic">
                                                <!--提现设置-->
                                                <div class="panel-body">

                                                  <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包类型</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                               <label class="checkbox-inline">
                                                                  <input type="radio" name="txtype"  value="0" <?php  if($settings['txtype'] == 0) { ?>checked<?php  } ?>> 红包提现
                                                               </label>
                                                               <label class="checkbox-inline">
                                                                  <input type="radio" name="txtype"  value="1" <?php  if($settings['txtype'] == 1) { ?>checked<?php  } ?>> 企业零钱支付
                                                               </label>
                                                               <label class="checkbox-inline">
                                                                  <input type="radio" name="txtype"  value="2" <?php  if($settings['txtype'] == 2) { ?>checked<?php  } ?>> 支付宝
                                                               </label>
                                                               <label class="checkbox-inline">
                                                                  <input type="radio" name="txtype"  value="3" <?php  if($settings['txtype'] == 3) { ?>checked<?php  } ?>> 集分宝(比例：1元=100集分宝)
                                                               </label>
                                                                <span class="help-block" style="color:#ff0000"></span>
                                                            </div>
                                                   </div>



                                                  <!--div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否借权</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                   <label class="checkbox-inline">
                                                                      <input type="radio" name="jiequan" id="optionsRadios4" value="0" <?php  if($settings['jiequan'] == 0) { ?>checked<?php  } ?>> 关闭
                                                                   </label>
                                                                   <label class="checkbox-inline">
                                                                      <input type="radio" name="jiequan" id="optionsRadios4" value="1" <?php  if($settings['jiequan'] == 1) { ?>checked<?php  } ?>> 开启
                                                                   </label>
                                                                    <span class="help-block" style="color:#ff0000">如果要使用兑换红包必须开启</span>
                                                                </div>
                                                   </div-->

                                                   <input type="hidden" name="jiequan" value="0">

                                                   

                                                  <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">AppID(应用ID)</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <input type="text" name="appid" value="<?php  echo $settings['appid'];?>" class="form-control" placeholder="微信公众平台APPID" >
                                                                    <span class="help-block">微信公众平台APPID</span>
                                                                </div>
                                                   </div>
                                                   <div class="form-group">
                                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">AppSecret(应用密钥)</label>
                                                        <div class="col-sm-9 col-xs-12">
                                                            <input type="text" value="<?php  echo $settings['secret'];?>" class="form-control" name="secret">
                                                             <div class="help-block">认证服务号secret</div>
                                                        </div>
                                                    </div>

                                                  <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">MCHID(商户号)</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <input type="text" name="mchid" value="<?php  echo $settings['mchid'];?>" class="form-control" placeholder="微信支付商户号(MchId)" >
                                                                    <span class="help-block">输入微信MCHID参数</span>
                                                                </div>
                                                   </div>

                                                   <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">PARTNERKEY</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <input type="text" name="apikey" value="<?php  echo $settings['apikey'];?>" class="form-control" placeholder="商户支付密钥(API密钥)" >
                                                                    <span class="help-block">商户支付密钥(API密钥)</span>
                                                                </div>
                                                   </div>
                                                   <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">商户支付证书</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                <textarea class="form-control" name="cert" rows="8" placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接输入"></textarea>
                                                                <span class="help-block">从商户平台上下载支付证书, 解压并取得其中的 <mark>apiclient_cert.pem</mark> 用记事本打开并复制文件内容, 填至此处</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">支付证书私钥</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                <textarea class="form-control" name="key" rows="8" placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接输入"></textarea>
                                                                <span class="help-block">从商户平台上下载支付证书, 解压并取得其中的 <mark>apiclient_key.pem</mark> 用记事本打开并复制文件内容, 填至此处</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">支付根证书</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                <textarea class="form-control" name="ca" rows="8" placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接输入"></textarea>
                                                                <span class="help-block">从商户平台上下载支付证书, 解压并取得其中的 <mark>rootca.pem</mark> 用记事本打开并复制文件内容, 填至此处</span>
                                                            </div>
                                                        </div>


                                                       <div class="form-group">
                                                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">client_ip</label>
                                                                    <div class="col-xs-12 col-sm-9">
                                                                        <input type="text" name="client_ip" value="<?php  echo $settings['client_ip'];?>" class="form-control" placeholder="服务器IP" >
                                                                        <span class="help-block">发放红包接口需要服务器IP. 程序将自动获取你的服务器IP, 如果获取失败, 请手动指定，这里填写你的服务器IP：<?php  echo $_SERVER["SERVER_ADDR"]?></span>
                                                                    </div>
                                                       </div>   

                                               </div>
                                               <div class="form-group col-sm-12">
                                                    <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                                                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                                </div>
                                               
                                                <!---->
                                            </div>

                                            <div class="tab-pane" id="tab_share">
                                                <!--积分商城设置-->

                                                      <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">联盟库首页自定义商品</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="sylmkey" value="<?php  echo $settings['sylmkey'];?>" class="form-control" placeholder="" >
                                                                <span class="help-block">请填写关键词 多个关键词用 | 分隔开</span>
                                                            </div>
                                                        </div>

                                                      <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="color:#ff0000">商品库选择</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="mmtype" value="0" 
                                                                     <?php  if($settings['mmtype']==0) { ?> checked="checked" <?php  } ?>>自己采集</label>
                                                                    <label class="radio-inline"><input type="radio" name="mmtype" value="1"
                                                                     <?php  if($settings['mmtype']==1) { ?> checked="checked" <?php  } ?>>联盟商品库</label>
                                                                      <span class="help-block">选择联盟商品库，无需采集数据，全自动更新数据，缺点：手机端没有排序，优点：搜索商品和淘宝的体验一样,只适合模版9</span>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="color:#ff0000">超级搜索优惠券</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="cjss" value="0" 
                                                                     <?php  if($settings['cjss']==0) { ?> checked="checked" <?php  } ?>>无券商品也显示</label>
                                                                    <label class="radio-inline"><input type="radio" name="cjss" value="1"
                                                                     <?php  if($settings['cjss']==1) { ?> checked="checked" <?php  } ?>>只显示有券商品</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">共用商品库</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="gyspsj" value="<?php  echo $settings['gyspsj'];?>" class="form-control" placeholder="公众号ID" >
                                                                <span class="help-block">填写要共用哪个公众号的商品数据，就填哪个公众号ID，这样就不用自己采集商品数据了</span>
                                                            </div>
                                                        </div>
                                           
                          
                                                        <input type="hidden" name='style' value='style2'><!--排行榜选择-->
                                                        <input type="hidden" name='paihang' value='0'><!--排行榜选择-->
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">淘宝ID</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="tbid" value="<?php  echo $settings['tbid'];?>" class="form-control" placeholder="数字" >
                                                                <span class="help-block">填写对应的淘宝ID，在客户领取打开淘宝口令的时候，会检测收到消息</span>
                                                            </div>
                                                        </div>

                                                        <!--div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">淘口令</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="tkltype" value="0" 
                                                                     <?php  if($settings['tkltype']==0) { ?> checked="checked" <?php  } ?>>付费官方接口</label>
                                                                    <label class="radio-inline"><input type="radio" name="tkltype" value="1"
                                                                     <?php  if($settings['tkltype']==1) { ?> checked="checked" <?php  } ?>>官方隐藏接口</label>
                                                                      <span class="help-block">平时使用，尽量不要用隐藏接口</span>
                                                                </div>
                                                        </div-->

                                                        <input type="hidden" name='tkltype' value='0'>
                                                        
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">软件采集库</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="ljcjk" value="0" 
                                                                     <?php  if($settings['ljcjk']==0) { ?> checked="checked" <?php  } ?>>全部商品</label>
                                                                    <label class="radio-inline"><input type="radio" name="ljcjk" value="1"
                                                                     <?php  if($settings['ljcjk']==1) { ?> checked="checked" <?php  } ?>>群发商品</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">直播产品</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="zblive" value="0" 
                                                                     <?php  if($settings['zblive']==0) { ?> checked="checked" <?php  } ?>>全部商品</label>
                                                                    <label class="radio-inline"><input type="radio" name="zblive" value="1"
                                                                     <?php  if($settings['zblive']==1) { ?> checked="checked" <?php  } ?>>群发库商品</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div>

                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">秒杀头部图片</label>
                                                                <div class="col-sm-9">
                                                                    <?php  echo tpl_form_field_image('mspicurl',$settings['mspicurl'])?>
                                                                    <span class="help-block">如：请上传jpg图片 宽640</span>
                                                                </div>
                                                                
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">秒杀时间</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="mstime" value="<?php  echo $settings['mstime'];?>" class="form-control" placeholder="" >
                                                                <span class="help-block">填写秒杀到期时间: 如：2017/05/18 00:00:00</span>
                                                            </div>
                                                        </div>



                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">积分商城自定义链接</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="jfscurl" value="<?php  echo $settings['jfscurl'];?>" class="form-control" placeholder="http://" >
                                                                <span class="help-block">这里添加过链接，就显示这个商城地址</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">签到自定义链接</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="qiandaourl" value="<?php  echo $settings['qiandaourl'];?>" class="form-control" placeholder="http://" >
                                                                <span class="help-block">如：这里设置了，就显示这个签到</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">会员中心自定义链接</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="huiyuanurl" value="<?php  echo $settings['huiyuanurl'];?>" class="form-control" placeholder="http://" >
                                                                <span class="help-block">如：这里设置了，就显示这个会员中心链接</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">查券优惠券是否入库</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="cxrk" value="0" 
                                                                     <?php  if($settings['cxrk']==0) { ?> checked="checked" <?php  } ?>>不入库</label>
                                                                    <label class="radio-inline"><input type="radio" name="cxrk" value="1"
                                                                     <?php  if($settings['cxrk']==1) { ?> checked="checked" <?php  } ?>>入库</label>
                                                                      <span class="help-block">如果选择不入库，粉丝在公众号，群，个人机器人查询的产品将不写入商品数据库（如果要入库，需要开启软件助手才会入库）</span>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页红包雨开关</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="hongbaoykg" value="0" 
                                                                     <?php  if($settings['hongbaoykg']==0) { ?> checked="checked" <?php  } ?>>不显示</label>
                                                                    <label class="radio-inline"><input type="radio" name="hongbaoykg" value="1"
                                                                     <?php  if($settings['hongbaoykg']==1) { ?> checked="checked" <?php  } ?>>显示</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">晒单广场</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="sdguangc" value="0" 
                                                                     <?php  if($settings['sdguangc']==0) { ?> checked="checked" <?php  } ?>>不显示</label>
                                                                    <label class="radio-inline"><input type="radio" name="sdguangc" value="1"
                                                                     <?php  if($settings['sdguangc']==1) { ?> checked="checked" <?php  } ?>>显示</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包雨图片</label>
                                                                <div class="col-sm-9">
                                                                    <?php  echo tpl_form_field_image('cjpicurl',$settings['cjpicurl'])?>
                                                                </div>
                                                                <span class="help-block">如：请上传PNG透明图片</span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">模版9首页红包雨自定义链接</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="hongbaourl" value="<?php  echo $settings['hongbaourl'];?>" class="form-control" placeholder="http://" >
                                                                <span class="help-block">如：可以设置其它的链接</span>
                                                            </div>
                                                        </div>
                                                        <!--div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页显示</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="hpx" value="0" 
                                                                     <?php  if($settings['hpx']==0) { ?> checked="checked" <?php  } ?>>最新</label>
                                                                    <label class="radio-inline"><input type="radio" name="hpx" value="1"
                                                                     <?php  if($settings['hpx']==1) { ?> checked="checked" <?php  } ?>>随机</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div-->
                                                        <input type="hidden" name="hpx" value="0">
                                                        <!--div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="color:#ff0000">分词搜索</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="fcss" value="0" 
                                                                     <?php  if($settings['fcss']==0) { ?> checked="checked" <?php  } ?>>不开</label>
                                                                    <label class="radio-inline"><input type="radio" name="fcss" value="1"
                                                                     <?php  if($settings['fcss']==1) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div-->
                                                        <input type="hidden" name="fcss" value="1">
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">模版9详情页面返佣金</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="rxyjxs" value="0" 
                                                                     <?php  if($settings['rxyjxs']==0) { ?> checked="checked" <?php  } ?>>不显示</label>
                                                                    <label class="radio-inline"><input type="radio" name="rxyjxs" value="1"
                                                                     <?php  if($settings['rxyjxs']==1) { ?> checked="checked" <?php  } ?>>显示</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div>

                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">详情页浏览器打开</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="llqtype" value="0" 
                                                                     <?php  if($settings['llqtype']==0) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                    <label class="radio-inline"><input type="radio" name="llqtype" value="1"
                                                                     <?php  if($settings['llqtype']==1) { ?> checked="checked" <?php  } ?>>关闭</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">订单提醒轮播</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="lbtx" value="0" 
                                                                     <?php  if($settings['lbtx']==0) { ?> checked="checked" <?php  } ?>>不开</label>
                                                                    <label class="radio-inline"><input type="radio" name="lbtx" value="1"
                                                                     <?php  if($settings['lbtx']==1) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">排行榜</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="phbtype" value="0" 
                                                                     <?php  if($settings['phbtype']==0) { ?> checked="checked" <?php  } ?>>不开</label>
                                                                    <label class="radio-inline"><input type="radio" name="phbtype" value="1"
                                                                     <?php  if($settings['phbtype']==1) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                        </div>
                                                    
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">类型自定义</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="hztype" value="<?php  echo $settings['hztype'];?>" class="form-control" placeholder="如：积分" >
                                                                <span class="help-block">如：积分、欢乐豆</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">余额名称自定义</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="yetype" value="<?php  echo $settings['yetype'];?>" class="form-control" placeholder="如：金币" >
                                                                <span class="help-block">如：金币，新年币</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">版权设置</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="copyright" value="<?php  echo $settings['copyright'];?>" class="form-control" placeholder="不设置默认公众号名称" >
                                                                <span class="help-block">不设置默认公众号名称</span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享标题</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="fxtitle" value="<?php  echo $settings['fxtitle'];?>" class="form-control" placeholder="分享标题" >
                                                                <span class="help-block">分享标题</span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享介绍</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="fxcontent" value="<?php  echo $settings['fxcontent'];?>" class="form-control" placeholder="分享介绍" >
                                                                <span class="help-block">分享介绍</span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">分享图片</label>
                                                                <div class="col-sm-9">
                                                                    <?php  echo tpl_form_field_image('fxpicurl',$settings['fxpicurl'])?>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">公众号二维码</label>
                                                                <div class="col-sm-9">
                                                                    <?php  echo tpl_form_field_image('gzewm',$settings['gzewm'])?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">客服二维码</label>
                                                                <div class="col-sm-9">
                                                                    <?php  echo tpl_form_field_image('kfewm',$settings['kfewm'])?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-sm-12">
                                                                <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                                                                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                                            </div>
                                                <!---->
                                            </div>


                                            <div class="tab-pane" id="tab_param">
                                                <!--地区设置-->


                                                <div class="panel panel-default">
                                                   <div class="panel-heading">
                                                      地区限制
                                                   </div>
                                                   <div class="panel-body">
                                                      <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">地区定位类型</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <label class="radio-inline"><input type="radio" name="locationtype" value="3" 
                                                                 <?php  if($settings['locationtype']==3) { ?> checked="checked" <?php  } ?>>不限制</label>
                                                                <label class="radio-inline"><input type="radio" name="locationtype" value="0"
                                                                 <?php  if(empty($settings['locationtype'])) { ?> checked="checked" <?php  } ?>>ip定位</label>
                                                                <label class="radio-inline"><input type="radio" name="locationtype" value="1" 
                                                                 <?php  if($settings['locationtype']==1) { ?> checked="checked" <?php  } ?>>gps定位</label>
                                                                 <label class="radio-inline"><input type="radio" name="locationtype" value="2" 
                                                                 <?php  if($settings['locationtype']==2) { ?> checked="checked" <?php  } ?>>两者同时定位(两个获取到的地址一样能才参加)</label>
                                                                
                                                                  <span class="help-block">在非wifi网络下，利用ip地址定位地区的话，不准确。利用gps定位更准确，不过页面会弹出获取用户地理信息的提示</span>
                                                            </div>
                                                        </div>
                                                      <div class="form-group">
                                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">地区限制</label>
                                                        <div class="col-xs-12 col-sm-9">
                                                            <input type="text" name="city" value="<?php  echo $settings['city'];?>" class="form-control" placeholder="杭州市,北京市" >
                                                            <span class="help-block">输入杭州市就杭州地区的能参加,多地区请用 , 逗号隔开</span>
                                                        </div>
                                                     </div>
                                                   </div>
                                                </div>
                                                
                                                <!---->
                                            </div>

                                            <div class="tab-pane" id="tab_param1">
                                                <!--晒单奖励提醒-->


                                                <div class="panel panel-default">
                                                   <div class="panel-heading">
                                                     模版消息提醒设置(只有认证服务号才能使用)
                                                   </div>
                                                   <div class="panel-body">
                                                       <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">提醒管理员OPENID</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                    <input type="text" class="form-control" placeholder="" name="glyopenid" value="<?php  echo $settings['glyopenid'];?>">
                                                                    <div class="help-block">填写了，管理员也会收到相应的提醒</div>
                                                            </div>
                                                        </div>  
                                                        <div class="form-group">
                                                            <label for="type" class="col-sm-2 control-label">客户提交订单管理员消息</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                  <select class="form-control" name="khgetorder" id="khgetorder">
                                                                        <option value="0">请选择模版消息</option>
                                                                        <?php  if(is_array($mblist)) { foreach($mblist as $v) { ?>
                                                                        <option value="<?php  echo $v['id'];?>" <?php  if($settings['khgetorder']==$v['id']) { ?>selected <?php  } ?>><?php  echo $v['title'];?></option>  
                                                                        <?php  } } ?>
                                                                    </select>                
                                                                </div>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label for="type" class="col-sm-2 control-label">客户提现申请管理员通知</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                  <select class="form-control" name="khgettx" id="khgettx">
                                                                        <option value="0">请选择模版消息</option>
                                                                        <?php  if(is_array($mblist)) { foreach($mblist as $v) { ?>
                                                                        <option value="<?php  echo $v['id'];?>" <?php  if($settings['khgettx']==$v['id']) { ?>selected <?php  } ?>><?php  echo $v['title'];?></option>  
                                                                        <?php  } } ?>
                                                                    </select>                
                                                                </div>
                                                            </div>
                                                       </div>
                                                       <!--div class="form-group">
                                                            <label for="type" class="col-sm-2 control-label">粉丝奖励到帐提醒</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                  <select class="form-control" name="fsjldsz" id="fsjldsz">
                                                                        <option value="0">请选择模版消息</option>
                                                                        <?php  if(is_array($mblist)) { foreach($mblist as $v) { ?>
                                                                        <option value="<?php  echo $v['id'];?>" <?php  if($settings['fsjldsz']==$v['id']) { ?>selected <?php  } ?>><?php  echo $v['title'];?></option>  
                                                                        <?php  } } ?>
                                                                    </select>                
                                                                </div>
                                                            </div>
                                                       </div-->
                                                       <div class="form-group">
                                                            <label for="type" class="col-sm-2 control-label" style="color:#ff0000">群发模版消息</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                  <select class="form-control" name="qfmbid" id="qfmbid">
                                                                        <option value="0">请选择模版消息</option>
                                                                        <?php  if(is_array($mblist)) { foreach($mblist as $v) { ?>
                                                                        <option value="<?php  echo $v['id'];?>" <?php  if($settings['qfmbid']==$v['id']) { ?>selected <?php  } ?>><?php  echo $v['title'];?></option>  
                                                                        <?php  } } ?>
                                                                    </select>    
                                                                   <div class="help-block">选择模版，保存，在到左边菜单群发</div>
                                                                </div>
                                                            </div>
                                                       </div>
                                                       

                                                   </div>
                                                </div>

                                                <div class="panel panel-default">
                                                   <div class="panel-heading">
                                                     晒单提醒设置
                                                   </div>
                                                   <div class="panel-body">
                                                       <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">晒单奖励提醒</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                    <input type="text" class="form-control" placeholder="" name="sdjltx" value="<?php  echo $settings['sdjltx'];?>">
                                                                    <div class="help-block">晒单成功!\n奖励您#积分#积分</div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">上级奖励提醒</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                    <input type="text" class="form-control" placeholder="" name="sjjltx" value="<?php  echo $settings['sjjltx'];?>">
                                                                    <div class="help-block">您的朋友#昵称#晒单成功!\n奖励您#积分#积分</div>
                                                            </div>
                                                        </div>
                                                   </div>
                                                </div>



                                                <div class="panel panel-default setting">
                                                    <div class="panel-heading">公众号查找商品</div>
                                                    <div class="panel-body">
                                                            <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启找商品</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <label class="radio-inline"><input type="radio" name="tttype" value="0" 
                                                                 <?php  if($settings['tttype']==0) { ?> checked="checked" <?php  } ?>>否</label>
                                                                <label class="radio-inline"><input type="radio" name="tttype" value="1"
                                                                 <?php  if($settings['tttype']==1) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                
                                                                  <span class="help-block">开启后粉丝输入关键词如  找女装 </span>
                                                            </div>
                                                        </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">头条标题</label>
                                                                <div class="col-sm-9 col-xs-12">
                                                                    <input type="text" class="form-control" name="tttitle" value="<?php  echo $settings['tttitle'];?>">
                                                                </div>
                                                            </div>	
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">图片</label>
                                                                <div class="col-sm-9">
                                                                    <?php  echo tpl_form_field_image('ttpicurl',$settings['ttpicurl'])?>
                                                                </div>
                                                            </div>
            
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">链接</label>
                                                                <div class="col-sm-9 col-xs-12">
                                                                    <input type="text" class="form-control" name="tturl" value="<?php  echo $settings['tturl'];?>">
                                                                    <span class="help-block"></span>
                                                                </div>
                                                            </div>	
                                                           <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">显示条数</label>
                                                                <div class="col-sm-9 col-xs-12">
                                                                    <input type="text" class="form-control" name="ttsum" value="<?php  echo $settings['ttsum'];?>">
                                                                </div>
                                                            </div>	
                                                            
                                                     </div>
                                                </div>


                                                <!--关注提醒设置-->
                                                <div class="panel panel-default">
                                                   <div class="panel-heading">
                                                      关注提醒设置
                                                   </div>
                                                   <div class="panel-body">
                                                      <!--div class="form-group">
                                                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">自定义粉丝基数</label>
                                                                    <div class="col-sm-9 col-xs-12">
                                                                            <input min="0" type="number" class="form-control" placeholder="" name="tiger_taoke_fansnum" value="<?php  echo $settings['tiger_taoke_fansnum'];?>">
                                                                            <div class="help-block">自定义粉丝基数+现有的微信粉丝数量的总和</div>
                                                                    </div>
                                                        </div-->
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注提示信息</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                <textarea style="height:150px;" name="tiger_taoke_usr" class="form-control" cols="60"><?php  echo $settings['tiger_taoke_usr'];?></textarea>
                                                               
                                                               
                                                                <div class="help-block" style="line-height:30px;">
                                                                <b>参数设置：</b>
                                                                <br>认证订阅号设置：#领取积分#
                                                                <br>链接设置例如:<code>&lt;a href="#领取积分#"&gt;点我领取积分&lt;/a&gt;</code>
                                                                <br>粉丝昵称：#昵称#
                                                                <br>粉丝姓别：#性别#
                                                                <br>粉丝积分：#积分#
                                                                <br>粉丝余额：#余额#
                                                                <br>公众号名称：#公众号名称#
                                                                <!--br>虚拟总粉丝：#假粉丝数# (粉丝基数+微信系统里面的粉丝总数)-->
                                                                <br>国家：#国家#
                                                                <br>省份：#省#
                                                                <br>市/县：#市#
                                                                <br>链接:<code>&lt;a href='http://www.baidu.com'&gt;点击进入&lt;/a&gt;</code></div>
                                                            </div>
                                                        </div>
      
                                                   </div>
                                                </div>

                                                <div class="panel panel-default setting">
                                                    <div class="panel-heading">关注提醒（图文）</div>
                                                    <div class="panel-body">
                                                            
                                                       
                                                            
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">标题</label>
                                                                <div class="col-sm-9 col-xs-12">
                                                                    <input type="text" class="form-control" name="gztitle" value="<?php  echo $settings['gztitle'];?>">
                                                                </div>
                                                            </div>	
                                                             <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">图片</label>
                                                                <div class="col-sm-9">
                                                                    <?php  echo tpl_form_field_image('gzpicurl',$settings['gzpicurl'])?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">概述</label>
                                                                <div class="col-sm-9 col-xs-12">
                                                                    <input type="text" class="form-control" name="gzdescription" value="<?php  echo $settings['gzdescription'];?>">
                                                                </div>
                                                            </div>	
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">链接</label>
                                                                <div class="col-sm-9 col-xs-12">
                                                                    <input type="text" class="form-control" name="gzurl" value="<?php  echo $settings['gzurl'];?>">
                                                                    <span class="help-block">认证订阅号链接设置：#领取积分# </span>
                                                                </div>
                                                            </div>	
                                                            
                                                     </div>
                                                     </div>
                                                <!---->
                                                <div class="form-group col-sm-12">
                                                    <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                                                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                                </div>
                                            </div>
                                            <!--兑吧开始-->
                                            <div class="tab-pane" id="tab_param2">
                                               <div class="form-group">
                                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">AppKey：</label>
                                                    <div class="col-sm-9 col-xs-12">
                                                            <input min="0" type="text" class="form-control" placeholder="" name="AppKey" value="<?php  echo $settings['AppKey'];?>">
                                                            <div class="help-block">注册兑吧：http://www.duiba.com.cn/  登录>添加应用->添加好后进入应用->基本配置->应用信息获取</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">appSecret：</label>
                                                    <div class="col-sm-9 col-xs-12">
                                                            <input min="0" type="text" class="form-control" placeholder="" name="appSecret" value="<?php  echo $settings['appSecret'];?>">
                                                            <div class="help-block">仔细填写，不要有空格</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">接口配置：</label>
                                                    <div class="col-sm-9 col-xs-12" style="line-height:30px;">
                                                          积分消费：<code><?php  echo $_W['siteroot'].str_replace('./','app/',$this->createMobileurl('duibaxf'))?></code><br>
                                                          结果通知：<code><?php  echo $_W['siteroot'].str_replace('./','app/',$this->createMobileurl('duibatz'))?></code><br>
                                                          直达页面接口：<code><?php  echo $_W['siteroot'].str_replace('./','app/',$this->createMobileurl('duibagoods'))?></code><br>
                                                    </div>
                                                </div>
                                             
                                            </div>
                                            <!---->
                                            
                                            <!--兑吧开始-->
                                            <div class="tab-pane" id="tab_param3">
                                               <div class="form-group">
                                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">生成海报限制时间：</label>
                                                    <div class="col-sm-9 col-xs-12">
                                                            <input min="0" type="text" class="form-control" placeholder="请填数字 60" name="hbsctime" value="<?php  echo $settings['hbsctime'];?>">
                                                            <div class="help-block">几分钟生成1次海报，比如:1分钟只有生成1次海报，就填 60 ，秒为单位</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">不能生成海报提示：</label>
                                                    <div class="col-sm-9 col-xs-12">
                                                            <input min="0" type="text" class="form-control" placeholder="" name="hbcsmsg" value="<?php  echo $settings['hbcsmsg'];?>">
                                                            <div class="help-block">如：尊敬的粉丝：\n1分钟之内只能生成1次海报\n请稍后在试，感谢您的参与！</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!---->

                                            <!--短信验证-->
                                            <div class="tab-pane" id="tab_param4">
                                               <!---->
                                                  <div class="panel panel-default">
                                                    <div class="panel-heading">短信平台参数设置</div>
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启短信验证</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <label class="radio-inline"><input type="radio" name="dxtype" value="0" 
                                                                 <?php  if($settings['dxtype']==0) { ?> checked="checked" <?php  } ?>>否</label>
                                                                <label class="radio-inline"><input type="radio" name="dxtype" value="1"
                                                                 <?php  if($settings['dxtype']==1) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                
                                                                  <span class="help-block">开启短信验证，粉丝在兑换的时候，需要先通过短信验证才可以，一个粉丝对应一个手机号码，只能验证一次，不能修改，不能重复</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">短信接口<?php  echo $settings['smstype'];?></label>
                                                            <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                <label><input type="radio" name="smstype" value="dayu" <?php  if($settings['smstype'] == 'dayu' || empty($settings['smstype'])) { ?>checked<?php  } ?> onclick="$('#juhesj').hide();$('#dayu').show();"/> 阿里大鱼</label>
                                                                <label><input type="radio" name="smstype" value="juhesj" <?php  if($settings['smstype'] == 'juhesj') { ?>checked<?php  } ?>/ onclick="$('#juhesj').show();$('#dayu').hide();"> 聚合数据</label>
                                                            </div>
                                                        </div>
                                                        <div id="dayu" <?php  if($settings['smstype'] != 'dayu' || empty($settings['smstype'])) { ?>style="display: none;"<?php  } ?>>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大鱼AppKey</label>
                                                            <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                <input type="text" value="<?php  echo $settings['dyAppKey'];?>" name="dyAppKey" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大鱼AppSecret</label>
                                                            <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                <input type="text" value="<?php  echo $settings['dyAppSecret'];?>" name="dyAppSecret" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大鱼短信签名</label>
                                                            <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                <input type="text" value="<?php  echo $settings['dysms_free_sign_name'];?>" name="dysms_free_sign_name" class="form-control" />
                                                                <span class="help-block">短信签名，传入的短信签名必须是在阿里大鱼“管理中心-短信签名管理”中的可用签名。如“阿里大鱼”已在短信签名管理中通过审核，则可传入”阿里大鱼“（传参时去掉引号）作为短信签名。短信效果示例：【阿里大鱼】欢迎使用阿里大鱼服务。</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">大鱼短信模板id</label>
                                                            <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                <input type="text" value="<?php  echo $settings['dysms_template_code'];?>" name="dysms_template_code" class="form-control" />
                                                                <span class="help-block">短信模板ID，传入的模板必须是在阿里大鱼“管理中心-短信模板管理”中的可用模板。示例：SMS_585014<br />模板格式：<b>验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！</b></span>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <div id="juhesj" <?php  if($settings['smstype'] != 'juhesj') { ?>style="display: none;"<?php  } ?>>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">聚合AppKey</label>
                                                            <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                <input type="text" value="<?php  echo $settings['jhappkey'];?>" name="jhappkey" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">聚合模版ID</label>
                                                            <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                <input type="text" value="<?php  echo $settings['jhcode'];?>" name="jhcode" class="form-control" />
                                                                <span class="help-block">短信模板ID，传入的模板必须是在聚合数据“管理中心-短信API服务->模板”中添加<br />模板格式：<b>【<?php  echo $_W['account']['name'];?>】您的注册短信验证码是#code#。如非本人操作，请忽略本短信</b></span>
                                                            </div>
                                                        </div>
                           
                                                        </div>
                                                    </div>
                                                </div>
                                              <!---->
                                              <div class="form-group col-sm-12">
                                                    <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                                                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                                </div>
                                            </div>
                                            <!---->


                                            <!--手机端模版-->
                                            <div class="tab-pane" id="tab_param5">
                                               <!---->
                                                  <div class="panel panel-default">
                                                    <div class="panel-heading">模版选择</div>
                                                        <div class="panel-body">
                                                           <table class="table table-hover" style="text-align:center">
                                                              <tbody id="table_content">
                                                                <tr>
                                                                  <td><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/images/style1.jpg"></td>
                                                                  <td><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/images/style2.jpg"></td>
                                                                  <td><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/images/style3.jpg"></td>
                                                                  <td><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/images/style4.jpg"></td>
                                                                  <td><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/images/style5.jpg"></td>
                                                                </tr> 
                                                                <tr>
                                                                  <td><input type="radio" name="qtstyle" id="qtstyle" value="style1" <?php  if($settings['qtstyle'] == 'style1') { ?>checked<?php  } ?>> 模版1</td>
                                                                  <td><input type="radio" name="qtstyle" id="qtstyle" value="style2" <?php  if($settings['qtstyle'] == 'style2') { ?>checked<?php  } ?>> 模版2</td>
                                                                  <td><input type="radio" name="qtstyle" id="qtstyle" value="style3" <?php  if($settings['qtstyle'] == 'style3') { ?>checked<?php  } ?>> 模版3</td>
                                                                  <td><input type="radio" name="qtstyle" id="qtstyle" value="style4" <?php  if($settings['qtstyle'] == 'style4') { ?>checked<?php  } ?>> 模版4</td>
                                                                  <td><input type="radio" name="qtstyle" id="qtstyle" value="style5" <?php  if($settings['qtstyle'] == 'style5') { ?>checked<?php  } ?>> 模版5</td>
                                                                </tr> 
                                                                <tr>
                                                                  <td><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/images/style6.jpg"></td>
                                                                  <td><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/images/style7.jpg"></td>
                                                                  <td><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/images/style8.jpg"></td>
                                                                  <td><img src="<?php  echo $_W['siteroot'];?>addons/tiger_taoke/template/mobile/tbgoods/images/style9.jpg"></td>
                                                                  <td></td>
                                                                </tr> 
                                                                <tr>
                                                                  <td><input type="radio" name="qtstyle" id="qtstyle" value="style6" <?php  if($settings['qtstyle'] == 'style6') { ?>checked<?php  } ?>> 模版6</td>
                                                                  <td><input type="radio" name="qtstyle" id="qtstyle" value="style7" <?php  if($settings['qtstyle'] == 'style7') { ?>checked<?php  } ?>> 模版7</td>
                                                                  <td><input type="radio" name="qtstyle" id="qtstyle" value="style8" <?php  if($settings['qtstyle'] == 'style8') { ?>checked<?php  } ?>> 模版8</td>
                                                                  <td><input type="radio" name="qtstyle" id="qtstyle" value="style9" <?php  if($settings['qtstyle'] == 'style9') { ?>checked<?php  } ?>> 模版9</td>
                                                                  <td></td>
                                                                </tr> 
                                                              </tbody>
                                                            </table>
                                                            <div class="form-group col-sm-12">
                                                                <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                                                                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                                            </div>
                           
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                              <!---->

                                           <!--API设置-->
                                            <div class="tab-pane" id="tab_param6">
                                               <!---->
                                                  <div class="panel panel-default">
                                                    <div class="panel-heading">API设置</div>
                                                        <div class="panel-body">
                                                           <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">软件自定义密钥</label>
                                                                <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['miyao'];?>" name="miyao" class="form-control" />
                                                                    <span class="help-block">自己随便写，字母加数字，不要有特服号（设置好，把这个密钥填到软件上就可以了）</span>
                                                                </div>
                                                            </div>
                                                           <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">大淘客APPKEY</label>
                                                                <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['dtkAppKey'];?>" name="dtkAppKey" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">淘客APPKEY</label>
                                                                <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['tkAppKey'];?>" name="tkAppKey" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">淘客secretKey</label>
                                                                <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['tksecretKey'];?>" name="tksecretKey" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">普通PID</label>
                                                                <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['ptpid'];?>" name="ptpid" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">鹊桥高佣PID</label>
                                                                <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['qqpid'];?>" name="qqpid" class="form-control" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-sm-12">
                                                                <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                                                                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                              <!---->
                                            </div>
                                            <!---->

                                            <!--直播设置-->
                                            <div class="tab-pane" id="tab_param8">
                                                <!--地区设置-->
                                                <div class="panel panel-default">
                                                   <div class="panel-heading">
                                                      直播设置
                                                   </div>
                                                   <div class="panel-body">
                                                      <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">wsurl</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="wsurl" value="<?php  echo $settings['wsurl'];?>" class="form-control" placeholder="" >
                                                                <span class="help-block">如：ws://<?php  echo $_SERVER["SERVER_ADDR"]?>:9502 (左边直接复制上去就可以了)</span>
                                                            </div>
                                                       </div>
                                                      <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">产品发送间隔时间</label>
                                                            <div class="col-xs-12 col-sm-9">
                                                                <input type="text" name="zbjgtime" value="<?php  echo $settings['zbjgtime'];?>" class="form-control" placeholder="秒为单位如：120" >
                                                                <span class="help-block"></span>
                                                            </div>
                                                       </div>
                                                       <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">机器人头像</label>
                                                            <div class="col-sm-9">
                                                                <?php  echo tpl_form_field_image('zbtouxiang',$settings['zbtouxiang'])?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                                <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                                                                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                                            </div>
                                                   </div>
                                                </div>
                                                
                                                <!---->
                                            </div>
                                            <!--直播结束-->

                                            <!--订单返现设置-->
                                            <div class="tab-pane" id="tab_param7">
                                               <!---->
                                                  <div class="panel panel-default">
                                                    <div class="panel-heading">云控授权设置</div>
                                                        <div class="panel-body">
                                                               <div class="form-group">
                                                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="color:#ff0000">云控设置</label>
                                                                    <div class="col-xs-12 col-sm-9">
                                                                        <label class="radio-inline"><input type="radio" name="yktype" value="0" 
                                                                         <?php  if($settings['yktype']==0) { ?> checked="checked" <?php  } ?>>不开启</label>
                                                                        <label class="radio-inline"><input type="radio" name="yktype" value="1"
                                                                         <?php  if($settings['yktype']==1) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                          <span class="help-block"></span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">淘宝ID</label>
                                                                        <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                            <input type="text" value="<?php  echo $settings['tbuid'];?>" name="tbuid" class="form-control" />
                                                                            <span class="help-block" style="color:#ff0000">阿里妈妈登录的对应淘宝ID 【特别注意：淘宝ID填好了先保存，在点下面的登录】</span>
                                                                        </div>
                                                                </div>
                                                                <div class="form-group">
                                                                          <div class="col-xs-12 col-sm-12">
                                                                            <?php  if($tksign['sign']<>'') { ?>
                                                                            <a href="https://oauth.taobao.com/authorize?response_type=code&client_id=23533320&redirect_uri=http://cs.youqi18.com/addons/tiger_shouquan/tkapi.php?i=3&state=<?php  echo $_W['siteroot'];?>|<?php  echo $_W['uniacid'];?>&view=web" class="btn btn-success" style="width:100%">登录成功，点击重新登录</a>
                                                                            <?php  } else { ?>
                                                                            <a href="https://oauth.taobao.com/authorize?response_type=code&client_id=23533320&redirect_uri=http://cs.youqi18.com/addons/tiger_shouquan/tkapi.php?i=3&state=<?php  echo $_W['siteroot'];?>|<?php  echo $_W['uniacid'];?>&view=web" class="btn btn-danger" style="width:100%">登录授权</a>
                                                                            <?php  } ?>  
                                                                            <span class="help-block" style="color:#ff0000"><?php  if($tksign['sign']) { ?>在线时间到：<?php  echo date('Y-m-d H:i:s',$tksign['createtime']+60*60*24*26)?>，时间到后请重新授权登录<?php  } ?></span>
                                                                           </div>
                                                                           
                                                                </div>
                                                        </div>
                                                    </div>


                                                    <!--返开--->
                                                    <div class="panel panel-default">
                                                    <div class="panel-heading">订单返现设置</div>
                                                        <div class="panel-body">
                                                        <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">佣金结算天数</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['yongjinjs'];?>" name="yongjinjs" class="form-control" />
                                                                    <span class="help-block">佣金结算天数：阿里妈妈订单结算时间+这里设置的天数，时间到期自动结算到粉丝的余额</span>
                                                                </div>
                                                        </div>

                                                           <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="color:#ff0000">公众号查券图片</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="gzhtp" value="0" 
                                                                     <?php  if($settings['gzhtp']==0) { ?> checked="checked" <?php  } ?>>不显示</label>
                                                                    <label class="radio-inline"><input type="radio" name="gzhtp" value="1"
                                                                     <?php  if($settings['gzhtp']==1) { ?> checked="checked" <?php  } ?>>显示</label>
                                                                      <span class="help-block">开启后查券会显示图片</span>
                                                                </div>
                                                            </div>
                                                           
                                                           <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="color:#ff0000">公众号返利</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="gzhfl" value="0" 
                                                                     <?php  if($settings['gzhfl']==0) { ?> checked="checked" <?php  } ?>>不开</label>
                                                                    <label class="radio-inline"><input type="radio" name="gzhfl" value="1"
                                                                     <?php  if($settings['gzhfl']==1) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                      <span class="help-block">开启，粉丝在公众号发送一个淘宝地址就可以把相对应的优惠信息推送给粉丝</span>
                                                                </div>
                                                            </div>
                                                            <!--div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="color:#ff0000">公众号/机器人查优惠券</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="tkzs" value="0" 
                                                                     <?php  if($settings['tkzs']==0) { ?> checked="checked" <?php  } ?>>不开</label>
                                                                    <label class="radio-inline"><input type="radio" name="tkzs" value="1"
                                                                     <?php  if($settings['tkzs']==1) { ?> checked="checked" <?php  } ?>>开启【查淘客推荐优先】淘客助手查优惠券</label>
                                                                     <label class="radio-inline"><input type="radio" name="tkzs" value="2"
                                                                     <?php  if($settings['tkzs']==2) { ?> checked="checked" <?php  } ?>>开启【只使用查淘客的，如果查不到，就不使用优惠券】</label>
                                                                     <label class="radio-inline"><input type="radio" name="tkzs" value="3"
                                                                     <?php  if($settings['tkzs']==3) { ?> checked="checked" <?php  } ?>>开启【轻淘客】</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                            </div-->
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">部分用户不能查券提醒</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <textarea style="height:50px;" name="cqmsg" class="form-control" cols="60"><?php  echo $settings['cqmsg'];?></textarea>
                                                                    <span class="help-block">如：查券功能已关闭！</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">公众号查询错误提示1</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['error1'];?>" name="error1" class="form-control" />
                                                                    <span class="help-block">不填默认：系统繁忙！请稍后在试！</span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">公众号查询错误提示2</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['error2'];?>" name="error2" class="form-control" />
                                                                    <span class="help-block">不填默认：该商品暂无优惠,请查看其他商品</span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">公众号查询错误提示3</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['error3'];?>" name="error3" class="form-control" />
                                                                    <span class="help-block">不填默认：抱歉，暂时无法处理您的请求！</span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">公众号查询错误提示4</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['error4'];?>" name="error4" class="form-control" />
                                                                    <span class="help-block">不填默认：链接查询失败。超时，请联系管理员！</span>
                                                                </div>
                                                            </div>





                                                            <!--div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="color:#ff0000">网站查券</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="wzcj" value="0" 
                                                                     <?php  if($settings['wzcj']==0) { ?> checked="checked" <?php  } ?>>不开</label>
                                                                    <label class="radio-inline"><input type="radio" name="wzcj" value="1"
                                                                     <?php  if($settings['wzcj']==1) { ?> checked="checked" <?php  } ?>>开启【查淘客推荐优先】淘客助手查优惠券</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="color:#ff0000">网站优惠券列表</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="yhlist" value="0" 
                                                                     <?php  if($settings['yhlist']==0) { ?> checked="checked" <?php  } ?>>不开</label>
                                                                    <label class="radio-inline"><input type="radio" name="yhlist" value="1"
                                                                     <?php  if($settings['yhlist']==1) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                            </div-->
                                                            <input type="hidden" name="wzcj" value="0">
                                                            <input type="hidden" name="yhlist" value="0">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">adzoneid</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['adzoneid'];?>" name="adzoneid" class="form-control" />
                                                                    <span class="help-block">对应数值 ：mm_memberid_siteid_adzoneid （使用导购推广位默认第一个）</span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">siteid</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['siteid'];?>" name="siteid" class="form-control" />
                                                                    <span class="help-block">对应数值 ：mm_memberid_siteid_adzoneid （使用导购推广位默认第一个）</span>
                                                                </div>
                                                            </div>


                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">提示信息</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                <textarea style="height:150px;" name="flmsg" class="form-control" cols="60"><?php  echo $settings['flmsg'];?></textarea>                  
                                                                <div class="help-block" style="line-height:30px;">
                                                                <b>参数设置：</b>
                                                                <!--br>链接设置例如:<code>&lt;a href="#领取积分#"&gt;点我领取积分&lt;/a&gt;</code-->
                                                                <br>昵称：#昵称#
                                                                <br>商品名称：#名称#
                                                                <br>商品原价：#原价#
                                                                <br>券后价：#券后价#
                                                                <br>总优惠后价：#惠后价#
                                                                <br>总优惠金额：#总优惠#
                                                                <br>优惠券：#优惠券# (优惠券如果没有提示，暂无优惠券)
                                                                <br>好评后优惠约：#返现金额#
                                                                <br>淘口令：#淘口令#
                                                                <br>购买地址：#短网址#

                                                              </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">机器人回复</label>
                                                                    <div class="col-xs-12 col-sm-9">
                                                                       <label class="checkbox-inline">
                                                                          <input type="radio" name="jqrss" id="optionsRadios4" value="0" <?php  if($settings['jqrss'] == 0) { ?>checked<?php  } ?>> 使用找关键词搜索
                                                                       </label>
                                                                       <label class="checkbox-inline">
                                                                          <input type="radio" name="jqrss" id="optionsRadios4" value="1" <?php  if($settings['jqrss'] == 1) { ?>checked<?php  } ?>> 链接
                                                                       </label>
                                                                        <span class="help-block" style="color:#ff0000">用找的，粉丝在群里面发 找女装  这样查找</span>
                                                                    </div>
                                                         </div>

                                                        <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">新提示信息(公众号)</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                <textarea style="height:150px;" name="newflmsg" class="form-control" cols="60"><?php  echo $settings['newflmsg'];?></textarea>                  
                                                                <div class="help-block" style="line-height:30px;">
                                                                <b>参数设置：</b>
                                                                <br>昵称：#昵称#你好
                                                                <br>你要找【#名称#】吗？                                                               
                                                                <br>点击查看更多相关内容：#短网址#
                                                              </div>
                                                            </div>
                                                        </div>

                                                         <div class="form-group">
                                                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">个人微信(微信群)机器人设置</label>
                                                            <div class="col-sm-9 col-xs-12">
                                                                <textarea style="height:150px;" name="jqrflmsg" class="form-control" cols="60"><?php  echo $settings['jqrflmsg'];?></textarea>                                                                                                                               
                                                                <div class="help-block" style="line-height:30px;">
                                                                <b>参数设置：</b>
                                                                <br>HI，#昵称# 
                                                               <br>【商品名称】

                                                                <br>商品名称#名称# 
                                                                <br>原价：#原价# 元
                                                                <br>总优惠后价：#惠后价# 元
                                                                <br>券后价：#券后价#
                                                                <!--<br>购买地址：#短网址#-->

                                                                <br>【优惠详情】
                                                                <br>优惠券：#优惠券#
                                                                <br>总优惠金额：#总优惠# 元
                                                                <br>好评后奖励：#返现金额#元
                                                                <br>[tp1]#图片#[tp2]
                                                                <br>【下单】
                                                                <br>长按复制本条消息，打开【淘宝客户端】下单#淘口令#
                                                              </div>
                                                            </div>
                                                        </div>

                                                          <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">发送链接错误提示</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <textarea style="height:50px;" name="ermsg" class="form-control" cols="60"><?php  echo $settings['ermsg'];?></textarea>
                                                                    <span class="help-block">粉丝发送商品链接错误提示。如：亲!您发送的链接有误哦！请参看这个链接 <Br><?php  echo $_W['siteroot'].str_replace('./','app/',$this->createMobileurl('newhelp'))?></span>
                                                                </div>
                                                            </div>


                                                           <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">佣金提现设置</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['yjtype'];?>" name="yjtype" class="form-control" />
                                                                    <span class="help-block">满多少才能提现</span>
                                                                </div>
                                                            </div>
                                                            
                                                           <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启返现功能</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="fxkg" value="0" 
                                                                     <?php  if($settings['fxkg']==0) { ?> checked="checked" <?php  } ?>>不开</label>
                                                                    <label class="radio-inline"><input type="radio" name="fxkg" value="1"
                                                                     <?php  if($settings['fxkg']==1) { ?> checked="checked" <?php  } ?>>开启</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                            </div>
                                                           <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">返现类型选择</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <label class="radio-inline"><input type="radio" name="fxtype" value="0" 
                                                                     <?php  if($settings['fxtype']==0) { ?> checked="checked" <?php  } ?>>不返</label>
                                                                    <label class="radio-inline"><input type="radio" name="fxtype" value="1"
                                                                     <?php  if($settings['fxtype']==1) { ?> checked="checked" <?php  } ?>>返积分</label>
                                                                     <label class="radio-inline"><input type="radio" name="fxtype" value="2"
                                                                     <?php  if($settings['fxtype']==2) { ?> checked="checked" <?php  } ?>>返余额</label>
                                                                      <span class="help-block"></span>
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">积分比例设置</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <input type="text" value="<?php  echo $settings['jfbl'];?>" name="jfbl" class="form-control" />
                                                                    <span class="help-block">1元等于多少积分</span>
                                                                </div>
                                                            </div>




                                                           <div class="input-group" >
                                                              <div class="input-group-addon">自购返</div>
                                                              <input type="number" name="zgf" value="<?php  echo $settings['zgf'];?>" class="form-control" placeholder="填写数字" >
                                                              <span class="input-group-addon">%  积分/余额</span>
                                                           </div>
                                                           <div class="input-group" >
                                                              <div class="input-group-addon">一级返</div>
                                                              <input type="number" name="yjf" value="<?php  echo $settings['yjf'];?>" class="form-control" placeholder="填写数字" >
                                                              <span class="input-group-addon">% 积分/余额</span>
                                                           </div>
                                                           <div class="input-group" >
                                                              <div class="input-group-addon">二级返</div>
                                                              <input type="number" name="ejf" value="<?php  echo $settings['ejf'];?>" class="form-control" placeholder="填写数字" >
                                                              <span class="input-group-addon">% 积分/余额</span>
                                                           </div>
                                                           <span class="help-block">计算方式：返现积分或余额=订单佣金*自购返%(取整)（选择积分类型百分比设置高一点，以免出现小数，不计入奖励（如：0.4积分奖不增加积分，1.6积分增加1积分））</span>
                                                        </div>

                                                           <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">自购提交订单提醒</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <textarea style="height:50px;" name="zgtxmsg" class="form-control" cols="60"><?php  echo $settings['zgtxmsg'];?></textarea>
                                                                    <span class="help-block">#昵称#  如：#昵称#，订单提交成功，客服会在24小时内审核订单，请耐性等待。订单号：#订单号#</span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">一级提醒</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <textarea style="height:50px;" name="yjtxmsg" class="form-control" cols="60"><?php  echo $settings['yjtxmsg'];?></textarea>
                                                                    <span class="help-block">#昵称#  如：您的朋友#昵称#成功下单了，订单确认收货后，您奖获得相应的奖励。订单号：#订单号#</span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">二级提醒</label>
                                                                <div class="col-sm-6 col-xs-12" style="margin-top: 6px;">
                                                                    <textarea style="height:50px;" name="ejtxmsg" class="form-control" cols="60"><?php  echo $settings['ejtxmsg'];?></textarea>
                                                                    <span class="help-block">#昵称# 如：您的朋友的朋友#昵称#成功下单了，订单确认收货后，您奖获得相应的奖励。订单号：#订单号#</span>
                                                                </div>
                                                            </div>





                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                                                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                                    </div>
                                              <!---->
                                            </div>
                                                    <!--返结束-->
                                            </div>
                                          <!--返现设置结束-->

                                            
                                              
                                            </div>
                                        

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                </div>
                            </form>
                        </div>
                        <script>
                        require(['jquery', 'util'], function($, u){
                            $(function(){
                                u.editor($('.richtext')[0]);
                            });
                        });
                        $(function () {
                                window.optionchanged = false;
                                $('#myTab a').click(function (e) {
                                    e.preventDefault();//阻止a链接的跳转行为
                                    $(this).tab('show');//显示当前选中的链接及关联的content
                                })
                            });
                        </script>
                        
                        <!--编辑内容结束-->
			        </section>
			        <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public_bottom', TEMPLATE_INCLUDEPATH)) : (include template('public_bottom', TEMPLATE_INCLUDEPATH));?>
			    </section>
			    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
			  </section>
			  <aside class="bg-light lter b-l aside-md hide" id="notes">
			       <div class="wrapper">不知道放什么</div>
			  </aside>
			<!--右边框架结束-->
			</section>
		  </section>
		<!--中间内容结束-->
	</section>


</body>
</html>