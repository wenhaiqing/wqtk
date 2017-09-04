<?php
if(!pdo_tableexists('ims_tiger_taoke_sdorder')){
$sql="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_sdorder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,  
  `openid` varchar(50) NOT NULL,
  `image` varchar(300) DEFAULT 0,
  `order` varchar(100) NOT NULL,
  `price` Decimal(10,2) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `evaluation` varchar(250) NOT NULL,
  `pf` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `jljf` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;
";
pdo_run($sql);
}

if(!pdo_tableexists('ims_tiger_taoke_cdtype')){
$sql1="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_cdtype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `px` int(10) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `fftype` int(3) NOT NULL DEFAULT '0' COMMENT '分类',
  `picurl` varchar(255) NOT NULL COMMENT '封面',
  `wlurl` varchar(255) NOT NULL COMMENT '外链',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
   KEY `weid` (`weid`),
   KEY `fftype` (`fftype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
";
pdo_run($sql1);
}
if(!pdo_tableexists('ims_tiger_taoke_tkorder')){
$sql2="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_tkorder` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `addtime` varchar(255) DEFAULT 0 COMMENT '创建时间',
   `orderid` varchar(255) DEFAULT 0 COMMENT '订单编号',
   `numid` varchar(255) DEFAULT 0 COMMENT '商品ID',
   `shopname` varchar(255) DEFAULT 0 COMMENT '店铺名称',
   `title` varchar(255) DEFAULT 0 COMMENT '商品标题',
   `orderzt` varchar(255) DEFAULT 0 COMMENT '订单状态',
   `srbl` varchar(255) DEFAULT 0 COMMENT '收入比例',
   `fcbl` varchar(255) DEFAULT 0 COMMENT '分成比例',
   `fkprice` varchar(255) DEFAULT 0 COMMENT '付款金额',
   `xgyg` varchar(255) DEFAULT 0 COMMENT '效果预估',
   `jstime` varchar(255) DEFAULT 0 COMMENT '结果时间',
   `pt` varchar(255) DEFAULT 0 COMMENT '平台',
   `createtime` varchar(255) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql2);
}

if(!pdo_tableexists('ims_tiger_taoke_txlog')){
$sql3="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_txlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL COMMENT '微信用户昵称',
  `openid` varchar(255) DEFAULT NULL COMMENT '微信用户openid',
  `avatar` varchar(255) DEFAULT 0 COMMENT '',
  `addtime` int(11) DEFAULT NULL COMMENT '打款时间',
  `credit1` int(11) DEFAULT NULL COMMENT '消耗积分',
  `credit2` varchar(100) DEFAULT NULL COMMENT '金额，分为单位',
  `dmch_billno` varchar(50) DEFAULT NULL COMMENT '生成的商户订单号',
  `sh` tinyint(1) DEFAULT NULL COMMENT '是否打款成功',
  `dresult` varchar(255) DEFAULT NULL COMMENT '失败提示',
    `createtime` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;
";
pdo_run($sql3);
}

if(!pdo_tableexists('ims_tiger_taoke_order')){
$sql3="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_order` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `openid` varchar(255) DEFAULT 0 COMMENT '',
   `nickname` varchar(255) DEFAULT 0 COMMENT '',
   `avatar` varchar(255) DEFAULT 0 COMMENT '',
   `memberid` varchar(255) DEFAULT 0 COMMENT '会员编号',
   `orderid` varchar(255) DEFAULT 0 COMMENT '订单编号',
   `price` varchar(255) DEFAULT 0 COMMENT '奖励金额',
   `type` varchar(255) DEFAULT 0 COMMENT '类型 0 自有  1级奖励 2级奖励',  
   `sh` varchar(255) DEFAULT 0 COMMENT '是否审核 0  1审核 2代返  3已返',  
   `msg` varchar(255) DEFAULT 0 COMMENT '留言',  
   `createtime` varchar(255) NOT NULL,
     KEY `indx_weid` (`weid`),
     KEY `indx_orderid` (`orderid`),
	 KEY `indx_openid` (`openid`),
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql3);
}

if(!pdo_tableexists('ims_tiger_taoke_msg')){
$sql4="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(200) NOT NULL,
  `picurl` varchar(255) NOT NULL COMMENT '封面',
  `createtime` int(10) NOT NULL,
   PRIMARY KEY (`id`),
   KEY `weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
";
pdo_run($sql4);
}

if(!pdo_tableexists('ims_tiger_taoke_ck')){
$sql5="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_ck` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `weid` int(11) DEFAULT '0',
	  `data` text DEFAULT NULL,
	  `createtime` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`),
	  KEY `idx_weid` (`weid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
pdo_run($sql5);
}

