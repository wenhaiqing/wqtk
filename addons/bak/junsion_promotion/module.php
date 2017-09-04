<?php
/**
 * 员工推荐系统模块定义
 * 
 * @author junsion 
 * @url http://s.we7.cc/index.php?c=store&a=author&uid=74516
 */
defined('IN_IA') or exit('Access Denied');

class junsion_promotionModule extends WeModule{
    
     public function settingsDisplay($settings){
         global $_W, $_GPC;
         // 点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
        // 在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
        if(checksubmit()){
             $settings['reg_checked'] = $_GPC['reg_checked'];
             $settings['qrtype'] = $_GPC['qrtype'];
             $settings['push_text'] = $_GPC['push_text'];
             $settings['invite_text'] = $_GPC['invite_text'];
             $settings['un_text'] = $_GPC['un_text'];
             $settings['reg_adv'] = $_GPC['reg_adv'];
             $settings['un_rule'] = $_GPC['un_rule'];
             $settings['fans_require'] = $_GPC['fans_require'];
             $settings['credit'] = $_GPC['credit'];
             $settings['acc_name'] = $_GPC['acc_name'];
             $settings['acc_rule'] = $_GPC['acc_rule'];
             $settings['notice'] = $_GPC['notice'];
             $settings['describeurl'] = $_GPC['describeurl'];
             $settings['copyright'] = htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['copyright']), ENT_QUOTES);
             $settings['un_score'] = abs($_GPC['un_score']);
             $settings['invite_score'] = abs($_GPC['invite_score']);
             $settings['checked_text'] = htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['checked_text']), ENT_QUOTES);
             $settings['alength'] = $_GPC['alength'];
             // 字段验证, 并获得正确的数据$settings
            if ($this -> saveSettings($settings)) message('保存成功!', 'refresh');
             }
         load () -> func ('tpl');
         // 这里来展示设置项表单
        include $this -> template('setting');
         }
    
    }
