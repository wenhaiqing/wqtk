var SEARCH_HTML = '<style>#modal-serach_m th{text-align:center !important;} #modal-serach_m td{text-align:center !important;}</style>'+
	'<div id="modal-serach_m"  class="modal fade" tabindex="-1">'+
    '<div class="modal-dialog" style="width: 520px;">'+
    '<form class="form form-horizontal">'+
      '  <div class="modal-content">'+
       '     <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3 id="search_mtitle"></h3></div>'+
      '      <div class="modal-body">'+
     '          	<div class="form-group">'+
               		'<label class="col-xs-12 col-sm-3 col-md-2 control-label">搜索</label>'+
                  '     <div class="col-sm-9">'+
                 '          <div class="input-group">'+
				'			<input class="form-control" type="text" id="SEARCH_MPLACE">'+
				'			<div class="input-group-btn"><a id="SEARCH_MFIND" class="btn btn-default">搜索</a></div>'+
				'		</div>'+
              '         </div>'+
             '  	</div>'+
            '      <table id="search_mtable" class="table table-hover"></table>'+
           '       <div class="form-group" id="SEARCH_BTNS">'+
          '            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>'+
         '             <div class="col-sm-9" style="text-align: center;">'+
        '                  <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>'+
       '                   <button type="button" id="SERACH_MSURE"  data-dismiss="modal" class="btn btn-primary">确定</button>'+
      '                </div>'+
     '             </div>'+
    '        </div>'+
   '     </div>'+
  '      </form>'+
 '   </div>'+
'</div>';

var SEARCH_CALLBACk;
var SEARCH_TYPE = 1;
/*
 * @param type 1:单选 2:多选
 * @param title 标题
 * @param placeholder 搜索框placeholder
 * @param headers 显示内容头部
 * @param data 显示数据 参数([{id:id,list:list,status:status}])
 * @param search 搜索函数 参数(关键字,增加数据函数)
 * @param callback 选中数据后回调
 */
function SEARCH(type,title,placeholder,headers,data,search,callback){
	$('#modal-serach_m').remove();
	$('body').append(SEARCH_HTML);
	SEARCH_TYPE = type;
	SEARCH_CALLBACk = callback;
	$('#modal-serach_m').modal();
	$('#search_mtitle').text(title);
	$('#search_mtable').html('');
	$('#SEARCH_MFIND').parent().prev('input').attr('placeholder',placeholder);
	var ths = '<tr>';
	$.each(headers,function(k,v){
		var w = '';
		if(v['width']) w = "style='width:"+v['width']+"'";
		ths += "<th "+w+">"+v['title']+"</th>";
	});
	ths += "<th style='width:60px'>选择</th></tr>";
	$('#search_mtable').append(ths);
	CREATE_TABLE_DATA(data);
	
	$('#SEARCH_MFIND').click(function(){
		var kw = $(this).parent().prev('input').val();
		search(kw,CREATE_TABLE_DATA);
		$(this).parent().prev('input').val('');
	});
	
	$('#SERACH_MSURE').click(function(){
		var arrs = new Array();
		$.each($('.search_mth td a'),function(k,v){
			if($(this).hasClass('btn-primary')){
				arrs.push(SEARCH_DATA_ARR[$(this).attr('data-id')]);
			}
		});
		if(type == 1){
			callback(arrs[0]);
		}else{
			callback(arrs);
		}
		$('#modal-serach_m').modal('hide');
	});
	
}

var SEARCH_DATA_ARR;
function CREATE_TABLE_DATA(data){
	SEARCH_DATA_ARR = new Array();
	$('tr.search_mth').remove();
	$.each(data,function(k,item){
		var t = "<tr class='search_mth'>";
		$.each(item['list'],function(i,v){
			t += "<td>"+v+"</td>";
		});
		var cs = "btn-default";
		if(item['status'] == 1) cs += " btn-primary";
		var dismiss = ' data-dismiss="modal" ';
		if(SEARCH_TYPE == 2) dismiss = '';
		t += "<td><a data-id="+item['id']+" "+dismiss+" class='btn "+cs+" btn-sm'>选取</a></td></tr>";
		$('#search_mtable').append(t);
		SEARCH_DATA_ARR[item['id']] = item;
	});
	
	$('.search_mth td a').click(function(){
		if(SEARCH_TYPE == 1){
			$('.search_mth td a').removeClass('btn-primary');
			$(this).addClass('btn-primary');
			SEARCH_CALLBACk(SEARCH_DATA_ARR[$(this).attr('data-id')]);
			$('#modal-serach_m').modal('hide');
		}else{
			if($(this).hasClass('btn-primary')){
				$(this).removeClass('btn-primary').addClass('btn-default')
			}else{
				$(this).removeClass('btn-default').addClass('btn-primary')
			}			
		}
	});
}
