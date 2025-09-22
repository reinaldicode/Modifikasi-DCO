<?php

include('header.php');
?>

<br />


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

<link rel="stylesheet" href="jquery-ui-1.10.3/themes/base/jquery.ui.all.css"></link>
	<script src="jquery-ui-1.10.3/jquery-1.9.1.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.widget.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.position.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.menu.js"></script>
	<script src="jquery-ui-1.10.3/ui/jquery.ui.autocomplete.js"></script>
	


	<script type="text/javascript">
$(document).ready(function () {

    $('.sec-file').click(function () {
        $('span.user-id').text($(this).data('id'));
		var Id = $(this).data('id');
     $(".modal-body #drf").val( Id );
	 
	 var lama = $(this).data('lama');
     $(".modal-body #lama").val( lama );

     var rev = $(this).data('rev');
     $(".modal-body #rev").val( rev );

     var type = $(this).data('type');
     $(".modal-body #type").val( type );

      var nodoc = $(this).data('nodoc');
     $(".modal-body #nodoc").val( nodoc );

     var cat = $(this).data('cat');
     $(".modal-body #cat").val( cat );

     var status = $(this).data('status');
     $(".modal-body #status").val( status );
    });

});
</script>



	<script type="text/javascript">
$(document).ready(function () {

    $('.mas-file').click(function () {
        $('span.user-id').text($(this).data('id'));
		var Id = $(this).data('id');
     $(".modal-body #drf").val( Id );
	 
	 var lama = $(this).data('lama');
     $(".modal-body #lama").val( lama );

     var rev = $(this).data('rev');
     $(".modal-body #rev").val( rev );

     var type = $(this).data('type');
     $(".modal-body #type").val( type );

      var nodoc = $(this).data('nodoc');
     $(".modal-body #nodoc").val( nodoc );

     var cat = $(this).data('cat');
     $(".modal-body #cat").val( cat );

     var status = $(this).data('status');
     $(".modal-body #status").val( status );
    });

});
</script>




	<script type="text/javascript">
$(document).ready(function () {

    $('.ex-file').click(function () {
        $('span.user-id').text($(this).data('id'));
		var Id = $(this).data('id');
     $(".modal-body #drf").val( Id );
	 
	 var lama = $(this).data('lama');
     $(".modal-body #lama").val( lama );

     var rev = $(this).data('rev');
     $(".modal-body #rev").val( rev );

     var type = $(this).data('type');
     $(".modal-body #type").val( type );

      var nodoc = $(this).data('nodoc');
     $(".modal-body #nodoc").val( nodoc );

     var cat = $(this).data('cat');
     $(".modal-body #cat").val( cat );

     var status = $(this).data('status');
     $(".modal-body #status").val( status );
    });

});
</script>

