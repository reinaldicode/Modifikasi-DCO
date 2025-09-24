<?php

include('header.php');
?>
<!DOCTYPE html>
<head>

 
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>

<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script>
</head>

z
<br />
<br />
<br />
<br />


 
  

<div id="profile">
<div class="alert alert-info" role="alert"><b id="welcome">Welcome : <i><?php echo $name; ?>, anda login sebagai <?php echo $state;?></i></b></div>
</div>

<div class="well well-lg">
<img src="images/stamped.jpg" class="img-responsive" alt="Responsive image"></img>
<div class="col-xs-2"></div>
<div class="col-xs-6">
<div class="alert alert-warning" role="alert" style="margin-top:-110px;margin-left:-70px;"><b id="welcome">Bapak / Ibu sekalian harap untuk memastikan kembali dokumen (prosedur, instruksi kerja, Formulir, atau check sheet) yang disimpan atau digunakan di area kerja bapak dan ibu telah memiliki stempel dari pengendali dokumen (document control). </b></div>
</div>
</div>



	
<script type="text/javascript">

 var chart1; // globally available
 $(document).ready(function() {
 chart1 = new Highcharts.Chart({
 chart: {
 renderTo: 'container',
 type: 'column'
 },
 title: {
 text: 'Grafik Jumlah Dokumen Aktif'
 },
 xAxis: {
 categories:
 			['Section'] 
 },
 yAxis: {
 title: {
 text: 'Dokumen '
 }
 },
 series:
 [

<?php
include 'koneksi.php';
$sql = "select DISTINCT section as section from docu order by section"; 
$query = mysqli_query($link, $sql) or die(mysqli_error());
while ($ret = mysqli_fetch_array($query)) {
 $section = $ret['section'];
 //$tanggal = $ret['tanggal'];
 $sql_jumlah = "select distinct count(section) As jumlah from docu where section='$section' and section<>'Manual' and status<>'Obsolate'";

 $query_jumlah = mysqli_query($link, $sql_jumlah) or die(mysqli_error());
 while ($data = mysqli_fetch_array($query_jumlah)) {
 $jumlah = $data['jumlah'];
 }
 ?>
 {
 name: '<?php 	
 	
 		echo $section;
 		?>',
 data: [<?php echo $jumlah; ?>]
 },
<?php } ?>
 ]
 });
 });
 </script>
	<div class="row">
	<div id='container' class="col-xs-12 well well-lg">
	</div>
</div>

<div class="">
	<div class="row">
		<div class="col-xs-12 well well-lg">
		<?php $tgl = date('d-m-Y'); ?>
		Latest Uploaded Document:
		<table>
		<tr>
		<?php 
		$query="SELECT no_doc, file,tgl_upload FROM docu WHERE doc_type = 'Procedure' AND STATUS = 'Review' ORDER BY no_drf desc LIMIT 3 ";
		$res=mysqli_query($link, $query)or die(mysqli_error());
	
	  ?>
		<td>Procedure</td><td>:</td><td>  <?php while ($data=mysqli_fetch_array($res)){ ?><a href="Procedure/<?php echo $data['file']; ?>" title="<?php echo $data['tgl_upload'] ?>"> <?php echo $data['no_doc'];?> </a>,  <?php }?></td>
		</tr>
		<tr>
		<?php 
		$query="SELECT no_doc, file,tgl_upload FROM docu WHERE doc_type = 'WI' AND STATUS = 'Review' ORDER BY no_drf desc LIMIT 3 ";
		$res=mysqli_query($link, $query)or die(mysqli_error());
	
	  ?>
		<td>Work Instruction</td><td>:</td><td> <?php while ($data=mysqli_fetch_array($res)){ ?> <a href="WI/<?php echo $data['file']; ?>" title="<?php echo $data['tgl_upload'] ?>"> <?php echo $data['no_doc'];?> </a>,  <?php }?></td>
		</tr>
		<tr>
		<?php 
		$query="SELECT no_doc, file,tgl_upload FROM docu WHERE doc_type = 'Form' AND STATUS = 'Review' ORDER BY no_drf desc LIMIT 3 ";
		$res=mysqli_query($link, $query)or die(mysqli_error());
	
	  ?>
		<td>Form</td><td>:</td><td><?php while ($data=mysqli_fetch_array($res)){ ?> <a href="Form/<?php echo $data['file']; ?>" title="<?php echo $data['tgl_upload'] ?>"> <?php echo $data['no_doc']; ?> </a>,  <?php }?></td>
		</tr>
		</table>
		</div>
	</div>
</div>




</body>
</html>