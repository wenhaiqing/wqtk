<?php
/**
 * 汇报表模块微站定义
 *
 * @author tg
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Tg_htableModuleSite extends WeModuleSite {

}

/*****************************************************/
/*******以下是为动态改变数据表前缀的函数，为解决在manifest文件中安装数据表
而无法确定数据表前缀设计，其中自定义了一个自己数据操作类，继承系统db类，并全部
对系统pdo函数进行更名改造，在每个函数前都加my，其余功能和参数不变

**********/
class mydb extends db{
	public function __construct ($name = 'master'){
		global $_W;
		$this->cfg = $_W['config']['db'];
		$this->connect($name);
	}
	public function getTablePre(){
		echo $this->tablepre;
	}
	public function setTablePre($preName){
		$this->tablepre = $preName ;
	}
}

function mypdo() {
	global $_W;
	static $db;
	if(empty($db)) {
		if($_W['config']['db']['slave_status'] == true && !empty($_W['config']['db']['slave'])) {
			load()->classs('slave.db');
			$db = new SlaveDb('master');
		} else {
			//load()->classs('db');
			if(empty($_W['config']['db']['master'])) {
				$_W['config']['db']['master'] = $GLOBALS['_W']['config']['db'];
				//$db = new DB($_W['config']['db']);
				$db = new mydb($_W['config']['db']);
			} else {
				//$db = new DB('master');
				$db = new mydb('master');
			}
		}
	}
	return $db;
}


function mypdo_query($sql, $params = array()) {
	return mypdo()->query($sql, $params);
}


function mypdo_fetchcolumn($sql, $params = array(), $column = 0) {
	return mypdo()->fetchcolumn($sql, $params, $column);
}

function mypdo_fetch($sql, $params = array()) {
	return mypdo()->fetch($sql, $params);
}

function mypdo_fetchall($sql, $params = array(), $keyfield = '') {
	return mypdo()->fetchall($sql, $params, $keyfield);
}


function mypdo_get($tablename, $condition = array(), $fields = array()) {
	return mypdo()->get($tablename, $condition, $fields);
}

function mypdo_getall($tablename, $condition = array(), $fields = array(), $keyfield = '') {
	return mypdo()->getall($tablename, $condition, $fields, $keyfield);
}

function mypdo_getslice($tablename, $condition = array(), $limit = array(), &$total = null, $fields = array(), $keyfield = '') {
	return mypdo()->getslice($tablename, $condition, $limit, $total, $fields, $keyfield);
}


function mypdo_update($table, $data = array(), $params = array(), $glue = 'AND') {
	return mypdo()->update($table, $data, $params, $glue);
}


function mypdo_insert($table, $data = array(), $replace = FALSE) {
	return mypdo()->insert($table, $data, $replace);
}


function mypdo_delete($table, $params = array(), $glue = 'AND') {
	return mypdo()->delete($table, $params, $glue);
}


function mypdo_insertid() {
	return mypdo()->insertid();
}


function mypdo_begin() {
	mypdo()->begin();
}


function mypdo_commit() {
	mypdo()->commit();
}


function mypdo_rollback() {
	mypdo()->rollBack();
}


function mypdo_debug($output = true, $append = array()) {
	return mypdo()->debug($output, $append);
}

function mypdo_run($sql) {
	return mypdo()->run($sql);
}


function mypdo_fieldexists($tablename, $fieldname = '') {
	return mypdo()->fieldexists($tablename, $fieldname);
}


function mypdo_indexexists($tablename, $indexname = '') {
	return mypdo()->indexexists($tablename, $indexname);
}


function mypdo_fetchallfields($tablename){
	$fields = mypdo_fetchall("DESCRIBE {$tablename}", array(), 'Field');
	$fields = array_keys($fields);
	return $fields;
}


function mypdo_tableexists($tablename){
	return mypdo()->tableexists($tablename);
}

function mypdo_settablepre($preName){
	mypdo()->setTablePre($preName);
}

function mypdo_gettablepre(){
	return mypdo()->getTablePre();
}


function page($arrData,$url,$param = 'page',$showNums = 15,$showPageNums = 7,$sign = '/'){
	$str = '';
	$maxpage = count($arrData)%$showNums ? intval(count($arrData)/$showNums)+1 : intval(count($arrData)/$showNums);
	$curpage = !isset($_GET[$param]) || intval($_GET[$param]) < 2 ? 1 : intval($_GET[$param]);
	$prepage = $curpage - 1;
	$prepage = $prepage < 1 ? 1 : $prepage;
	$nextpage = $curpage + 1;
	$nextpage = $nextpage > $maxpage ? $maxpage : $nextpage;
	$middle  = $showPageNums%2 ? intval($showPageNums/2)+1 : $showPageNums/2;
	$startPage = intval($_GET[$param])-$middle>1 ? intval($_GET[$param])-$middle : 1;
	$startPage = intval($_GET[$param])+$middle<$maxpage ? intval($_GET[$param])-$middle : $maxpage-$showPageNums+1;

	$str .= "<div class='tgpage'>共 $maxpage 页&nbsp;";
	$str .= "<a class='previous' href=$url$param$sign$prepage>上一页</a>&nbsp;";
	if($maxpage < $showPageNums){
		for($i = 1 ; $i < $maxpage + 1 ; $i++){
			if($curpage == $i){
				$str .= "<a class='clicked'>$i</a>&nbsp;";
			}else{
				$str .= "<a href=$url$param$sign$i>$i</a>&nbsp;";
			}	
		}	
	}else{
		for($j = $startPage ; $j < $startPage + $showPageNums ; $j++){
			if($curpage == $i){
				$str .= "<a class='clicked'>$j</a>&nbsp;";
			}else{
				$str .= "<a href=$url$param$sign$j>$j</a>&nbsp;";
			}
		}
	}
	$str .= "<a class='next' href=$url$param$sign$nextpage>下一页</a></div>";
	
	return $str;
}

