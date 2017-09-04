<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$ms = array();
$ms['platform'][] =  array(
	'title' => '基本功能',
	'permission_name' => 'platform_basic_function',
	'items' => array(
		array(
			'title' => '文字回复',
			'url' => url('platform/reply', array('m' => 'basic')),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>', 
				'url' => url('platform/reply/post', array('m' => 'basic'))
			),
			'permission_name' => 'platform_reply_basic'
		),
		array(
			'title' => '图文回复',
			'url' => url('platform/reply', array('m' => 'news')),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>', 
				'url' => url('platform/reply/post', array('m' => 'news')),
			),
			'permission_name' => 'platform_reply_news'
		),
		array(
			'title' => '音乐回复',
			'url' => url('platform/reply', array('m' => 'music')),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>', 
				'url' => url('platform/reply/post', array('m' => 'music'))
			),
			'permission_name' => 'platform_reply_music'
		),
		array(
			'title' => '图片回复',
			'url' => url('platform/reply', array('m' => 'images')),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>',
				'url' => url('platform/reply/post', array('m' => 'images'))
			),
			'permission_name' => 'platform_reply_images'
		),
		array(
			'title' => '语音回复',
			'url' => url('platform/reply', array('m' => 'voice')),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>',
				'url' => url('platform/reply/post', array('m' => 'voice'))
			),
			'permission_name' => 'platform_reply_voice'
		),
		array(
			'title' => '视频回复',
			'url' => url('platform/reply', array('m' => 'video')),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>',
				'url' => url('platform/reply/post', array('m' => 'video'))
			),
			'permission_name' => 'platform_reply_video'
		),
		array(
			'title' => '微信卡券回复',
			'url' => url('platform/reply', array('m' => 'wxcard')),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>',
				'url' => url('platform/reply/post', array('m' => 'wxcard'))
			),
			'permission_name' => 'platform_reply_wxcard'
		),
		array(
			'title' => '自定义接口回复',
			'url' => url('platform/reply', array('m' => 'userapi')),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>', 
				'url' => url('platform/reply/post', array('m' => 'userapi')),
			),
			'permission_name' => 'platform_reply_userapi'
		),
		array(
			'title' => '系统回复',
			'url' => url('platform/special/display'),
			'permission_name' => 'platform_reply_system'
		),
	)
);
$ms['platform'][] =  array(
	'title' => '高级功能',
	'permission_name' => 'platform_high_function',
	'items' => array(
		array(
			'title' => '常用服务接入',
			'url' => url('platform/service/switch'),
			'permission_name' => 'platform_service'
		),
		array(
			'title' => '自定义菜单',
			'url' => url('platform/menu'),
			'permission_name' => 'platform_menu'
		),
		array(
			'title' => '特殊消息回复',
			'url' => url('platform/special/message'),
			'permission_name' => 'platform_special'
		),
		array(
			'title' => '二维码管理',
			'url' => url('platform/qr'),
			'permission_name' => 'platform_qr'
		),
		array(
			'title' => '多客服接入',
			'url' => url('platform/reply', array('m' => 'custom')),
			'permission_name' => 'platform_reply_custom'
		),
		array(
			'title' => '长链接二维码',
			'url' => url('platform/url2qr'),
			'permission_name' => 'platform_url2qr'
		)
	)
);
$ms['platform'][] =  array(
	'title' => '数据统计',
	'permission_name' => 'platform_stat',
	'items' => array(
		array(
			'title' => '聊天记录',
			'url' => url('platform/stat/history'),
			'permission_name' => 'platform_stat_history'
		),
		array(
			'title' => '回复规则使用情况',
			'url' => url('platform/stat/rule'),
			'permission_name' => 'platform_stat_rule'
		),
		array(
			'title' => '关键字命中情况',
			'url' => url('platform/stat/keyword'),
			'permission_name' => 'platform_stat_keyword'
		),
		array(
			'title' => '参数',
			'url' => url('platform/stat/setting'),
			'permission_name' => 'platform_stat_setting'
		)
	)
);
$ms['site'][] =  array(
	'title' => '微站管理',
	'permission_name' => 'site_manage',
	'items' => array(
		array(
			'title' => '站点管理',
			'url' => url('site/multi/display'),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>',
				'url' => url('site/multi/post'),
			),
			'permission_name' => 'site_multi_display'
		),
		array(
			'title' => '站点添加/编辑',
			'is_permission' => 1,
			'permission_name' => 'site_multi_post'
		),
		array(
			'title' => '站点删除',
			'is_permission' => 1,
			'permission_name' => 'site_multi_del'
		),
		array(
			'title' => '模板管理',
			'url' => url('site/style/template'),
			'permission_name' => 'site_style_template'
		),
		array(
			'title' => '模块模板扩展',
			'url' => url('site/style/module'),
			'permission_name' => 'site_style_module'
		),
	)
);
$ms['site'][] =  array(
	'title' => '特殊页面管理',
	'permission_name' => 'site_special_page',
	'items' => array(
		array(
			'title' => '会员中心',
			'url' => url('site/editor/uc'),
			'permission_name' => 'site_editor_uc'
		),
		array(
			'title' => '专题页面', 
			'url' => url('site/editor/page'),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>',
				'url' => url('site/editor/design'),
			),
			'permission_name' => 'site_editor_page'
		),
	)
);
$ms['site'][] =  array(
	'title' => '功能组件',
	'permission_name' => 'site_widget',
	'items' => array(
		array(
			'title' => '分类设置',
			'url' => url('site/category'),
			'permission_name' => 'site_category'
		),
		array(
			'title' => '文章管理',
			'url' => url('site/article'),
			'permission_name' => 'site_article'
		),
	)
);
$ms['mc'][] = array(
	'title' => '粉丝管理',
	'permission_name' => 'mc_fans_manage',
	'items' => array(
		array(
			'title' => '粉丝分组',
			'url' => url('mc/fangroup'),
			'permission_name' => 'mc_fangroup'
		),
		array(
			'title' => '粉丝',
			'url' => url('mc/fans'),
			'permission_name' => 'mc_fans'
		),
	)
);

