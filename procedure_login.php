<?php
// procedure_login.php (dimodifikasi: tambahkan tombol Upload Sosialisasi + modal)
// Pastikan header.php membuka <html>/<head> seperti di project Anda.
include('header.php');
?>

<!-- tambahan CSS/JS -->
<link href="bootstrap/css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet">
<link href="bootstrap/css/bootstrap-responsive.min.css" media="all" type="text/css" rel="stylesheet">
<link href="bootstrap/css/facebook.css" media="all" type="text/css" rel="stylesheet">

<link rel="stylesheet" href="bootstrap/css/datepicker.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-select.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">

<script src="bootstrap/js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="bootstrap/js/bootstrap-dropdown.js"></script>
<script src="bootstrap/js/facebook.js"></script>
<script src="bootstrap/js/bootstrap-datepicker.js"></script>

<style>
/* sedikit style tambahan */
.btn-upload-sos { margin-left:6px; }
.modal .form-control { margin-bottom:10px; }
</style>

</head>

<?php
// koneksi
include 'koneksi.php';
require_once('Connections/config.php');

// ambil session state/ nrp jika ada
$state = $_SESSION['state'] ?? '';
$nrp   = $_SESSION['nrp'] ?? '';
?>

<script type="text/javascript">
$(document).ready(function () {

    // event ketika tombol upload sosialisasi diklik -> isi modal & tampilkan
    $(document).on('click', '.btn-upload-sos', function(e){
        e.preventDefault();
        var drf = $(this).data('drf') || '';
        var nodoc = $(this).data('nodoc') || '';
        $('#modal_upload_drf').val(drf);
        $('#modal_upload_nodoc').text(nodoc);
        $('#modalSosialisasi').modal('show');
    });

    // optional: saat modal ditutup, reset form
    $('#modalSosialisasi').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});
</script>

<br /><br /><br />

