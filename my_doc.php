<?php

// session_start();
// if(empty($_SESSION['user_authentication']))
// {
//  header("location:login.php");
//  die();
// }
// if($_SESSION['user_authentication'] !="valid")
// {
//  header("location:login.php");
//  die();
// }

include 'header.php';
include 'koneksi.php';
extract($_REQUEST);

// Fungsi untuk menghitung notifikasi
function getNotificationCounts($link, $nrp, $state) {
    $counts = array('WI' => 0, 'Procedure' => 0, 'Form' => 0, 'total' => 0);
    
    $types = ['WI', 'Procedure', 'Form'];
    
    foreach($types as $type) {
        if($state == 'Admin') {
            // Admin melihat semua dokumen yang butuh review atau overdue
            $sql = "SELECT COUNT(*) as count FROM docu WHERE doc_type='$type' AND 
                    (status='Review' OR (DATEDIFF(NOW(), tgl_upload) >= 1 AND status='Review'))";
        } elseif($state == 'Originator') {
            // Originator melihat dokumen miliknya yang ada update
            $sql = "SELECT COUNT(*) as count FROM docu WHERE doc_type='$type' AND 
                    user_id='$nrp' AND status IN ('Review','Pending','Approved')";
        } elseif($state == 'Approver') {
            // Approver melihat dokumen yang perlu di-approve
            $sql = "SELECT COUNT(*) as count FROM docu,rev_doc WHERE docu.doc_type='$type' AND
                    docu.status='Review' AND rev_doc.status='Review' AND 
                    docu.no_drf=rev_doc.id_doc AND rev_doc.nrp='$nrp'";
        }
        
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $counts[$type] = (int)$row['count'];
        $counts['total'] += $counts[$type];
    }
    
    return $counts;
}

