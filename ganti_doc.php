<?php

include 'header.php';
include 'koneksi.php';
extract($_REQUEST);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">


<head profile="http://www.google.com">
	
	<html>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<?php
			include ('koneksi.php');
			
			
				
				if(isset($_POST['upload'])){

					if(empty($_FILES['baru'])){
					die("File tidak bisa diupload");
					}

					$tmp_file = $_FILES['baru']['tmp_name'];
					$nma_file = $_FILES['baru']['name'];
					// unlink("document/".$filelama);

					if ($type=="WI"){
					if(copy($tmp_file, "WI/".$nma_file)){
					echo "File $nma_file Berhasil Disalin";
					}
					else {
					echo "File gagal ".$type." disalin";
					}
					}

					if ($type=="Form"){
					if(copy($tmp_file, "Form/".$nma_file)){
					echo "File $nma_file Berhasil Disalin";
					}
					else {
					echo "File gagal ".$type." disalin";
					}
					}

					if ($type=="Procedure"){
					if(copy($tmp_file, "Procedure/".$nma_file)){
					echo "File $nma_file Berhasil Disalin";
					}
					else {
					echo "File gagal ".$type." disalin";
					}
					}

					

					if(empty($_FILES['masterbaru'])){
					die("File tidak bisa diupload");
					}
					$tmp_file2 = $_FILES['masterbaru']['tmp_name'];
					$nma_file2 = $_FILES['masterbaru']['name'];
					if(copy($tmp_file2, "master/".$nma_file2)){
					echo "File $nma_file2 Berhasil Disalin";
					unlink("master/".$lama);
					}
					else {
					echo "File gagal disalin";
					}

					}
				
				
				$sql2="SELECT DISTINCT users.name, users.email FROM rev_doc, users WHERE (rev_doc.id_doc ='$drf' AND users.username = rev_doc.nrp and rev_doc.status='Pending') OR users.state = 'admin'";
				$res2=mysqli_query($link, $sql2)or die(mysqli_error());  
				mysqli_num_rows($res2);	 
				//echo $sql2;

				$query="update docu set file='$nma_file', status='Review' where no_drf=$drf";
				
				$hasil=mysqli_query($link, $query);
				//echo $query;
				$query3="update rev_doc set status='Review' where id_doc='$drf' and status='Pending'";
				
				$hasil3=mysqli_query($link, $query3);
				


				
				if ($res2>0){

			while ($data2=mysqli_fetch_row($res2))
			{

			
			require_once("class.smtp.php");
				require_once("class.phpmailer.php");

				$mail = new PHPMailer();

	$mail->IsSMTP();// send via SMTP
				$mail->Host     = "relay.sharp.co.jp"; // SMTP servers
				//$mail->Port = "3080"; 
				// $mail->SMTPAuth = true;// turn on SMTP authentication
				// $mail->Username = "int\gzb310003";// SMTP username
				// $mail->Password = "20132013";// SMTP password
				// pengirim
				$mail->From     = "dc_admin@ssi.sharp-world.com";
				$mail->FromName = "Admin Document Online System";

				// penerima
				 $mail->AddAddress($data2[1],$data2[0]);
				
				$mail->WordWrap = 50;                              // set word wrap
				//$mail->AddAttachment(getcwd()."/document/".$file_name);      // attachment
	//$mail->AddAttachment(getcwd() . "/file2.zip", "file_kedua.zip");
				$mail->IsHTML(true);                               // send as HTML
			
				//$link="http://www.ssi.global.sharp.co.jp/AglSystemOpto/";
				$mail->Subject  = "Revisi Dokumen" ;
				$mail->Body     =  "Attention Mr./Mrs. : Reviewer <br /> This following document has been changed, please consider to re-review 
				 <br /> No Document : ".$nodoc."<br /> Title : ".$title."<br /> Please Login into <a href='192.168.132.15/document'>Document Online System</a>, Thank You";
				//$mail->AltBody  =  "This research is supported by MIS";

				if(!$mail->Send())
				{
			   		echo "Message was not sent <p>";
			   		echo "Mailer Error: " . $mail->ErrorInfo;
   					exit;
				}
			
			
		}
	}

					
				
				
				if ($hasil)
				{
					
					
					?>
					<script language='javascript'>
							alert('File changed');
							document.location='my_doc.php';
						</script>
					<?php
					
				}else
				{
					echo "<center>";echo "No Database Connection";
					
				}		
			
			
			?>
			
			
			</html>
