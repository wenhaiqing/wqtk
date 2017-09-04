$(document).ready(function(){  

        var jumpurl=$("#jumpurl").text();
        if(getck("showdiv") == "")
        {
            var data = new Date();
            var timestr = data.getFullYear() + "-" + (Number(data.getMonth())+1) + "-" + data.getDate() + " " + data.getHours() + ":" + data.getMinutes() + ":" + data.getSeconds();
            document.cookie="showdiv=" + timestr;
        }
        else
        {
            var date_ = convertdate(getck("showdiv"));
            var data = new Date();
            var num = Number(data.getTime()) - Number(date_.getTime());
            if(num >= 24*3600000)//已经过期重新设置
            {
               document.cookie="showdiv=";
            }
        }
        $("#closebtn").click(function(){ document.cookie="isshow=true";$("#popup_scroll").fadeOut("slow");});
        $("#add_fav").click(function(){ 
            window.open(jumpurl); 
            document.cookie="isshow=true";
            $("#popup_scroll").fadeOut("slow");
        });

        $("#welcome_img").click(function(){
              window.open(jumpurl); 
              document.cookie="isshow=true";
              $("#popup_scroll").fadeOut("slow");
        });

        setTimeout("showdiv()",1000);  //这里修改打开网页多久之后显示提示
 });

    function showdiv()
    { 
       if(getck("isshow")  == "true")
       {
         return;
       }
       else
       {
         if(getck("showdiv") == "")
         {
            $("#popup_scroll").width(document.body.clientWidth);
            $("#popup_scroll").height(document.body.clientHeight);
            $("#popup_scroll").show();
         }
         else
         {
            var date_ = convertdate(getck("showdiv"));
            var data = new Date();
            var num = Number(data.getTime()) - Number(date_.getTime());
            if(num >= 24*3600000)//已经过期重新设置
            {
                document.cookie="showdiv=";
                $("#popup_scroll").width(document.body.clientWidth);
                $("#popup_scroll").height(document.body.clientHeight);
                $("#popup_scroll").show();
            } 
         }
       }
    }

    var acookie=document.cookie.split("; ");
    function getck(sname)
    {
       //获取单个cookies
        for(var i=0;i<acookie.length;i++){
        var arr=acookie[i].split("=");
        if(sname==arr[0]){
        if(arr.length>1)
        return unescape(arr[1]);
        else
        return "";
        }}
        return "";
    }

    function convertdate(datestr)
    {
      var date_hidden = datestr;
      date_hidden = date_hidden.replace(":","-");
      date_hidden = date_hidden.replace(":","-");
      date_hidden = date_hidden.replace(" ","-");
      var date = new Date(Number(date_hidden.split("-")[0]),Number(date_hidden.split("-")[1])-1,Number(date_hidden.split("-")[2]),Number(date_hidden.split("-")[3]),Number(date_hidden.split("-")[4]),Number(date_hidden.split("-")[5]));
      return date;  
    }

    function clearcookie()
    {
        document.cookie="showdiv=";
        document.cookie="isshow=";location.href=location.href;
    }   