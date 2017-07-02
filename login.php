<?php
$log = '';
if (isset($_POST['submit'])) {
    require_once 'db.inc.php';
    $user = $_POST['user'];
    $passwd = $_POST['passwd'];
    if (($passwd == '') || ($user == '')) {
        $log = "enterdata";
    } else if (($passwd != '') && ($user != '')) {

        $query = "select * from users where user_id='$user'";
        //$result = mysql_query($query);
          $result=$conn->query($query);
       // if (mysql_num_rows($result) == 1) 
        if($result->num_rows ==1)
        {
            //$row = mysql_fetch_assoc($result);
            $row = $result->fetch_assoc();
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['Last_Activity'] = time();
            $_SESSION['role'] = $row['role'];
            $_SESSION['showView'] = '';
            if (strpos(getenv("HTTP_REFERER"), "login.php") !== FALSE)
                header('Location: home.php');
            else if (strpos(getenv("HTTP_REFERER"), "logOut.php") !== FALSE)
                header('Location: home.php');
            else
                header('Location: ' . getenv("HTTP_REFERER"));


            $query = "insert into sessionlogs(Action,Username) values('Login','$user')";
            if(!$conn->query($query)){
            	echo mysqli_error($conn);
            }
        } else {
            $log = "Unauthorized user : Reach us at DL-Fastrac-Support for access !";
        }
    }
} else if ( strpos(strtolower($_SERVER['REQUEST_URI']), "login.php") !== FALSE){
    session_start();
    if (isset($_SESSION['user']) && isset($_SESSION['Last_Activity'])) {
        if (time() - $_SESSION['Last_Activity'] < 600) {
            $_SESSION['Last_Activity'] = time();
            header('Location: home.php');
            exit();
        }
    }
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Fastrac Login</title>
        <link rel="stylesheet" href="css/style.css">
        <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <link rel="shortcut icon" href="images/logo2.png" />
    </head>
    <body>
        <form action="login.php" class="login" style="background: #0866C6" method="post">
            <!--<h1>Fastrac</h1>-->
            <img src="images/logo1.png" style="max-width: 100%;margin-bottom: 6px">
            <?php if (strpos(strtolower($_SERVER["QUERY_STRING"]), 'loginagain') !== FALSE) { ?>
                <div align="center" style="width:100%; color:#FFFFFF; font-weight:bold; ">Session Expired... Login Again</div>
            <?php } ?>
            <input type="text" name="user" class="login-input" placeholder="User Name" autofocus>
            <input type="password" name="passwd" class="login-input" placeholder="Password">
            <input type="submit" name="submit" value="Login" class="login-submit">

    <!--<p class="login-help"><a href="index.html">Forgot password?</a></p>-->
            <?php if ($log == "enterdata") { ?>
                <div align="center" style="width:100%; color:#FFFFFF; font-weight:bold; "> Enter Credentials</div>
            <?php } else if ($log == "fail") { ?>
                <div align="center" style="width:100%; color:#FFFFFF; font-weight:bold; ">Enter Valid Credentials</div>
            <?php } else { ?>
                <div align="center" style="width:100%; color:#FFFFFF; font-weight:bold; "><?php echo $log; ?></div>
            <?php } ?>
        </form>
    </body>
</html>
