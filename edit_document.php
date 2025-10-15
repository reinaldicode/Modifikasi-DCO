<?php
// ===== PROSES POST DULU SEBELUM ADA OUTPUT APAPUN =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $jsonFile = __DIR__ . '/data/document_types.json';
    $types = json_decode(file_get_contents($jsonFile), true);
    
    $idx = intval($_POST['idx']);
    $name = trim($_POST['name']);
    $hasSubmenu = isset($_POST['has_submenu']) ? true : false;
    
    // Ambil filter config
    $filterConfig = [
        'section' => isset($_POST['filter_section']) ? true : false,
        'device' => isset($_POST['filter_device']) ? true : false,
        'process' => isset($_POST['filter_process']) ? true : false,
        'status' => isset($_POST['filter_status']) ? true : false,
        'category' => isset($_POST['filter_category']) ? true : false
    ];
    
    if ($name !== '') {
        // Check duplicate (kecuali dirinya sendiri)
        $tmp = $types;
        unset($tmp[$idx]);
        $existingNames = array_column($tmp, 'name');
        $lower = array_map('strtolower', $existingNames);
        
        if (in_array(strtolower($name), $lower)) {
            header("Location: edit_document.php?idx=$idx&error=duplicate");
            exit;
        } else {
            // Update data (TANPA custom file - pertahankan legacy_file jika ada)
            $types[$idx]['name'] = $name;
            $types[$idx]['has_submenu'] = $hasSubmenu;
            $types[$idx]['filter_config'] = $filterConfig;
            
            // Generate ID baru dari nama
            $types[$idx]['id'] = strtolower(str_replace(' ', '_', $name));
            
            // Set use_custom_file = false untuk document type baru
            if (!isset($types[$idx]['legacy_file']) || empty($types[$idx]['legacy_file'])) {
                $types[$idx]['use_custom_file'] = false;
                $types[$idx]['custom_file'] = '';
            }
            
            // Jika has_submenu dimatikan, hapus semua submenu
            if (!$hasSubmenu) {
                $types[$idx]['submenu'] = [];
            }
            
            @file_put_contents($jsonFile, json_encode(array_values($types), JSON_PRETTY_PRINT));
            
            header("Location: conf_document.php?success=updated");
            exit;
        }
    } else {
        header("Location: edit_document.php?idx=$idx&error=empty");
        exit;
    }
}

// ===== SETELAH PROSES POST, BARU LOAD HEADER =====
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
$current = $types[$idx];

// Pesan error dari redirect
$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'duplicate') {
        $error = "Document type dengan nama tersebut sudah ada.";
    } elseif ($_GET['error'] == 'empty') {
        $error = "Silakan masukkan nama document type.";
    }
}

$hasSubmenu = isset($current['has_submenu']) && $current['has_submenu'] === true;
$submenuCount = $hasSubmenu && !empty($current['submenu']) ? count($current['submenu']) : 0;
$legacyFile = isset($current['legacy_file']) ? $current['legacy_file'] : '';
$isLegacy = !empty($legacyFile);