<script type="text/javascript">
function confirmObsolate()
{
	confirm("Are you sure to make this document obsolate?");
}
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
      
      Employee ID : <input name="empid" type="text" id="empid" size="60" class="form-control">
    </p>
    <p>
    		Month :
				 		
				 			<select name="bulan" id="bulan" class="form-control">
				
					<!-- <option value="<?= date('m')?>" selected="selected" ><?= date('F')?></option> -->
				  <option value="00" selected="selected">Select Month</option>
				  <option value="01" >January</option>
				  <option value="02" >February</option>
				  <option value="03" >March</option>
				  <option value="04" >April</option>
				  <option value="05" >May</option>
				  <option value="06" >June</option>
				  <option value="07" >July</option>
				  <option value="08" >August</option>
				  <option value="09" >September</option>
				  <option value="10" >October</option>
				  <option value="11" >November</option>
				  <option value="12" >December</option>
				  </select>
    </p>
    <p>
    	Year :
		<select name="tahun" id="tahun" class="form-control">
		<!-- <option value="<?= date('Y')?>" selected="selected" ><?= date('Y')?></option>? -->
		<option value="00" selected="selected">Select Year</option>
		<?php for ($year = 2015; $year <= date('Y') ; $year++)
		{
		echo "<option value=$year>$year</option>";
		};?>
		
		</select>
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
if (isset($_GET['submit'])){





$no_doc=$_GET['doc_no'];
$title=$_GET['title'];
$empid=$_GET['empid'];
$bulan=$_GET['bulan'];
$tahun=$_GET['tahun'];

if ($title=='' and $empid==''){
	$sql="select * from docu where no_doc='$no_doc' or (no_doc like '$no_doc%' or no_doc like '%$no_doc' or no_doc like '%$no_doc%')  order by no_drf limit 150";
}
if ($empid<>''){
	$sql="select * from docu where user_id='$empid' order by no_drf limit 150";
}
if ($no_doc=='' and $empid==''){
	$sql="select * from docu where title like '%$title%' or title like '$title%' or title like '%$title' order by title limit 150";
}
if ($title<>'' and $no_doc<>''){
	$sql="select * from docu where no_doc='$no_doc' and (title like '%$title%' or title like '$title%' or title like '%$title') order by no_drf limit 150";
}

if($bulan<>'00' && $tahun<>'00'){
	$sql="select * from docu where mid(tgl_upload,4,2)='$bulan' and right(tgl_upload,4)='$tahun' order by no_drf ";
}
// echo $sql;

?>
<br />

<?php

$res=mysqli_query($link, $sql);
//$rows = mysql_num_rows($res);

// echo $sql;


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
	<td>Type</td>
	<td>Process</td>
	<td>Section</td>
	<td>Device</td>
	<td>Detail</td>
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
	<td><?php echo $info['status']?></td>
	<td><?php echo $info['doc_type']?></td>
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
		<a href="detail.php?drf=<?php echo $info['no_drf'];?>&no_doc=<?php echo $info['no_doc'];?>" class="btn btn-xs btn-info" title="lihat detail"><span class="glyphicon glyphicon-search" ></span> </a>
		<a href="radf.php?drf=<?php echo $info['no_drf'];?>&section=<?php echo $info['section']?>" class="btn btn-xs btn-info" title="lihat RADF"><span class="glyphicon glyphicon-eye-open" ></span> </a>
	<a href="lihat_approver.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-info" title="lihat approver"><span class="glyphicon glyphicon-user" ></span> </a>	
	</td>
	<td>
		<?php if ($state=='Admin' or ( $state="Originator" and $info['user_id']==$nrp )){?>
	<a href="edit_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-primary" title="Edit Doc"><span class="glyphicon glyphicon-pencil" ></span> </a>
	<a href="del_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-danger" onClick="return confirm('Delete document <?php echo $info['no_doc']?>?')" title="Delete Doc"><span class="glyphicon glyphicon-remove" ></span> </a>
	<?php if ($info['status']=='Approved') {?>
	
	
	<?php }} ?>

	<?php if ($info['status']=='Secured' and $state=='Admin' and $info['category']=="Internal" and ($info['no_doc']<>'QC-ML-001' and $info['no_doc']<>'QC-ML-002' and $info['no_doc']<>'QC-ML-003' and $info['no_doc']<>'QC-ML-004' and $info['no_doc']<>'QC-ML-005' and
						$info['no_doc']<>'QC-ML-006' and $info['no_doc']<>'QC-ML-007' and $info['no_doc']<>'QC-ML008' and $info['no_doc']<>'DC-022' and $info['no_doc']<>'QC-DC-023'))
	 {
	 	?>
	<a data-toggle="modal" data-target="#myModal2" data-id="<?php echo $info['no_drf']?>" data-nodoc="<?php echo $info['no_doc'] ?>" data-rev="<?php echo $info['rev_to'] ?>" data-lama="<?php echo $info['file']?>" data-status="<?php echo $info['status']?>" data-type="<?php echo $info['doc_type']?>" class="btn btn-xs btn-success sec-file" title="Secure Document">
	<span class="glyphicon glyphicon-play" ></span></a>
	<?php } ?>

	<?php if ($state=='Admin' and $info['category']=="External")
	 {
	 	?>
	<a data-toggle="modal" data-target="#myModalex" data-id="<?php echo $info['no_drf']?>" data-nodoc="<?php echo $info['no_doc'] ?>" data-lama="<?php echo $info['file']?>" data-type="<?php echo $info['doc_type']?>" class="btn btn-xs btn-success ex-file" title="Secure Document">
	<span class="glyphicon glyphicon-play" ></span></a>
	<?php } ?>


	<?php if ($info['status']=='Secured' and $state=='Admin' and $info['category']=="Internal" and ($info['no_doc']=='QC-ML-001' or $info['no_doc']=='QC-ML-002' or $info['no_doc']=='QC-ML-003' or $info['no_doc']=='QC-ML-004' or $info['no_doc']=='QC-ML-005' or
						$info['no_doc']=='QC-ML-006' or $info['no_doc']=='QC-ML-007' or $info['no_doc']=='QC-ML008' or $info['no_doc']=='DC-022' or $info['no_doc']=='DC-023'))
	 {
	 	?>
	<a data-toggle="modal" data-target="#myModal5" data-id="<?php echo $info['no_drf']?>" data-nodoc="<?php echo $info['no_doc'] ?>" data-rev="<?php echo $info['rev_to'] ?>" data-lama="<?php echo $info['file']?>" data-status="<?php echo $info['status']?>" data-type="<?php echo $info['doc_type']?>" class="btn btn-xs btn-success mas-file" title="Secure Master File Document">
	<span class="glyphicon glyphicon-play" ></span></a>
	<?php } ?>

	<?php if ($state=='Admin')
	 {
	 	?>
	<!-- <button data-toggle="modal" data-target="#myModal6" data-drf="<?php echo $info['no_drf']?>" data-type="<?php echo $info['doc_type']?>" data-nodoc="<?php echo $info['no_doc']?>" data-title="<?php echo $info['title']?>" data-lama="<?php echo $info['file']?>"  class="btn btn-xs btn-warning upload-file">
	<span class="glyphicon glyphicon-upload"></span>
	Change Document</button> -->
	<a data-toggle="modal" data-target="#myModal6" data-id="<?php echo $info['no_drf']?>" data-nodoc="<?php echo $info['no_doc'] ?>" data-rev="<?php echo $info['rev_to'] ?>" data-lama="<?php echo $info['file']?>" data-status="<?php echo $info['status']?>" data-type="<?php echo $info['doc_type']?>" class="btn btn-xs btn-warning sec-file" title="Update Document">
	<span class="glyphicon glyphicon-play" ></span> Ganti Doc.</a>
	<?php } ?>

	</td>
	
</tr>
</tbody>
<div>
<?php 
$i++;} 


