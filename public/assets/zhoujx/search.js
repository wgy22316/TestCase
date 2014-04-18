var Case = function () {

	$(document).keyup(function(event){
				  if(event.keyCode ==13){
//					  alert("keyup");
//					  if(event.data.index==1)
					  searchajax(event);
//					  else{
//						  resck(event);
//					  }
			  }
	});
	var init = function(){
		$("#search").focus();
		$("#searchCk").bind('click',searchajax);
		$("#returnsearch").bind('click',returnsearch);
	}

	function searchajax(){
			var searchValue=$("#search").val();
			if(searchValue==null||searchValue==''){
				$("#search").css("background",'red');
				$("#search").focus();
				return false;
			}
			else{
				$("#search").css("background",'#ffffff');
				$("#tip").val();
				$("#tip").append('查询中。。。');
				$.ajax({
			        type: "POST", 
			        url: "../../Case/Search/search", 
			        dataType: "json", 
					data:{"searchValue":searchValue},
					beforeSend: function(){ 
						// alert("beforeSend");
					},
			        success: function(array){
			        	// alert('rep');
						$("#tip").empty();
			            if(array['state']=="success"){
			            	showCase(array);
			            }
			            else{
							$("#tip").empty();
							$("#search_re").empty();
			            	alert(array['error']);
				            }
				    },
			        error: function(msg) {
		        			alert("越权");
		        	}

				});
			}

	}

	function returnsearch(){
		$("#detail").hide();
		$("#returnsearch").hide();
		$("#search_re").show();
	}
	var showCase =  function(array){
		var search = array['searchresult'];
		var data = [];
		// alert(search);
		// var tc_case = search['tc_case'];
		// var tc_case_v = search['tc_case_v'];
		// var tc_user = search['tc_user'];
		var detail_count=[];
		var innerHTML= "<div class=\"portlet box blue\"><div class=\"portlet-title\"><div class=\"caption\"><i class=\"fa fa-globe\"></i>搜索结果";
		innerHTML+="</div></div></div><table class='table table-striped table-bordered table-hover' id='search_id' >";
		innerHTML+= '<thead><tr role="row" class="heading"><th>caseID</th><th  width="10%">用例名称</th><th   width="20%">用例版本</th>';
		innerHTML+='<th   width="5%">作者ID</th><th   width="10%">创建时间</th><th  width="10%">提交时间</th><th>查看详情</th>';
		innerHTML+='<th>添加到我的任务</th></tr></thead><tbody></tbody><tfoot></tfoot></table>';
		
		for(var i = 0,length=search.length;i<length;i++){
			if(search[i].commit_time>"9998-12-29 23:59:59"){
				search[i].commit_time="未提交";
			}
			if(search[i].commit_time==null){
				search[i].commit_time="未提交";
			}
			var id = search[i].case_v_id;
			data[i] = [search[i].case_v_id,search[i].casename,search[i].version,search[i].username,search[i].create_time,search[i].commit_time,"<a><font color=\"#1d943b\" id=\"detail"+id+"\">详情</font></a>","<a><font color=\"#1d943b\" id=\"add"+id+"\">添加到我的用例</font></a>"];
			detail_count.push(id);
			// innerHTML+="</div></div></div>";
			// innerHTML+="<HR style=\"FILTER: progid:DXImageTransform.Microsoft.Shadow(color:#987cb9,direction:145,strength:15)\" width=\"80%\" color=#987cb9 SIZE=1>";

		}
		// alert(innerHTML);
		$("#search_re").empty();
		$("#search_re").append(innerHTML);


		//-----------------------------------------------------------------------------------------------------------

		var oTable=$('#search_id').DataTable({
			  "bSort":false,
			  "bFilter":false,
			  "aLengthMenu": [
		   	                 [50, 100, -1],
		   	                 [50, 100, "All"] // change per page values here
		   	  ],
		   	 "iDisplayLength": 50,  //设置初始化时显示多少条记录

			     "aoColumnDefs": [
			         //              {
			         //                  "mRender": function ( data, type, row ) {
			         //                	  var row="<a href='../../table/table/interchange?case_v_id="+row[0]+"&task_id="+$('#task_id').val()+"'>"+row[1]+"</a>";
										  // return row;
			         //                  },
			         //                  "aTargets": [1],
			         //              },
			                      { "bVisible": false,  "aTargets": [ 0 ] },
			                //      { "sClass": "center", "aTargets": [ 4 ] }
			                  ],
				"aaData":data,
			    //"bProcessing": true,  //显示 ”正在处理” 提示条
		        //"bServerSide": true,  //使用服务器端数据填充表格
		        //"sAjaxSource":"listCasesUnderTask?task_id="+$('#task_id').val(),
		        "fnDrawCallback": function () {
		            		//editTable();
		        			// trClick();
		        			// bindClick();
		        },
		        "fnInitComplete": function(oSettings, json) {
		        		//	console.info(oSettings);
		        		//	console.info(json);
		          }
	    });


		//----------------------------------------------------------------------------------------------------------

		$("#detail").hide();
		$("#search_re").show();
		binddetail(detail_count);
		bindadd(detail_count);
	}
	// function detail(event,id){
	function binddetail(detail_count){
		for(var i = 0,length=detail_count.length;i<length;i++){
			$("#detail"+detail_count[i]).bind('click',{index:detail_count[i]},detail);
		}
	}
	function detail(event){
		var id;
		if(event.data2 === undefined){
		   id = event.data.index;
		 }else{
		   id = event.data2.index;
		 }
		 // alert(id);
		 $.ajax({
			type: "POST", 
	        url: "../../Case/Search/detail", 
	        dataType: "json", 
			data:{"id":id},
			beforeSend: function(){ 
				// alert("beforeSend");
			},
	        success: function(array){
	        	// alert('rep');
	            if(array['state']=="success"){
	        		// alert('success');
	            	showDetail(array);
	            }
	            else{
	            	alert(array['error']);
	            }
	        },
			error: function(msg) {
		        alert("越权");
		    }
		});
	}

	// function detail(event,id){
	function bindadd(detail_count){
		for(var i = 0,length=detail_count.length;i<length;i++){
			$("#add"+detail_count[i]).bind('click',{index:detail_count[i]},add);
		}
	}
	function add(event){
		var id;
		if(event.data2 === undefined){
		   id = event.data.index;
		 }else{
		   id = event.data2.index;
		 }
		var game_v_id = $('#game_v_id').val();
		var task_id = $('#task_id').val();
		 // alert(game_v_id);
		 // alert(task_id);
		 $.ajax({
			type: "POST", 
	        url: "../../Case/Search/add", 
	        dataType: "json", 
			data:{"id":id,"game_v_id":game_v_id,'task_id':task_id},
			beforeSend: function(){ 
				// alert("beforeSend");
			},
	        success: function(array){
	        	// alert('rep');
	            if(array['state']=="success"){
	        		// alert('success');
	          //   	showDetail(array);
	          		reload();
	            }
	            else{
	            	alert(array['error']);
	            }
	        },
			error: function(msg) {
			    alert("越权");
			}
		});
	}

	function showDetail(array){
		$("#search_re").hide();
		var search = array['searchresult'];
		var innerHTML = "<div class=\"portlet box blue\"><div class=\"portlet-title\"><div class=\"caption\"><i class=\"fa fa-globe\"></i>案例详情";
			innerHTML +="<a id=\"returnsearch\"class=\"btn blue\" style=\"margin-left:5px;padding:0\" href=\"#\"><i class=\"fa fa-reply\"></i>返回搜索结果</a>";
			innerHTML+="</div></div></div><table class='table table-striped table-bordered table-hover' id='information_id' >";
			innerHTML+= '<thead><tr role="row" class="heading">';
			innerHTML +="<th>用例版本ID</th>";
			innerHTML +="<th>用例TC_ID</th>";
			innerHTML +="<th>titile</th>";
			innerHTML +="<th>初始条件</th>";
			innerHTML +="<th>操作方法</th>";
			innerHTML +="<th>期望结果</th>";
			innerHTML +="</tr></thead><tbody></tbody><tfoot></tfoot></table>";
			// innerHTML+="<HR style=\"FILTER: progid:DXImageTransform.Microsoft.Shadow(color:#987cb9,direction:145,strength:15)\" width=\"80%\" color=#987cb9 SIZE=1>";

		var data=[];
		for(var i = 0,length=search.length;i<length;i++){
				
				data[i]=[search[i].case_v_id,search[i].tc_id,search[i].title,search[i].Initial_condition,search[i].procedures,search[i].expected_result];
				// innerHTML +="</div>";
				// innerHTML+="<HR style=\"FILTER: progid:DXImageTransform.Microsoft.Shadow(color:#987cb9,direction:145,strength:15)\" width=\"80%\" color=#987cb9 SIZE=1>";
		}	
		$("#detail").empty();
		// $("#returnsearch").show();
		$("#detail").append(innerHTML);

		var oTable=$('#information_id').DataTable({
			  "bSort":false,
			  "bFilter":false,
			  "aLengthMenu": [
		   	                 [50, 100, -1],
		   	                 [50, 100, "All"] // change per page values here
		   	  ],
		   	 "iDisplayLength": 50,  //设置初始化时显示多少条记录

			     // "aoColumnDefs": [
			     //                  { "bVisible": false,  "aTargets": [ 0 ] },
			     //              ],
				"aaData":data,

		        "fnDrawCallback": function () {
		            		//editTable();
		        			// trClick();
		        			// bindClick();
		        },
		        "fnInitComplete": function(oSettings, json) {
		        		//	console.info(oSettings);
		        		//	console.info(json);
		          }
	    });
		$("#detail").show();
		bindreturnsearch();
		
	}
	function bindreturnsearch(){
		$("#returnsearch").bind('click',function(){
			$("#detail").hide();
			$("#search_re").show();
		});
	}

	return{
   		init: function(){
   			  init();
   		},
   		// search:function(searchValue){
   		// 	searchajax(searchValue);
   		// }
   	};


}();