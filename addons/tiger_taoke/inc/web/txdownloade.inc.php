<?php
 global $_W;
        load()->model('mc');
        require_once IA_ROOT.'/addons/tiger_taoke/lib/excel.php';
        $filename = '兑换记录_' . date('YmdHis') . '.csv';
        $exceler = new Tiger_Export();
        $exceler->charset('UTF-8');
        // 生成excel格式 这里根据后缀名不同而生成不同的格式。jason_excel.csv
        $exceler->setFileName($filename);
        // 设置excel标题行
        $excel_title = array('收款帐号','发放集分宝数（个）');
        $exceler->setTitle($excel_title);
        // 设置excel内容
        $excel_data = array();
        
        $list = pdo_fetchall('select * from '.tablename($this->modulename."_txlog")."  where weid='{$_W['uniacid']}' and sh<>1 and zfbuid<>''");
        $allsum = 0;
        foreach ($list as $value) {
            pdo_update($this->modulename."_txlog", array('addtime'=>time(),'sh'=>1), array('id' => $value['id']));
            $data = array();
            $data[] = $value['zfbuid'];
            $data[] = $value['credit2']*100;
            $excel_data[] = $data;
            $allsum++;
        }
        
        
        $excel_data[] = array('总数目:', $allsum);
        $exceler->setContent($excel_data);
        // 生成excel
        $exceler->export();
        exit;
       //include "downloade.php";