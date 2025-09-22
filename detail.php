<?php
extract($_REQUEST);
if ($log==1){include "index.php";$state="";$nrp="";} else
{include("header.php");}
include"koneksi.php";
$sql="select * from docu where no_drf='$drf'";
//echo $sql;
$res=mysqli_query($link, $sql);
while($info = mysqli_fetch_array($res)) 
{ 
	//$info['no_doc]=$nomor;
	

//echo $judul;

?>

<title> Detail Document : <?php echo $info['title'];?></title>
<br />
<br />
<br />
<head> <h1>Detail Document : <?php echo $info['title'];?></h1> </head>


<body>
<table border="0" cellpadding="0" cellspacing="0" class="table table-hover">
<tr>
	<td> RADF&nbsp;&nbsp;</td><td>:</td><td>&nbsp;&nbsp;<?php echo $info['no_drf'];?></td>
</tr>
<tr>
	<td> Title&nbsp;&nbsp;</td><td>:</td><td>&nbsp;&nbsp;<b><?php echo $info['title'];?></b></td>
</tr>
<tr>
	<td> Date&nbsp;&nbsp;</td><td>:</td><td>&nbsp;&nbsp;<?php echo $info['tgl_upload'];?></td>
</tr>

<tr>
	<td> Doc.Number&nbsp;&nbsp;</td><td>:</td><td>&nbsp;&nbsp;<?php echo $info['no_doc'];?></td>
</tr>
<?php if ($state=='Admin' or ($state="Originator" and $nrp==$info['user_id'])){?>
<tr>
	<td> File Master&nbsp;&nbsp;</td><td>:</td><td>&nbsp;&nbsp;<A href="master/<?php echo $info['file_asli'];?>" class="btn btn-xs btn-primary" title="Download File Master"><span class="glyphicon glyphicon-cloud-download"></span>&nbsp;Download</A></td>
</tr>
<?php }?>
<tr>
	<td> Description&nbsp;&nbsp;</td><td>:</td><td>&nbsp;&nbsp;<?php echo $info['descript'];?></td>
</tr>
<tr>
	<td> Related/Addopted Doc.&nbsp;&nbsp;</td><td>:</td>
	<td>
	
	&nbsp;&nbsp;<?php 
	$sql2="SELECT rel_doc.no_doc,do.file,do.no_rev FROM docu DO,rel_doc WHERE rel_doc.no_drf='$info[no_drf]' AND rel_doc.no_doc=do.no_doc AND no_rev=(SELECT max(no_rev) FROM docu dm WHERE dm.no_doc='$no_doc')";
	//echo $sql2;
	$res2=mysqli_query($link, $sql2);
	while($info2 = mysqli_fetch_array($res2)) 
	{ ?>
		
			
			<a href="document/<?php echo "$info2[file]"; ?>">
			<?php echo $info2['no_doc']; echo ", "; ?>
			</a>
			
		
	<?php }



	?>
	</td>
</tr>
<tr>
	<td> Review to &nbsp;&nbsp;</td><td>:</td><td>&nbsp;&nbsp;<?php echo $info['rev_to'];?></td>
</tr>
<tr>
	<td> Revision Cause &nbsp;&nbsp;</td><td>:</td><td>&nbsp;&nbsp;<?php echo $info['history'];?></td>
</tr>
<tr>
	<td>Doc. History&nbsp;&nbsp;</td><td>:</td>
	<td>
	
	&nbsp;&nbsp;<?php 
	$sql3="Select no_rev,file,history from docu where no_doc='$info[no_doc]' and no_rev<> '$info[no_rev]' order by no_rev";
	//echo $sql2;
	$res3=mysqli_query($link, $sql3);
	while($info3 = mysqli_fetch_array($res3)) 
	{?>
		
			
			<a href="document/<?php echo "$info3[file]"; ?>"  title="<?php echo $info3['history']?>" >
			<?php echo $info3['no_rev'];  ?>
			</a>,
			
		
	<?php }



	?>
	</td>
</tr>
<?php } ?>