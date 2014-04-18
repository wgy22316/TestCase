var MenuSortTable = function () {
    var moduleselect = '';//模块下拉框
    var parentselect = '';//父菜单下拉框
    var viewselect = '';//是否有视图下拉框
    var sortselect = '';//排序ID下拉框
    return {

        //main function to initiate the module
        init: function (array) {
            var modulearray = array['module'];
            var menu = array['menu'];
            var data = [];
            for(var i = 0,length=menu.length;i<length;i++){
                var id = menu[i].menu_id;
                var pid ='';
                var view = '是';
                var module;
                var menuedit = '<a class=\"edit\">编辑</a>';
                var menudelete = '<a class=\"delete\">删除</a>';

                var module_id = menu[i].module_id;
                    for(var module_i = 0,module_length=modulearray.length;module_i<module_length;module_i++){
                        if(modulearray[module_i].module_id == module_id){
                             module = modulearray[module_i].module_name;
                        }
                    }
                 for(var pid_i = 0,pid_length=menu.length;pid_i<pid_length;pid_i++){
                    if(menu[i].menu_pid==menu[pid_i].menu_id){
                        pid = menu[pid_i].menu_name;
                    }
                    if(menu[i].menu_pid=='0'){
                        pid = "无";
                    }
                 }
                 if(menu[i].menu_view=='0'){
                    view = "否";
                 }
                data[i] = [module,menu[i].menu_id,menu[i].menu_name,menu[i].menu_url,pid,menu[i].menu_sort_id,view,menuedit
                ,menudelete];
            }
            //初始化表格
           
            var oTable = $('#sort_menu2').dataTable({
                "bFilter": false,
               "bSort":false,
               "aLengthMenu": [
                     [10, 30, -1],
                     [10, 30, "All"] // change per page values here
                  ],
                 "iDisplayLength": 10, 
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
                "aaData":data,
                "aoColumnDefs": [],
            });
            //初始化下拉框选项

            initselect(array);

            function initselect(array){

                var modulearray = array['module'];
                var menu = array['menu'];

                moduleselect +="<select id=\"moduleselect\" style=\"width:100px\"  data-placeholder=\"Select...\" tabindex=\"-1\">";
                for(var i=0,length=modulearray.length;i<length;i++){
                    moduleselect +="<option value=\""+modulearray[i].module_id+"\">"+modulearray[i].module_name+"</option>";
                }
                moduleselect +="</select>";


                viewselect +="<select id=\"viewselect\" style=\"width:100px\"  data-placeholder=\"Select...\" tabindex=\"-1\">";
                viewselect +="<option value=\"0\">否</option>";
                viewselect +="<option value=\"1\">是</option>";
                viewselect +="</select>";


                sortselect +="<select id=\"sortselect\" style=\"width:100px\"  data-placeholder=\"Select...\" tabindex=\"-1\">";                   
                for(var i=1,length=10;i<length;i++){
                    sortselect +="<option value=\""+i+"\">"+i+"</option>";
                }
                sortselect +="</select>";


                parentselect +="<select id=\"parentselect\" style=\"width:100px\"  data-placeholder=\"Select...\" tabindex=\"-1\">";                       
                parentselect +="<option value=\"0\">无</option>"; 
                for(var i=0,length=menu.length;i<length;i++){
                    if(menu[i].menu_view=='1'){
                        parentselect +="<option value=\""+menu[i].menu_id+"\">"+menu[i].menu_name+"</option>"; 
                    }
                }
                parentselect +="</select>";

            }


            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                   oTable.fnUpdate(aData[i], nRow, i, false);
                }
                oTable.fnDraw();
            }
            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                // jqTds[0].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[0] + '"/>';
                jqTds[0].innerHTML = moduleselect;//模块下拉框
                jqTds[1].innerHTML = '<span>' + aData[1] + '</span>';
                jqTds[2].innerHTML = '<input type="text" rows="3" class="form-control" value="' + aData[2] + '"/>';
                jqTds[3].innerHTML = '<input type="text" rows="3" class="form-control" value="' + aData[3] + '"/>';
                jqTds[4].innerHTML = parentselect;//父菜单下拉框
                jqTds[5].innerHTML = sortselect;//排序ID下拉框
                jqTds[6].innerHTML = viewselect;//是否有视图下拉框
                jqTds[7].innerHTML = '<a class="edit" href="">保存</a>';
                jqTds[8].innerHTML = '<a class="cancel" href="">关闭</a>';

                // console.info($('#moduleselect'));
                // console.info(aData[0]);
                // // if(aData[0])
                // $('#moduleselect').val("1");
            }
            function saveRow(oTable, nRow) {
                var input = $('input', nRow);
                var select = $('select', nRow);
                var module_name = $("#moduleselect option:selected").text(); 
                var view_name = $("#viewselect option:selected").text(); 
                var parent_name = $("#parentselect option:selected").text(); 
                var sort_name = $("#sortselect option:selected").text(); 
                var id = $('span', nRow);
                var menu ={};
                 menu.id = id.text();
                 menu.module = select[0].value;
                 menu.name = input[0].value;
                 menu.url = input[1].value;
                 menu.pid = select[1].value;
                 menu.sort = select[2].value;
                 menu.view = select[3].value;


              $.ajax({ 
                        type: "POST", 
                        url: "updatemenu", 
                        dataType: "json", 
                        data: {"menu":menu}, 
                        success: function(array){
                            if(array['state']=="success"){
                                oTable.fnUpdate(module_name, nRow, 0, false);
                                oTable.fnUpdate(input[0].value, nRow, 2, false);
                                oTable.fnUpdate(input[1].value, nRow, 3, false);
                                oTable.fnUpdate(parent_name, nRow, 4, false);
                                oTable.fnUpdate(sort_name, nRow, 5, false);
                                oTable.fnUpdate(view_name, nRow, 6, false);
                                oTable.fnUpdate('<a class="edit" href="#">编辑</a>', nRow, 7, false);
                                oTable.fnUpdate('<a class="delete" href="#">删除</a>', nRow, 8, false);
                                oTable.fnDraw();
                            }else{
                                alert(array['error']);
                                return false; 
                            } 
                        },
                        error: function(msg) {
                                alert("越权");
                        }
                    });          
            }

            function deleteRow(oTable, nRow){
                var aData = oTable.fnGetData(nRow);
                var id = aData[1];
                 $.ajax({ 
                        type: "POST", 
                        url: "deletemenu", 
                        dataType: "json", 
                        data: {"id":id}, 
                        success: function(array){
                            if(array['state']=="success"){
                                oTable.fnDeleteRow(nRow);
                            }else{
                                alert(array['error']);
                                return false; 
                            } 
                        },
                        error: function(msg) {
                                alert("越权");
                        }
                    });  
            }
            var nEditing = null;
            $('#sort_menu2 a.delete').live('click', function (e) {
                e.preventDefault();

                if (confirm("你确定要删除这个菜单吗?") == false) {
                    return;
                }

                var nRow = $(this).parents('tr')[0];
                deleteRow(oTable, nRow);
            });

            $('#sort_menu2 a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    // console.info($(this).parents('tr')[0]);
                    oTable.fnDeleteRow(nRow);
                } else {
                   // alert("restore");
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#sort_menu2 a.edit').live('click', function (e) {

                e.preventDefault();
                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];
                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "保存") {
                    /* Editing this row and want to save it */
                    saveRow(oTable, nEditing);
                        // nEditing = null;
                   // alert("Updated! Do not forget to do some ajax to sync with backend :)");
                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        },

    };

}();