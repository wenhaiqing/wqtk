<?php

//www.guifox.Com www.guifox.Com

defined('IN_IA') or die('Access Denied');
class junsion_promotionModuleProcessor extends WeModuleProcessor
{
	public function respond()
	{
		global $_W;
		if ($this->message['msgtype'] == 'event') {
			if ($this->message['event'] == 'subscribe') {
				$ticket = $this->message['ticket'];
				if (!empty($ticket)) {
					$info = pdo_fetch('select * from ' . tablename($this->modulename . "_info") . " where ticket='{$ticket}' and status=1");
					return $this->sendPrize($info);
				}
			} elseif ($this->message['event'] == 'SCAN') {
				$cfg = $this->getConfig();
				if ($cfg['news']) {
					foreach ($cfg['news'] as $value) {
						$response[] = array('title' => $value['ntitle'], 'description' => $value['ndesc'], 'picurl' => tomedia($value['nthumb']), 'url' => $this->buildSiteUrl($value['nurl']));
					}
					return $this->respNews($response);
				}
				return '';
			}
		} elseif ($this->message['msgtype'] == 'text') {
			$account = $this->message['content'];
			$info = pdo_fetch('select * from ' . tablename($this->modulename . "_info") . " where account='{$account}' and status=1 and weid='{$_W['uniacid']}'");
			return $this->sendPrize($info);
		}
	}
	private function sendPrize($info)
	{
		global $_W;
		if (!empty($info)) {
			$openid = $this->message['from'];
			$fans = pdo_fetch('select * from ' . tablename($this->modulename . "_fans") . " where openid='{$openid}' and weid='{$_W['uniacid']}'");
			$cfg = $this->getConfig();
			if ($cfg['date']) {
				if ($cfg['date']['starttime'] > time()) {
					return $this->respText($cfg['no_start_tips']);
				} elseif ($cfg['date']['endtime'] < time()) {
					return $this->respText($cfg['end_tips']);
				}
			}
			if ($openid == $info['openid']) {
				return '';
			}
			if (empty($fans) || $fans['hasDel']) {
				load()->model('mc');
				$mc = mc_fetch($openid);
				if (empty($fans)) {
					$time = pdo_fetch('select updatetime from ' . tablename('mc_mapping_fans') . " where uid='{$mc['uid']}'");
					if (empty($cfg['fans_require']) || empty($time['updatetime']) || $time['updatetime'] > $cfg['date']['starttime']) {
						$this->placeLimit($cfg);
						$mm = array('groupid' => $cfg['mgid']);
						if (empty($mc['nickname']) || empty($mc['avatar']) || empty($mc['resideprovince']) || empty($mc['residecity'])) {
							$ACCESS_TOKEN = $this->getAccessToken();
							$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
							load()->func('communication');
							$json = ihttp_get($url);
							$userInfo = @json_decode($json['content'], true);
							if ($userInfo['nickname']) {
								$mc['nickname'] = $userInfo['nickname'];
							}
							if ($userInfo['headimgurl']) {
								$mc['avatar'] = $userInfo['headimgurl'];
							}
							if ($userInfo['province']) {
								$mc['resideprovince'] = $userInfo['province'];
							}
							if ($userInfo['city']) {
								$mc['residecity'] = $userInfo['city'];
							}
							$mm = array_merge(array('nickname' => $mc['nickname'], 'avatar' => $mc['avatar'], 'resideprovince' => $mc['resideprovince'], 'residecity' => $mc['residecity']), $mm);
						}
						mc_update($this->message['from'], $mm);
						pdo_insert($this->modulename . "_fans", array('weid' => $_W['uniacid'], 'nickname' => $mc['nickname'], 'avatar' => $mc['avatar'], 'openid' => $openid, 'pid' => $info['id'], 'createtime' => time()));
						$acc = WeAccount::create($_W['acid']);
						if (!empty($cfg['fgid'])) {
							$data = $acc->updateFansGroupid($openid, $cfg['fgid']);
							if (!is_error($data)) {
								pdo_update('mc_mapping_fans', array('groupid' => $cfg['fgid']), array('uniacid' => $_W['uniacid'], 'acid' => $_W['acid'], 'openid' => $openid));
							}
						}
					} else {
						$old_fans = true;
					}
				} else {
					pdo_update($this->modulename . "_fans", array('hasDel' => 0), array('id' => $fans['id']));
				}
				if (!$old_fans) {
					$credit = $cfg['credit'];
					if (empty($credit)) {
						$credit = 'credit1';
					}
					$p = mc_fetch($info['openid'], array('uid'));
					if ($cfg['invite_score'] > 0) {
						mc_credit_update($p['uid'], $credit, $cfg['invite_score'], array($p['uid'], '推广奖励'));
					}
					if ($cfg['invite_text']) {
						$a = $this->getFansNum($info['id']);
						$today = $a['today'];
						$all = $a['all'];
						$p = mc_fetch($info['openid'], array($credit));
						$str = str_replace('#昵称#', $mc['nickname'], $cfg['invite_text']);
						$str = str_replace('#今天#', $today, $str);
						$str = str_replace('#总数#', $all, $str);
						if ($cfg['invite_score'] > 0) {
							$str = str_replace('#奖励#', $cfg['invite_score'], $str);
							$str = str_replace('#总分#', $p[$credit], $str);
						}
						$this->sendText($info['openid'], $str);
					}
					if ($cfg['push_text']) {
						$str = str_replace('#昵称#', $mc['nickname'], $cfg['push_text']);
						$str = str_replace('#推荐#', $info['nickname'], $str);
						$str = str_replace('#工号#', $info['account'], $str);
						$str = str_replace('#手机#', $info['mobile'], $str);
						if ($cfg['cols']) {
							$str = str_replace('#链接#', $this->buildSiteUrl($this->createMobileUrl('info')), $str);
						}
						if ($cfg['news'] || $this->message['checked']) {
							$this->sendText($openid, $str);
						} else {
							return $this->respText($str);
						}
					}
				}
			}
			if ($cfg['news']) {
				foreach ($cfg['news'] as $value) {
					$str = str_replace('#昵称#', $mc['nickname'], $value['ntitle']);
					$str = str_replace('#推荐#', $info['nickname'], $str);
					$response[] = array('title' => $str, 'description' => $value['ndesc'], 'picurl' => tomedia($value['nthumb']), 'url' => $this->buildSiteUrl($value['nurl']));
				}
				if ($this->message['checked'] == 'checked') {
					$this->sendNews($this->message['from'], $response);
					return '';
					die;
				}
				return $this->respNews($response);
			}
		}
	}
	public function sendNews($openid, $response)
	{
		$data = array("touser" => $openid, "msgtype" => "news", "news" => array("articles" => $response));
		$ret = $this->sendRes($this->getAccessToken(), $this->json_encode2($data));
		return $ret;
	}
	private function json_encode2($arr)
	{
		array_walk_recursive($arr, function (&$item, $key) {
			if (is_string($item)) {
				$item = mb_encode_numericentity($item, array(128, 65535, 0, 65535), 'UTF-8');
			}
		});
		return mb_decode_numericentity(json_encode($arr), array(128, 65535, 0, 65535), 'UTF-8');
	}
	private function getFansNum($sid = '')
	{
		global $_W;
		if ($sid) {
			$con = " and pid='{$sid}'";
		}
		$share = pdo_fetchall('select id,createtime,openid from ' . tablename($this->modulename . "_fans") . " where weid='{$_W['uniacid']}' {$con}", array(), 'openid');
		$cfg = $this->getConfig();
		$unrule = $cfg['un_rule'];
		if (!$unrule) {
			$openids = array_keys($share);
			if (!empty($openids)) {
				$uids = pdo_fetchall('select openid from ' . tablename('mc_mapping_fans') . " where openid in ('" . implode("','", array_keys($share)) . "') and follow=1", array(), 'openid');
			}
		}
		$today = 0;
		foreach ($share as $key => $value) {
			if (empty($uids[$value['openid']]) && !$unrule) {
				unset($share[$key]);
				continue;
			}
			if (date('Ymd') == date('Ymd', $value['createtime'])) {
				$today++;
			}
		}
		$all = count($share);
		return array('today' => $today, 'all' => $all);
	}
	private function getConfig()
	{
		global $_W;
		$config = $this->module['config'];
		$settings = pdo_fetch('select * from ' . tablename($this->modulename . "_config") . " where uniacid='{$_W['uniacid']}'");
		$settings = unserialize($settings['settings']);
		if ($settings) {
			if (empty($config)) {
				return $settings;
			} else {
				return array_merge($config, $settings);
			}
		}
		return $config;
	}
	private function placeLimit($cfg)
	{
		global $_W;
		$city = $cfg['city'];
		if (!empty($city) && !empty($city[0])) {
			file_put_contents(IA_ROOT . "/addons/junsion_promotion/log.txt", date('Y-m-d H:i:s', time()) . " openid：" . $this->message['from'] . " checked：" . $this->message['checked'] . "\n", FILE_APPEND);
			if (empty($this->message['checked'])) {
				$t = time();
				$msg = array('msgtype' => $this->message['msgtype'], 'event' => $this->message['event'], 'content' => $this->message['content'], 'ticket' => $this->message['ticket'], 'sign' => md5('junyi' . $t), 'rid' => $this->rule, 'createtime' => $t, 'limit' => $cfg['limittype']);
				$url = $this->buildSiteUrl($this->createMobileUrl('getip', array('msg' => $msg)));
				$text = str_replace('#链接#', $url, $cfg['checktips']);
				$this->sendText($this->message['from'], $text);
				die;
			}
			$this->calPlace($cfg, array('resideprovince' => $this->message['province'], 'residecity' => $this->message['city']));
		}
	}
	private function calPlace($cfg, $addr)
	{
		$city = $cfg['city'];
		$tips = str_replace('#地址#', $addr['resideprovince'] . $addr['residecity'], $cfg['outtips']);
		$out = true;
		foreach ($city as $value) {
			if ($addr['residecity'] == $value || strstr($value, $addr['residecity']) || strstr($addr['residecity'], $value)) {
				$out = false;
			}
		}
		if ($out) {
			$this->sendText($this->message['from'], $tips);
			die;
		}
	}
	private function getAccessToken()
	{
		global $_W;
		load()->model('account');
		$acid = $_W['acid'];
		if (empty($acid)) {
			$acid = $_W['uniacid'];
		}
		$account = WeAccount::create($acid);
		$token = $account->fetch_available_token();
		return $token;
	}
	public function sendText($openid, $text)
	{
		$post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
		$ret = $this->sendRes($this->getAccessToken(), $post);
		return $ret;
	}
	private function sendRes($access_token, $data)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
		load()->func('communication');
		$ret = ihttp_request($url, $data);
		file_put_contents(IA_ROOT . "/addons/junsion_promotion/log.txt", date('Y-m-d H:i:s', time()) . " send：" . $data . " res：" . $ret['content'] . "\n", FILE_APPEND);
		$content = @json_decode($ret['content'], true);
		return $content['errcode'];
	}
}