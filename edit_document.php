<?php
// ===== PROSES POST DULU SEBELUM ADA OUTPUT APAPUN =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $jsonFile = __DIR__ . '/data/document_types.json';
    $types = json_decode(file_get_contents($jsonFile), true);
    
    $idx = intval($_POST['idx']);
    $name = trim($_POST['name']);
    $hasSubmenu = isset($_POST['has_submenu']) ? true : false;
    $useCustomFile = isset($_POST['use_custom_file']) ? true : false;
    $customFile = trim($_POST['custom_file']);
    
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
            // Update data
            $types[$idx]['name'] = $name;
            $types[$idx]['has_submenu'] = $hasSubmenu;
            $types[$idx]['use_custom_file'] = $useCustomFile;
            $types[$idx]['custom_file'] = $customFile;
            $types[$idx]['filter_config'] = $filterConfig;
            
            // Generate ID baru dari nama
            $types[$idx]['id'] = strtolower(str_replace(' ', '_', $name));
            
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
        $error = "Document type already exists.";
    } elseif ($_GET['error'] == 'empty') {
        $error = "Please enter a name.";
    }
}

$hasSubmenu = isset($current['has_submenu']) && $current['has_submenu'] === true;
$submenuCount = $hasSubmenu && !empty($current['submenu']) ? count($current['submenu']) : 0;
$useCustomFile = isset($current['use_custom_file']) && $current['use_custom_file'] === true;
$customFile = isset($current['custom_file']) ? $current['custom_file'] : '';

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
 <h2>Edit Document Type</h2>

 <?php if ($error): ?>
    <div class="alert alert-danger" style="margin-top:10px;">
        <?php echo htmlspecialchars($error); ?>
    </div>
 <?php endif; ?>

 <?php if ($submenuCount > 0 && !$hasSubmenu): ?>
    <div class="alert alert-warning">
        <strong>Perhatian!</strong> Dokumen ini memiliki <?php echo $submenuCount; ?> submenu. 
        Jika Anda menonaktifkan "Has Submenu", semua submenu akan dihapus!
    </div>
 <?php endif; ?>

 <form action="" method="POST">
     <input type="hidden" name="idx" value="<?php echo $idx; ?>">
     
     <div class="form-group">
         <label>Type Name <span class="text-danger">*</span></label>
         <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($current['name']); ?>" required>
     </div>
     
     <div class="form-group">
         <div class="checkbox">
             <label>
                 <input type="checkbox" name="use_custom_file" id="use_custom_file" <?php echo $useCustomFile ? 'checked' : ''; ?>> 
                 <strong>Gunakan File PHP Custom</strong>
             </label>
             <p class="help-block">
                 Centang jika ingin link menu ke file PHP terpisah yang sudah ada
             </p>
         </div>
     </div>
     
     <div class="form-group" id="custom_file_group" style="<?php echo $useCustomFile ? '' : 'display:none;'; ?>">
         <label>Nama File Custom</label>
         <input type="text" class="form-control" name="custom_file" id="custom_file" 
                value="<?php echo htmlspecialchars($customFile); ?>" 
                placeholder="contoh: procedure_login.php">
         <p class="help-block">
             File harus ada di root folder. 
             <?php if ($useCustomFile && !empty($customFile)): ?>
                 Status: 
                 <?php if (file_exists(__DIR__ . '/' . $customFile)): ?>
                     <span class="label label-success"><span class="glyphicon glyphicon-ok"></span> File ditemukan</span>
                 <?php else: ?>
                     <span class="label label-danger"><span class="glyphicon glyphicon-remove"></span> File tidak ditemukan</span>
                 <?php endif; ?>
             <?php endif; ?>
         </p>
     </div>
     
     <div class="form-group">
         <div class="checkbox">
             <label>
                 <input type="checkbox" name="has_submenu" <?php echo $hasSubmenu ? 'checked' : ''; ?>> 
                 <strong>Dokumen ini memiliki Sub-Menu</strong>
             </label>
             <p class="help-block">
                 Centang jika dokumen ini akan memiliki kategori sub-menu
                 <?php if ($submenuCount > 0): ?>
                 <br><span class="text-info">Saat ini memiliki <?php echo $submenuCount; ?> submenu</span>
                 <?php endif; ?>
             </p>
         </div>
     </div>

     <hr>

     <h4>Pengaturan Filter Dropdown</h4>
     <p class="text-muted">Pilih filter yang akan ditampilkan di halaman document list:</p>

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
         <a href="conf_document.php" class="btn btn-default btn-lg">Cancel</a>
     </div>
 </form>

</div>
</div>

<script>
$(document).ready(function(){
    $('#use_custom_file').change(function(){
        if($(this).is(':checked')) {
            $('#custom_file_group').slideDown();
        } else {
            $('#custom_file_group').slideUp();
            $('#custom_file').val('');
        }
    });

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