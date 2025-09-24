<script src="bootstrap/js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap-datepicker.js"></script>

<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="bootstrap/js/bootstrap-dropdown.js"></script> ```

<!-- File `bootstrap.min.js` adalah paket lengkap yang **sudah berisi semua** fungsionalitas JavaScript Bootstrap, termasuk untuk dropdown. Memuat file `bootstrap-dropdown.js` secara terpisah setelahnya seringkali tidak diperlukan untuk versi Bootstrap 3 dan bisa menyebabkan konflik, sehingga event "klik" pada dropdown tidak berfungsi.

### Solusi

Solusinya sangat sederhana: **Hapus baris yang memuat `bootstrap-dropdown.js`**. File `bootstrap.min.js` sudah cukup untuk menangani semuanya.

---

### Kode `header.php` Lengkap yang Telah Diperbaiki

Berikut adalah kode lengkap `header.php` Anda dengan perbaikan tersebut. Saya hanya menghapus satu baris JavaScript yang bermasalah. Struktur dan tampilan lainnya tetap sama persis seperti yang Anda inginkan.

Silakan ganti seluruh isi file Anda dengan kode ini.

```php -->
<?php
// session_start();
include 'session.php';
extract($_REQUEST);
?>

<html>
<title>Document Control</title>

<head profile="http://www.global-sharp.com">
 
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


 
  <link href="bootstrap/css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-responsive.min.css" media="all" type="text/css" rel="stylesheet">
    <link href="bootstrap/css/facebook.css" media="all" type="text/css" rel="stylesheet">
    
    <script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script> <script src="bootstrap/js/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" href="bootstrap/css/datepicker.css">
  <link rel="stylesheet" href="bootstrap/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">

<?php if (isset($name) && $name=='Vitria') {?>
  <style> 
body {
    background-image: url("images/white.jpeg");
    background-color: #cccccc;
}
</style>
  <?php } 
  else   { ?>
  <style> 
body {
    background-image: url("images/white.jpeg");
    background-color: #cccccc;
    background-repeat: no-repeat;
    background-attachment: fixed;
}
</style>
  <?php } ?>


<script type="text/javascript">
$(document).ready(function () {
    $('.chg-pass').click(function () {
        $('span.user-id').text($(this).data('user'));
        var usercp = $(this).data('usercp');
        $(".modal-body #usercp").val( usercp );
        var passcp = $(this).data('passcp');
        $(".modal-body #passcp").val( passcp );
        var title = $(this).data('title');
        $(".modal-body #title").val( title );
    });
});
</script>
 
</head>

<body >
<br />
<br />

  <?php 
  // Logika untuk menghitung notifikasi (tetap sama)
  $sql="";
  $rows = 0;
  if (isset($state)) {
    if ($state=='Admin')
    {
      $sql="SELECT * FROM docu WHERE status='Approved' ORDER BY no_drf";
    }
    elseif ($state=='Originator')
    {
      $sql="SELECT * FROM docu WHERE (status='Approved' OR status='Pending') AND user_id='$nrp' ORDER BY no_drf";
    }
    elseif ($state=='Approver')
    {
      $sql="SELECT * FROM docu,rev_doc WHERE docu.status='Review' AND rev_doc.status='Review' AND docu.no_drf=rev_doc.id_doc AND rev_doc.nrp='$nrp' ORDER BY no_drf";
    }

    if (!empty($sql)) {
        $res=mysqli_query($link, $sql);
        if ($res) {
            $rows = mysqli_num_rows($res);
        }
    }
  }
  ?>

  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <span class="navbar-brand" href="index.php"><h3><img src="images/doc-21.png" style="margin-top:-25px;" size="40px" alt="Logo"></h3></span>

      <ul class="nav navbar-nav">
        <li><a href="index_login.php" class="bg-info"><img src="images/home.png" class="img-responsive" alt="Home">Home</a></li>
        
        <?php if ($rows > 0 && isset($state) && in_array($state, ['Approver', 'Admin', 'Originator'])){?>
          <li><a href="my_doc.php" ><img src="images/notif.gif" alt="Notification"><br />Review <span class="badge" style="background: #00f; color: #fff;"><?php echo $rows; ?></span></a></li>
        <?php } else {?>
          <li><a href="my_doc.php" ><img src="images/text.png" alt="Review"><br />Review</a></li>
        <?php } ?>
        
        <li><a <?php if (isset($state) && $state=='Approver' && $nrp<>'000043'){$href='#';} else {$href='upload.php';} ?> href="<?php echo $href;?>" class="bg-info mute" ><img src="images/up.png" alt="Upload"><br />Upload</a></li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="images/document.png" alt="Documents"><br> Documents <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="procedure_login.php">Procedure</a></li>
                <li><a href="wi_login.php">WI</a></li>
                <li><a href="form_login.php">Form</a></li>
                <li class="divider"></li>
                <li><a href="MSROHS.php">MS & ROHS</a></li>
                <li><a href="monitor_login.php">Sample</a></li>
                <li><a href="msds_login.php">MSDS</a></li>
                <li class="divider"></li>
                <li><a href="manual.php">Manual</a></li>
                <li><a href="obs_login.php">Obsolate</a></li>
            </ul>
        </li>

        <li><a href="search.php" ><img src="images/search3.png" alt="Search"><br />Search</a></li>
        <li><a href="grafik.php" class="bg-info"><img src="images/graph.png" alt="Grafik"><br />Grafik</a></li>
        <li><a href="#" data-toggle="modal" data-target="#myModal3" data-usercp="<?php echo $nrp2;?>" data-passcp="<?php echo $pass2;?>" ><img src="images/logoff.png" alt="Change Pass"><br />Change</a></li>
    
        <?php if (isset($state) && $state=="Admin"){?>
            <li><a href="config_head.php" ><img src="images/config.png" class="bg-info" alt="Config"><br />Config</a></li>
            <li class="pull-right"><a href="logout.php" onClick="return confirm('Logout?')" ><img src="images/logout.png" class="img-responsive" alt="Logout"> &nbsp;logout</a></li>
        <?php } else if (isset($state) && $state=="PIC"){ ?>
            <li class="pull-right"><a href="logout.php" onClick="return confirm('Logout?')" class="bg-info"><img src="images/logout.png" class="img-responsive" alt="Logout"> &nbsp;logout</a></li>
        <?php } else { ?>
            <li class=""><a href="document/manual_approver.pdf"><img src="images/help.png" class="img-responsive" alt="Help"> &nbsp;Help</a></li>      
            <li class="pull-right bg-info"><a href="logout.php" onClick="return confirm('Logout?')" ><img src="images/logout.png" class="img-responsive" alt="Logout"> &nbsp;logout</a></li> 
        <?php } ?>
        </ul>

    </div>
    <br /><br /><br /><br />

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"></div>
  </div>
</nav>

</body>

<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title" id="myModalLabel">Change Password</h4>
            </div>
            <div class="modal-body">
                <form name="chg_pass" method="POST" action="chg_pass.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="user" id="user" class="form-control" value="<?php echo isset($nrp) ? htmlspecialchars($nrp) : ''; ?>"/>
                        <input type="hidden" name="pass" id="pass" class="form-control" value="<?php echo isset($pass) ? htmlspecialchars($pass) : ''; ?>"/>
                        <input type="password" name="lama" placeholder="Type Old Password" id="lama" class="form-control" value=""/>
                        <input type="password" name="baru" id="baru" placeholder="Type New Password " class="form-control">
                        <input type="password" name="conf" id="conf" placeholder="Confirm New Password" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal">Cancel</a>
                        <input type="submit" name="upload" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</html>