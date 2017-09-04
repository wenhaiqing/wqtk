<?php
 global $_W,$_GPC;
		load()->func('tpl');
        $fzlist = pdo_fetchall("SELECT * FROM " . tablename($this->modulename."_fztype") . " WHERE weid = '{$_W['uniacid']}'  ORDER BY px desc");
		if(checksubmit('submit')){
		    $header=$_GPC['header'];
            
		    if($_FILES["excelfile"]["name"]){
	        require_once  IA_ROOT.'/framework/library/phpexcel/PHPExcel.php';
	        require_once  IA_ROOT.'/framework/library/phpexcel/PHPExcel/IOFactory.php';
	        require_once  IA_ROOT.'/framework/library/phpexcel/PHPExcel/Reader/Excel5.php';
	        require_once  IA_ROOT.'/framework/library/phpexcel/PHPExcel/Shared/Date.php';
	        $path = IA_ROOT . "/addons/tiger_taoke/uploads/";
	        if (!is_dir($path)) {
	            load()->func('file');
	            mkdirs($path, '0777');
	        }
	        $file     = time() . $_W['uniacid'] . ".xlsx";
	        $filename = $_FILES["excelfile"]['name'];
	        $tmpname  = $_FILES["excelfile"]['tmp_name'];
	        if (empty($tmpname)) {
	            message('请选择要上传的Excel文件!', '', 'error');
	        }
	        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	        if ($ext != 'xlsx') {
	            message('请上传 xlsx 格式的Excel文件!', '', 'error');
	        }
	        $uploadfile = $path . $file;
	        $result     = move_uploaded_file($tmpname, $uploadfile);
	        if (!$result) {
	            message('上传Excel 文件失败, 请重新上传!', '', 'error');
	        }
	        $reader             = PHPExcel_IOFactory::createReader('Excel2007');
	        $excel              = $reader->load($uploadfile);
	        $sheet              = $excel->getActiveSheet();
	        $highestRow         = $sheet->getHighestRow();
	        $highestColumn      = $sheet->getHighestColumn();
	        $highestColumnCount = PHPExcel_Cell::columnIndexFromString($highestColumn);
	        $values             = array();
	        for ($row = 1; $row <= $highestRow; $row++) {
		            $rowValue = array();
		            for ($col = 0; $col < $highestColumnCount; $col++) {
		                $rowValue[] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
		            }
		            if($row==1){
	        			$header=implode("\r\n",$rowValue);
	        		}else{
		            	$import[]=$rowValue;
		            }
	        }
	    }
	    
//		$insert = array(
//				'uniacid' => $_W['uniacid'],
//				'title' => $_GPC['title'],
//				'header' => $header,
//				'createtime'=>$day,
//			);
//		 if($insert){
//		 	pdo_insert($this->table_reply, $insert);
//		 }else{
//		 	message('提交失败','','error');
//		 }
//		$time=date('y-m-d',time());


        
        $type=$_GPC['type'];
        $yjtype=$_GPC['yjtype'];
        if(empty($yjtype) || $yjtype==1){
            if($import){
			foreach($import as $data){
                if(empty($data[0])){
                    continue;
                }
                
				      $item = array(
				      	'weid' => $_W['uniacid'],
				      	'type'=>$type,
                        'yjtype'=>1,
				      	 'num_iid'=>$data[0],//商品ID
				      	 'title'=>$data[1],//商品名称
                         'tjcontent'=>$data[1],//推荐内容
				      	 'pic_url'=>$data[2],//主图地址
				      	 'item_url'=>$data[3],//详情页地址
				      	 'shop_title'=>$data[4],//店铺名称
				      	 'price'=>$data[5],//商品价格
                         'goods_sale'=>$data[6],//月销售
				      	 'tk_rate'=>$data[7],//通用佣金比例
				      	 'yongjin'=>$data[8],//通用佣金
                         //活动状态event_zt
                         //活动收入比event_yjbl
                         //活动佣金event_yj
                         //活动开始event_start_time
                         //活动结束event_end_time
                          'nick'=>$data[9],//卖家旺旺
                          'tk_durl'=>$data[10],//淘客短链接
                          'click_url'=>$data[11],//淘客长链接
                          'taokouling'=>$data[12],//淘口令
                          'coupons_total'=>$data[13],//优惠券总量
                          'coupons_take'=>$data[14],//优惠券剩余
                          'coupons_price'=>'',//优惠券面额
                          'quan_condition'=>$data[15],//'优惠券使用条件',  
                          'coupons_start'=>strtotime($data[16]),//优惠券开始
                          'coupons_end'=>strtotime($data[17]),//优惠券结束
                          'coupons_url'=>$data[18],//优惠券链接
                          'coupons_tkl'=>$data[19],//优惠淘口令
                          'createtime'=>TIMESTAMP,
				      	);
                    $go = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$_W['uniacid']}' and  num_iid='{$data[0]}' ORDER BY px desc");
                    if(empty($go)){
                      pdo_insert($this->modulename."_tbgoods",$item);
                    }else{
                      pdo_update($this->modulename."_tbgoods", $item, array('num_iid' => $data[0]));
                    }
					
				}
                 message('导入成功', $this -> createWebUrl('tbgoods', array('op' => 'display')), 'success');
			}
        
        }elseif($yjtype==2){//高佣金
          if($import){
			foreach($import as $data){
                if(empty($data[0])){
                    continue;
                }
				      $item = array(
				      	'weid' => $_W['uniacid'],
				      	'type'=>$type,
                        'yjtype'=>2,
				      	 'num_iid'=>$data[0],//商品ID
				      	 'title'=>$data[1],//商品名称
                         'tjcontent'=>$data[1],//推荐内容
				      	 'pic_url'=>$data[2],//主图地址
				      	 'item_url'=>$data[3],//详情页地址
				      	 'shop_title'=>$data[4],//店铺名称
				      	 'price'=>$data[5],//商品价格
                         'goods_sale'=>$data[6],//月销售
				      	 'tk_rate'=>$data[7],//通用佣金比例
				      	 'yongjin'=>$data[8],//通用佣金
                         'event_zt'=>$data[9],//活动状态event_zt
                         'event_yjbl'=>$data[10],//活动收入比event_yjbl
                         'event_yj'=>$data[11],//活动佣金event_yj
                         'event_start_time'=>strtotime($data[12]),//活动开始event_start_time
                         'event_end_time'=>strtotime($data[13]),//活动结束event_end_time
                          'nick'=>$data[14],//卖家旺旺
                          'tk_durl'=>$data[15],//淘客短链接
                          'click_url'=>$data[16],//淘客长链接
                          'taokouling'=>$data[17],//淘口令
                          'coupons_total'=>$data[18],//优惠券总量
                          'coupons_take'=>$data[19],//优惠券剩余
                          'coupons_price'=>'',//优惠券面额
                          'quan_condition'=>$data[20],//'优惠券使用条件',  
                          'coupons_start'=>strtotime($data[21]),//优惠券开始
                          'coupons_end'=>strtotime($data[22]),//优惠券结束
                          'coupons_url'=>$data[23],//优惠券链接
                          'coupons_tkl'=>$data[24],//优惠淘口令
                          'createtime'=>TIMESTAMP,
				      	);
					$go = pdo_fetch("SELECT * FROM " . tablename($this->modulename."_tbgoods") . " WHERE weid = '{$_W['uniacid']}' and  num_iid='{$data[0]}' ORDER BY px desc");
                    if(empty($go)){
                      pdo_insert($this->modulename."_tbgoods",$item);
                    }else{
                      pdo_update($this->modulename."_tbgoods", $item, array('num_iid' => $data[0]));
                    }
				}
                 message('导入成功', $this -> createWebUrl('tbgoods', array('op' => 'display')), 'success');
			}
        }
        //echo '<pre>';
       // print_r($import);
        //exit;
		
		}
         include $this->template('tbgoodform');