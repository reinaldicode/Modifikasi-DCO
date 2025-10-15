<?php
// ===== PROSES POST DULU =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $jsonFile = __DIR__ . '/data/document_types.json';
    $types = json_decode(file_get_contents($jsonFile), true);
    
    if (!is_array($types)) {
        $types = [];
    }
    
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
        // Check duplicate
        $existingNames = array_column($types, 'name');
        $lower = array_map('strtolower', $existingNames);
        
        if (in_array(strtolower($name), $lower)) {
            header("Location: add_document.php?error=duplicate");
            exit;
        }
        
        // Generate ID dari nama
        $id = strtolower(str_replace(' ', '_', $name));
        
        // Create new document type (SELALU DYNAMIC - tidak ada custom file)
        $newType = [
            'id' => $id,
            'name' => $name,
            'has_submenu' => $hasSubmenu,
            'submenu' => [],
            'filter_config' => $filterConfig,
            'use_custom_file' => false,
            'custom_file' => ''
        ];
        
        // Add to array
        $types[] = $newType;
        
        // Save
        if (file_put_contents($jsonFile, json_encode($types, JSON_PRETTY_PRINT))) {
            header("Location: conf_document.php?success=added");
            exit;
        } else {
            header("Location: add_document.php?error=save_failed");
            exit;
        }
    } else {
        header("Location: add_document.php?error=empty");
        exit;
    }
}

// ===== LOAD HEADER =====
include('header.php');
include('config_head.php');

// Pesan error
$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'duplicate') {
        $error = "Document type dengan nama tersebut sudah ada.";
    } elseif ($_GET['error'] == 'empty') {
        $error = "Silakan masukkan nama document type.";
    } elseif ($_GET['error'] == 'save_failed') {
        $error = "Gagal menyimpan ke file JSON. Periksa permission folder.";
    }
}
?>

<br /><br />
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-6 well well-lg">
        <h2><span class="glyphicon glyphicon-plus-sign"></span> Add New Document Type</h2>
        <p class="text-muted">Tambahkan tipe dokumen baru ke sistem</p>
        <hr>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label><strong>Document Type Name</strong> <span class="text-danger">*</span></label>
                <input type="text" class="form-control input-lg" name="name" required 
                       placeholder="Contoh: Quality Report, Training Record, dll">
                <p class="help-block">
                    <span class="glyphicon glyphicon-info-sign"></span> 
                    Nama tipe dokumen yang akan ditampilkan di menu navigation
                </p>
            </div>
            
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="has_submenu" id="has_submenu"> 
                        <strong>Dokumen ini memiliki Sub-Menu</strong>
                    </label>
                </div>
                <p class="help-block">
                    <span class="glyphicon glyphicon-info-sign"></span> 
                    Centang jika dokumen ini akan memiliki kategori sub-menu (misalnya: Production, Other, dll)
                </p>
            </div>

            <div class="alert alert-info" id="submenu_info" style="display:none;">
                <span class="glyphicon glyphicon-info-sign"></span> 
                <strong>Info:</strong> Setelah document type dibuat, Anda bisa menambahkan submenu melalui tombol <strong>"Add Submenu"</strong> di halaman config.
            </div>

            <hr>

            <h4><span class="glyphicon glyphicon-filter"></span> Filter Configuration</h4>
            <p class="text-muted">Pilih filter yang akan tersedia di halaman document list:</p>

            <div class="well">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_section" checked>
                                <strong>Section</strong>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_device" id="filter_device">
                                <strong>Device</strong>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_process" id="filter_process">
                                <strong>Process</strong>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-xs-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_status" checked>
                                <strong>Status</strong>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_category">
                                <strong>Category</strong>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success btn-lg btn-block">
                    <span class="glyphicon glyphicon-save"></span> Add Document Type
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
    // Show submenu info
    $('#has_submenu').change(function(){
        if($(this).is(':checked')) {
            $('#submenu_info').slideDown();
        } else {
            $('#submenu_info').slideUp();
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

<style>
.help-block {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
    margin-bottom: 0;
}
</style>