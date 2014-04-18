$(function(){

    /*
    <div class='checkbox_div'>
        <input type="checkbox"><span>街机武侠</span>
        <ul><input type="checkbox">查看用例
            <ul><input type="checkbox">三级菜单</ul>
        </ul>
        <ul><input type="checkbox">导入用例</ul>
    </div>
    */
    /*
   var genTree=function(data){
        //该方法需要重写，用递归
        for(var i=0;i<data.length;i++){
            var $div=$("<div class='checkbox_div'></div>");
            if(data[i].menu_pid==0){   //顶级菜单
                var $parent_checkbox=$("<span style='font-weight:bold'><input type='checkbox' class='parent_menu' value='"+data[i].menu_id+"'/>"+data[i].menu_name+"</span>");
                if(data[i].son){   //如果有孩子节点
                    var son_object=data[i].son;
                    var son_length=son_object.length;
                    for(var son_i=0;son_i<son_length;son_i++){
                        var $son_checkbox=$("<ul><input class='child_menu' type='checkbox' value='"+son_object[son_i].menu_id+"'/>"+son_object[son_i].menu_name+"</ul>");
                        if(son_object[son_i].son){
                            var son_object_three=son_object[son_i].son;//三级菜单
                            for(var son_three_i=0;son_three_i<son_object_three.length;son_three_i++){
                            var $son_checkbox_three=$("<ul><input type='checkbox' value='"+son_object_three[son_three_i].menu_id+"'/>"+son_object_three[son_three_i].menu_name+"</ul>");
                            $son_checkbox.append($son_checkbox_three);
                            }
                        }
                        $parent_checkbox.append($son_checkbox);
                    }
                }
                $("#special_content_div").append($div.append($parent_checkbox));
            }else{
                alert("菜单格式有问题，必须存在顶级菜单！");
            }
        }
   } 



	//加载登陆用户的权限
	var user_id=$("#user_id").val();
   	$.post("showspecialprivilege",{"user_id":user_id},function(data,status){
            console.log(data);
            genTree(data);
            $(".parent_menu").bind('click',function(){   //全选，全不选
                var $child_checkbox=$(this).parent().find("input[type='checkbox']");
                if($(this).is(':checked')){
                    $child_checkbox.attr('checked',true);
                }else{
                    $child_checkbox.attr('checked',false);
                }
            });
            // $.post("gettargetspecialprivilege",{"user_id":user_id,"user_id_target":},function(data,status){

            // },"json");
            // console.log(data[0].menu_name);
   			// console.log(data.length);


   	},"json");
     */
     var genChildrenTree=function(parentNode,sonData){
        //console.log(sonData);
        //alert(sonData.length);
            var $ul=$("<ul><input type='checkbox' value='"+sonData.menu_id+"'>"+sonData.menu_name+"</ul>");
            if(sonData.son){
                for(var j=0;j<sonData.son.length;j++){
                    genChildrenTree($ul,sonData.son[j]);
                }
            }
      var res=parentNode.append($ul);
      return res;
   }
   var genTree=function(data){
        for(var i=0;i<data.length;i++){
            var $div=$("<div class='checkbox_div'></div>");
           // if(data[i].menu_pid==0){            //顶级菜单存在            
                genChildrenTree($div,data[i]);
                $("#special_content_div").append($div);               
          //  }else{
            //    alert("菜单格式有问题，必须存在顶级菜单！");
            //    return;
           // }
        }
   }

    //加载登陆用户的权限
    var user_id=$("#user_id").val();
    $.post("showspecialprivilege",{"user_id":user_id},function(data,status){
           // console.log(data);
            genTree(data);

            $(".checkbox_div").each(function(){
                $(this).find("input[type=checkbox]:first").click(function(){
                   var $child_checkbox=$(this).parent().find("input[type='checkbox']");
                   
                    if($(this).is(':checked')){
                        $child_checkbox.attr('checked',true);
                    }else{
                        $child_checkbox.attr('checked',false);
                    }
                });
            });
    },"json");

    $("#user_id_input").keyup(function(){
         //每次查询用户时要清除之前选中的checkbox
         $("input[type='checkbox']").each(function(){
                $(this).attr("checked",false);
        });
        //console.log($(this)[0].value);
        var user_id_target=$(this)[0].value;  
        if(!user_id_target){
            return;
        }
        // if(user_id_target!=user_id&&user_id_target!=username){
            $.post("showusername",{"user_id_target":user_id_target},function(data,status){
            var targetName=data.targetName;
            $("#targetName_span").empty();
            if(targetName.length>0){
               // console.log(targetName);
               /*
                $("#targetName_span").append("<span><input id='user_id_target_checkbox' type='checkbox' value='"+targetName[0].user_id+"'>"+targetName[0].username+"</span>");
                */
                for(var i=0;i<targetName.length;i++){
                    // if(targetName[i].user_id==user_id){   //当前登录用户不显示【已取消】
                    //     continue;
                    // }
                    $("#targetName_span").append("<span><input value='"+targetName[i].user_id+"' type='radio' name='user_id_target'>"+targetName[i].username+"</span>");
                }
                // $("input[type='radio']").bind('click',function(){
                //     if($(this).is(':checked')){
                //         alert("选择");
                //     }
                // });

                // $("#user_id_target_checkbox").bind('click',function(){
                $("input[type='radio']").bind('click',function(){
                    //console.log($(this));
                    //多个单项按钮之间切换时要清除以前已经选中的checkbox
                     $("input[type='checkbox']").each(function(){
                        $(this).attr("checked",false);
                    });
                    var user_id_target=$(this).val();
                    if($(this).is(':checked')){  //如果勾选了用户名
                        /* 自己可以取消自己的权限
                        if(user_id_target==user_id){
                            alert("您无法给自己授权！");
                            $(this).attr("checked",false);
                            $("input[type='checkbox']").each(function(){
                                $(this).attr("checked",false);
                            });
                            return;
                        } 
                        */                               
                                //gettargetgroupprivilege
                        $.post("gettargetspecialprivilege",{"user_id":user_id,"user_id_target":user_id_target},function(data,status){
                            if(data){   //如果有相同的，则遍历并勾选 
                                for(var i=0;i<data.length;i++){
                                    $("input[type='checkbox']").each(function(){
                                        if($(this).val()==data[i]){
                                            $(this).attr("checked",true);
                                        }
                                    });
                                }
                            }
                        },"json");
                    }
                });
            }else{
                $("#targetName_span").append("<font color='red' size='5'>系统中没有该用户！</font>");
            }
        },"json");    
        // }else{
        //     alert("您无法给自己授权！");
        //     return;
        // }
        // console.log($(this).attr('value'));
        // alert("ddddssss");
    });
    /*
	///原来的点击查询用户方式
	$("#user_id_btn").click(function(){
    	//$("#content").empty();
    	$("input[type='checkbox']").attr("checked",false);
    	var user_id_target=$("#user_id_input").val();
        if(user_id_target){

            if(user_id_target!=user_id){
            $.post("showusername",{"user_id_target":user_id_target},function(data,status){
            var targetName=data.targetName;
            $("#targetName_span").empty();
            if(targetName){
               // console.log(targetName);
                $("#targetName_span").append("<span><input id='user_id_target_checkbox' type='checkbox' value='"+targetName[0].user_id+"'>"+targetName[0].username+"</span>");
                $("#user_id_target_checkbox").bind('click',function(){
                    //console.log($(this));
                    var user_id_target=$(this).val();
                    if($(this).is(':checked')){  //如果勾选了用户名
                        $.post("gettargetspecialprivilege",{"user_id":user_id,"user_id_target":user_id_target},function(data,status){
                            if(data){   //如果有相同的，则遍历并勾选 
                                for(var i=0;i<data.length;i++){
                                    $("input[type='checkbox']").each(function(){
                                        if($(this).val()==data[i]){
                                            $(this).attr("checked",true);
                                        }
                                    });
                                }
                            }
                        },"json");
                        
                        // $(".parent_menu").parent().find("input[type='checkbox']").bind('click',function(){
                        //     if($(this).is(":checked")){
                        //         console.log($(this).parent().parent());
                        //     }
                        // });
                    }
                });
            }else{
                $("#targetName_span").append("<font color='red' size='5'>系统中没有该用户！</font>");
            }
         },"json");    
            }else{
                alert("您不能给自己授权！");
            }
        }else{
            alert("请输入用户的工号！");
        }
    });
    */
    $("#modify").click(function(){
    	var menu_ids=[];
        var user_id_target="";
        $("input[type='radio']").each(function(){
            if($(this).is(':checked')){
                user_id_target=$(this).val();
            }    
        });
        
        // alert(user_id_target);
        if(!user_id_target){
            alert("请选择被授权用户！");
            return;
        }

    	// if(!$("#user_id_target_checkbox").attr("checked")){
    	// 	alert("请选择被授权用户");
    	// }else{
    		//var user_id_target=	$("#user_id_target_checkbox").val();
    		$("#special_content_div input:checked").each(function(){
    			menu_ids.push($(this).val());
    		});
    		// if(menu_ids.length<1){
    		// 	alert("请选择要授予该用户的权限！");
    		// }else{
    			$.post("settingprivilege",{"user_id":user_id,"user_id_target":user_id_target,"menu_ids":menu_ids},function(data,status){
    				console.log(data);
                    if(data==1){
                        alert("更新权限success!");
                    // }else if(data==3){
                        // alert("权限没有发生变化");
                    }else{
                        alert("授权失败！");
                    }

    			},"json");
    		// }
    	// }

    });

});