var Home = function () {

	var userloginout = function(){
		$("#loginout").bind('click',{index: 3}, loginout);
	}

   	function loginout(event){
   		$.cookie('password',null, { expires: -1, path:'/'});
          $.ajax({ 
		        type: "GET",
		        url: "loginout", 
		        dataType: "json", 
		        success: function(array){ 
				   if(array['state']=="success"){
				      	// alert('success');
		        		top.location="../../login/Login/login";
		        	}
		        },
		        error: function(msg) {
	        			alert("越权");
	        	}
			}); 

    }
    



    function showModule(array){
    	// alert(module);
    	var module = array['module'];
    	var game_id = array['game_id'];
    	var innerHTML='';
		for(var i=0,length=module.length;i<length;i++){

			innerHTML +="<div style=\"margin-left:50px;margin-top:5px;float:left;\">";
			if(module[i].module_id==game_id){
				innerHTML +="<button  align=\"left\" type=\"button\" class=\"btn green pull-right\">";
			}
			else{
				innerHTML +="<button  align=\"left\" type=\"button\" class=\"btn  pull-right\">";
			}
			innerHTML +=module[i].module_name+"</button></div>";
		}
    	$("#module").append(innerHTML);
    	changemodule();
    }
    function changemodule(){
    	$("#module div").each(function(){
    		 $(this).click(function(){
    		 	// var siblings = $(this).siblings();
    		 	// alert($(this).siblings().text());	
    		 	$(this).siblings().children().removeClass('green');
    		 	$(this).children().addClass('green');
                var game  = $(this).text();  
                  $.ajax({ 
			        type: "POST",
			        url: "changemodule", 
			        dataType: "json", 
				    data: {"module":game}, 
			        success: function(array){
					   if(array['state']=="success"){
					      	// alert(array['game_id']);
			        		top.location="../../Home/Home/home";
			        	}
			        },
			        error: function(msg) {
		        			alert("越权");
		        	}
				}); 
            }); 
    	});
    }
	return{
   		init: function(){
   			  userloginout();
   		},
   		Module : function(array){
   			 showModule(array);
   		}
   	};


}();