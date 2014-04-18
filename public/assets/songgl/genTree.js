var genChildrenTree=function(parentNode,sonData){
        //console.log(sonData);
        //alert(sonData.length);
            var $ul=$("<ul><input type='checkbox' pid='"+sonData.menu_pid+"' value='"+sonData.menu_id+"'>"+sonData.menu_name+"</ul>");
            if(sonData.son){
                for(var j=0;j<sonData.son.length;j++){
                    genChildrenTree($ul,sonData.son[j]);
                }
            }
      var res=parentNode.append($ul);
      return res;
   }
var genTree=function(content_div,data){
      for(var i=0;i<data.length;i++){
			var $div=$("<div class='checkbox_div'></div>");
          genChildrenTree($div,data[i]);
          content_div.append($div);               
      }
}

var selectAll=function(dom){
  dom.find("input[type='checkbox']").each(function(){
      $(this).click(function(){
        var pid=$(this).attr("pid");
        // alert(pid);
        if(pid=='0'){  //顶级菜单
          if($(this).is(":checked")){
            $(this).parent().find("input[type='checkbox']:gt(0)").attr("checked",true); 
          }else{
            $(this).parent().find("input[type='checkbox']:gt(0)").attr("checked",false);
          }
        }else{

        }
    });
  });
}