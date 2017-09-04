<?php

defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/junsion_promotion/jun/jun.php';
define('RES', '../addons/junsion_promotion/template/');
class junsion_promotionModuleSite extends WeModuleSite{
    private function getConfig(){ 
        global $_W;
        $config = $this -> module['config'];
        $settings = pdo_fetch('select * from ' . tablename($this -> modulename . "_config") . " where uniacid='{$_W['uniacid']}'");
        $settings = unserialize($settings['settings']);
        if ($settings){
            if (empty($config)) return $settings;
            else{
                return array_merge($config, $settings);
            }
        }
        return $config;
    }
    private function getInfo(){
        global $_W;
        WXLimit();
        $info = $_W['fans'];
        if (empty($info['nickname'])){
            if ($_W['account']['level'] >= 3){
                $openid = $_W['openid'];
                load() -> model('account');
                $acid = $_W['acid'];
                if (empty($acid)){
                    $acid = $_W['uniacid'];
                }
                $account = WeAccount :: create($acid);
                $token = $account -> fetch_available_token();
                $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
                load() -> func('communication');
                $res = ihttp_get($url);
                $res = @json_decode($res['content'], true);
                if ($_W['account']['level'] == 3 && !$res['subscribe']) return '';
                $info = array('nickname' => $res['nickname'], 'avatar' => $res['headimgurl'], 'openid' => $openid, 'follow' => $res['subscribe']);
            }else{
                MSG('限认证号使用!');
            }
        }
        return $info;
    }
    public function doMobileCover(){
        global $_W, $_GPC;
        $fans = $this -> getInfo();
        $openid = $fans['openid'];
        $staff = pdo_fetch('select * from ' . tablename($this -> modulename . "_info") . " where openid='{$openid}'");
        if (empty($staff)){
            header('location:' . $this -> createMobileUrl('reg'));
            exit;
        }
        if ($staff['status'] == 0){
            MSG('审核中，请耐心等待审核结果……', 'close', 'w');
        }
        $nums = $this -> getFansNum($staff['id']);
        $today = $nums['today'];
        $all = $nums['all'];
        $cfg = $this -> getConfig();
        $acc_name = '员工号';
        if (!empty($cfg['acc_name'])) $acc_name = $cfg['acc_name'];
        load() -> model('mc');
        if ($cfg['invite_score'] > 0){
            $m = mc_fetch($openid, array($cfg['credit']));
            $credit = $m[$cfg['credit']];
            if (empty($credit)) $credit = 0;
            $list = pdo_fetch("SELECT creditnames FROM " . tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
            $list = iunserializer($list['creditnames']);
            $creditname = $list[$cfg['credit']]['title'];
        }
        if (empty($staff['avatar'])){
            $f = mc_fansinfo($openid);
            $staff['avatar'] = $f['tag']['avatar'];
        }
        if ($cfg['data'] && $cfg['bg']){
            $qrcode = str_replace('#id#', $staff['id'], IA_ROOT . $this -> POSTER_PATH);
            if (!file_exists($qrcode)){
                $qrcode = $this -> createPoster($staff, $cfg);
            }
            $qrcode = str_replace('#id#', $staff['id'], ".." . $this -> POSTER_PATH);
        }
        include $this -> template('index');
    }
    private function getFansNum($sid = '', $un = false, $starttime = 0, $endtime = 0){
        global $_W;
        if ($sid) $con = " and pid='{$sid}'";
        if (!empty($starttime)){
            $con .= " and createtime>={$starttime}";
        }
        if (!empty($endtime)){
            $con .= " and createtime <= {$endtime}";
        }
        $share = pdo_fetchall('select id,createtime,openid from ' . tablename($this -> modulename . "_fans") . " where weid='{$_W['uniacid']}' {$con}", array(), 'openid');
        $cfg = $this -> getConfig();
        $unrule = $cfg['un_rule'];
        $openids = array_keys($share);
        if (!empty($openids)){
            if (!$unrule){
                $uids = pdo_fetchall('select openid from ' . tablename('mc_mapping_fans') . " where openid in ('" . implode("','", array_keys($share)) . "') and follow=1", array(), 'openid');
            }
            if ($un){
                $uns = pdo_fetchall('select openid from ' . tablename('mc_mapping_fans') . " where openid in ('" . implode("','", array_keys($share)) . "') and follow=0", array(), 'openid');
            }
        }
        $today = 0;
        $today_un = 0;
        $uncount = 0;
        foreach ($share as $key => $value){
            if (!empty($uns[$value['openid']]) && $un){
                if (date('Ymd') == date('Ymd', $value['createtime'])) $today_un++;
                $uncount++;
            }
            if (empty($uids[$value['openid']]) && !$unrule){
                unset($share[$key]);
                continue;
            }
            if (date('Ymd') == date('Ymd', $value['createtime'])) $today++;
        }
        $all = count($share);
        return array('today' => $today, 'all' => $all, 'today_un' => $today_un, 'un' => $uncount);
    }
    public function doWebQrcode(){
        global $_W, $_GPC;
        $staff = pdo_fetch('select * from ' . tablename($this -> modulename . "_info") . " where id='{$_GPC['sid']}'");
        $qrcode = $this -> createQrcode($staff);
        header('Content-type: image/jpeg');
        header("Content-Disposition: attachment; filename='二维码_{$staff['account']}.png'");
        readfile($qrcode);
        exit;
    }
    public function doWebGetFans(){
        global $_W, $_GPC;
        $keyword = $_GPC['keyword'];
        $fans = pdo_fetchall('select nickname,openid,uid from ' . tablename('mc_mapping_fans') . " where nickname like '%{$keyword}%' and uniacid='{$_W['uniacid']}'");
        die(json_encode($fans));
    }
    public function doWebImport(){
        global $_W, $_GPC;
        $cfg = $this -> getConfig();
        if (checksubmit('submit')){
            set_time_limit(0);
            include 'excel/oleread.php';
            include 'excel/excel.php';
            $tmp = $_FILES['file']['tmp_name'];
            if (!empty ($tmp)){
                $file_name = date('Ymdhis') . ".xls";
                if (copy($tmp, $file_name)){
                    $xls = new Spreadsheet_Excel_Reader();
                    $xls -> setOutputEncoding('utf-8');
                    $xls -> read($file_name);
                    for ($i = 2; $i <= $xls -> sheets[0]['numRows']; $i++){
                        $data_values[] = $xls -> sheets[0]['cells'][$i];
                    }
                }
                if (!empty($data_values)){
                    $accounts = array();
                    $openids = array();
                    foreach ($data_values as $value){
                        if (!$cfg['acc_rule']){
                            if (empty($value[1])){
                                message("存在员工未填写工号:{$value[3]}!");
                            }
                            if (in_array($value[1], $accounts)){
                                message("存在重复员工号:{$value[1]}!");
                            }
                            $i = pdo_fetch('select id from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}' and account='{$value[1]}'");
                            if (!empty($i)) message("系统内已存在重复员工号:{$value[1]}!");
                            $accounts[] = $value[1];
                            $openid = $value[2];
                        }else $openid = $value[1];
                        if (in_array($openid, $openids)){
                            message("存在重复openid:{$openid}!");
                        }
                        $i = pdo_fetch('select id from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}' and openid='{$openid}'");
                        if (!empty($i)) message("系统内已存在重复openid:{$openid}!");
                        $openids[] = $openid;
                    }
                    load() -> model('mc');
                    foreach ($data_values as $value){
                        $index = 1;
                        if (!$cfg['acc_rule']){
                            $index++;
                        }
                        $data = array('weid' => $_W['uniacid'], 'openid' => $value[$index], 'realname' => $value[$index + 1], 'mobile' => $value[$index + 2], 'status' => $value[$index + 3], 'createtime' => time(),);
                        if ($data['openid']){
                            $fans = mc_fansinfo($data['openid']);
                            $data['nickname'] = $fans['tag']['nickname'];
                            $data['avatar'] = $fans['tag']['avatar'];
                        }
                        if (empty($cfg['acc_rule'])){
                            $data['account'] = $value[1];
                        }else{
                            $i = pdo_fetchcolumn('select max(account+0) from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}'");
                            if ($i){
                                $data['account'] = $i + 1;
                            }else{
                                $data['account'] = pow(10, intval($cfg['alength']));
                                if (empty($data['account'])) $data['account'] = 1;
                            }
                        }
                        if (pdo_insert($this -> modulename . "_info", $data) === false){
                            message('导入失败！');
                        }else{
                            if ($_W['account']['level'] < 4) $this -> createRuleKeyword($data['account']);
                        }
                    }
                }else message('没有可导入的数据');
                @unlink($file_name);
                message('导入成功!', $this -> createWebUrl('staff'));
            }
            message('请选择文件');
        }
        include $this -> template('import');
    }
    public function doWebAdd(){
        global $_W, $_GPC;
        $cfg = $this -> getConfig();
        $sid = $_GPC['sid'];
        $item = pdo_fetch('select * from ' . tablename($this -> modulename . "_info") . " where id='{$sid}'");
        if (checksubmit('submit')){
            $data = array('weid' => $_W['uniacid'], 'openid' => $_GPC['openid'], 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'status' => $_GPC['status'],);
            if ($data['openid'] && $data['openid'] != $item['openid']){
                load() -> model('mc');
                $fans = mc_fansinfo($data['openid']);
                $data['nickname'] = $fans['tag']['nickname'];
                $data['avatar'] = $fans['tag']['avatar'];
            }
            if ($sid){
                if ($_W['account']['level'] == 4 && !empty($_GPC['account']) && $_GPC['account'] != $item['account']){
                    $i = pdo_fetch('select id from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}' and account='{$_GPC['account']}'");
                    if ($i) message('该工号已注册！');
                    $data['account'] = $_GPC['account'];
                }
                if (pdo_update($this -> modulename . "_info", $data, array('id' => $sid)) === false){
                    message('编辑失败！');
                }else message('编辑成功！', $this -> createWebUrl('staff'));
            }else{
                if (empty($cfg['acc_rule'])){
                    $i = pdo_fetch('select id from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}' and account='{$_GPC['account']}'");
                    if ($i) message('该工号已注册！');
                    $data['account'] = $_GPC['account'];
                }else{
                    $i = pdo_fetchcolumn('select max(account+0) from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}'");
                    if ($i){
                        $data['account'] = $i + 1;
                    }else{
                        $data['account'] = pow(10, intval($cfg['alength']));
                        if (empty($data['account'])) $data['account'] = 1;
                    }
                }
                $data['createtime'] = time();
                if (pdo_insert($this -> modulename . "_info", $data) === false){
                    message('添加失败！');
                }else{
                    if ($_W['account']['level'] < 4) $this -> createRuleKeyword($data['account']);
                    message('添加成功！', $this -> createWebUrl('staff'));
                }
            }
        }
        include $this -> template('add');
    }
    public function doMobileGetQrcode(){
        global $_W, $_GPC;
        $openid = $_GPC['openid'];
        $staff = pdo_fetch('select * from ' . tablename($this -> modulename . "_info") . " where openid='{$openid}'");
        echo $this -> createQrcode($staff);
    }
    public function createQrcode($staff, $is_url = false){
        global $_W;
        if (empty($staff)) return '';
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=";
        if ($staff['ticket']){
            if ($is_url){
                return pdo_fetchcolumn('select url from ' . tablename("qrcode") . " where uniacid='{$_W['uniacid']}' and ticket='{$staff['ticket']}'");
            }
            return $url . $staff['ticket'];
        }
        $cfg = $this -> getConfig();
        $qrtype = $cfg['qrtype'];
        if ($qrtype == 1){
            $qrcode = pdo_fetch('select id from ' . tablename("qrcode") . " where uniacid='{$_W['uniacid']}' and scene_str='" . $this -> modulename . "{$staff['id']}' limit 1");
            if (!empty($qrcode['ticket'])){
                pdo_update($this -> modulename . "_info", array('ticket' => $qrcode['ticket']), array('id' => $staff['id']));
                return $url . $qrcode['ticket'];
            }
            $barcode['action_info']['scene']['scene_str'] = $this -> modulename . $staff['id'];
        }else{
            $sceneid = pdo_fetchcolumn('select qrcid from ' . tablename("qrcode") . " where uniacid='{$_W['uniacid']}' order by qrcid desc limit 1");
            if (empty($sceneid)) $sceneid = 1;
            else $sceneid++;
            $barcode['action_info']['scene']['scene_id'] = $sceneid;
        }
        load() -> model('account');
        $acid = pdo_fetchcolumn('select acid from ' . tablename('account') . " where uniacid={$_W['uniacid']}");
        $uniacccount = WeAccount :: create($acid);
        $time = 0;
        if (!$qrtype){
            $barcode['action_name'] = 'QR_SCENE';
            $barcode['expire_seconds'] = 30 * 24 * 3600;
            $res = $uniacccount -> barCodeCreateDisposable($barcode);
            $time = $barcode['expire_seconds'];
        }else{
            $barcode['action_name'] = 'QR_LIMIT_STR_SCENE';
            $res = $uniacccount -> barCodeCreateFixed($barcode);
        }
        pdo_update($this -> modulename . "_info", array('ticket' => $res['ticket']), array('id' => $staff['id']));
        $qrcode = array('uniacid' => $_W['uniacid'], 'acid' => $acid, 'name' => $this -> modulename, 'keyword' => $this -> modulename, 'ticket' => $res['ticket'], 'expire' => $time, 'createtime' => time(), 'status' => 1, 'url' => $res['url']);
        if ($qrtype == 1){
            $qrcode['scene_str'] = $barcode['action_info']['scene']['scene_str'];
            $qrcode['model'] = 2;
            $qrcode['type'] = 'scene';
        }else{
            $qrcode['qrcid'] = $sceneid;
            $qrcode['model'] = 1;
        }
        pdo_insert('qrcode', $qrcode);
        $rule = pdo_fetch('select * from ' . tablename('rule') . " where uniacid='{$_W['uniacid']}' and name='" . $this -> modulename . "' and module='" . $this -> modulename . "'");
        if (empty($rule)){
            $rule = array('uniacid' => $_W['uniacid'], 'name' => $this -> modulename, 'module' => $this -> modulename, 'status' => 1, 'displayorder' => 254,);
            pdo_insert('rule', $rule);
            $rid = pdo_insertid();
            unset($rule['id']);
            unset($rule['name']);
            $rule['type'] = 1;
            $rule['rid'] = $rid;
            $rule['content'] = $this -> modulename;
            pdo_insert('rule_keyword', $rule);
        }
        if ($is_url) return $qrcode['url'];
        return $url . $res['ticket'];
    }
    public function doMobileReg(){
        global $_W, $_GPC;
        WXLimit();
        $fans = $this -> getInfo();
        $staff = pdo_fetch('select * from ' . tablename($this -> modulename . "_info") . " where openid='{$fans['openid']}'");
        if (!empty($staff)){
            if ($staff['status'] == 1){
                header('location:' . $this -> createMobileUrl('cover'));
                exit;
            }
            MSG('审核中，请耐心等待审核结果……', 'close', 'w');
        }
        $cfg = $this -> getConfig();
        if (checksubmit('submit')){
            if(!empty($_GPC['mobile']) && !preg_match("/^1[34578]\d{9}$/", $_GPC['mobile'])){
                MSG('请填写正确的手机号码');
            }
            if (empty($cfg['acc_rule'])){
                $i = pdo_fetch('select id from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}' and account='{$_GPC['account']}'");
                if ($i) MSG('该工号已注册！');
            }else{
                $i = pdo_fetchcolumn('select max(account+0) from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}'");
                if ($i){
                    $_GPC['account'] = $i + 1;
                }else{
                    $_GPC['account'] = pow(10, intval($cfg['alength']));
                    if (empty($_GPC['account'])) $_GPC['account'] = 1;
                }
            }
            $data = array('weid' => $_W['uniacid'], 'nickname' => $fans['nickname'], 'avatar' => $fans['avatar'], 'openid' => $fans['openid'], 'account' => $_GPC['account'], 'createtime' => time(), 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'],);
            $regc = $cfg['reg_checked'];
            if ($regc) $data['status'] = 1;
            pdo_insert($this -> modulename . "_info", $data);
            if ($regc){
                if ($_W['account']['level'] < 4) $this -> createRuleKeyword($_GPC['account']);
                MSG('提交成功!', $this -> createMobileUrl('cover'), 's');
            }
            MSG('提交成功，请耐心等待审核结果……', 'close', 's');
        }
        if (empty($fans)){
            $rid = pdo_fetchcolumn('select rid from ' . tablename('cover_reply') . " where module='" . $this -> modulename . "' and uniacid='{$_W['uniacid']}'");
            $keyword = pdo_fetchcolumn('select content from ' . tablename('rule_keyword') . " where rid='{$rid}'");
        }
        include $this -> template('reg');
    }
    public function doMobileInfo(){
        global $_W, $_GPC;
        WXLimit();
        $fans = $this -> getInfo();
        $info = pdo_fetch('select * from ' . tablename($this -> modulename . "_fans") . " where openid='{$fans['openid']}'");
        if (empty($info)){
            MSG('请先参与活动！', 'close', 'w');
        }
        if ($info['cols']){
            MSG('你已填写资料！', 'close', 's');
        }
        $cfg = $this -> getConfig();
        if (checksubmit('submit')){
            $data = array();
            foreach ($cfg['cols'] as $k => $value){
                $data[$value['col_name']] = $_GPC['col' . $k];
            }
            pdo_update($this -> modulename . "_fans", array('cols' => serialize($data)), array('id' => $info['id']));
            MSG('提交成功!', 'close', 's');
        }
        include $this -> template('info');
    }
    private function createRuleKeyword($account){
        global $_W, $_GPC;
        $rule = pdo_fetch('select * from ' . tablename('rule') . " where uniacid='{$_W['uniacid']}' and name='" . $this -> modulename . "' and module='" . $this -> modulename . "'");
        if (empty($rule)){
            $rule = array('uniacid' => $_W['uniacid'], 'name' => $this -> modulename, 'module' => $this -> modulename, 'status' => 1, 'displayorder' => 254,);
            pdo_insert('rule', $rule);
            $rid = pdo_insertid();
        }else $rid = $rule['id'];
        $rk = pdo_fetch('select * from ' . tablename('rule_keyword') . " where rid='{$rid}' and content='{$account}'");
        if (empty($rk)){
            unset($rule['id']);
            unset($rule['name']);
            $rule['type'] = 1;
            $rule['rid'] = $rid;
            $rule['content'] = $account;
            pdo_insert('rule_keyword', $rule);
        }
    }
    public function doMobileRecord(){
        global $_W, $_GPC;
        WXLimit();
        $type = $_GPC['type'];
        if ($type == 1){
            $condition = " and to_days(now()) = to_days(from_unixtime(f.createtime))";
        }
        $fans = $this -> getInfo();
        $openid = $fans['openid'];
        $staff = pdo_fetch('select * from ' . tablename($this -> modulename . "_info") . " where openid='{$openid}'");
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $limit = " LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
        $cfg = $this -> getConfig();
        $unrule = $cfg['un_rule'];
        if ($unrule){
            $list = pdo_fetchall('select * from ' . tablename($this -> modulename . "_fans") . " f where pid='{$staff['id']}' {$condition} order by createtime desc {$limit}");
        }else{
            $list = pdo_fetchall('select * from ' . tablename($this -> modulename . "_fans") . " f left join " . tablename('mc_mapping_fans') . " m on m.openid=f.openid where f.pid='{$staff['id']}' {$condition} and m.follow=1 order by createtime desc {$limit}");
        }
        foreach ($list as & $value){
            $value['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
        }
        if ($_W['isajax']){
            if (empty($list)) die('1');
            die(json_encode($list));
        }
        include $this -> template('record');
    }
    public function doMobileRank(){
        global $_W, $_GPC;
        $cfg = $this -> getConfig();
        $unrule = $cfg['un_rule'];
        if (!$unrule){
            $unrule = " left join " . tablename('mc_mapping_fans') . ' m on m.openid=f.openid where f.pid=a.id and m.follow=1';
        }else $unrule = ' where f.pid=a.id ';
        $type = $_GPC['type'];
        if (!$type){
            $condition .= " and to_days(now()) = to_days(from_unixtime(f.createtime))";
        }elseif ($type == 1){
            $condition .= " and date_format(from_unixtime(f.createtime),'%Y-%m')=date_format(now(),'%Y-%m')";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $limit = "LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
        $list = pdo_fetchall('select *,(select count(1) from ' . tablename($this -> modulename . "_fans") . " f {$unrule} {$condition}) num from " . tablename($this -> modulename . "_info") . " a where weid='{$_W['uniacid']}' and a.status=1 order by num desc,createtime {$limit}");
        if ($_W['isajax']){
            if (empty($list)) die('1');
            die(json_encode($list));
        }
        include $this -> template('rank');
    }
    public function doWebDel(){
        global $_W, $_GPC;
        $sid = $_GPC['sid'];
        $info = pdo_fetch('select ticket from ' . tablename($this -> modulename . "_info") . " where id='{$sid}'");
        pdo_delete('qrcode', array('uniacid' => $_W['uniacid'], 'ticket' => $info['ticket'], 'keyword' => $this -> modulename));
        pdo_delete($this -> modulename . "_info", array('id' => $sid));
        pdo_delete($this -> modulename . "_fans", array('pid' => $sid));
        message('删除成功！', $this -> createWebUrl('staff'));
    }
    public function doWebSet(){
        global $_W, $_GPC;
        if(checksubmit()){
            $settings = array();
            $settings['city'] = $_GPC['citys'];
            $settings['limittype'] = $_GPC['limittype'];
            $settings['checktips'] = htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['checktips']), ENT_QUOTES);
            $settings['outtips'] = $_GPC['outtips'];
            $settings['mgid'] = $_GPC['mgid'];
            $settings['fgid'] = $_GPC['fgid'];
            $settings['date'] = $_GPC['date'];
            $settings['bg'] = $_GPC ['bg'];
            $settings['data'] = htmlspecialchars_decode($_GPC ['data']);
            $settings['no_start_tips'] = $_GPC['no_start_tips'];
            $settings['end_tips'] = $_GPC['end_tips'];
            $settings['news'] = array();
            foreach ($_GPC['ntitle'] as $key => $value){
                if (empty($value)) continue;
                $settings['news'][] = array('ntitle' => $value, 'ndesc' => $_GPC['ndesc'][$key], 'nthumb' => $_GPC['nthumb'][$key], 'nurl' => $_GPC['nurl'][$key]);
            }
            $settings['btns'] = array();
            foreach ($_GPC['btitle'] as $key => $value){
                if (empty($value)) continue;
                $settings['btns'][] = array('title' => $value, 'color' => $_GPC['bcolor'][$key], 'icon' => $_GPC['bicon'][$key], 'link' => $_GPC['blink'][$key]);
            }
            $settings['cols'] = array();
            foreach ($_GPC['col_name'] as $key => $value){
                if (empty($value)) continue;
                $settings['cols'][] = array('col_name' => $value, 'col_type' => $_GPC['col_type'][$key], 'col_must' => intval($_GPC['col_must'][$key]));
            }
            $settings['date']['starttime'] = strtotime($_GPC['date']['start']);
            $settings['date']['endtime'] = strtotime($_GPC['date']['end']);
            $s = pdo_fetch('select * from ' . tablename($this -> modulename . "_config") . " where uniacid='{$_W['uniacid']}'");
            if ($s){
                pdo_update($this -> modulename . "_config", array('settings' => serialize($settings)), array('uniacid' => $_W['uniacid']));
            }else{
                pdo_insert($this -> modulename . "_config", array('settings' => serialize($settings), 'uniacid' => $_W['uniacid']));
            }
            message('保存成功!', 'refresh');
        }
        load () -> func ('tpl');
        $settings = pdo_fetch('select * from ' . tablename($this -> modulename . "_config") . " where uniacid='{$_W['uniacid']}'");
        $settings = unserialize($settings['settings']);
        if (!$settings['date']){
            $settings['date'] = array('start' => date('Y-m-d H:i:s'), 'end' => date('Y-m-d H:i:s', time() * 30 * 24 * 3600));
        }
        $data = json_decode(str_replace('&quot;', "'", $settings['data']), true);
        $mgroups = pdo_fetchall('select * from ' . tablename('mc_groups') . " where uniacid='{$_W['uniacid']}' order by isdefault desc");
        $fgroups = pdo_fetch('SELECT * FROM ' . tablename('mc_fans_groups') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
        $fgroups = unserialize($fgroups['groups']) ? unserialize($fgroups['groups']) : array();
        include $this -> template('set');
    }
    private $POSTER_PATH = "/attachment/pro_poster/qrcode_#id#.png";
    public function doWebClear(){
        global $_W;
        $m = pdo_fetchall('select id from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}'");
        foreach ($m as $value){
            @unlink(str_replace('#id#', $value['id'], IA_ROOT . $this -> POSTER_PATH));
        }
        die('1');
    }
    public function doWebStaff(){
        global $_W, $_GPC;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $condition = '';
        $type = $_GPC['type'];
        $keyword = $_GPC['keyword'];
        if ($keyword){
            if ($type == 'account'){
                $condition .= " and account='{$keyword}'";
            }elseif ($type == 'username'){
                $condition .= " and realname like '%{$keyword}%'";
            }elseif ($type == 'tel'){
                $condition .= " and tel like '{$keyword}%'";
            }
        }
        $limit = " LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
        if (checksubmit('export')) $limit = '';
        $list = pdo_fetchall('select * from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}' {$condition} order by status,createtime desc {$limit}");
        load() -> model('mc');
        $token = $_W['setting']['site']['token'];
        $cfg = $this -> getConfig();
        $credit = $cfg['credit'];
        if (empty($credit)) $credit = 'credit1';
        foreach ($list as & $value){
            $res = $this -> getFansNum($value['id'], true);
            $value['count'] = $res['today'];
            $value['all'] = $res['all'];
            if ($cfg['invite_score'] > 0){
                $m = mc_fetch($value['openid'], array($credit));
                $value['score'] = $m[$credit];
            }
            $value['un'] = intval($res['un']);
            $value['tun'] = intval($res['today_un']);
        }
        if (checksubmit('export')){
            $this -> mexport($list, $cfg['invite_score']);
            exit;
        }
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> modulename . '_info') . " where weid='{$_W['uniacid']}' {$condition}");
        $pager = pagination($total, $pindex, $psize);
        include $this -> template('staff');
    }
    private function mexport($list, $score){
        include_once 'excel.php';
        $filename = '推广记录_' . date('YmdHis') . '.csv';
        $exceler = new Jason_Excel_Export();
        $exceler -> charset('UTF-8');
        $exceler -> setFileName($filename);
        $excel_title = array('工号');
        $excel_title[] = '姓名';
        $excel_title[] = '手机号码';
        $excel_title[] = '今天推广数';
        $excel_title[] = '总推广数';
        $excel_title[] = '今天取消数';
        $excel_title[] = '总取消数';
        if ($score > 0) $excel_title[] = '奖励';
        $exceler -> setTitle($excel_title);
        $allsum = 0;
        $all = 0;
        $uns = 0;
        foreach ($list as $val){
            $data = array($val['account']);
            $data[] = $val['realname'];
            $data[] = $val['mobile'];
            $data[] = $val['count'];
            $data[] = $val['all'];
            $data[] = $val['tun'];
            $data[] = $val['un'];
            if ($score > 0) $data[] = $val['score'];
            $excel_data[] = $data;
            $allsum++;
            $uns += $val['un'];
            $all += $val['all'];
        }
        $excel_data[] = array('总人数:', $allsum, '总推广数:', $all, '总取消数:', $uns);
        $exceler -> setContent($excel_data);
        $exceler -> export();
    }
    private function getAccessToken(){
        global $_W;
        load() -> model('account');
        $acid = $_W['acid'];
        if (empty($acid)){
            $acid = $_W['uniacid'];
        }
        $account = WeAccount :: create($acid);
        $token = $account -> fetch_available_token();
        return $token;
    }
    public function sendText($openid, $text){
        $post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
        $ret = $this -> sendRes($this -> getAccessToken(), $post);
        return $ret;
    }
    private function sendRes($access_token, $data){
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        load() -> func('communication');
        $ret = ihttp_request($url, $data);
        $content = @json_decode($ret['content'], true);
        return $content['errcode'];
    }
    public function doWebStatus(){
        global $_GPC, $_W;
        $info = pdo_fetch('select status,openid,realname,account from ' . tablename($this -> modulename . "_info") . " where id='{$_GPC['id']}'");
        if (pdo_query('update ' . tablename($this -> modulename . "_info") . " set status = !status where id='{$_GPC['id']}'") === false) die('0');
        if ($info['status'] == 0){
            $cfg = $this -> getConfig();
            $text = $cfg['checked_text'];
            if ($text){
                $text = str_replace('#员工#', $info['realname'], $text);
                $text = str_replace('#链接#', $_W['siteroot'] . "app" . substr($this -> createMobileUrl('cover'), 1) , htmlspecialchars_decode(str_replace('&quot;', '&#039;', $text), ENT_QUOTES));
                $this -> sendText($info['openid'], $text);
                if ($_W['account']['level'] < 4) $this -> createRuleKeyword($info['account']);
            }
        }
        die('1');
    }
    public function doWebFClear(){
        global $_GPC, $_W;
        if (pdo_delete($this -> modulename . "_fans", array('weid' => $_W['uniacid'])) === false){
            message('清除失败！');
        }else message('清除推广数据成功！', $this -> createWebUrl('staff'));
    }
    public function doWebSClear(){
        global $_GPC, $_W;
        if (pdo_delete($this -> modulename . "_info", array('weid' => $_W['uniacid'])) === false){
            message('清除失败！');
        }else{
            pdo_delete($this -> modulename . "_fans", array('weid' => $_W['uniacid']));
            message('清除员工与推广数据成功！', $this -> createWebUrl('staff'));
        }
    }
    public function doWebFans(){
        global $_W, $_GPC;
        $sid = $_GPC['sid'];
        $con = '';
        $con2 = '';
        if ($sid){
            $con = " and id='{$sid}'";
            $con2 = " and pid='{$sid}'";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $limit = "LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
        $infos = pdo_fetchall('select id,nickname,realname from ' . tablename($this -> modulename . "_info") . " where weid='{$_W['uniacid']}' {$con}", array(), 'id');
        $list = pdo_fetchall('select *,m.followtime,m.follow,m.uid from ' . tablename($this -> modulename . "_fans") . " f left join " . tablename('mc_mapping_fans') . " m on m.openid=f.openid where weid='{$_W['uniacid']}' {$con2} order by createtime desc {$limit}");
        load() -> model('mc');
        foreach ($list as & $value){
            $m = $infos[$value['pid']];
            $value['parent'] = $m['nickname'] ? $m['nickname'] : $m['realname'];
            $m = mc_fetch($value['uid'], array('resideprovince', 'residecity'));
            $value['p'] = $m['resideprovince'];
            $value['c'] = $m['residecity'];
        }
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> modulename . '_fans') . " where weid='{$_W['uniacid']}' {$con2}");
        $pager = pagination($total, $pindex, $psize);
        $cfg = $this -> getConfig();
        include $this -> template('fans');
    }
    public function doWebData(){
        global $_W, $_GPC;
        $now = strtotime(date('Y-m-d'));
        $starttime = empty($_GPC['time']['start']) ? $now - 30 * 86400 : strtotime($_GPC['time']['start']);
        $endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
        $num = ($endtime + 1 - $starttime) / 86400;
        $stat = array();
        for($i = 0; $i < $num; $i++){
            $time = $i * 86400 + $starttime;
            $key = date('m-d', $time);
            $stat['all'][$key] = 0;
            $stat['today'][$key] = 0;
        }
        $cfg = $this -> getConfig();
        $unrule = $cfg['un_rule'];
        if (!$unrule){
            $con = "left join " . tablename('mc_mapping_fans') . " m on f.openid=m.openid";
            $con1 = " and m.follow=1";
        }
        $list = pdo_fetchall('select createtime from ' . tablename($this -> modulename . "_fans") . " f {$con} where weid='{$_W['uniacid']}' and f.createtime <= {$endtime} and f.createtime>={$starttime} {$con1}");
        foreach ($list as $value){
            $key = date('m-d', $value['createtime']);
            $stat['all'][$key]++;
            $stat['today'][$key]++;
        }
        $out['label'] = array_keys($stat['all']);
        $out['datasets'] = array('all' => array_values($stat['all']), 'today' => array_values($stat['today']));
        exit(json_encode($out));
    }
    public function doWebStat(){
        global $_W, $_GPC;
        $op = $_GPC['op'];
        $now = strtotime(date('Y-m-d'));
        $starttime = empty($_GPC['time']['start']) ? $now - 30 * 86400 : strtotime($_GPC['time']['start']);
        $endtime = empty($_GPC['time']['end']) ? TIMESTAMP : strtotime($_GPC['time']['end']) + 86399;
        if (!$op){
            $m = $this -> getFansNum('', true, $starttime, $endtime);
            $all_num = $m['today'];
            $total_num = $m['all'];
            $today_un = $m['today_un'];
            $all_un = $m['un'];
        }else{
            $condition = " and f.createtime <= {$endtime} and f.createtime>={$starttime}";
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $limit = "LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
            if ($_GPC['export']){
                $limit = '';
            }
            $cfg = $this -> getConfig();
            $unrule = $cfg['un_rule'];
            if (!$unrule){
                $unrule = " left join " . tablename('mc_mapping_fans') . ' m on m.openid=f.openid where f.pid=a.id and m.follow=1';
            }else $unrule = ' where f.pid=a.id';
            $list = pdo_fetchall('select *,(select count(1) from ' . tablename($this -> modulename . "_fans") . " f {$unrule} {$condition}) num from " . tablename($this -> modulename . "_info") . " a where weid='{$_W['uniacid']}' order by num desc,createtime {$limit}");
            if ($_GPC['export']){
                $this -> sexport($list);
                exit;
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> modulename . '_info') . " where weid='{$_W['uniacid']}'");
            $pager = pagination($total, $pindex, $psize);
        }
        include $this -> template('stat');
    }
    private function sexport($list){
        include_once 'excel.php';
        $filename = '推广排行_' . date('YmdHis') . '.csv';
        $exceler = new Jason_Excel_Export();
        $exceler -> charset('UTF-8');
        $exceler -> setFileName($filename);
        $excel_title = array('排名', '工号');
        $excel_title[] = '姓名';
        $excel_title[] = '推广数';
        $exceler -> setTitle($excel_title);
        $allsum = 0;
        $all = 0;
        foreach ($list as $k => $val){
            $data = array($k + 1, $val['account']);
            $data[] = $val['realname'];
            $data[] = intval($val['num']);
            $excel_data[] = $data;
            $allsum++;
            $all += $val['all'];
        }
        $excel_data[] = array('总人数:', $allsum, '总推广数:', $all);
        $exceler -> setContent($excel_data);
        $exceler -> export();
    }
    public function doMobileGetIp(){
        global $_W, $_GPC;
        $msg = $_GPC['msg'];
        if (!$_W['isajax']){
            include $this -> template('getip');
            if ($msg['limit'] == 2){
                return ;
            }
        }
        if ($msg['sign'] != md5('junyi' . $msg['createtime'])) return;
        if ($_W['isajax']){
            $addr = array('region' => $_GPC['province'], 'city' => $_GPC['city']);
        }else{
            load() -> func('communication');
            $res = ihttp_get('http://ip.taobao.com/service/getIpInfo.php?ip=' . $_W['clientip']);
            $res = @json_decode($res['content'], true);
            $addr = $res['data'];
        }
        $method = "respond";
        $site = WeUtility :: createModuleProcessor($this -> modulename);
        $site -> message = array('msgtype' => $msg['msgtype'], 'event' => $msg['event'], 'checked' => 'checked', 'content' => $msg['content'], 'ticket' => $msg['ticket'], 'from' => $_W['openid'], 'province' => $addr['region'], 'city' => $addr['city'],);
        $this -> priority = $msg['priority'];
        $site -> module['name'] = $this -> modulename;
        $site -> rule = $msg['rid'];
        $site -> $method();
        exit;
    }
    public function createPoster($staff, $cfg){
        $data = json_decode(str_replace('&quot;', "'", $cfg['data']), true);
        set_time_limit(0);
        @ini_set('memory_limit', '256M');
        $bg = $cfg['bg'];
        $size = getimagesize(tomedia($bg));
        $target = imagecreatetruecolor($size[0], $size[1]);
        $bg = $this -> imagecreates(tomedia($bg));
        imagecopy($target, $bg, 0, 0, 0, 0, $size[0], $size[1]);
        imagedestroy($bg);
        $qrcode = str_replace('#id#', $staff['id'], IA_ROOT . $this -> POSTER_PATH);
        foreach ($data as $value){
            $value = $this -> trimPx($value);
            if ($value['type'] == 'qr'){
                $img = "../attachment/temp" . random(8) . ".png";
                $errorCorrectionLevel = "L";
                $matrixPointSize = "4";
                include 'phpqrcode.php';
                if ($_W['account']['level'] == 4){
                    $sharelink = $this -> createQrcode($staff, true);
                }else $sharelink = $this -> saveImage($_W['account']['qrcode'], "qr" . $staff['id']);
                QRcode :: png($sharelink, $img, $errorCorrectionLevel, $matrixPointSize);
                $this -> mergeImage($target, $img, array('left' => $value['left'], 'top' => $value['top'], 'width' => $value['width'], 'height' => $value['height']));
                @unlink($img);
            }elseif ($value['type'] == 'img'){
                $img = $this -> saveImage($staff['avatar']);
                $this -> mergeImage($target, $img, array('left' => $value['left'], 'top' => $value['top'], 'width' => $value['width'], 'height' => $value['height']));
                @unlink($img);
            }elseif ($value['type'] == 'name') $this -> mergeText($target, $staff['realname'], array('size' => $value['size'], 'color' => $value['color'], 'left' => $value['left'], 'top' => $value['top']));
            elseif ($value['type'] == 'code') $this -> mergeText($target, $staff['account'], array('size' => $value['size'], 'color' => $value['color'], 'left' => $value['left'], 'top' => $value['top']));
        }
        imagejpeg($target, $qrcode);
        imagedestroy($target);
        return $qrcode;
    }
    function trimPx($data){
        $data['left'] = intval(str_replace('px', '', $data['left'])) * 2;
        $data['top'] = intval(str_replace('px', '', $data['top'])) * 2;
        $data['width'] = intval(str_replace('px', '', $data['width'])) * 2;
        $data['height'] = intval(str_replace('px', '', $data['height'])) * 2;
        $data['size'] = intval(str_replace('px', '', $data['size'])) * 2;
        $data['src'] = tomedia($data['src']);
        return $data;
    }
    function mergeImage($target, $imgurl , $data){
        $img = $this -> imagecreates($imgurl);
        $w = imagesx($img);
        $h = imagesy($img);
        imagecopyresized($target, $img, $data['left'], $data['top'], 0, 0, $data['width'], $data['height'], $w, $h);
        imagedestroy($img);
        return $target;
    }
    function mergeText($target , $text , $data){
        $font = IA_ROOT . '/web/resource/fonts/msyhbd.ttf';
        $colors = $this -> hex2rgb($data['color']);
        $color = imagecolorallocate($target, $colors['red'], $colors['green'], $colors['blue']);
        imagettftext($target, $data['size'], 0, $data['left'], $data['top'] + $data['size'], $color, $font, $text);
        return $target;
    }
    function hex2rgb($colour){
        if ($colour[0] == '#'){
            $colour = substr($colour, 1);
        }
        if (strlen($colour) == 6){
            list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
        }elseif (strlen($colour) == 3){
            list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
        }else{
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array('red' => $r, 'green' => $g, 'blue' => $b);
    }
    function imagecreates($bg){
        $bgImg = @imagecreatefromjpeg($bg);
        if (FALSE == $bgImg){
            $bgImg = @imagecreatefrompng($bg);
        }
        if (FALSE == $bgImg){
            $bgImg = @imagecreatefromgif($bg);
        }
        return $bgImg;
    }
    function saveImage($url, $tag = ''){
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_URL, $url);
        ob_start ();
        curl_exec ($ch);
        $return_content = ob_get_contents ();
        ob_end_clean ();
        $return_code = curl_getinfo ($ch, CURLINFO_HTTP_CODE);
        $filename = IA_ROOT . "/attachment/temp" . random(32) . ".jpg";
        $fp = @fopen($filename, "a");
        fwrite($fp, $return_content);
        return $filename;
    }
}

?>