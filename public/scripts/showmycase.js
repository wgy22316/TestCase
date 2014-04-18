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
			
			var anSelecteds =fnGetSelected(oTable);
			//console.log(anSelecteds);
		//	alert(anSelecteds.length);
			var anSelected = fnGetSelected(oTable)[0];
			var data = oTable.fnGetData(anSelected);
	
			var updArr = [];
			$('input:checkbox[name="plan_id"]:checked').each(function(){ 
				updArr.push($(this).val());    
			});
			if(updArr.length < 1)
			{	
				$("#editbtn").attr("href","");
				alert("请选择要修改的行记录！");
			}else if(updArr.length > 1)
			{	
							$("#editbtn").attr("href","");
								alert("一次修改不能超过一行哦！");
			}else{
				$("#editbtn").attr("href","#portlet-config"); 
				//用原来数据填充表单
				var inputs=["plan_id","plan_name","execution_level","validatedbyqa","project","build","sku"];
				//["<input type="checkbox" name="plan_id[]" value="1"/>", "1", "背包", "1", "song", "海贼", "0.0.01", "android", "2014-03-12 11:10:26"] 
				for(var i=0;i<inputs.length;i++){
					$("#"+inputs[i]).val(data[i+1]);
				}
			}
}
//编辑一行数据
function fnEditRow(){
//	$("#submitbtn").attr("onClick","fnEditRow();");
		var sendData=getFormData();
	//	console.info(sendData);
		$.post("edit",sendData,function(data,status){
			if(status=="success"){
				if(data.code=="1"){
					alert("修改成功啦！");
					oTable.fnDraw();
				}else{
					alert("修改出错啦~~");
				}
			}else{
				alert("edit ajax request has failed！please check your network connection！");
			}
		},"json");
	
}
//删除一行数据
function fnDeleteRow(){
	//$(this).children("td:first").children("input:checkbox").attr("checked", true);
      // console.info($("input:gt(0):checked").length);
	//var anSelected = fnGetSelected(oTable)[0];
	var anSelecteds = fnGetSelected(oTable);
	//console.log(anSelecteds.length);
	//console.log(anSelecteds[0]);
	//console.log(anSelecteds[1]);
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
	console.log(sendData);
	$.post("add",sendData,function(data,status){
			//	console.info(status);
		if(status=="success"){
				if(data.code=="1"){
					oTable.fnDraw();	
					//reload();
			}else{
					alert("add plan failed");
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
   	                 [5, 15, 20, -1],
   	                 [5, 15, 20, "All"] // change per page values here
   	  ],
   	 "iDisplayLength": 5,  //设置初始化时显示多少条记录
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
	                        	  var row="<a href='../../table/table/index?plan_id="+row[1]+"&user_id="+user_id+"'>"+row[2]+"</a>";
								  return row;
	                          },
	                          "aTargets": [2],
	                      },
	                      
	                    //  { "bVisible": false,  "aTargets": [ 3 ] },
	                //      { "sClass": "center", "aTargets": [ 4 ] }
	                  ],
		
	    "bProcessing": true,  //显示 ”正在处理” 提示条
        "bServerSide": true,  //使用服务器端数据填充表格
        "sAjaxSource":"listmycase",
        "fnDrawCallback": function () {
            		//editTable();
        			trClick();
        },
        "fnInitComplete": function(oSettings, json) {
        		//	console.info(oSettings);
        		//	console.info(json);
          }
	    });
	  //解决 全选
		$(".group-checkable").click(function(){
			   var checked=$(this).is(":checked");
			   if(checked==true){
				   $('input[type="checkbox"]').attr("checked",true);
				   $("tr:gt(0)").addClass("row_selected");
			   }else{
				   $('input[type="checkbox"]').attr("checked",false);
				 $("tr:gt(0)").removeClass("row_selected");
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
		
			// var data = oTable.fnGetData( this );
			// console.info(data);
		});
	}
	
	
	
	
});