// Fungsi untuk mendapatkan notifikasi terbaru
function getRecentNotifications($link, $nrp, $state, $limit = 10) {
    $notifications = array();
    
    if($state == 'Admin') {
        $sql = "SELECT no_drf, no_doc, title, doc_type, status, tgl_upload, 
                DATEDIFF(NOW(), tgl_upload) as days_passed
                FROM docu WHERE status IN ('Review','Pending') 
                ORDER BY tgl_upload DESC LIMIT $limit";
    } elseif($state == 'Originator') {
        $sql = "SELECT no_drf, no_doc, title, doc_type, status, tgl_upload,
                DATEDIFF(NOW(), tgl_upload) as days_passed
                FROM docu WHERE user_id='$nrp' AND status IN ('Review','Pending','Approved')
                ORDER BY tgl_upload DESC LIMIT $limit";
    } elseif($state == 'Approver') {
        $sql = "SELECT docu.no_drf, docu.no_doc, docu.title, docu.doc_type, docu.status, docu.tgl_upload,
                DATEDIFF(NOW(), docu.tgl_upload) as days_passed
                FROM docu,rev_doc WHERE docu.status='Review' AND rev_doc.status='Review' 
                AND docu.no_drf=rev_doc.id_doc AND rev_doc.nrp='$nrp'
                ORDER BY docu.tgl_upload DESC LIMIT $limit";
    }
    
    $result = mysqli_query($link, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
    
    return $notifications;
}

// Ambil data notifikasi
$notifCounts = getNotificationCounts($link, $nrp, $state);
$recentNotifications = getRecentNotifications($link, $nrp, $state);

?>

<style>
.notification-badge {
    position: relative;
    display: inline-block;
}

.notification-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    animation: pulse 2s infinite;
    z-index: 1000;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.notification-panel {
    position: fixed;
    top: 70px;
    right: 20px;
    width: 350px;
    max-height: 400px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    z-index: 1050;
    display: none;
    border: 1px solid #dee2e6;
}

.notification-header {
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.notification-body {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    padding: 12px 15px;
    border-bottom: 1px solid #f1f3f4;
    cursor: pointer;
    transition: background-color 0.2s;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.urgent {
    background-color: #fff3cd;
    border-left: 3px solid #ffc107;
}

.notification-item.overdue {
    background-color: #f8d7da;
    border-left: 3px solid #dc3545;
}

.notification-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.summary-item:last-child {
    margin-bottom: 0;
}

.dropdown-with-badge {
    position: relative;
}

.dropdown-badge {
    position: absolute;
    top: 5px;
    right: 25px;
    background: #dc3545;
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 10px;
    font-weight: bold;
    z-index: 10;
}

.auto-refresh-indicator {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 12px;
    display: none;
}
</style>

<div id="profile">
<div class="alert alert-info" role="alert">
    <b id="welcome">Welcome : <i><?php echo $name; ?>, anda login sebagai <?php echo $state;?></i></b>
    
    <!-- Notification Bell -->
    <div style="float: right;">
        <button class="btn btn-outline-primary notification-badge" id="notificationBell" title="Notifications">
            <span class="glyphicon glyphicon-bell"></span>
            <?php if($notifCounts['total'] > 0): ?>
            <span class="notification-count" id="totalNotifications"><?php echo $notifCounts['total']; ?></span>
            <?php endif; ?>
        </button>
    </div>
</div>
</div>

<!-- Notification Summary Dashboard -->
<?php if($notifCounts['total'] > 0): ?>
<div class="notification-summary">
    <h5><span class="glyphicon glyphicon-dashboard"></span> Notification Summary</h5>
    <div class="summary-item">
        <span><span class="glyphicon glyphicon-file"></span> WI Documents</span>
        <span class="badge" style="background: white; color: #333;"><?php echo $notifCounts['WI']; ?></span>
    </div>
    <div class="summary-item">
        <span><span class="glyphicon glyphicon-list-alt"></span> Procedures</span>
        <span class="badge" style="background: white; color: #333;"><?php echo $notifCounts['Procedure']; ?></span>
    </div>
    <div class="summary-item">
        <span><span class="glyphicon glyphicon-th-list"></span> Forms</span>
        <span class="badge" style="background: white; color: #333;"><?php echo $notifCounts['Form']; ?></span>
    </div>
</div>
<?php endif; ?>

<!-- Notification Panel -->
<div class="notification-panel" id="notificationPanel">
    <div class="notification-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h6 style="margin: 0;"><span class="glyphicon glyphicon-bell"></span> Recent Updates</h6>
            <button class="btn btn-xs btn-default" onclick="$('#notificationPanel').fadeOut();">×</button>
        </div>
    </div>
    <div class="notification-body" id="notificationBody">
        <?php if(empty($recentNotifications)): ?>
        <div class="notification-item">
            <small class="text-muted">No recent notifications</small>
        </div>
        <?php else: ?>
        <?php foreach($recentNotifications as $notif): ?>
        <div class="notification-item <?php echo ($notif['days_passed'] >= 3) ? 'overdue' : (($notif['days_passed'] >= 1) ? 'urgent' : ''); ?>" 
             onclick="window.location.href='detail.php?drf=<?php echo $notif['no_drf']; ?>&no_doc=<?php echo $notif['no_doc']; ?>'">
            <div style="display: flex; justify-content: space-between;">
                <strong><?php echo $notif['doc_type']; ?> - <?php echo $notif['status']; ?></strong>
                <small class="text-muted"><?php echo $notif['days_passed']; ?> days ago</small>
            </div>
            <small><?php echo $notif['no_doc']; ?>: <?php echo substr($notif['title'], 0, 50); ?>...</small>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    $('.upload-file').click(function () {
        $('span.user-id').text($(this).data('id'));
        var Id = $(this).data('id');
     $(".modal-body #drf").val( Id );
     
     var nodoc = $(this).data('nodoc');
     $(".modal-body #nodoc").val( nodoc );

     var type = $(this).data('type');
     $(".modal-body #type").val( type );

      var lama = $(this).data('lama');
     $(".modal-body #lama").val( lama );

     var title = $(this).data('title');
     $(".modal-body #title").val( title );
    });

    $('.sec-file').click(function () {
        $('span.user-id').text($(this).data('id'));
        var Id = $(this).data('id');
     $(".modal-body #drf").val( Id );
     
     var lama = $(this).data('lama');
     $(".modal-body #lama").val( lama );

      var type = $(this).data('type');
     $(".modal-body #type").val( type );

      var rev = $(this).data('rev');
     $(".modal-body #rev").val( rev );

     var status = $(this).data('status');
     $(".modal-body #status").val( status );
    });

    // Notification System
    $('#notificationBell').click(function(e) {
        e.preventDefault();
        $('#notificationPanel').fadeToggle();
    });

    // Close notification panel when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('#notificationPanel, #notificationBell').length) {
            $('#notificationPanel').fadeOut();
        }
    });

    // Auto refresh every 60 seconds
    setInterval(function() {
        checkForUpdates();
    }, 60000);

    function checkForUpdates() {
        $('.auto-refresh-indicator').fadeIn();
        
        // AJAX call to check for new notifications
        $.ajax({
            url: 'check_notifications.php',
            method: 'GET',
            success: function(data) {
                if(data.hasUpdates) {
                    // Update notification counts
                    $('#totalNotifications').text(data.totalCount);
                    
                    // Update notification panel
                    updateNotificationPanel(data.notifications);
                    
                    // Show toast notification
                    showToastNotification('New updates available!');
                }
            },
            complete: function() {
                $('.auto-refresh-indicator').fadeOut();
            }
        });
    }

    function showToastNotification(message) {
        var toast = $('<div class="alert alert-info alert-dismissible" style="position: fixed; top: 20px; right: 20px; z-index: 2000; min-width: 300px;">' +
                     '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>' +
                     message + '</div>');
        
        $('body').append(toast);
        
        setTimeout(function() {
            toast.alert('close');
        }, 5000);
    }
});
</script>

