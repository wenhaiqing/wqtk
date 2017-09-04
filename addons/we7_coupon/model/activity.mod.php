<?php
defined('IN_IA') or exit('Access Denied');

function we7_coupon_activity_coupon_grant($id, $openid) {
	activity_coupon_type_init();
	global $_W, $_GPC;
	if (empty($openid)) {
		$openid = $_W['openid'];
		if(empty($openid)) {
			$openid = $_W['member']['uid'];
		}
		if (empty($openid)) {
			return error(-1, '没有找到指定会员');
		}
	}
	$fan = mc_fansinfo($openid, '', $_W['uniacid']);
	$openid = $fan['openid'];
	if (empty($openid)) {
		return error(-1, '兑换失败');
	}
	$code = base_convert(md5(uniqid() . random(4)), 16, 10);
	$code = substr($code, 1, 16);
	$user = mc_fetch($fan['uid'], array('groupid'));
	$credit_names = array('credit1' => '积分', 'credit2' => '余额');
	$coupon = activity_coupon_info($id);
	$pcount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('coupon_record') . " WHERE `openid` = :openid AND `couponid` = :couponid", array(':couponid' => $id, ':openid' => $openid));
	$coupongroup = pdo_fetchall("SELECT * FROM " . tablename('coupon_groups') . " WHERE `couponid` = :couponid", array(':couponid' => $id), 'groupid');
	$coupon_group = array_keys($coupongroup);
	$member = pdo_get('mc_members', array('uniacid' => $_W['uniacid'], 'uid' => $fan['uid']));
	if (COUPON_TYPE == WECHAT_COUPON) {
		$fan_groups = $fan['tag']['tagid_list'];
	} else {
		$fan_groups[] = $user['groupid'];
	}
	$group = @array_intersect($coupon_group, $fan_groups);
	if (empty($coupon)) {
		return error(-1, '未找到指定卡券');
	}
	elseif (empty($group) && !empty($coupon_group)) {
		if (!empty($fan_groups)) {
			return error(-1, '无权限兑换');
		} else {
			if (is_array($coupon_group) && !in_array('0', $coupon_group)) {
				return error(-1, '无权限兑换');
			}
		}
	}
	elseif (strtotime(str_replace('.', '-', $coupon['date_info']['time_limit_end'])) < strtotime(date('Y-m-d')) && $coupon['date_info']['time_type'] != 2) {
		return error(-1, '活动已结束');
	}
	elseif ($coupon['quantity'] <= 0) {
		return error(-1, '卡券发放完毕');
	}
	elseif ($pcount >= $coupon['get_limit'] && !empty($coupon['get_limit'])) {
		return error(-1, '数量超限');
	}
	elseif (!empty($coupon['modules']) && !in_array($_W['current_module']['name'], array_keys($coupon['modules'])) && ($_GPC['c'] != 'activity' && $_GPC['c'] != 'mc' ) && $_W['current_module']['name'] != 'we7_coupon') {
		return error(-1, '该模块没有此卡券发放权限');
	}
	$give = $_W['activity_coupon_id'] ? true :false;
	$uid = !empty($_W['member']['uid']) ? $_W['member']['uid'] : $fan['uid'];
	$insert = array(
			'couponid' => $id,
			'uid' => $uid,
			'uniacid' => $_W['uniacid'],
			'openid' => $fan['openid'],
			'code' => $code,
			'grantmodule' => $give ? $_W['activity_coupon_id'] : $_W['current_module']['name'],
			'addtime' => TIMESTAMP,
			'status' => 1,
			'remark' => $give ? '系统赠送' : '用户使用' . $coupon['exchange']['credit'] . $credit_names[$coupon['exchange']['credittype']] . '兑换'
	);
	if ($coupon['source'] == 2) {
		$insert['card_id'] = $coupon['card_id'];
		$insert['code'] = '';
	}
	$arr = pdo_insert('coupon_record', $insert);
	pdo_update('coupon', array('quantity' => $coupon['quantity'] - 1, 'dosage' => $coupon['dosage'] +1), array('uniacid' => $_W['uniacid'],'id' => $coupon['id']));
	return true;
}