$ms['mc'][] = array(
	'title' => '会员中心',
	'permission_name' => 'mc_members_manage',
	'items' => array(
		array(
			'title' => '会员中心关键字',
			'url' => url('platform/cover/mc'),
			'permission_name' => 'platform_cover_mc'
		),
		array(
			'title' => '会员',
			'url' => url('mc/member'),
			'append' => array(
				'title' => '<i class="fa fa-plus"></i>',
				'url' => url('mc/member/add'),
			),
			'permission_name' => 'mc_member'
		),
		array(
			'title' => '会员组',
			'url' => url('mc/group'),
			'permission_name' => 'mc_group'
		),
	)
);
$ms['mc'][] = array(
	'title' => '微信素材&群发',
	'permission_name' => 'material_manage',
	'items' => array(
		array(
			'title' => '素材&群发',
			'url' => url('material/display'),
			'permission_name' => 'material_display',
		),
		array(
			'title' => '定时群发',
			'url' => url('material/mass'),
			'permission_name' => 'material_mass',
		),
	)
);
$ms['mc'][] = array(
	'title' => '统计中心',
	'permission_name' => 'stat_center',
	'items' => array(
		array(
			'title' => '会员积分统计',
			'url' => url('stat/credit1'),
			'permission_name' => 'stat_credit1',
		),
		array(
			'title' => '会员余额统计',
			'url' => url('stat/credit2'),
			'permission_name' => 'stat_credit2',
		),
	)
);

$ms['setting'][] = array(
	'title' => '公众号选项',
	'permission_name' => 'account_setting',
	'items' => array(
		array(
			'title' => '支付参数',
			'url' => url('profile/payment'),
			'permission_name' => 'profile_payment',
		),
		array(
			'title' => '借用 oAuth 权限',
			'url' => url('mc/passport/oauth'),
			'permission_name' => 'mc_passport_oauth',
		),
		array(
			'title' => '借用 JS 分享权限',
			'url' => url('profile/jsauth'),
			'permission_name' => 'profile_jsauth',
		),
		array(
			'title' => '会员字段管理',
			'url' => url('mc/fields'),
			'permission_name' => 'mc_fields',
		),
		array(
			'title' => '微信通知设置',
			'url' => url('mc/tplnotice'),
			'permission_name' => 'mc_tplnotice',
		),
	)
);
$ms['setting'][] = array(
	'title' => '会员及粉丝选项',
	'permission_name' => 'mc_setting',
	'items' => array(
		array(
			'title' => '积分设置',
			'url' => url('mc/credit'),
			'permission_name' => 'mc_credit',
		),
		array(
			'title' => '注册设置',
			'url' => url('mc/passport/passport'),
			'permission_name' => 'mc_passport_passport',
		),
		array(
			'title' => '粉丝同步设置',
			'url' => url('mc/passport/sync'),
			'permission_name' => 'mc_passport_sync',
		),
		array(
			'title' => 'UC站点整合',
			'url' => url('mc/uc'),
			'permission_name' => 'mc_uc',
		),
	)
);
$ms['setting'][] = array(
	'title' => '其他功能选项',
	'items' => array(
			)
);

$ms['ext'][] = array(
	'title' => '管理',
	'items' => array(
		array(
			'title' => '扩展功能管理',
			'url' => url('profile/module'),
			'permission_name' => 'profile_module',
		),
	)
);
return $ms;
