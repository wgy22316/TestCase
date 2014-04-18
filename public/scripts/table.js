// //展开历史列表
// var myFrame=$(parent.document.getElementById("myFrame"));
// var closeVersion=function(){
// 	myFrame.attr("cols","100%,*");
// 	var tip="<i class='fa  fa-hand-o-right'></i>展开历史更新列表";
// 	$("#versionbtn").attr("onclick","extendVersion()").html(tip);
// }
// 	var extendVersion=function(){
// 		myFrame.attr("cols","50%,*");
// 		var tip="<i class='fa  fa-hand-o-left'></i>关闭历史更新列表";
// 		$("#versionbtn").attr("onclick","closeVersion()").html(tip);
// }

//导出TestCase ajax无流返回 使用js动态构建form表单提交
var exportCases = function()
{
	var case_v_id = $("#case_v_id").val();
	var form = $("<form>");   //定义一个form表单
       form.attr('style','display:none');   //在form表单中添加查询参数
       form.attr('target','');
       form.attr('method','post');
       form.attr('action',"../../excel/excelopera/exportexcel");

       var input1 = $('<input>'); 
       input1.attr('type','hidden'); 
       input1.attr('name','case_v_id'); 
       input1.attr('value',case_v_id); 

       $('body').append(form);  //将表单放置在web中
       form.append(input1);   //将查询参数控件提交到表单上
       form.submit();   //表单提交
      form.remove();
}

//datatable的一些操作
var oTable;
function reload(){
	oTable.fnDraw();
}

/* Formating function for row details */
function fnFormatDetails ( nTr )
{
    var aData = oTable.fnGetData( nTr );
    var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
    sOut += '<tr><td>initial_condition:</td><td>'+aData[4]+'</td></tr>';
    sOut += '<tr><td>procedure:</td><td>'+aData[5]+'</td></tr>';
    sOut += '<tr><td>expected_result:</td><td>'+aData[6]+'</td></tr>';
    sOut += '</table>';
     
    return sOut;
}


