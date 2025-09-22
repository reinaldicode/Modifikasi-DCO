
 <br />
 <br />
 <br />
 <br />
 <?php

			include "koneksi.php";
			extract($_REQUEST);
			
			
			// require_once("class.smtp.php");
			// 	require_once("class.phpmailer.php");
				require 'PHPMailer/PHPMailerAutoload.php';
				// include "smtp.php";

				$mail = new PHPMailer();

				$mail->IsSMTP();// send via SMTP
				// $mail->Host     = "relay.sharp.co.jp"; // SMTP servers
				include "smtp.php";
				//$mail->Port = "3080"; 
				// $mail->SMTPAuth = true;// turn on SMTP authentication
				// $mail->Username = "int\gzb310003";// SMTP username
				// $mail->Password = "20132013";// SMTP password

				// pengirim
				$mail->From     = "dc_admin@ssi.sharp-world.com";
				$mail->FromName = "Admin Document Online System";

				$sql2="SELECT DISTINCT users.name, users.email FROM rev_doc, users WHERE (rev_doc.id_doc ='$drf' AND users.username = rev_doc.nrp) and rev_doc.status='Review'";
			$res2=mysqli_query($link, $sql2)or die(mysqli_error());  
			mysqli_num_rows($res2);

			if ($res2>0){	 

			while ($data2=mysqli_fetch_row($res2))
			{


				// penerima
				 $mail->AddAddress($data2[1],$data2[0]);

				}
				
				$mail->WordWrap = 50;                              // set word wrap
				//$mail->AddAttachment(getcwd()."/document/".$file_name);      // attachment
	//$mail->AddAttachment(getcwd() . "/file2.zip", "file_kedua.zip");
				$mail->IsHTML(true);                               // send as HTML
			
				//$link="http://www.ssi.global.sharp.co.jp/AglSystemOpto/";
				$mail->Subject  = "Reminder" ;
				$mail->Body     =  "Attention Mr./Mrs. : Reviewer <br /> This following <span style='color:green'>".$type."</span> 
				Document need to be <span style='color:blue'>reviewed</span> <br /> No. Document : ".$nodoc."<br /> Document Title : 
				".$title."<br />Please Login into <a href='192.168.132.34/newdocument'>Document Online System</a>, Thank You";
				//$mail->AltBody  =  "This research is supported by MIS";

				if(!$mail->Send())
				{
			   		echo "Message was not sent <p>";
			   		echo "Mailer Error: " . $mail->ErrorInfo;
			   		echo $sql2;
   					exit;
				}
			
			
			
		}
		
		
			$sql_upd="update docu set reminder=reminder+1 where no_drf=$drf";
			$res_upd=mysqli_query($link, $sql_upd);
			
			
			
			
				?>
				<script language='javascript'>
							alert('Reminder sent');
							document.location='my_doc.php';
						</script>
				<?php
						
						
						
 
  

?>
