<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li class="active"><a href="<?php  echo $this->createWebUrl('memberorder',array('openid'=>$openid))?>"><?php  echo $share['nickname'];?>分销订单</a></li>
    <li><a href="<?php  echo $this->createWebUrl('member',array('pid'=>$pid))?>">会员管理</a></li>
    <li ><a href="<?php  echo $this->createWebUrl('dlmember',array('pid'=>$pid,'status'=>1))?>">代理管理</a></li>
</ul>
<style>
th{
	text-align: left !important;
}
.table-responsive .label { display:inline-block;margin:0;margin-bottom:2px;}
td{
	text-align: left !important;
	/*white-space: normal !important;
	word-break: break-all !important;*/
}
</style>
<div class="panel panel-info">
        <div class="panel-heading">筛选</div>
        <div class="panel-body">
            <form action="<?php  echo $this->createWebUrl('memberorder')?>" method="get" class="form-horizontal">
                                <input type="hidden" name="i" value="<?php  echo $_W['uniacid'];?>">
                                <input type="hidden" name="c" value="site">
                                <input type="hidden" name="a" value="entry">
                                <input type="hidden" name="m" value="tiger_wxdaili">
                                <input type="hidden" name="do" value="memberorder">
                                <input type="hidden" name="openid" value="<?php  echo $openid;?>">
                <div class="form-group">
                   <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label" style="width: 200px;">订单号</label>
                    <div class="col-sm-2 col-lg-3">
	                    <input type="text" name="name" value="<?php  echo $name;?>" class="form-control" style="display: inline-block;">
                    </div>
                   <button class="btn btn-default">搜索</button>
                </div>
            </form>
        </div>
</div>

<div class="panel panel-default">

	<div class="panel-body" style="text-align: center;">

    <table class="table table-bordered">
         <caption style="color:#fff;line-height:30px;background:#f45454;"><?php  echo $share['tname'];?>[<?php  echo $share['nickname'];?>]的订单明细 | 联系：<?php  echo $share['tel'];?></caption>
        <tr>
          <td>全部结算收入：<?php  echo $fsbl['zong'];?>元</td>
          <td>上月结算收入：<?php  echo $fsbl['s1'];?>元</td>
        </tr>
        <tr>
          <td>本月预估收入：<?php  echo $fsbl['b1'];?>元</td>
          <td>今日预估收入：<?php  echo $fsbl['j1'];?>元</td>
        </tr>
    </table>


        <table class="table table-hover table-responsive">

            <thead class="navbar-inner">
                <tr>
                    <th >商品信息</th>		
                    <th width='200'>佣金信息</th>
                    <th width='200'>代理名称</th>                    
                    <th width='250'>时间</th>
					<!--th>操作</th-->
                </tr>
            </thead>

            <tbody >
            <style>
            .bjred{background:#8ac007;color:#ffffff}
            </style>
            

                <?php  if(is_array($list)) { foreach($list as $l) { ?>

                <tr>
                    <td><?php  echo $l['title'];?><Br>商品ID：<?php  echo $l['numid'];?><Br><span class="btn-xs bjred">订单号：<?php  echo $l['orderid'];?></span><Br>店铺名称：<?php  echo $l['shopname'];?>

                    <Br>订单状态：<?php  if($l['orderzt']=='订单失效') { ?><span style="color:#ff0000"><?php  echo $l['orderzt'];?></span><?php  } else if($l['orderzt']=='订单付款') { ?><span style="color:#5cb85c;"><?php  echo $l['orderzt'];?></span><?php  } else if($l['orderzt']=='订单结算') { ?><b style="color:blue"><?php  echo $l['orderzt'];?></b><?php  } ?><Br>
                    
                    来源平台：<?php  echo $l['pt'];?></td>
                    <td><span style="color:#ff0000">推广位ID：<?php  echo $l['tgwid'];?></span><Br>收入比例：<?php  echo $l['srbl'];?><Br>分成比例：<?php  echo $l['fcbl'];?><Br>订单金额：<?php  echo number_format($l['fkprice'], 2, '.', '')?><Br>预估佣金：<?php  echo number_format($l['xgyg'], 2, '.', '')?></td>
                    <td><?php  echo $share['nickname'];?><Br>
                      代理所得比例：<?php  echo $share['dlbl'];?>%<Br>
                      <span class="btn-xs bjred">代理所得佣金：<?php  echo $l['dlyj'];?></span>
             
                    
                    </td>
                    <td>更新时间：<?php  echo $l['createtime'];?><Br>创建时间：<?php  echo $l['addtime'];?><Br>结算时间：<?php  echo $l['jstime'];?><Br></td>
                    <!--td>
                        <a href='<?php  echo $this->createWebUrl("memberedit",array("id"=>$l["id"]))?>' class='btn btn-info btn-sm'>分销订单</a-->
                    	<!--a onclick="return confirm('删除后将无法恢复，确定删除吗？')" href='<?php  echo $this->createWebUrl("delete",array("sceneid"=>$l["sceneid"],"sid"=>$l["id"],"status"=>$status))?>' class='btn btn-danger btn-sm'>删除</a>
                    </td-->
                </tr>
                <?php  } } ?>

            </tbody>

        </table>

        <?php  echo $pager;?>

    </div>

</div>
<script language="javascript">
			     			require(['bootstrap'],function(){
        $("[rel=pop]").popover({
            trigger:'manual',
            placement : 'left', 
            title : $(this).data('title'),
            html: 'true', 
            content : $(this).data('content'),
            animation: false
        }).on("mouseenter", function () {
                    var _this = this;
                    $(this).popover("show"); 
                    $(this).siblings(".popover").on("mouseleave", function () {
                        $(_this).popover('hide');
                    });
                }).on("mouseleave", function () {
                    var _this = this;
                    setTimeout(function () {
                        if (!$(".popover:hover").length) {
                            $(_this).popover("hide")
                        }
                    }, 100);
                });
 
	 
	   });
				   
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>