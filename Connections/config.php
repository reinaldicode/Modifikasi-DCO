<?php

# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_config = "192.168.132.36";
$database_config = "doc";
$username_config = "admin";
$password_config = "SSItop123!";    
$config = mysqli_connect($hostname_config, $username_config, $password_config, $database_config) or trigger_error(mysqli_error(),E_USER_ERROR); 
?>