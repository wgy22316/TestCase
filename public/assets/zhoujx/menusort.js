var Menusort = function () {
	var padding=1;
	var showSortMenu = function(array){
		
		//画表
		MenuSortTable.init(array);
		$("#sortmenu").bind('click',{index: 1}, sortmenusub);
		$("#addmenu").bind('click',{index: 1}, addmenusub);
		$("#btnback").bind('click',{index: 1}, btnbacksub);
		$("#submitaddmenu").bind('click',{index: 1}, submitaddmenu);


	}

	function submitaddmenu(){
		var module= $("input[name='module']:checked").val();
		var selectmenu = $("#selectmenu").find("option:selected").val();
		var menuname= $("input[name='menuname']").val();
		var menuurl= $("input[name='menuurl']").val();
		var menusort=$("#sortidmenu").find("option:selected").val();
		var view = $("input[name='view']:checked").val();
		// alert(module+selectmenu+menuname+menuurl+menusort+view);
		if(menuname==''||menuname==null){
			$("#addmenu_tip").text('菜单名称不能为空');
			$("#addmenu_tip").fadeIn(1000).fadeOut(2000);
			return false;
		}
		if(menuurl==''||menuurl==null){
			$("#addmenu_tip").text('菜单链接不能为空');
			$("#addmenu_tip").fadeIn(1000).fadeOut(2000);
			return false;
		}

		var menu={};
		menu.module = module;
		menu.selectmenu = selectmenu;
		menu.menuname = menuname;
		menu.menuurl = menuurl;
		menu.menusort = menusort;
		menu.view = view;
		$.ajax({ 
				type: "POST", 
				url: "addmenu", 
				dataType: "json", 
				data: {"menu":menu}, 
				success: function(array){
					if(array['state']=="success"){
						$("#addmenu_tip").text('添加成功，下次登录生效');
						$("#addmenu_tip").fadeIn(1000).fadeOut(2000);
					}else{
						$("#addmenu_tip").text(array['error']);
						$("#addmenu_tip").fadeIn(1000).fadeOut(2000);
						 return false; 
					} 
				},
			    error: function(msg) {
		        	alert("越权");
		        }
		}); 
	}
	function addmenusub(){
		$("#btnmenu").hide();
		$("#sort_menu1").hide();
		$("#btnback").show();
		$("#menuhead").show();
		$.ajax({ 
				type: "POST", 
				url: "getparentmenu", 
				dataType: "json", 
				success: function(array){
					if(array['state']=="success"){
						// alert('success');
						showparentmenu(array);
					}else{
						$("#addmenu_tip").text(array['error']);
						$("#addmenu_tip").fadeIn(1000).fadeOut(2000);
						// $("#logintip").text("登陆");
						 return false; 
					} 
				},
			    error: function(msg) {
		        	alert("越权");
		        }
		}); 
	}

	function showparentmenu(array){
		parentmenu = array['parentmenu'];

		var innerHTML ='';
		innerHTML +="<option value=\"0\">无</option>";
		for(var i=0,length=parentmenu.length;i<length;i++){
			innerHTML +="<option value=\""+parentmenu[i].key+"\">"+parentmenu[i].value+"</option>";
		}
		// $("#selectmenu").append("innerHTML");
		$("#selectmenu").append(innerHTML);
	}


	function btnbacksub(){
		$("#btnmenu").show();
		$("#sort_menu1").show();
		$("#btnback").hide();
		$("#menuhead").hide();
	}

	function addsortmenuchild(array,innerHTML,padding){
				var left=padding*10;
				var son = array;
				for(var i=0,length=son.length;i<length;i++){
					innerHTML+=
						"<div class=\"menuone\">"+
						"<div class=\"menuid\" name=\"module\" >"+son[i].module_id+"</div> "+
						"<div class=\"menuid\" name=\"id\" >"+son[i].menu_id+"</div> "+
						"<div class=\"menuname\"  style=\"padding-left:"+left+"px;\"><input name=\"name\" width=\"100px\" value='"+son[i].menu_name+"'/></div>  "+
						"<div class=\"menuurl\"><input name=\"url\" style=\"width:400px ;\" value='"+son[i].menu_url+"'/></div>  "+
						"<div class=\"menupid\"><input name=\"pid\" style=\"width:50px ;\" value='"+son[i].menu_pid+"'/></div>  "+
						"<div class=\"menusort\"><input name=\"sort_id\" style=\"width:50px ;\" value='"+son[i].menu_sort_id+"'/></div> "+
						"</div>";
					if(son[i].son){
						innerHTML=addsortmenuchild(son[i].son,innerHTML,padding++);
					}
								
				}
			return innerHTML;
	}

	function sortmenusub(){
		 var menu = {};
		 var id=[];
		 var name=[];
		 var url=[];
		 var pid=[];
		 var sort_id=[];
		$("div[name=id]").each(function() {
            id.push($(this).text());

        });
        $("input[name=name]").each(function() {
        	if(($(this).val()!=null)&&($(this).val()!='')) {
            	name.push($(this).val());
			}
			else{
				$(this).css("background",'red');
				return false;
			}
        });
        $("input[name=url]").each(function() {
        	if(($(this).val()!=null)&&($(this).val()!='')) {
            	url.push($(this).val());
			}
			else{
				$(this).css("background",'red');
				return false;
			}

        });
		$("input[name=pid]").each(function() {
			if((!isNaN($(this).val()))&&($(this).val()!=null)&&($(this).val()!='')) {
            	pid.push($(this).val());
			}
			else{
				$(this).css("background",'red');
				return false;
			}

        });

		$("input[name=sort_id]").each(function() {
			if((!isNaN($(this).val()))&&($(this).val()!=null)&&($(this).val()!='')) {
          	  	sort_id.push($(this).val());
			}
			else{
				$(this).css("background",'red');
				return false;
			}
        });
        menu.id=id;
        menu.url=url;	
        menu.pid=pid;	
        menu.name=name;	
        menu.sort_id=sort_id;
		// alert(menu.name);
		// alert(menu.pid);
		// alert(menu.sort_id);
		if(0){

		}else{
		    	  	$.ajax({ 
				        type: "POST", 
				        url: "updatemenu", 
				        dataType: "json", 
				        data: {"menu":menu}, 
				        success: function(array){
				            if(array['state']=="success"){
				            	// alert("success");
				            	// top.location="../../Home/Home/home";
				            	$("#menu_tip").text("更新成功，下次登录生效");
				            	$("#menu_tip").fadeIn(1000).fadeOut(2000);
				            }else{
				            	$("#menu_tip").text(array['error']);
				            	$("#menu_tip").fadeIn(1000).fadeOut(2000);
				            	// $("#logintip").text("登陆");
				                return false; 
				            } 
				        },
				        error: function(msg) {
			        			alert("越权");
			        	}
				    }); 
		    }
	}

	return{
   		init: function(array){
   			  showSortMenu(array);
   		}
   	};


}();