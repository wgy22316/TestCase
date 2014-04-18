var oTable;
var search = '';
function reload(){
	search = $('#search').val();
	oTable.fnDraw();
}

//获取选中的行
function fnGetSelected (oTable){
	return oTable.$("tr.row_selected");
}

//删除一行或者多行数据
function fnDeleteRow(){
	if (confirm("删除了就没有了！！！") == false) {
	    return;
    }
    search = $('#search').val();
	var anSelecteds = fnGetSelected(oTable);
	var delDataLength=anSelecteds.length;
	// console.info(delDataLength);
	    // $("tr:gt(0)");
	    if(delDataLength>0){
	        var case_v_ids=[];
	        	for(var i=0;i<delDataLength;i++){
	        		var rowDel=  oTable.fnDeleteRow(anSelecteds[i],function(){},false);  //删除页面tr DOM节点
	        		var id=rowDel[0]._aData[0];
	        		case_v_ids.push(id);	
	        	}
	        	// console.info(case_v_ids);
	        	//console.info(rowdel[0]._aData);
	        	var ids=case_v_ids.join(",").toString();
	        	
	        	$.ajax({ 
		        	type: "POST",
		        	url: "deleteCase", 
		        	data:"case_v_ids="+ids,
		       		dataType: "json", 
		        	success: function(data){ 
				   		if(data.code=="1"){
	        		  		alert("删除成功啦！");
	        		  	}else if(data.code==3)
							alert("删除tc_case表中无版本的case出错");
						else{
	        		  		alert("删除失败了~~");
	        		  	}
		        	},
		        	error: function(msg) {
	        				alert("越权");
	        		}
				}); 
	     //    	$.post("deleteCase",{"case_v_ids":ids},function(data,status){
	     //    		if(status=="success"){
	     //    		  	if(data.code=="1"){
	     //    		  		alert("删除成功啦！");
	     //    		  		oTable.fnDraw();
	     //    		  	}else if(data.code==3)
						// 	alert("删除tc_case表中无版本的case出错");
						// else{
	     //    		  		alert("删除失败了~~");
	     //    		  	}
	     //    		}else{
	     //    		  	alert("send delete ajax request has failed ! please check your network connection！");
	     //    		}
	     //    	},"json");
	        	oTable.fnDraw();
	       }else{
	    	   alert("请选择要删除的行记录！");
	       }
}

function trClick(){
	oTable.$('tr').click(function(){

		// console.info($(this).find('.hasCommit'));
		if($(this).find('.hasCommit').size()==0)
		{
			$(this).toggleClass('row_selected');
			$(this).toggleClass('active');
		}

	});
}

$(function(){

	showCases.init('','','',0);
	//模糊搜索
	$("#btnSearch").live("click",function(){
		var search = $('#search').val();
		if(null==search || ''==search)
			location.href="showAllVersionOfCase";

		$('#casesTable').empty();
		$('#casesTable').html('<div class="table-container"><table class="table table-striped table-bordered table-hover" id="table_id" ><thead></thead><tbody></tbody><tfoot></tfoot></table></div>');

		showCases.init(search,'','',1);
		if(!$("#research").is(":visible"))
		{
			
			$('#research').show();
		}
	});

	//绑定回车键
	$(document).keyup(function(event){
		if(event.keyCode ==13){
			$('#btnSearch').trigger("click");
		}
	});

	//二次搜索
	$("#btnResearch").live("click",function(){
		var search = $('#search').val();
		if(null==search || ''==search)
		{
			location.href="showAllVersionOfCase";
		}
		var contentArrs = [];
		$('input:checkbox[name="content"]:checked').each(function(){
				contentArrs.push($(this).val());
		});
		contentArrs = contentArrs.join(",").toString();
		var author = $("#author").val();
		$('#casesTable').empty();
		$('#casesTable').html('<div class="table-container"><table class="table table-striped table-bordered table-hover" id="table_id" ><thead></thead><tbody></tbody><tfoot></tfoot></table></div>');

		showCases.init(search,contentArrs,author,2);
	});



})



var showCases = function(){

    return {

    	init: function(search,contentArrs,author,isreSearch){

			  oTable=$('#table_id').DataTable({
			  "bSort":false,

			  "aLengthMenu": [
		   	                 [50, 100, -1],
		   	                 [50, 100, "All"] // change per page values here
		   	  ],
		   	 "iDisplayLength": 50,  //设置初始化时显示多少条记录
		   	  "bFilter":false,
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
		         "aoColumns": [
                     { "sTitle": 'CaseVersionID'},
                     { "sTitle": "所属游戏" },
                     { "sTitle": "案例名" },
                     { "sTitle": "案例版本号" },
                     { "sTitle": "用例总数" },
                     { "sTitle": "创建者" },
                     { "sTitle": "创建时间" },
                     { "sTitle": '<a href="#" class="comparebutton btn btn-sm green">对比</a>' }

    			  ],
			     "aoColumnDefs": [
			                      {
			                          "mRender": function ( data, type, row ) {
			                        	  var row="<a href='../../table/table/showdetailtestcase?case_v_id="+row[0]+"'>"+row[2]+"</a>";
										  return row;
			                          },
			                          "aTargets": [2],
			                      },
			                     //  {
			                     //    	"bSortable":false,
			                     //    	'aTargets':[0,1,3,4,5,6],
			                    	// },
			                     { "bVisible": false,  "aTargets": [ 0 ] },
			                //      { "sClass": "center", "aTargets": [ 4 ] }
			                  ],
				
			    "bProcessing": true,  //显示 ”正在处理” 提示条
		        "bServerSide": true,  //使用服务器端数据填充表格
		        "sAjaxSource":"listAllVersionOfCase?search="+search+"&contentArrs="+contentArrs+"&author="+author+"&isreSearch="+isreSearch,
		        "fnDrawCallback": function () {
		            		//editTable();
		            oTable.$('tr').click(function(){
						if($(this).find('.hasCommit').size()==0)
						{
							$(this).toggleClass('row_selected');
							$(this).toggleClass('active');
						}

					});
		        	
		        },
		        "fnInitComplete": function(oSettings, json) {
		        		//console.info(oSettings);
		        		//	console.info(json);
		          }
			    });	
				
			  
			  $(".comparebutton").live("click", function(){
				  var caseVersionId=[];
				  $('input:checkbox[name="caseVersions"]:checked').each(function(){ 
					  caseVersionId.push($(this).val());    
				  	});
				  if(caseVersionId.length == 0){
						alert("请选择要比较的对象");
						return ;
				  }
				  if(caseVersionId.length == 1){
					  alert("请选择两个比较的对象");
					  return ;
				  }
				  if(caseVersionId.length > 2){
					  alert("只能选择两个比较的对象");
					  return ;
				  }
				  if(caseVersionId.length == 2){
					  window.location.href = "../../table/table/compareTwoCase?firstCase="+caseVersionId[0]+"&&secondCase="+caseVersionId[1];
				  }
			  });
			}
		};
	  
}()