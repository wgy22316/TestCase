var Menu = function () {

	//菜单排序
	function allcosonmenu(array){
		// alert("allcosonmenu");
		sonmenuarray = array;
		var allcoson_menu = [];
		var allcoson_index = 0;
		// alert(menuarray[0].menu_sort_id);
		for(var i = 0 ; i<sonmenuarray.length;i++){
			// alert(menuarray.length);
			allcoson_index = getMinsortindex(sonmenuarray);
			// alert(index);// 1 0 2 3	 
			allcoson_menu.push(sonmenuarray[allcoson_index]);
			sonmenuarray[allcoson_index].menu_sort_id=9999;
		}

		// for(var i =0 ,length=menu.length;i<length;i++){
		// 	alert(menu[i].menu_sort_id);
		// }
		return allcoson_menu;
	}
	//获取最小排序号
	function getMinsortindex(array){
		var min = array[0].menu_sort_id;
		var minsort_index = 0 ;
		for(var i = 0 ,length=array.length;i<length;i++){
			if(Number(array[i].menu_sort_id)<Number(min)){//取出来的是string，转换成int比较大小
				min=array[i].menu_sort_id;
				minsort_index = i;
			}
		}
		return minsort_index;
	}
	function isArray(obj) {   
  		return Object.prototype.toString.call(obj) === '[object Array]';    
	}  	
	//递归子菜单
	function sortMenu(array){
		var sonmenu = array;
		var sortedmenu;
		var amenu = [];
		var length = sonmenu.length;
		for(var i = 0 ; i < length;i++){
			if(sonmenu[i].son){
				sortedmenu = sortMenu(sonmenu[i].son);
				sonmenu[i].son = sortedmenu;
			}
			else{
				amenu.push(sonmenu[i]);
			}
		}
		amenu = allcosonmenu(sonmenu);
		return amenu;
	}

	//查询子菜单
	function allcomenu(array){
		// alert("allcomenu");
		menuarray = array;
		var menu = [];
		menu = sortMenu(menuarray);
		return menu;
	}


	//show menu
	var showMenu = function(array){
		var unallcomenu = array['menu'];
		var amenu = allcomenu(unallcomenu);//按照菜单顺序排序
		var menu = amenu;//按照菜单顺序排序
		// var menu = array['menu'];//按照菜单顺序排序
		// alert(menu[0].son);
		var innerHTML="<li class=\"start active\" id=\"menu"+menu[0].menu_id+"\">"+
						"	<a href=\""+menu[0].menu_url+"\"  >"+
						"	<i class=\"fa fa-home\"></i>"+ 
						"	<span class=\"title\">"+menu[0].menu_name+"</span>"+
						"	<span class=\"selected\"></span>"+
						"	</a>";
		if(menu[0].son){
				innerHTML=addchild(menu[0].son,innerHTML);
		}
		innerHTML+="</li>";
		for(var i=1,length=menu.length;i<length;i++){
			if(menu[i].menu_pid==0){
				 innerHTML+="<li class=\"\" id=\"menu"+menu[i].menu_id+"\">"+
							"	<a href=\"index.html\">"+
							"	<i class=\"fa fa-briefcase\"></i>"+ 
							"	<span class=\"title\">"+menu[i].menu_name+"</span>"+
							"	</a>";
			}
			if(menu[i].son){
				innerHTML=addchild(menu[i].son,innerHTML);
			}

		}
		$("#start_menu").append(innerHTML);
	
	}
	function addchild(array,innerHTML){
				var son = array;
				innerHTML+="<ul class=\"sub-menu\" style=\"display: none;\">";
				for(var i=0,length=son.length;i<length;i++){

					 innerHTML+="<li id=\"menu"+son[i].menu_id+"\">"+
								"<a class=\"active\" href=\""+son[i].menu_url+"\" target=\"page_iframe\">"+son[i].menu_name;
								// "<a class=\"active\" href=\"http://www.baidu.com\" target=\"page_iframe\">"+son[i].menuname;
								// "<a class=\"active\" href=\"#\" id=\"menu"+son[i].id+"\">"+son[i].menuname;
					if(son[i].isleaf!='1'){
						innerHTML+="<span class=\"arrow\"></span>";
					}
					innerHTML+="</a>";

					if(son[i].son){
						innerHTML=addchild(son[i].son,innerHTML);
					}
								
				}
				innerHTML+="</li></ul>";
			return innerHTML;
	}
    
    //显示需要排序的菜单内容
	var showSortMenu = function(array){
		var unallcomenu = array['menu'];
		var amenu = allcomenu(unallcomenu);//按照菜单顺序排序
		var menu = amenu;//按照菜单顺序排序
		var width = 100/menu.length;
		var innerHTML="<div  id=\"menu"+menu[0].menu_id+"\" style=\"margin-left:3%;margin-bottom:1%\" width="+width+"%>"+
						"<div>"+menu[0].menu_name+" <font style=\"margin-left:3%\" color=\"#1d943b\"> "+
						"<span id=\"upmenu"+menu[0].menu_id+"\">上移</span>&nbsp;&nbsp;&nbsp;"+
						"<span id=\"downmenu"+menu[0].menu_id+"\">下移</span> </font> </div> ";
						// alert("be click");
						// bindClickEvent(menu[0].menu_id);
						// alert("af click");
		if(menu[0].son){
				innerHTML=showChildMenu(menu[0].son,innerHTML);
		}
		innerHTML+="</div></div>";
		for(var i=1,length=menu.length;i<length;i++){
			if(menu[i].menu_pid==0){
				 innerHTML+="<div  id=\"menu"+menu[i].menu_id+"\" style=\"margin-left:3%;margin-bottom:1%\" width="+width+"%>"+
							"<div>"+menu[i].menu_name+" <font style=\"margin-left:3%\" color=\"#1d943b\">"+
							"<span id=\"upmenu"+menu[i].menu_id+"\">上移</span>&nbsp;&nbsp;&nbsp;"+
							"<span id=\"downmenu"+menu[i].menu_id+"\">下移</span> </font> </div> ";
							// bindClickEvent(menu[i].menu_id);
			}
			if(menu[i].son){
				innerHTML=showChildMenu(menu[i].son,innerHTML);
			}
			innerHTML+="</div>";

		}
		innerHTML+="</div>";
		$("#sort_menu").append(innerHTML);
		bindClickEvent();
	}

	function bindClickEvent(){
		for(var k =0 ;k<30;k++){
			$("#upmenu"+k).bind('click',{index:k},function(event,a){
				// alert(k);
				moveupMenu(event,a);
			});
			$("#downmenu"+k).bind('click',{index:k},function(event,a){
				// alert(k);
				movedownMenu(event,a);
			});
		}
		// $("#sort_menu").bind('click',function(event,a){
			// alert("bind");
			// movedownMenu(event,a);
		// });		
	}

	function movedownMenu(event,a){
		var a;
		if(event.data2 === undefined){
		   a = event.data.index;
		 }else{
		   a = event.data2.index;
		 }
		var div = $("#upmenu"+a).parent().parent().parent();
		var prevdiv = $("#upmenu"+a).parent().parent().parent().next();
		prevdiv.after(div);
	}

	function moveupMenu(event,a){
		var a;
		if(event.data2 === undefined){
		   a = event.data.index;
		 }else{
		   a = event.data2.index;
		 }
		var div = $("#upmenu"+a).parent().parent().parent();
		var prevdiv = $("#upmenu"+a).parent().parent().parent().prev();
		prevdiv.before(div);
	}

	function showChildMenu(array,innerHTML){
				var son = array;	
				// innerHTML+="<div class=\"sub-menu\" >";
				for(var i=0,length=son.length;i<length;i++){

					 innerHTML+="<div id=\"menu"+son[i].menu_id+"\" style=\"margin-left:3%\" >"+
							"	<div >"+son[i].menu_name+" <font style=\"margin-left:3%\"  color=\"#1d943b\"> "+
							"<span id=\"upmenu"+son[i].menu_id+"\">上移</span>&nbsp;&nbsp;&nbsp;"+
							"<span id=\"downmenu"+son[i].menu_id+"\">下移</span> </font> </div> ";
							// bindClickEvent(son[i].menu_id);
					if(son[i].son){
						innerHTML=showChildMenu(son[i].son,innerHTML);
					}
					innerHTML+="</div>";
								
				}
			return innerHTML;
	}




	return{
   		init: function(array){
   			  showMenu(array);
   		},
   		sortmenu: function(array){
   			  showSortMenu(array);
   		}
   	};


}();