function getFormData(){
	var tc_id=$("#tc_id").val();
	var case_v_id=$("#case_v_id").val();
	var title=$("#title").val();
	var initial_condition=$("#initial_condition").val();
	var procedure=$("#procedure").val();
	var expected_result=$("#expected_result").val();
	var graph=$("#graph").val();
	var sound=$("#sound").val();
	var feature=$("#feature").val();
	var total_duration=$("#total_duration").val();
	var bug_id=$("#bug_id").val();
	var sendData = {"tc_id":tc_id,"case_v_id":case_v_id,"title":title,"initial_condition":initial_condition,"procedure":procedure,"expected_result":expected_result,
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
			$('input:checkbox[name="tc_id"]:checked').each(function(){ 
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
				var inputs=["tc_id","title","initial_condition","procedure","expected_result","graph","sound","feature","total_duration","bug_id"];
				$("#editshow").show();
				//console.info(data);
				for(var i=0;i<inputs.length;i++){
					//alert(data[i+2]);
					$("#"+inputs[i]).val(data[i+2]);
				}
			}
}
//编辑一行数据
function fnEditRow(){
//	$("#submitbtn").attr("onClick","fnEditRow();");
		
			var sendData=getFormData();
			//console.info(sendData);
			$.post("update",sendData,function(data,status){
				if(data.code==1 || data.code == 3){
					oTable.fnDraw();
				}else{
					alert("出错了");
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
        		var case_v_id=$("#case_v_id").val();
//        		alert(case_v_id);
        		  $.post("delete",{"id":id,"case_v_id":case_v_id},function(data,status){
        		  		if(data.code == 1){
        		  			oTable.fnDraw();
        		  		}else if(data.code==2){
        		  			alert("出错了");
        		  		}else{
        		  			alert("没有获得要删除的数据，请重选");
        		  		}
        		  },"json");
       }else{
    	   alert("请选择要删除的行记录！");
       }
}

//删除一或多行数据
function fnDeleteRows(){
	var delArr = [];
	$('input:checkbox[name="tc_id"]:checked').each(function(){ 
		delArr.push($(this).val());    
	});
	if(delArr.length == 0)
		alert("请选择要删除的行记录!");
	else
	{
		$.post("delete",{"ids":delArr,"case_v_id":$("#case_v_id").val()},function(data,status){
	  		if(data.code==1){
	  			oTable.fnDraw();
	  		}else if(data.code == 2){
	  			alert("send delete ajax request has failed !");
	  		}
	  		else
	  			alert("tc_id或案例id不存在");
		},"json");
	}
	
	
}

//添加之前clear已有数据
function clearForm(){
	$("input.form-control").each(function(){
		$(this).val("");
	});
	$("select.form-control").each(function(){
		$(this).val("");
	})
	$("#procedure").val("");
	$("#editshow").hide();
//	$("#submitbtn").live("click",add());
	$("#submitbtn").attr("onClick","add();");
}
//添加新数据
function add(){
	
	var sendData=getFormData();
	$.post("add",sendData,function(data,status){
			//	console.info(status);
		if(status=="success"){
			if(data.code=="1"){
				oTable.fnDraw();	
				//reload();
			}else if(data.code == '4'){
				alert("标题或期望结果不能为空");
			}else{
				alert("发生了错误");
			}
		}else{
			alert("ajax请求失败");
		}

		},"json");
}


$(function(){
	  oTable=$('#table_id').DataTable({
		  
	 // "sScrollX": "1000%",
   	 "aLengthMenu": [
   	                 [50, 100, -1],
   	                 [50, 100, "All"] // change per page values here
   	  ],
   	 "iDisplayLength": 50,  //设置初始化时显示多少条记录
   	 "bFilter":false,
   	 "bSort":false,
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
						  	  var row;
						  	  if(row[7]=='Pass'){
						  		  row="<font color='green'>"+row[7]+"</font>";
						  	  }else if(row[7]=='Fail'){
						  		  row="<font color='red'>"+row[7]+"</font>";
						  	  }else{
						  		  row="<font color='gray'>"+row[7]+"</font>";
						  	  }
								  return row;
						    },
						    "aTargets": [7],
						},
						{
							  "mRender": function ( data, type, row ) {
								  var row;
								  if(row[8]=='Pass'){
									  row="<font color='green'>"+row[8]+"</font>";
								  }else if(row[8]=='Fail'){
									  row="<font color='red'>"+row[8]+"</font>";
								  }else{
									  row="<font color='gray'>"+row[8]+"</font>";
								  }
								  return row;
							  },
							  "aTargets": [8],
						},
						{
							  "mRender": function ( data, type, row ) {
								  var row;
								  if(row[9]=='Pass'){
									  row="<font color='green'>"+row[9]+"</font>";
								  }else if(row[9]=='Fail'){
									  row="<font color='red'>"+row[9]+"</font>";
								  }else{
									  row="<font color='gray'>"+row[9]+"</font>";
								  }
								  return row;
							  },
							  "aTargets": [9],
						},
						// { "bVisible": false,  "aTargets": [ 3 ] },
						{ "bVisible": false,  "aTargets": [ 4 ] },
						{ "bVisible": false,  "aTargets": [ 5 ] },
						{ "bVisible": false,  "aTargets": [ 6 ] }
						],
		
	    "bProcessing": true,  //显示 ”正在处理” 提示条
        "bServerSide": true,  //使用服务器端数据填充表格
        "bSort":false,
//        "bAutoWidth":false,
       // "sScrollX":'disabled',
//        "sScrollY":"100%",
//        "bScrollInfinite":true, //无限滚动-.-
        "sAjaxSource":"show?case_v_id="+$("#case_v_id").val(),
        "fnDrawCallback": function () {
            		//editTable();
        	$(".group-checkable").attr("checked",false);
        			// trClick();
        			trdbClick();
        			// alert("hello");
        	// $("#goTop").trigger('click');
				$("#goTop")[0].click();        
        	// //重画thead
        	// var data = [];
        	// var i = 0;
        	// $("tbody").children("tr:first").children("td").each(function(){
        			
        	// 	$("#th"+i).css("width", $(this).outerWidth());
        	// 		i++;
        
        	// })

        			
        },
        "fnInitComplete": function(oSettings, json) {
        		//	console.info(oSettings);
        		//	console.info(json);
          }
	    });

	// function trClick(){
	// 	oTable.$('tr').click(function(){
	// 		$(this).toggleClass('row_selected');
	// 		//$(this).toggleClass('active');
	// 		if ($(this).hasClass('row_selected'))
	//             $(this).children("td:first").children("input:checkbox").attr("checked", true);
	        
	//         else 
	//             $(this).children("td:first").children("input:checkbox").attr("checked", false);
			
	// 		// var data = oTable.fnGetData( this );
	// 		// console.info(data);
	// 	});
	// }
		
		function trdbClick(){
			oTable.$('tr').dblclick(function(){
				$(this).addClass('row_selected');
	            $(this).children("td:first").children("input:checkbox").attr("checked", true); 
				$("#editbtn")[0].click();
				$(this).removeClass('row_selected');
				$(this).children("td:first").children("input:checkbox").attr("checked", false);
			})
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

	$('.row-details').live( 'click', function () {
        var nTr = $(this).parents('tr')[0];
        if ( oTable.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
            $(this).removeClass('row-details-open');
            $(this).addClass('row-details-close');
            oTable.fnClose( nTr );
        }
        else
        {
            /* Open this row */
            $(this).removeClass('row-details-close');
            $(this).addClass('row-details-open');
            
            oTable.fnOpen( nTr, fnFormatDetails(nTr), 'details' );
        }
    } );
	
	$('.chk').live( 'click', function () {
        var nTr = $(this).parents('tr')[0];
        var checked = $(this).is(":checked");
        if (checked)
        {
            $(nTr).addClass('active');
            $(nTr).addClass('row_selected');
        }
        else
        {
            $(nTr).removeClass('active');
            $(nTr).removeClass('row_selected');
        }
    } );

});