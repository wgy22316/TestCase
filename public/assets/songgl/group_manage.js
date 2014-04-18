var oTable;
function reload(){
	oTable.fnDraw();
}

$(function(){
	  oTable=$('#table_id').DataTable({
	  "bSort":false,
      "bFilter":false,
	  "aLengthMenu": [
   	                 [50, 100, -1],
   	                 [50, 100, "All"] // change per page values here
   	  ],
   	 "iDisplayLength": 50,  //设置初始化时显示多少条记录
   	  "oLanguage": {  
                    "sLengthMenu": "每页显示 _MENU_条",  
                    "sZeroRecords": "没有找到符合条件的数据", 
                    "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",  
                    "sInfoEmpty": "木有记录",  
                    "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",  
                    "sSearch": "搜索：",  
                    "oPaginate": {  
                        "sFirst": "首页",  
                        "sPrevious": "前一页",  
                        "sNext": "后一页",  
                        "sLast": "尾页"  
                    }  
                },
	     "aoColumnDefs": [
                        {
                            "mRender": function ( data, type, row ) {
                              var row="<input type='checkbox' value='"+row[0]+"'>";
                              return row;
                        },
                            "aTargets": [0],
                        },
                         {
                            "mRender": function ( data, type, row ) { 
                         													  	
                              var row="<button onClick='view(this)' type='button'  class='btn green' value='"+row[0]+"'>查看组内用户</button>";
                              return row;
                        },
                            "aTargets": [3],
                        },
                        {
                            "mRender": function ( data, type, row ) {
                              var row="<button onClick='edit(this)' type='button' data-toggle='modal' href='#portlet-config-edit' class='btn green' value='"+row[0]+"'>编辑</button>";
                              return row;
                        },
                            "aTargets": [4],
                        },
                        {
                            "mRender": function ( data, type, row ) {
                            	//console.log(data);
                            //	console.log(row);
                              var rowD="<button onClick='del(this)' type='button' class='btn red' value='"+row[0]+"'>删除</button>";
                              if(data=='1000'){   //共有组
	                              rowD="<button  type='button' class='btn gray disabled'>删除</button>";
                              }
                              return rowD;
                        },
                            "aTargets": [5],
                        },		

	                   { "bVisible": false,  "aTargets": [ 0 ] },
	                //      { "sClass": "center", "aTargets": [ 4 ] }
	                  ],
		
	    "bProcessing": true,  //显示 ”正在处理” 提示条
        "bServerSide": true,  //使用服务器端数据填充表格
        // "sAjaxSource":"listCaseByTask?task_id="+$('#task_id').val(),
        "sAjaxSource":"listAllGroups",
        "fnDrawCallback": function () {
        		//全选处理
        		var $allCheckbox=$("#allCheckbox");
        		var $table=$allCheckbox.parent().parent().parent().parent();
            	$allCheckbox.click(function(){
            		$table.find("input[type='checkbox']:gt(0)").each(function(){
            			if($allCheckbox.is(":checked")){
            				$(this).attr("checked",true);
            			}else{
            				$(this).attr("checked",false);
            			}
            		});
            	});
        	
        },
        "fnInitComplete": function(oSettings, json) {
        		//console.info(oSettings);
        		//	console.info(json);
          }
	    });	
	 

	 $("#add_module_id").bind("change",function(){
		var module_id=$(this).val();
		$("#add_menus_div").empty();		
		$.post("listMenuUnderModule",{"module_id":module_id},function(data){
			genTree($("#add_menus_div"),data);
			//全选
			selectAll($("#add_menus_div"));		
		},"json");
	});

	$("#closebtn_add").click(function(){
		$("#add_menus_div").empty();
	});

});
//编辑组
var edit=function(arg){
	var group_id=$(arg).val();
	$("#edit_menus_div").empty();
	$.post("listGroupById",{"group_id":group_id},function(data){
		if(data.status=='success'){
			var group_data =data.data[0];
			//console.log(group_data);

			$("#edit_hidden_group_id").attr("value",group_data.group_id);
			$("#edit_group_name").val(group_data.group_name);
			// $.post("listAllMenus",function(data_menu){
			$.post("listMenuUnderModule",{"module_id":group_data.module_id},function(data_menu){
				genTree($("#edit_menus_div"),data_menu);
				$.post("listMenuByGroup",{"menu_ids":group_data.menu_id},function(data_group_menu){
					// console.log(data_group_menu);
					for(var i=0;i<data_group_menu.length;i++){
						$("#edit_menus_div").find("input[type='checkbox']").each(function(){
							if($(this).val()==data_group_menu[i].menu_id){
								$(this).attr("checked",true);
							}
						});	
					}
					//全选
					selectAll($("#edit_menus_div"));
					////////////////////////
				},"json");
			},"json");				
		}else{
			alert("获取组信息出错啦！");
		}

	},"json");

}
//保存组内新的菜单
var saveGroup=function(){
	var group_id=$("#edit_hidden_group_id").val();
	var group_name=$("#edit_group_name").val();
	var menu_ids=[];
	$("#edit_menus_div").find("input[type='checkbox']").each(function(){
		if($(this).is(":checked")){
			menu_ids.push($(this).val());
		}
	});
	$.post("settingGroupMenu",{"group_id":group_id,"group_name":group_name,"menu_ids":menu_ids},function(data){
		reload();
		if(data=='2'){
			alert("修改组出错！")
			return;
		}
		//alert("修改组success")
	},"json");
	$("#edit_menus_div").empty();
}
//展示‘添加组’页面，ajax填充菜单和模块下拉框
var showAddGroupPage=function(){
	$("#add_menus_div").empty();//清空上次的菜单
	var $module_option=$("#add_module_id");   //模块下拉框
	$module_option.empty();
	$module_option.append("<option value='top'>------请选择模块------</option>");
	//获取module
	$.post("listModule",function(data){
		if(data.status=='success'){
			var module_length=data.data.length;
			for(var i=0;i<module_length;i++){
				var $opt ="<option value='"+data.data[i].module_id+"'>"+data.data[i].module_name+"</option>";
				$module_option.append($opt);
			}
		}else{
			alert("获取模块列表出错啦！");
		}
	},"json");
	
}

