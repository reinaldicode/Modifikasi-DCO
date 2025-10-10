<?php
include('header.php');
include('config_head.php');
include 'koneksi.php';

// path ke json
$jsonFile = __DIR__ . '/data/document_types.json';

// pastikan file ada
if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode(["Procedure","WI","Form","MS & ROHS","Sample","MSDS","Manual","Obsolate"], JSON_PRETTY_PRINT));
}

$types = json_decode(file_get_contents($jsonFile), true);
?>

<br /><br />

<h2>Manage Document Types</h2>
<br />
<a href="add_document.php" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-plus"></span> Add Document Type</a>
<br /><br />

<table class="table table-hover">
<thead bgcolor="#00FFFF">
<tr>
    <td>No</td>
    <td>Document Type</td>
    <td>Action</td>
</tr>
</thead>
<tbody>
<?php
$i = 1;
if (is_array($types)) {
    foreach ($types as $idx => $t) {
        $enc = urlencode($t);
        echo "<tr>";
        echo "<td>{$i}</td>";
        echo "<td>" . htmlspecialchars($t) . "</td>";
        echo "<td>
                <a href=\"edit_document.php?idx={$idx}\" class=\"btn btn-info\"><span class=\"glyphicon glyphicon-edit\"></span> Edit</a>
                <a href=\"del_document.php?idx={$idx}\" class=\"btn btn-danger\" onclick=\"return confirm('Delete document type?')\"><span class=\"glyphicon glyphicon-remove\"></span> Delete</a>
              </td>";
        echo "</tr>";
        $i++;
    }
} else {
    echo "<tr><td colspan='3'>No types found</td></tr>";
}
?>
</tbody>
</table>
