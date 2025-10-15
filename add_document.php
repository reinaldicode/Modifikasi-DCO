<?php
// ===== PROSES POST DULU =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $jsonFile = __DIR__ . '/data/document_types.json';
    $types = json_decode(file_get_contents($jsonFile), true);
    
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
        } else {
            // Generate ID dari nama
            $id = strtolower(str_replace(' ', '_', $name));
            
            // Tambahkan document type baru (DYNAMIC, NO LEGACY FILE)
            $newType = [
                'id' => $id,
                'name' => $name,
                'has_submenu' => $hasSubmenu,
                'submenu' => [],
                'filter_config' => $filterConfig
            ];
            
            $types[] = $newType;
            
            @file_put_contents($jsonFile, json_encode($types, JSON_PRETTY_PRINT));
            
            header("Location: conf_document.php?success=added");
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
    }
}
?>

<br /><br />
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-6 well well-lg">
        <h2>Tambah Document Type Baru</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-top:10px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Document Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" required 
                       placeholder="Contoh: PE, Quality Report, Training Record, dll">
                <p class="help-block">Masukkan nama document type yang unik. Halaman akan di-generate otomatis.</p>
            </div>
            
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="has_submenu" id="has_submenu"> 
                        <strong>Document Type ini memiliki Sub-Menu</strong>
                    </label>
                    <p class="help-block">
                        Centang jika document type ini akan memiliki submenu dropdown di navigation bar. 
                        Submenu bisa ditambahkan setelah document type dibuat.
                    </p>
                </div>
            </div>

            <hr>

            <h4><span class="glyphicon glyphicon-filter"></span> Filter yang Tersedia</h4>
            <p class="text-muted">Pilih filter yang akan ditampilkan di halaman document list:</p>

            <div class="well">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_section" checked>
                                <strong>Section</strong><br>
                                <small class="text-muted">Filter berdasarkan section/department</small>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_device" id="filter_device">
                                <strong>Device</strong><br>
                                <small class="text-muted">Filter berdasarkan device/mesin</small>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_process" id="filter_process">
                                <strong>Process</strong><br>
                                <small class="text-muted">Filter berdasarkan process (butuh Device)</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-xs-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_status" checked>
                                <strong>Status</strong><br>
                                <small class="text-muted">Filter berdasarkan status approval</small>
                            </label>
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="filter_category">
                                <strong>Category</strong><br>
                                <small class="text-muted">Filter berdasarkan Internal/External</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-success">
                <strong><span class="glyphicon glyphicon-ok-circle"></span> Document Type Baru = Dynamic Page</strong>
                <ul class="small" style="margin:5px 0 0 0;">
                    <li>Halaman dokumen akan di-generate <strong>otomatis</strong> dengan filter yang Anda pilih</li>
                    <li>Menu akan langsung muncul di navigation bar setelah disimpan</li>
                    <li>Tidak perlu setup teknis tambahan - sistem handle semua routing</li>
                </ul>
            </div>

            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-success btn-lg">
                    <span class="glyphicon glyphicon-save"></span> Tambah Document Type
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