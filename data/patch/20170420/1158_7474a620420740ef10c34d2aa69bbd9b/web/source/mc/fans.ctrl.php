<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
uni_user_permission_check('mc_fans');
load()->model('mc');
$dos = array('display', 'view', 'initsync', 'tag');
$do = in_array($do, $dos) ? $do : 'display';
if ($do == 'display') {
	$_W['page']['title'] = '粉丝列表 - 粉丝 - 会员中心';
	if (checksubmit('submit')) {
		if (!empty($_GPC['delete'])) {
			$fanids = array();
			foreach ($_GPC['delete'] as $v) {
				$fanids[] = intval($v);
			}
			pdo_delete('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'fanid' => $fanids));
			pdo_delete('mc_fans_tag_mapping', array('fanid' => $fanids));
			message('粉丝删除成功！', url('mc/fans/', array('type' => $_GPC['type'])), 'success');
		}
	}
	$acid = $_W['acid'];
	if ($_W['isajax']) {
		$post = $_GPC['__input'];
		if ($post['method'] == 'sync') {
			if (is_array($post['fanids'])) {
				$fanids = array();
				foreach ($post['fanids'] as $fanid) {
					$fanid = intval($fanid);
					$fanids[] = $fanid;
				}
				$fanids = implode(',', $fanids);
				$sql = 'SELECT `fanid`,`uid`,`openid` FROM ' . tablename('mc_mapping_fans') . " WHERE `acid`='{$acid}' AND `fanid` IN ({$fanids})";
				$ds = pdo_fetchall($sql);
				foreach ($ds as $row) {
										mc_init_fans_info($row);
				}
			}
			message(error(0, 'success'), '', 'ajax');
		}
		if ($post['method'] == 'download') {
			$acc = WeAccount::create($acid);
			if (!empty($post['next'])) {
				$_GPC['next_openid'] = $post['next'];
			} else {
				pdo_update('mc_mapping_fans', array('follow' => 0), array('uniacid' => $_W['uniacid']));
			}
			$fans = $acc->fansAll();
			if (!is_error($fans)) {
				$ret = array();
				if (is_array($fans['fans'])) {
					$count = count($fans['fans']);
					$buffSize = ceil($count / 500);
					for ($i = 0; $i < $buffSize; $i++) {
						$buffer = array_slice($fans['fans'], $i * 500, 500);
						$openids = implode("','", $buffer);
						$openids = "'{$openids}'";
						$sql = 'SELECT `openid`, `uniacid`, `acid` FROM ' . tablename('mc_mapping_fans') . " WHERE `openid` IN ({$openids})";
						$ds = pdo_fetchall($sql, array(), 'openid');
						$repeat_openids = array();
						$sql = '';
						foreach ($buffer as $openid) {
							if (!empty($ds) && !empty($ds[$openid])) {
								if ($ds[$openid]['uniacid'] != $_W['uniacid']) {
									$repeat_openids[] = $openid;
								} else {
									unset($ds[$openid]);
									continue;
								}
							}
							$salt = random(8);
							$sql .= "('{$acid}', '{$_W['uniacid']}', 0, '{$openid}', '{$salt}', 1, 0, ''),";
						}
						if (!empty($repeat_openids)) {
							pdo_delete('mc_mapping_fans', array('openid' => $repeat_openids));
						}
						if (!empty($sql)) {
							$sql = rtrim($sql, ',');
							$sql = 'INSERT INTO ' . tablename('mc_mapping_fans') . ' (`acid`, `uniacid`, `uid`, `openid`, `salt`, `follow`, `followtime`, `tag`) VALUES ' . $sql;
							pdo_query($sql);
						}
						pdo_query("UPDATE " . tablename('mc_mapping_fans') . " SET follow = '1' WHERE `openid` IN ({$openids})");
					}
					$ret['total'] = $fans['total'];
					$ret['count'] = !empty($fans['fans']) ? $count : 0;
					$ret['next'] = !empty($fans['next']) ? $fans['next'] : '';
				} else {
					$ret['total'] = $fans['total'];
					$ret['count'] = 0;
					$ret['next'] = '';
				}
				message(error(0, $ret), '', 'ajax');
			} else {
				message(error(-1, $fans), '', 'ajax');
			}
		}
	}
	$fans_tag = mc_fans_groups(true);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 50;
	
	$params = array(
		':uniacid' => $_W['uniacid'],
		':acid' => $_W['acid'],
	);
	$condition = " WHERE f.`uniacid` = :uniacid AND f.`acid` = :acid";
	
	if ($_GPC['type'] == 'bind') {
		$condition .= " AND f.`uid` > 0";
		$type = 'bind';
	} elseif($_GPC['type'] == 'unbind') {
		$condition .= " AND f.`uid` = 0";
		$type = 'unbind';
	}
	if (!empty($_GPC['nickname'])) {
		$searchmod = intval($_GPC['searchmod']);
		$nickname = $_GPC['nickname'] ? addslashes(trim($_GPC['nickname'])) : '';
		
		if ($searchmod == 1) {
			$condition .= " AND ((f.`nickname` = :nickname) OR (f.`openid` = :openid))";
			$params[':nickname'] = $nickname;
			$params[':openid'] = $nickname;
		} else {
			$condition .= " AND ((f.`nickname` LIKE :nickname) OR (f.`openid` LIKE :openid))";
			$params[':nickname'] = "%{$nickname}%";
			$params[':openid'] = "%{$nickname}%";
		}
	}
	if (!empty($_GPC['uid'])) {
		$condition .= " AND f.uid = :uid ";
		$params[':uid'] = intval($_GPC['uid']);
	}
	if (!empty($_GPC['time']['start'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$endtime = !empty($endtime) ? $endtime + 86399 : 0;
		
		if (!empty($starttime)) {
			$params[':starttime'] = $starttime;
		}
		if (!empty($endtime)) {
			$params[':endtime'] = $endtime;
		}
	}
	$follow = intval($_GPC['follow']) ? intval($_GPC['follow']) : 1;
	if ($follow == 1) {
		$orderby = " ORDER BY f.`fanid` DESC";
		$condition .= " AND f.`follow` = 1";
		if (!empty($starttime)) {
			$condition .= " AND f.`followtime` >= :starttime AND f.`followtime` <= :endtime";
		}
	} elseif ($follow == 2) {
		$orderby = " ORDER BY f.`unfollowtime` DESC";
		$condition .= " AND f.`follow` = 0";
		if (!empty($starttime)) {
			$condition .= " AND f.`followtime` >= :starttime AND f.`followtime` <= :endtime";
		}
	}
	if (!empty($_GPC['tag_selected_id'])) {
		$join_tag_sql = " LEFT JOIN ".tablename('mc_fans_tag_mapping')." AS m ON m.`fanid` = f.`fanid`";
		$condition .= " AND m.`tagid` = :tagid GROUP BY f.`fanid`";
		$params[':tagid'] = $tag_selected_id = intval($_GPC['tag_selected_id']);
	}
	$list = pdo_fetchall("SELECT f.* FROM " .tablename('mc_mapping_fans')." AS f" . $join_tag_sql . $condition . $orderby . " LIMIT " . ($pindex - 1) * $psize . "," . $psize, $params);
	if (!empty($list)) {
		foreach ($list as &$v) {
			$v['tag_show'] = mc_show_tag($v['groupid']);
			$v['groupid'] = trim($v['groupid'], ',');
			if (!empty($v['uid'])) {
				$user = mc_fetch($v['uid'], array('realname', 'nickname', 'mobile', 'email', 'avatar'));
			}
			if (!empty($v['tag']) && is_string($v['tag'])) {
				if (is_base64($v['tag'])) {
					$v['tag'] = base64_decode($v['tag']);
				}
								if (is_serialized($v['tag'])) {
					$v['tag'] = @iunserializer($v['tag']);
				}
				if (!empty($v['tag']['headimgurl'])) {
					$v['tag']['avatar'] = tomedia($v['tag']['headimgurl']);
				}
			}
			if (empty($v['tag'])) {
				$v['tag'] = array();
			}

			if (!empty($user)) {
				$niemmo = $user['realname'];
				if (empty($niemmo)) {
					$niemmo = $user['nickname'];
				}
				if (empty($niemmo)) {
					$niemmo = $user['mobile'];
				}
				if (empty($niemmo)) {
					$niemmo = $user['email'];
				}
				if (empty($niemmo) || (!empty($niemmo) && substr($niemmo, -6) == 'we7.cc' && strlen($niemmo) == 39)) {
					$niemmo_effective = 0;
				} else {
					$niemmo_effective = 1;
				}
				$v['user'] = array('niemmo_effective' => $niemmo_effective, 'niemmo' => $niemmo, 'nickname' => $user['nickname']);
			}
			if (empty($v['user']['nickname']) && !empty($v['tag']['nickname'])) {
				$v['user']['nickname'] = $v['tag']['nickname'];
			}
			if (empty($v['user']['avatar']) && !empty($v['tag']['avatar'])) {
				$v['user']['avatar'] = $v['tag']['avatar'];
			}
			unset($user,$niemmo,$niemmo_effective);
		}
	}
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " .tablename('mc_mapping_fans')." AS f"  . $join_tag_sql . $condition, $params);
	$pager = pagination($total, $pindex, $psize);
	$fans['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid = :uniacid AND acid = :acid AND follow = 1', array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid']));
}

if ($do == 'view') {
	$_W['page']['title'] = '粉丝详情 - 粉丝 - 会员中心';
	$fanid = floatval($_GPC['id']);
	if (empty($fanid)) {
		message('访问错误.', '', 'error');
	}
	$row = pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE fanid = :fanid AND uniacid = :uniacid LIMIT 1", array(':fanid' => $fanid,':uniacid' => $_W['uniacid']));
	$account = WeAccount::create($row['acid']);
	$accountInfo = $account->fetchAccountInfo();
	$row['account'] = $accountInfo['name'];
	if (!empty($row['uid'])) {
		$user = mc_fetch($row['uid'], array('nickname', 'mobile', 'email'));
		$row['user'] = $user['nickname'];
		if (empty($row['user'])) {
			$row['user'] = $user['mobile'];
		}
		if (empty($row['user'])) {
			$row['user'] = $user['email'];
		}
		if (!empty($row['user']) && substr($row['user'], -6) == 'we7.cc' && strlen($row['user']) == 39) {
			$row['user'] = "用户uid：{$row['uid']}。昵称,手机号,邮箱尚未完善";
		}
	} else {
		$row['user'] = '还未登记为会员';
	}
}

if ($do == 'initsync') {
	$acid = intval($_W['acid']);
	if (intval($_GPC['page']) == 0) {
		message('正在更新粉丝数据,请不要关闭浏览器', url('mc/fans/initsync', array('page' => 1, 'acid' => $acid)), 'success');
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 50;
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND acid = :acid AND follow = '1'", array(':uniacid' => $_W['uniacid'], ':acid' => $acid));
	$total_page = ceil($total / $psize);
	$ds = pdo_fetchall("SELECT * FROM ".tablename('mc_mapping_fans') ." WHERE uniacid = :uniacid AND acid = :acid AND follow = '1' ORDER BY `fanid` DESC LIMIT ".($pindex - 1) * $psize.','.$psize, array(':uniacid' => $_W['uniacid'], ':acid' => $acid));
	if (!empty($ds)) {
		foreach($ds as $row) {
			mc_init_fans_info($row);
		}
	}
	$pindex++;
	$log = ($pindex - 1) * $psize;
	if ($pindex > $total_page) {
		message('粉丝数据更新完成', url('mc/fans'), 'success');
	} else {
		message('正在更新粉丝数据,请不要关闭浏览器,已完成更新 ' . $log . ' 条数据。', url('mc/fans/initsync', array('page' => $pindex, 'acid' => $acid)));
	}
}

if ($do == 'tag' && $_W['isajax']) {
	$acc = WeAccount::create($_W['acid']);
	$post = $_GPC['__input'];
		if ($post['batch_tagging']) {
		$batch_data = (array) $post['batch_data'];
		$batch_tagids = (array) $post['batch_tagids']; 
		if (empty($batch_data) || empty($batch_tagids)) {
			message(error(-1, '标签或粉丝参数异常'), '', 'ajax');
		}
				foreach ($batch_tagids as $tagid) {
			$add_openid_list = $add_fanid_list = array();
			foreach ($batch_data as $batch_info) {
				if (!in_array($tagid, explode(',', $batch_info['tagids']))) {
					$add_openid_list[] = $batch_info['openid'];
					$add_fanid_list[] = $batch_info['fanid'];
				}
			}
			if (empty($add_openid_list)) {
				continue;
			}
			$data = $acc->fansTagBatchTagging($add_openid_list, $tagid);
			if (is_error($data)) {
				message(error(-1, $data['message']), '', 'ajax');
			} else {
				mc_batch_insert_fanstag_mapping($add_fanid_list, $tagid);
			}
		}
				foreach ($batch_data as $batch_info) {
			$fanid = $batch_info['fanid'];
			$tagid_arr = array_unique(array_merge(explode(',', $batch_info['tagids']), $batch_tagids));
			sort($tagid_arr, SORT_NATURAL);
			$groupid = ',' . implode(',', $tagid_arr) . ',';
			pdo_update('mc_mapping_fans', array('groupid' => $groupid), array('fanid' => $fanid));
		}
		message(error(0, 'success'), '', 'ajax');
	}

	$openid = trim($post['openid']);
	if (empty($openid)) {
		message(error(-1, '粉丝openid错误'), '', 'ajax');
	}
		if ($post['fetch']) {
		$data = $acc->fansTagFetchOwnTags($openid);
		if (is_error($data)) {
			message(error(-1, $data['message']), '', 'ajax');
		} else {
			message(error(0, array('tagids' => $data['tagid_list'])), '', 'ajax');
		}
	}
		if ($post['update']) {
		$fanid = trim($post['fanid']);
		if (empty($fanid)) {
			message(error(-1, '粉丝id错误'), '', 'ajax');
		}
		$del_tagids = $post['del_tagids'] ? $post['del_tagids'] : array();
		$add_tagids = $post['add_tagids'] ? $post['add_tagids'] : array();
		$origin_tagids = $post['origin_tagids'] ? $post['origin_tagids'] : array();
		if (!empty($del_tagids)) {
			foreach ($del_tagids as $del_tagid) {
				$data = $acc->fansTagBatchUntagging($openid, $del_tagid);
				if (is_error($data)) {
					message(error(-1, $data['message']), '', 'ajax');
				} else {
					pdo_delete('mc_fans_tag_mapping', array('fanid' => $fanid, 'tagid' => $del_tagid));
				}
			}
		}
		if (!empty($add_tagids)) {
			foreach ($add_tagids as $add_tagid) {
				$data = $acc->fansTagBatchTagging($openid, $add_tagid);
				if (is_error($data)) {
					message(error(-1, $data['message']), '', 'ajax');
				} else {
					mc_insert_fanstag_mapping($fanid, $add_tagid);
				}
			}
		}
		$groupid = '';
		$tagid_arr = array_merge($origin_tagids, $add_tagids);
		if (!empty($tagid_arr)) {
			sort($tagid_arr, SORT_NATURAL);
			$tagids = join(',', $tagid_arr);
			$groupid = ',' . $tagids . ',';
		}
		pdo_update('mc_mapping_fans', array('groupid' => $groupid), array('fanid' => $fanid));

		$fans_tag = mc_fans_groups();
		$tag_show = mc_show_tag($groupid);
		message(error(0, array('tag_show' => $tag_show, 'tagids' => $tagids)), '', 'ajax');
	}
}

template('mc/fans');