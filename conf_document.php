<?php
include('header.php');
include('config_head.php');
include 'koneksi.php';

// path ke json
$jsonFile = __DIR__ . '/data/document_types.json';

// pastikan file ada dengan struktur baru
if (!file_exists($jsonFile)) {
    $defaultData = [
        [
            'id' => 'procedure',
            'name' => 'Procedure',
            'has_submenu' => false,
            'use_custom_file' => false,
            'custom_file' => '',
            'submenu' => [],
            'filter_config' => [
                'section' => true,
                'device' => false,
                'process' => false,
                'status' => true,
                'category' => false
            ]
        ],
        [
            'id' => 'wi',
            'name' => 'WI',
            'has_submenu' => false,
            'use_custom_file' => false,
            'custom_file' => '',
            'submenu' => [],
            'filter_config' => [
                'section' => true,
                'device' => false,
                'process' => false,
                'status' => true,
                'category' => false
            ]
        ],
        [
            'id' => 'form',
            'name' => 'Form',
            'has_submenu' => false,
            'use_custom_file' => false,
            'custom_file' => '',
            'submenu' => [],
            'filter_config' => [
                'section' => true,
                'device' => false,
                'process' => false,
                'status' => true,
                'category' => false
            ]
        ]
    ];
    file_put_contents($jsonFile, json_encode($defaultData, JSON_PRETTY_PRINT));
}

$types = json_decode(file_get_contents($jsonFile), true);

// Tampilkan pesan sukses jika ada
$successMsg = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'updated') $successMsg = "Document type berhasil diupdate!";
    if ($_GET['success'] == 'deleted') $successMsg = "Document type berhasil dihapus!";
    if ($_GET['success'] == 'submenu_added') $successMsg = "Submenu berhasil ditambahkan!";
    if ($_GET['success'] == 'submenu_deleted') $successMsg = "Submenu berhasil dihapus!";
    if ($_GET['success'] == 'added') $successMsg = "Document type berhasil ditambahkan!";
}

// Helper function untuk menampilkan filter config
function displayFilterConfig($config) {
    if (empty($config)) return '<em class="text-muted">No filters</em>';
    
    $filters = [];
    if (!empty($config['section'])) $filters[] = '<span class="label label-primary">Section</span>';
    if (!empty($config['device'])) $filters[] = '<span class="label label-info">Device</span>';
    if (!empty($config['process'])) $filters[] = '<span class="label label-info">Process</span>';
    if (!empty($config['status'])) $filters[] = '<span class="label label-success">Status</span>';
    if (!empty($config['category'])) $filters[] = '<span class="label label-warning">Category</span>';
    
    return empty($filters) ? '<em class="text-muted">No filters</em>' : implode(' ', $filters);
}
?>

<br /><br />

<?php if ($successMsg): ?>
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo htmlspecialchars($successMsg); ?>
</div>
<?php endif; ?>

<h2>Manage Document Types</h2>
<p class="text-muted">Kelola tipe dokumen dan konfigurasi filter untuk sistem document control</p>
<br />
<a href="add_document.php" class="btn btn-primary btn-lg">
    <span class="glyphicon glyphicon-plus"></span> Add Document Type
</a>
<br /><br />

<table class="table table-hover table-bordered">
<thead bgcolor="#00FFFF">
<tr>
    <td width="5%">No</td>
    <td width="15%">Document Type</td>
    <td width="15%">Custom File</td>
    <td width="10%">Has Submenu</td>
    <td width="15%">Filter Config</td>
    <td width="25%">Submenus</td>
    <td width="15%">Action</td>
