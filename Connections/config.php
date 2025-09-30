<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
 * Daftar konfigurasi (urut: prioritas)
 * - entry pertama: remote DB (sesuai yang kamu pakai sebelumnya)
 * - entry kedua: localhost fallback
 *
 * NOTE: Simpan credential ini dengan aman. Jangan commit ke repo public jika sensitif.
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

// coba koneksi sesuai urutan di $dbCandidates
foreach ($dbCandidates as $c) {
    $cfg = @mysqli_connect($c['hostname'], $c['username'], $c['password'], $c['database']);
    if ($cfg) {
        $config = $cfg;
        $usedConfig = $c;
        break;
    }
}

// jika tidak ada yang berhasil -> hentikan dan tampilkan error
if (!$config) {
    // gunakan mysqli_connect_error() untuk pesan yang benar
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// set variabel kompatibilitas (agar kode lama yang menggunakan variabel ini tetap bekerja)
$hostname_config = $usedConfig['hostname'];
$database_config = $usedConfig['database'];
$username_config = $usedConfig['username'];
$password_config = $usedConfig['password'];

// set charset agar aman untuk emoji & multibyte
mysqli_set_charset($config, "utf8mb4");

// kalau kamu ingin men-debug koneksi, uncomment baris berikut (Hanya di testing)
// echo "Connected to {$hostname_config} / DB: {$database_config}";

?>
