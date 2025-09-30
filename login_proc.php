<?php
// login_proc.php (debug-enhanced)
// --------------------------------------------------
// NOTE: Hanya untuk environment testing. Setelah debug selesai,
// set $DEBUG = false dan jangan tampilkan credential di halaman publik.
// --------------------------------------------------

session_start(); // Starting Session

// ----- konfigurasi debug -----
$DEBUG = true; // ubah ke false setelah debugging selesai
error_reporting(E_ALL);
ini_set('display_errors', $DEBUG ? 1 : 0);
ini_set('display_startup_errors', $DEBUG ? 1 : 0);

// ----- setup log file -----
$logDir = __DIR__ . '/logs';
$logFile = $logDir . '/login_debug.log';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
if (!file_exists($logFile)) {
    @touch($logFile);
    @chmod($logFile, 0660);
}

function dbg($message) {
    global $DEBUG, $logFile;
    $ts = date('Y-m-d H:i:s');
    $line = "[$ts] $message\n";
    @error_log($line, 3, $logFile);
    if ($DEBUG) {
        echo nl2br(htmlspecialchars($line));
    }
}

// ----- include koneksi DB -----
$dbIncluded = false;
dbg("Mulai login_proc.php");
if (file_exists(__DIR__ . '/koneksi.php')) {
    dbg("Mencoba include 'koneksi.php'");
    include __DIR__ . '/koneksi.php';
    $dbIncluded = true;
} else {
    dbg("WARN: file 'koneksi.php' tidak ditemukan di " . __DIR__);
}

// cek variabel koneksi: kode kamu pakai $link
if (!isset($link) || !($link instanceof mysqli)) {
    dbg("WARN: Variabel \$link tidak ditemukan atau bukan mysqli. Tipe: " . (isset($link) ? gettype($link) : 'NULL'));
    // jika mau koneksi fallback (opsional), aktifkan block ini dan sesuaikan cred (HATI-HATI simpan credential secara aman)
    /*
    $host = "192.168.132.36";
    $user = "admin";
    $pass = "SSItop123!";
    $db   = "doc";
    $link = mysqli_connect($host, $user, $pass, $db);
    if (!$link) {
        dbg("Fallback koneksi gagal: " . mysqli_connect_error());
        if ($DEBUG) { die("Fallback connection failed: " . mysqli_connect_error()); }
    } else {
        dbg("Fallback koneksi berhasil ke DB: $db");
    }
    */
} else {
    dbg("DB connected. Host info: " . mysqli_get_host_info($link));
}

// ----- ambil input dengan aman -----
$raw_user = isset($_POST['username']) ? $_POST['username'] : '';
$raw_pass = isset($_POST['password']) ? $_POST['password'] : '';
// menjaga compat dengan trim sebelumnya yang memakai "gzb" sebagai charlist (tetap dipertahankan)
$username = addslashes(trim(strtolower($raw_user), "gzb"));
$password = addslashes(trim($raw_pass));

dbg("POST diterima. username raw: '" . substr($raw_user,0,200) . "' -> processed: '" . $username . "'. password length: " . strlen($password) . " (masked)");

// validasi awal
if (empty($username) || empty($password)) {
    dbg("Input validation failed: username atau password kosong.");
    if ($DEBUG) {
        echo "Input validation failed: username atau password kosong.";
        exit;
    } else {
        header("Location: login.php?error=empty");
        exit;
    }
}

// ----- LDAP config -----
$ldap_hostname = "ldap://ASIASHARP.com:389";
$ldap_username = "GZB" . $username . "@ASIASHARP.COM";
$ldap_password = $raw_pass; // untuk LDAP gunakan password asli tanpa addslashes
dbg("Mencoba koneksi LDAP ke $ldap_hostname untuk user $ldap_username");

// cek apakah extension ldap tersedia
$ldapbind = false;
$ldap_connection = null;
if (function_exists('ldap_connect')) {
    $ldap_connection = @ldap_connect($ldap_hostname);
    if ($ldap_connection === false || $ldap_connection === null) {
        dbg("LDAP connect gagal (resource null/false).");
    } else {
        // set protocol version dan options jika perlu
        @ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        @ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0);

        // lakukan bind (coba)
        $ldapbind = @ldap_bind($ldap_connection, $ldap_username, $ldap_password);
        if ($ldapbind) {
            dbg("LDAP bind SUKSES untuk $ldap_username");
        } else {
            $ldapErr = function_exists('ldap_error') ? @ldap_error($ldap_connection) : 'ldap_bind failed';
            dbg("LDAP bind GAGAL: $ldapErr");
        }
    }
} else {
    dbg("LDAP extension tidak tersedia di PHP (function ldap_connect tidak ditemukan). Akan lanjut cek DB internal.");
}

