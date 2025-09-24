<?php
include('header.php');
include('koneksi.php');
session_start();
?>

<?php 
$type   = isset($_GET['type']) ? $_GET['type'] : '';
$section= isset($_GET['section']) ? $_GET['section'] : '';
$id_doc = isset($_GET['id_doc']) ? $_GET['id_doc'] : '';
$iso    = isset($_GET['iso']) ? $_GET['iso'] : '';
$nodoc  = isset($_GET['nodoc']) ? $_GET['nodoc'] : '';
$title  = isset($_GET['title']) ? $_GET['title'] : '';
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

$sql2 = "SELECT * FROM users WHERE state='approver' ORDER BY section,name";
$q2   = mysqli_query($link, $sql2);

if ($q2) {
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
                <td><input type='hidden' name='email[]' value='".htmlspecialchars($row2['email'])."'></td>
                <td><input type='hidden' name='name[]'  value='".htmlspecialchars($row2['name'])."'></td>
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

<?php 
if (isset($_POST['save'])){
    if (isset($_POST["item"]) && is_array($_POST["item"])) {
        $jumlah = count($_POST["item"]);
        echo $jumlah;

        $id_doc = $_POST['id_doc'] ?? '';
        $type   = $_POST['type']   ?? '';
        $iso    = $_POST['iso']    ?? '';
        $nodoc  = $_POST['nodoc']  ?? '';
        $title  = $_POST['title']  ?? '';

        require 'PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host     = "relay.sharp.co.jp"; 
        $mail->setFrom('dc_admin@ssi.sharp-world.com');
        $mail->FromName = "Admin Document Online System";

        for ($i=0; $i < $jumlah; $i++) {
            if (isset($_POST["item"][$i])) {
                $id    = $_POST["item"][$i];
                $pecah = explode('|', $id);

                if (count($pecah) >= 4) {
                    $id_user = $pecah[0];
                    $user_name = $pecah[1];
                    $user_email = $pecah[2];
                    $user_section = $pecah[3];
                    
                    // **NEW: Insert dengan snapshot data approver**
                    $sql_in  = "INSERT INTO rev_doc(id,id_doc,nrp,reviewer_name,reviewer_section,status,tgl_approve,reason) 
                                VALUES (0,'$id_doc','$id_user','$user_name','$user_section','Review','-','')";
                    mysqli_query($link, $sql_in); 
                    $mail->AddAddress($user_email, $user_name);
                }
            }
        }

        // tambahan approver default berdasarkan type dan iso dengan snapshot
        if ($type=="Procedure" && $iso==1){
            // Get current data for snapshot
            $snap_query = "SELECT name, section FROM users WHERE username='000043'";
            $snap_result = mysqli_query($link, $snap_query);
            $snap_data = mysqli_fetch_array($snap_result);
            $snap_name = isset($snap_data['name']) ? $snap_data['name'] : 'Kosnurdin';
            $snap_section = isset($snap_data['section']) ? $snap_data['section'] : 'QA Section';
            
            mysqli_query($link, "INSERT INTO rev_doc(id,id_doc,nrp,reviewer_name,reviewer_section,status,tgl_approve,reason) 
                                 VALUES ('','$id_doc','000043','$snap_name','$snap_section','Review','-','')");
            $mail->AddAddress("nurdin@ssi.sharp-world.com",$snap_name);
        }
        if ($type=="Procedure" && $iso==2){
            $snap_query = "SELECT name, section FROM users WHERE username='gzbs103181'";
            $snap_result = mysqli_query($link, $snap_query);
            $snap_data = mysqli_fetch_array($snap_result);
            $snap_name = isset($snap_data['name']) ? $snap_data['name'] : 'T. Takatahara';
            $snap_section = isset($snap_data['section']) ? $snap_data['section'] : 'Accounting Section';
            
            mysqli_query($link, "INSERT INTO rev_doc(id,id_doc,nrp,reviewer_name,reviewer_section,status,tgl_approve,reason) 
                                 VALUES ('','$id_doc','gzbs103181','$snap_name','$snap_section','Review','-','')");
            $mail->AddAddress("ikhsandio@ssi.sharp-world.com",$snap_name);
        }
        if ($type=="Procedure" && $iso==3){
            $snap_query = "SELECT name, section FROM users WHERE username='000032'";
            $snap_result = mysqli_query($link, $snap_query);
            $snap_data = mysqli_fetch_array($snap_result);
            $snap_name = isset($snap_data['name']) ? $snap_data['name'] : 'Ridwan W.';
            $snap_section = isset($snap_data['section']) ? $snap_data['section'] : 'Production Section';
            
            mysqli_query($link, "INSERT INTO rev_doc(id,id_doc,nrp,reviewer_name,reviewer_section,status,tgl_approve,reason) 
                                 VALUES ('','$id_doc','000032','$snap_name','$snap_section','Review','-','')");
            $mail->AddAddress("ikhsandio@ssi.sharp-world.com",$snap_name);
        }

        $mail->Subject  = "Document to Review";
        $mail->Body     = "Attention Mr./Mrs. : Reviewer <br /> 
                          This following <span style='color:green'>".$type."</span> Document need to be 
                          <span style='color:blue'>reviewed</span> <br /> 
                          No. Document : ".$nodoc."<br /> 
                          Document Title : ".$title."<br />
                          Please Login into <a href='192.168.132.34/newdocument'>Document Online System</a>, Thank You";
        $mail->AltBody  = "This research is supported by MIS";

        if(!$mail->Send()){
            echo "Message was not sent <p>";
            echo "Mailer Error: " . $mail->ErrorInfo;
        }   
    }
?>
    <script language='javascript'>
        alert('Approver Updated');
        document.location='index_login.php';
    </script>
<?php
}

if (isset($_POST['skip'])){
?>
    <script language='javascript'>
        document.location='upload.php';
    </script>
<?php
}
?>