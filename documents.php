<?php
include('header.php');
include 'koneksi.php';

// Ambil parameter
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$subtype = isset($_GET['subtype']) ? trim($_GET['subtype']) : '';

if (empty($type)) {
    echo "<div class='alert alert-danger'>Invalid document type</div>";
    exit;
}

// Load document types dari JSON untuk mendapatkan filter config
$jsonFile = __DIR__ . '/data/document_types.json';
$docTypes = [];
$filterConfig = [];

if (file_exists($jsonFile)) {
    $docTypes = json_decode(file_get_contents($jsonFile), true);
    
    // Cari document type yang sesuai
    foreach ($docTypes as $dt) {
        if (strcasecmp($dt['name'], $type) === 0) {
            // Jika ada subtype, cari config di submenu
            if (!empty($subtype) && !empty($dt['submenu'])) {
                foreach ($dt['submenu'] as $sub) {
                    if (strcasecmp($sub['name'], $subtype) === 0) {
                        $filterConfig = isset($sub['filter_config']) ? $sub['filter_config'] : [];
                        break;
                    }
                }
            } else {
                // Gunakan config dari parent
                $filterConfig = isset($dt['filter_config']) ? $dt['filter_config'] : [];
            }
            break;
        }
    }
}

// Default filter config jika tidak ditemukan
if (empty($filterConfig)) {
    $filterConfig = [
        'section' => true,
        'device' => false,
        'process' => false,
        'status' => true,
        'category' => false
    ];
}

// Title halaman
$pageTitle = htmlspecialchars($type);
if (!empty($subtype)) {
    $pageTitle .= " - " . htmlspecialchars($subtype);
}
?>

<!-- jQuery -->
<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    // Handler untuk cascading dropdown device -> process
    <?php if ($filterConfig['device'] && $filterConfig['process']): ?>
    $('#wait_device').hide();
    $('#device').change(function(){
        $('#wait_device').show();
        $('#result_process').hide();
        $.get("func.php", {
            func: "device",
            drop_var2: $('#device').val()
        }, function(response){
            $('#result_process').fadeOut();
            setTimeout(function(){ 
                finishAjaxProcess('result_process', escape(response));
            }, 400);
        });
        return false;
    });
    <?php endif; ?>

    // Handler untuk cascading section -> device (jika perlu)
    <?php if ($filterConfig['section'] && $filterConfig['device']): ?>
    $('#wait_section').hide();
    $('#section').change(function(){
        $('#wait_section').show();
        $('#result_device').hide();
        $.get("func.php", {
            func: "section",
            drop_var: $('#section').val()
        }, function(response){
            $('#result_device').fadeOut();
            setTimeout(function(){ 
                finishAjaxDevice('result_device', escape(response));
            }, 400);
        });
        return false;
    });
    <?php endif; ?>

    // Modal handler
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

    // Tombol upload sosialisasi
    $(document).on('click', '.btn-upload-sos', function(e){
        e.preventDefault();
        var drf = $(this).data('drf');
        var nodoc = $(this).data('nodoc');
        $('#modal_upload_drf').val(drf);
        $('#modal_upload_nodoc').text(nodoc);
        $('#modalSosialisasi').modal('show');
    });
});

// Function untuk handle response device dropdown
function finishAjaxDevice(id, response) {
    $('#wait_section').hide();
    $('#'+id).html(unescape(response));
    $('#'+id).fadeIn();

    // Re-attach event handler untuk device -> process setelah device dropdown di-load
    <?php if ($filterConfig['process']): ?>
    $('#wait_device').hide();
    $('#device').change(function(){
        $('#wait_device').show();
        $('#result_process').hide();
        $.get("func.php", {
            func: "device",
            drop_var2: $('#device').val()
        }, function(response){
            $('#result_process').fadeOut();
            setTimeout(function(){ 
                finishAjaxProcess('result_process', escape(response));
            }, 400);
        });
        return false;
    });
    <?php endif; ?>
}

// Function untuk handle response process dropdown
function finishAjaxProcess(id, response) {
    $('#wait_device').hide();
    $('#'+id).html(unescape(response));
    $('#'+id).fadeIn();
}
</script>

<br />

