$(function(){
    var user_id=$("#user_id").val();
   	$.post("showmyprivilege",{"user_id":user_id},function(data,status){
   			var mymenus =data.menus;
    		for(var i=0;i<mymenus.length;i++){
    			var div_col=$("<div class='col-md-6'></div>");
    			var div_box=$("<div class='portlet box green'></div>");
    			var div_title=$("<div class='portlet-title'>");
    			var div_caption=$("<div class='caption'><i class='fa fa-reorder'></i>"+mymenus[i].menu_name+"</div>");
    			var checkbox=$("<span><input type='checkbox' value='"+mymenus[i].menu_id+"'>"+mymenus[i].menu_name+"【父菜单】</span>");
    			var div_body=$("<div class='portlet-body'></div>");
    			div_body.append(checkbox);
    			if (mymenus[i].son){
    				for(var j=0;j<mymenus[i].son.length;j++){
	    				var div_body_checkbox="<span><input  value='"+mymenus[i].son[j].menu_id+"' type='checkbox'>"+mymenus[i].son[j].menu_name+"</span>";
    					div_body.append(div_body_checkbox);
    					}
    			}
    			div_title.append(div_caption)
    			div_box.append(div_title).append(div_body);
    			div_col.append(div_box);
				$("#content").append(div_col);
			}
   	},"json");

    $("#user_id_btn").click(function(){
    	//$("#content").empty();
    	$("input[type='checkbox']").attr("checked",false);
    	var user_id_target=$("#user_id_input").val();
        if(user_id_target){
            $.post("showusername",{"user_id_target":user_id_target},function(data,status){
            var targetName=data.targetName;
            $("#targetName_span").empty();
            if(targetName){
                console.log(targetName);
                $("#targetName_span").append("<span><input id='user_id_target_checkbox' type='checkbox' value='"+targetName[0].user_id+"'>"+targetName[0].username+"</span>");
            }else{
                $("#targetName_span").append("<font color='red' size='5'>系统中没有该用户！</font>");
            }
        },"json");    
        }else{
            alert("请输入用户的工号");
        }
    	
    });

    $("#modify").click(function(){
    	var menu_ids=[];
    	if(!$("#user_id_target_checkbox").attr("checked")){
    		alert("请选择被授权用户");
    	}else{
    		var user_id_target=	$("#user_id_target_checkbox").val();
    		$("#content input:checked").each(function(){
    			menu_ids.push($(this).val());
    		});
    		if(menu_ids.length<1){
    			alert("请选择要授予该用户的权限！");
    		}else{
    			$.post("settingprivilege",{"menu_ids":menu_ids},function(data,status){
    				

    			},"json");
    		}
    	}

    });

});