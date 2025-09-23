<?php
include('header.php');
?>
<script type="text/javascript">

document.ready = function() {
document.getElementById('section').onchange = disablefield;

}

function disablefield()
{
if ( document.getElementById('section').value != 'Production Section' )
{
	
document.getElementById('device').readonly = true;
document.getElementById('process').readonly = true;
}
else{
	document.getElementById('device').readonly = false;
	document.getElementById('process').readonly = false;
}

}

</script>

<script type="text/javascript">
			$(document).ready(function() {
				$('#wait_1').hide();
				$('#device').change(function(){
				  $('#wait_1').show();
				  $('#result_1').hide();
				  $.get("func2.php", {
					func: "device",
					drop_var: $('#device').val()
				  }, function(response){
					$('#result_1').fadeOut();
					setTimeout("finishAjax1('result_1', '"+escape(response)+"')", 400);
				  });
					return false;
				});



			});

			function finishAjax1(id, response) {
			  $('#wait_1').hide();
			  $('#'+id).html(unescape(response));
			  $('#'+id).fadeIn();

			  $('#wait_2').hide();
				$('#device').change(function(){
					
				  $('#wait_2').show();
				  $('#result_2').hide();
				  $.get("func.php", {
					func: "device",
					drop_var2: $('#device').val()
				  }, function(response){
					$('#result_2').fadeOut();
					setTimeout("finishAjax('result_2', '"+escape(response)+"')", 400);
				  });
					return false;
				});

				function finishAjax(id, response) {
			  $('#wait_2').hide();
			  $('#'+id).html(unescape(response));
			  $('#'+id).fadeIn();

			}

			}

			function finishAjax(id, response) {
			  $('#wait_2').hide();
			  $('#'+id).html(unescape(response));
			  $('#'+id).fadeIn();

			}
			</script>

<script>
    // Function to prevent spaces in No. Document field
    function preventSpaces(event) {
        if (event.which === 32 || event.keyCode === 32) {
            event.preventDefault();
            alert('Spasi tidak diperbolehkan pada field No. Document');
            return false;
        }
    }

    // Function to remove spaces if pasted
    function removeSpaces(element) {
        element.value = element.value.replace(/\s/g, '');
    }

    function validasi(){
        // var namaValid    = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
        // var emailValid   = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
        var nodoc         = formulir.nodoc.value;
        var norev         = formulir.norev.value;
	        var revto 		 = formulir.revto.value;
	        var type         = formulir.type1.value;
	        var cat		= formulir.cat.value;
	        var title		= formulir.title1.value;
	         var desc		= formulir.desc.value;
	       
        // File validation
        var file = formulir.file.files[0];
        var master = formulir.master.files[0];

        var pesan = '';
         
        

        if (nodoc== ''){
        	pesan += '-Data Nomor Dokumen belum diisi\n';	
        }
        
        // Check for spaces in No. Document
        if (nodoc.indexOf(' ') !== -1) {
            pesan += '-Nomor Dokumen tidak boleh mengandung spasi\n';
        }

         if (norev== ''){
        	pesan += '-Data Nomor revisi belum diisi\n';	
        }
         if (revto== '-'){
        	pesan += '-Data Revision to belum diisi\n';	
        }
         if (type== '-'){
        	pesan += '-Data type Dokumen belum diisi\n';	
        }
         if (cat== '-'){
        	pesan += '-Data Kategori Dokumen belum diisi\n';	
        }
         if (title== ''){
        	pesan += '-Data Judul Dokumen belum diisi\n';	
        }
         if (desc== '-'){
        	pesan += '-Data Description Dokumen belum diisi\n';	
        }

        // File validation - both files are required
        if (!file) {
            pesan += '-File Document belum dipilih\n';
        }
        
        if (!master) {
            pesan += '-File Master belum dipilih\n';
        }

        // if ( xtarget== '' || ytarget == '' || lum == '' || ra == ''){
        // 	pesan += '-Data Target belum lengkap\n';		
        // }
        
         
        // if (lum1 == '' || lum2 == '' || x1 == ''|| x2 == ''
        // 	|| y1 == ''|| y2 == ''|| ra1 == ''|| ra2 == ''
        // 	) {
        //     pesan += '-Data Scale Factor Tidak Lengkap\n';
        // }
         
        
         
        if (pesan != '') {
            alert('Data yang diisikan belum lengkap : \n'+pesan);
            return false;
        }
    return true
    }