</tr>
</thead>
<tbody>
<?php
$i = 1;
if (is_array($types)) {
    foreach ($types as $idx => $t) {
        $hasSubmenu = isset($t['has_submenu']) && $t['has_submenu'] === true;
        $submenuCount = $hasSubmenu && !empty($t['submenu']) ? count($t['submenu']) : 0;
        $useCustomFile = isset($t['use_custom_file']) && $t['use_custom_file'] === true;
        $customFile = isset($t['custom_file']) ? $t['custom_file'] : '';
        $filterConfig = isset($t['filter_config']) ? $t['filter_config'] : [];
        
        echo "<tr>";
        echo "<td>{$i}</td>";
        echo "<td><strong>" . htmlspecialchars($t['name']) . "</strong></td>";
        
        // Kolom Custom File
        echo "<td>";
        if ($useCustomFile && !empty($customFile)) {
            $fileExists = file_exists(__DIR__ . '/' . $customFile);
            if ($fileExists) {
                echo '<span class="label label-success"><span class="glyphicon glyphicon-ok"></span> ' . htmlspecialchars($customFile) . '</span>';
            } else {
                echo '<span class="label label-danger"><span class="glyphicon glyphicon-remove"></span> ' . htmlspecialchars($customFile) . '</span>';
            }
        } else {
            echo '<span class="text-muted">Dynamic Page</span>';
        }
        echo "</td>";
        
        echo "<td>" . ($hasSubmenu ? '<span class="label label-success">Yes ('.$submenuCount.')</span>' : '<span class="label label-default">No</span>') . "</td>";
        
        // Kolom Filter Config
        echo "<td>" . displayFilterConfig($filterConfig) . "</td>";
        
        // Kolom Submenus
        echo "<td>";
        
        if ($hasSubmenu && $submenuCount > 0) {
            echo "<ul style='margin:0; padding-left:20px; font-size:12px;'>";
            foreach ($t['submenu'] as $subIdx => $sub) {
                $subUseCustomFile = isset($sub['use_custom_file']) && $sub['use_custom_file'] === true;
                $subCustomFile = isset($sub['custom_file']) ? $sub['custom_file'] : '';
                $subFilterConfig = isset($sub['filter_config']) ? $sub['filter_config'] : [];
                
                echo "<li style='margin-bottom:8px;'><strong>" . htmlspecialchars($sub['name']) . "</strong><br>";
                
                // File info
                if ($subUseCustomFile && !empty($subCustomFile)) {
                    $subFileExists = file_exists(__DIR__ . '/' . $subCustomFile);
                    if ($subFileExists) {
                        echo '<span class="label label-xs label-info">' . htmlspecialchars($subCustomFile) . '</span> ';
                    } else {
                        echo '<span class="label label-xs label-danger">' . htmlspecialchars($subCustomFile) . ' ✗</span> ';
                    }
                } else {
                    echo '<span class="label label-xs label-default">dynamic</span> ';
                }
                
                // Filter info (simplified)
                echo "<br><small>" . displayFilterConfig($subFilterConfig) . "</small><br>";
                
                // Action buttons
                echo "<a href='edit_submenu.php?idx={$idx}&subidx={$subIdx}' class='btn btn-xs btn-warning' title='Edit Submenu' style='margin-top:4px;'>
                      <span class='glyphicon glyphicon-edit'></span></a> ";
                echo "<a href='del_submenu.php?idx={$idx}&subidx={$subIdx}' class='btn btn-xs btn-danger' onclick=\"return confirm('Delete submenu?')\" title='Delete Submenu'>
                      <span class='glyphicon glyphicon-trash'></span></a>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<em class='text-muted'>No submenu</em>";
        }
        
        echo "</td>";
        
        // Action buttons
        echo "<td>
                <a href=\"edit_document.php?idx={$idx}\" class=\"btn btn-info btn-sm\" style='margin-bottom:4px;'>
                    <span class=\"glyphicon glyphicon-edit\"></span> Edit
                </a><br>
                <a href=\"add_submenu.php?idx={$idx}\" class=\"btn btn-success btn-sm\" style='margin-bottom:4px;'>
                    <span class=\"glyphicon glyphicon-plus\"></span> Add Submenu
                </a><br>
                <a href=\"del_document.php?idx={$idx}\" class=\"btn btn-danger btn-sm\" onclick=\"return confirm('Delete document type and all submenus?')\">
                    <span class=\"glyphicon glyphicon-remove\"></span> Delete
                </a>
              </td>";
        echo "</tr>";
        $i++;
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No types found</td></tr>";
}
?>
</tbody>
</table>

<div class="alert alert-info">
    <strong><span class="glyphicon glyphicon-info-sign"></span> Informasi:</strong>
    <ul>
        <li><strong>Custom File:</strong> Jika dicentang, menu akan link ke file PHP terpisah yang sudah ada</li>
        <li><strong>Dynamic Page:</strong> Jika tidak menggunakan custom file, sistem akan otomatis generate halaman dengan filter yang dikonfigurasi</li>
        <li><strong>Filter Config:</strong> Menunjukkan filter dropdown yang aktif untuk document type tersebut</li>
        <li><strong>Has Submenu:</strong> Jika Yes, document type akan memiliki sub-menu dropdown di navigation</li>
        <li><strong>Submenu:</strong> Setiap submenu bisa memiliki konfigurasi filter yang berbeda</li>
    </ul>
</div>

<style>
.label-xs {
    font-size: 10px;
    padding: 2px 5px;
}
</style>