//添加新的组
var addGroup=function(){
	var group_name=$("#add_group_name").val();
	var module_id=$("#add_module_id").val();
	if (!$.trim(group_name)) {   //需要去除空格和空白字符
		alert("请填写组名！");
		return false;
	}
	if(module_id=='top'){
		alert("请选择所属模块！");
		return false;
	}

	var menu_ids=[];
	$("#add_menus_div").find("input[type='checkbox']").each(function(){
		if($(this).is(":checked")){
			menu_ids.push($(this).val());
		}
	});
	$.post("newGroup",{"group_name":group_name,"module_id":module_id,"menu_ids":menu_ids},function(data){
		
		$("#add_menus_div").empty();
		$("#add_group_name").val("");
		reload();
		$("#closebtn_add").trigger("click");
		if(data=='2'){
			alert("新建组出错了！");
			return;
		}
		//alert("新建组success");
	},"json");
	$("#add_menus_div").empty();
}
var del=function(arg){
	var group_id=$(arg).val();
	// alert("ss");
	if(confirm("删除不可恢复！请谨慎操作！确认删除该组？")){
		$.post("delGroup",{"group_id":group_id},function(data){
			reload();
			if(data=='2'){
				alert("删除出错了！");
				return;
			}
			//alert("删除组success");
		},"json");	
	}
}
var view=function(arg){
	var group_id=$(arg).val();
	var user_in_group_div=$("#user_in_group");
	$.post("listUserUnderGroup",{"group_id":group_id},function(data){
		if(data=='2'){
			user_in_group_div.html("<font color='red' size='4'> 获取用户出错了！</font>");
		}else{
			// user_in_group_div.
			if(data.length>0){
				user_in_group_div.empty();
				for(var i=0;i<data.length;i++){
					var span="<span style='margin:20px'><font size='3' color='blue'>"+data[i].username+"</font></span>";
					user_in_group_div.append(span);
				}
			}else{
				user_in_group_div.empty().append("<font color='red' size='4'>该组内没有用户！</font>");
			}

		}

	},"json");
}