</script>

</head>
<?php

//include 'index.php';
include 'koneksi.php';

 ?>

<br />
<div class="row">
<div class="col-xs-1"></div>
<div class="col-xs-6 well well-lg">
 <h2>Add New Document</h2>

				<form action="" method="POST" name="formulir" enctype="multipart/form-data">
				 <table class="table">
				 	<tr cellpadding="50px">
				 		<td>User ID &nbsp;&nbsp;</td>
				 		<td>:&nbsp;	&nbsp; &nbsp;</td>
				 		<td><input type="text" class="form-control" name="user" readonly="readonly" value="<?php echo $nrp;?>"></td>
				 	</tr>
				 	<tr cellpadding="50px">
				 		<td>Email &nbsp;&nbsp;</td>
				 		<td>:&nbsp;	&nbsp; &nbsp;</td>
				 		<td><input type="text" class="form-control" name="email" readonly="readonly" value="<?php echo $email;?>" ></td>
				 	</tr>
				 	<tr>
				 	<?php
				 	$sql1="select * from section where id_section='$sec' or sect_name='$sec' ";
				 	
				 	$ses_sql=mysqli_query($link, $sql1);
				 	$row1 = mysqli_fetch_array($ses_sql);
				 	$se=$row1['section_dept'];
				 	 ?>
				 		<td>Department </td>
				 		<td>:</td>
				 		<td><input type="text" class="form-control" name="dep" readonly="readonly" value="<?php echo $se;?>"></td>
				 	</tr>
				 	<tr cellpadding="50px">
				 		<td>No. Document &nbsp;&nbsp;</td>
				 		<td>:&nbsp;	&nbsp; &nbsp;</td>
				 		<td><input type="text" class="form-control" name="nodoc" onkeypress="return preventSpaces(event)" oninput="removeSpaces(this)" placeholder="Tidak boleh menggunakan spasi"></td>
				 	</tr>
				 	<tr cellpadding="50px">
				 		<td>No. Revision &nbsp;&nbsp;</td>
				 		<td>:&nbsp;	&nbsp; &nbsp;</td>
				 		<td><input type="text" class="form-control" name="norev">
				 		<input type="hidden" name="state" value=<?php echo $state;?> ></td>
				 	</tr>

				 	<tr>
				 		<td>Review To</td>
				 		<td>:</td>
				 		<td>
				 			
						 <select name="revto" class="form-control">
										<option value="-"> --- Select --- </option>
										
										<option value="Issue"> Issue </option>
										<option value="Revision"> Revision </option>
										<option value="Cancel"> Cancel </option>
									
										</option>
									</select>
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Category</td>
				 		<td>:</td>
				 		<td>
				 			
						 <select name="cat" class="form-control">
										<option value="-"> --- Select --- </option>
										
										<option value="Internal"> Internal </option>
										<option value="External"> External </option>
										
									
										</option>
									</select>
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Document Type</td>
				 		<td>:</td>
				 		<td>
				 			
						 <select name="type1" class="form-control">
										<option value="-"> --- Select Type --- </option>
										
										<option value="Form"> Form </option>
										<option value="Procedure"> Procedure </option>
										<option value="WI"> WI </option>
										<option value="Monitor Sample"> Monitor Sample </option>
										<option value="MSDS"> MSDS </option>
										<option value="Material Spec"> Material Spec </option>
										<option value="ROHS"> ROHS </option>
										</option>
									</select>
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Section</td>
				 		<td>:</td>
				 		<td>
				 			<?php 
						$sect="select * from section order by id_section";
						$sql_sect=mysqli_query($link, $sect);

					?>
						 <select id="section" name="section" class="form-control" >
										<option value="-"> --- Select Section --- </option>
										<?php while($data_sec = mysqli_fetch_array( $sql_sect )) 
										{ ?>
										<option value="<?php echo $data_sec['sect_name']; ?>"> <?php echo $data_sec['sect_name']; ?> </option>
										<?php } ?>
										</option>
									</select>
				 		</td>
				 	</tr>


					<tr>
				 		<td>Device</td>
				 		<td>:</td>
				 		<td>
				 			<?php 
						$sect="select * from device where status='Aktif' order by name";
						$sql_sect=mysqli_query($link, $sect);

					?>
						 <select id="device" name="device" class="form-control" >
										<option value="-"> --- Select Device --- </option>
										<option value="General Production"> General Production </option>
										<?php while($data_sec = mysqli_fetch_array( $sql_sect )) 
										{ ?>
										<option value="<?php echo "$data_sec[name]"; ?>"> <?php echo "$data_sec[name]"; ?> </option>
										<?php } ?>
										</option>
									</select>
				 		</td>
				 	</tr>


				 	<tr>
				 		<td>Process</td>
				 		<td>:</td>
				 		<td>
				 			 <span id="wait_1" style="display: none;">
					<img alt="Please Wait" src="images/wait.gif"/>
					</span>
					<span id="result_1" style="display: none;">
						
					</span>
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Doc. Title</td>
				 		<td>:</td>
				 		<td><input type="text" class="form-control" name="title1"></td>
				 	</tr>

				 	<tr>
				 		<td>Doc. Description</td>
				 		<td>:</td>
				 		<td >
				 		<textarea  class="form-control" name="desc" cols="40" rows="10" wrap="physical"></textarea>
                	    </td>
				 	</tr>

				 	<tr>
				 		<td>Requirement Document</td>
				 		<td>:</td>
				 		<td >
							<table>
								<tr><input type="radio" aria-label="ISO 9001" name="iso" value="1" >&nbsp; ISO 9001 <br /></tr>
								<tr><input type="radio" aria-label="ISO 14001" name="iso" value="2" >&nbsp; ISO 14001 <br /></tr>
								<tr><input type="radio" aria-label="ISO 45001" name="iso" value="4" >&nbsp; ISO 45001 <br /></tr>
								<tr><input type="radio" aria-label="OHSAS" name="iso" value="3" >&nbsp; OHSAS / SMK3<br /></tr>
								<tr><input type="radio" aria-label="indlaw" name="iso" value="5" >&nbsp; Indonesian Law <br /></tr>
					      	</table>
						</td>
				 	</tr>

					 <tr>
				 		<td>Document Transfer System</td>
				 		<td>:</td>
				 		<td >
							<table>								
								<tr><input type="checkbox" aria-label="RADF" name="radf" value="1" checked disabled>&nbsp; RADF (mandatory) <br /></tr>
								<tr><input type="checkbox" aria-label="Sequence Training" name="seqtrain" value="1" >&nbsp; Sequence Training <br /></tr>
								<tr><input type="checkbox" aria-label="Direct Training" name="dirtrain" value="1" >&nbsp; Direct Training <br /></tr>
							</table>
                	    </td>
				 	</tr>

				 	<tr>
				 		<td>Related/Addopted Document</td>
				 		<td>:</td>
				 		<td>
				 		<input type="text" class="form-control" name="rel[1]">
				 		<input type="text" class="form-control" name="rel[2]">
				 		<input type="text" class="form-control" name="rel[3]">
				 		<input type="text" class="form-control" name="rel[4]">
				 		<input type="text" class="form-control" name="rel[5]">
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Upload File Document (*Di-Review)<br />
				 		(File .pdf untuk WI/Procedure)<br />
				 		(File .xlsx untuk Form) <span style="color: red;">*Required</span>
				 		</td>
				 		<td>:</td>
				 		<td>
				 		<input type="file" class="form-control" name="file" required>
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Upload File Master(.xlsx/.docx) <span style="color: red;">*Required</span></td>
				 		<td>:</td>
				 		<td>
				 		<input type="file" class="form-control" name="master" required>
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Revision reason/history</td>
				 		<td>:</td>
				 		<td >
				 		<textarea  class="form-control" name="hist" cols="40" rows="10" wrap="physical"></textarea>
                	    </td>
				 	</tr>

				 	<tr>
				 		<td></td>
				 		<td></td>
				 		<td>
				 			<input type="submit" value="Save" name="submit" class="btn btn-success" onclick="return validasi()">
				 		</td>
				 	</tr>
				</form>
				 </table>
				 </div>

 </div>


 <?php
