<?php
// session_start();
// if(empty($_SESSION['user_authentication']))
// {
// 	header("location:login.php");
// 	die();
// }
// if($_SESSION['user_authentication'] !="valid")
// {
// 	header("location:login.php");
// 	die();
// }
include 'header.php';
include 'koneksi.php';
extract($_REQUEST);
?>

<div id="profile">
<div class="alert alert-info" role="alert"><b id="welcome">Welcome : <i><?php echo $name; ?>, anda login sebagai <?php echo $state;?></i></b></div>
</div>
<script type="text/javascript">
$(document).ready(function () {

    $('.upload-file').click(function () {
        $('span.user-id').text($(this).data('id'));
		var Id = $(this).data('id');
     $(".modal-body #drf").val( Id );
	 
	 var nodoc = $(this).data('nodoc');
     $(".modal-body #nodoc").val( nodoc );

     var type = $(this).data('type');
     $(".modal-body #type").val( type );

      var lama = $(this).data('lama');
     $(".modal-body #lama").val( lama );

     var title = $(this).data('title');
     $(".modal-body #title").val( title );
    });

});
</script>

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

     var status = $(this).data('status');
     $(".modal-body #status").val( status );
    });

});
</script>
	
</head>

<h2>Manage Document</h2>

<form action="" method="GET" >
	<div class="col-sm-4">
				<select name="tipe" class="form-control">
					<option value="-"> --- Select Type --- </option>
					
					<option value="WI"> WI </option>
					<option value="Procedure"> Procedure </option>
					<option value="Form"> Form </option>
					</select>
					<?php if ($state=='Admin'){ ?>
					<select name="status" class="form-control">
					<option value="-"> --- Select Status --- </option>
					
					<option selected value="Review"> Review </option>
					<option value="Pending"> Pending </option>
					<option value="Approved"> Approved </option>
					</select>			
					<?php } ?>

	 <input type="submit" name="submit" value="Show" class="btn btn-primary" />
    </div>
    	<br />
    		<br />
    			<br />
    			
	</form>
	

<?php

