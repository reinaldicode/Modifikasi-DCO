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

 ?>
<h3>Obsolate Document's List</h3>
<form action="" method="GET" >
	<div class="col-sm-4">
				<select name="tipe" class="form-control">
					<option value="0"> --- Select Type --- </option>
					
					<option value="WI"> WI </option>
					<option value="Procedure"> Procedure </option>
					<option value="Form"> Form </option>
					
										
									</select>		

	<?php 
		$sect="select * from section order by sect_name";
		$sql_sect=mysqli_query($link, $sect);

	?>
		 <select name="section" class="form-control">
						<option value="0"> --- Select Section --- </option>
						<?php while($data_sec = mysqli_fetch_array( $sql_sect )) 
						{ ?>
						<option value="<?php echo "$data_sec[id_section]"; ?>"> <?php echo "$data_sec[sect_name]"; ?> </option>
						<?php } ?>
						</option>
					</select>
					
		<select name="cat" class="form-control">
						<option value="0"> --- Select Category --- </option>
						
						<option value="External"> External </option>
						<option value="Internal" selected="selected"> Internal </option>
						
						</option>
					</select>
			
					
					
	 <input type="submit" name="submit" value="Show" class="btn btn-primary" />
    </div>
    	<br />
    		<br />
    			<br />
    			
	</form>

<?php
if (isset($_GET['submit'])){





$section=$_GET['section'];
$tipe=$_GET['tipe'];
$cat=$_GET['cat'];

	$sql="select * from docu where section='$section' and doc_type='$tipe' and category='$cat' and status='Obsolate' order by no_drf";	
	
	// echo $sql;
?>
<br /><br />

<?php

$res=mysqli_query($link, $sql);
//$rows = mysql_num_rows($res);

//echo $sql;


?>
<br />
<table class="table table-hover">
<h1> Obsolate <?php echo $tipe; ?> List For Section: <strong><?php echo $section;?></strong> , Category: <strong><?php echo $tipe;?></strong></h1>
<thead bgcolor="#00FFFF">
<tr>
	<td>No</td>
	<td>Date</td>
	<td>No Document</td>
	<td>No Rev.</td>
	<td>No. Drf</td>
	<td>Title</td>
	<td>Process</td>
	<td>Section</td>
	<td>Action</td>
	
</tr>
</thead>
<?php
$i=1;
while($info = mysqli_fetch_array($res)) 
{ ?>
<tbody>
<tr>
	<td>
		<?php echo $i; ?>
	</td>
	<td>
		<?php echo "$info[tgl_upload]";?>
	</td>
	<td>
		<?php echo "$info[no_doc]";?>
	</td>
	<td>
		<?php echo "$info[no_rev]";?>
	</td>
	<td>
		<?php echo "$info[no_drf]";?>
	</td>
	<td>
	<?php if ($info['no_drf']>12800){$tempat=$info['doc_type'];} else {$tempat='document';}?>
	<a href="<?php echo $tempat; ?>/<?php echo "$info[file]"; ?>" >
		<?php echo "$info[title]";?>
		</a>
	</td>
	<td>
		<?php echo "$info[process]";?>
	</td>
	<td>
		<?php echo "$info[section]";?>
	</td>
	<td>
		<a href="detail.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-info" title="lihat detail"><span class="glyphicon glyphicon-search" ></span> </a>
	<a href="lihat_approver.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-info" title="lihat approver"><span class="glyphicon glyphicon-user" ></span> </a>
	<a href="radf.php?drf=<?php echo $info['no_drf'];?>&section=<?php echo $info['section']?>" class="btn btn-xs btn-info" title="lihat RADF"><span class="glyphicon glyphicon-eye-open" ></span> </a>
	<?php if ($state=='Admin' || $state=="Originator"){?>
	<a href="edit_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-primary" title="Edit Doc"><span class="glyphicon glyphicon-pencil" ></span> </a>
	<a href="del_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-danger" onClick="return confirm('Delete document <?php echo $info['no_doc']?>?')" title="Delete Doc"><span class="glyphicon glyphicon-remove" ></span> </a>
	
	<?php if ($info['status']=='Approved') {?>
	<a data-toggle="modal" data-target="#myModal2" data-id="<?php echo $info['no_drf']?>" data-lama="<?php echo $info['file']?>" data-status="<?php echo $info['status']?>" class="btn btn-xs btn-success sec-file" title="Secure Document">
	<span class="glyphicon glyphicon-play" ></span></a>
	<?php }} ?>
	</td>
	
	
</tr>
</tbody>
<div>
<?php 
$i++;} 


}


?> 
</div>