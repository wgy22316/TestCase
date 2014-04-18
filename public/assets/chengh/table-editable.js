var TableEditable = function () {

    var userselecthead = '';
    var userselectend = '';
    var oTable = null;
    var gameVid = null;
    return {

        //main function to initiate the module
        init: function () {
            // alert(initdata['aaData'][0]);
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);

                var jqTds = $('>td', nRow);
                
                for (var i = 0, iLen = jqTds.length; i <= iLen; i++) {

                   oTable.fnUpdate(aData[i], nRow, i, false);
                }

                oTable.fnDraw();
            }

            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                //console.info(aData);
                var jqTds = $('>td', nRow);

                jqTds[0].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[0] + '">';
                jqTds[1].innerHTML = '<input type="textarea" rows="3" class="form-control" value="' + aData[1] + '">';
                jqTds[2].innerHTML = userselecthead + aData[2] + userselectend;
                //console.info(userselecthead + aData[2]+userselectend);
                //jqTds[3].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[3] + '">';
                //jqTds[3].innerHTML = '<a href="../../case/testcase/showCaseByTask?task_id='+aData[3]+'&&task_name='+aData[0]+'">查看</a>';
                jqTds[3].innerHTML = '查看';
                jqTds[4].innerHTML = '<a class="edit" href="">Save</a>';
                jqTds[5].innerHTML = '<a class="cancel" href="">Cancel</a>';

                //console.info($('#tester_id'));
                $('#tester_id').val(aData[2]);

            }

            



            function saveRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqInputs = $('input', nRow);
                var tester = $('select',nRow);
                //console.info(tester);
                //判断任务名不能空
                if(jqInputs[0].value==''||jqInputs[0].value==null)
                {
                    alert('任务名不能为空');
                    return;
                }
                //判断测试者不能为空
                if(tester[0].value=='')
                {
                    alert('测试者不能为空');
                    return;
                }
                $.ajax({
                    type:'post',
                    url:'saveTask',
                    dataType:'json',
                    data:{
                        "task_id":aData[3],
                        "task_name":jqInputs[0].value,
                        "task_desc":jqInputs[1].value,
                        "tester_name":tester[0].value,
                        "game_v_id":gameVid
                    },
                    success: function(data){
                        if(data.code==1)
                        {
                            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                            oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                            oTable.fnUpdate(tester[0].value, nRow, 2, false);
                            aData[3] = data.taskId;
                            // oTable.fnUpdate('<a href="../../case/testcase/showCaseByTask?task_id='+aData[3]+'&&task_name='+aData[0]+'">查看</a>', nRow, 4, false);
                            oTable.fnUpdate('<a href="" class="review">查看</a>', nRow, 4, false);
                            oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 5, false);
                            oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 6, false);
                            oTable.fnDraw();
                            nEditing = null;
                        }
                        else if(data.code == 3)
                        {
                            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                            oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                            oTable.fnUpdate(tester[0].value, nRow, 2, false);
                            aData[3] = aData[3];
                           // oTable.fnUpdate('<a href="../../case/testcase/showCaseByTask?task_id='+aData[3]+'&&task_name='+aData[0]+'">查看</a>', nRow, 4, false);
                            oTable.fnUpdate('<a href="" class="review">查看</a>', nRow, 4, false);
                            oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 5, false);
                            oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 6, false);
                            oTable.fnDraw();
                            nEditing = null;
                        }
                        else if(data.code == -3)
                        {
                            alert('ajax request fail');
                        }
                    }
                });              
            }

            function deleteRow(oTable, nRow){
                var aData = oTable.fnGetData(nRow);
                $.post("deleteTask",{"task_id":aData[3]},function(data,status){
                    if(data.code==1)
                        oTable.fnDeleteRow(nRow);
                    else if(data.code == 0)
                        alert('任务id为空！');
                    else if(data.code == 2)
                        alert('任务不存在！');
                    else
                        alert("删除请求失败");
                },'json');
            }

            function cancelEditRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                var tester = $('select',nRow);
                //判断任务名不能空
                if(jqInputs[0].value==''||jqInputs[0].value==null)
                {
                    alert('任务名不能为空');
                    return;
                }
                //判断测试者不能为空
                if(tester[0].value=='')
                {
                    alert('测试者不能为空');
                    return;
                }
                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                oTable.fnUpdate(tester[0].value, nRow,2,false);
                oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4, false);
                oTable.fnDraw();
            }
            //获取测试人员列表
            $.post("listUsers",function(data,status){

                if(status=="success"){
                    if(data.length==0)
                        alert("no Users");
                    else{
                        userselecthead = '<select id="tester_id" style="width:150px" class="form-control" value="';
                        userselectend = '" ><option value="">请选择测试者</option>';
                        for(var i =0;i<data.length;i++)
                        {
                            userselectend+="<option value='"+data[i].username+"'>"+data[i].username+"</option>";
                        }
                        userselectend+='</select>';
                    };
                                           
                }else{
                    alert("send delete ajax request has failed ! please check your network connection！");
                }

            },"json");

            // //初始化表格
            // var oTable = $('#sample_editable_1').dataTable({
            //     "bFilter": false,
            //     "bPaginate": false,
            //    // "bSort":false,
            //     "aaData":initdata['aaData'],
            //    // "sPaginationType": "bootstrap",
            //     "aoColumnDefs": [
            //         {
            //             "bVisible":false,
            //             'aTargets':[3],
            //         },
            //         //{'bSortable': false},null
            //         {
            //             "bSortable":false,
            //             'aTargets':[0,1,3,4,5,6],
            //         },
            //     ],
            // });

            jQuery('#sample_editable_1_wrapper .dataTables_filter input').addClass("form-control input-medium"); // modify table search input
            jQuery('#sample_editable_1_wrapper .dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
            jQuery('#sample_editable_1_wrapper .dataTables_length select').select2({
                showSearchInput : false //hide search box with special css class
            }); // initialize select2 dropdown

            var nEditing = null;

            $('#sample_editable_1_new').click(function (e) {
                e.preventDefault();
                var key = true;
                //console.info($('table tbody tr'));
                var tds = $('table tbody tr');
                for(var i=0; i<tds.length; i++)
                {
                    // console.info($(tds[i]).children('td:eq(4)').children('a'));
                    if($(tds[i]).children('td:eq(4)').children('a').text() == 'Save')
                    {
                        alert("存在未保存的编辑行!!!");
                        key = false;
                    };
                    if(!key)
                        break;
                };
                if(!key)
                    return false;
                var aiNew = oTable.fnAddData(['', '', '',null,'查看',
                        '<a class="edit" href="">Edit</a>', '<a class="cancel" data-mode="new" href="">Cancel</a>'
                ]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                editRow(oTable, nRow);
                nEditing = nRow;
            });

            $('#sample_editable_1 a.delete').live('click', function (e) {
                e.preventDefault();

                if (confirm("Are you sure to delete this row ?") == false) {
                    return;
                }

                var nRow = $(this).parents('tr')[0];
                deleteRow(oTable, nRow);
                //oTable.fnDeleteRow(nRow);
               // alert("Deleted! Do not forget to do some ajax to sync with backend :)");
            });

            $('#sample_editable_1 a.cancel').live('click', function (e) {
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

            $('#sample_editable_1 a.edit').live('click', function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];

                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "Save") {
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

            $('#sample_editable_1 a.review').live('click', function (e) {
                e.preventDefault();
                var nRow = $(this).parents('tr')[0];
                var aData = oTable.fnGetData(nRow);
                var task_id = aData[3];
                var task_name = aData[0];

                if(task_id == null || ''==task_id)
                    return;
                //console.info(task_id);
                if(!$('#showCaseByTask').is(":visible"))
                {
                    $('#showCaseByTask').show();
                }
                //console.info($('#sample_editable_1 tbody'));
                $('#tableObj2').empty();
                $('#casesTitle').text(task_name+' 任务案例列表');
                $('#tableObj2').append('<table class="table table-striped table-bordered table-hover" id="table_id" ><thead><tr role="row" class="heading"><th width="20%">案例</th><th>版本</th><th width="5%">用例总数</th><th width="5%">通过条数</th><th width="5%">失败条数</th><th>创建时间</th><th>提交时间</th><th>创建者</th></tr></thead><tbody></tbody><tfoot></tfoot></table>');
                TableShow.init(task_id);
            });

        },

        createTable:function(initdata,game_v_id){
            gameVid = game_v_id;
            //初始化表格
            oTable = $('#sample_editable_1').dataTable({
                "bFilter": false,
                "bPaginate": false,
               // "bSort":false,
                "aaData":initdata['aaData'],
               // "sPaginationType": "bootstrap",
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
                        "bVisible":false,
                        'aTargets':[3],
                    },
                    //{'bSortable': false},null
                    {
                        "bSortable":false,
                        'aTargets':[0,1,3,4,5,6],
                    },
                ],
            });
        }

    };

}();