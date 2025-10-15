<?php
// ===== PROSES POST DULU =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $jsonFile = __DIR__ . '/data/document_types.json';
    $types = json_decode(file_get_contents($jsonFile), true);
    
    $idx = intval($_POST['idx']);
    $submenuName = trim($_POST['submenu_name']);
    
    // Ambil filter config
    $filterConfig = [
        'section' => isset($_POST['filter_section']) ? true : false,
        'device' => isset($_POST['filter_device']) ? true : false,
        'process' => isset($_POST['filter_process']) ? true : false,
        'status' => isset($_POST['filter_status']) ? true : false,
        'category' => isset($_POST['filter_category']) ? true : false
    ];
    
    if ($submenuName !== '') {
        // Cek duplikat dalam submenu yang sama
        $existingSubmenus = isset($types[$idx]['submenu']) ? $types[$idx]['submenu'] : [];
        $submenuNames = array_column($existingSubmenus, 'name');
        $lower = array_map('strtolower', $submenuNames);
        
        if (in_array(strtolower($submenuName), $lower)) {
            header("Location: add_submenu.php?idx=$idx&error=duplicate");
            exit;
        }
        
        // Generate ID untuk submenu
        $submenuId = strtolower(str_replace(' ', '_', $submenuName));
        
        // Tambahkan submenu (SELALU DYNAMIC)
        $newSubmenu = [
            'id' => $submenuId,
            'name' => $submenuName,
            'use_custom_file' => false, // Selalu false untuk submenu baru
            'custom_file' => '', // Selalu kosong
            'filter_config' => $filterConfig,
            'is_legacy' => false // Submenu baru = tidak legacy
        ];
        
        if (!isset($types[$idx]['submenu'])) {
            $types[$idx]['submenu'] = [];
        }
        
        $types[$idx]['submenu'][] = $newSubmenu;
        
        // Pastikan has_submenu = true
        $types[$idx]['has_submenu'] = true;
        
        @file_put_contents($jsonFile, json_encode($types, JSON_PRETTY_PRINT));
        
        header("Location: conf_document.php?success=submenu_added");
        exit;
    } else {
        header("Location: add_submenu.php?idx=$idx&error=empty");
        exit;
    }
}

// ===== LOAD HEADER =====
include('header.php');
include('config_head.php');

$jsonFile = __DIR__ . '/data/document_types.json';
$types = json_decode(file_get_contents($jsonFile), true);

// Validasi index
if (!isset($_GET['idx']) || !isset($types[$_GET['idx']])) {
    echo "<div class='alert alert-danger'>Invalid index</div>"; 
    exit;
}

$idx = intval($_GET['idx']);
$parent = $types[$idx];

// Pesan error
$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'duplicate') {
        $error = "Submenu dengan nama tersebut sudah ada.";
    } elseif ($_GET['error'] == 'empty') {
        $error = "Silakan masukkan nama submenu.";
    }
}
?>

<br /><br />
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-6 well well-lg">
        <h2>Tambah Submenu untuk: <span class="text-primary"><?php echo htmlspecialchars($parent['name']); ?></span></h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-top:10px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($parent['submenu'])): ?>
        <div class="panel panel-info">
            <div class="panel-heading">Submenu yang sudah ada:</div>
            <div class="panel-body">
                <ul>
                <?php foreach ($parent['submenu'] as $sub): ?>
                    <li>
                        <?php echo htmlspecialchars($sub['name']); ?>
                        <?php 
                        $isLegacy = isset($sub['is_legacy']) && $sub['is_legacy'] === true;
                        $hasLegacyFile = isset($sub['legacy_file']) && !empty($sub['legacy_file']);
                        if ($isLegacy || $hasLegacyFile): 
                        ?>
                            <span class="label label-warning">Legacy (Custom File)</span>
                        <?php else: ?>
                            <span class="label label-success">Dynamic</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="idx" value="<?php echo $idx; ?>">
            
            <div class="form-group">
                <label>Nama Submenu <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="submenu_name" required 
                       placeholder="Contoh: PE Production, Quality Daily Report, dll">
                <p class="help-block">
                    Masukkan nama kategori/submenu untuk <?php echo htmlspecialchars($parent['name']); ?>. Halaman akan di-generate otomatis.
                    <br><strong>Tips:</strong> Gunakan kata "Production" atau "Other" di nama submenu untuk aktivasi auto-filtering:
                </p>
                <div class="well well-sm">
                    <ul class="small" style="margin-bottom:0;">
                        <li><strong>[Nama] Production</strong> → Otomatis filter dokumen dengan device production (device != '-')</li>
                        <li><strong>[Nama] Other</strong> → Otomatis filter dokumen tanpa device spesifik (device = '-' atau kosong)</li>
                        <li><strong>Nama lain</strong> → Tampilkan semua dokumen tanpa filter device otomatis</li>
                    </ul>
                </div>
            </div>

            <hr>

            <h4><span class="glyphicon glyphicon-filter"></span> Filter yang Tersedia</h4>
            <p class="text-muted">Pilih filter yang akan ditampilkan di halaman submenu ini:</p>

            <div class="well">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_section" checked>
                                <strong>Section</strong><br>
                                <small class="text-muted">Filter berdasarkan section</small>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_device" id="filter_device">
                                <strong>Device</strong><br>
                                <small class="text-muted">Filter berdasarkan device</small>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_process" id="filter_process">
                                <strong>Process</strong><br>
                                <small class="text-muted">Filter berdasarkan process</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-xs-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_status" checked>
                                <strong>Status</strong><br>
                                <small class="text-muted">Filter berdasarkan status</small>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_category">
                                <strong>Category</strong><br>
                                <small class="text-muted">Filter Internal/External</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-success">
                <strong><span class="glyphicon glyphicon-ok-circle"></span> Submenu ini akan dynamic!</strong>
                <p class="small" style="margin:5px 0 0 0;">Halaman akan di-generate otomatis dengan filter yang Anda pilih. Tidak perlu setup teknis tambahan.</p>
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success btn-lg">
                    <span class="glyphicon glyphicon-save"></span> Tambah Submenu
                </button>
                <a href="conf_document.php" class="btn btn-default btn-lg">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
    // Auto-check device when process is checked
    $('#filter_process').change(function(){
        if($(this).is(':checked')) {
            $('#filter_device').prop('checked', true);
        }
    });

    // Warning when unchecking device while process is checked
    $('#filter_device').change(function(){
        if(!$(this).is(':checked') && $('#filter_process').is(':checked')) {
            alert('Filter Process membutuhkan Filter Device. Filter Process akan dinonaktifkan.');
            $('#filter_process').prop('checked', false);
        }
    });
});
</script>