function we7_coupon_activity_get_member($type, $param = array()) {
	activity_coupon_type_init();
	global $_W;
	$types =  array('new_member', 'old_member', 'quiet_member', 'activity_member', 'group_member', 'cash_time', 'openids');
	if (!in_array($type, $types)) {
		return error('1', '没有匹配的用户类型');
	}
	$propertys = activity_member_propertys();
	$openids = pdo_getall('mc_mapping_fans', array('openid <>' => '', 'uniacid' => $_W['uniacid'], 'uid <>' => ''), array('uid', 'openid'), 'uid');
	$uids = array_keys($openids);
	if ($type == 'new_member') {
		$property_time = strtotime('-' . $propertys['newmember'] . ' month', time());
		$members_sql = "SELECT c.openid FROM ( SELECT a.uid FROM ". tablename('mc_members')." as a LEFT JOIN ".tablename('mc_cash_record')." as b ON a.uid = b.uid WHERE a.uniacid = :uniacid AND a.createtime > :time AND (b.createtime > :time or b.id is null) GROUP BY a.uid HAVING COUNT(*) < 2) as d  LEFT JOIN ". tablename('mc_mapping_fans')." as c ON d.uid = c.uid WHERE c.openid <> ''";
		$members = pdo_fetchall($members_sql, array(':uniacid' => $_W['uniacid'], ':time' => $property_time), 'openid');
	}
	if ($type == 'old_member') {
		$property_time = strtotime('-' . $propertys['oldmember'] . ' month', time());
		$members = pdo_fetchall("SELECT b.openid FROM ".tablename('mc_members')." as a LEFT JOIN ". tablename('mc_mapping_fans')." as b ON a.uid = b.uid WHERE a.createtime < :time AND a.uniacid = :uniacid AND b.openid <> ''", array(':time' => $property_time, ':uniacid' => $_W['uniacid']), 'openid');
	}
	if ($type == 'activity_member') {
		$property_time = strtotime('-' . $propertys['activitymember'] . ' month', time());
		$cash_records = pdo_fetchall("SELECT COUNT(*) AS total, uid, createtime FROM " . tablename('mc_cash_record') . " WHERE uid IN (" . implode(',', $uids) . ") GROUP BY uid", array(), 'uid');
		foreach ($cash_records as $uid => $record) {
			if ($record['total'] >= 2 && $record['createtime'] > $property_time) {
				$activity_member[$uid] = $record;
			}
		}
		foreach ($activity_member as $uid => $member) {
			$members[$openids[$uid]['openid']] = $openids[$uid]['openid'];
		}
		unset($member);
	}
	if ($type == 'quiet_member') {
		$property_time = strtotime('-' . $propertys['quietmember'] . ' month', time());
		$cash_records = pdo_fetchall("SELECT COUNT(*) AS total, uid, createtime FROM " . tablename('mc_cash_record') . " WHERE uid IN (" . implode(',', $uids) . ") GROUP BY uid", array(), 'uid');
		foreach ($uids as $uid) {
			if (empty($cash_records[$uid])) {
				$quiet_member[$uid]['uid'] = $uid;
			}
		}
		foreach ($quiet_member as $uid => $member) {
			$members[$openids[$uid]['openid']] = $openids[$uid]['openid'];
		}
	}

	if ($type == 'group_member') {
		if (empty($param)) {
			return error(1, '请选择会员组');
		}
		if (COUPON_TYPE == WECHAT_COUPON) {
			$members =  pdo_getall('mc_mapping_fans', array('uniacid' => $_W['uniacid']), array(), 'openid');
			foreach ($members as $key => &$fan) {
				$fan['groupid'] = explode(',', $fan['groupid']);
				if (!is_array($fan['groupid']) || !in_array($param['groupid'], $fan['groupid'])) {
					unset($members[$key]);
				}
			}
		} else {
			$members = pdo_fetchall('SELECT b.openid FROM '.tablename('mc_members')." as a LEFT JOIN ". tablename('mc_mapping_fans')." as b ON a.uid  = b.uid WHERE a.groupid  = :groupid AND a.uniacid = :uniacid AND b.openid <> ''", array(':groupid' => $param['groupid'], ':uniacid' => $_W['uniacid']), 'openid');
		}
	}
	if ($type == 'cash_time') {
		$members = pdo_fetchall("SELECT a.openid FROM ". tablename('mc_mapping_fans')." as a LEFT JOIN ".tablename('mc_cash_record')." as b ON a.uid = b.uid WHERE a.uniacid = :uniacid AND b.createtime >= :start AND b.createtime <= :end GROUP BY a.openid", array(':uniacid' => $_W['uniacid'], ':start' => $param['start'], ':end' => $param['end']), 'openid');
	}
	if ($type == 'openids') {
		$members = json_decode($_COOKIE['fans_openids'.$_W['uniacid']]);
	}

	if (is_array($members)) {
		$member = $type == 'openids' ? $members : array_keys($members);
		$members = array();
		$members['members'] = $member;
		$members['total'] = count($members['members']);
	} else {
		$members = array();
	}
	return $members;
}

