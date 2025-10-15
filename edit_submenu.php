<?php
// ===== PROSES POST DULU =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $jsonFile = __DIR__ . '/data/document_types.json';
    $types = json_decode(file_get_contents($jsonFile), true);
    
    $idx = intval($_POST['idx']);
    $subidx = intval($_POST['subidx']);
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
        // Cek duplikat (kecuali dirinya sendiri)
        $existingSubmenus = isset($types[$idx]['submenu']) ? $types[$idx]['submenu'] : [];
        $tmp = $existingSubmenus;
        unset($tmp[$subidx]);
        $submenuNames = array_column($tmp, 'name');
        $lower = array_map('strtolower', $submenuNames);
        
        if (in_array(strtolower($submenuName), $lower)) {
            header("Location: edit_submenu.php?idx=$idx&subidx=$subidx&error=duplicate");
            exit;
        }
        
        // Pertahankan legacy_file jika ada
        $legacyFile = isset($types[$idx]['submenu'][$subidx]['legacy_file']) ? $types[$idx]['submenu'][$subidx]['legacy_file'] : null;
        $isLegacy = isset($types[$idx]['submenu'][$subidx]['is_legacy']) ? $types[$idx]['submenu'][$subidx]['is_legacy'] : false;
        
        // Update submenu
        $submenuId = strtolower(str_replace(' ', '_', $submenuName));
        $types[$idx]['submenu'][$subidx] = [
            'id' => $submenuId,
            'name' => $submenuName,
            'filter_config' => $filterConfig
        ];
        
        // Tambahkan legacy_file dan is_legacy jika ada
        if ($legacyFile) {
            $types[$idx]['submenu'][$subidx]['legacy_file'] = $legacyFile;
        }
        if ($isLegacy) {
            $types[$idx]['submenu'][$subidx]['is_legacy'] = true;
        }
        
        @file_put_contents($jsonFile, json_encode($types, JSON_PRETTY_PRINT));
        
        header("Location: conf_document.php?success=updated");
        exit;
    } else {
        header("Location: edit_submenu.php?idx=$idx&subidx=$subidx&error=empty");
        exit;
    }
}

// ===== LOAD HEADER =====
include('header.php');
include('config_head.php');

$jsonFile = __DIR__ . '/data/document_types.json';

// Cek file JSON ada
if (!file_exists($jsonFile)) {
    echo "<div class='alert alert-danger'>File document_types.json tidak ditemukan.</div>";
    echo "<p><a href='conf_document.php' class='btn btn-primary'>Kembali ke Config Document</a></p>";
    exit;
}

$types = json_decode(file_get_contents($jsonFile), true);

// Validasi parameter GET
if (!isset($_GET['idx'])) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>Error: Parameter 'idx' tidak ditemukan</h4>";
    echo "<p>URL yang benar: <code>edit_submenu.php?idx=0&subidx=0</code></p>";
    echo "<p><a href='conf_document.php' class='btn btn-primary'>Kembali ke Config Document</a></p>";
    echo "</div>";
    exit;
}

if (!isset($_GET['subidx'])) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>Error: Parameter 'subidx' tidak ditemukan</h4>";
    echo "<p>URL yang benar: <code>edit_submenu.php?idx=0&subidx=0</code></p>";
    echo "<p><a href='conf_document.php' class='btn btn-primary'>Kembali ke Config Document</a></p>";
    echo "</div>";
    exit;
}

$idx = intval($_GET['idx']);
$subidx = intval($_GET['subidx']);

// Validasi idx
if (!isset($types[$idx])) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>Error: Invalid parent index</h4>";
    echo "<p><a href='conf_document.php' class='btn btn-primary'>Kembali ke Config Document</a></p>";
    echo "</div>";
    exit;
}

$parent = $types[$idx];

// Validasi subidx
if (!isset($parent['submenu'][$subidx])) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>Error: Invalid submenu index</h4>";
    echo "<p>Parent: " . htmlspecialchars($parent['name']) . "</p>";
    echo "<p><a href='conf_document.php' class='btn btn-primary'>Kembali ke Config Document</a></p>";
    echo "</div>";
    exit;
}

$currentSub = $parent['submenu'][$subidx];

