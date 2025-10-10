<?php
include('header.php');
include('config_head.php');
include 'koneksi.php';

$type = isset($_GET['type']) ? trim($_GET['type']) : '';
if ($type === '') {
    echo "<h3>No document type specified</h3>";
    exit;
}

// sanitize untuk query
$type_sql = mysqli_real_escape_string($link, $type);

$sql = "SELECT * FROM docu WHERE doc_type = '$type_sql' ORDER BY no_doc LIMIT 200";
$res = mysqli_query($link, $sql);

if (!$res) {
    echo "<div class='alert alert-danger'>Query error: ". mysqli_error($link) ."</div>";
}
?>

<!-- jQuery -->
<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script>

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

     var tipe = $(this).data('tipe');
     $(".modal-body #tipe").val( tipe );
    });

    // tombol upload sosialisasi -> isi modal
    $(document).on('click', '.btn-upload-sos', function(e){
        e.preventDefault();
        var drf = $(this).data('drf');
        var nodoc = $(this).data('nodoc');
        $('#modal_upload_drf').val(drf);
        $('#modal_upload_nodoc').text(nodoc);
        $('#modalSosialisasi').modal('show');
    });

});
</script>

<br /><br />
<h1>Documents: <strong><?php echo htmlspecialchars($type); ?></strong></h1>
<br />

<table class="table table-hover">
<thead bgcolor="#00FFFF">
<tr>
    <td>No</td>
    <td>Date</td>
    <td>No. Document</td>
    <td>No Rev.</td>
    <td>drf</td>
    <td>Title</td>
    <td>Process</td>
    <td>Section</td>
    <td>Status</td>
    <td>Action</td>
    <td>Sosialisasi</td>
</tr>
</thead>
<?php
$i = 1;
if ($res) {
    while ($info = mysqli_fetch_assoc($res)) {
        // periksa apakah ada bukti sosialisasi
        $has_sos = !empty($info['sos_file']);
?>
<tbody>
<tr>
    <td><?php echo $i; ?></td>
    <td><?php echo htmlspecialchars($info['tgl_upload']);?></td>
    <td><?php echo htmlspecialchars($info['no_doc']);?></td>
    <td><?php echo htmlspecialchars($info['no_rev']);?></td>
    <td><?php echo htmlspecialchars($info['no_drf']);?></td>
    <td>
        <?php
        if ($info['no_drf'] > 12967) { $tempat = $info['doc_type']; } else { $tempat = 'document'; }
        ?>
        <a href="<?php echo htmlspecialchars($tempat . '/' . $info['file']); ?>">
            <?php echo htmlspecialchars($info['title']);?>
        </a>
    </td>
    <td><?php echo htmlspecialchars($info['process']);?></td>
    <td><?php echo htmlspecialchars($info['section']);?></td>
    <td><?php echo htmlspecialchars($info['status']);?></td>

    <!-- Action -->
    <td>
        <a href="detail.php?drf=<?php echo urlencode($info['no_drf']);?>&no_doc=<?php echo urlencode($info['no_doc']);?>" class="btn btn-xs btn-info" title="lihat detail">
            <span class="glyphicon glyphicon-search"></span>
        </a>

        <a href="lihat_approver.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-info" title="lihat approver">
            <span class="glyphicon glyphicon-user"></span>
        </a>

        <a href="radf.php?drf=<?php echo urlencode($info['no_drf']);?>&section=<?php echo urlencode($info['section']);?>" class="btn btn-xs btn-info" title="lihat RADF">
            <span class="glyphicon glyphicon-eye-open"></span>
        </a>

        <!-- Tombol Upload Bukti Sosialisasi -->
        <button type="button"
            class="btn btn-xs btn-success btn-upload-sos"
            data-drf="<?php echo htmlspecialchars($info['no_drf']);?>"
            data-nodoc="<?php echo htmlspecialchars($info['no_doc']);?>"
            title="Upload Bukti Sosialisasi">
            <span class="glyphicon glyphicon-upload"></span>
        </button>

        <?php
        // tombol edit/delete/secure tetap hanya untuk Admin / Originator yang buat doc
        if ( ($_SESSION['state'] ?? '') == 'Admin' || (($_SESSION['state'] ?? '') == "Originator" && ($info['user_id'] ?? '') == ($_SESSION['nrp'] ?? '')) ) {
        ?>
            <a href="edit_doc.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-primary" title="Edit Doc">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>
            <a href="del_doc.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-danger" onClick="return confirm('Delete document <?php echo addslashes($info['no_doc'])?>?')" title="Delete Doc">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
            
            <?php if ($info['status'] == 'Secured') { ?>
                <!-- Tombol Secure Document -->
                <a data-toggle="modal" data-target="#myModal2" 
                   data-id="<?php echo $info['no_drf']?>" 
                   data-lama="<?php echo $info['file']?>" 
                   data-tipe="<?php echo $info['category']?>" 
                   data-status="<?php echo $info['status']?>" 
                   class="btn btn-xs btn-success sec-file" 
                   title="Secure Document">
                    <span class="glyphicon glyphicon-play"></span>
                </a>
            <?php } ?>
        <?php } ?>
    </td>

    <!-- Kolom Sosialisasi: lihat detail, tunjukkan status -->
    <td>
        <?php if ($has_sos) { ?>
            <a href="lihat_sosialisasi.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-primary" title="Lihat Detail Sosialisasi">
                <span class="glyphicon glyphicon-file"></span>
            </a>
        <?php } else { ?>
            <a href="lihat_sosialisasi.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-default" title="Belum ada bukti sosialisasi">
                <span class="glyphicon glyphicon-file"></span>
            </a>
        <?php } ?>
    </td>
</tr>
</tbody>
<?php
        $i++;
    }
} else {
    echo "<tr><td colspan='11'>No documents found</td></tr>";
}
?>
</table>

<!-- Modal Update Document (Secure) -->
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
						<input type="hidden" name="status" id="status" class="form-control" value=""/>
						<input type="hidden" name="tipe" id="tipe" class="form-control" value=""/>
						<input type="file" name="baru" class="form-control">
					</div>
					
                    <div class="modal-footer"> 
                        <a class="btn btn-default" data-dismiss="modal">Cancel</a>
                        <input type="submit" name="upload" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Sosialisasi -->
<div class="modal fade" id="modalSosialisasi" tabindex="-1" role="dialog" aria-labelledby="modalSosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="upload_sosialisasi.php" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="modalSosLabel">Upload Bukti Sosialisasi</h4>
      </div>
      <div class="modal-body">
            <p>Upload bukti sosialisasi untuk No. Document: <strong id="modal_upload_nodoc"></strong></p>
            <input type="hidden" name="drf" id="modal_upload_drf" value="">
            <?php
            // token CSRF sederhana
            if (empty($_SESSION['csrf_token'])) {
                if (function_exists('random_bytes')) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                else $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label>File bukti (pdf / jpg / png)</label>
                <input type="file" name="sos_file" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Catatan / Keterangan</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="submit" name="upload_sosialisasi" class="btn btn-success">Upload</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- bootstrap.js -->
<script src="bootstrap/js/bootstrap.min.js"></script>