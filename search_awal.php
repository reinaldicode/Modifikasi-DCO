<?php

include('index.php');
// include('func.php');
?>

<br />
<br />
<br />
<br />




	
	
	
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
        <script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
		<style type="text/css">
		.auto-complete{
			float:left;
			z-index:9999;
		}
		</style>
	
	
</head>





<?php

//include 'index.php';
include 'koneksi.php';

 ?>
    
    


	 <?php require_once('Connections/config.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

//mysql_select_db($database_config, $config);
$query_rsData = "SELECT distinct no_doc FROM docu ORDER BY no_doc ASC";
$rsData = mysqli_query($link, $query_rsData) or die(mysqli_error());
$row_rsData = mysqli_fetch_assoc($rsData);
$totalRows_rsData = mysqli_num_rows($rsData);

//mysql_select_db($database_config, $config);
$query_title = "SELECT distinct title FROM docu ORDER BY title ASC";
$title = mysqli_query($link, $query_title) or die(mysqli_error());
$row_title = mysqli_fetch_assoc($title);
$totalRows_title = mysqli_num_rows($title);
?>

<!doctype html>

<meta charset="utf-8">

<link rel="stylesheet" href="jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
	<script src="jquery-ui-1.10.3/jquery-1.9.1.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.widget.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.position.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.menu.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.autocomplete.js"></script>
  
    <link rel="stylesheet" href="js/a.js" />
    <script src="js/b.js"></script>
    <script src="js/c.js"></script>
 
    <script>

    </script>


<br />
<br />

<div class="row">
<div class="col-xs-4" style="margin-left=50px;">
  
  <form name="form1" method="GET" action="">
    <p>
      
      Input Document No. : <input name="doc_no" type="text" id="cari" size="60" class="form-control">
    </p>
     <p>
      
      Input Document Title : <input name="title" type="text" id="title" size="60" class="form-control">
    </p>
	<p>
		Document Status :
		<select name="status" id="status" class="form-control">
				
		<option value="" selected="selected" >Select status</option>
		<option value="Secured">Approved</option>
		<option value="Obsolate">Obsolate</option>
	</p>
	<p>
	</p>
    <p>
      <input type="submit" name="submit" id="submit" value="Show" class="btn btn-primary">
      <input type="reset" name="submit2" id="submit2" value="Reset" class="btn btn-warning">
    </p>
  </form>
  </div>
  </div>



<?php
mysqli_free_result($rsData);
mysqli_free_result($title);
?>

<?php
if (isset($_GET['submit']))
{
	$no_doc=$_GET['doc_no'];
	$title=$_GET['title'];
	$status=$_GET['status'];

	if ($title=='' AND $no_doc=='')
	{
		$sql="SELECT * FROM docu WHERE status = '$status' ORDER by no_drf";
	}
	elseif ($no_doc=='' AND $status=='')
	{
		$sql="SELECT * FROM docu WHERE title LIKE '%$title%' OR title LIKE '$title%' OR title LIKE '%$title' ORDER by title";
	}
	elseif ($title=='' AND $status=='')
	{
		// $sql="SELECT * FROM docu WHERE no_doc='$no_doc' ORDER by no_drf limit 150";
		$sql="SELECT * FROM docu WHERE no_doc LIKE '%$no_doc%' OR no_doc LIKE '$no_doc%' OR no_doc LIKE '%$no_doc' ORDER by no_doc";
	}
	elseif ($title=='')
	{
		$sql="SELECT * FROM docu WHERE status = '$status' AND (no_doc LIKE '%$no_doc%' OR no_doc LIKE '$no_doc%' OR no_doc LIKE '%$no_doc') ORDER by no_drf";
	}
	elseif ($no_doc=='')
	{
		$sql="SELECT * FROM docu WHERE status = '$status' AND (title LIKE '%$title%' OR title LIKE '$title%' OR title LIKE '%$title') ORDER by title";
	}
	elseif ($status=='')
	{
		$sql="SELECT * FROM docu WHERE no_doc LIKE '%$no_doc%' OR no_doc LIKE '$no_doc%' OR no_doc LIKE '%$no_doc' AND (title LIKE '%$title%' OR title LIKE '$title%' OR title LIKE '%$title') ORDER by title";
	}
	if ($title<>'' AND $no_doc<>''AND $status<>'')
	{
		$sql="SELECT * FROM docu WHERE status = '$status' AND (no_doc LIKE '%$no_doc%' OR no_doc LIKE '$no_doc%' OR no_doc LIKE '%$no_doc') AND (title LIKE '%$title%' OR title LIKE '$title%' OR title LIKE '%$title') ORDER by no_drf";
	}
	// echo $sql;

	?>
	<br />

	<?php

	$res=mysqli_query($link, $sql);
	?>
	<table class="table table-hover">
	<thead bgcolor="#00FFFF">
	<tr>
		<td>No</td>
		<td>Date</td>
		<td>No Document</td>
		<td>No Rev.</td>
		<td>No. Drf</td>
		<td>Title</td>
		<td>Status</td>
		<td>Process</td>
		<td>Section</td>
		<td>Device</td>
		<td>Detail</td>
		
		
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
			<?php echo "$info[status]";?>
		</td>
		<td>
			<?php echo "$info[process]";?>
		</td>
		<td>
			<?php echo "$info[section]";?>
		</td>
		<td>
			<?php echo "$info[device]";?>
		</td>
		<td>		
			<a href="detail.php?drf=<?php echo $info['no_drf'];?>&no_doc=<?php echo $info['no_doc'];?>&log=1" class="btn btn-xs btn-info" title="lihat detail"><span class="glyphicon glyphicon-search" ></span> </a>
			<a href="radf.php?drf=<?php echo $info['no_drf'];?>&section=<?php echo $info['section']?>" class="btn btn-xs btn-info" title="lihat RADF"><span class="glyphicon glyphicon-eye-open" ></span> </a>
		
		</td>
	</tr>
	</tbody>
	<div>
	<?php 
	$i++;} 
}


?> 
</div>