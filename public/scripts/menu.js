/*
 * author：zhoujx
 * time:2014-03-06
 * name:无限极子菜单js
 * version:2.0
 * use way:
 * 		传入一个array给createmenu
 * 		array样式[id,menuname ,url,pid]
 * 			id为本节点的id
 * 			menuname为节点的名字
 * 			url为请求链接
 * 			pid为父节点的名字
 * 
 * problem:
 * 		收起打开了多级子菜单时，没有将所有子菜单隐藏
 * 		对菜单数组有特殊的要求，数组必须先按父子的关系排序
 * 
 * show:
 * 		打开子菜单的时候，把不相关的兄弟菜单都隐藏掉
 * */
		//end get the menu ajax
//!!存在问题，收起打开了多级子菜单时，没有将所有子菜单隐藏
		/**
		 * start showMenu
		 * 绑定非叶子节点打开子节点
		 * 
		 * */
		function showMenu(event){
			var i= event.data.index;
// 			alert(i);
			if($("#amenu"+i).children().length>0){
				var display = $("#amenu"+i).children().first().css('display');
				var z = $("#amenu"+i).children().attr("z")
				$("#amenu"+i).css("z-index",z);
				$("#amenu"+i).children().css("z-index",z);
				if(display=='none'){//当前菜单的子节点是隐藏的
					blockChildMenu(i);
//		 			alert(i);
				}
				if(display=='block'){//当前菜单的子节点是显示的
					noneChildMenu(i);
//		 			alert("block");
				}
			}
			if($("#amenu"+i).siblings().children().length>0){
				$("#amenu"+i).siblings().css("z-index",i);
				//循环子节点关闭菜单
				$("#amenu"+i).siblings().children().hide();
				
			}
// 			$("#amenu"+i).siblings().children().toggle();
			//停止事件的传播
			event.stopPropagation(); 
		}
		/**
		 * start blockChildMenu
		 * 显示子节点
		 * 
		 * 
		 * 
		 * */
		function blockChildMenu(i){

//			var nownodetop = $("#amenu"+i).offset().top;//当前节点的top距离
//			var siblingsnodetop = $("#amenu"+i).siblings().offset().top;//获取兄弟节点的top
////			alert(siblingstop);
//			var now_siblings_top = siblingsnodetop - nownodetop;//兄弟节点和当前节点的top距离
//			now_siblings_top = Math.floor(now_siblings_top);
			$("#amenu"+i).children().show();
			$("#amenu"+i).siblings().hide();
//			adaptMenu(i,true);
			
		}
		//end  blockChildMenu
		/**
		 * start noneChildMenu
		 * 隐藏子节点
		 * 
		 * 
		 * 
		 * */
		function noneChildMenu(i){

//			var nownodetop = $("#amenu"+i).offset().top;//当前节点的top距离
//			var siblingsnodetop = $("#amenu"+i).siblings().offset().top;//获取兄弟节点的top
//			alert(siblingstop);
//			var now_siblings_top = siblingsnodetop - nownodetop;//兄弟节点和当前节点的top距离
//			now_siblings_top = Math.floor(now_siblings_top);
			$("#amenu"+i).children().hide();
			$("#amenu"+i).siblings().show();
			
//			adaptMenu(i,false);
			
		}
		
		//end  blockChildMenu
		//end showMenu
		/**
		 * 
		 * start adaptMenu
		 * 
		 * 
		 * 
		 * 
		 * 
		 * */
		function adaptMenu(i,bool){
//			alert(i);
//			alert($("#amenu"+i).siblings().)
//			alert($("#amenu"+i).next().attr("id"));
			alert(i);
			var siblingslength = $("#amenu"+i).siblings().length;//获取next兄弟节点的个数
//			alert(siblingslength);
			alert(siblingslength);
			for(; i <= siblingslength ; i++){
				if(bool){
					$("#amenu"+(i+1)).hide();
				}else{
					$("#amenu"+(i+1)).show();
				}

			}
			
		}
		
		
		
		
		
		//end   adaptMenu
		/**
		 * start showHtml
		 * 绑定叶子请求
		 * 
		 * */
		function showHtml(event){
			var i= event.data.index;
//			var src = $("#amenu"+(i+1)).attr('src');
//			alert(src);
			$("#page_iframe").attr("src","http://www.baidu.com");
//			$("#page_iframe").src("www.baidu.com");
//			alert("叶子节点"+i);
//			$("#amenu"+i).
			//停止事件的传播
			event.stopPropagation(); 
		}
		//end showHtml
		
		
		//end showMenu
// 		function hideChildNode(i){
// 			if($("#amenu"+i).children().length>0)
// 				{
// 					hideChildNode(("#amenu"+i).children());
// 				}

// 		}
// 		function hideMenu(i){
// 			if($("#amenu"+i).siblings().children()

// 		}
		/**
		 * start createmenu
		 * 创建生成菜单
		 * 
		 * */
		function createmenu(array){
			var menu = array['menu'];
			var nownode;
			var is = true;
			var menu_length=menu.length
			//循环所有菜单
// 			alert(menu[0]['id']==menu[6]['pid']);
			for(var father_i=0; father_i< menu_length ;father_i++)
			{
					nownode = menu[father_i];
					//以及菜单没有父菜单，做特殊处理
					if(nownode['pid']==0){
						$("#menu").append("<ul class='home home-menu menubody amenu' id='amenu"+nownode['id']+"' src='"+nownode['url']+"'>"+nownode['menuname']+"</ul>");
						$("#amenu"+nownode['id']).css("top",father_i*40);
						$("#amenu"+nownode['id']).css("z-index",1);
					}
					//如果不是pid!=0，说明有父菜单，循环已经输出的菜单，查找父菜单
					else{
						for(var child_i=0; child_i<father_i ;child_i++){
							father_menu = menu[child_i];
							if(nownode['pid']==father_menu['id']){
								$("#amenu"+father_menu['id']).append("<ul class='home home-menu menubody amenu child' id='amenu"+nownode['id']+"'  src='"+nownode['url']+"' >"+nownode['menuname']+"</ul>");
								//查看目前有多少个孩子节点，方便定义当前自己点的top距离
								var childlength=$("#amenu"+father_menu['id']).children().length;
								$("#amenu"+nownode['id']).css("top",40*childlength);
							}
							
						}
					}
					$("#amenu"+nownode['id']).attr("z",father_i);
// 				$("#amenu"+nownode['id']).bind('click', showmenu(nownode['id']));
			}//end for 
			for(var father_i=1; father_i< menu_length+1 ;father_i++){
// 				alert(father_i);
				if($("#amenu"+father_i).children().length>0){
					$("#amenu"+father_i).bind('click',{index: father_i}, showMenu);
				}
				else{
					$("#amenu"+father_i).bind('click',{index: father_i}, showHtml);
				}
			}
// 			var father_i=1;
// 			alert($("#amenu"+father_i).append("<p>a</p>"));
// 			$("#amenu"+1).bind('click', function(){$("#amenu"+1).children().toggle();});
		}

		//end createmenu