<h2>Manage Document</h2>

<form action="" method="GET" >
    <div class="col-sm-4">
        <div class="dropdown-with-badge">
            <select name="tipe" class="form-control">
                <option value="-">--- Select Type ---</option>
                <option value="WI" <?php echo (isset($tipe) && $tipe == 'WI') ? 'selected' : ''; ?>>
                    WI <?php echo ($notifCounts['WI'] > 0) ? '(' . $notifCounts['WI'] . ' new)' : ''; ?>
                </option>
                <option value="Procedure" <?php echo (isset($tipe) && $tipe == 'Procedure') ? 'selected' : ''; ?>>
                    Procedure <?php echo ($notifCounts['Procedure'] > 0) ? '(' . $notifCounts['Procedure'] . ' new)' : ''; ?>
                </option>
                <option value="Form" <?php echo (isset($tipe) && $tipe == 'Form') ? 'selected' : ''; ?>>
                    Form <?php echo ($notifCounts['Form'] > 0) ? '(' . $notifCounts['Form'] . ' new)' : ''; ?>
                </option>
            </select>
            
            <!-- Badge indicators for each type -->
            <?php if($notifCounts['WI'] > 0 && (!isset($tipe) || $tipe == '-')): ?>
            <span class="dropdown-badge" style="right: 150px;"><?php echo $notifCounts['WI']; ?></span>
            <?php endif; ?>
            <?php if($notifCounts['Procedure'] > 0 && (!isset($tipe) || $tipe == '-')): ?>
            <span class="dropdown-badge" style="right: 100px;"><?php echo $notifCounts['Procedure']; ?></span>
            <?php endif; ?>
            <?php if($notifCounts['Form'] > 0 && (!isset($tipe) || $tipe == '-')): ?>
            <span class="dropdown-badge" style="right: 50px;"><?php echo $notifCounts['Form']; ?></span>
            <?php endif; ?>
        </div>
        
        <?php if ($state=='Admin'){ ?>
        <select name="status" class="form-control">
            <option value="-">--- Select Status ---</option>
            <option <?php echo (isset($status) && $status == 'Review') ? 'selected' : 'selected'; ?> value="Review">Review</option>
            <option <?php echo (isset($status) && $status == 'Pending') ? 'selected' : ''; ?> value="Pending">Pending</option>
            <option <?php echo (isset($status) && $status == 'Approved') ? 'selected' : ''; ?> value="Approved">Approved</option>
        </select>           
        <?php } ?>

     <input type="submit" name="submit" value="Show" class="btn btn-primary" />
    </div>
        <br />
            <br />
                <br />
</form>

<!-- Auto Refresh Indicator -->
<div class="auto-refresh-indicator" id="refreshIndicator">
    <span class="glyphicon glyphicon-refresh"></span> Checking for updates...
</div>

<?php

