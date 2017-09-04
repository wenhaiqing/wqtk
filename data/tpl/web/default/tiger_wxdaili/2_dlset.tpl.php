<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link href="<?php  echo $_W['siteroot'];?>/addons/tiger_jifenbao/js/bootstrap.file-input.css" rel="stylesheet">
<script type="text/javascript" src="<?php  echo $_W['siteroot'];?>/addons/tiger_jifenbao/js/bootstrap.file-input.js"></script>
<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php  echo $set['id'];?>">
<!--基础设置-->
<div class="panel panel-default">
       <div class="panel-heading">
          代理付费设置
       </div>
       <div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">超级搜索</label>
                <div class="col-xs-12 col-sm-9">
                   <label class="checkbox-inline">
                      <input type="radio" name="seartype" id="seartype" value="0" <?php  if($set['seartype'] ==0) { ?>checked<?php  } ?>>菜单显示
                   </label>
                   <label class="checkbox-inline">
                      <input type="radio" name="seartype" id="seartype" value="1" <?php  if($set['seartype'] == 1) { ?>checked<?php  } ?>>不显示
                   </label>
                    <span class="help-block" >不显示，底部菜单超级搜索不显示</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">分站名称自定义</label>
                <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                    <div class="input-group">
                       <input type="text" name="fzname" value="<?php  echo $set['fzname'];?>" class="form-control" placeholder="如：小店">
                    </div>
                </div>
            </div>
              

              <div class="form-group">
                    <div class="col-sm-10">
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" /> <input
                            name="submit" value="submit" type="hidden" />
                        <hr />
                        <button class="btn btn-primary" type="submit">提交</button>
                    </div>
             </div>


        </div>
    </div>
