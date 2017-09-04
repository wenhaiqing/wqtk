<?php
   global $_W;
        global $_GPC;

       $id = intval($_GPC['id']);
        if (!empty($id)){
            $item = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_share") . " WHERE id = :id" , array(':id' => $id));
            if (empty($item)){
                message('会员不存在！', '', 'error');
            }
        }
        //echo '<pre>';
        //print_r($item);
        //exit;

       if (checksubmit('submit')){
           //echo '<pre>';
           //print_r($_GPC);
           //exit;
           $data = array(
               //'usernames' => $_GPC['usernames'],
               'tel' => $_GPC['tel'],
               'weixin' => $_GPC['weixin'],
               'helpid' => $_GPC['helpid'],
               'cqtype' => $_GPC['cqtype'],
               );
               //print_r($data);
               //exit;
           pdo_update($this->modulename."_share", $data, array('id' => $id));
           message('会员更新成功！', $this -> createWebUrl('share', array('op' => 'display')), 'success');
         
       }

       

       include $this -> template('memberedit');    