if(isset($_GET['submit']))
{
 $tipe=$_GET['tipe'];

if($tipe=="-"){
    $sort="";
}else{
$sort="and doc_type='$tipe'";
}

if($state=='Admin') {
    $sql="select * from docu where status='$status' $sort order by no_drf";
}
elseif($state=='Originator') {
    $sql="select * from docu where (docu.status='Review' or docu.status='Pending' or docu.status='Approved') $sort and user_id='$nrp' order by no_drf";
}
elseif($state=='Approver') {
    $sql="select * from docu,rev_doc where docu.status='Review' and rev_doc.status='Review' and docu.no_drf=rev_doc.id_doc and rev_doc.nrp='$nrp' $sort order by no_drf";
}

$res=mysqli_query($link, $sql);
?>

<table class="table table-hover">
<thead bgcolor="#00FFFF">
<tr>
    <td>No</td>
    <td>Date</td>
    <td>No. Drf</td>
    <td>No Document</td>
    <td>No Rev.</td>
    <td>Title</td>
    <td>Process</td>
    <td>Section</td>
    <td>Type</td>
    <td>Review To</td>
    <td>Pass Day</td>
    <td>Status</td>
    <td>Action</td>
</tr>
</thead>
<tbody>
<?php
$j=1;
while($info = mysqli_fetch_array($res)) 
{ ?>

<tr <?php 
    $tglsekarang = date('d-m-Y');
    $tglissue  =$info['tgl_upload'];
    $pecah1 = explode("-", $tglissue );
    $date1 = $pecah1[0];
    $month1 = $pecah1[1];
    $year1 = $pecah1[2];
    $pecah2 = explode("-", $tglsekarang);
    $date2 = $pecah2[0];
    $month2 = $pecah2[1];
    $year2 =  $pecah2[2];
    $waktusekarang = GregorianToJD($month1, $date1, $year1);
    $waktuinput = GregorianToJD($month2, $date2, $year2);
    $selisih =$waktuinput - $waktusekarang; 
    
    // Calculate working days
    $dat1 = $info['tgl_upload'];
    $dat2 = $tglsekarang;
    $pecahTgl1 = explode("-", $dat1);
    $tgl1 = $pecahTgl1[0];
    $bln1 = $pecahTgl1[1];
    $thn1 = $pecahTgl1[2];
    
    $i = 0;
    $sum = 0;
    do {
        $tanggal = date("d-m-Y", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1));
        if (date("w", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)) == 0 or date("w", mktime(0, 0, 0, $bln1, $tgl1+$i, $thn1)) == 6) {
            $sum++;
        }
        $i++;
    } while ($tanggal != $dat2);
    
    $day=$selisih-$sum;
    $dayx=$day+1;
    
    // Highlight rows based on days passed
    if($dayx >= 3 && $info['status']=='Review') {
        echo 'style="background-color: #f8d7da;"'; // Red for overdue
    } elseif($dayx >= 1 && $info['status']=='Review') {
        echo 'style="background-color: #fff3cd;"'; // Yellow for urgent
    }
