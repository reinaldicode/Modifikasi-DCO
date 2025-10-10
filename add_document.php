<?php
// ===== PROSES POST DULU SEBELUM ADA OUTPUT APAPUN =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Jangan include apapun yang bisa menghasilkan output
    $jsonFile = __DIR__ . '/data/document_types.json';
    
    // Pastikan file JSON ada
    if (!file_exists($jsonFile)) {
        @file_put_contents($jsonFile, json_encode([], JSON_PRETTY_PRINT));
    }
    
    $name = trim($_POST['name']);

    if ($name !== '') {
        $types = json_decode(@file_get_contents($jsonFile), true);
        if (!is_array($types)) $types = [];

        // Hindari duplikat (case-insensitive)
        $lower = array_map('strtolower', $types);
        if (!in_array(strtolower($name), $lower)) {
            $types[] = $name;
            
            // Simpan dan langsung redirect tanpa output
            @file_put_contents($jsonFile, json_encode($types, JSON_PRETTY_PRINT));
            
            header("Location: add_document.php?success=1");
            exit;
        } else {
            header("Location: add_document.php?error=duplicate");
            exit;
        }
    } else {
        header("Location: add_document.php?error=empty");
        exit;
    }
}

// ===== SETELAH REDIRECT, BARU LOAD CONFIG DAN HEADER =====
include('config_head.php');
include('header.php');

$error = '';
$success = '';

// Pesan sukses setelah redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "Document berhasil ditambahkan.";
}

// Pesan error
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'duplicate') {
        $error = "Tipe dokumen sudah ada.";
    } elseif ($_GET['error'] == 'empty') {
        $error = "Silakan masukkan nama dokumen.";
    }
}
?>

<br /><br />
<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-4 well well-lg">
        <h2>Tambah Jenis Dokumen</h2>

        <?php if ($success): ?>
            <div class="alert alert-success" style="margin-top:10px;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-top:10px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <table>
                <tr>
                    <td>Nama Type Dokumen</td>
                    <td>:</td>
                    <td><input type="text" class="form-control" name="name" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><br/><input type="submit" value="Save" name="submit" class="btn btn-success"></td>
                </tr>
            </table>
        </form>
    </div>
</div>