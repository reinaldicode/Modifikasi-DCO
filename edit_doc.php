<?php

include('header.php');

?>

<br />
<br />
<br />
<br />


</head>



<?php

//include 'index.php';
include 'koneksi.php';

// $id=$_POST['id'];
//echo $id;
$sql="select * from docu where no_drf='$drf'";
$res=mysqli_query($link, $sql);

while($data = mysqli_fetch_array($res)) 
{
 ?>

<div class="row">
<div class="col-xs-1"></div>
<div class="col-xs-7 well well-lg">
 <h2>Edit Document</h2>

				
			


				<form action="" method="POST" enctype="multipart/form-data">
				 <table class="table">
				 	
				 	
				 	<tr cellpadding="50px">
				 		<td>No. Document &nbsp;&nbsp;</td>
				 		<td>:&nbsp;	&nbsp; &nbsp;</td>
				 		<td><input type="text" class="form-control" name="nodoc" value="<?php echo "$data[no_doc]" ?>"></td>
				 	</tr>
				 	<tr cellpadding="50px">
				 		<td>No. Revision &nbsp;&nbsp;</td>
				 		<td>:&nbsp;	&nbsp; &nbsp;</td>
				 		<td><input type="text" class="form-control" name="norev" value="<?php echo "$data[no_rev]" ?>"></td>
				 	</tr>

				 	<tr>
				 		<td>Review To</td>
				 		<td>:</td>
				 		<td>
				 			
						 <select name="revto" class="form-control">
										<option value="-"> --- Select --- </option>
										
										<option value="Issue" <?php if ($data['rev_to']=="Issue") {echo 'selected';} ?> > Issue </option>
										<option value="Revision" <?php if ($data['rev_to']=="Revision") {echo 'selected';} ?> > Revision </option>
										<option value="Cancel" <?php if ($data['rev_to']=="Cancel") {echo 'selected';} ?> > Cancel </option>
									
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
										
										<option value="Internal" <?php if ($data['category']=="Internal") {echo 'selected';} ?> > Internal </option>
										<option value="External" <?php if ($data['category']=="External") {echo 'selected';} ?> > External </option>
										
									
										</option>
									</select>
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Document Type</td>
				 		<td>:</td>
				 		<td>
				 			
						 <select name="type" class="form-control">
										<option value="-"> --- Select Type --- </option>
										
										<option value="Form" <?php if ($data['doc_type']=="Form") {echo 'selected';} ?> > Form </option>
										<option value="Procedure" <?php if ($data['doc_type']=="Procedure") {echo 'selected';} ?> > Procedure </option>
										<option value="WI" <?php if ($data['doc_type']=="WI") {echo 'selected';} ?> > WI </option>
									
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
										<option value="<?php echo "$data_sec[id_section]"; ?>" <?php if ($data['section']==$data_sec['id_section']) {echo 'selected';} ?>> <?php echo "$data_sec[sect_name]"; ?> </option>
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
						$dev="select * from device where status='Aktif' order by group_dev";
						$sql_dev=mysqli_query($link, $dev);

					?>
						 <select id="device" name="device" class="form-control" >
										<option value="-"> --- Select Device --- </option>
										<?php while($data_dev = mysqli_fetch_array( $sql_dev )) 
										{ ?>
										<option value="<?php echo "$data_dev[name]"; ?>" <?php if ($data['device']==$data_dev['name']) {echo 'selected';} ?> > <?php echo "$data_dev[name]"; ?> </option>
										<?php } ?>
										</option>
									</select>
				 		</td>
				 	</tr>


				 	<tr>
				 		<td>Process</td>
				 		<td>:</td>
				 		<td>
				 			<?php 
						$sect="select * from process order by  proc_name";
						$sql_sect=mysqli_query($link, $sect);

					?>
						 <select id="process" name="process" class="form-control">
										<option value="-"> --- Select Process --- </option>
										<?php while($data_sec = mysqli_fetch_array( $sql_sect )) 
										{ ?>
										<option value="<?php echo "$data_sec[proc_name]"; ?>" <?php if ($data['process']==$data_sec['proc_name']) {echo 'selected';} ?> > <?php echo "$data_sec[proc_name]"; ?> </option>
										<?php } ?>
										</option>
									</select>
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Doc. Title</td>
				 		<td>:</td>
				 		<td><input type="text" class="form-control" name="title" value="<?php echo $data['title']; ?>"></td>
				 		<td><input type="hidden" class="form-control" name="iso" value="0"></td>
				 	</tr>

				 	<tr>
				 		<td>Doc. Description</td>
				 		<td>:</td>
				 		<td >
				 		<textarea  class="form-control" name="desc" cols="40" rows="10" wrap="physical" ><?php echo $data['descript']; ?></textarea>
                	    </td>
				 	</tr>

				 	<tr>
				 		<td>Requirement Document</td>
				 		<td>:</td>
				 		<td >
							
					     
					      <table>
					     <tr><input type="radio" aria-label="ISO 9001" name="iso" value="1" <?php if ($data['iso']==1){echo "checked";}?> >&nbsp; ISO 9001 <br /></tr>
					        <tr><input type="radio" aria-label="ISO 14001" name="iso" value="2" <?php if ($data['iso']==2){echo "checked";}?> >&nbsp; ISO 14001 <br /></tr>
					        <tr><input type="radio" aria-label="OHSAS" name="iso" value="3" <?php if ($data['iso']==3){echo "checked";}?>  >&nbsp; OHSAS <br /></tr>
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
				 		<td>Revision reason/history</td>
				 		<td>:</td>
				 		<td >
				 		<textarea  class="form-control" name="hist" cols="40" rows="10" wrap="physical" > <?php echo $data['history']?> </textarea>
                	    </td>
				 	</tr>

				 	<tr>
				 		<td></td>
				 		<td></td>
				 		<td>
				 			<input type="submit" value="Save" name="submit" class="btn btn-success">
				 		</td>
				 	</tr>
				</form>



				 </table>
				 </div>

 </div>
	<?php } ?>


 <?php
if (isset($_POST['submit'])){


include 'koneksi.php';

// $nama=$_POST['nama'];
// $group=$_POST['group'];
// $status=$_POST['status'];

//$drf=$_POST['drf'];

$nodoc=$_POST['nodoc'];
$norev=$_POST['norev'];
$revto=$_POST['revto'];
$cat=$_POST['cat'];
$type=$_POST['type'];
$section=$_POST['section'];
$device=$_POST['device'];
$process=$_POST['process'];
$title=$_POST['title'];
$desc=$_POST['desc'];
$iso=$_POST['iso'];
$hist=$_POST['hist'];
// echo $nodoc;
echo $drf;

echo $sql="update docu set no_doc='$nodoc' , no_rev='$norev' , rev_to='$revto', category='$cat', doc_type='$type', section='$section', device='$device',process='$process',
	title='$title',descript='$desc', iso='$iso', history='$hist' where no_drf='$drf'";

$res=mysqli_query($link, $sql);


$jumlah = count($_POST["rel"]);

for($i=0; $i < $jumlah; $i++) 
{
    $no_doc=$_POST["rel"][$i];

    if ($no_doc <> '' ){
	
  $q=mysqli_query($link, "insert into rel_doc(id,no_drf,no_doc) values ('','$drf','$no_doc')"); 
}
}


if (!$res) {
    die("Connection failed: " . mysqli_error());
} else{
	?>

<script language='javascript'>
							alert('Document updated');
							document.location='my_doc.php';
						</script>

<?php }
}
 ?>