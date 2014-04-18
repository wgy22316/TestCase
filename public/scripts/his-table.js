var oTable;
function reload(){
	oTable.fnDraw();
}

/* Formating function for row details */
function fnFormatDetails ( nTr )
{
    var aData = oTable.fnGetData( nTr );
    var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
    sOut += '<tr><td>initial_condition:</td><td>'+aData[3]+'</td></tr>';
    sOut += '<tr><td>procedure:</td><td>'+aData[4]+'</td></tr>';
    sOut += '<tr><td>expected_result:</td><td>'+aData[5]+'</td></tr>';
    sOut += '</table>';
     
    return sOut;
}

$(function(){
	  oTable=$('#table_id').DataTable({
		  
	 // "sScrollX": "1000%",
   	 "aLengthMenu": [
   	                 [50, 100, -1],
   	                 [50, 100, "All"] // change per page values here
   	  ],
   	 "iDisplayLength": 50,  //设置初始化时显示多少条记录
//   	  "aoColumns": [
//	                    { "sTitle": "TC_ID" },
//	                    { "sTitle": "case_v_id" },
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
	     "aoColumnDefs": [
						{
						    "mRender": function ( data, type, row ) {
						  	  var row;
						  	  if(row[6]=='Pass'){
						  		  row="<font color='green'>"+row[6]+"</font>";
						  	  }else if(row[6]=='Fail'){
						  		  row="<font color='red'>"+row[6]+"</font>";
						  	  }else{
						  		  row="<font color='gray'>"+row[6]+"</font>";
						  	  }
								  return row;
						    },
						    "aTargets": [6],
						},
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
						// { "bVisible": false,  "aTargets": [ 2 ] },
						{ "bVisible": false,  "aTargets": [ 3 ] },
						{ "bVisible": false,  "aTargets": [ 4 ] },
						{ "bVisible": false,  "aTargets": [ 5 ] }
						// { "sClass": "center", "aTargets": [ 3 ] }
						],
		
	    "bProcessing": true,  //显示 ”正在处理” 提示条
        "bServerSide": true,  //使用服务器端数据填充表格
        "bSort":false,
//        "bAutoWidth":false,
       // "sScrollX":'disabled',
//        "sScrollY":"100%",
//        "bScrollInfinite":true, //无限滚动-.-
        "sAjaxSource":"showhis?case_v_id="+$("#case_v_id").val()+"&&update_version="+$("#update_version").val(),
        "fnDrawCallback": function () {
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
	
});