if(!pdo_tableexists('ims_tiger_taoke_gfhuodong')){
$sql6="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_gfhuodong` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `px` int(10) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `type` int(3) NOT NULL DEFAULT '0' COMMENT '分类',
  `picurl` varchar(255) NOT NULL COMMENT '图片',
  `turl` varchar(255) NOT NULL COMMENT '外链',
  `kouling` varchar(255) NOT NULL COMMENT '口令',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
   KEY `weid` (`weid`),
   KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
";
pdo_run($sql6);
}
if(!pdo_tableexists('ims_tiger_taoke_gfhuodong')){
$sql7="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_shoucang` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `title` varchar(150) DEFAULT 0,
   `goodsid` varchar(50) DEFAULT 0,
   `picurl` varchar(250) DEFAULT 0,
   `openid` varchar(50) DEFAULT 0,
   `uid` varchar(50) DEFAULT 0,   
   `createtime` int(10) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql7);
}

if(!pdo_tableexists('ims_tiger_taoke_mobanmsg')){
$sql8="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_mobanmsg` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `title` varchar(250) DEFAULT NULL COMMENT '模版标题',
   `mbid` varchar(250) DEFAULT NULL COMMENT '模版ID',
   `first` varchar(250) DEFAULT NULL COMMENT '头部内容',
   `firstcolor` varchar(100) DEFAULT NULL COMMENT '头部颜色',
   `zjvalue` text COMMENT '中间内容',
   `zjcolor` text COMMENT '中间颜色',
   `remark` varchar(250) DEFAULT NULL COMMENT '尾部内容',
   `remarkcolor` varchar(100) DEFAULT NULL COMMENT '尾部颜色',
   `turl` varchar(250) DEFAULT NULL NULL COMMENT '模版链接',
   `createtime` int(10) NOT NULL,
   KEY `weid` (`weid`),
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql8);
}
if(!pdo_tableexists('ims_tiger_taoke_tksign')){
$sql9="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_tksign` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `weid` int(11) DEFAULT 0,
   `tbuid` varchar(50) DEFAULT NULL,
   `sign` varchar(100) DEFAULT NULL,
   `endtime` varchar(30) DEFAULT NULL COMMENT  '结束时间',
   `createtime` int(10) NOT NULL,
   KEY `weid` (`weid`),
   KEY `tbuid` (`tbuid`),
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql9);
}
if(!pdo_tableexists('ims_tiger_taoke_zttype')){
$sql10="
CREATE TABLE IF NOT EXISTS `ims_tiger_taoke_zttype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `px` int(10) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `fftype` int(3) NOT NULL DEFAULT '0' COMMENT '分类',
  `picurl` varchar(255) NOT NULL COMMENT '封面',
  `wlurl` varchar(255) NOT NULL COMMENT '外链',
  `createtime` int(10) NOT NULL,
   PRIMARY KEY (`id`),
   KEY `weid` (`weid`),
   KEY `fftype` (`fftype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
";
pdo_run($sql10);
}

if (!pdo_fieldexists('tiger_taoke_tbgoods', 'event_zt')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `event_zt` varchar(50) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'event_yjbl')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `event_yjbl` varchar(50) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'event_yj')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `event_yj` varchar(50) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'yjtype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `yjtype` int(2) NOT NULL DEFAULT '0';");
}
if (!pdo_fieldexists('tiger_taoke_fztype', 'dtkcid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_fztype') . " ADD  `dtkcid` varchar(10) NOT NULL;");
}

if (!pdo_fieldexists('tiger_taoke_tbgoods', 'istmall')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `istmall` varchar(5) NOT NULL;");
}

if (!pdo_fieldexists('tiger_taoke_tbgoods', 'dsr')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `dsr` varchar(20) NOT NULL;");
}

if (!pdo_fieldexists('tiger_taoke_tbgoods', 'quan_id')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `quan_id` varchar(100) NOT NULL;");
}

if (!pdo_fieldexists('tiger_taoke_tbgoods', 'quan_condition')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `quan_condition` varchar(20) NOT NULL;");
}

if (!pdo_fieldexists('tiger_taoke_tbgoods', 'org_price')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `org_price` varchar(50) NOT NULL;");
}

if (!pdo_fieldexists('tiger_taoke_goods', 'fxprice')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_goods') . " ADD  `fxprice` decimal(10,2) NOT NULL DEFAULT '0';");
}

if (!pdo_fieldexists('tiger_taoke_request', 'fxprice')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_request') . " ADD  `fxprice` decimal(10,2) NOT NULL DEFAULT '0';");
}
if (!pdo_fieldexists('tiger_taoke_request', 'tborder')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_request') . " ADD  `tborder` varchar(200) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_goods', 'taokouling')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_goods') . " ADD  `taokouling`  varchar(200) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'status')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `status` varchar(20) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_sdorder', 'sjjl')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_sdorder') . " ADD  `sjjl` varchar(200) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_fztype', 'hlcid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_fztype') . " ADD  `hlcid` varchar(10) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'dingxianurl')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `dingxianurl` varchar(300) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_tkorder', 'type')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tkorder') . " ADD  `type` varchar(20) DEFAULT 0 ;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'zd')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `zd` int(10) NOT NULL DEFAULT '0';");
}