<!---->
<div class="panel panel-default">
   <div class="panel-heading">
      代理佣金设置
   </div>
   <div class="panel-body">
   <style>
   .yjtable{width:100%}
   .yjtable th{padding:10px;}
   .yjtable td{padding:10px;border-right:1px #cecece solid}
   </style>

  <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">更新数据</label>
        <div class="col-xs-12 col-sm-9">
           <a class="btn btn-primary" href="<?php  echo $this->createWebUrl('gxshare', array('op' => 'gx'))?>"/></i>更新数据</a>
            <span class="help-block">如果佣金显示不正确，可以点一下更新数据</span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">代理提交订单返现</label>
        <div class="col-xs-12 col-sm-9">
           <label class="checkbox-inline">
              <input type="radio" name="dlfxtype" id="dlfxtype" value="0" <?php  if($set['dlfxtype'] ==0) { ?>checked<?php  } ?>>不开启
           </label>
           <label class="checkbox-inline">
              <input type="radio" name="dlfxtype" id="dlfxtype" value="1" <?php  if($set['dlfxtype'] == 1) { ?>checked<?php  } ?>>开启
           </label>
            <span class="help-block" >开启，代理在会员中心，提交不了订单返现</span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">代理的订单提交二三级返现</label>
        <div class="col-xs-12 col-sm-9">
           <label class="checkbox-inline">
              <input type="radio" name="dlyjfltype" id="dlyjfltype" value="0" <?php  if($set['dlyjfltype'] ==0) { ?>checked<?php  } ?>>不开启
           </label>
           <label class="checkbox-inline">
              <input type="radio" name="dlyjfltype" id="dlyjfltype" value="1" <?php  if($set['dlyjfltype'] == 1) { ?>checked<?php  } ?>>开启
           </label>
            <span class="help-block" >不开启，代理的订单，在会员中心提交订单，二，三级不返现</span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">代理中心直播菜单</label>
        <div class="col-xs-12 col-sm-9">
           <label class="checkbox-inline">
              <input type="radio" name="dlzbtype" id="dlzbtype" value="0" <?php  if($set['dlzbtype'] ==0) { ?>checked<?php  } ?>>不显示
           </label>
           <label class="checkbox-inline">
              <input type="radio" name="dlzbtype" id="dlzbtype" value="1" <?php  if($set['dlzbtype'] == 1) { ?>checked<?php  } ?>>显示
           </label>
            <span class="help-block" ></span>
        </div>
    </div>
   

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">订单号显示</label>
        <div class="col-xs-12 col-sm-9">
           <label class="checkbox-inline">
              <input type="radio" name="ddtype" id="ddtype" value="0" <?php  if($set['ddtype'] ==0) { ?>checked<?php  } ?>>全显示
           </label>
           <label class="checkbox-inline">
              <input type="radio" name="ddtype" id="ddtype" value="1" <?php  if($set['ddtype'] == 1) { ?>checked<?php  } ?>>显示一部分
           </label>
            <span class="help-block" >效果是在我的订单列表页面显示的订单号</span>
        </div>
    </div>

   <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">代理级别设置</label>
        <div class="col-xs-12 col-sm-9">
           <label class="checkbox-inline">
              <input type="radio" name="dltype" id="dltype" value="1" <?php  if($set['dltype'] == 1) { ?>checked<?php  } ?>> 一级
           </label>
           <label class="checkbox-inline">
              <input type="radio" name="dltype" id="dltype" value="2" <?php  if($set['dltype'] == 2) { ?>checked<?php  } ?>> 二级
           </label>
           <label class="checkbox-inline">
              <input type="radio" name="dltype" id="dltype" value="3" <?php  if($set['dltype'] == 3) { ?>checked<?php  } ?>> 三级
           </label>
            <span class="help-block">等级显示设置（选择好后不要去随便修改，会影响佣金计算）</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">分销模式</label>
        <div class="col-xs-12 col-sm-9">
           <label class="checkbox-inline">
              <input type="radio" name="fxtype" id="fxtype" value="0" <?php  if($set['fxtype'] == 0) { ?>checked<?php  } ?>> 抽成模式
           </label>
           <label class="checkbox-inline">
              <input type="radio" name="fxtype" id="fxtype" value="1" <?php  if($set['fxtype'] == 1) { ?>checked<?php  } ?>> 普通大众模式
           </label>
            <span class="help-block">模式不一样，佣金结算不一样，选择好了不要改变，不然会影响佣金结算</span>
        </div>
    </div>

    <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">代理佣金扣除</label>
                <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                    <div class="input-group">
                       <input type="text" name="dlkcbl" value="<?php  echo $set['dlkcbl'];?>" class="form-control" placeholder="填写数字">
                       <span class="input-group-addon">%</span>
                    </div>
                    <span class="help-block">扣除总佣金的多少，在计算结算佣金</span>
                </div>
            </div>
      
     <table class="yjtable">
            <thead>
                <tr>
                    <th>一级代理比率设置</th>
                    <th>二级代理比率设置</th>
                    <th>三级代理比率设置</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="input-group">
                          <div class="input-group-addon" style="color:#ff0000">一级名称</div>
                          <input type="text" name="dlname1" value="<?php  echo $set['dlname1'];?>" class="form-control" placeholder="">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                          <div class="input-group-addon" style="color:#ff0000">二级名称</div>
                          <input type="text" name="dlname2" value="<?php  echo $set['dlname2'];?>" class="form-control" placeholder="">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                          <div class="input-group-addon" style="color:#ff0000">三级名称</div>
                          <input type="text" name="dlname3" value="<?php  echo $set['dlname3'];?>" class="form-control" placeholder="">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                          <div class="input-group-addon">自己产生佣金比率<span style="color:#ff0000"> (一级)</span></div>
                          <input type="number" name="dlbl1" value="<?php  echo $set['dlbl1'];?>" class="form-control" placeholder="填写数字">
                          <span class="input-group-addon">%</span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                          <div class="input-group-addon">自己产生佣金比率<span style="color:#ff0000"> (二级)</span></div>
                          <input type="number" name="dlbl2" value="<?php  echo $set['dlbl2'];?>" class="form-control" placeholder="填写数字">
                          <span class="input-group-addon">%</span>
                        </div>
                    </td>
                    <td style="border-right:0">
                        <div class="input-group">
                          <div class="input-group-addon">自己产生佣金比率<span style="color:#ff0000"> (三级)</span></div>
                          <input type="number" name="dlbl3" value="<?php  echo $set['dlbl3'];?>" class="form-control" placeholder="填写数字">
                          <span class="input-group-addon">%</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                          <div class="input-group-addon">提取二级佣金比率</div>
                          <input type="number" name="dlbl1t2" value="<?php  echo $set['dlbl1t2'];?>" class="form-control" placeholder="填写数字">
                          <span class="input-group-addon">%</span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                          <div class="input-group-addon">提取三级佣金比率</div>
                          <input type="number" name="dlbl2t3" value="<?php  echo $set['dlbl2t3'];?>" class="form-control" placeholder="填写数字">
                          <span class="input-group-addon">%</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                          <div class="input-group-addon">提取三级佣金比率</div>
                          <input type="number" name="dlbl1t3" value="<?php  echo $set['dlbl1t3'];?>" class="form-control" placeholder="填写数字">
                          <span class="input-group-addon">%</span>
                        </div>
                    </td>
                </tr>
            </tbody>
      </table>

      <div class="form-group">
                    <div class="col-sm-10">
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" /> <input
                            name="submit" value="submit" type="hidden" />
                        <hr />
                        <button class="btn btn-primary" type="submit">提交</button>
                    </div>
             </div>

     

    </div>
</div>

   <div class="panel panel-default">
       <div class="panel-heading">
          代理付费设置
       </div>
       <div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">支付模式</label>
                <div class="col-xs-12 col-sm-9">
                   <label class="checkbox-inline">
                      <input type="radio" name="dlfftype" id="dlfftype" value="0" <?php  if($set['dlfftype'] ==0) { ?>checked<?php  } ?>>不开启
                   </label>
                   <label class="checkbox-inline">
                      <input type="radio" name="dlfftype" id="dlfftype" value="1" <?php  if($set['dlfftype'] == 1) { ?>checked<?php  } ?>> 开启
                   </label>
                    <span class="help-block" >开启支付模式需要开通微信支付（功能选项--支付--微信支付开启，在到参数设置里面设置）</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">代理支付金额</label>
                <div class="col-sm-9 col-xs-12" style="margin-top: 6px;">
                    <div class="input-group">
                       <input type="text" name="dlffprice" value="<?php  echo $set['dlffprice'];?>" class="form-control" placeholder="填写数字">
                       <span class="input-group-addon">元</span>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" >一级佣金：</label>
                <div class="input-group" style="width:250px;">
                  <input type="text" class="form-control" name="level1" value="<?php  echo $set['level1'];?>"  placeholder="如：20">
                  <span class="input-group-addon">%</span>
                  <input type="text" class="form-control" name="glevel1" value="<?php  echo $set['glevel1'];?>"  placeholder="如：20">
                  <span class="input-group-addon">元</span>
                  
                </div> 
                
              </div>
              <div class="form-group">
                <label for="" class="col-sm-2 control-label" >二级佣金：</label>
                <div class="input-group" style="width:250px;">
                  <input type="text" class="form-control" name="level2" value="<?php  echo $set['level2'];?>"  placeholder="如：10">
                  <span class="input-group-addon">%</span>
                  <input type="text" class="form-control" name="glevel2" value="<?php  echo $set['glevel2'];?>"  placeholder="如：10">
                  <span class="input-group-addon">元</span>
                  
                </div>    
              </div>
              <div class="form-group">
                <label for="" class="col-sm-2 control-label" >三级佣金：</label>
                <div class="input-group" style="width:250px;">
                  <input type="text" class="form-control" name="level3"  value="<?php  echo $set['level3'];?>"  placeholder="如：5">
                  <span class="input-group-addon">%</span>
                  <input type="text" class="form-control" name="glevel3"  value="<?php  echo $set['glevel3'];?>"  placeholder="如：5">
                  <span class="input-group-addon">元</span>
                </div>
                
              </div>
              <div class="form-group">
                    <label for="" class="col-sm-2 control-label" >&nbsp;</label>
                    <div class="col-sm-10">
                      <span class="help-block" >优先比例计算，比例不设置，使用固定金额奖励(不奖励设置0)</span>
                    </div>
             </div>

              

              <div class="form-group">
                    <div class="col-sm-10">
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" /> <input
                            name="submit" value="submit" type="hidden" />
                        <hr />
                        <button class="btn btn-primary" type="submit">提交</button>
                    </div>
             </div>


        </div>
    </div>

</form>

    <div class="panel panel-default">
        <div class="panel-body">
            <h4 style="color:#ff0000">1、抽成模式</h4>
            <p>假设：二级代理产佣金200元</p>
            <p>假设：一级代理提取二级佣金比率10%、二级代理自己产生佣金比率50%</p>
            <p>二级代理佣金可以得到 200元*50%=100元</p>
            <p>一级代理抽取了二级代理的10%就是100元*10%=10元</p>
            <p>二级代理最终得到佣金为 100-10=90元，一级代理可以得到二级代理抽成的10元佣金</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <h4 style="color:#ff0000">2、普通大众模式</h4>
            <p>只需要设置，自己产生佣金比率 (一级)50%、自己产生佣金比率 (二级)30%、自己产生佣金比率 (三级)20%，这三个就可以了，其它的比例不用设置</p>
            <p>假设：三级产生佣金为100元</p>
            <p>三级自身拿50元、二级拿30元、一级拿20%</p>
            <p>循环三级，最多三级</p>
        </div>
    </div>

<script>
require(['jquery','util'], function($, util){
	$(function(){
		util.emotion($('#emotion'), $('#reply'));
	});
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
