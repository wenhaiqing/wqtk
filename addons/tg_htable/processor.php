<?php
/**
 * 汇报表模块处理程序
 *
 * @author tg
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Tg_htableModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
	
		global $_GPC , $_W;
		
		$list = pdo_fetchall("SELECT * FROM `tgnet_htb_settings` WHERE status = :status",array(":status"=>1));
		if(preg_match('/htb@[1-9]+[0-9]*/',$content)){
			$local = $this->createMobileUrl('senddata');
			$local = (substr($local,0,-1) == '&') ? $local : $local.'&';
			
			$id = intval(ltrim($content,'htb@'));
			if($id < 0 || $id > count($list)){
				return $this->respText('不存在该报表！');
			}

			$news = array(
				'Title' => $list[($id-1)]['name'],
				'Description' => '请点击进入报表页面',
				'PicUrl' => '',
				'Url' => $local.'totable='.$list[$id-1]['id'],
				);
			return $this->respNews($news);
		}else{
			$txt = "请回复下列关键字来访问相应的报表\n【报表列表】\n";
			
			foreach($list as $key => $value){
				$txt .= 'htb@'.($key+1).': '.$value['name']."\n";
			}
			return $this->respText($txt);
		}	
	}
}