if(isset($_GET['submit']))
{

 $tipe=$_GET['tipe'];

if($tipe=="-"){
	$sort="";
}else{
$sort="and doc_type='$tipe'";
}

if($state=='Admin') {
	$sql="select * from docu where status='$status' $sort order by no_drf";
}
elseif($state=='Originator') {
	$sql="select * from docu where (docu.status='Review' or docu.status='Pending' or docu.status='Approved') $sort and user_id='$nrp' order by no_drf";
}
elseif($state=='Approver') {
	$sql="select * from docu,rev_doc where docu.status='Review' and rev_doc.status='Review' and docu.no_drf=rev_doc.id_doc and rev_doc.nrp='$nrp' $sort order by no_drf";
}

$res=mysqli_query($link, $sql);
//$rows = mysql_num_rows($res);

// echo $sql;


?>

<table class="table table-hover">
<thead bgcolor="#00FFFF">
<tr>
	<td>No</td>
	<td>Date</td>
	<td>No. Drf</td>
	<td>No Document</td>
	<td>No Rev.</td>
	<td>Title</td>
	<td>Process</td>
	<td>Section</td>
	<td>Type</td>
	<td>Review To</td>
	<td>Pass Day</td>
	<td>Status</td>
	<td>Action</td>
	
</tr>
</thead>
<tbody>
<?php
$j=1;
// echo $sql;
while($info = mysqli_fetch_array($res)) 
{ ?>

<tr>
	<td>
		<?php echo $j; ?>
	</td>
	<td>
		<?php echo $info['tgl_upload'];?>
	</td>
	<td>
		<?php echo $info['no_drf'];?>
	</td>
	<td>
	
		<?php echo $info['no_doc'];?>
		
	</td>
	
	<td>
		<?php echo $info['no_rev'];?>
	</td>
	<td>
	<?php if ($info['no_drf']>12967){$tempat=$info['doc_type'];} else {$tempat="document";}?>
	<a href="<?php echo $tempat; ?>/<?php echo $info['file']; ?>" >
		<?php echo $info['title'];?>
		</a>
	</td>
	<td>
		<?php echo $info['process'];?>
	</td>
	<td>
		<?php echo $info['section'];?>
	</td>
	<td>
		<?php echo $info['doc_type'];?>
	</td>
	<td>
		<?php echo $info['rev_to'];?>
	</td>
	<td>
		<?php
			$tglsekarang = date('d-m-Y');
			$tglissue  =$info['tgl_upload'];
			$pecah1 = explode("-", $tglissue );
			$date1 = $pecah1[0];
			$month1 = $pecah1[1];
			$year1 = $pecah1[2];
			$pecah2 = explode("-", $tglsekarang);
			$date2 = $pecah2[0];
			$month2 = $pecah2[1];
			$year2 =  $pecah2[2];
			$waktusekarang = GregorianToJD($month1, $date1, $year1);
			$waktuinput = GregorianToJD($month2, $date2, $year2);
			$selisih =$waktuinput - $waktusekarang; 
			//echo $selisih;



			$dat1 = $info['tgl_upload'];
			$dat2 = $tglsekarang;
	 
			// memecah bagian-bagian dari tanggal $date1
			$pecahTgl1 = explode("-", $dat1);
			
			// membaca bagian-bagian dari $date1
			$tgl1 = $pecahTgl1[0];
			$bln1 = $pecahTgl1[1];
			$thn1 = $pecahTgl1[2];
	 
			//echo "<p>Tanggal yang merupakan hari minggu adalah:</p>";
			
			// counter looping
			$i = 0;
			
			// counter untuk jumlah hari minggu
			$sum = 0;
	 
				do
				{
			// mengenerate tanggal berikutnya
				$tanggal = date("d-m-Y", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1));
				
				// cek jika harinya minggu, maka counter $sum bertambah satu, lalu tampilkan tanggalnya
				if (date("w", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)) == 0 or date("w", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)) == 6)
				{
					$sum++;
					//echo $tanggal."<br>";
				}
				
				// increment untuk counter looping
				$i++;
				}
				while ($tanggal != $dat2);
				
				// looping di atas akan terus dilakukan selama tanggal yang digenerate tidak sama dengan $date2.
				
				// tampilkan jumlah hari Minggu
				//echo "<p>Jumlah hari minggu antara ".$date1." s/d ".$date2." adalah: ".$sum."</p>";

				$day=$selisih-$sum;
				$dayx=$day+1;

				echo $day+1;
								if ($dayx>=1 and $info['status']=='Review' and ($state=='Admin' or $state=='Originator') )
								{
									?>
										<a href="reminder.php?drf=<?php echo $info['no_drf'];?>&type=<?php echo $info['doc_type'];?>&nodoc=<?php echo $info['no_doc'];?>&title=<?php echo $info['title'];?>" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-envelope"></span>&nbsp; Reminder <strong><?php echo $info['reminder']?>x</strong></a>

									<?php
								}
		?>
	</td>
	<td>
	<?php if ($info['status']=='Review'){ ?>
	<span class="label label-info"><?php } ?>
	<?php if ($info['status']=='Pending'){ ?>
	<span class="label label-warning"><?php } ?>
	<?php if ($info['status']=='Approved'){ ?>
	<span class="label label-Success"><?php }?>
		<?php echo $info['status'];?>
		</span>
	</td>
	<td>
	<a href="detail.php?drf=<?php echo $info['no_drf'];?>&no_doc=<?php echo $info['no_doc'];?>" class="btn btn-xs btn-info" title="lihat detail"><span class="glyphicon glyphicon-search" ></span> </a>
	<a href="radf.php?drf=<?php echo $info['no_drf'];?>&section=<?php echo $info['section']?>" class="btn btn-xs btn-info" title="lihat RADF"><span class="glyphicon glyphicon-eye-open" ></span> </a>
	<a href="lihat_approver.php?drf=<?php echo $info['no_drf'];?>&nodoc=<?php echo $info['no_doc']?>&title=<?php echo $info['title']?>&type=<?php echo $info['doc_type']?>" class="btn btn-xs btn-info" title="lihat approver"><span class="glyphicon glyphicon-user" ></span> </a>	
	
	<?php if ($state=='Approver'){?>
	<a href="approve.php?drf=<?php echo $info['no_drf'];?>&device=<?echo $info['device']?>&no_doc=<?php echo $info['no_doc'];?>&title=<?php echo $info['title'] ?>&tipe=<?php echo $tipe; ?>" class="btn btn-xs btn-success" title="Approve Doc"><span class="glyphicon glyphicon-thumbs-up" ></span> </a>
	<a href="pending.php?drf=<?php echo $info['no_drf'];?>&no_doc=<?php echo $info['no_doc'];?>&type=<?php echo $info['doc_type'];?>" class="btn btn-xs btn-warning" title="Suspend Doc"><span class="glyphicon glyphicon-warning-sign" ></span>  </a>
	<?php } ?>

	<?php if ($state=='Admin' ||  ($state=="Originator" && $info['status']<>"Approved")){ ?>
	<a href="edit_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-primary" title="Edit Doc"><span class="glyphicon glyphicon-pencil" ></span> </a>
	<a href="del_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-danger" onClick="return confirm('Delete document <?php echo $info['no_doc']?>?')" title="Delete Doc"><span class="glyphicon glyphicon-remove" ></span> </a>
	
	<?php if ($info['status']=='Approved') { ?>
	<a data-toggle="modal" data-target="#myModal2" data-id="<?php echo $info['no_drf']?>" data-lama="<?php echo $info['file']?>" data-type="<?php echo $info['doc_type']?>" data-status="<?php echo $info['status']?>" data-rev="<?php echo $info['rev_to']?>" class="btn btn-xs btn-success sec-file" title="Secure Document">
	<span class="glyphicon glyphicon-play" ></span></a>
	<?php } } ?>

	<?php if ($info['status']=='Pending' and ($state=='Originator' or $state='Admin')){ ?>
	<button data-toggle="modal" data-target="#myModal" data-id="<?php echo $info['no_drf']?>" data-type="<?php echo $info['doc_type']?>" data-nodoc="<?php echo $info['no_doc']?>" data-title="<?php echo $info['title']?>" data-lama="<?php echo $info['file']?>"  class="btn btn-xs btn-warning upload-file">
	<span class="glyphicon glyphicon-upload"></span>
	Change Document</button>
	<?php }?>
	</td>
	
