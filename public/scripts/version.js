var oTable;
function reload(){
	oTable.fnDraw();
}
function getFormData(){

	var id=$("#id").val();
	var tc_testcase_id=$("#planID").val();
	var title=$("#title").val();
	var initial_condition=$("#initial_condition").val();
	var procedure=$("#procedure").val();
	var expected_result=$("#expected_result").val();
	var graph=$("#graph").val();
	var sound=$("#sound").val();
	var feature=$("#feature").val();
	var total_duration=$("#total_duration").val();
	var bug_id=$("#bug_id").val();
	var sendData = {"id":id,"tc_testcase_id":tc_testcase_id,"title":title,"initial_condition":initial_condition,"procedure":procedure,"expected_result":expected_result,
			"graph":graph,"sound":sound,"feature":feature,"total_duration":total_duration,"bug_id":bug_id};
	
	
	
//	console.info(sendData);
	
	
	return sendData;
}

function fnGetSelected (oTable){
		return oTable.$("tr.row_selected");
}
//改变“保存”按钮的onClick事件
function changeClickEvent(){
			$("#submitbtn").attr("onClick","fnEditRow();");
			var anSelected = fnGetSelected(oTable)[0];
			var data = oTable.fnGetData(anSelected);
			var updArr = [];
			$('input:checkbox[name="tc_testcase_id"]:checked').each(function(){ 
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
			}else
			{

				$("#editbtn").attr("href","#portlet-config"); 
				//用原来数据填充表单
				var inputs=["id","tc_testcase_id","title","initial_condition","procedure","expected_result","graph","sound","feature","total_duration","bug_id"];
				$("#editshow").show();
				
				for(var i=0;i<inputs.length;i++){
					$("#"+inputs[i]).val(data[i+1]);
				}
			}
}
//编辑一行数据
function fnEditRow(){
//	$("#submitbtn").attr("onClick","fnEditRow();");
		
			var sendData=getFormData();
	//		console.info(sendData);
			$.post("update",sendData,function(data,status){
				if(status=="success"){
					oTable.fnDraw();
				}else{
					alert("edit ajax request has failed！");
				}
				
			},"json");
		
	
}
//删除一行数据(old-not use)
function fnDeleteRow(){
        var anSelected = fnGetSelected(oTable)[0];
        if(anSelected){
        		var rowDel=  oTable.fnDeleteRow(anSelected,function(){},false);  //删除页面tr DOM节点
//        		console.info(rowDel[0]._aData);
        		var id=rowDel[0]._aData[1];
        		var tc_testcase_id=rowDel[0]._aData[2];
//        		alert(tc_testcase_id);
        		  $.post("delete",{"id":id,"tc_testcase_id":tc_testcase_id},function(data,status){
        		  		if(status=="success"){
        		  			oTable.fnDraw();
        		  		}else{
        		  			alert("send delete ajax request has failed !");
        		  		}
        		  },"json");
       }else{
    	   alert("请选择要删除的行记录！");
       }
}

//删除一或多行数据
function fnDeleteRows(){
	var delArr = [];
	$('input:checkbox[name="tc_testcase_id"]:checked').each(function(){ 
		delArr.push($(this).val());    
	});
	if(delArr.length == 0)
		alert("请选择要删除的行记录!");
	else
	{
		$.post("delete",{"ids":delArr,"tc_testcase_id":$("#planID").val()},function(data,status){
	  		if(status=="success"){
	  			oTable.fnDraw();
	  		}else{
	  			alert("send delete ajax request has failed !");
	  		}
		},"json");
	}
	
	
}

//添加之前clear已有数据
function clearForm(){
	$("input.form-control").each(function(){
		$(this).val("");
	});
	$("#procedure").val("");
	$("#editshow").hide();
//	$("#submitbtn").live("click",add());
	$("#submitbtn").attr("onClick","add();");
}
//添加新数据
function add(){
	
//	var id=$("#id").val();
//	var title=$("#title").val();
//	var initial_condition=$("#initial_condition").val();
//	var procedure=$("#procedure").val();
//	var expected_result=$("#expected_result").val();
//	var sendData = {"id":id,"title":title,"initial_condition":initial_condition,"procedure":procedure,"expected_result":expected_result};
//	console.info(sendData);
	var sendData=getFormData();
	$.post("add",sendData,function(data,status){
			//	console.info(status);
		if(status=="success"){
				if(data.code=="1"){
					oTable.fnDraw();	
					//reload();
			}else{
					alert("add test_case failed");
			}
			}else{
				alert("ajax请求失败");
		}

		},"json");
}


$(function(){
	  oTable=$('#table_id').DataTable({
		  "bFilter": false,//取消搜索框
	 // "sScrollX": "1000%",
   	 "aLengthMenu": [
   	                 [50, 100, -1],
   	                 [50, 100, "All"] // change per page values here
   	  ],
   	 "iDisplayLength": 50,  //设置初始化时显示多少条记录
//   	  "aoColumns": [
//	                    { "sTitle": "TC_ID" },
//	                    { "sTitle": "tc_testcase_id" },
//	                    { "sTitle": "title" },
//	                    { "sTitle": "initial_condition" },
//	                    { "sTitle": "procedure" },
//	                    { "sTitle": "expected_result" },
//	                    { "sTitle": "画面" },
//	                    { "sTitle": "声效" },
//	                    { "sTitle": "功能" },
//	                    { "sTitle": "total_duration" },
//	                    { "sTitle": "BUG_ID" },
//	                    { "sTitle": "创建者" },
//	                    { "sTitle": "创建时间"},
//	     ],
	     //自定义单元格
	     
	    "bProcessing": true,  //显示 ”正在处理” 提示条
        "bServerSide": true,  //使用服务器端数据填充表格
        "bSort":false,
//        "bAutoWidth":false,
       // "sScrollX":'disabled',
//        "sScrollY":"100%",
//        "bScrollInfinite":true, //无限滚动-.-
        "sAjaxSource":"showversionlist?tc_testcase_id="+$("#planID").val(),
        "fnDrawCallback": function () {
        	$("#goTop")[0].click(); 
            		//editTable();
//         	$(".group-checkable").attr("checked",false);
//         			trClick();
//         	//重画thead
//         	var data = [];
//         	var i = 0;
//         	$("tbody").children("tr:first").children("td").each(function(){
//         			//data.push($(this).css("width"));
//         			//alert(i+"......."+$(this).css("width"));
// //        		alert("111");
//         		$("#th"+i).css("width", $(this).outerWidth());
//         			i++;
        
//         	})
        	//var i = 0;
//        	alert(data);
//        	$(".taheadth").each(function(){
//        		alert(i+" "+data[i]+"   "+$(this))
//        		if(i==6)
//        		{
//        			//alert($(".table_head_th_in"));
//        			$(".taheadinth").each(function(){
//        				alert(i+"...");
//        				$(this).css("width", data[i++]);
//        			})
//        			
//        		}
//        		$(this).css("width", data[i++]);
//        	})
        			
        },
        "fnInitComplete": function(oSettings, json) {
        		//	console.info(oSettings);
        		//	console.info(json);
          }
	    });

	function trClick(){
		oTable.$('tr').click(function(){
			$(this).toggleClass('row_selected');
			$(this).toggleClass('active');
			if ($(this).hasClass('row_selected'))
	            $(this).children("td:first").children("input:checkbox").attr("checked", true);
	        
	        else 
	            $(this).children("td:first").children("input:checkbox").attr("checked", false);
			
			// var data = oTable.fnGetData( this );
			// console.info(data);
		});
	}
	
	
	$(".group-checkable").click(function(){
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

	
	
	
});