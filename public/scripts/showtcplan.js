var oTable;
function reload(){
	oTable.fnDraw();
}
function getFormData(){
	var plan_id=$("#plan_id").val();
	var  plan_name=$("#plan_name").val();
	var	exe_level=$("#execution_level").val();
	var	project =$("#project").val();
	var	build=$("#build").val();
	var	sku=$("#sku").val();
	var	valbyqa=$("#validatedbyqa").val();
	var sendData ={"plan_id":plan_id,"plan_name":plan_name,"exe_level":exe_level,"project":project,"build":build,"sku":sku,"valbyqa":valbyqa};
	return sendData;
}
//删除单条数据时候的
/*
function fnGetSelected (oTable){
		return oTable.$("tr.row_selected");
}
*/
function fnGetSelected (oTable){
	var checkboxs =[];
			$('input:checkbox[name="plan_id"]:checked').each(function(){
				checkboxs.push($(this).val());
	});
		//console.info(checkboxs);
			

		console.info($('tr:input:checkbox[name="plan_id"]:checked'));
		return oTable.$("tr.row_selected");
}

//改变“保存”按钮的onClick事件
function changeClickEvent(){
			$("#submitbtn").attr("onClick","fnEditRow();");
			var anSelected = fnGetSelected(oTable)[0];
			var data = oTable.fnGetData(anSelected);
			console.log(data);
			if(anSelected){
				$("#editbtn").attr("href","#portlet-config"); 
				//用原来数据填充表单
				var inputs=["plan_id","plan_name","execution_level","validatedbyqa","project","build","sku"];
				//["<input type="checkbox" name="plan_id[]" value="1"/>", "1", "背包", "1", "song", "海贼", "0.0.01", "android", "2014-03-12 11:10:26"] 
				for(var i=0;i<inputs.length;i++){
					
					$("#"+inputs[i]).val(data[i+1]);
				}
			}else{
				$("#editbtn").attr("href",""); //避免没选中行还是弹出表单
				alert("请选择要编辑的行记录！");
				//$("#closebtn").trigger("click");
				//console.info($("#edit").val());
			}
}
//编辑一行数据
function fnEditRow(){
//	$("#submitbtn").attr("onClick","fnEditRow();");
		var sendData=getFormData();
		console.info(sendData);
		$.post("edit",sendData,function(data,status){
			if(status=="success"){
				oTable.fnDraw();
			}else{
				alert("edit ajax request has failed！");
			}
			
		},"json");
	
}
//删除一行或者多行数据
function fnDeleteRow(){
	var anSelecteds = fnGetSelected(oTable);
	var delDataLength=anSelecteds.length;
       // $("tr:gt(0)");
        if(delDataLength>0){
        		var plan_ids=[];
        		for(var i=0;i<delDataLength;i++){
        			var rowDel=  oTable.fnDeleteRow(anSelecteds[i],function(){},false);  //删除页面tr DOM节点
        			var id=rowDel[0]._aData[1];
        			plan_ids.push(id);	
        		}
        		console.info(plan_ids);
        		//console.info(rowdel[0]._aData);
        		var ids=plan_ids.join(",").toString();
        		  $.post("del",{"ids":ids},function(data,status){
        		  		if(status=="success"){
        		  			if(data.code=="1"){
        		  				alert("删除成功啦！");
        		  				$(".group-checkable").attr("checked",false);
        		  				oTable.fnDraw();
        		  			}else{
        		  				alert("删除失败了~~");
        		  			}
        		  		}else{
        		  			alert("send delete ajax request has failed ! please check your network connection！");
        		  		}
        		  },"json");
       }else{
    	   alert("请选择要删除的行记录！");
       }
}

//添加之前clear已有数据
function clearForm(){
	$("input.form-control").each(function(){
		$(this).val("");
	});
//	$("#submitbtn").live("click",add());
	$("#submitbtn").attr("onClick","add();");
}
//添加新数据
function add(){
	
//	var id=$("#id").val();
//	var	bkname=$("#bkname").val();
//	var	bkprice =$("#bkprice").val();
//	var	bkpublish=$("#bkpublish").val();
//	var	bkauthor=$("#bkauthor").val();
//	var	bktime=$("#bktime").val();
//	var	bkcount=$("#bkcount").val();
//	var sendData ={"id":id,"bkname":bkname,"bkauthor":bkauthor,"bkprice":bkprice,"bkpublish":bkpublish,"bktime":bktime,"bkcount":bkcount};
	//console.info(sendData);
	var sendData=getFormData();
	$.post("add",sendData,function(data,status){
			//	console.info(status);
		if(status=="success"){
				if(data.code=="1"){
					oTable.fnDraw();	
					//reload();
			}else{
					alert("add book failed");
			}
			}else{
				alert("ajax请求失败");
		}

		},"json");
}


$(function(){
	  oTable=$('#table_id').DataTable({
	  "bSort":false,

	  "aLengthMenu": [
   	                 [50, 100, -1],
   	                 [50, 100, "All"] // change per page values here
   	  ],
   	 "iDisplayLength": 50,  //设置初始化时显示多少条记录
//   	  "aoColumns": [
//	                    { "sTitle": "TC ID" },
//	                    { "sTitle": "Title" },
//	                    { "sTitle": "Duration" },
//	                    { "sTitle": "画面" },
//	                    { "sTitle": "声效" },
//	                    { "sTitle": "功能" },
//	     ],
	     "aoColumnDefs": [
	                      {
	                          "mRender": function ( data, type, row ) {
	                        	  var row="<a href='../../table/table/index?tc_testcase_id="+row[1]+"'>"+row[2]+"</a>";
								  return row;
	                          },
	                          "aTargets": [2],
	                      },
	                    //  { "bVisible": false,  "aTargets": [ 3 ] },
	                //      { "sClass": "center", "aTargets": [ 4 ] }
	                  ],
		
	    "bProcessing": true,  //显示 ”正在处理” 提示条
        "bServerSide": true,  //使用服务器端数据填充表格
        "sAjaxSource":"listall",
        "fnDrawCallback": function () {
            		//editTable();
        			trClick();
        },
        "fnInitComplete": function(oSettings, json) {
        		//	console.info(oSettings);
        		//	console.info(json);
          }
	    });
//	function addCheckbox(){
//		oTable.$("tr").each(function(){
//			//alert("sss");
//			
//		});
//		
//	}
	$(".group-checkable").click(function(){
			/*
		   var checked=$(this).is(":checked");
		   if(checked==true){
			   $('input[type="checkbox"]').attr("checked",true);
		   }else{
			   $('input[type="checkbox"]').attr("checked",false);
		   }
		   */
		   var checked=$(this).is(":checked");
		   if(checked==true){
			   $('input[type="checkbox"]').attr("checked",true);
			   $('tr:gt(0)').addClass('active');
			   $('tr:gt(0)').addClass('row_selected');
		   }else{
			   $('input[type="checkbox"]').attr("checked",false);
			   $('tr:gt(0)').removeClass("active");
			   $('tr:gt(0)').removeClass('row_selected');
		   }
	});
	
	function trClick(){
		oTable.$('tr').click(function(){
		
			$(this).toggleClass('row_selected');
	
			if ( $(this).hasClass('row_selected') ) {
				$(this).children("td:first").children("input:checkbox").attr("checked", true);
	        //   $(this).removeClass('row_selected');
	        }
	        else {
	        	$(this).children("td:first").children("input:checkbox").attr("checked", false);
	           // oTable.$('tr.row_selected').removeClass('row_selected');
	            //$(this).addClass('row_selected');
	        }
		});
	}
	
	
	
	
});