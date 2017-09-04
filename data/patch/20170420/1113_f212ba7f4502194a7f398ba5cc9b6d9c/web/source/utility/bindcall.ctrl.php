<?php 
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

load()->model('module');

$modulename = trim($_GPC['modulename']);
$callname = trim($_GPC['callname']);
$uniacid = intval($_GPC['uniacid']);
$_W['uniacid'] = intval($_GPC['uniacid']);
$args = $_GPC['args'];
$module_info = module_fetch($modulename);
if (empty($module_info)) {
	message(error(-1, '该模块不存在'), '', 'ajax');
}
$site = WeUtility::createModuleSite($modulename);
if (empty($site)) {
	message(array(), '', 'ajax');
}
if (!method_exists($site, $callname)) {
	message(error(-1, '该方法不存在'), '', 'ajax');
}
$ret = @$site->$callname($args);
message($ret, '', 'ajax');