$(function(){
	var genChildrenTree=function(parentNode,sonData){
            var $ul=$("<ul><input type='checkbox' value='"+sonData.menu_id+"'>"+sonData.menu_name+"</ul>");
            if(sonData.son){
                for(var j=0;j<sonData.son.length;j++){
                    genChildrenTree($ul,sonData.son[j]);
                }
            }
      var res=parentNode.append($ul);
      return res;
   }
   var genTree=function(content_div,data){
        for(var i=0;i<data.length;i++){
            var $div=$("<div ></div>");
            genChildrenTree($div,data[i]);
            //修复组内子菜单也可以选中bug
            $div.find("input[type='checkbox']").each(function(){
                $(this).attr("disabled","disabled");
            });
            content_div.append($div);               
        }
   }
    //加载登陆用户的权限
    var user_id=$("#user_id").val();
    $.post("showgroupprivilege",{"user_id":user_id},function(data,status){   //加载用户的组
           // console.log(data);
           for(var i=0;i<data.length;i++){   //循环用户的组ID
              // var group_id=data[i];
           		$.post("listMenusUnderGroup",{"group_id":data[i]},function(resData){
           			var	$group_div =$("<div class='group_div'></div>");
           			genTree($group_div,resData.menuData);
           			var $ul=$("<div class='group_div_title'><div class='title_checkbox'><input type='checkbox' value='"+resData.group_id+"' class='group_title'><b>"+resData.group_name+"</b></div></div>");
           			$ul.append($group_div);
           			$("#group_content_div").append($ul);
                //全选问题
                $(".group_title").each(function(){
                   $(this).live("click",function(){
                      var $child_checkbox=$(this).parent().parent().find("input[type='checkbox']:gt(0)");
         
                      if($(this).is(':checked')){
                         $child_checkbox.attr('checked',true);
                      }else{
                         $child_checkbox.attr('checked',false);
                      }
                    });
                });
           		},"json");
           }
    },"json");
    //加载用户名并勾选相同的组
    $("#user_id_input").keyup(function(){
         //每次查询用户时要清除之前选中的checkbox
         $("input[type='checkbox']").each(function(){
                $(this).attr("checked",false);
        });
        var user_id_target=$(this)[0].value;  
        if(!user_id_target){
            return;
        }
            $.post("showusername",{"user_id_target":user_id_target},function(data,status){
            var targetName=data.targetName;
            $("#targetName_span").empty();
            if(targetName.length>0){
               
                for(var i=0;i<targetName.length;i++){
                    // if(targetName[i].user_id==user_id){   //查到的结果是当前登录的用户
                    //     //continue;
                    // }
                    $("#targetName_span").append("<span><input value='"+targetName[i].user_id+"' type='radio' name='user_id_target'>"+targetName[i].username+"</span>");
                }
                $("input[type='radio']").bind('click',function(){
                    //console.log($(this));
                    //多个单项按钮之间切换时要清除以前已经选中的checkbox
                     $("input[type='checkbox']").each(function(){
                        $(this).attr("checked",false);
                    });
                    var user_id_target=$(this).val();
                    if($(this).is(':checked')){  //如果勾选了用户名                           
                                //gettargetgroupprivilege
                        $.post("gettargetgroup",{"user_id_target":user_id_target},function(data,status){
                            if(data){   //如果有相同的，则遍历并勾选 
                                for(var i=0;i<data.length;i++){
                                    $(".group_title").each(function(){
                                        // alert("5454545454545");
                                        if($(this).val()==data[i]){
                                            //$(this).attr("checked",true);
                                            $(this).click();
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
    });
    //修改
    $("#modify").click(function(){
    var user_id=$("#user_id").val();
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
    var group_ids=[];
    $(".group_title").each(function(){
      if($(this).is(":checked")){
        group_ids.push($(this).val());
      }
    });
    $.post("settinggroup",{"user_id":user_id,"user_id_target":user_id_target,"group_ids":group_ids},function(data,status){
       // console.log(data);
        if(data==1){
            alert("更新权限success!");
        }else{
            alert("授权失败！");
        }

    },"json");
        // }
});


});

