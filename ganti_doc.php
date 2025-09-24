<?php
// Gunakan use statement untuk PHPMailer modern di awal file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'header.php';
include 'koneksi.php';

// Cek apakah form telah di-submit
if (isset($_POST['upload'])) {
    
    // Ambil data dari POST dengan aman
    $drf = isset($_POST['drf']) ? (int)$_POST['drf'] : 0;
    $type = $_POST['type'] ?? '';
    $nodoc = $_POST['nodoc'] ?? '';
    $title = $_POST['title'] ?? '';
    $filelama = $_POST['lama'] ?? '';

    if ($drf == 0) {
        die("Error: DRF tidak valid.");
    }

    $nma_file = '';
    $nma_file2 = '';

    // --- Proses Upload File Dokumen (*.pdf, *.xlsx) ---
    if (isset($_FILES['baru']) && $_FILES['baru']['error'] == UPLOAD_ERR_OK) {
        $tmp_file = $_FILES['baru']['tmp_name'];
        $nma_file = basename($_FILES['baru']['name']);
        
        // Tentukan folder tujuan berdasarkan tipe dokumen
        $target_dir = rtrim($type, '/') . '/'; // Contoh: "WI/"
        
        // Buat folder jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Pindahkan file yang di-upload ke folder tujuan
        if (move_uploaded_file($tmp_file, $target_dir . $nma_file)) {
            echo "File Dokumen $nma_file Berhasil Di-upload.<br>";
        } else {
            echo "Error: File Dokumen gagal di-upload.<br>";
        }
    }

    // --- Proses Upload File Master ---
    if (isset($_FILES['masterbaru']) && $_FILES['masterbaru']['error'] == UPLOAD_ERR_OK) {
        $tmp_file2 = $_FILES['masterbaru']['tmp_name'];
        $nma_file2 = basename($_FILES['masterbaru']['name']);
        $master_dir = 'master/';

        // Buat folder 'master' jika belum ada
        if (!is_dir($master_dir)) {
            mkdir($master_dir, 0777, true);
        }

        if (move_uploaded_file($tmp_file2, $master_dir . $nma_file2)) {
            echo "File Master $nma_file2 Berhasil Di-upload.<br>";
            // Hapus file master lama jika ada
            if (!empty($filelama) && file_exists($master_dir . $filelama)) {
                // unlink($master_dir . $filelama);
            }
        } else {
            echo "Error: File Master gagal di-upload.<br>";
        }
    }

    // --- Update Database ---
    // Update tabel docu, hanya update kolom file jika file baru di-upload
    if (!empty($nma_file)) {
        $query_docu = "UPDATE docu SET file='$nma_file', status='Review' WHERE no_drf=$drf";
        mysqli_query($link, $query_docu);
    }
    // Update tabel rev_doc
    $query_rev = "UPDATE rev_doc SET status='Review' WHERE id_doc='$drf' AND status='Pending'";
    mysqli_query($link, $query_rev);


    // --- Kirim Email Notifikasi dengan PHPMailer Modern ---
    require 'vendor/autoload.php'; // Pastikan path ini benar
    $mail = new PHPMailer(true);

    try {
        // Pengaturan Server
        include 'smtp.php'; // File ini harusnya berisi pengaturan SMTP untuk $mail
        $mail->IsSMTP();
        // $mail->Host = "relay.sharp.co.jp";
        
        // Pengirim
        $mail->setFrom('dc_admin@ssi.sharp-world.com', 'Admin Document Online System');

        // Penerima (approver yang statusnya pending dan admin)
        $sql2 = "SELECT DISTINCT users.name, users.email 
                 FROM rev_doc 
                 JOIN users ON users.username = rev_doc.nrp 
                 WHERE (rev_doc.id_doc ='$drf' AND rev_doc.status='Pending') OR users.state = 'admin'";
        
        $res2 = mysqli_query($link, $sql2);

        // Periksa hasil query dengan benar
        if ($res2 && mysqli_num_rows($res2) > 0) {
            while ($data2 = mysqli_fetch_assoc($res2)) {
                $mail->addAddress($data2['email'], $data2['name']);
            }
        }
        
        // Konten Email
        $mail->isHTML(true);
        $mail->Subject = "Revisi Dokumen";
        $mail->Body    = "Attention Mr./Mrs. : Reviewer <br /> This following document has been changed, please consider to re-review 
                          <br /> No Document : ".$nodoc."<br /> Title : ".$title."<br /> Please Login into <a href='192.168.132.15/document'>Document Online System</a>, Thank You";

        $mail->send();
        
        // Redirect setelah semua proses selesai
        echo "<script>
                alert('File changed successfully!');
                document.location='my_doc.php';
              </script>";

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    
} else {
    // Tampilkan pesan jika halaman diakses tanpa submit form
    echo "<div style='text-align:center; margin-top: 50px;'>Tidak ada file yang di-upload. Silakan kembali dan coba lagi.</div>";
}
?>