?>>
    <td><?php echo $j; ?></td>
    <td><?php echo $info['tgl_upload'];?></td>
    <td><?php echo $info['no_drf'];?></td>
    <td><?php echo $info['no_doc'];?></td>
    <td><?php echo $info['no_rev'];?></td>
    <td>
    <?php if ($info['no_drf']>12967){$tempat=$info['doc_type'];} else {$tempat="document";}?>
    <a href="<?php echo $tempat; ?>/<?php echo $info['file']; ?>" >
        <?php echo $info['title'];?>
        </a>
    </td>
    <td><?php echo $info['process'];?></td>
    <td><?php echo $info['section'];?></td>
    <td><?php echo $info['doc_type'];?></td>
    <td><?php echo $info['rev_to'];?></td>
    <td>
        <?php
        echo $day+1;
        if ($dayx>=1 and $info['status']=='Review' and ($state=='Admin' or $state=='Originator') ) {
        ?>
            <a href="reminder.php?drf=<?php echo $info['no_drf'];?>&type=<?php echo $info['doc_type'];?>&nodoc=<?php echo $info['no_doc'];?>&title=<?php echo $info['title'];?>" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-envelope"></span>&nbsp; Reminder <strong><?php echo $info['reminder']?>x</strong></a>
        <?php
        }
        ?>
    </td>
    <td>
    <?php if ($info['status']=='Review'){ ?>
    <span class="label label-info"><?php } ?>
    <?php if ($info['status']=='Pending'){ ?>
    <span class="label label-warning"><?php } ?>
    <?php if ($info['status']=='Approved'){ ?>
    <span class="label label-success"><?php }?>
        <?php echo $info['status'];?>
        </span>
    </td>
    <td>
    <a href="detail.php?drf=<?php echo $info['no_drf'];?>&no_doc=<?php echo $info['no_doc'];?>" class="btn btn-xs btn-info" title="lihat detail"><span class="glyphicon glyphicon-search" ></span> </a>
    <a href="radf.php?drf=<?php echo $info['no_drf'];?>&section=<?php echo $info['section']?>" class="btn btn-xs btn-info" title="lihat RADF"><span class="glyphicon glyphicon-eye-open" ></span> </a>
    <a href="lihat_approver.php?drf=<?php echo $info['no_drf'];?>&nodoc=<?php echo $info['no_doc']?>&title=<?php echo $info['title']?>&type=<?php echo $info['doc_type']?>" class="btn btn-xs btn-info" title="lihat approver"><span class="glyphicon glyphicon-user" ></span> </a> 
    
    <?php if ($state=='Approver'){?>
    <a href="approve.php?drf=<?php echo $info['no_drf'];?>&device=<?php echo $info['device']?>&no_doc=<?php echo $info['no_doc'];?>&title=<?php echo $info['title'] ?>&tipe=<?php echo $tipe; ?>" class="btn btn-xs btn-success" title="Approve Doc"><span class="glyphicon glyphicon-thumbs-up" ></span> </a>
    <a href="pending.php?drf=<?php echo $info['no_drf'];?>&no_doc=<?php echo $info['no_doc'];?>&type=<?php echo $info['doc_type'];?>" class="btn btn-xs btn-warning" title="Suspend Doc"><span class="glyphicon glyphicon-warning-sign" ></span>  </a>
    <?php } ?>

    <?php if ($state=='Admin' ||  ($state=="Originator" && $info['status']<>"Approved")){ ?>
    <a href="edit_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-primary" title="Edit Doc"><span class="glyphicon glyphicon-pencil" ></span> </a>
    <a href="del_doc.php?drf=<?php echo $info['no_drf'];?>" class="btn btn-xs btn-danger" onClick="return confirm('Delete document <?php echo $info['no_doc']?>?')" title="Delete Doc"><span class="glyphicon glyphicon-remove" ></span> </a>
    
    <?php if ($info['status']=='Approved') { ?>
    <a data-toggle="modal" data-target="#myModal2" data-id="<?php echo $info['no_drf']?>" data-lama="<?php echo $info['file']?>" data-type="<?php echo $info['doc_type']?>" data-status="<?php echo $info['status']?>" data-rev="<?php echo $info['rev_to']?>" class="btn btn-xs btn-success sec-file" title="Secure Document">
    <span class="glyphicon glyphicon-play" ></span></a>
    <?php } } ?>

    <?php if ($info['status']=='Pending' and ($state=='Originator' or $state='Admin')){ ?>
    <button data-toggle="modal" data-target="#myModal" data-id="<?php echo $info['no_drf']?>" data-type="<?php echo $info['doc_type']?>" data-nodoc="<?php echo $info['no_doc']?>" data-title="<?php echo $info['title']?>" data-lama="<?php echo $info['file']?>"  class="btn btn-xs btn-warning upload-file">
    <span class="glyphicon glyphicon-upload"></span>
    Change Document</button>
    <?php }?>
    </td>
</tr>

<?php 
$j++;} 
?> 
</tbody>
</table>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Upload Document</h4>
            </div>
            <div class="modal-body">
                <form name="ganti_doc" method="POST" action="ganti_doc.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="drf" id="drf" class="form-control" value=""/>
                        <input type="hidden" name="nodoc" id="nodoc" class="form-control" value=""/>
                        <input type="hidden" name="type" id="type" class="form-control" value=""/>
                        <input type="hidden" name="title" id="title" class="form-control" value=""/>
                        <input type="text" name="lama" id="lama" class="form-control" value=""/>
                        File Document(.pdf/.xlsx):<input type="file" name="baru" class="form-control">
                        File Master(.docx/.xlsx):<input type="file" name="masterbaru" class="form-control"><br />
                    </div>
                    <div class="modal-footer"> <a class="btn btn-default" data-dismiss="modal">Cancel</a>
                        <input type="submit" name="upload" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Secure Document</h4>
            </div>
            <div class="modal-body">
                <form name="secure_doc" method="POST" action="process.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="drf" id="drf" class="form-control" value=""/>
                        <input type="hidden" name="rev" id="rev" class="form-control" value=""/>
                        <input type="hidden" name="type" id="type" class="form-control" value=""/>
                        <input type="hidden" name="status" id="status" class="form-control" value=""/>
                        <input type="file" name="baru" class="form-control">
                    </div>
                    <div class="modal-footer"> <a class="btn btn-default" data-dismiss="modal">Cancel</a>
                        <input type="submit" name="upload" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php } ?>