// ----- Jika LDAP gagal atau tidak tersedia => cek DB users -----
if (!$ldapbind) {
    dbg("LDAP tidak berhasil atau tidak tersedia => cek DB local users.");

    if (!isset($link) || !($link instanceof mysqli)) {
        dbg("ERROR: Tidak ada koneksi DB untuk mengecek user. Proses login dihentikan.");
        if ($DEBUG) { die("No DB connection available to validate user. Check koneksi.php"); }
        header("Location: login.php?error=serv");
        exit;
    }

    // safety: gunakan prepared statement
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        dbg("Prepare statement gagal: " . mysqli_error($link) . " | SQL: $query");
        if ($DEBUG) die("DB prepare failed: " . mysqli_error($link));
        header("Location: login.php?error=db");
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $username);
    $exec = mysqli_stmt_execute($stmt);
    if (!$exec) {
        dbg("Execute statement gagal: " . mysqli_stmt_error($stmt));
        if ($DEBUG) die("DB execute failed: " . mysqli_stmt_error($stmt));
        header("Location: login.php?error=db");
        exit;
    }
    $res = mysqli_stmt_get_result($stmt);
    if (!$res) {
        dbg("Get result gagal: " . mysqli_error($link));
        if ($DEBUG) die("Get result failed: " . mysqli_error($link));
        header("Location: login.php?error=db");
        exit;
    }
    $data = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $rows = $data ? 1 : 0;
    dbg("DB lookup selesai. rows = $rows");

    if ($rows > 0) {
        // existing logic: match password (plain) OR hashed if kamu implement
        // cek apakah kolom password ada
        $db_pass = isset($data['password']) ? $data['password'] : null;
        if ($db_pass === null) {
            dbg("Tidak menemukan kolom 'password' pada record user.");
            if ($DEBUG) { echo "User record found, but no password column."; exit; }
            header("Location: login.php?error=serv");
            exit;
        }

        // jika menggunakan hash: uncomment dan sesuaikan
        // if (password_verify($raw_pass, $data['password'])) { ... }
        if ($raw_pass == $db_pass || password_verify($raw_pass, $db_pass)) {
            // success
            dbg("Login DB SUCCESS untuk user $username (password cocok). Set session.");
            $_SESSION['username'] = $data['username'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['section'] = $data['section'];
            $_SESSION['state'] = $data['state'];
            $_SESSION['level'] = isset($data['level']) ? $data['level'] : null;
            $_SESSION['user_authentication'] = "valid";

            dbg("Session data set: username={$data['username']}, name={$data['name']}");

            if ($DEBUG) {
                dbg("DEBUG mode ON: Tidak redirect. Seharusnya redirect ke index_login.php");
                exit;
            } else {
                header("Location: index_login.php");
                exit;
            }
        } else {
            dbg("Login DB FAILED: password salah untuk user $username");
            if ($DEBUG) {
                echo "Password incorrect (DEBUG).";
                exit;
            } else {
                header("Location: login.php?error=Your Password Incorrect!");
                exit;
            }
        }
    } else {
        dbg("Login DB FAILED: user '$username' tidak ditemukan di tabel users.");
        if ($DEBUG) {
            echo "Account doesn't exist (DEBUG). Please contact administrator.";
            exit;
        } else {
            header("Location: login.php?error=Your account doesn't exist! Please contact administrator.");
            exit;
        }
    }
} else {
    // LDAP bind sukses -> login via LDAP
    dbg("LDAP bind sukses -> akan cek data user di DB (jika perlu) atau langsung set session.");

    // cek di DB apakah user ada (opsional)
    if (!isset($link) || !($link instanceof mysqli)) {
        dbg("WARN: Tidak ada koneksi DB. Akan set session hanya berdasarkan LDAP.");
    } else {
        $res2 = mysqli_prepare($link, "SELECT * FROM users WHERE username = ?");
        if ($res2) {
            mysqli_stmt_bind_param($res2, "s", $username);
            mysqli_stmt_execute($res2);
            $res2_r = mysqli_stmt_get_result($res2);
            $data2 = mysqli_fetch_array($res2_r, MYSQLI_ASSOC);
            $rows2 = $data2 ? 1 : 0;
            dbg("Cek DB setelah LDAP bind: rows2 = $rows2");
        } else {
            dbg("Prepare untuk cek DB setelah LDAP gagal: " . mysqli_error($link));
            $data2 = null;
            $rows2 = 0;
        }
    }

    if (isset($data2) && $rows2 == 1) {
        // set session dengan data DB
        $_SESSION['username'] = $data2['username'];
        $_SESSION['name'] = $data2['name'];
        $_SESSION['email'] = $data2['email'];
        $_SESSION['section'] = $data2['section'];
        $_SESSION['state'] = $data2['state'];
        $_SESSION['user_authentication'] = "valid";

        dbg("Session set from DB after LDAP: username={$data2['username']}");

        if ($DEBUG) {
            dbg("DEBUG mode ON: Tidak redirect. Seharusnya redirect ke my_doc.php");
            exit;
        } else {
            header("Location: my_doc.php");
            exit;
        }
    } else {
        // jika tidak ada di DB, tetap bisa set session minimal dari LDAP username
        $_SESSION['username'] = $username;
        $_SESSION['user_authentication'] = "valid";
        dbg("Session set from LDAP only (no DB record). username={$username}");

        if ($DEBUG) {
            dbg("DEBUG mode ON: Tidak redirect. Seharusnya redirect ke my_doc.php");
            exit;
        } else {
            header("Location: my_doc.php");
            exit;
        }
    }
}

// penutup
dbg("Akhir eksekusi login_proc.php");
?>
