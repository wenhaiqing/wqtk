<?php
global $_W, $_GPC;
        $fzlist = pdo_fetchall("select * from ".tablename($this->modulename."_fztype")." where weid='{$_W['uniacid']}'  order by px desc");
        $op=$_GPC['op'];
        if($op=='dtk'){
            if (checksubmit()){
                $id = $_GPC['id'];
                $dtkcid = $_GPC['dtkcid'];
                $dtkarr = '';
                foreach ($dtkcid as $key => $value) {
                    //if (empty($value)) continue;
                    $dtkarr[] = array('id'=>$id[$key],'dtkcid'=>$value);
                }

                foreach($dtkarr as $v){
                    pdo_update($this->modulename."_fztype", array('dtkcid'=>$v['dtkcid']), array('id' => $v['id']));             
                }
                message ( '更新成功');            
            }        
        }

		include $this->template ( 'caijiset' );