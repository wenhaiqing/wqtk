<?php
defined('IN_IA') or exit('Access Denied');

class junsion_promotionModuleReceiver extends WeModuleReceiver {
	public function receive() {
		if ($this->message['msgtype'] == 'event') {
			if ($this->message['event'] == 'unsubscribe') {
				$openid = $this->message['from'];
				$fans = pdo_fetch('select nickname,pid,id from '.tablename($this->modulename."_fans")." where openid='{$openid}' and hasDel=0");
				file_put_contents(IA_ROOT."/addons/junsion_promotion/un.txt"," /n fans: {$openid} ".json_encode($fans),FILE_APPEND);
				$cfg = $this->module['config'];
				$text = $cfg['un_text'];
				if (!empty($fans) && $text){
					load()->model('mc');
					if (!$fans['nickname']){
						$mc = mc_fetch($openid,array('nickname'));
						$fans['nickname'] = $mc['nickname'];
					}
					$text = str_replace('#昵称#', $fans['nickname'], $text);
					
					$score = $cfg['un_score'];
					$pid = pdo_fetchcolumn('select openid from '.tablename($this->modulename."_info")." where id='{$fans['pid']}'");
					if ($score > 0){
						$credit = $cfg['credit'];
						if (empty($credit)) $credit = 'credit1';
						$m = mc_fetch($pid,array('uid',$credit));
						mc_credit_update($m['uid'],$credit,-$score,array($m['uid'],'粉丝取消关注'));
						$text = str_replace('#奖励#', $score, $text);
						$text = str_replace('#总分#', $m[$credit]-$score, $text);
					}
					$res = $this->sendText($pid, $text);
					file_put_contents(IA_ROOT."/addons/junsion_promotion/un.txt"," /n res: {$openid} ".$res,FILE_APPEND);
					pdo_update($this->modulename."_fans",array('hasDel'=>1),array('id'=>$fans['id']));
					return '';
				}
			}
		}
	}
	
	public function sendText($openid, $text) {
		$post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
		$ret = $this->sendRes($this->getAccessToken(), $post);
		return $ret;
	}
	
	private function sendRes($access_token, $data) {
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
		load()->func('communication');
		$ret = ihttp_request($url, $data);
		return $ret['content'];
	}
	
	private function getAccessToken() {
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
}
