<?php
// login_proc.php - Resolved version
// Menggabungkan fitur debug dari mydocnotif dengan logic sederhana dari main
session_start(); // Starting Session

// ----- Konfigurasi debug -----
$DEBUG = false; // ubah ke true untuk debugging, false untuk production
error_reporting(E_ALL);
ini_set('display_errors', $DEBUG ? 1 : 0);
ini_set('display_startup_errors', $DEBUG ? 1 : 0);

// ----- Setup log file (opsional untuk debugging) -----
if ($DEBUG) {
    $logDir = __DIR__ . '/logs';
    $logFile = $logDir . '/login_debug.log';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    if (!file_exists($logFile)) {
        @touch($logFile);
        @chmod($logFile, 0660);
    }
}

function dbg($message)
{
    global $DEBUG, $logFile;
    if (!$DEBUG) return;
    
    $ts = date('Y-m-d H:i:s');
    $line = "[$ts] $message\n";
    
    if (isset($logFile)) {
        @error_log($line, 3, $logFile);
    }
    echo nl2br(htmlspecialchars($line));
}

// ----- Include koneksi DB -----
dbg("Mulai login_proc.php");
include __DIR__ . '/koneksi.php';

// Cek variabel koneksi
if (!isset($link) || !($link instanceof mysqli)) {
    dbg("ERROR: Variabel \$link tidak tersedia atau bukan mysqli");
    if ($DEBUG) {
        die("Database connection not available. Check koneksi.php");
    }
    header("Location: login.php?error=Database connection failed");
    exit;
}

dbg("DB connected. Host info: " . mysqli_get_host_info($link));

// ----- Ambil input dengan aman -----
$raw_user = isset($_POST['username']) ? $_POST['username'] : '';
$raw_pass = isset($_POST['password']) ? $_POST['password'] : '';

// Trim dengan charlist "gzb" untuk kompatibilitas dengan sistem lama
$username = addslashes(trim(strtolower($raw_user), "gzb"));
$password = addslashes(trim($raw_pass));

dbg("POST diterima. username: '$username', password length: " . strlen($password));

// Validasi input
if (empty($username) || empty($password)) {
    dbg("Input validation failed: username atau password kosong");
    if ($DEBUG) {
        die("Input validation failed: username atau password kosong");
    }
    header("Location: login.php?error=Username or Password is invalid");
    exit;
}

// ----- LDAP Configuration -----
$ldap_hostname = "ldap://ASIASHARP.com:389";
$ldap_username = "GZB" . $username . "@ASIASHARP.COM";
$ldap_password = $raw_pass; // gunakan password asli untuk LDAP

dbg("Mencoba koneksi LDAP ke $ldap_hostname untuk user $ldap_username");

$ldapbind = false;
$ldap_connection = null;

// Cek apakah LDAP extension tersedia
if (function_exists('ldap_connect')) {
    $ldap_connection = @ldap_connect($ldap_hostname);
    
    if ($ldap_connection) {
        // Set LDAP options
        @ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        @ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0);
        
        // Coba bind
        $ldapbind = @ldap_bind($ldap_connection, $ldap_username, $ldap_password);
        
        if ($ldapbind) {
            dbg("LDAP bind SUKSES untuk $ldap_username");
        } else {
            $ldapErr = @ldap_error($ldap_connection);
            dbg("LDAP bind GAGAL: $ldapErr");
        }
    } else {
        dbg("LDAP connect gagal");
    }
} else {
    dbg("LDAP extension tidak tersedia. Akan cek DB internal");
}

// ----- Jika LDAP gagal => cek DB users -----
if (!$ldapbind) {
    dbg("LDAP tidak berhasil => cek DB local users");
    
    // Gunakan prepared statement untuk keamanan
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($link, $query);
    
    if (!$stmt) {
        dbg("Prepare statement gagal: " . mysqli_error($link));
        if ($DEBUG) {
            die("DB prepare failed: " . mysqli_error($link));
        }
        header("Location: login.php?error=Database error");
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $rows = $data ? 1 : 0;
    
    dbg("DB lookup selesai. rows = $rows");
    
    if ($rows > 0) {
        $db_pass = isset($data['password']) ? $data['password'] : null;
        
        if ($db_pass === null) {
            dbg("Kolom password tidak ditemukan");
            if ($DEBUG) {
                die("User record found, but no password column");
            }
            header("Location: login.php?error=Server error");
            exit;
        }
        
        // Cek password (support plain text dan hash)
        if ($raw_pass == $db_pass || password_verify($raw_pass, $db_pass)) {
            dbg("Login DB SUCCESS untuk user $username");
            
            // Set session
            $_SESSION['username'] = $data['username'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['section'] = $data['section'];
            $_SESSION['state'] = $data['state'];
            $_SESSION['level'] = isset($data['level']) ? $data['level'] : null;
            $_SESSION['user_authentication'] = "valid";
            
            dbg("Session set: username={$data['username']}, name={$data['name']}");
            
            if ($DEBUG) {
                dbg("DEBUG mode: Seharusnya redirect ke index_login.php");
                exit;
            }
            
            header("Location: index_login.php");
            exit;
        } else {
            dbg("Login DB FAILED: password salah");
            if ($DEBUG) {
                die("Password incorrect");
            }
            header("Location: login.php?error=Your Password Incorrect!");
            exit;
        }
    } else {
        dbg("Login DB FAILED: user tidak ditemukan");
        if ($DEBUG) {
            die("Account doesn't exist");
        }
        header("Location: login.php?error=Your account doesn't exist! Please contact administrator.");
        exit;
    }
    
} else {
    // ----- LDAP bind sukses -----
    dbg("LDAP bind sukses => cek data user di DB");
    
    // Cek apakah user ada di DB
    $stmt2 = mysqli_prepare($link, "SELECT * FROM users WHERE username = ?");
    
    if ($stmt2) {
        mysqli_stmt_bind_param($stmt2, "s", $username);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $data2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        $rows2 = $data2 ? 1 : 0;
        
        dbg("Cek DB setelah LDAP bind: rows = $rows2");
    } else {
        dbg("Prepare untuk cek DB setelah LDAP gagal");
        $data2 = null;
        $rows2 = 0;
    }
    
    if ($rows2 == 1 && isset($data2)) {
        // Set session dengan data dari DB
        $_SESSION['username'] = $data2['username'];
        $_SESSION['name'] = $data2['name'];
        $_SESSION['email'] = $data2['email'];
        $_SESSION['section'] = $data2['section'];
        $_SESSION['state'] = $data2['state'];
        $_SESSION['user_authentication'] = "valid";
        
        dbg("Session set from DB: username={$data2['username']}");
        
        if ($DEBUG) {
            dbg("DEBUG mode: Seharusnya redirect ke my_doc.php");
            exit;
        }
        
        header("Location: my_doc.php");
        exit;
    } else {
        // Set session minimal dari LDAP username
        $_SESSION['username'] = $username;
        $_SESSION['user_authentication'] = "valid";
        
        dbg("Session set from LDAP only: username=$username");
        
        if ($DEBUG) {
            dbg("DEBUG mode: Seharusnya redirect ke my_doc.php");
            exit;
        }
        
        header("Location: my_doc.php");
        exit;
    }
}

dbg("Akhir eksekusi login_proc.php");
?>