<div class="row">
    <div class="col-xs-4 well well-lg">
        <h2>Filter Documents</h2>
        
        <form action="" method="GET">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
            <?php if (!empty($subtype)): ?>
            <input type="hidden" name="subtype" value="<?php echo htmlspecialchars($subtype); ?>">
            <?php endif; ?>
            
            <table>
                <?php if ($filterConfig['section']): ?>
                <tr>
                    <td>Section</td>
                    <td>:</td>
                    <td>
                        <?php 
                        $sect = "SELECT * FROM section ORDER BY sect_name";
                        $sql_sect = mysqli_query($link, $sect);
                        ?>
                        <select name="section" id="section" class="form-control">
                            <option value=""> --- Select Section --- </option>
                            <?php while($data_sec = mysqli_fetch_array($sql_sect)) { 
                                $selected = (isset($_GET['section']) && $_GET['section'] == $data_sec['sect_name']) ? 'selected' : '';
                            ?>
                            <option value="<?php echo htmlspecialchars($data_sec['sect_name']); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($data_sec['sect_name']); ?>
                            </option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>

                <?php if ($filterConfig['device']): ?>
                <tr>
                    <td>Device</td>
                    <td>:</td>
                    <td>
                        <?php if ($filterConfig['section']): ?>
                            <!-- Jika ada section filter, device dropdown akan di-load via AJAX dari func.php -->
                            <span id="wait_section" style="display: none;"><img alt="Please Wait" src="images/wait.gif"/></span>
                            <span id="result_device" style="display: none;"></span>
                        <?php else: ?>
                            <?php 
                            // Jika TIDAK ada section filter, tampilkan device dropdown langsung
                            $dev = "SELECT * FROM device ORDER BY name";
                            $sql_dev = mysqli_query($link, $dev);
                            
                            // List device hardcoded yang pasti muncul (sesuai screenshot)
                            $hardcodedDevices = [
                                'General production',
                                'General PC',
                                'New Remocon',
                                'PI',
                                'PC300N',
                                'PC400 N',
                                'PT-GL',
                                'SC-63',
                                'SC-63 Matrix',
                                'DDS',
                                'Smoke Sensor',
                                'SOP 4 PIN',
                                'SSR 400'
                            ];
                            ?>
                            <select name="device" id="device" class="form-control">
                                <option value="" selected="selected"> --- Select Device --- </option>
                                <?php 
                                // Tampilkan semua hardcoded devices
                                foreach ($hardcodedDevices as $dev_name) {
                                    $selected = (isset($_GET['device']) && $_GET['device'] == $dev_name) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($dev_name) . '" ' . $selected . '>' . htmlspecialchars($dev_name) . '</option>';
                                }
                                
                                // Tambahkan device dari database yang belum ada di hardcoded list
                                while($data_dev = mysqli_fetch_array($sql_dev)) { 
                                    if (!in_array($data_dev['name'], $hardcodedDevices)) {
                                        $selected = (isset($_GET['device']) && $_GET['device'] == $data_dev['name']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($data_dev['name']) . '" ' . $selected . '>' . htmlspecialchars($data_dev['name']) . '</option>';
                                    }
                                } 
                                ?>
                            </select>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>

                <?php if ($filterConfig['process']): ?>
                <tr>
                    <td>Process</td>
                    <td>:</td>
                    <td>
                        <?php if ($filterConfig['device']): ?>
                            <span id="wait_device" style="display: none;"><img alt="Please Wait" src="images/wait.gif"/></span>
                            <span id="result_process" style="display: none;"></span>
                        <?php else: ?>
                            <?php 
                            // Jika tidak ada device filter, tampilkan semua process
                            $proc = "SELECT DISTINCT process FROM docu WHERE process IS NOT NULL AND process != '' ORDER BY process";
                            $sql_proc = mysqli_query($link, $proc);
                            ?>
                            <select name="proc" class="form-control">
                                <option value=""> --- Select Process --- </option>
                                <?php while($data_proc = mysqli_fetch_array($sql_proc)) { 
                                    $selected = (isset($_GET['proc']) && $_GET['proc'] == $data_proc['process']) ? 'selected' : '';
                                ?>
                                <option value="<?php echo htmlspecialchars($data_proc['process']); ?>" <?php echo $selected; ?>>
                                    <?php echo htmlspecialchars($data_proc['process']); ?>
                                </option>
                                <?php } ?>
                            </select>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>

                <?php if ($filterConfig['status']): ?>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                        <select name="status" class="form-control">
                            <option value=""> --- Select Status --- </option>
                            <option value="Secured" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Secured') ? 'selected' : ''; ?>>Approved</option>
                            <option value="Review" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Review') ? 'selected' : ''; ?>>Review</option>
                            <option value="Pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>

                <?php if ($filterConfig['category']): ?>
                <tr>
                    <td>Category</td>
                    <td>:</td>
                    <td>
                        <select name="cat" class="form-control">
                            <option value=""> --- Select Category --- </option>
                            <option value="Internal" <?php echo (isset($_GET['cat']) && $_GET['cat'] == 'Internal') ? 'selected' : ''; ?>>Internal</option>
                            <option value="External" <?php echo (isset($_GET['cat']) && $_GET['cat'] == 'External') ? 'selected' : ''; ?>>External</option>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>

                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="hidden" name="by" value="no_drf">
                        <input type="submit" value="Show" name="submit" class="btn btn-info">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php
// Build query jika form disubmit
if (isset($_GET['submit'])) {
    // WHERE condition untuk doc_type
    $whereConditions = ["doc_type = '" . mysqli_real_escape_string($link, $type) . "'"];
    
    // LOGIC: Filter otomatis berdasarkan submenu menggunakan kolom device
    if (!empty($subtype)) {
        $subtype_lower = strtolower($subtype);
        
        // PRODUCTION submenu = dokumen yang punya device production
        if (strpos($subtype_lower, 'production') !== false) {
            $whereConditions[] = "(device IS NOT NULL AND device != '' AND device != '-' AND device != 'General Production')";
        }
        // OTHER submenu = dokumen tanpa device spesifik atau general
        elseif (strpos($subtype_lower, 'other') !== false) {
            $whereConditions[] = "(device IS NULL OR device = '' OR device = '-' OR device = 'General Production')";
        }
        // Untuk submenu lainnya yang tidak menggunakan pattern production/other, tidak ada filter tambahan
    }
    
    // Apply filters berdasarkan input user
    if ($filterConfig['section'] && !empty($_GET['section'])) {
        $whereConditions[] = "section = '" . mysqli_real_escape_string($link, $_GET['section']) . "'";
    }
    
    if ($filterConfig['device'] && !empty($_GET['device'])) {
        $whereConditions[] = "device = '" . mysqli_real_escape_string($link, $_GET['device']) . "'";
    }
    
    if ($filterConfig['process'] && !empty($_GET['proc'])) {
        $whereConditions[] = "process = '" . mysqli_real_escape_string($link, $_GET['proc']) . "'";
    }
    
    if ($filterConfig['status'] && !empty($_GET['status'])) {
        $whereConditions[] = "status = '" . mysqli_real_escape_string($link, $_GET['status']) . "'";
    }
    
    if ($filterConfig['category'] && !empty($_GET['cat'])) {
        $whereConditions[] = "category = '" . mysqli_real_escape_string($link, $_GET['cat']) . "'";
    }
    
    $by = isset($_GET['by']) ? mysqli_real_escape_string($link, $_GET['by']) : 'no_drf';
    $sql = "SELECT * FROM docu WHERE " . implode(' AND ', $whereConditions) . " ORDER BY $by DESC LIMIT 200";
    
    $result = mysqli_query($link, $sql);
    
    if (!$result) {
        echo "<div class='alert alert-danger'>Query error: ". htmlspecialchars(mysqli_error($link)) ."</div>";
        exit;
    }
?>

<br /><br />
<table class="table table-hover table-bordered">
    <h1>Document List: <strong><?php echo $pageTitle; ?></strong></h1>
    
    <thead bgcolor="#00FFFF">
        <tr>
            <td>No</td>
            <td>Date</td>
            <td>No. Document</td>
            <td>No Rev.</td>
            <td>DRF</td>
            <td>Title</td>
            <?php if ($filterConfig['process']): ?>
            <td>Process</td>
            <?php endif; ?>
            <?php if ($filterConfig['section']): ?>
            <td>Section</td>
            <?php endif; ?>
            <td>Status</td>
            <td>Action</td>
            <td>Sosialisasi</td>
        </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    while ($info = mysqli_fetch_assoc($result)) {
        $has_sos = !empty($info['sos_file']);
    ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo htmlspecialchars($info['tgl_upload']);?></td>
            <td><?php echo htmlspecialchars($info['no_doc']);?></td>
            <td><?php echo htmlspecialchars($info['no_rev']);?></td>
            <td><?php echo htmlspecialchars($info['no_drf']);?></td>
            <td>
                <?php
                if ($info['no_drf'] > 12967) { 
                    $tempat = $info['doc_type']; 
                } else { 
                    $tempat = 'document'; 
                }
                ?>
                <a href="<?php echo htmlspecialchars($tempat . '/' . $info['file']); ?>" target="_blank">
                    <?php echo htmlspecialchars($info['title']);?>
                </a>
            </td>
            <?php if ($filterConfig['process']): ?>
            <td><?php echo htmlspecialchars($info['process']);?></td>
            <?php endif; ?>
            <?php if ($filterConfig['section']): ?>
            <td><?php echo htmlspecialchars($info['section']);?></td>
            <?php endif; ?>
            <td>
                <?php
                $statusClass = '';
                switch ($info['status']) {
                    case 'Approved':
                    case 'Secured':
                        $statusClass = 'success';
                        break;
                    case 'Review':
                        $statusClass = 'warning';
                        break;
                    case 'Pending':
                        $statusClass = 'info';
                        break;
                    default:
                        $statusClass = 'default';
                }
                ?>
                <span class="label label-<?php echo $statusClass; ?>">
                    <?php echo htmlspecialchars($info['status']); ?>
                </span>
            </td>

            <!-- Action -->
            <td>
                <a href="detail.php?drf=<?php echo urlencode($info['no_drf']);?>&no_doc=<?php echo urlencode($info['no_doc']);?>" 
                   class="btn btn-xs btn-info" title="Lihat detail">
                    <span class="glyphicon glyphicon-search"></span>
                </a>

                <a href="lihat_approver.php?drf=<?php echo urlencode($info['no_drf']);?>" 
                   class="btn btn-xs btn-warning" title="Lihat approver">
                    <span class="glyphicon glyphicon-user"></span>
                </a>

                <a href="radf.php?drf=<?php echo urlencode($info['no_drf']);?>&section=<?php echo urlencode($info['section']);?>" 
                   class="btn btn-xs btn-primary" title="Lihat RADF">
                    <span class="glyphicon glyphicon-eye-open"></span>
                </a>

                <button type="button"
                    class="btn btn-xs btn-success btn-upload-sos"
                    data-drf="<?php echo htmlspecialchars($info['no_drf']);?>"
                    data-nodoc="<?php echo htmlspecialchars($info['no_doc']);?>"
                    title="Upload Bukti Sosialisasi">
                    <span class="glyphicon glyphicon-upload"></span>
                </button>

                <?php
                if ( ($_SESSION['state'] ?? '') == 'Admin' || 
                     (($_SESSION['state'] ?? '') == "Originator" && ($info['user_id'] ?? '') == ($_SESSION['nrp'] ?? '')) ) {
                ?>
                    <a href="edit_doc.php?drf=<?php echo urlencode($info['no_drf']);?>" 
                       class="btn btn-xs btn-primary" title="Edit Doc">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a href="del_doc.php?drf=<?php echo urlencode($info['no_drf']);?>" 
                       class="btn btn-xs btn-danger" 
                       onClick="return confirm('Delete document <?php echo addslashes($info['no_doc'])?>?')" 
                       title="Delete Doc">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                    
                    <?php if ($info['status'] == 'Secured') { ?>
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

            <!-- Kolom Sosialisasi -->
            <td>
                <?php if ($has_sos) { ?>
                    <a href="lihat_sosialisasi.php?drf=<?php echo urlencode($info['no_drf']);?>" 
                       class="btn btn-xs btn-primary" title="Lihat Detail Sosialisasi">
                        <span class="glyphicon glyphicon-file"></span>
                    </a>
                <?php } else { ?>
                    <a href="lihat_sosialisasi.php?drf=<?php echo urlencode($info['no_drf']);?>" 
                       class="btn btn-xs btn-default" title="Belum ada bukti sosialisasi">
                        <span class="glyphicon glyphicon-file"></span>
                    </a>
                <?php } ?>
            </td>
        </tr>
    <?php
        $i++;
    }
    ?>
    </tbody>
</table>

<?php
} else {
    // Tampilkan instruksi jika belum submit
    echo "<div class='alert alert-info' style='margin-top:20px;'>";
    echo "<h4><span class='glyphicon glyphicon-info-sign'></span> Cara Menggunakan</h4>";
    echo "<p>Silakan pilih filter di sidebar kiri, kemudian klik tombol <strong>Show</strong> untuk menampilkan dokumen.</p>";
    
    // Tampilkan info submenu logic jika ada
    if (!empty($subtype)) {
        $subtype_lower = strtolower($subtype);
        if (strpos($subtype_lower, 'production') !== false) {
            echo "<p class='text-success'><strong>Info:</strong> Submenu ini menampilkan dokumen <strong>Production</strong> (dengan device production).</p>";
        } elseif (strpos($subtype_lower, 'other') !== false) {
            echo "<p class='text-info'><strong>Info:</strong> Submenu ini menampilkan dokumen <strong>Other/General</strong> (tanpa device spesifik).</p>";
        }
    }
    
    echo "</div>";
}
?>

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

<script src="bootstrap/js/bootstrap.min.js"></script>