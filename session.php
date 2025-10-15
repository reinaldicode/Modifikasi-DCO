<?php
// Selalu gunakan include_once untuk file koneksi dan session
// Ini untuk mencegah file dimuat berulang kali secara tidak sengaja.
include_once 'koneksi.php';

// Mulai session jika belum aktif
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah session 'username' ada dan tidak kosong
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: login.php'); // Jika tidak ada session, langsung redirect
    exit(); // Hentikan eksekusi skrip
}

// Ambil username dari session
$user_check = $_SESSION['username'];

// ================================================================
// PERBAIKAN: Menggunakan Prepared Statements untuk mencegah SQL Injection
// ================================================================

// 1. Siapkan query dengan placeholder (?)
$stmt = mysqli_prepare($link, "SELECT * FROM users WHERE username = ?");

// 2. Bind parameter ke placeholder
mysqli_stmt_bind_param($stmt, "s", $user_check); // "s" berarti tipenya string

// 3. Eksekusi statement
mysqli_stmt_execute($stmt);

// 4. Ambil hasilnya
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

// ================================================================

// Periksa apakah pengguna ditemukan di database
if (!$row) {
    // Jika user di session tidak ada di DB, hancurkan session dan redirect
    session_destroy();

    // ================================================================
    // PERBAIKAN: Koneksi ditutup HANYA jika user tidak valid
    // ================================================================
    mysqli_close($link); // Tutup koneksi
    header('Location: login.php');
    exit(); // Hentikan eksekusi
}

// Simpan informasi pengguna ke dalam variabel-variabel global (sesuai kode lama Anda)
$name  = $row['name'];
$state = $row['state'];
$nrp   = $row['username'];
$sec   = $row['section'];
$email = $row['email'];
$pass  = $row['password'];

// Variabel duplikat ini mungkin bisa disederhanakan nanti
$nrp2  = $row['username'];
$pass2 = $row['password'];

?>