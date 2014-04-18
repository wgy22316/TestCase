$(document).ready(function(){  
					   // initiate layout and plugins
			$(document).keyup(function(event){
				  if(event.keyCode ==13){
			//		  alert("keyup");
			//		  if(event.data.index==1)
					  modclick(event);
			//		  else{
			//			  resck(event);
			//		  }
				  }
			});
	    	$("#modpassclick").bind('click',{index: 1}, modclick);
	    	
		   function modclick(){
		   	var password = $("#password").val();
		   	var modpassword = $("#modpassword").val();
		   	var modrpassword = $("#modrpassword").val();
		   	var modemail = $("#modemail").val();
		   	// alert(modusername+modrpassword+modpassword+modemail);
	      	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;//邮箱
		   	var	re= /select|update|delete|truncate|join|union|exec|insert|drop|count|’|"|=|;|>|<|%/i;
				$("#modpassclick").text("Submit....");
	      	    if(password==null||password==""){
	      	    	$("#mod_tip").text("原密码不能为空");
	            	$("#mod_tip").fadeIn(1000).fadeOut(2000);
					$("#modpassclick").text("Submit");
	                return false; 
	      	    }
	      	    else if(modpassword==null||modpassword==""){
	      	  	   	$("#mod_tip").text("新密码不能为空");
	      	  	    $("#mod_tip").fadeIn(1000).fadeOut(2000);
					$("#modpassclick").text("Submit");
	      	  	    return false; 
	      	  					
	      	  	}
	      	    else if(modrpassword==null||modrpassword==""){
	      	  	   	$("#mod_tip").text("重复新密码不能为空");
	      	  	    $("#mod_tip").fadeIn(1000).fadeOut(2000);
					$("#modpassclick").text("Submit");
	      	  	    return false; 
	      	  					
	      	  	}
	      	    else if(modpassword!=modrpassword){
	      	  	   	$("#mod_tip").text("新密码和重复新密码必须一致");
	      	  	    $("#mod_tip").fadeIn(1000).fadeOut(2000);
					$("#modpassclick").text("Submit");
	      	  	    return false; 
	      	  					
	      	  	}

	      	    else if(modemail==null||modemail==""){
	      	    	$("#mod_tip").text("email不能为空");
	      	  	    $("#mod_tip").fadeIn(1000).fadeOut(2000);
					$("#modpassclick").text("Submit");
	      	  	    return false; 
		      	}
	      	    else if ( !reg.test(modemail))
		      	{
	      	    	$("#mod_tip").text("请输入正确的email");
	      	  	    $("#mod_tip").fadeIn(1000).fadeOut(2000);
					$("#modpassclick").text("Submit");
	      	  	    return false; 
		      	}
	      	    else if ( re.test(password) ||re.test(modpassword)||re.test(modrpassword)||re.test(modemail)) {

	      	    	$("#mod_tip").text("请输入合法的信息（拒绝注入。。）");
	      	  	    $("#mod_tip").fadeIn(1000).fadeOut(2000);
					$("#modpassclick").text("Submit");
	      	  	    return false; 
		      	}
	      	    else{
	      	    	$.ajax({
				        type: "POST", 
				        url: "modpassword", 
				        dataType: "json", 
				        data: {"password":password,"modpassword":modpassword,"modrpassword":modrpassword,"modemail":modemail}, 
				        success: function(array){ 
					        
//				        	alert("--"+array+"---");
				            if(array['state']=="success"){
//				            	alert('success');
// 				            	  window.location.href='index/home?username='+$username+'password='+$password  target="_blank" rel="nofollow";
			      	    		$("#mod_tip").text("修改成功，下次请使用新密码登录");
			      	  	   		$("#mod_tip").fadeIn(1000).fadeOut(2000);
								$("#modpassclick").text("Submit");
				            }else{
				            	$("#mod_tip").text(array['error']);
				            	$("#mod_tip").fadeIn(1000).fadeOut(2000);
								$("#modpassclick").text("Submit");
				                return false; 
				            } 
				        },
					    error: function(msg) {
				        	alert("越权");
				        } 
				    }); 
	      	    }
		   }
		});