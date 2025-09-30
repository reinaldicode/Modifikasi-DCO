<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

$hostname_config = "localhost";
$database_config = "doc";
$username_config = "root";
$password_config = "";

// 1. Buat koneksi dan simpan ke dalam variabel $config
$config = mysqli_connect($hostname_config, $username_config, $password_config, $database_config);

// 2. Periksa koneksi menggunakan blok if (cara yang lebih modern dan aman)
if (!$config) {
    // Hentikan eksekusi skrip dan tampilkan pesan error yang spesifik untuk koneksi
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// 3. (Opsional tapi sangat direkomendasikan) Atur karakter set ke utf8mb4
// Ini untuk memastikan semua karakter (termasuk emoji, dll.) bisa ditangani dengan baik.
mysqli_set_charset($config, "utf8mb4");
?>