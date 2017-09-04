<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2016-12-22 20:33:19
 * @version $Id$
 */
global $_GPC,$_W;
mypdo_settablepre('tgnet_');
load()->func('tpl');
//var_dump($_GPC);

function createHtml($name,$type,$ex='',$key=0){
	$exarr = explode('|', $ex);
	$html  = '';
	switch($type){
		case 'number':
		case 'string':
			$html = '<div class="form-group">
    				   <label class="col-sm-2 control-label">'.$name.'</label>
    				   <div class="col-sm-10">
      				 <input type="text" name="tgnet_htb[]" title="'.$name.'" class="form-control" placeholder="请输入'.$name.'"/>
    				   </div>
  					   </div>';
		break;
		case 'text':
			$html = '<div class="form-group">
               <label class="col-sm-2 control-label">'.$name.'</label>
               <div class="col-sm-10">
               <textarea name="tgnet_htb[]" title="'.$name.'" class="form-control" rows="3"></textarea>
               </div>
               </div>';
		break;
    case 'image':
      return  '<div class="form-group">
               <label class="col-sm-2 control-label">'.$name.'</label>
               <div class="col-sm-10">'.
               tpl_form_field_image('tgnet_htb_image','default.jpg')
               .'</div>
               </div>';
    break;
    case 'time':
      return  '<div class="form-group">
               <label class="col-sm-2 control-label">'.$name.'</label>
               <div class="col-sm-10">'.
               tpl_form_field_date('tgnet_htb_time')
               .'</div>
               </div>';
    break;
		case 'checkbox':
			$html = '<div class="form-group">
    				   <label class="col-sm-2 control-label">'.$name.'</label>
               <div class="col-sm-10">';
      		$tmp = '';
      		foreach($exarr as $k => $v){
      			$tmp .= '<label class="checkbox-inline">
    					 <input name="tgnet_htb_ckb_'.$key.'[]" type="checkbox" value="'.$v.'">'.$v.'
  						 </label>';
      		}
        	$html .= $tmp.'</div></div>';
		break;
		case 'radio':
			$html = '<div class="form-group">
    				 <label class="col-sm-2 control-label">'.$name.'</label>
             <div class="col-sm-10">';
      		$tmp = '';
      		foreach($exarr as $k => $v){
      			$tmp .= '<label class="radio-inline">
    					 <input name="tgnet_htb[]" type="radio" value="'.$v.'"/>'.$v.'
  						 </label>';
      		}
        	$html .= $tmp.'</div></div>';
		break;
		case 'select':
			$html = '<div class="form-group">
    				  <label class="col-sm-2 control-label">'.$name.'</label><div class="col-sm-10">
    				  <select name="tgnet_htb[]" class="form-control" style="max-width:200px;">';
      		$tmp = '';
      		foreach($exarr as $k => $v){
      			$tmp .= '<option value="'.$v.'">'.$v.'</option>';
      		}
      		$html .= $tmp.'</select></div></div>';
		break;
	}
	return $html;
}

if(!empty($_GPC['totable']) && intval($_GPC['totable']) > 0){
	//var_dump($data);
}else{
  return false;
}

$data = mypdo_get('htb_settings',array('id'=>intval($_GPC['totable'])));
$list = json_decode($data['link']);
$imgtype = array(
      '.jpeg','.JPEG','.jpg','.JPG',
      '.png','.PNG','.gif','.GIF',
      '.bmp','.BMP','.svg','.SVG','.ai','.AI'
  );

if(checksubmit()){
  $subData = array();
  //var_dump($_GPC);
  //$subData['nickname'] = $_W['uniacid'];
  if(trim($_W['fans']['nickname']) != ''){
    $subData['nickname'] = $_W['fans']['nickname'] ;
  }else{
    $subData['nickname'] = 'unknown';
  }
  
  foreach($_GPC as $key => $value){
    if(preg_match('/^tgnet_htb$/',$key)){
      $subData = array_merge($subData,$value);
    }
    else if(preg_match('/tgnet_htb_ckb_[0-9]+/',$key)){
      $insertkey = str_replace('tgnet_htb_ckb_','',$key);
      if($insertkey == 0){
        $subData[0] = implode(',',$value);
      }
      else if($insertkey == (count($subData) - 1)){
        array_push($subData,implode(',',$value));
      }
      else{
        array_splice($subData,$insertkey,0,$options);
      }
    }
  }

  $tmpData['nickname'] = $subData['nickname'];
  for($i = 0 ; $i < count($subData)-1 ; $i++){
    if(in_array(strrchr($subData[$i],'.'),$imgtype)){
      $tmpData['col_'.$i] = $_W['attachurl'].$subData[$i];
    }else{
      $tmpData['col_'.$i] = $subData[$i];
    }
  }
  $tmptb = str_replace('tgnet_','',$data['tbname']);
  if(mypdo_insert($tmptb,$tmpData)){
    message('添加成功！','refresh','success');
  }else{
    message('添加失败！','refresh','error');
  }
}

include $this->template('web/senddata');
?>