// Pesan error
$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'duplicate') {
        $error = "Submenu dengan nama tersebut sudah ada.";
    } elseif ($_GET['error'] == 'empty') {
        $error = "Silakan masukkan nama submenu.";
    }
}

// Get filter config (dengan default values)
$filterConfig = isset($currentSub['filter_config']) ? $currentSub['filter_config'] : [
    'section' => true,
    'device' => false,
    'process' => false,
    'status' => true,
    'category' => false
];

// Check if legacy (untuk logic saja, tidak ditampilkan)
$isLegacy = isset($currentSub['is_legacy']) && $currentSub['is_legacy'] === true;
$hasLegacyFile = isset($currentSub['legacy_file']) && !empty($currentSub['legacy_file']);
?>

<br /><br />
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-6 well well-lg">
        <h2><span class="glyphicon glyphicon-edit"></span> Edit Submenu</h2>
        <p class="text-muted">
            <strong><?php echo htmlspecialchars($currentSub['name']); ?></strong>
            <span class="text-muted"> - Parent: <?php echo htmlspecialchars($parent['name']); ?></span>
        </p>
        <hr>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="idx" value="<?php echo $idx; ?>">
            <input type="hidden" name="subidx" value="<?php echo $subidx; ?>">
            
            <div class="form-group">
                <label><strong>Submenu Name</strong> <span class="text-danger">*</span></label>
                <input type="text" class="form-control input-lg" name="submenu_name" required 
                       value="<?php echo htmlspecialchars($currentSub['name']); ?>">
                <p class="help-block">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    <strong>Tips:</strong> Gunakan kata "Production" atau "Other" di nama submenu untuk auto-filtering
                </p>
            </div>

            <div class="alert alert-info" style="font-size:12px;">
                <strong><span class="glyphicon glyphicon-lightbulb"></span> Auto-Filter:</strong>
                <ul style="margin:5px 0 0 0; padding-left:20px;">
                    <li><strong>Production</strong> → Otomatis filter dokumen dengan device production</li>
                    <li><strong>Other</strong> → Otomatis filter dokumen tanpa device spesifik (general)</li>
                </ul>
            </div>

            <hr>

            <h4><span class="glyphicon glyphicon-filter"></span> Filter Configuration</h4>
            <p class="text-muted">Pilih filter yang akan tersedia di halaman submenu ini:</p>

            <div class="well">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_section" 
                                       <?php echo ($filterConfig['section'] ?? false) ? 'checked' : ''; ?>
                                       <?php echo ($isLegacy || $hasLegacyFile) ? 'disabled' : ''; ?>>
                                <strong>Section</strong>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_device" id="filter_device" 
                                       <?php echo ($filterConfig['device'] ?? false) ? 'checked' : ''; ?>
                                       <?php echo ($isLegacy || $hasLegacyFile) ? 'disabled' : ''; ?>>
                                <strong>Device</strong>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_process" id="filter_process" 
                                       <?php echo ($filterConfig['process'] ?? false) ? 'checked' : ''; ?>
                                       <?php echo ($isLegacy || $hasLegacyFile) ? 'disabled' : ''; ?>>
                                <strong>Process</strong>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-xs-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_status" 
                                       <?php echo ($filterConfig['status'] ?? false) ? 'checked' : ''; ?>
                                       <?php echo ($isLegacy || $hasLegacyFile) ? 'disabled' : ''; ?>>
                                <strong>Status</strong>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_category" 
                                       <?php echo ($filterConfig['category'] ?? false) ? 'checked' : ''; ?>
                                       <?php echo ($isLegacy || $hasLegacyFile) ? 'disabled' : ''; ?>>
                                <strong>Category</strong>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($isLegacy || $hasLegacyFile): ?>
            <div class="alert alert-warning" style="font-size:12px;">
                <span class="glyphicon glyphicon-warning-sign"></span>
                <strong>Note:</strong> This is a legacy submenu. Filter configuration is managed in the custom file.
            </div>
            <?php endif; ?>

            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success btn-lg btn-block">
                    <span class="glyphicon glyphicon-save"></span> Update Submenu
                </button>
                <a href="conf_document.php" class="btn btn-default btn-lg btn-block">
                    <span class="glyphicon glyphicon-arrow-left"></span> Cancel
                </a>
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

<style>
.help-block {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}
</style>