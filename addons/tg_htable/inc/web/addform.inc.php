<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2016-12-03 22:22:10
 * @version $Id$
 */

defined('IN_IA') or exit('Access Denied');
global $_GPC , $_W;
mypdo_settablepre('tgnet_');

function isNull($arr){
	foreach($arr as $k => $v){
		if(trim($v) == ''){
			return true;
			break;
		}
	}
	return false;
}

function getRandChar($length){
	$str = null;
	$strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
	$max = strlen($strPol)-1;

	for($i=0;$i<$length;$i++){
    	$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	}
	return $str;
}
function createQuery($arr,$type,$index){
	$sql = '';
	switch($type){
		case 'number':
			$sql = '`col_'.$index.'` int('.$arr[$type].') ,';
		break;
		case 'checkbox':
		case 'string':
		case 'image':
		case 'time':
		case 'radio':
		case 'select':
			$sql = '`col_'.$index.'` varchar('.$arr[$type].') CHARACTER SET utf8 NOT NULL,';
		break;
		case 'text':
			$sql = '`col_'.$index.'` text CHARACTER SET utf8 NOT NULL,';
		break;
	}
	return $sql;
}

if(checksubmit('submit')){
	$info = array($_GPC['name']);
	array_push($info,$_GPC['type']);
	array_push($info,$_GPC['ex']);

	$combine = array_combine($_GPC['type'],$_GPC['len']);
	if(trim($_GPC['formname']) !== ''){
		if(!isNull($_GPC['name'])){
			$tbname = 'htb_'.getRandChar(6);
			$data['name'] = $_GPC['formname'];
			$data['tbname'] = 'tgnet_'.$tbname;
			$data['keyword'] = '';
			$data['link'] = json_encode($info);

			$sql = 'CREATE TABLE IF NOT EXISTS `'.$data['tbname'].'` (`id` int(10) NOT NULL AUTO_INCREMENT,`nickname` varchar(20) CHARACTER SET utf8 NOT NULL,';
			$colnum = 0;

			foreach($_GPC['type'] as $k => $v){
				$sql .= createQuery($combine,$v,$colnum);
				$colnum++;
			}
			$sql .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
			pdo_query($sql);

			if(mypdo_insert('htb_settings',$data) && mypdo_tableexists($tbname)){
				message('添加成功！','refresh','success');
			}else{
				message('添加失败','refresh','error');
			}
		}else{
			message('字段内容不能为空！','refresh','error');
		}
	}else{
		message('报表名称不能为空！','refresh','error');
	}
}

//$info = mypdo_getall('htb_settings');
//var_dump(json_decode($info[0]['link']));

include $this->template("web/addform");