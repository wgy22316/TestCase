<?php
// include 'Models_User_IMenu.php';

class Models_User_Sendemail {

	public function sendEmail($email,$newpass,$tip){
		require("class.phpmailer.php"); //下载的文件必须放在该文件所在目录
		$mail = new PHPMailer(); //建立邮件发送类
		// $address ="15527881098@163.com";
		// $email ="15527881098@163.com";
		$mail->IsSMTP(); // 使用SMTP方式发送
		$mail->Host = "smtp.163.com"; // 您的企业邮局域名
		$mail->SMTPAuth = true; // 启用SMTP验证功能
		$mail->Username = "15527881098@163.com"; // 邮局用户名(请填写完整的email地址)
		$mail->Password = "19910614"; // 邮局密码
		$mail->Port=25;
		$mail->From = "15527881098@163.com"; //邮件发送者email地址
		$mail->FromName = "zhoujixiang";
		$mail->AddAddress($email, "a");//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
		//$mail->AddReplyTo("", "");
		//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
		//$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
		$mail->Subject = "Data Web重置密码"; //邮件标题
		$mail->Body = "你的".$tip."为".$newpass."。你需要使用新密码登陆。"; //邮件内容
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
		if(!$mail->Send())
		{
		// echo "error. <p>";
		// echo "result: " . $mail->ErrorInfo;
			return false;
		// exit;
		}
		// echo "success";
		return true;

	}
	
}