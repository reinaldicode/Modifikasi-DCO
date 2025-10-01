    <?php
    session_start(); // Starting Session

    include 'koneksi.php';

    // function validation(array $listInput)
    // {
    //     # variabel berisi inputan baik dari metode POST mau pun GET
    //     $request = $_REQUEST;

    //     # perulangan untuk array terluar (berisi nama input)
    //     foreach ($listInput as $input => $listRule) {
    //         echo "Please check your <strong>{$input}</strong><br>";

    //         # perulangan untuk sub array (berisi nama peraturan)
    //         foreach ($listRule as $rule) {
    //             echo "-> Peraturan <strong>{$rule}</strong>";

    //             # pemeriksaan tiap peraturan akan kita lakukan di sini

    //             echo "<br>";
    //         }

    //         echo "<br>";
    //     }
    // }

    // $rule =
    // [
    // 	'username' => 'required',
    // 	'password' => 'required'
    // ];

    $username = addslashes(trim(strtolower($_POST['username']), "gzb"));
    $password = addslashes(trim($_POST['password']));

    $error = "Username or Password is invalid";

    if (!empty($username) && !empty($password))
    {
        //variable for LDAP
        $ldap_hostname = "ldap://ASIASHARP.com:389";
        $ldap_username = "GZB".$username."@ASIASHARP.COM";
        $ldap_password = $password;
        $ldap_connection = ldap_connect($ldap_hostname);
        $ldapbind = ldap_bind($ldap_connection, $ldap_username, $ldap_password);
            
        if (!$ldapbind)
        {
            // SQL query to fetch information of registered users and finds user match.
            $query = "SELECT * FROM users WHERE username = '$username'";
            $res = mysqli_query($link, $query);
            $data = mysqli_fetch_array($res);
            $rows = mysqli_num_rows($res);
            if ($rows > 0)
            {
                // use below script if using hash
                // if(password_verify($password, $data['password']))

                // use below script if doesn't use hash
                if ($password == $data['password'])
                {
                    $_SESSION['username']=$data['username'];
                    $_SESSION['name']=$data['name']; // Initializing Session
                    $_SESSION['email']=$data['email'];
                    $_SESSION['section']=$data['section'];
                    $_SESSION['state']=$data['state'];
                    $_SESSION['level']=$data['level'];
                    $_SESSION['user_authentication']="valid";
                    $nrp = $data['username'];
                    $name = $data['name'];
                    $email = $data['email'];
                    $sec = $data['section'];
                    $state = $data['state'];
                    header("location:index_login.php"); // Redirecting To Other Page
                }
                else
                {
                    header("Location: login.php?error=Your Password Incorrect!");
                }
            }
            else
            {
                header("Location: login.php?error=Your account doesn't exist! Please contact administrator.");
            }
        }
        else
        {
            // SQL query to fetch information of registerd users and finds user match.
            $res2 = mysqli_query($link, "SELECT * FROM users WHERE username='$username'");
            $data2=mysqli_fetch_array($res2);
            $rows2 = mysqli_num_rows($res2);
            
            if ($rows2 == 1)
            {
                // $_SESSION['login_user']=$username; // Initializing Session
                $_SESSION['username']=$data2['username'];
                $_SESSION['name']=$data2['name'];
                $_SESSION['email']=$data2['email'];
                $_SESSION['section']=$data2['section'];
                $_SESSION['state']=$data2['state'];
                $_SESSION['user_authentication']="valid";
                $nrp = $data2['username'];
                $name = $data2['name'];
                $email = $data2['email'];
                $sec = $data2['section'];
                $state = $data2['state'];
                        
                header("location:my_doc.php"); // Redirecting To Other Page
                // echo $name;
                // echo $state;
                // var_dump($_SESSION);
            }
            else
            {
                header("Location: login.php?error=Your account doesn't exist! Please contact administrator.");
            }
            //mysqli_close($connection); // Closing Connection
        }
    }
    ?>