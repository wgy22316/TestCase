$(document).ready(function(){
//	       alert("1");
			$(document).keyup(function(event){
				  if(event.keyCode ==13){
//					  alert("keyup");
//					  if(event.data.index==1)
					  loginck(event);
//					  else{
//						  resck(event);
//					  }
			  }
			});
		//begin login check
	    $("*[name='loginck']").bind('click',{index: 1}, loginck);
	    $("*[name='resck']").bind('click',{index: 2}, resck);
	    $("#forget-password").bind('click',function(){
	    	$("#loginform").hide();
	    	$("#forgetform").show();
	    	$("#regform").hide();
	    });
	    $("#register-btn").bind('click',function(){
	    	$("#loginform").hide();
	    	$("#forgetform").hide();
	    	$("#regform").show();
	    });
	    $("#back-btn").bind('click',function(){
	    	$("#loginform").show();
	    	$("#forgetform").hide();
	    	$("#regform").hide();
	    });
	    $("#register-back-btn").bind('click',function(){
	    	$("#loginform").show();
	    	$("#forgetform").hide();
	    	$("#regform").hide();
	    });
	    function getcookie(){
	    	  	var user_id = $.cookie('user_id');
	    	  	var password = $.cookie('password');
	    	  	// alert(username+password);
	    	  	if(user_id){
		    	  	$("#user_id").val(user_id);
		    	}
	    	  	if(password){
		    	  	$("#password").val(password);
		    	  	$.ajax({ 
				        type: "POST", 
				        url: "loginautoCk", 
				        dataType: "json", 
				        data: {"user_id":user_id,"password":password}, 
				        success: function(array){
				            if(array['state']=="success"){
				            	top.location="../../home/Home/home";
				            }else{
				            	$("#error_tip").text("用户名或密码错误");
				            	$("#error_tip").fadeIn(1000).fadeOut(2000);
				            	$("#logintip").text("登陆");
				                return false; 
				            } 
				        }
				    }); 
		    	}
	    }
	    getcookie();
	    function loginck(event){
	    	    var username ;
      	        var password ;
	    	    user_id =$("#user_id").val();
	      	    password = $("#password").val();
	      	    rem_login = $("#rem_login").val();
	      	     if (!!$("#remember_login").is(":checked")) {
	      	     	rem_login=true;
	      	    }
	      	    re= /select|update|delete|truncate|join|union|exec|insert|drop|count|’|"|=|;|>|<|%/i;
				$("#logintip").text("登陆....");
	      	    if(user_id==null||user_id==""){
	//	      	    alert(username+password);
	      	    	$("#error_tip").text("用户名不能为空");
	            	$("#error_tip").fadeIn(1000).fadeOut(2000);
	            	$("#logintip").text("登陆");
	                return false; 
	      	    }
	      	    else if(password==null||password==""){
	      	  	//	alert(username+password);
	      	  	   	$("#error_tip").text("密码不能为空");
	      	  	    $("#error_tip").fadeIn(1000).fadeOut(2000);
	      	  	    $("#logintip").text("登陆");
	      	  	    return false; 
	      	  					
	      	  	}
	      	    else if ( re.test(user_id) ||re.test(password))
		      	{
	      	    	$("#error_tip").text("请输入合法的用户名 密码");
	      	  	    $("#error_tip").fadeIn(1000).fadeOut(2000);
	      	  	    $("#logintip").text("登陆");
	      	  	    return false; 
		      	}
	      	    else{
	      	    	$.ajax({ 
				        type: "POST", 
				        url: "loginCk", 
				        dataType: "json", 
				        data: {"user_id":user_id,"password":password,"rem_login":rem_login}, 
				        success: function(array){
				            if(array['state']=="success"){
		    	  				$.cookie('user_id', $("#user_id").val(), { expires: 7, path:'/'});
		    	  				if(array['pass']){
		    	  					$.cookie('password', array['pass'], { expires: 7, path:'/'});
		    	  				}
				            	top.location="../../home/Home/home";
				            }else{
				            	$("#error_tip").text("用户名或密码错误");
				            	$("#error_tip").fadeIn(1000).fadeOut(2000);
				            	$("#logintip").text("登陆");
				                return false; 
				            } 
				        } 
				    }); 
	      	    }
		  };
	     //end login check 
	     
	      //begin forgot  
	       $("*[name='forgot']").click(function(){
	    	   var email;
	    	   var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
	    	   email =$("#forgot_email").val();
				$("#fsubmit_tip").text("提交....");
	      	    if(email==null||email==""){
	//	      	    alert(username+password);
	      	    	$("#forgot_tip").text("请输入你注册本网站所使用的email");
	            	$("#forgot_tip").fadeIn(1000).fadeOut(2000);
	                return false; 
	      	    }
	      	    else if (!reg.test(email) )
		      	{
	      	    	$("#forgot_tip").text("请输入正确的email");
	      	  	    $("#forgot_tip").fadeIn(1000).fadeOut(2000);
	      	  	    return false; 
		      	}
	      	    else{
	      	    	$.ajax({ 
				        type: "POST", 
				        url: "forgot", 
				        dataType: "json", 
				        data: {"email":email}, 
				        success: function(array){
				            if(array['state']=="success"){
				            	$("#forgot_tip").text("请使用Email接受的新密码登陆");
				            	$("#forgot_tip").fadeIn(4000).fadeOut(5000);
								$("#fsubmit_tip").text("提交");
				            }else{
				            	$("#forgot_tip").text(array['error']);
				            	$("#forgot_tip").fadeIn(1000).fadeOut(2000);
								$("#fsubmit_tip").text("提交");
				                return false; 
				            } 
				        } 
				    }); 

	      	    }
		  });
	     
	     
	     
	     
	     //end forgot  
	     
	     
	     
	     //begin register  
	      
	      function resck(){
//	    	  alert('---');
	    	   var res_username;
      	       var res_email;
      	       var res_user_id;
	      	   var res_password;
	      	   var res_rpassword;
	      	   var mustagree;
	      	   var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;//邮箱
	      	   var  rs= /select|update|delete|truncate|join|union|exec|insert|drop|count|’|"|=|;|>|<|%/i;//注入
	      	   var rsusername=/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){4,19}$/;//只能输入5-20个以字母开头、可带数字、“_”、“.”的字串 
	      	   var rspass=/([a-zA-Z0-9]|[._]){8,20}$/;//8~20位密码验证
	      	    res_username =$("#res_username").val();
		      	res_email = $("#res_email").val();
		      	res_user_id =$("#res_user_id").val();
		      	res_password = $("#res_password").val();
		      	res_rpassword =$("#res_rpassword").val();
//		      	alert($("#mustagree").attr("checked"));
	      	    re= /select|update|delete|truncate|join|union|exec|insert|drop|count|’|"|=|;|>|<|%/i;
	      	    if(res_username==null||res_username==""||res_email==null||res_email==""
	      	    		||res_user_id==null||res_user_id==""
	      	    		||res_password==null||res_password==""
	      	    		||res_rpassword==null||res_rpassword==""){
	//	      	    alert(username+password);
	      	    	$("#res_tip").text("所有内容必须填写");
	            	$("#res_tip").fadeIn(1000).fadeOut(2000);
	                return false; 
	      	    }
	      	    else if(rs.test(res_user_id)||rs.test(res_email)||rs.test(res_username)||
	      	    		rs.test(res_password)||rs.test(res_rpassword)){
	      	    	$("#res_tip").text("请不要输入不合法的字符");
	            	$("#res_tip").fadeIn(1000).fadeOut(2000);
	                return false; 
	      	    }
	      	    else if (!reg.test(res_email) ){
	      	    	$("#res_tip").text("请输入正确的email");
	            	$("#res_tip").fadeIn(1000).fadeOut(2000);
	                return false; 
	      	    }
	      	    else if(res_rpassword!=res_password){
	      	    	$("#res_tip").text("密码和确认密码必须一致");
	            	$("#res_tip").fadeIn(1000).fadeOut(2000);
	                return false; 
	      	    }
	      	    else if(!$("#mustagree").is(":checked"))
	      	    {
	      	    	$("#res_tip").text("请同意本网站的协议");
	            	$("#res_tip").fadeIn(1000).fadeOut(2000);
	                return false; 
	      	    }
	      	    else 
	      	    {
	      	    	$.ajax({ 
				        type: "POST", 
				        url: "regsiterCk", 
				        dataType: "json", 
				        data: {"res_user_id":res_user_id,"res_email":res_email,
				        	   "res_username":res_username,"res_password":res_password,
				        	   "res_rpassword":res_rpassword},
				        beforeSend: function(){ 
							$("#sign_tip").text("注册....");
						},       	   
				        success: function(resarray){ 
				            if(resarray['state']=="success"){
								$("#res_tip").text("");
								$("#sign_tip").text("注册");
				            	alert("注册成功，点击'确定'自动登陆");
				            	top.location="../../home/Home/home";
//				            	top.location='home/home?username='+resarray['username']+'password='+resarray['password'];
				            }else{
				            	$("#res_tip").text(resarray['error']);
				            	$("#res_tip").fadeIn(2000).fadeOut(2000);
								$("#sign_tip").text("注册");
				                return false; 
				            } 
				        } 
				    }); 

	      	    }
		  };
	     
	     
	     
	     //end register  
		});