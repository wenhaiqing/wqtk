/**
 * 
 * @authors tg (923046694@qq.com)
 * @date    2016-08-03 19:35:04
 * @version $Id$
 */

(function($){
	/***建立组件类***/
	var CheckSubmit = function(element,func,itemsName,title){
		var checkMethod = func || "isNull";
		this.element = element;
		this.itemsName = $.extend(true,$.fn.CheckSubmit.defaults.itemsName,itemsName || []);
		this.title = $.extend(true,$.fn.CheckSubmit.defaults.title,title || []);
		
		this._initEvent(checkMethod);
	};
	CheckSubmit.prototype = {
		/**检测内容是否空**/
		isNull : function(){
			var form = this._getForm(),
				tempItem = null,
				title = null;
			for(var i = 0 ; i < this.itemsName.length ; i++){
				tempItem = form.find("input[name='" + this.itemsName[i] + "']");
				for(var j = 0 ; j < tempItem.length ; j++){
					if(tempItem.eq(j).get(0)){
						if(tempItem.eq(j).val().replace(/\s/g,"") == ""){
							var tmpTl = (typeof this.title[i] == 'object') ? this.title[i][j] : this.title[i];
							title = tmpTl || tempItem.eq(j).closest("dl").children("dt").html() || tempItem.eq(j).attr("title") || "undefined";
							alert(title + "不能为空！");
							tempItem.eq(j).focus();
							return false;
							break;
						}
					}
				}
				/*if(tempItem[0]){
					if(tempItem.val().replace(/\s/g,"") == ""){
						title = this.title[i] || tempItem.closest("dl").children("dt").html() || tempItem.attr("title") || "undefined";
						alert(title + "不能为空！");
						tempItem.focus();
						return false;
						break;
					}
				}*/
			}
			return true;
		},
		/**检测内容的长度**/
		checkLength : function(){
			var form = this._getForm(),
				items = this.itemsName,
				tempItem = null,
				title = null,
				data = {">":"大于","<":"小于","==":"等于","===":"等于","!=":"不等于","!==":"不等于"};
			tempItem = form.find("input[name='" + items[0] + "']");
			
			if(tempItem[0]){
				if(!eval("(" + tempItem.val().length + items[1] + items[2] + ")")){
					title = this.title[0] || tempItem.closest("dl").children("dt").html() || tempItem.attr("title") || "undefined";
					alert(title + "的长度要" + data[items[1]] + items[2]);
					tempItem.focus();
					return false;
				}
			}
			return true;
		},
		/**检测两字段内容是否相等，一般用于密码的确认**/
		isEqual : function(){
			var form = this._getForm(),
				tempItem = null,
				title = [],
				dbl	= [];
			for(var i = 0 ; i < 2 ; i++){
				tempItem = form.find("input[name='" + this.itemsName[i] + "']");
				if(tempItem[0]){
					dbl[i] = tempItem;
					title[i] = this.title[i] || tempItem.closest("dl").children("dt").html() || tempItem.attr("title") || "undefined";
				}
			}
			if(dbl.length == 2){
				if(dbl[0].val().replace(/\s/g,'') != dbl[1].val().replace(/\s/g,'')){
					alert(title[0] + "和" + title[1] + "的值不一致！");
					dbl[1].focus();
					return false;
				}
			}else{
				alert("比较两项的值是否一致处设置不正确！正确设置为两个字段！");
				return false;
			}
			return true;
		},
		/**检测邮件格式**/
		checkMail : function (){
			var form = this._getForm(),
				tempItem = null,
				title = null,
				module = /^[a-zA-Z0-9]+([._-]*[a-zA-Z0-9])*@[a-zA-Z0-9]+[-a-zA-Z0-9]*\.[a-zA-Z0-9]+$/;
				/**上述模式外不能用引号包围**/
			for(var i = 0 ; i < this.itemsName.length ; i++){
				tempItem = form.find("input[name='" + this.itemsName[i] + "']");
				if(tempItem[0]){
					/**用search方法也可以**/
					if(!(tempItem.val().match(module))){
						title = this.title[i] || tempItem.closest("dl").children("dt").html() || tempItem.attr("title") || "undefined";
						alert(title + "不是正确的邮件格式！");
						tempItem.focus();
						return false;
						break;
					}
				}
			}
			return true;
		},
		/**自动检测，主要用于注册时通过ajax检测是否已存在**/
		autoCheck : function (){
			var form = this._getForm(),
				items = this.itemsName,
				tempItem = null,
				title = this.title,
			tempItem = form.find("input[name='" + items[0] + "']");
			
			if(tempItem[0]){
				$.ajax({
					url : items[1],
					type : "post",
					dataType : "json",
					data : {"autoCheckData" : " " + tempItem.val()},
					/**上行要加至少一个空以上的字符，否则传入为空时返回错误**/
					success : function (res,status,xhr){
						if($.type(title[0]) == "function"){
							title[0](res);
						}
					},
					error : function (xhr,status,error){
						alert("网络不好！");
					}
				});
			}
			return true;
		},
		/**获取绑定按钮所处于的表单**/
		_getForm : function(){
			//或者等价于this.element.get(0)
			var curTag = (this.element)[0];
			while(curTag.tagName !== "HTML"){
				if(curTag.tagName == "FORM"){
					return $(curTag);
					break;
				}else{
					curTag = curTag.parentNode;
				}
			}
			return false;
		},
		/**初始化绑定事件**/
		_initEvent : function(func){
			var me = this,
				form = this._getForm();
			if(func == "autoCheck"){
				var tempItem = form.find("input[name='" + this.itemsName[0] + "']");
				tempItem.on("blur",function(){
					return me[func]();
				});
			}else{
				this.element.on("click",function(){
					/**return me[func]();**/
					/**类似链式调用，用于判断多个条件**/
					var queue = $.fn.CheckSubmit.queue;
					for(var key in queue){
						if(!me[queue[key]]()){
							return false;
						}
					}
				});
			}
		}
	};
	/**对象组件入口**/
	$.fn.CheckSubmit = function(func,itemsName,title){
		if(func != 'autoCheck'){
			$.fn.CheckSubmit.queue.push(func);
		}
		return this.each(function(index,element){
			var me = $(this),
				key = $.fn.CheckSubmit.key,
				instance = me.data("CheckSubmit");
			if(!instance){
				instance = new CheckSubmit(me,func,itemsName,title);
				me.data("CheckSubmit",instance);
			}
		});
	}
	/**默认配置**/
	$.fn.CheckSubmit.defaults = {
		itemsName : [],
		title     : []
	};
	/**用于储存调用的所有函数方法**/
	$.fn.CheckSubmit.queue = [];
})(jQuery);