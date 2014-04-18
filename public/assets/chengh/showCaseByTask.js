var TableShow = function(){
 var oTable = null;
  return {
    reload:function(){
      oTable.fnDraw();
    },

    init:function(task_id){
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
                                var row="<font color='green'>"+row[3]+"</font>";
                                return row;
                          },
                              "aTargets": [3],
                          },
  	                      {
  	                          "mRender": function ( data, type, row ) {
                                if(row[4]>0){
                                  // row="<font color='red'>"+row[4]+"</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger'>详情</button>";
                                  row="<font color='red'>"+row[4]+"</font>";  
                                }else{
    	                        	  row="<font color='green'>"+row[4]+"</font>";
  								              }
                                return row;
  	                      },
  	                          "aTargets": [4],
  	                      },
  	                       {
  	                          "mRender": function ( data, type, row ) {
                                if(row[6]==null||row[6]=="9999-12-29 23:59:59"){
                                	row="<a href='#' class='btn btn-sm dark disabled'>暂未提交</a>"
                                }else{
    	                        	  row="<font color='green'>"+row[6]+"</font>";
  							  }
                                return row;
  	                      },
  	                          "aTargets": [6],
  	                      },
  	                //     { "bVisible": false,  "aTargets": [ 0 ] },
  	                //      { "sClass": "center", "aTargets": [ 4 ] }
  	                  ],
  		
  	    "bProcessing": true,  //显示 ”正在处理” 提示条
          "bServerSide": true,  //使用服务器端数据填充表格
          "sAjaxSource":"../../case/testcase/listCaseByTask?task_id="+task_id,
          "fnDrawCallback": function () {
              		//editTable();
          	
          },
          "fnInitComplete": function(oSettings, json) {
          }
  	    });

        $('#reload').live('click',function(){
          oTable.fnDraw();
        });
    },
  }
}();