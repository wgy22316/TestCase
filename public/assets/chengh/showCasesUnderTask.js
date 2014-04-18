var oTable;
function reload(){
	oTable.fnDraw();
}

//删除单条数据时候的
/*
function fnGetSelected (oTable){
		return oTable.$("tr.row_selected");
}
*/
function fnGetSelected (oTable){

		return oTable.$("tr.row_selected");
}

//删除一行或者多行数据
function fnDeleteRow(){
	if (confirm("删除了就没有了哦！！！") == false) {
                    return;
    }
	var anSelecteds = fnGetSelected(oTable);
	var delDataLength=anSelecteds.length;
	console.info(delDataLength);
       // $("tr:gt(0)");
        if(delDataLength>0){
        		var case_v_ids=[];
        		for(var i=0;i<delDataLength;i++){
        			var rowDel=  oTable.fnDeleteRow(anSelecteds[i],function(){},false);  //删除页面tr DOM节点
        			var id=rowDel[0]._aData[0];
        			case_v_ids.push(id);	
        		}
        		console.info(case_v_ids);
        		//console.info(rowdel[0]._aData);
        		var ids=case_v_ids.join(",").toString();
        		  $.post("del",{"case_v_ids":ids},function(data,status){
        		  		if(status=="success"){
        		  			if(data.code=="1"){
        		  				alert("删除成功啦！");
        		  				oTable.fnDraw();
        		  			}else if(data.code==-1)
								alert("已提交的不能删除哦!");
							else{
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


$(function(){
	  oTable=$('#table_id').DataTable({
	  "bSort":false,

	  "aLengthMenu": [
   	                 [50, 100, -1],
   	                 [50, 100, "All"] // change per page values here
   	  ],
   	 "iDisplayLength": 50,  //设置初始化时显示多少条记录

	     "aoColumnDefs": [
	                      {
	                          "mRender": function ( data, type, row ) {
	                        	  var row="<a href='../../table/table/interchange?case_v_id="+row[0]+"&task_id="+$('#task_id').val()+"'>"+row[1]+"</a>";
								  return row;
	                          },
	                          "aTargets": [1],
	                      },
	                      { "bVisible": false,  "aTargets": [ 0 ] },
	                //      { "sClass": "center", "aTargets": [ 4 ] }
	                  ],
		
	    "bProcessing": true,  //显示 ”正在处理” 提示条
        "bServerSide": true,  //使用服务器端数据填充表格
        "sAjaxSource":"listCasesUnderTask?task_id="+$('#task_id').val(),
        "fnDrawCallback": function () {
            		//editTable();
        			trClick();
        			bindClick();
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

	
	function trClick(){
		oTable.$('tr').click(function(){

			console.info($(this).find('.hasCommit'));
			if($(this).find('.hasCommit').size()==0)
			{
				$(this).toggleClass('row_selected');
				$(this).toggleClass('active');
			}

		});
	}
	function bindClick(){
		console.info($('a.commit'));
		$('a.commit').click(function(){

			// console.info($(this));
			$(this).closest('tr').toggleClass('row_selected');
			$(this).closest('tr').toggleClass('active');
			//alert($(this).attr("value"));
			if (confirm("小伙伴你真的要提交么 ?") == false) {
                    return;
            }

			var case_v_id = $(this).attr('value');
			var a = $(this);
			$.post("commitCase",{"case_v_id":case_v_id},function(data,status){
				if(data.code==1)
				{
					a.removeClass('commit');
					a.addClass('hasCommit');
					a.removeClass('green');
					a.addClass('red');
					a.addClass('disabled');
					a.html('已提交');
					alert("提交成功，无法修改！！！");
				}
				else
					alert("something wrong!!!");
			},"json");
				
		});
	}
	
	
	
});