?>





<?php
}


?> 
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Secure Document</h4>

            </div>
            <div class="modal-body">
                <form name="secure_doc" method="POST" action="process.php" enctype="multipart/form-data">

                   	<div class="modal-body">
						
						<input type="hidden" name="drf" id="drf" class="form-control" value=""/>
						<input type="hidden" name="nodoc" id="nodoc" class="form-control" value=""/>
						<input type="hidden" name="rev" id="rev" class="form-control" value=""/>
						<input type="hidden" name="status" id="status" class="form-control" value=""/>
						<input type="hidden" name="type" id="type" class="form-control" value=""/>
						<input type="file" name="baru" class="form-control">
						
					</div>
					
                    <div class="modal-footer"> <a class="btn btn-default" data-dismiss="modal">Cancel</a>

                        <input type="submit" name="upload" value="Update" class="btn btn-primary" onclick="return confirm('Are you sure to make <?php !empty($info['no_doc']) ? print($info['no_doc']) : ''; ?> obsolate?');">
                    </div>
                </form>
            </div>
           
        </div>
    </div>
</div>


<div class="modal fade" id="myModalex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Update Document External</h4>

            </div>
            <div class="modal-body">
                <form name="secure_doc" method="POST" action="process_ex.php" enctype="multipart/form-data">

                   	<div class="modal-body">
						
						<input type="text" name="drf" id="drf" class="form-control" value=""/>
						<input type="hidden" name="nodoc" id="nodoc" class="form-control" value=""/>
						<input type="hidden" name="rev" id="rev" class="form-control" value=""/>
						<input type="hidden" name="status" id="status" class="form-control" value=""/>
						<input type="hidden" name="type" id="type" class="form-control" value=""/>
						<input type="file" name="baru" class="form-control">
						
					</div>
					
                    <div class="modal-footer"> <a class="btn btn-default" data-dismiss="modal">Cancel</a>

                        <input type="submit" name="upload" value="Update" onclick="return confirm('<?php !empty($info['no_doc']) ? print($info['no_doc']) : ''; ?> obsolate?');" class="btn btn-primary">
                    </div>
                </form>
            </div>
           
        </div>
    </div>
</div>

<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Update Document Master File</h4>

            </div>
            <div class="modal-body">
                <form name="secure_doc" method="POST" action="process_mas.php" enctype="multipart/form-data">

                   	<div class="modal-body">
						
						<input type="hidden" name="drf" id="drf" class="form-control" value=""/>
						<input type="text" name="nodoc" id="nodoc" class="form-control" value=""/>
						<input type="hidden" name="rev" id="rev" class="form-control" value=""/>
						<input type="hidden" name="status" id="status" class="form-control" value=""/>
						<input type="hidden" name="type" id="type" class="form-control" value=""/>
						<input type="file" name="baru" class="form-control">
						
					</div>
					
                    <div class="modal-footer"> <a class="btn btn-default" data-dismiss="modal">Cancel</a>

                        <input type="submit" name="upload" value="Update" class="btn btn-primary" onclick="return confirm('<?php !empty($info['no_doc']) ? print($info['no_doc']) : ''; ?> obsolate?');">
                    </div>
                </form>
            </div>
           
        </div>
    </div>
</div>

<div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Update Documents</h4>

            </div>
            <div class="modal-body">
                <form name="ganti_doc" method="POST" action="ganti_doc2.php" enctype="multipart/form-data" onclick="return confirm('<?php !empty($info['no_doc']) ? print($info['no_doc']) : ''; ?> obsolate?');">

                   	<div class="modal-body">
						
						<input type="hidden" name="drf" id="drf" class="form-control" value=""/>
						<input type="hidden" name="nodoc" id="nodoc" class="form-control" value=""/>
						<input type="hidden" name="rev" id="rev" class="form-control" value=""/>
						<input type="hidden" name="status" id="status" class="form-control" value=""/>
						<input type="hidden" name="type" id="type" class="form-control" value=""/>
						<input type="file" name="baru" class="form-control">
						
					</div>
					
                    <div class="modal-footer"> <a class="btn btn-default" data-dismiss="modal">Cancel</a>

                        <input type="submit" name="upload" value="Update" class="btn btn-primary" onclick="return confirm('Are you sure to make <?php !empty($info['no_doc']) ? print($info['no_doc']) : ''; ?> obsolate?');">
                    </div>
                </form>
            </div>
           
        </div>
    </div>
</div>