
var currentType = 'list'; //当前操作类型  list - 常规列表  search - 搜索
var currentPage = 1; //当前页

$(function ($) {
    
    var w = $.trim($('#inpSearch').val());
    if(w!=''){
        currentType = 'search';
        goSearch();
    }


    $('.cjf_con li').click(function () {
        if (currentType == 'list' && $('.cjf_con > .handon')[0] == this) {
            return;
        }
        
        currentType = 'list';

        $('.cjf_con').find('li').removeClass('handon');
        $(this).addClass('handon');
        
        $('.cjf_con').find('.active').removeClass('active');
        $(this).find('p').addClass('active');
        
 
        $('#divList').html('');
        currentCategory = $(this).attr('data');
        currentPage = 0;
        
        //存cookie
        setCookie("currentCategory",currentCategory,"d1");
        
        $('#inpSearch').val('');
        
        loadNextPage('');

    });

    //返回顶部
    $('#imgTop').click(function () {
        $("html,body").animate({ scrollTop: 0 }, 1000);
    });
    
    
    $('.fc_share_bg').click(function(){
        $('.fc_share').hide();
        $('.fc_share_bg').hide();
    });
    

    //滚动加载下一页
    $(window).scroll(function () {

        if ($('#divTips').css('display') == 'block') {
            return;
        }

        //$(window).scrollTop()这个方法是当前滚动条滚动的距离
        //$(window).height()获取当前窗体的高度
        //$(document).height()获取当前文档的高度
        var bot = 50; //bot是底部距离的高度
        if ((bot + $(window).scrollTop()) >= ($(document).height() - $(window).height())) {
            //当底部基本距离+滚动的高度〉=文档的高度-窗体的高度时；
            //我们需要去异步加载数据了
            
            if(currentType=='search'){
                var w = $.trim($('#inpSearch').val());
                if(w==''){
                    alert('请输入搜索关键词');
                    return;
                }
                loadNextPage(w);
            }else{
                loadNextPage('');
            }
            

        }


        //返回顶部按钮
        if ($(window).scrollTop() > 500) {
            $('#imgTop').show();
        } else {
            $('#imgTop').hide();
        }

    });
    
    
    //捕获键盘 搜索 事件
    $('form').submit(function(){
        goSearch();
        $('#inpSearch').blur();
        return false;
    });
    
    
    //初始化 当前菜单项
    if( $('.cjf_con').find('li').eq(0).attr('data')!=currentCategory){
        $('.cjf_con').find('li').removeClass('handon');
        $('.cjf_con').find('.active').removeClass('active');
        
        $('.cjf_con').find('li').each(function(){
            if($(this).attr('data')==currentCategory){
                $(this).addClass('handon');
                $(this).find('p').addClass('active');
            }
        });
        
    }

});


function goFirstMemu(){
    $('.cjf_con').find('li').eq(0).click();
}


function goSearch(){
    var w = $.trim($('#inpSearch').val());
    if(w==''){
        alert('请输入搜索关键词');
        return false;
    }
    currentType = 'search';
    currentPage = 0;
    
    $('.cjf_con').find('li').removeClass('handon');
    $('.cjf_con').find('li').eq(0).addClass('handon');
    
    $('.cjf_con').find('.active').removeClass('active');
    $('.cjf_con').find('li').eq(0).find('p').addClass('active');
    
    $('#divList').html('');
    
    loadNextPage(w);
    
}



var loadOperation = undefined;

