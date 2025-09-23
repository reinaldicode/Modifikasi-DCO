	<link href="bootstrap/css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap-responsive.min.css" media="all" type="text/css" rel="stylesheet">
		<link href="bootstrap/css/facebook.css" media="all" type="text/css" rel="stylesheet">
		
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="bootstrap/js/bootstrap-dropdown.js"></script>
		<script src="bootstrap/js/facebook.js"></script>
	
	<link rel="stylesheet" href="bootstrap/css/datepicker.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
		 <script src="bootstrap/js/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap-datepicker.js"></script>
</head>


<?php

include 'index.php';
include 'koneksi.php';
extract($_REQUEST);
 ?>
 <br />
 <br />
<h3>Procedure List</h3>
<form action="" method="GET" >
	<div class="col-sm-4">


	<?php 
		$sect="select * from section order by sect_name";
		$sql_sect=mysqli_query($link, $sect);

	?>
		 <select name="section" class="form-control">
						<option value="0"> --- Select Section --- </option>
						<?php while($data_sec = mysqli_fetch_array($sql_sect)) 
						{ ?>
						<option value="<?php echo "$data_sec[id_section]"; ?>"> <?php echo "$data_sec[sect_name]"; ?> </option>
						<?php } ?>
						</option>
					</select>
					
		
		<select name="status" class="form-control">
						<option value="0"> --- Select Status --- </option>
						
						<option value="Secured" selected> Approved </option>
						<option value="Review"> Review </option>
						<option value="Pending"> Pending </option>
						
						</option>
					</select>			
					
					<input type='hidden' name='by' value='no_drf'>
	 <input type="submit" name="submit" value="Show" class="btn btn-primary" />
    </div>
    	<br />
    		<br />
    			<br />
    			
	</form>

<?php
if (isset($_GET['submit'])){
$section=$_GET['section'];

$status=$_GET['status'];

if ($section=='0'){
	$sql="select * from docu where doc_type='Procedure' and status='Secured' order by $by limit 150";	
}
else{
	$sql="select * from docu where section='$section' and doc_type='Procedure' and status='$status' order by $by limit 150";
}
	
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
<h1> Procedure List For Section: <strong><?php echo $section;?></strong> , Status: <strong><?php echo $status; ?></strong></h1>
<thead bgcolor="#00FFFF">
<tr>
	<td>No</td>
	<td>Date</td>
	<td><a href='procedure_login.php?section=<?php echo $section; ?>&by=no_doc&status=<?php echo $status; ?>&submit=Show'>No. Document</a></td>
	<td>No Rev.</td>
	<td><a href='procedure_awal.php?section=<?php echo $section; ?>&by=no_drf&status=<?php echo $status; ?>&submit=Show'>drf</a></td>
	<td><a href='procedure_awal.php?section=<?php echo $section; ?>&by=title&status=<?php echo $status; ?>&submit=Show'>Title</a></td>
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
	<?php if ($info['no_drf']>12967){$tempat=$info['doc_type'];} else {$tempat='document';}?>
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
	<a href="detail.php?drf=<?php echo $info['no_drf'];?>&no_doc=<?php echo $info['no_doc'];?>&log=1" class="btn btn-xs btn-info" title="lihat detail"><span class="glyphicon glyphicon-search" ></span></a>
	
	<a href="radf.php?drf=<?php echo $info['no_drf'];?>&section=<?php echo $info['section']?>&log=1" class="btn btn-xs btn-info" title="lihat RADF"><span class="glyphicon glyphicon-eye-open" ></span> </a>
	
	</td>
	
	
</tr>
</tbody>
<div>
<?php 
$i++;} 
}


?> 
</div>