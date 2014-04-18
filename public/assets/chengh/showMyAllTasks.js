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
	                        	  var row="<a href='../../case/testcase/showCasesUnderTask?task_id="+row[0]+"'>"+row[1]+"</a>";
								              return row;
	                          },
	                          "aTargets": [1],
	                      },
	                     { "bVisible": false,  "aTargets": [ 0 ] },
	                //      { "sClass": "center", "aTargets": [ 4 ] }
	                  ],
		
	    "bProcessing": true,  //显示 ”正在处理” 提示条
        "bServerSide": true,  //使用服务器端数据填充表格
        "sAjaxSource":"listMyAllTasks",
        "fnDrawCallback": function () {
            		//editTable();
        	
        },
        "fnInitComplete": function(oSettings, json) {
        		//console.info(oSettings);
        		//	console.info(json);
          }
	    });	
	
});