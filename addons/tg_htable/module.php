<?php
/**
 * 汇报表模块定义
 *
 * @author tg
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Tg_htableModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	
		global $_GPC,$_W;

		$keywords = $_GPC['keywords'];
		$keywords = str_replace(array('&quot;'),array('"'),$keywords);
		$keywords = json_decode($keywords,true);
		$content = $keywords[0]["content"];

		$diydata = array(
				"rid"			=> $rid,
				"uniacid"		=> $_W["uniacid"],
				"module"		=> $_GPC["m"],
				"content"		=> $content,
				"type"			=> 1,
				"displayorder"	=> 0,
				"status"		=> 1,
			);
		
		$myrule = "^htb@";
		$defdata = array(
				"rid"			=> $rid,
				"uniacid"		=> $_W["uniacid"],
				"module"		=> $_GPC["m"],
				"content"		=> $myrule,
				"type"			=> 3,
				"displayorder"	=> 118,
				"status"		=> 1,
			);
		
		$ret = pdo_delete("rule_keyword",array("rid"=>$rid));
		pdo_insert("rule_keyword",$diydata);
		pdo_insert("rule_keyword",$defdata);
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}


}