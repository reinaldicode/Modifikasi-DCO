<script src="bootstrap/js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="bootstrap/js/bootstrap-dropdown.js"></script> <script src="bootstrap/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css"> <script src="bootstrap/js/bootstrap-datepicker.js"></script> ```

<?php
// Jika Anda membutuhkan koneksi database untuk menu dinamis di masa depan,
// letakkan include koneksi.php di sini.
// include 'koneksi.php';
?>
<html>
<title>Document Control</title>
<head profile="http://www.global-sharp.com">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="bootstrap/css/facebook.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/datepicker.css">

    <script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap/js/bootstrap-datepicker.js"></script>
    
    <script>
    $(document).ready(function(){
        // Inisialisasi dropdown Bootstrap
        $('.dropdown-toggle').dropdown();
    });
    </script>

    <style> 
    body {
        background-image: url("images/white.jpeg");
        background-color: #cccccc;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-brand"><h3>Document Control Online</h3></span>

                <ul class="nav navbar-nav">
                    <li><a href="index.php"><img src="images/home.png" class="img-responsive" alt="Home">Home</a></li>
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="images/document.png" alt="Documents"><br> Documents <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="dokawal.php">All Documents</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="procedure_awal.php">Procedure</a></li>
                            <li><a href="wi_awal.php">WI</a></li>
                            <li><a href="form_awal.php">Form</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="mon_awal.php"><img src="images/report.png" alt="Monitor Sample"><br />Monitor Sample</a></li> 
                    <li><a href="search_awal.php" class="bg-success"><img src="images/search3.png" alt="Search"><br />Search</a></li>

                    <li class="pull-right"><a href="login.php"><img src="images/login.png" class="img-responsive" alt="Login"> &nbsp;Login</a></li>
                </ul>
            </div>
            
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                </div>
        </div>
    </nav>
    <br /><br />
    
    </body>
</html>