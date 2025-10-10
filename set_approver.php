<?php
include('header.php');
include('koneksi.php');
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get parameters from URL
$type   = isset($_GET['type']) ? $_GET['type'] : '';
$section= isset($_GET['section']) ? $_GET['section'] : '';
$id_doc = isset($_GET['id_doc']) ? $_GET['id_doc'] : '';
$iso    = isset($_GET['iso']) ? $_GET['iso'] : '';
$nodoc  = isset($_GET['nodoc']) ? $_GET['nodoc'] : '';
$title  = isset($_GET['title']) ? $_GET['title'] : '';

// Debug: Log received parameters
if (isset($_GET['debug']) && $_GET['debug'] == '1') {
    echo "<div class='alert alert-info'><strong>DEBUG - Received Parameters:</strong><br>";
    echo "Type: $type<br>Section: $section<br>ID Doc: $id_doc<br>ISO: $iso<br>No Doc: $nodoc<br>Title: $title</div>";
}
?>

<div class="row">
<div class="col-xs-1"></div>
<div class="col-xs-6 well well-lg">
<h2>Select Reviewer</h2>

<form action="" method="post" enctype="multipart/form-data">
<table class="table table-hover">
<?php 
if (!empty($section)) {
    $sql = "SELECT * FROM section WHERE sect_name='$section'";
    $q   = mysqli_query($link, $sql);

    if (!$q) {
        die("Query error: " . mysqli_error($link));
    }

    if (mysqli_num_rows($q) > 0) {
        $row = mysqli_fetch_array($q);
        $se  = isset($row['id_section']) ? $row['id_section'] : null;
    } else {
        $se = null;
        echo "<div class='alert alert-danger'>Section '$section' tidak ditemukan.</div>";
    }
}

// Query to get all approvers
$sql2 = "SELECT * FROM users WHERE state='approver' ORDER BY section,name";
$q2   = mysqli_query($link, $sql2);

if ($q2) {
    $total_approvers = mysqli_num_rows($q2);
    
    // Debug: Show total approvers found
    if (isset($_GET['debug']) && $_GET['debug'] == '1') {
        echo "<div class='alert alert-info'>Total Approvers Found: $total_approvers</div>";
    }
    
    if ($total_approvers == 0) {
        echo "<div class='alert alert-warning'>No approvers found in the system.</div>";
    }
    
    while ($row2 = mysqli_fetch_array($q2)) { 
        echo "
        <div class='input-group'>
            <tr>
                <td>
                <span class='input-group-addon2'>
                <input type='checkbox' name='item[]' id='item[]' 
                    value='".htmlspecialchars($row2['username'])."|".htmlspecialchars($row2['name'])."|".htmlspecialchars($row2['email'])."|".htmlspecialchars($row2['section'])."'>
                </td>
                <td>".htmlspecialchars($row2['name'])." &nbsp;</td>
                <td>".htmlspecialchars($row2['section'])." &nbsp;</td>
                <td>".htmlspecialchars($row2['email'])." &nbsp;</td>
                <td><input type='hidden' name='id_doc' value='".htmlspecialchars($id_doc)."'></td>
                <td><input type='hidden' name='type'   value='".htmlspecialchars($type)."'></td>
                <td><input type='hidden' name='iso'    value='".htmlspecialchars($iso)."'></td>
                <td><input type='hidden' name='nodoc'  value='".htmlspecialchars($nodoc)."'></td>
                <td><input type='hidden' name='title'  value='".htmlspecialchars($title)."'></td>
            </tr> 
        </div>
        ";
    }
}
?>
</table>
<input type="submit" value="Save" class="btn btn-success" name="save">
<input type="submit" value="Skip" class="btn btn-info" name="skip">
</form>
</div>
</div>

