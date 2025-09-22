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
include('header.php');
extract($_REQUEST);
?>




<script type="text/javascript">
$(document).ready(function () {

    $('.sec-file').click(function () {
        $('span.user-id').text($(this).data('id'));
		var Id = $(this).data('id');
     $(".modal-body #drf").val( Id );
	 
	 var lama = $(this).data('lama');
     $(".modal-body #lama").val( lama );

      var type = $(this).data('type');
     $(".modal-body #type").val( type );

      var nodoc = $(this).data('nodoc');
     $(".modal-body #nodoc").val( nodoc );

      var rev = $(this).data('rev');
     $(".modal-body #rev").val( rev );

      var status = $(this).data('status');
     $(".modal-body #status").val( status );

     var tipe = $(this).data('tipe');
     $(".modal-body #tipe").val( tipe );
    });

});
</script>

<br />
<br />
<br />
<br />

</head>

<?php

//include 'index.php';
include 'koneksi.php';

 ?>
<h3>Procedure List</h3>
<form action="" method="GET" >
	<div class="col-sm-4">


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
	$sql="select * from docu where doc_type='Procedure' and status='Secured' order by $by ";	
}
else{
	$sql="select * from docu where section='$section' and doc_type='Procedure' and status='$status' order by $by ";
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
	<td><a href='procedure_login.php?section=<?php echo $section; ?>&by=no_drf&status=<?php echo $status; ?>&submit=Show'>drf</a></td>
	<td><a href='procedure_login.php?section=<?php echo $section; ?>&by=title&status=<?php echo $status; ?>&submit=Show'>Title</a></td>
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
		<?php echo $i++; ?>
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
	<a href="detail.php?drf=<?php echo $info['no_drf'];?>&no_doc=<?php echo $info['no_doc'];?>" class="btn btn-xs btn-info" title="lihat detail"><span class="glyphicon glyphicon-search" ></span></a>
	<a href="lihat_approver.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-info" title="lihat approver"><span class="glyphicon glyphicon-user" ></span></a>
	<a href="radf.php?drf=<?php echo $info['no_drf'];?>&section=<?php  echo $info['section']?>" class="btn btn-xs btn-info" title="lihat RADF"><span class="glyphicon glyphicon-eye-open" ></span> </a>
	<?php if ($state=='Admin' or ($state=="Originator" and $info['user_id']==$nrp)){?>
	<a href="edit_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-primary" title="Edit Doc"><span class="glyphicon glyphicon-pencil" ></span> </a>
	<a href="del_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-danger" onClick="return confirm('Delete document <?php echo $info['no_doc']?>?')" title="Delete Doc"><span class="glyphicon glyphicon-remove" ></span> </a>
	<?php if ($info['status']=='Secured') {?>
	
	<a data-toggle="modal" data-target="#myModal2" data-id="<?php echo $info['no_drf']?>" data-nodoc="<?php echo $info['no_doc'] ?>" data-rev="<?php echo $info['rev_to'] ?>" data-lama="<?php echo $info['file']?>" data-status="<?php echo $info['status']?>" data-type="<?php echo $info['doc_type']?>" class="btn btn-xs btn-success sec-file" title="Secure Document">
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

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Update Document</h4>

            </div>
            <div class="modal-body">
                <form name="secure_doc" method="POST" action="process.php" enctype="multipart/form-data">

                   	<div class="modal-body">
						
						<input type="hidden" name="drf" id="drf" class="form-control" value=""/>
						<input type="hidden" name="rev" id="rev" class="form-control" value=""/>
						<input type="text" name="type" id="type" class="form-control" value=""/>
						<input type="text" name="status" id="status" class="form-control" value=""/>
						<input type="text" name="nodoc" id="nodoc" class="form-control" value=""/>
						<input type="file" name="baru" class="form-control">
						
					</div>
					
                    <div class="modal-footer"> <a class="btn btn-default" data-dismiss="modal">Cancel</a>

                        <input type="submit" name="upload" value="Update" class="btn btn-primary" onclick="return confirm('Are you sure to make <?php echo $info['no_doc']?> obsolate?');">
                    </div>
                </form>
            </div>
           
        </div>
    </div>
</div>