</tr>


<?php 
$j++;} 





?> 
</tbody>
</table>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Upload Document</h4>

            </div>
            <div class="modal-body">
                <form name="ganti_doc" method="POST" action="ganti_doc.php" enctype="multipart/form-data">

                   	<div class="modal-body">
						
						<input type="hidden" name="drf" id="drf" class="form-control" value=""/>
						<input type="hidden" name="nodoc" id="nodoc" class="form-control" value=""/>
						<input type="hidden" name="type" id="type" class="form-control" value=""/>
						<input type="hidden" name="title" id="title" class="form-control" value=""/>
						<input type="text" name="lama" id="lama" class="form-control" value=""/>
						File Document(.pdf/.xlsx):<input type="file" name="baru" class="form-control">
						File Master(.docx/.xlsx):<input type="file" name="masterbaru" class="form-control"><br />
					</div>
					
                    <div class="modal-footer"> <a class="btn btn-default" data-dismiss="modal">Cancel</a>

                        <input type="submit" name="upload" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
           
        </div>
    </div>
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
						<input type="hidden" name="rev" id="rev" class="form-control" value=""/>
						<input type="hidden" name="type" id="type" class="form-control" value=""/>
						<input type="hidden" name="status" id="status" class="form-control" value=""/>
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



<?php } ?>