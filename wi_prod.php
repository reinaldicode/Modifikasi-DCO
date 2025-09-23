<?php
// wi_prod.php
include "wi_login.php"; // pastikan file ini mem-include koneksi $link & session
extract($_REQUEST);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING| E_PARSE|  E_DEPRECATED));
?>
<br />

<!-- jQuery -->
<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#wait_1').hide();
    $('#section').change(function(){
      $('#wait_1').show();
      $('#result_1').hide();
      $.get("func.php", {
        func: "section",
        drop_var: $('#section').val()
      }, function(response){
        $('#result_1').fadeOut();
        setTimeout(function(){ finishAjax1('result_1', escape(response)); }, 400);
      });
        return false;
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

function finishAjax1(id, response) {
  $('#wait_1').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();

  $('#wait_2').hide();
    $('#device').change(function(){
      $('#wait_2').show();
      $('#result_2').hide();
      $.get("func.php", {
        func: "device",
        drop_var2: $('#device').val()
      }, function(response){
        $('#result_2').fadeOut();
        setTimeout(function(){ finishAjax('result_2', escape(response)); }, 400);
      });
        return false;
    });
}

function finishAjax(id, response) {
  $('#wait_2').hide();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
}
</script>

<div class="row">
<div class="col-xs-4 well well-lg">
 <h2>Select Device & Process</h2>

<form action="" method="GET">
 <table >
    <?php
    $sect="select * from device";
    $sql_sect=mysqli_query($link, $sect);
    ?>
    <tr>
        <td>Section</td><td>:</td>
        <td>
            <?php include('func.php'); ?>
            <select name="section" id="section" class="form-control">
              <option value="" selected="selected" >Select Section</option>
              <?php getTierOne(); ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Device</td><td>:</td>
        <td>
            <span id="wait_1" style="display: none;"><img alt="Please Wait" src="images/wait.gif"/></span>
            <span id="result_1" style="display: none;"></span>
        </td>
    </tr>
    <tr>
        <td>Process</td><td>:</td>
        <td>
            <span id="wait_2" style="display: none;"><img alt="Please Wait" src="images/wait.gif"/></span>
            <span id="result_2" style="display: none;"></span>
        </td>
    </tr>
    <tr>
        <td>Status</td><td>:</td>
        <td>
            <select name="status" class="form-control">
                <option value="0"> --- Select Status --- </option>
                <option value="Secured" selected> Approved </option>
                <option value="Review"> Review </option>
                <option value="Pending"> Pending </option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Category</td><td>:</td>
        <td>
            <select name="cat" class="form-control">
                <option value="0"> --- Select Category --- </option>
                <option value="Internal" selected> Internal </option>
                <option value="External"> External </option>
            </select>
        </td>
    </tr>
    <tr>
        <td></td><td></td>
        <td><input type='hidden' name='by' value='no_drf'>
            <input type="submit" value="Show" name="submit" class="btn btn-info"></td>
    </tr>
</form>
 </table>
 </div>
</div>

<?php
if (isset($_GET['submit'])) {

    $dev = isset($_GET['device']) ? mysqli_real_escape_string($link, $_GET['device']) : '';
    $proc = isset($_GET['proc']) ? mysqli_real_escape_string($link, $_GET['proc']) : '';
    $status = isset($_GET['status']) ? mysqli_real_escape_string($link, $_GET['status']) : '';
    $cat = isset($_GET['cat']) ? mysqli_real_escape_string($link, $_GET['cat']) : '';
    $by = isset($_GET['by']) ? mysqli_real_escape_string($link, $_GET['by']) : 'no_drf';

    if ($dev=='General PC') {
        $sql="select * from docu where no_doc like 'O-W-EGPC%' and doc_type='WI' and status='Secured' order by $by ";
    } else {
        if ($proc=='-') {
            $sql="select * from docu where device='$dev' and doc_type='WI' and status='$status' and section='Production' and category='$cat'  order by $by ";
        } else {
            $sql="select * from docu where device='$dev' and process='$proc' and doc_type='WI' and status='$status' and section='Production' and category='$cat'   order by $by";
        }
    }

    $res = mysqli_query($link, $sql);
    if (!$res) {
        echo "<div class='alert alert-danger'>Query error: ". mysqli_error($link) ."</div>";
    }
?>
<br /><br />
<table class="table table-hover">
<h1> Work Instruction's List For Device: <strong><?php echo htmlspecialchars($dev);?></strong> , Process: <strong><?php echo htmlspecialchars($proc); ?></strong>, Category: <strong><?php echo htmlspecialchars($cat); ?></strong></h1>
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
    <td>Action</td>
    <td>Sosialisasi</td>
</tr>
</thead>
<?php
    $i = 1;
    while ($info = mysqli_fetch_assoc($res)) {
        // periksa apakah ada bukti sosialisasi (field di docu)
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

    <!-- Action -->
    <td>
        <a href="detail.php?drf=<?php echo urlencode($info['no_drf']);?>&no_doc=<?php echo urlencode($info['no_doc']);?>" class="btn btn-xs btn-info" title="lihat detail">
            <span class="glyphicon glyphicon-search"></span>
        </a>

        <a href="lihat_approver.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-warning" title="lihat approver">
            <span class="glyphicon glyphicon-user"></span>
        </a>

        <a href="radf.php?drf=<?php echo urlencode($info['no_drf']);?>&section=<?php echo urlencode($info['section']);?>" class="btn btn-xs btn-primary" title="lihat RADF">
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
        // tombol edit/delete tetap hanya untuk Admin / Originator yang buat doc
        if ( ($_SESSION['state'] ?? '') == 'Admin' || (($_SESSION['state'] ?? '') == "Originator" && ($info['user_id'] ?? '') == ($_SESSION['nrp'] ?? '')) ) {
        ?>
            <a href="edit_doc.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-primary" title="Edit Doc">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>
            <a href="del_doc.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-danger" onClick="return confirm('Delete document <?php echo addslashes($info['no_doc'])?>?')" title="Delete Doc">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
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
    } // end while
} // end if submit
?>
</table>

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
