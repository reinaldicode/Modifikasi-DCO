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
        
        // Update submenu
        $submenuId = strtolower(str_replace(' ', '_', $submenuName));
        $types[$idx]['submenu'][$subidx] = [
            'id' => $submenuId,
            'name' => $submenuName,
            'filter_config' => $filterConfig
        ];
        
        // Tambahkan legacy_file jika ada
        if ($legacyFile) {
            $types[$idx]['submenu'][$subidx]['legacy_file'] = $legacyFile;
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
$types = json_decode(file_get_contents($jsonFile), true);

// Validasi index
if (!isset($_GET['idx']) || !isset($types[$_GET['idx']])) {
    echo "<div class='alert alert-danger'>Invalid parent index</div>"; 
    exit;
}

$idx = intval($_GET['idx']);
$subidx = intval($_GET['subidx']);
$parent = $types[$idx];

if (!isset($parent['submenu'][$subidx])) {
    echo "<div class='alert alert-danger'>Invalid submenu index</div>"; 
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
?>

<br /><br />
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-6 well well-lg">
        <h2>Edit Submenu: <span class="text-primary"><?php echo htmlspecialchars($currentSub['name']); ?></span></h2>
        <p class="text-muted">Parent: <?php echo htmlspecialchars($parent['name']); ?></p>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-top:10px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="idx" value="<?php echo $idx; ?>">
            <input type="hidden" name="subidx" value="<?php echo $subidx; ?>">
            
            <div class="form-group">
                <label>Nama Submenu <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="submenu_name" required 
                       value="<?php echo htmlspecialchars($currentSub['name']); ?>">
            </div>

            <hr>

            <h4>Pengaturan Filter Dropdown</h4>
            <p class="text-muted">Pilih filter yang akan ditampilkan di halaman submenu ini:</p>

            <div class="well">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="filter_section" <?php echo ($filterConfig['section'] ?? false) ? 'checked' : ''; ?>>
                        <strong>Filter Section</strong> - Filter berdasarkan section/department
                    </label>
                </div>
                
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="filter_device" id="filter_device" <?php echo ($filterConfig['device'] ?? false) ? 'checked' : ''; ?>>
                        <strong>Filter Device</strong> - Filter berdasarkan device/mesin
                    </label>
                </div>
                
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="filter_process" id="filter_process" <?php echo ($filterConfig['process'] ?? false) ? 'checked' : ''; ?>>
                        <strong>Filter Process</strong> - Filter berdasarkan process (membutuhkan Device)
                    </label>
                </div>
                
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="filter_status" <?php echo ($filterConfig['status'] ?? false) ? 'checked' : ''; ?>>
                        <strong>Filter Status</strong> - Filter berdasarkan status approval
                    </label>
                </div>
                
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="filter_category" <?php echo ($filterConfig['category'] ?? false) ? 'checked' : ''; ?>>
                        <strong>Filter Category</strong> - Filter berdasarkan kategori (Internal/External)
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success btn-lg">
                    <span class="glyphicon glyphicon-save"></span> Update
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