if (isset($_POST['submit']))
{
	include 'koneksi.php';

	$nama=$_POST['user'];
	$email=$_POST['email'];
	$dep=$_POST['dep'];
	$nodoc=$_POST['nodoc'];
	$norev=$_POST['norev'];
	$revto=$_POST['revto'];
	$type=$_POST['type1'];
	$section=$_POST['section'];
	$device=$_POST['device'];
	$process=$_POST['proc'];
	$title=$_POST['title1'];
	$desc=$_POST['desc'];
	$cat=$_POST['cat'];
	$iso=$_POST['iso'];
	$seqtrain=isset($_POST['seqtrain'])? $_POST['seqtrain']:0;
	$dirtrain=isset($_POST['dirtrain'])? $_POST['seqtrain']:0;
	//$master=$POST['master']
	$tgl = date('d-m-Y');
	$state=$_POST['state'];

	// Server-side validation for spaces in nodoc
	if (strpos($nodoc, ' ') !== false) {
		?>
		<script language='javascript'>
			alert('Nomor Dokumen tidak boleh mengandung spasi');
			document.location='upload.php';
		</script>
		<?php
		exit;
	}

	// Server-side validation for file uploads
	if (!isset($_FILES["file"]) || $_FILES["file"]["error"] == UPLOAD_ERR_NO_FILE) {
		?>
		<script language='javascript'>
			alert('File Document harus diupload');
			document.location='upload.php';
		</script>
		<?php
		exit;
	}

	if (!isset($_FILES["master"]) || $_FILES["master"]["error"] == UPLOAD_ERR_NO_FILE) {
		?>
		<script language='javascript'>
			alert('File Master harus diupload');
			document.location='upload.php';
		</script>
		<?php
		exit;
	}

	if ($cat=='External')
	{
		$sta_doc='Secured';
	}
	else
	{
		$sta_doc='Review';
	}

	$cek_opl = substr($nodoc, 0,3);

	if($cek_opl=='OPL')
	{
		$sta_doc='Secured';
	}
	if($type=='Monitor Sample')
	{
		$sta_doc='Secured';
	}
	if($type=='MSDS')
	{
		$sta_doc='Secured';	
	}

	if($type=='Material Spec')
	{
		$sta_doc='Secured';	
	}
	if($type=='ROHS')
	{
		$sta_doc='Secured';	
	}

	$hist=$_POST['hist'];

	$target_dir = "$type/";
	$target_file = $target_dir . basename($_FILES["file"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image

		
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
	{
		echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
	}
	
	$target_dir = "master/";
	$target_file = $target_dir . basename($_FILES["master"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image

	// Check file size
	if($_FILES["master"]["size"] > 15000000)
	{
		?>
	
		<script language='javascript'>
								alert('Ukuran File Master Terlalu Besar, Max 15Mb');
								document.location='upload.php';
							</script>

		<?php
	}
	else
	{
		if(move_uploaded_file($_FILES["master"]["tmp_name"], $target_file))
		{
			echo "The file ". basename( $_FILES["master"]["name"]). " has been uploaded.";
		}
		$nama_file=basename($_FILES["file"]["name"]);
		$nama_master=basename($_FILES["master"]["name"]);
		
		$sql="insert into docu(no_drf,user_id,email,dept,no_doc,no_rev,rev_to,doc_type,section,device,process,title,descript,iso,seqtrain,dirtrain,file,history,status,tgl_upload,category,final,file_asli,reminder)
		values (0,'$nama','$email','$dep','$nodoc','$norev','$revto','$type','$section','$device','$process','$title','$desc',$iso,$seqtrain,$dirtrain,'$nama_file','$hist','$sta_doc','$tgl','$cat','','$nama_master',0)";

		// echo $sql;
		// echo $email;
		$res=mysqli_query($link, $sql);
		$drf=mysqli_insert_id($link);

		if($res)
		{
			if(($state != 'Admin' or $cat != 'External' ) and ($type!='Material Spec' or $type!='ROHS' or $type!='MSDS'))
			{
				echo "berhasil";
				?>
				<script language='javascript'>
					alert('Document Uploaded , please set the approvers');
					document.location='set_approver.php?id_doc=<?php echo $drf?>&section=<?php echo $section?>&type=<?php echo $type?>&iso=<?php echo $iso?>&nodoc=<?php echo $nodoc;?>&title=<?php echo $title;?>';
				</script>
				<?php 
			}
			elseif($type=='MSDS' or $type=='Material Spec' or $type=='ROHS')
			{
				// echo "gagal";
				?>
				<script language='javascript'>
					alert('Document Uploaded');
					//document.location='set_approver.php?id_doc=<?php echo $drf?>&section=<?php echo $section?>&type=<?php echo $type?>&iso=<?php echo $iso?>&nodoc=<?php echo $nodoc;?>&title=<?php echo $title;?>';
					document.location='upload.php';
				</script>
				<?php
			}
			else
			{
				?>
				<script language='javascript'>
					alert('Document Uploaded');
					//document.location='set_approver.php?id_doc=<?php echo $drf?>&section=<?php echo $section?>&type=<?php echo $type?>&iso=<?php echo $iso?>&nodoc=<?php echo $nodoc;?>&title=<?php echo $title;?>';
					document.location='upload.php';
				</script>
				<?php
			}

			//require_once("class.smtp.php");
			//require_once("class.phpmailer.php");
			require 'PHPMailer/PHPMailerAutoload.php';
			
			$mail = new PHPMailer();

			// setting			
			$mail->IsSMTP();// send via SMTP
			include 'smtp.php';
			// $mail->Host     = "relay.sharp.co.jp"; // SMTP servers
			//$mail->Port = "3080"; 
			// $mail->SMTPAuth = true;// turn on SMTP authentication
			// $mail->Username = "int\gzb310003";// SMTP username
			// $mail->Password = "20132013";// SMTP password

			// pengirim
			$mail->setFrom('dc_admin@ssi.sharp-world.com');
			//$mail->From     = "dc_admin@ssi.sharp-world.com";
			$mail->FromName = "Admin Document Online System";

			// penerima
			$mail->addAddress($email);
			if($cat=='External')
			{
				$mail->addAddress("qa01@ssi.sharp-world.com");
			}
			
			$mail->WordWrap = 50;                              // set word wrap
			$mail->IsHTML(true);                               // send as HTML
				
			//$link="http://www.ssi.global.sharp.co.jp/AglSystemOpto/";
			$mail->Subject  = "Document Uploaded" ;
			$mail->Body     =  "Attention Mr./Mrs. : Originator <br /> This following <span style='color:green'>".$type."</span> document was 
			<span style='color:green'>Uploaded</span> into the System <br /> No. Document : ".$nodoc."<br /> Revision History : ".$hist."<br />
			Please Login into <a href='192.168.132.34/document'>Document Online System</a> to monitor the Document, Thank You";
			//$mail->AltBody  =  "This research is supported by MIS";

			if(!$mail->Send())
			{
				echo "Message was not sent <p>";
				echo "Mailer Error: " . $mail->ErrorInfo;
				// echo $sql2;
				exit;
			}
		}
		else
		{
			echo "gagal_total";
			?>
			<script language='javascript'>
			// alert('Document Upload Failed');
			document.location='upload.php';
			</script>
			<?php
		}

		$jumlah = count($_POST["rel"]);

		for($i=0; $i < $jumlah; $i++) 
		{
			$no_doc=$_POST["rel"][$i];

			if ($no_doc <> '' )
			{
				$q=mysqli_query($link, "insert into rel_doc(id,no_drf,no_doc) values ('',$drf,'$no_doc')"); 
			}
		}

		if ($norev=='0')
		{
			$insert="insert into distribusi(id_dis,no_drf,pic,give,date_give,location,receiver,retrieve,retrieve_from,retrieve_date)
			values('',$drf,'','','','','','','','')";
			$result=mysqli_query($link, $insert);
		}
	}
	//else{ echo "Error: " . $sql . "<br>" . $conn->error;}
}
?>