//加载分享任务列表
function loadNextPage(_w) {
    
    

    if (loadOperation != undefined) return false;
    loadOperation = {
        start: function () {

            $('#divLoading').show();

            var para = "action=getNextPage&cid=" + currentCategory + "&pg=" + (currentPage + 1) + "&time=" + new Date();
            
            if(currentType=='search'){
                para = "action=search&w=" + _w + "&pg=" + (currentPage + 1) + "&time=" + new Date();
            }
            
            $.ajax({
                dataType: "json", //返回类型
                type: "POST",
                data: para,
                cache: false, // cache只有GET方式的时候有效。
                url: "tbyhq_index.aspx",
                async: false, //是否异步 false 会等待该方法执行
                error: function () {
                    loadOperation = undefined;
                    $('#divLoading').hide();
                    $('#divTips').hide();
                },
                success: function (data, textStatus) {
//                    $('#divLoading').hide();
                    $('#divTips').hide();

                    if (data.status == 0) {
                        currentPage++;

                        var id, num_iid, title, pict_url_ddz, price, price_yh, yhq_price, user_type, provcity, cname, yh_url;

                        for (var i = 0; i < data.Items.length; i++) {
                            id = decodeURIComponent(data.Items[i].ID);
                            num_iid = decodeURIComponent(data.Items[i].NUM_IID);
                            title = decodeURIComponent(data.Items[i].TITLE);
                            pict_url_ddz = decodeURIComponent(data.Items[i].PICT_URL_DDZ);
                            price = decodeURIComponent(data.Items[i].PRICE);
                            price_yh = decodeURIComponent(data.Items[i].PRICE_YH);
                            yhq_price = decodeURIComponent(data.Items[i].YHQ_PRICE);
                            user_type = decodeURIComponent(data.Items[i].USER_TYPE);
                            provcity = decodeURIComponent(data.Items[i].PROVCITY);
                            cname = decodeURIComponent(data.Items[i].CNAME);
                            yh_url = decodeURIComponent(data.Items[i].YH_URL);

                            var str = '<div class="yhj" onclick="javascript:window.location.href=\'tbyhq_detail.aspx?aid='+aid+'&id='+num_iid+'\'">'
                                    + '    <div class="yhj_l"><img src="' + pict_url_ddz + '" width="85" height="85" /></div>'
                                    + '    <div class="yhj_m">'
                                    + '        <div class="title">'
                                    + '            <span>';
                            if (user_type == "1") {
                                str += '<img src="img/tm.png" width="40" height="40"/>';
                            } else {
                                str += '<img src="img/tb.png" width="40" height="40"/>';
                            }

                            str += '</span>&nbsp;<font class="title2">' + title+'</font>'
                                    + '        </div>'
                                    + '        <div class="mes">'
                                    + '            现 价 <span class="gr">￥' + fmoney(price, 2) + '</span><br>'
                                    + '            用券后 <span class="red">￥' + fmoney(price_yh, 2) + '</span>'
                                    + '        </div>'
                                    + '    </div>'
                                    + '    <div class="yhq_img"><img src="img/yhj_m.jpg" width="12" height="95"/></div>'
                                    + '    <div class="yhj_r">￥' + yhq_price + '<br><span>优惠券</span></span></div>'
                                    + '</div>';
                                    
                                    
                                    

                            $('#divList').append(str);

                        }
                        
                        
                        if(data.Items.length<10){
                            $('#divLoading').hide();
                            $('#divTips').show();
                        }

                    } else {
                        var message = decodeURIComponent(data.msg);
                        alert(message);
                    }


                    loadOperation = undefined;
                }
            });

        }
    }

    loadOperation.start();
    return true;

}



function fmoney(s, n) {
    n = n > 0 && n <= 20 ? n : 2;
    s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
    var l = s.split(".")[0].split("").reverse(),
   r = s.split(".")[1];
    t = "";
    for (i = 0; i < l.length; i++) {
        t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
    }
    return t.split("").reverse().join("") + "." + r;
}










function showShareFC(obj){
    
    setWXShareData(obj);
    
    $('.fc_share').show();
    $('.fc_share_bg').show();
    
}


function setWXShareData(obj){

    var yhj =  $(obj).parent().parent().parent();
    var price = yhj.find('.price').html();
    var price_yh = yhj.find('.red').html().replace('￥','');
    var title = yhj.find('.title2').html();
    var img =yhj.find('.yhj_l').find('img').attr('src');
    var url = yhj.find('.an_n_l').attr('onclick').replace('javascript:window.location.href=\'','').replace('\'','');
    
    var path = window.location.href.substring(0,window.location.href.lastIndexOf('/')+1);
    url = path + url;
    
    var newShareData = {
        appid: shareData.appid,
        title: "【在售"+price+"元，券后"+price_yh+"元】"+title,
        desc:  "【在售"+price+"元，券后"+price_yh+"元】"+title,
        link: url, //分享链接
        imgUrl: img
    }
    
    shareData = newShareData;
    
    updateWXShareData();

}















/*****************************操作Cookie****************/

//写cookies 

function setCookie(name, value, time) {
    var strsec = getsec(time);
    var exp = new Date();
    exp.setTime(exp.getTime() + strsec * 1);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=/";
}
function getsec(str) {
    var str1 = str.substring(1, str.length) * 1;
    var str2 = str.substring(0, 1);
    if (str2 == "s") {
        return str1 * 1000;
    }
    else if (str2 == "h") {
        return str1 * 60 * 60 * 1000;
    }
    else if (str2 == "d") {
        return str1 * 24 * 60 * 60 * 1000;
    }
}

//读取cookies 
function getCookie(name) {
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
}

//删除cookies 
function delCookie(name) {
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getCookie(name);
    if (cval != null)
        document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}


//这是有设定过期时间的使用示例： 
//s20是代表20秒 
//h是指小时，如12小时则是：h12 
//d是天数，30天则：d30 

//setCookie("name","hayden","s20"); 
//alert(getCookie("name")); 