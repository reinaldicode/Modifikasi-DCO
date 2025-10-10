<?php
// ===== PROSES POST DULU SEBELUM ADA OUTPUT APAPUN =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $jsonFile = __DIR__ . '/data/document_types.json';
    $types = json_decode(file_get_contents($jsonFile), true);
    
    $idx = intval($_POST['idx']); // harus kirim idx via hidden input
    $name = trim($_POST['name']);
    
    if ($name !== '') {
        // Check duplicate
        $tmp = $types;
        unset($tmp[$idx]);
        $lower = array_map('strtolower', $tmp);
        
        if (in_array(strtolower($name), $lower)) {
            // Redirect dengan error
            header("Location: edit_document.php?idx=$idx&error=duplicate");
            exit;
        } else {
            $types[$idx] = $name;
            @file_put_contents($jsonFile, json_encode(array_values($types), JSON_PRETTY_PRINT));
            
            // Redirect dengan success
            header("Location: conf_document.php?success=updated");
            exit;
        }
    } else {
        // Redirect dengan error
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
?>

<br /><br />
<div class="row">
<div class="col-xs-1"></div>
<div class="col-xs-4 well well-lg">
 <h2>Edit Document Type</h2>

 <?php if ($error): ?>
    <div class="alert alert-danger" style="margin-top:10px;">
        <?php echo htmlspecialchars($error); ?>
    </div>
 <?php endif; ?>

 <form action="" method="POST">
     <input type="hidden" name="idx" value="<?php echo $idx; ?>">
     <table>
         <tr>
             <td>Type Name</td>
             <td>:</td>
             <td><input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($current); ?>" required></td>
         </tr>
         <tr>
             <td></td><td></td>
             <td><br/><input type="submit" value="Update" name="submit" class="btn btn-success"></td>
         </tr>
     </table>
 </form>

</div>
</div>