<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
$_W['page']['title'] = '性能优化 - 系统管理';
$dos = array('opcache');
$do = in_array($do, $dos) ? $do : 'index';
if ($do == 'opcache') {
	opcache_reset();
	message('清空缓存成功', url('system/optimize'), 'success');
} else {
	$extensions = array(
		'memcache' => array(
			'support' => extension_loaded('memcache'),
			'status' => ($_W['config']['setting']['cache'] == 'memcache'),
			'clear' => array(
				'url' => url('system/updatecache'),
				'title' => '更新缓存',
			),
		),
		'eAccelerator' => array(
			'support' => function_exists('eaccelerator_optimizer'),
			'status' => function_exists('eaccelerator_optimizer'),
		),
		'opcache' => array(
			'support' => function_exists('opcache_get_configuration'),
			'status' => ini_get('opcache.enable') || ini_get('opcache.enable_cli'),
			'clear' => array(
				'url' => url('system/optimize/opcache'),
				'title' => '清空缓存',
			)
		)
	);
	$slave = $_W['config']['db'];
	$setting = $_W['config']['setting'];
	
	if ($extensions['memcache']['status']) {
		$memobj = cache_memcache();
		if (!empty($memobj) && method_exists($memobj, 'getExtendedStats')) {
			$status = $memobj->getExtendedStats();
			if (!empty($status)) {
				foreach ($status as $server => $row) {
					$data_status[] = '已用：' . round($row['bytes'] / 1048567, 2) . ' M / 共：' . round($row['limit_maxbytes'] / 1048567) . ' M';
				}
				$extensions['memcache']['extra'] = ', ' . implode(', ', $data_status);
			}
		}
	}
	template('system/optimize');
}