if (!pdo_fieldexists('tiger_taoke_order', 'jlnickname')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_order') . " ADD  `jlnickname` varchar(255) DEFAULT 0 ;");
}
if (!pdo_fieldexists('tiger_taoke_order', 'jlavatar')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_order') . " ADD  `jlavatar` varchar(255) DEFAULT 0 ;");
}
if (!pdo_fieldexists('tiger_taoke_order', 'yongjin')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_order') . " ADD  `yongjin` varchar(255) DEFAULT 0 ;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'hit')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `hit` varchar(50) DEFAULT 0;");
}
if (!pdo_fieldexists('tiger_taoke_fztype', 'tag')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_fztype') . " ADD  `tag` varchar(250) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_share', 'weixin')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `weixin` varchar(100) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_share', 'type')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `type` varchar(15) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_txlog', 'zfbuid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_txlog') . " ADD  `zfbuid` varchar(100) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'qf')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `qf` int(10) NOT NULL DEFAULT '0';");
}

if (!pdo_fieldexists('tiger_taoke_tkorder', 'mtid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tkorder') . " ADD  `mtid` varchar(150) DEFAULT 0 ;");
}
if (!pdo_fieldexists('tiger_taoke_tkorder', 'mttitle')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tkorder') . " ADD  `mttitle` varchar(150) DEFAULT 0 ;");
}
if (!pdo_fieldexists('tiger_taoke_tkorder', 'tgwid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tkorder') . " ADD  `tgwid` varchar(150) DEFAULT 0 ;");
}
if (!pdo_fieldexists('tiger_taoke_tkorder', 'tgwtitle')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tkorder') . " ADD  `tgwtitle` varchar(150) DEFAULT 0 ;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'lxtype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `lxtype` int(2) NOT NULL DEFAULT '0';");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'zy')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `zy` int(10) NOT NULL DEFAULT '0';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'tgwid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `tgwid` varchar(250) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_share', 'dltype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `dltype` varchar(10) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_share', 'dlbl')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `dlbl` varchar(110) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_share', 'dlqqpid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `dlqqpid` varchar(110) NOT NULL COMMENT '代理鹊桥PID';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'dlptpid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `dlptpid` varchar(110) NOT NULL COMMENT '代理普通PID';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'dlsh')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `dlsh` varchar(10) NOT NULL COMMENT '0 审核中 1通过';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'dlmsg')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `dlmsg` varchar(250) NOT NULL COMMENT '代理申请理由';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'zfbuid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `zfbuid` varchar(150) NOT NULL COMMENT '支付宝';");
}

if (!pdo_fieldexists('tiger_taoke_share', 'pctitle')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pctitle` varchar(250) NOT NULL COMMENT 'PC网站标题';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pckeywords')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pckeywords` varchar(250) NOT NULL COMMENT 'PC网站关键词';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pcdescription')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pcdescription` varchar(250) NOT NULL COMMENT 'PC网站描述';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pcsearchkey')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pcsearchkey` varchar(250) NOT NULL COMMENT 'PC网站搜索框下面关键词';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pcewm1')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pcewm1` varchar(250) NOT NULL COMMENT 'PC二维码1';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pcewm2')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pcewm2` varchar(250) NOT NULL COMMENT 'PC二维码2';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pcbottom1')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pcbottom1` varchar(250) NOT NULL COMMENT 'PC网站底部1';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pcbottom2')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pcbottom2` varchar(250) NOT NULL COMMENT 'PC网站底部1';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pcuser')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pcuser` varchar(250) NOT NULL COMMENT '登录帐号';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pcpasswords')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pcpasswords` varchar(250) NOT NULL COMMENT '登录密码';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pclogo')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pclogo` varchar(250) NOT NULL COMMENT 'PCLOGO';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'pcurl')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `pcurl` varchar(250) NOT NULL COMMENT '代理独立域名';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'dlbl2')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `dlbl2` varchar(50) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_share', 'dlbl3')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `dlbl3` varchar(50) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_share', 'tname')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `tname` varchar(50) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_share', 'qunname')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `qunname` varchar(100) NOT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_ck', 'taodata')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_ck') . " ADD  `taodata` text DEFAULT NULL;");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'dxtime')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `dxtime` int(13) NOT NULL COMMENT '定向申请时间';");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'videoid')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD `videoid` varchar(50) NOT NULL COMMENT '视频ID';");
}
if (!pdo_fieldexists('tiger_taoke_tbgoods', 'zt')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_tbgoods') . " ADD  `zt` int(10) NOT NULL DEFAULT '0' COMMENT '专题';");
}
if (!pdo_fieldexists('tiger_taoke_share', 'cqtype')) {
	pdo_query("ALTER TABLE " . tablename('tiger_taoke_share') . " ADD  `cqtype` varchar(10) NOT NULL COMMENT '0 不能查询  1 能查询';");
}