/********************分页类****************************/

class Page{
	private $data 			= array();
	private $retData		= array();
	private $curPage 		= 0;
	private $pageKey		= 1;
	private $prevPage		= 1;
	private $nextPage		= 2;
	private $totalPage 		= 0;
	private $rows 			= 0;
	private $showRow 		= 30;
	private $showPage 		= 10;
	private $showPageBtn	= false;
	private $param			= 'page';
	private $sign			= '=';
	private $baseUrl		= '';
	private $method			= 'GET';
	public function __construct($data = array()){
		$this->data = $data;
		//$this->rows = count($data);
		//$this->_init();
	}
	private function _init(){
		$this->rows = count($this->data);
		/***计算总页数***/
		if($this->showRow > $this->rows - 1){
			$this->totalPage = 1;
		}else{
			$this->totalPage = ceil($this->rows/$this->showRow);
		}
		/***计算当前的页数***/
		if(strtoupper($this->method) == 'POST'){
			
		}else{
			if(empty($_GET[$this->param])){
				$this->curPage = 1;
				$this->pageKey = 1;
			}else{
				$tmp = explode('_',$_GET[$this->param]);
				$this->curPage = intval($tmp[0]);
				$this->pageKey = intval($tmp[1]);
			}
		}
		
		$this->prevPage = $this->curPage - 1;
		$this->nextPage = $this->curPage + 1;

		if($this->prevPage < 2){
			$this->prevPage = 1;
			$this->pageKey = 1;
		}
		if($this->nextPage > $this->totalPage - 1){
			$this->nextPage = $this->totalPage;
			//$this->pageKey = $this->showPage - 1;
		}
		/***设置URL地址***/
		if(trim($this->baseUrl) == ''){
			$this->baseUrl = $_SERVER["REQUEST_URI"];
		}
		if(!strpos($this->baseUrl,'?')){
			$this->baseUrl .= '?';
		}else{
			$this->baseUrl = rtrim($this->baseUrl,'&').'&';
		}
		$this->baseUrl = preg_replace('/'.$this->param.$this->sign.'.*/','',$this->baseUrl);
		/***提取符合条件的数据段***/
		// if($this->showRow > $this->rows){
		// 	$this->retData = array_slice($this->data,0,$this->rows);
		// }else{
		// 	$start = ($this->curPage-1)*$this->showRow;
		// 	$start = $start < count($this->data) ? $start : ($this->curPage-2)*$this->showRow;
		// 	$this->retData = array_slice($this->data,$start,$this->showRow);
		// }	
	}
	private function showPageBtn(){
		$this->_init();
		if(!$this->showPageBtn){
			return false;
		}
		$pageBtn = '';
		$middle  = $this->showPage%2 ? intval($this->showPage/2)+1 : $this->showPage/2;
		$startPage = $this->pageKey-$middle>0 ? ($this->curPage - $middle +1) : 1;
		$startPage = $startPage+$this->showPage<$this->totalPage+1 ? $startPage : $this->totalPage-$this->showPage+1;
		if($this->totalPage < $this->showPage){
			for($i = 1 ; $i < $this->totalPage + 1 ; $i++){
				if($this->curPage == $i){
					$pageBtn .= "<a class='clicked'>$i</a>&nbsp;";
				}else{
					$pageBtn .= '<a href="'.$this->baseUrl.$this->param.$this->sign.$i.'_'.$i.'">'.$i.'</a>&nbsp;';
				}
			}
		}else{
			for($j = $startPage ; $j < $startPage + $this->showPage ; $j++){
				if($this->curPage == $j){
					$pageBtn .= "<a class='clicked'>$j</a>&nbsp;";
				}else{
					$pageBtn .= '<a href="'.$this->baseUrl.$this->param.$this->sign.$j.'_'.($j-$startPage+1).'">'.$j.'</a>&nbsp;';
				}
			}
		}
		return $pageBtn;
	}
	public function dumpPage(){
		$this->_init();
		$url = '<a class="prevpage" href="'.$this->baseUrl.$this->param.$this->sign.$this->prevPage.'_'.($this->pageKey).'">上一页</a>&nbsp;<a class="nextpage" href="'.$this->baseUrl.$this->param.$this->sign.($this->nextPage).'_'.($this->pageKey+1).'">下一页</a>';
		$url .= $this->showPageBtn();
		return $url;
	}
	public function curPage(){
		$this->_init();
		return $this->curPage;
	}
	public function totalPage(){
		$this->_init();
		return $this->totalPage;
	}
	public function retData(){
		$this->_init();
		$start = ($this->curPage-1)*$this->showRow;
		$start = $start < count($this->data) ? $start : ($this->curPage-2)*$this->showRow;
		$this->retData = array_slice($this->data,$start,$this->showRow,true);
		return $this->retData;
	}
	public function set($param,$value = ''){
		if(property_exists(__CLASS__,$param)){
			$this->$param = $value;
		}
	}
};