<?php 
// ========================================================================
// PROSES SAVE - DENGAN DEBUGGING LENGKAP
// ========================================================================
if (isset($_POST['save'])){
    
    // Debug log
    $debug_log = array();
    $debug_log[] = "=== START SAVE PROCESS ===";
    $debug_log[] = "Time: " . date('Y-m-d H:i:s');
    
    // Cek apakah ada item yang dipilih
    if (isset($_POST["item"]) && is_array($_POST["item"])) {
        $jumlah = count($_POST["item"]);
        $debug_log[] = "Total selected reviewers: $jumlah";
        
        // Validasi minimal 1 approver dipilih
        if ($jumlah == 0) {
            $debug_log[] = "ERROR: No reviewer selected";
            echo "<script language='javascript'>
                      alert('Please select at least one reviewer!');
                      history.back();
                  </script>";
            exit;
        }

        // Get form data
        $id_doc = isset($_POST['id_doc']) ? mysqli_real_escape_string($link, $_POST['id_doc']) : '';
        $type   = isset($_POST['type']) ? mysqli_real_escape_string($link, $_POST['type']) : '';
        $iso    = isset($_POST['iso']) ? mysqli_real_escape_string($link, $_POST['iso']) : '';
        $nodoc  = isset($_POST['nodoc']) ? mysqli_real_escape_string($link, $_POST['nodoc']) : '';
        $title  = isset($_POST['title']) ? mysqli_real_escape_string($link, $_POST['title']) : '';

        $debug_log[] = "Document Info - ID: $id_doc, Type: $type, ISO: $iso, No: $nodoc";
        
        // Initialize PHPMailer
        require 'PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer();
        $mail->IsSMTP();
        
        // Include SMTP configuration (CRITICAL!)
        if (file_exists('smtp.php')) {
            include "smtp.php";
            $debug_log[] = "SMTP config loaded from smtp.php";
        } else {
            // Fallback manual configuration
            $mail->Host     = "relay.sharp.co.jp";
            $mail->Port     = 25;
            $mail->SMTPAuth = false;
            $debug_log[] = "Using manual SMTP config (smtp.php not found)";
        }
        
        $mail->setFrom('dc_admin@ssi.sharp-world.com', 'Admin Document Online System');
        $mail->WordWrap = 50;
        $mail->IsHTML(true);
        
        // Enable SMTP debugging (level 2 for detailed output)
        $mail->SMTPDebug = 2; // Set to 0 in production
        $mail->Debugoutput = function($str, $level) use (&$debug_log) {
            $debug_log[] = "SMTP Debug: $str";
        };

        $debug_log[] = "=== Processing Selected Reviewers ===";
        
        // Counter untuk tracking
        $email_added_count = 0;
        $insert_success_count = 0;
        
        // Loop untuk setiap reviewer yang dipilih
        for ($i=0; $i < $jumlah; $i++) {
            if (isset($_POST["item"][$i])) {
                $id    = $_POST["item"][$i];
                $pecah = explode('|', $id);

                $debug_log[] = "Processing item $i: " . print_r($pecah, true);

                if (count($pecah) >= 4) {
                    $id_user = mysqli_real_escape_string($link, $pecah[0]);
                    $user_name = mysqli_real_escape_string($link, $pecah[1]);
                    $user_email = mysqli_real_escape_string($link, $pecah[2]);
                    $user_section = mysqli_real_escape_string($link, $pecah[3]);
                    
                    $debug_log[] = "Reviewer: $user_name ($user_email) - Section: $user_section";
                    
                    // Insert ke database dengan snapshot data
                    $sql_in  = "INSERT INTO rev_doc(id,id_doc,nrp,reviewer_name,reviewer_section,status,tgl_approve,reason) 
                                VALUES (0,'$id_doc','$id_user','$user_name','$user_section','Review','-','')";
                    
                    if (mysqli_query($link, $sql_in)) {
                        $insert_success_count++;
                        $debug_log[] = "✓ Database insert successful for $user_name";
                    } else {
                        $debug_log[] = "✗ Database insert FAILED for $user_name: " . mysqli_error($link);
                    }
                    
                    // Tambahkan email penerima
                    try {
                        $mail->AddAddress($user_email, $user_name);
                        $email_added_count++;
                        $debug_log[] = "✓ Email address added: $user_email";
                    } catch (Exception $e) {
                        $debug_log[] = "✗ Failed to add email: $user_email - Error: " . $e->getMessage();
                    }
                } else {
                    $debug_log[] = "✗ Invalid data format for item $i";
                }
            }
        }

        $debug_log[] = "=== Processing Default Approvers ===";
        
        // Tambahan approver default berdasarkan type dan iso
        if ($type=="Procedure" && $iso==1){
            $snap_query = "SELECT name, email, section FROM users WHERE username='000043'";
            $snap_result = mysqli_query($link, $snap_query);
            
            if ($snap_result && mysqli_num_rows($snap_result) > 0) {
                $snap_data = mysqli_fetch_array($snap_result);
                $snap_name = $snap_data['name'];
                $snap_email = $snap_data['email'];
                $snap_section = $snap_data['section'];
            } else {
                $snap_name = 'Kosnurdin';
                $snap_email = 'nurdin@ssi.sharp-world.com';
                $snap_section = 'QA Section';
            }
            
            $debug_log[] = "Default Approver ISO 1: $snap_name ($snap_email)";
            
            $sql_default = "INSERT INTO rev_doc(id,id_doc,nrp,reviewer_name,reviewer_section,status,tgl_approve,reason) 
                           VALUES ('','$id_doc','000043','$snap_name','$snap_section','Review','-','')";
            if (mysqli_query($link, $sql_default)) {
                $debug_log[] = "✓ Default approver inserted";
            } else {
                $debug_log[] = "✗ Default approver insert failed: " . mysqli_error($link);
            }
            
            $mail->AddAddress($snap_email, $snap_name);
            $email_added_count++;
        }
        
        if ($type=="Procedure" && $iso==2){
            $snap_query = "SELECT name, email, section FROM users WHERE username='gzbs103181'";
            $snap_result = mysqli_query($link, $snap_query);
            
            if ($snap_result && mysqli_num_rows($snap_result) > 0) {
                $snap_data = mysqli_fetch_array($snap_result);
                $snap_name = $snap_data['name'];
                $snap_email = $snap_data['email'];
                $snap_section = $snap_data['section'];
            } else {
                $snap_name = 'T. Takatahara';
                $snap_email = 'ikhsandio@ssi.sharp-world.com';
                $snap_section = 'Accounting Section';
            }
            
            $debug_log[] = "Default Approver ISO 2: $snap_name ($snap_email)";
            
            $sql_default = "INSERT INTO rev_doc(id,id_doc,nrp,reviewer_name,reviewer_section,status,tgl_approve,reason) 
                           VALUES ('','$id_doc','gzbs103181','$snap_name','$snap_section','Review','-','')";
            if (mysqli_query($link, $sql_default)) {
                $debug_log[] = "✓ Default approver inserted";
            } else {
                $debug_log[] = "✗ Default approver insert failed: " . mysqli_error($link);
            }
            
            $mail->AddAddress($snap_email, $snap_name);
            $email_added_count++;
        }
        
        if ($type=="Procedure" && $iso==3){
            $snap_query = "SELECT name, email, section FROM users WHERE username='000032'";
            $snap_result = mysqli_query($link, $snap_query);
            
            if ($snap_result && mysqli_num_rows($snap_result) > 0) {
                $snap_data = mysqli_fetch_array($snap_result);
                $snap_name = $snap_data['name'];
                $snap_email = $snap_data['email'];
                $snap_section = $snap_data['section'];
            } else {
                $snap_name = 'Ridwan W.';
                $snap_email = 'ikhsandio@ssi.sharp-world.com';
                $snap_section = 'Production Section';
            }
            
            $debug_log[] = "Default Approver ISO 3: $snap_name ($snap_email)";
            
            $sql_default = "INSERT INTO rev_doc(id,id_doc,nrp,reviewer_name,reviewer_section,status,tgl_approve,reason) 
                           VALUES ('','$id_doc','000032','$snap_name','$snap_section','Review','-','')";
            if (mysqli_query($link, $sql_default)) {
                $debug_log[] = "✓ Default approver inserted";
            } else {
                $debug_log[] = "✗ Default approver insert failed: " . mysqli_error($link);
            }
            
            $mail->AddAddress($snap_email, $snap_name);
            $email_added_count++;
        }

        $debug_log[] = "=== Email Summary ===";
        $debug_log[] = "Total emails to send: $email_added_count";
        $debug_log[] = "Database inserts successful: $insert_success_count";
        
        // Get all recipient addresses for debugging
        $all_recipients = $mail->getAllRecipientAddresses();
        $debug_log[] = "Recipients list: " . print_r($all_recipients, true);

        // Set email subject dan body
        $mail->Subject  = "Document to Review";
        $mail->Body     = "Attention Mr./Mrs. : Reviewer <br /> 
                          This following <span style='color:green'>".$type."</span> Document need to be 
                          <span style='color:blue'>reviewed</span> <br /> 
                          No. Document : ".$nodoc."<br /> 
                          Document Title : ".$title."<br />
                          Please Login into <a href='http://192.168.132.34/newdocument'>Document Online System</a>, Thank You";
        $mail->AltBody  = "Document to Review - No: ".$nodoc." - Title: ".$title;

        $debug_log[] = "=== Attempting to Send Email ===";
        
        // Kirim email dengan error handling
        if(!$mail->Send()){
            $debug_log[] = "✗✗✗ EMAIL SEND FAILED ✗✗✗";
            $debug_log[] = "Mailer Error: " . $mail->ErrorInfo;
            
            // Simpan log ke file
            file_put_contents('email_debug_log.txt', implode("\n", $debug_log) . "\n\n", FILE_APPEND);
            
            echo "<script language='javascript'>
                      alert('Approver Updated but Email FAILED to send!\\n\\nError: " . addslashes($mail->ErrorInfo) . "\\n\\nCheck email_debug_log.txt for details');
                      document.location='index_login.php';
                  </script>";
        } else {
            $debug_log[] = "✓✓✓ EMAIL SENT SUCCESSFULLY ✓✓✓";
            $debug_log[] = "Total recipients: $email_added_count";
            
            // Simpan log ke file
            file_put_contents('email_debug_log.txt', implode("\n", $debug_log) . "\n\n", FILE_APPEND);
            
            echo "<script language='javascript'>
                      alert('Approver Updated and Email Sent Successfully to $email_added_count recipients!');
                      document.location='index_login.php';
                  </script>";
        }
        
        $debug_log[] = "=== END SAVE PROCESS ===\n\n";
        
    } else {
        // Tidak ada item yang dipilih
        echo "<script language='javascript'>
                  alert('No reviewer selected! Please select at least one reviewer.');
                  history.back();
              </script>";
    }
}

// ========================================================================
// PROSES SKIP
// ========================================================================
if (isset($_POST['skip'])){
?>
    <script language='javascript'>
        document.location='upload.php';
    </script>
<?php
}
?>

<?php include('footer.php'); ?>