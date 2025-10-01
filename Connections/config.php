<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
 * Konfigurasi koneksi database
 * - Pertama coba koneksi ke server kantor (192.168.132.36, user admin)
 * - Kalau gagal, fallback ke localhost (user root)
 */
$dbCandidates = [
    [
        'hostname' => '192.168.132.36',
        'username' => 'admin',
        'password' => 'SSItop123!',
        'database' => 'doc',
    ],
    [
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'doc',
    ],
];

$config = null;
$usedConfig = null;

// Coba koneksi sesuai urutan di $dbCandidates
foreach ($dbCandidates as $c) {
    $cfg = @mysqli_connect($c['hostname'], $c['username'], $c['password'], $c['database']);
    if ($cfg) {
        $config = $cfg;
        $usedConfig = $c;
        break;
    }
}

// Jika tidak ada yang berhasil -> hentikan dan tampilkan error
if (!$config) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Set variabel kompatibilitas (untuk kode lama)
$hostname_config = $usedConfig['hostname'];
$database_config = $usedConfig['database'];
$username_config = $usedConfig['username'];
$password_config = $usedConfig['password'];

// Set charset agar aman untuk multibyte/emoji
mysqli_set_charset($config, "utf8mb4");

// Set variabel $link untuk kompatibilitas dengan kode yang menggunakan $link
$link = $config;

// Debug opsional (aktifkan hanya di local testing)
// echo "<!-- Connected to {$hostname_config} / DB: {$database_config} -->";
?>