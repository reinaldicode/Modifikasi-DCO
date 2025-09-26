<?php
// search.php (dimodifikasi: hapus draft filter, pindah Month/Year ke atas, samakan warna results)
// Siap-tempel — pastikan koneksi ($link) / header.php sesuai environment Anda.

include('header.php');    // header / session bila perlu
include 'koneksi.php';
require_once('Connections/config.php');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Search Dokumen</title>

  <!-- CSS / JS (sesuaikan path jika perlu) -->
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
  <script src="jquery-ui-1.10.3/jquery-1.9.1.js"></script>
  <script src="bootstrap/js/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
  <script src="jquery-ui-1.10.3/ui/jquery.ui.widget.js"></script>
  <script src="jquery-ui-1.10.3/ui/jquery.ui.position.js"></script>
  <script src="jquery-ui-1.10.3/ui/jquery.ui.menu.js"></script>
  <script src="jquery-ui-1.10.3/ui/jquery.ui.autocomplete.js"></script>

  <style>
    /* ===== BACKGROUND DARI index.php ===== */
    body {
        background-image: url("images/white.jpeg");
        background-color: #cccccc;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    
    /* ===== UI ===== */
    .search-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 6px 20px rgba(31,45,61,0.08);
      margin-bottom: 18px;
    }
    .controls { margin-top: 10px; }
    .btn-perpage .btn { margin-right:6px; }
    .badge-info-custom {
      background: linear-gradient(90deg,#0d6efd,#6610f2);
      color: #fff;
      border-radius: 999px;
      padding: 6px 10px;
      font-weight:600;
    }
    .muted-small { font-size: 12px; color:#6c757d; }
    .perpage-active { box-shadow: inset 0 -3px 0 rgba(0,0,0,0.08); }
    .pagination > li > a, .pagination > li > span { border-radius:6px; }
    
    /* TAMBAHAN: CSS untuk search-input yang hilang */
    .search-input .input-group-addon { background: #fff; border-right:0; }
    .search-input .form-control { border-left:0; }

    /* ===== Table ===== */
    .table-modern {
      width: 100%;
      background: transparent; /* transparan */
    }
    .table-modern thead {
      background: #f8f9fa;
      position: sticky;
      top: 0;
      z-index: 2;
    }
    .table-modern tbody tr:hover { background: rgba(0,0,0,0.03); }

    @media(max-width:767px){
      .search-card { padding:12px; }
      .controls .btn { margin-bottom:8px; }
    }

    .auto-complete{ float:left; z-index:9999; }
  </style>
</head>
<body>
<br/><br/>

<div class="container">
  <div class="search-card">
    <h4 style="margin-top:0">Search Dokumen <small class="muted-small">(No Doc, Title, Employee ID, Type, Month, Year, DRF)</small></h4>

    <?php
    // Ambil daftar doc_type untuk dropdown (jika dibutuhkan)
    $types = [];
    $qtypes = "SELECT DISTINCT doc_type FROM docu WHERE doc_type IS NOT NULL AND doc_type <> '' ORDER BY doc_type";
    $rtypes = mysqli_query($link, $qtypes);
    if ($rtypes) {
      while ($t = mysqli_fetch_assoc($rtypes)) {
        $types[] = $t['doc_type'];
      }
      mysqli_free_result($rtypes);
    }

    $currentPerPage = isset($_GET['perPage']) ? $_GET['perPage'] : '20';

    // SORT parameter: 'oldest' (terlama ke terbaru) atau 'newest' (terbaru ke terlama)
    // Default: 'oldest' sesuai permintaan Anda (terlama muncul di atas)
    $currentSort = isset($_GET['sort']) ? $_GET['sort'] : 'oldest';
    if (!in_array($currentSort, ['oldest','newest'])) $currentSort = 'oldest';
    ?>

    <!-- FORM -->
    <form id="searchForm" method="GET" action="" class="form-horizontal">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group search-input">
            <label class="control-label">No Document</label>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-folder-open"></span></span>
              <input type="text" name="doc_no" class="form-control" value="<?php echo isset($_GET['doc_no']) ? htmlspecialchars($_GET['doc_no']) : ''; ?>" placeholder="e.g. DC-001">
            </div>
          </div>

          <div class="form-group search-input">
            <label class="control-label">Title</label>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-book"></span></span>
              <input type="text" name="title" class="form-control" value="<?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>" placeholder="Masukkan kata kunci title">
            </div>
          </div>

          <!-- DRF search (tetap di sini di kolom kiri) -->
          <div class="form-group search-input">
            <label class="control-label">DRF (No. DRF)</label>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span>
              <input type="text" name="drf" class="form-control" value="<?php echo isset($_GET['drf']) ? htmlspecialchars($_GET['drf']) : ''; ?>" placeholder="Masukkan no DRF (mis. 17246)">
            </div>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group search-input">
            <label class="control-label">Employee ID</label>
            <div class="input-group">
              <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
              <input type="text" name="empid" class="form-control" value="<?php echo isset($_GET['empid']) ? htmlspecialchars($_GET['empid']) : ''; ?>" placeholder="Employee ID">
            </div>
          </div>

          <!-- Pindahkan Month & Year ke atas (di sini) -->
          <div class="row">
            <div class="col-sm-6 form-group">
              <label class="control-label">Month</label>
              <select name="bulan" id="bulan" class="form-control">
                <option value="00" <?php if(!isset($_GET['bulan']) || $_GET['bulan']=='00') echo 'selected'; ?>>Select Month</option>
                <?php
                $months = [
                  '01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June',
                  '07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'
                ];
                foreach($months as $k=>$v){
                  $sel = (isset($_GET['bulan']) && $_GET['bulan']==$k) ? 'selected' : '';
                  echo "<option value=\"$k\" $sel>$v</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-sm-6 form-group">
              <label class="control-label">Year</label>
              <select name="tahun" id="tahun" class="form-control">
                <option value="00" <?php if(!isset($_GET['tahun']) || $_GET['tahun']=='00') echo 'selected'; ?>>Select Year</option>
                <?php for ($year = 2015; $year <= date('Y') ; $year++): 
                  $sel = (isset($_GET['tahun']) && $_GET['tahun']==$year) ? 'selected' : '';
                ?>
                  <option value="<?php echo $year;?>" <?php echo $sel;?>><?php echo $year;?></option>
                <?php endfor; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label">Type Document</label>
            <select name="doc_type" class="form-control">
              <option value="">-- Semua Type --</option>
              <?php foreach ($types as $dt): ?>
                <option value="<?php echo htmlspecialchars($dt); ?>" <?php if(isset($_GET['doc_type']) && $_GET['doc_type']==$dt) echo 'selected'; ?>>
                  <?php echo htmlspecialchars($dt); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>
      </div>

      <div class="controls clearfix">
        <input type="hidden" id="perPageInput" name="perPage" value="<?php echo htmlspecialchars($currentPerPage); ?>">
        <!-- Sorting hidden input -->
        <input type="hidden" id="sortInput" name="sort" value="<?php echo htmlspecialchars($currentSort); ?>">
        <button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
        <button type="button" id="resetBtn" class="btn btn-default"><span class="glyphicon glyphicon-refresh"></span> Reset</button>

        <!-- per-page buttons -->
        <div class="btn-perpage pull-right" style="margin-left:10px;">
          <div class="btn-group" role="group" aria-label="Per page">
            <?php
              $perOptions = ['10','20','50','all'];
              foreach ($perOptions as $opt) {
                $label = ($opt === 'all') ? 'All' : $opt;
                $active = ($currentPerPage === $opt) ? ' perpage-active' : '';
                echo '<button type="button" data-per="'.$opt.'" class="btn btn-sm btn-default'.$active.'">'.$label.'</button>';
              }
            ?>
          </div>
        </div>

        <!-- Sort control -->
        <div class="pull-right" style="margin-right:10px;">
          <label style="display:inline-block;margin-right:8px;" class="muted-small">Sort</label>
          <select id="sortSelect" class="form-control input-sm" style="display:inline-block;width:auto;">
            <option value="oldest" <?php if($currentSort=='oldest') echo 'selected'; ?>>Oldest first (terlama → terbaru)</option>
            <option value="newest" <?php if($currentSort=='newest') echo 'selected'; ?>>Newest first (terbaru → terlama)</option>
          </select>
        </div>

      </div>
    </form>
  </div>
</div>

<?php
// Processing search jika submit
if (isset($_GET['submit']) || isset($_GET['perPage']) || isset($_GET['page']) || isset($_GET['sort']) || isset($_GET['drf'])) {
  // sanitize
  $doc_no = isset($_GET['doc_no']) ? mysqli_real_escape_string($link, trim($_GET['doc_no'])) : '';
  $title  = isset($_GET['title']) ? mysqli_real_escape_string($link, trim($_GET['title'])) : '';
  $empid  = isset($_GET['empid']) ? mysqli_real_escape_string($link, trim($_GET['empid'])) : '';
  $doc_type = isset($_GET['doc_type']) ? mysqli_real_escape_string($link, trim($_GET['doc_type'])) : '';
  $bulan  = isset($_GET['bulan']) ? mysqli_real_escape_string($link, trim($_GET['bulan'])) : '00';
  $tahun  = isset($_GET['tahun']) ? mysqli_real_escape_string($link, trim($_GET['tahun'])) : '00';
  $perPageRaw = $_GET['perPage'] ?? '20';
  $sort = isset($_GET['sort']) ? $_GET['sort'] : 'oldest';
  if (!in_array($sort, ['oldest','newest'])) $sort = 'oldest';

  // DRF search
  $drf_search = isset($_GET['drf']) ? mysqli_real_escape_string($link, trim($_GET['drf'])) : '';

  $whereParts = [];
  if ($doc_no !== '') {
    $whereParts[] = "(no_doc LIKE '%$doc_no%')";
  }
  if ($title !== '') {
    $whereParts[] = "(title LIKE '%$title%')";
  }
  if ($empid !== '') {
    $whereParts[] = "(user_id = '$empid')";
  }
  if ($doc_type !== '') {
    $whereParts[] = "(doc_type = '$doc_type')";
  }
  if ($drf_search !== '') {
    // search by no_drf (partial match)
    $whereParts[] = "(no_drf LIKE '%$drf_search%')";
  }
  if ($bulan !== '00' && $tahun !== '00') {
    $whereParts[] = "(MID(tgl_upload,4,2) = '$bulan' AND RIGHT(tgl_upload,4) = '$tahun')";
  }

  $where = (count($whereParts) > 0) ? ' WHERE ' . implode(' AND ', $whereParts) : '';

  $isAll = ($perPageRaw === 'all');
  $perPage = $isAll ? 0 : (int)$perPageRaw;
  if (!$isAll && $perPage <= 0) $perPage = 20;
  $page = max(1, (int)($_GET['page'] ?? 1));
  $offset = ($page - 1) * $perPage;

  // count
  $countSql = "SELECT COUNT(*) AS total FROM docu $where";
  $countRes = mysqli_query($link, $countSql);
  $totalRows = 0;
  if ($countRes) {
    $totalRows = (int)mysqli_fetch_assoc($countRes)['total'];
    mysqli_free_result($countRes);
  }

  // main query
  // Use STR_TO_DATE to sort by tgl_upload (format dd-mm-yyyy or dd/mm/yyyy)
  // Replace slashes with hyphen first to make parsing consistent
  $orderDir = ($sort === 'oldest') ? 'ASC' : 'DESC';
  $sql = "SELECT * FROM docu $where ORDER BY STR_TO_DATE(REPLACE(tgl_upload,'/','-'), '%d-%m-%Y') $orderDir";
  if (!$isAll) $sql .= " LIMIT $offset,$perPage";
  $res = mysqli_query($link, $sql);

  function build_page_url($page_number) {
    $params = $_GET;
    $params['page'] = $page_number;
    if (!isset($params['perPage'])) $params['perPage'] = '20';
    if (!isset($params['sort'])) $params['sort'] = 'oldest';
    return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($params));
  }

  if ($totalRows > 0) {
    $startRow = $isAll ? 1 : $offset + 1;
    $endRow   = $isAll ? $totalRows : min($offset + $perPage, $totalRows);
    echo '<div class="container">';
    // gunakan same card style seperti search
    echo '<div class="search-card">';
    echo '<span class="badge-info-custom">Results</span> Menampilkan <strong>'.$startRow.'</strong> - <strong>'.$endRow.'</strong> dari <strong>'.$totalRows.'</strong>';
    echo ' &nbsp; <small class="muted-small">Sort: '.htmlspecialchars(($sort==='oldest'?'Oldest first':'Newest first')).'</small>';
    echo '</div>';
    echo '</div>';
  } else {
    echo '<div class="container"><div class="search-card"><div class="alert alert-warning" style="margin:0;border:none;background:transparent;">Tidak ada hasil untuk filter tersebut.</div></div></div>';
  }
?>

  <?php if ($totalRows > 0): ?>
  <div id="resultsContainer" style="padding:0 15px;">
    <div class="table-responsive">
      <table class="table table-hover table-modern">
        <thead>
          <tr>
            <th>No</th>
            <th>Date</th>
            <th>No Document</th>
            <th>No Rev.</th>
            <th>No. Drf</th>
            <th>Title</th>
            <th>Status</th>
            <th>Type</th>
            <th>Section</th>
            <th>Device</th>
            <th>Process</th>
            <th>Action</th>
            <th>Sosialisasi</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $i = $isAll ? 1 : $offset + 1;
            while ($row = mysqli_fetch_assoc($res)) {
              // periksa apakah ada bukti sosialisasi
              $has_sos = !empty($row['sos_file']);
              
              echo '<tr>';
              echo '<td>'. $i .'</td>';
              echo '<td>'. htmlspecialchars($row['tgl_upload']) .'</td>';
              echo '<td>'. htmlspecialchars($row['no_doc']) .'</td>';
              echo '<td>'. htmlspecialchars($row['no_rev']) .'</td>';
              echo '<td>'. htmlspecialchars($row['no_drf']) .'</td>';

              // link file path as original: if no_drf > 12967 then tempat = doc_type else 'document' (kept)
              $tempat = (isset($row['no_drf']) && intval($row['no_drf'])>12967) ? $row['doc_type'] : 'document';
              $filePath = htmlspecialchars($tempat . '/' . $row['file']);

              echo '<td><a href="'. $filePath .'">'. htmlspecialchars($row['title']) .'</a></td>';
              echo '<td>'. htmlspecialchars($row['status']) .'</td>';
              echo '<td>'. htmlspecialchars($row['doc_type']) .'</td>';
              echo '<td>'. htmlspecialchars($row['section']) .'</td>';
              echo '<td>'. htmlspecialchars($row['device']) .'</td>';
              echo '<td>'. htmlspecialchars($row['process']) .'</td>';
              echo '<td>
                    <a class="btn btn-xs btn-info" title="Lihat Detail" href="detail.php?drf='.urlencode($row['no_drf']).'&no_doc='.urlencode($row['no_doc']).'">
                      <span class="glyphicon glyphicon-search"></span>
                    </a>
                    <a class="btn btn-xs btn-primary" title="Lihat RADF" href="radf.php?drf='.urlencode($row['no_drf']).'">
                      <span class="glyphicon glyphicon-eye-open"></span>
                    </a>
                    
                    <!-- Tombol Upload Bukti Sosialisasi -->
                    <button type="button"
                        class="btn btn-xs btn-success btn-upload-sos"
                        data-drf="'.htmlspecialchars($row['no_drf']).'"
                        data-nodoc="'.htmlspecialchars($row['no_doc']).'"
                        title="Upload Bukti Sosialisasi">
                        <span class="glyphicon glyphicon-upload"></span>
                    </button>
                    </td>';
              
              // Kolom Sosialisasi
              echo '<td>';
              if ($has_sos) {
                  echo '<a href="lihat_sosialisasi.php?drf='.urlencode($row['no_drf']).'" class="btn btn-xs btn-primary" title="Lihat Detail Sosialisasi">';
                  echo '<span class="glyphicon glyphicon-file"></span>';
                  echo '</a>';
              } else {
                  echo '<a href="lihat_sosialisasi.php?drf='.urlencode($row['no_drf']).'" class="btn btn-xs btn-default" title="Belum ada bukti sosialisasi">';
                  echo '<span class="glyphicon glyphicon-file"></span>';
                  echo '</a>';
              }
              echo '</td>';
              
              echo '</tr>';
              $i++;
            }
            mysqli_free_result($res);
          ?>
        </tbody>
      </table>
    </div>

    <?php
      if (!$isAll) {
        $totalPages = ceil($totalRows / $perPage);
        if ($totalPages > 1) {
          echo '<nav><ul class="pagination justify-content-center">';
          if ($page > 1) {
            echo '<li><a href="'.build_page_url($page-1).'">Prev</a></li>';
          }
          $range = 2;
          for ($p = max(1, $page - $range); $p <= min($totalPages, $page + $range); $p++) {
            $active = ($p==$page)?' class="active"':'';
            echo '<li'.$active.'><a href="'.build_page_url($p).'">'.$p.'</a></li>';
          }
          if ($page < $totalPages) {
            echo '<li><a href="'.build_page_url($page+1).'">Next</a></li>';
          }
          echo '</ul></nav>';
        }
      }
    ?>
  </div>
  <?php endif; ?>

<?php } // end processing ?>

<!-- Modal definitions (dipertahankan dari file lama) -->
<!-- myModal2, myModalex, myModal5, myModal6 -->
<?php
// Jika Anda menggunakan modal-button dari row action sebelumnya, berikut template modals (disalin & disederhanakan)
?>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Secure Document</h4>
      </div>
      <div class="modal-body">
        <form name="secure_doc" method="POST" action="process.php" enctype="multipart/form-data">
          <input type="hidden" name="drf" id="drf" class="form-control" value=""/>
          <input type="hidden" name="nodoc" id="nodoc" class="form-control" value=""/>
          <input type="hidden" name="rev" id="rev" class="form-control" value=""/>
          <input type="hidden" name="status" id="status" class="form-control" value=""/>
          <input type="hidden" name="type" id="type" class="form-control" value=""/>
          <div class="form-group"><input type="file" name="baru" class="form-control"></div>
          <div class="modal-footer">
            <a class="btn btn-default" data-dismiss="modal">Cancel</a>
            <input type="submit" name="upload" value="Update" class="btn btn-primary" onclick="return confirm('Are you sure?');">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- keep other modals if needed, simplified versions -->
<div class="modal fade" id="myModalex" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Update Document External</h4></div>
    <div class="modal-body">
      <form method="POST" action="process_ex.php" enctype="multipart/form-data">
        <input type="text" name="drf" id="drf_ex" class="form-control" value=""/>
        <input type="file" name="baru" class="form-control">
        <div class="modal-footer"><a class="btn btn-default" data-dismiss="modal">Cancel</a><input type="submit" name="upload" value="Update" class="btn btn-primary"></div>
      </form>
    </div>
  </div></div>
</div>

<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Update Document Master File</h4></div>
    <div class="modal-body">
      <form method="POST" action="process_mas.php" enctype="multipart/form-data">
        <input type="hidden" name="drf" id="drf_mas" class="form-control" value=""/>
        <input type="text" name="nodoc" id="nodoc_mas" class="form-control" value=""/>
        <input type="file" name="baru" class="form-control">
        <div class="modal-footer"><a class="btn btn-default" data-dismiss="modal">Cancel</a><input type="submit" name="upload" value="Update" class="btn btn-primary"></div>
      </form>
    </div>
  </div></div>
</div>

<div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Update Documents</h4></div>
    <div class="modal-body">
      <form method="POST" action="ganti_doc2.php" enctype="multipart/form-data">
        <input type="hidden" name="drf" id="drf6" class="form-control" value=""/>
        <input type="hidden" name="nodoc" id="nodoc6" class="form-control" value=""/>
        <input type="hidden" name="rev" id="rev6" class="form-control" value=""/>
        <input type="hidden" name="status" id="status6" class="form-control" value=""/>
        <input type="file" name="baru" class="form-control">
        <div class="modal-footer"><a class="btn btn-default" data-dismiss="modal">Cancel</a><input type="submit" name="upload" value="Update" class="btn btn-primary"></div>
      </form>
    </div>
  </div></div>
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

<script>
(function(){
  const form = document.getElementById('searchForm');
  const resetBtn = document.getElementById('resetBtn');
  const results = document.getElementById('resultsContainer');
  const perPageInput = document.getElementById('perPageInput');
  const sortInput = document.getElementById('sortInput');
  const sortSelect = document.getElementById('sortSelect');

  // Set initial sortInput from select
  if (sortSelect && sortInput) {
    sortInput.value = sortSelect.value;
    sortSelect.addEventListener('change', function(){
      sortInput.value = this.value;
      // submit otomatis saat ganti sort (opsional)
      form.submit();
    });
  }

  // Event untuk tombol perPage
  document.querySelectorAll('.btn-perpage button').forEach(function(b){
    b.addEventListener('click', function(){
      const per = this.getAttribute('data-per');
      if (per === 'all') {
        if (!confirm('Menampilkan semua hasil dapat berat. Lanjutkan?')) return;
      }
      if (perPageInput) perPageInput.value = per;
      document.querySelectorAll('.btn-perpage button').forEach(x => x.classList.remove('perpage-active'));
      this.classList.add('perpage-active');
      form.submit();
    });
  });

  // Tombol upload sosialisasi -> isi modal
  document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-upload-sos')) {
      e.preventDefault();
      const btn = e.target.closest('.btn-upload-sos');
      const drf = btn.getAttribute('data-drf');
      const nodoc = btn.getAttribute('data-nodoc');
      
      document.getElementById('modal_upload_drf').value = drf;
      document.getElementById('modal_upload_nodoc').textContent = nodoc;
      
      // Show modal - menggunakan Bootstrap 3 syntax
      $('#modalSosialisasi').modal('show');
    }
  });

  // Reset button
  if (resetBtn && form) {
    resetBtn.addEventListener('click', function(){
      form.querySelectorAll('input[type="text"]').forEach(i => i.value='');
      form.querySelectorAll('select').forEach(s => s.selectedIndex=0);
      if (perPageInput) perPageInput.value = '20';
      if (sortInput) sortInput.value = 'oldest';
      if (sortSelect) sortSelect.value = 'oldest';
      if (results) results.style.display = 'none';
      if (window.history && history.replaceState) {
        const cleanUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
        history.replaceState({}, '', cleanUrl);
      }
    });
  }

  // Modal data-binding (mengisi modal ketika tombol action diklik)
  function bindModal(selector, modalPrefix) {
    document.querySelectorAll(selector).forEach(function(el){
      el.addEventListener('click', function(){
        const id = el.getAttribute('data-id') || '';
        const nodoc = el.getAttribute('data-nodoc') || '';
        const rev = el.getAttribute('data-rev') || '';
        const lama = el.getAttribute('data-lama') || '';
        const status = el.getAttribute('data-status') || '';
        const type = el.getAttribute('data-type') || '';

        if (modalPrefix==='2') {
          document.querySelector('#myModal2 #drf').value = id;
          document.querySelector('#myModal2 #nodoc').value = nodoc;
          document.querySelector('#myModal2 #rev').value = rev;
          document.querySelector('#myModal2 #status').value = status;
          document.querySelector('#myModal2 #type').value = type;
        }
        // contoh untuk modal ex / mas / 6
        if (modalPrefix==='ex') {
          const eldrf = document.querySelector('#myModalex #drf');
          if (eldrf) eldrf.value = id;
        }
        if (modalPrefix==='mas') {
          const eldrf = document.querySelector('#myModal5 #drf');
          if (eldrf) eldrf.value = id;
          const elnod = document.querySelector('#myModal5 #nodoc');
          if (elnod) elnod.value = nodoc;
        }
        if (modalPrefix==='6') {
          const eldrf = document.querySelector('#myModal6 #drf');
          if (eldrf) eldrf.value = id;
          const elnod = document.querySelector('#myModal6 #nodoc');
          if (elnod) elnod.value = nodoc;
        }
      });
    });
  }

  // delegasi: akan mencari elemen dengan class tertentu saat klik dibuat dinamically
  document.addEventListener('click', function(e){
    if (e.target.closest('.sec-file')) {
      const el = e.target.closest('.sec-file');
      // isi modal myModal2
      document.querySelector('#myModal2 #drf').value = el.getAttribute('data-id') || '';
      document.querySelector('#myModal2 #nodoc').value = el.getAttribute('data-nodoc') || '';
      document.querySelector('#myModal2 #rev').value = el.getAttribute('data-rev') || '';
      document.querySelector('#myModal2 #status').value = el.getAttribute('data-status') || '';
      document.querySelector('#myModal2 #type').value = el.getAttribute('data-type') || '';
    }
    if (e.target.closest('.ex-file')) {
      const el = e.target.closest('.ex-file');
      document.querySelector('#myModalex #drf').value = el.getAttribute('data-id') || '';
    }
    if (e.target.closest('.mas-file')) {
      const el = e.target.closest('.mas-file');
      document.querySelector('#myModal5 #drf').value = el.getAttribute('data-id') || '';
      document.querySelector('#myModal5 #nodoc').value = el.getAttribute('data-nodoc') || '';
    }
    if (e.target.closest('.upload-file')) {
      const el = e.target.closest('.upload-file');
      document.querySelector('#myModal6 #drf').value = el.getAttribute('data-drf') || '';
      document.querySelector('#myModal6 #nodoc').value = el.getAttribute('data-nodoc') || '';
    }
  });
})();
</script>

</body>
</html>