// Get filter config (dengan default values)
$filterConfig = isset($current['filter_config']) ? $current['filter_config'] : [
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
 <h2><span class="glyphicon glyphicon-edit"></span> Edit Document Type</h2>
 <p class="text-muted">Edit konfigurasi untuk: <strong><?php echo htmlspecialchars($current['name']); ?></strong></p>
 <hr>

 <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
    </div>
 <?php endif; ?>

 <?php if ($submenuCount > 0 && !$hasSubmenu): ?>
    <div class="alert alert-warning">
        <span class="glyphicon glyphicon-warning-sign"></span>
        <strong>Perhatian!</strong> Dokumen ini memiliki <?php echo $submenuCount; ?> submenu. 
        Jika Anda menonaktifkan "Has Submenu", semua submenu akan dihapus!
    </div>
 <?php endif; ?>
 
 <?php if ($isLegacy): ?>
    <div class="alert alert-info">
        <span class="glyphicon glyphicon-info-sign"></span>
        <strong>Legacy Document Type</strong>
        <p style="margin-top:8px;">Document type ini menggunakan file legacy: <code><?php echo htmlspecialchars($legacyFile); ?></code></p>
        <p class="small" style="margin-bottom:0;">
            File legacy akan tetap digunakan untuk routing. Filter config di bawah hanya berlaku jika file legacy dihapus dari JSON secara manual.
        </p>
    </div>
 <?php endif; ?>

 <form action="" method="POST">
     <input type="hidden" name="idx" value="<?php echo $idx; ?>">
     
     <div class="form-group">
         <label><strong>Document Type Name</strong> <span class="text-danger">*</span></label>
         <input type="text" class="form-control input-lg" name="name" 
                value="<?php echo htmlspecialchars($current['name']); ?>" required>
         <p class="help-block">
             <span class="glyphicon glyphicon-info-sign"></span>
             Nama tipe dokumen yang akan ditampilkan di menu navigation
         </p>
     </div>
     
     <div class="form-group">
         <div class="checkbox">
             <label>
                 <input type="checkbox" name="has_submenu" <?php echo $hasSubmenu ? 'checked' : ''; ?>> 
                 <strong>Dokumen ini memiliki Sub-Menu</strong>
             </label>
         </div>
         <p class="help-block">
             <span class="glyphicon glyphicon-info-sign"></span>
             Centang jika dokumen ini akan memiliki kategori sub-menu (misalnya: Production, Other, dll)
             <?php if ($submenuCount > 0): ?>
             <br><span class="text-info"><strong>Saat ini memiliki <?php echo $submenuCount; ?> submenu</strong></span>
             <?php endif; ?>
         </p>
     </div>

     <hr>

     <h4><span class="glyphicon glyphicon-filter"></span> Filter Configuration</h4>
     <p class="text-muted">Pilih filter yang akan tersedia di halaman document list:</p>

     <?php if ($isLegacy): ?>
     <div class="alert alert-warning">
         <span class="glyphicon glyphicon-warning-sign"></span>
         <strong>Info:</strong> Filter config di bawah tidak berlaku untuk legacy document type. Filter diatur di file legacy.
     </div>
     <?php endif; ?>

     <div class="well">
         <div class="row">
             <div class="col-xs-6">
                 <div class="checkbox">
                     <label>
                         <input type="checkbox" name="filter_section" 
                                <?php echo ($filterConfig['section'] ?? false) ? 'checked' : ''; ?>
                                <?php echo $isLegacy ? 'disabled' : ''; ?>>
                         <strong>Section</strong>
                     </label>
                 </div>
                 
                 <div class="checkbox">
                     <label>
                         <input type="checkbox" name="filter_device" id="filter_device" 
                                <?php echo ($filterConfig['device'] ?? false) ? 'checked' : ''; ?>
                                <?php echo $isLegacy ? 'disabled' : ''; ?>>
                         <strong>Device</strong>
                     </label>
                 </div>
                 
                 <div class="checkbox">
                     <label>
                         <input type="checkbox" name="filter_process" id="filter_process" 
                                <?php echo ($filterConfig['process'] ?? false) ? 'checked' : ''; ?>
                                <?php echo $isLegacy ? 'disabled' : ''; ?>>
                         <strong>Process</strong>
                     </label>
                 </div>
             </div>
             
             <div class="col-xs-6">
                 <div class="checkbox">
                     <label>
                         <input type="checkbox" name="filter_status" 
                                <?php echo ($filterConfig['status'] ?? false) ? 'checked' : ''; ?>
                                <?php echo $isLegacy ? 'disabled' : ''; ?>>
                         <strong>Status</strong>
                     </label>
                 </div>
                 
                 <div class="checkbox">
                     <label>
                         <input type="checkbox" name="filter_category" 
                                <?php echo ($filterConfig['category'] ?? false) ? 'checked' : ''; ?>
                                <?php echo $isLegacy ? 'disabled' : ''; ?>>
                         <strong>Category</strong>
                     </label>
                 </div>
             </div>
         </div>
     </div>

     <div class="form-group">
         <button type="submit" name="submit" class="btn btn-success btn-lg btn-block">
             <span class="glyphicon glyphicon-save"></span> Update Document Type
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