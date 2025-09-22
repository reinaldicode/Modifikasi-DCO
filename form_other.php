<?php include "form_login.php"; ?>
<br />




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


      var rev = $(this).data('rev');
     $(".modal-body #rev").val( rev );

     var section = $(this).data('section');
     $(".modal-body #section").val( section );

      var status = $(this).data('status');
     $(".modal-body #status").val( status );

     var tipe = $(this).data('tipe');
     $(".modal-body #tipe").val( tipe );
    });

});
</script>



<div class="row">

<div class="col-xs-4 well well-lg">
 <h2>Select Section</h2>

				<form action="" method="GET">
				 <table >
				 

				 	<tr>
				 	<?php 
					$sect="select * from section order by sect_name";
					$sql_sect=mysqli_query($link, $sect);
						?>
				 		<td>Section</td>
				 		<td>:</td>
				 		<td>
				 			<select name="section" class="form-control">
				 				<option value="0"> --- Select Section --- </option>
				 				<?php while($data_sec = mysqli_fetch_array( $sql_sect )) 
						{ ?>
						<option value="<?php echo "$data_sec[id_section]"; ?>"> <?php echo "$data_sec[sect_name]"; ?> </option>
						<?php } ?>
				 			</select>
				 		</td>
				 	</tr>

				 	<tr>
				 		<td>Status</td>
				 		<td>:</td>
				 		<td>
				 			<select name="status" class="form-control">
						<option value="0"> --- Select Status --- </option>
						
						<option value="Secured" selected> Approved </option>
						<option value="Review"> Review </option>
						<option value="Pending"> Pending </option>
						
						</option>
					</select>			
				 		</td>
				 	</tr>
				 	<tr>
				 		<td>Category</td>
				 		<td>:</td>
				 		<td>
				 			<select name="category" class="form-control">
						<option value="0"> --- Select Category --- </option>
						
						<option value="Internal" selected> Internal </option>
						<option value="External"> External </option>
						
						
						</option>
					</select>			
				 		</td>
				 	</tr>
				 	<tr>
				 		<td></td>
				 		<td></td>
				 		<td><input type='hidden' name='by' value='no_drf'>
				 			<input type="submit" value="Show" name="submit" class="btn btn-info">
				 		</td>
				 	</tr>
				</form>
				 </table>
				 </div>

 </div>

 <?php
if (isset($_GET['submit'])){





$section=$_GET['section'];
$status=$_GET['status'];

if ($section=='Engineering'){
	$sql="select * from docu where section='$section' and doc_type='Form' and status='$status' and category='$category' and device='-' order by $by";
}
else {
	$sql="select * from docu where section='$section' and doc_type='Form' and status='$status' and category='$category' order by $by";	
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
<h1> Form's List For Section: <strong><?php echo $section;?></strong></h1>
<table class="table table-hover">
<thead bgcolor="#00FFFF">
<tr>
	<td>No</td>
	<td>Date</td>
	<td><a href='form_other.php?section=<?php echo $section; ?>&by=No_doc&status=<?php echo $status; ?>&category=<?php echo $category?>&submit=Show'>No. Document</a></td>
	<td>No Rev.</td>
	<td><a href='form_other.php?section=<?php echo $section; ?>&by=no_drf&status=<?php echo $status; ?>&category=<?php echo $category?>&submit=Show'>drf</a></td>
	<td><a href='form_other.php?section=<?php echo $section; ?>&by=title&status=<?php echo $status; ?>&category=<?php echo $category?>&submit=Show'>Title</a></td>
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
	<a href="detail.php?drf=<?php echo $info['no_drf'];?>&no_doc=<?php echo $info['no_doc']; ?>" class="btn btn-xs btn-info" title="lihat detail"><span class="glyphicon glyphicon-search" ></span> </a>
	<a href="lihat_approver.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-info" title="lihat approver"><span class="glyphicon glyphicon-user" ></span> </a>	
	<a href="radf.php?drf=<?php echo $info['no_drf'];?>&section=<?php echo $info['section'] ?>" class="btn btn-xs btn-info" title="lihat RADF"><span class="glyphicon glyphicon-eye-open" ></span> </a>	
	<?php if ($state=='Admin' or ($state=="Originator" and $info['user_id']==$nrp)){ ?>
	<a href="edit_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-primary" title="Edit Doc"><span class="glyphicon glyphicon-pencil" ></span> </a>
	<a href="del_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-danger" onClick="return confirm('Delete document <?php echo $info['no_doc']?>?')" title="Delete Doc"><span class="glyphicon glyphicon-remove" ></span> </a>
	<?php if ($info['status']=='Secured') {?>
	
	
	<a data-toggle="modal" data-target="#myModal2" data-id="<?php echo $info['no_drf']?>" data-lama="<?php echo $info['file']?>" data-tipe="<?php echo $info['category']?>" data-status="<?php echo $info['status']?>" data-section="<?php echo $info['section']?>" class="btn btn-xs btn-success sec-file" title="Secure Document">
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
						<input type="hidden" name="type" id="type" class="form-control" value=""/>
						<input type="hidden" name="section" id="section" class="form-control" value=""/>
						<input type="hidden" name="status" id="status" class="form-control" value=""/>
						<input type="hidden" name="tipe" id="tipe" class="form-control" value=""/>
						<input type="file" name="baru" class="form-control">
						
					</div>
					
                    <div class="modal-footer"> <a class="btn btn-default" data-dismiss="modal">Cancel</a>

                        <input type="submit" name="upload" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
           
        </div>
    </div>
</div>