function we7_coupon_activity_coupon_give() {
	global $_W;
	$openid = $_W['openid'];
	if (!empty($openid)) {
		$member = array();
		$new_members = we7_coupon_activity_get_member('new_member');
		if (!empty($new_members['members'])) {
			$member['is_newmember'] = in_array($openid, $new_members['members'])? 'new_member' : '';
		} else {
			$member['is_newmember'] = '';
		}
		$old_members = we7_coupon_activity_get_member('old_member');
		if (!empty($old_members['members'])) {
			$member['is_oldmember'] = in_array($openid, $old_members['members'])? 'old_member' : '';
		} else {
			$member['is_oldmember'] = '';
		}
		$quiet_members = we7_coupon_activity_get_member('quiet_member');
		if (!empty($quiet_members['members'])) {
			$member['is_quietmember'] = in_array($openid, $quiet_members['members'])? 'quiet_member' : '';
		} else {
			$member['is_quietmember'] = '';
		}
		$activity_members = we7_coupon_activity_get_member('activity_member');
		if (!empty($activity_members['members'])) {
			$member['is_activitymember'] = in_array($openid, $activity_members['members'])? 'activity_member' : '';
		} else {
			$member['is_activitymember'] = '';
		}
	} else {
		$member = array();
	}
	$coupon_activitys = pdo_getall('coupon_activity', array('uniacid' => $_W['uniacid'], 'type' => 1, 'status' => 1));
	foreach ($coupon_activitys as $activity) {
		$is_give = pdo_get('coupon_record', array('grantmodule' => $activity['id'], 'remark' => '系统赠送'));
		if (!empty($is_give)) {
			continue;
		}
		$activity['members'] = empty($activity['members']) ? array() : iunserializer($activity['members']);
				if (in_array('group_member', $activity['members'])) {
			$groupid = pdo_fetchcolumn("SELECT groupid FROM ". tablename('mc_members')." WHERE uniacid = :uniacid AND uid = :uid", array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid']));
			if ($groupid == $activity['members']['groupid']) {
				$member['is_groupmember'] = 'group_member';
			}
		}
		if (in_array('cash_time', $activity['members'])) {
			$cash_member = pdo_fetch("SELECT * FROM " . tablename('mc_cash_record') . " WHERE uniacid = :uniacid AND uid = :uid AND createtime > :start AND createtime < :end", array(':uniacid' => $_W['uniacid'], ':uid' => $_W['member']['uid'], ':start' => strtotime($activity['members']['start']), ':end' => strtotime($activity['members']['start'])));
			if (!empty($cash_member)) {
				$member['is_cashtime'] = 'cash_time';
			}
		}
		if (in_array('openids', $activity['members'])) {
			$fan = pdo_get('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
			$openid = $_W['openid'];
			if (in_array($openid, $activity['members']['openids'])) {
				$member['is_openids'] = 'openids';
			}
		}
		if (array_intersect($activity['members'], $member)) {
			$activity['coupons'] = empty($activity['coupons']) ? array() : iunserializer($activity['coupons']);
			foreach ($activity['coupons'] as $id){
				$coupon = activity_coupon_info($id);
				if(is_error($coupon)){
					continue;
				}
				$_W['activity_coupon_id'] = $activity['id'];
				$ret = we7_coupon_activity_coupon_grant($id, $_W['member']['uid']);
				unset($_W['activity_coupon_id']);
				if(is_error($ret)) {
					continue;
				}
			}
			unset($id);
		}
	}
	unset($activity);
}

function we7_coupon_activity_coupon_owned() {
	global $_W, $_GPC;
	$uid = $_W['member']['uid'];
	$param = array('uniacid' => $_W['uniacid'], 'openid' => $_W['openid'], 'status' => 1);
	$data = pdo_getall('coupon_record', $param);
	foreach ($data as $key => $record) {
		$coupon = activity_coupon_info($record['couponid']);
		if ($coupon['source'] != COUPON_TYPE) {
			unset($data[$key]);
			continue;
		}
		if ($coupon['status'] != '3') {
			pdo_delete('coupon_record', array('id' => $record['id']));
			unset($data[$key]);
			continue;
		}
		if (is_error($coupon)) {
			unset($data[$key]);
			continue;
		}
		$modules = array();
		if (!empty($coupon['modules'])) {
			foreach ($coupon['modules'] as $module) {
				$modules[] = $module['name'];
			}
		}
		if (!empty($modules) && !in_array($_W['current_module']['name'], $modules) && !empty($_W['current_module']['name']) && $_W['current_module']['name'] != 'we7_coupon') {
			unset($data[$key]);
			continue;
		}
		if (is_array($coupon['date_info']) && $coupon['date_info']['time_type'] == '2') {
			$starttime = $record['addtime'] + $coupon['date_info']['deadline'] * 86400;
			$endtime = $starttime + ($coupon['date_info']['limit'] - 1) * 86400;
			if ($endtime < time()) {
				unset($data[$key]);
				pdo_delete('coupon_record', array('id' => $record['id']));
				continue;
			} else {
				$coupon['extra_date_info'] = '有效期:' . date('Y.m.d', $starttime) . '-' . date('Y.m.d', $endtime);
			}
		}
		if (is_array($coupon['date_info']) && $coupon['date_info']['time_type'] == '1') {
			$endtime = str_replace('.', '-', $coupon['date_info']['time_limit_end']);
			$endtime = strtotime($endtime);
			if ($endtime < time()) {
				pdo_delete('coupon_record', array('id' => $record['id']));
				unset($data[$key]);
				continue;
			}

		}
		if ($coupon['type'] == COUPON_TYPE_DISCOUNT) {
			$coupon['icon'] = '<div class="price">' . $coupon['extra']['discount'] * 0.1 . '<span>折</span></div>';
		}
		elseif($coupon['type'] == COUPON_TYPE_CASH) {
			$coupon['icon'] = '<div class="price">' . $coupon['extra']['reduce_cost'] * 0.01 . '<span>元</span></div><div class="condition">满' . $coupon['extra']['least_cost'] * 0.01 . '元可用</div>';
		}
		elseif($coupon['type'] == COUPON_TYPE_GIFT) {
			$coupon['icon'] = '<img src="resource/images/wx_gift.png" alt="" />';
		}
		elseif($coupon['type'] == COUPON_TYPE_GROUPON) {
			$coupon['icon'] = '<img src="resource/images/groupon.png" alt="" />';
		}
		elseif($coupon['type'] == COUPON_TYPE_GENERAL) {
			$coupon['icon'] = '<img src="resource/images/general_coupon.png" alt="" />';
		}
		$data[$key] = $coupon;
		$data[$key]['recid'] = $record['id'];
		$data[$key]['code'] = $record['code'];
		if ($coupon['source'] == '2') {
			if (empty($data[$key]['code'])) {
				$data[$key]['extra_ajax'] = url('entry', array('m' => 'we7_coupon', 'do' => 'activity', 'type' => 'coupon', 'op' => 'addcard'));
			} else {
				$data[$key]['extra_ajax'] = url('entry', array('m' => 'we7_coupon', 'do' => 'activity', 'type' => 'coupon', 'op' => 'opencard'));
			}
		}
	}
	return $data;
}

function we7_coupon_activity_paycenter_coupon_available() {
	$coupon_owned = we7_coupon_activity_coupon_owned();
	foreach ($coupon_owned as $key => &$val) {
		if (empty($val['code'])) {
			unset($val);
		}
		if ($val['type'] == '1' || $val['type'] == '2') {
			$coupon_available[$val['id']] = $val;
		}
	}
	return $coupon_available;
}

function we7_coupon_activity_store_sync() {
	global $_W;
	load()->classs('coupon');
	$cachekey = "storesync:{$_W['uniacid']}";
	$cache = cache_load($cachekey);
	if (!empty($cache) && $cache['expire'] > time()) {
		return false;
	}
	$stores = pdo_getall('activity_stores', array('uniacid' => $_W['uniacid'], 'source' => 2));
	foreach ($stores as $val) {
		if ($val['status'] == 3) {
			continue;
		}
		$coupon_api = new coupon($_W['acid']);
		$location = $coupon_api->LocationGet($val['location_id']);
		if(is_error($location)) {
			return error(-1, $location['message']);
		}
		$location = $location['business']['base_info'];
		$status2local = array('', 3, 2, 1, 3);
		$location['status'] = $status2local[$location['available_state']];
		$location['location_id'] = $location['poi_id'];
		$category_temp = explode(',', $location['categories'][0]);
		$location['category'] = iserializer(array('cate' => $category_temp[0], 'sub' => $category_temp[1], 'clas' => $category_temp[2]));
		$location['photo_list'] = iserializer($location['photo_list']);
		unset($location['categories'], $location['poi_id'], $location['update_status'], $location['available_state'], $location['offset_type'], $location['sid'], $location['type'], $location['qualification_list']);
		pdo_update('activity_stores', $location, array('uniacid' => $_W['uniacid'], 'id' => $val['id']));
	}
	cache_write($cachekey, array('expire' => time() + 1800));
	return true;
}