<h3>Procedure List</h3>
<form action="" method="GET" class="form-inline" style="margin-bottom:18px;">
  <div class="form-group">
    <?php
      $sect="select * from section order by sect_name";
      $sql_sect=mysqli_query($link, $sect);
    ?>
    <select name="section" class="form-control">
      <option value="0"> --- Select Section --- </option>
      <?php while($data_sec = mysqli_fetch_array($sql_sect)) { ?>
        <option value="<?php echo htmlspecialchars($data_sec['id_section']); ?>">
          <?php echo htmlspecialchars($data_sec['sect_name']); ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group" style="margin-left:10px;">
    <select name="status" class="form-control">
      <option value="0"> --- Select Status --- </option>
      <option value="Secured" selected> Approved </option>
      <option value="Review"> Review </option>
      <option value="Pending"> Pending </option>
    </select>
  </div>

  <input type='hidden' name='by' value='no_drf'>
  <button type="submit" name="submit" value="Show" class="btn btn-primary" style="margin-left:10px;">Show</button>
</form>

<?php
if (isset($_GET['submit'])){

  $section = isset($_GET['section']) ? mysqli_real_escape_string($link, $_GET['section']) : '0';
  $status  = isset($_GET['status'])  ? mysqli_real_escape_string($link, $_GET['status'])  : 'Secured';
  $by      = isset($_GET['by'])      ? mysqli_real_escape_string($link, $_GET['by'])      : 'no_drf';

  if ($section == '0') {
    $sql = "SELECT * FROM docu WHERE doc_type='Procedure' AND status='Secured' ORDER BY $by";
  } else {
    $sql = "SELECT * FROM docu WHERE section='$section' AND doc_type='Procedure' AND status='$status' ORDER BY $by";
  }

  $res = mysqli_query($link, $sql);
  if (!$res) {
    echo '<div class="alert alert-danger">Query error: ' . mysqli_error($link) . '</div>';
  } else {
?>
  <br />
  <h4>Procedure List For Section: <strong><?php echo htmlspecialchars($section);?></strong> , Status: <strong><?php echo htmlspecialchars($status); ?></strong></h4>

  <div class="table-responsive">
  <table class="table table-hover">
    <thead style="background:#00FFFF;">
      <tr>
        <th>No</th>
        <th>Date</th>
        <th><a href="<?php echo $_SERVER['PHP_SELF'];?>?section=<?php echo urlencode($section); ?>&by=no_doc&status=<?php echo urlencode($status); ?>&submit=Show">No. Document</a></th>
        <th>No Rev.</th>
        <th><a href="<?php echo $_SERVER['PHP_SELF'];?>?section=<?php echo urlencode($section); ?>&by=no_drf&status=<?php echo urlencode($status); ?>&submit=Show">drf</a></th>
        <th><a href="<?php echo $_SERVER['PHP_SELF'];?>?section=<?php echo urlencode($section); ?>&by=title&status=<?php echo urlencode($status); ?>&submit=Show">Title</a></th>
        <th>Process</th>
        <th>Section</th>
        <th>Action</th>
        <th>Sosialisasi</th>
      </tr>
    </thead>
    <tbody>
<?php
    $i=1;
    while($info = mysqli_fetch_assoc($res)) {
      // apakah ada file sosialisasi? asumsikan kolom 'sos_file' di table docu menyimpan nama file (ubah sesuai real schema)
      $has_sos = !empty($info['sos_file']);
      $tempat = (isset($info['no_drf']) && intval($info['no_drf'])>12967) ? $info['doc_type'] : 'document';
?>
      <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo htmlspecialchars($info['tgl_upload']); ?></td>
        <td><?php echo htmlspecialchars($info['no_doc']); ?></td>
        <td><?php echo htmlspecialchars($info['no_rev']); ?></td>
        <td><?php echo htmlspecialchars($info['no_drf']); ?></td>
        <td>
          <a href="<?php echo htmlspecialchars($tempat . '/' . $info['file']); ?>">
            <?php echo htmlspecialchars($info['title']); ?>
          </a>
        </td>
        <td><?php echo htmlspecialchars($info['process']); ?></td>
        <td><?php echo htmlspecialchars($info['section']); ?></td>
        <td>
          <a href="detail.php?drf=<?php echo urlencode($info['no_drf']); ?>&no_doc=<?php echo urlencode($info['no_doc']); ?>" class="btn btn-xs btn-info" title="lihat detail">
            <span class="glyphicon glyphicon-search"></span>
          </a>

          <a href="lihat_approver.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-info" title="lihat approver">
            <span class="glyphicon glyphicon-user"></span>
          </a>

          <a href="radf.php?drf=<?php echo urlencode($info['no_drf']); ?>&section=<?php echo urlencode($info['section']);?>" class="btn btn-xs btn-info" title="lihat RADF">
            <span class="glyphicon glyphicon-eye-open"></span>
          </a>

          <!-- tombol upload sosialisasi (tersedia untuk semua user; jika perlu batasi, tambahkan kondisi) -->
          <button type="button"
              class="btn btn-xs btn-success btn-upload-sos"
              data-drf="<?php echo htmlspecialchars($info['no_drf']);?>"
              data-nodoc="<?php echo htmlspecialchars($info['no_doc']);?>"
              title="Upload Bukti Sosialisasi">
            <span class="glyphicon glyphicon-upload"></span>
          </button>

          <?php
          // tombol edit/delete hanya untuk Admin / Originator yang membuat doc (sesuai original)
          if ($state=='Admin' or ($state=="Originator" and $info['user_id']==$nrp)) {
          ?>
            <a href="edit_doc.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-primary" title="Edit Doc"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="del_doc.php?drf=<?php echo urlencode($info['no_drf']);?>" class="btn btn-xs btn-danger" onClick="return confirm('Delete document <?php echo addslashes($info['no_doc']); ?>?')" title="Delete Doc"><span class="glyphicon glyphicon-remove"></span></a>
          <?php } ?>
        </td>

        <td>
          <?php if ($has_sos) { ?>
            <a href="lihat_sosialisasi.php?drf=<?php echo urlencode($info['no_drf']); ?>" class="btn btn-xs btn-primary" title="Lihat Bukti Sosialisasi">
              <span class="glyphicon glyphicon-file"></span>
            </a>
          <?php } else { ?>
            <a href="lihat_sosialisasi.php?drf=<?php echo urlencode($info['no_drf']); ?>" class="btn btn-xs btn-default" title="Belum ada bukti sosialisasi">
              <span class="glyphicon glyphicon-file"></span>
            </a>
          <?php } ?>
        </td>
      </tr>
<?php
      $i++;
    } // end while
?>
    </tbody>
  </table>
  </div>

<?php
  mysqli_free_result($res);
} // end else query ok
} // end if submit
?>

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
            <input type="file" name="sos_file" class="form-control" accept=".pdf,image/*" required>
          </div>
          <div class="form-group">
            <label>Catatan / Keterangan</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" data-dismiss="modal" type="button">Batal</button>
          <button type="submit" name="upload_sosialisasi" class="btn btn-success">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- pastikan bootstrap.js ada di akhir -->
<script src="bootstrap/js/bootstrap.min.js"></script>

<?php
// jangan menutup </body></html> jika footer/header project menutupnya
// jika perlu, tambahkan footer include di sini:
// include('footer.php');
?>
