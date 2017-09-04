<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2016-12-03 22:23:02
 * @version $Id$
 */

defined('IN_IA') or exit('Access Denied');
global $_GPC , $_W;
mypdo_settablepre('tgnet_');
$local = $this->createWebUrl('manageform');
$local = (substr($local,0,-1) == '&') ? $local : $local.'&';
$send  = $this->createWebUrl('senddata').'&totable=13';

$imgtype = array(
      '.jpeg','.JPEG','.jpg','.JPG',
      '.png','.PNG','.gif','.GIF',
      '.bmp','.BMP','.svg','.SVG','.ai','.AI'
  );

$list = mypdo_getall('htb_settings');
function resetData($arr){
	foreach($arr as $key => $value){
		$tmp[$key+1] = $value;
	}
	return $tmp;
}

$list = resetData($list);

if(!empty($_GET['st_status'])){
	$status = $list[$_GET['key']]['status'] ? 0 : 1 ; 
	if(mypdo_update('htb_settings',array('status'=>$status),array('id'=>$_GET['st_status']))){
		message("",$local,"success");
	}else{
		message("修改失败！",$local,"error");
	}
}

if(!empty($_GPC['deltb'])){
	$tablename = $list[$_GPC['key']]['tbname'];
	pdo_query("DROP TABLE IF EXISTS `".$tablename."`");
	$tablename = ltrim($tablename,'tgnet_');
	$deltb = mypdo_delete("htb_settings",array('id'=>$_GPC['deltb']));

	if(!pdo_tableexists($tablename) && $deltb){
		message('删除成功！',$local,'success');
	}else{
		message('删除失败！',$local,'error');
	}
}

function excelTxt($data,$th = ''){
	return '<html xmlns:o="urn:schemas-microsoft-com:office:office" 
	xmlns:x="urn:schemas-microsoft-com:office:excel" 
	xmlns="http://www.w3.org/TR/REC-html40"> 
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
	<html> 
	<head> 
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" /> 
		<style id="Classeur1_16681_Styles"></style> 
	</head> 
	<body> 
		<div id="Classeur1_16681" align=center x:publishsource="Excel"> 
			<table x:str border=1 >'.
			$th.
			$data
			.'</table> 
		</div> 
	</body> 
	</html>';
}

if(!empty($_GPC['output'])){
	$title = mypdo_get('htb_settings',array('tbname'=>'tgnet_'.$_GPC['tbname']));
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename=".$title['name'].".xls");
	
	$firstline = $title['name'];
	$title = json_decode($title['link']);
	array_unshift($title[0],'微信');
	array_unshift($title[0],'序号');
	$th = '<tr><th style="font-size:22px;" colspan="'.count($title[0]).'">'.$firstline.'</th></tr><tr>';

	foreach($title[0] as $k => $v){
		$th .= '<th>'.$v.'</th>';
	}
	$th .= '</tr>';
	$tr = '';//var_dump($title);
	foreach($_GPC['sections'] as $key => $value){
		$data = mypdo_get($_GPC['tbname'],array('id'=>$value));
		$tr .= '<tr>';
		foreach($data as $k => $v){
			if(in_array(strrchr($v,'.'),$imgtype)){
				$tr .= '<td style="height:60px;"><img width="80" height="60" src="'.$v.'" /></td>';
			}else{
				$tr .= '<td>'.$v.'</td>';
			}
		}
		$tr .= '</tr>';
	}
	echo excelTxt($tr,$th);
	exit();
	//exit(json_encode(array()));
}

if(!empty($_GPC['outputall'])){
	$title = mypdo_get('htb_settings',array('tbname'=>'tgnet_'.$_GPC['tbname']));
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename=".$title['name'].".xls");
	
	$firstline = $title['name'];
	$title = json_decode($title['link']);
	array_unshift($title[0],'微信');
	array_unshift($title[0],'序号');
	$th = '<tr><th style="font-size:22px;" colspan="'.count($title[0]).'">'.$firstline.'</th></tr><tr>';

	foreach($title[0] as $k => $v){
		$th .= '<th>'.$v.'</th>';
	}
	$th .= '</tr>';
	$tr = '';
	$data = mypdo_getall($_GPC['tbname']);
	foreach($data as $key => $value){
		$tr .= '<tr>';
		foreach($value as $k => $v){
			if(in_array(strrchr($v,'.'),$imgtype)){
				$tr .= '<td style="height:60px;"><img width="80" height="60" src="'.$v.'" /></td>';
			}else{
				$tr .= '<td>'.$v.'</td>';
			}
		}
		$tr .= '</tr>';
	}
	
	echo excelTxt($tr,$th);
	exit();
}

$showRow = 15;
$page = new Page($list);
$page->set('showRow',$showRow);
if(isset($_GPC['searchcont'])){
	//$alldatas = mypdo_getall(trim($_GPC['tbname']));
	$curPage = $_GPC['curpage'];
	$start   = ($curPage-1)*$showRow;
	$end     = $start + $showRow;
	$tb      = 'tgnet_'.trim($_GPC['tbname']);
	$type    = $_GPC['searchtype'];
	$cont    = $_GPC['searchcont'];
	
	$sql = "SELECT * FROM `".$tb."` WHERE ".$type." LIKE '%".$cont."%'";
	$info = pdo_fetchall($sql);
	//$page = new Page($info);
	$page->set('method','post');
	$page->set('data',$info);
	$page->set('curPage',$curPage);
	$retData = array(
			"data"		=> $page->retData(),
			"totalpage"	=> $page->totalPage(),
			"curpage"	=> $page->curPage(),
		);
	exit(json_encode($retData));
	exit(json_encode(array('msgdf'=>'5ok')));
}
if(!empty($_GPC['info'])){
	$curTable = $list[$_GET['key']];
	$title    = $curTable['name'];
	$tbname   = $curTable['tbname'];
	$data     = $curTable['link'];
	$data     = json_decode($data);

	$sql    = "SELECT * FROM `".$tbname.'`';
	$info   = pdo_fetchall($sql);
	$tbname = ltrim($tbname,'tgnet_');
	//$page = new Page($info);
	$page->set('method','get');
	$page->set('data',$info);
	$pageNum  = $page->totalPage();
	$curPage  = $page->curPage();
	$pageData = $page->retData();
	$pageStr  = $page->dumpPage();

	include $this->template("web/info");
}else{
	$page->set('showRow',15);
	$pageNum  = $page->totalPage();
	$curPage  = $page->curPage();
	$pageData = $page->retData();
	$pageStr  = $page->dumpPage();
	include $this->template("web/manageform");
}


?>