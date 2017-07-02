
<?php
require_once 'session.inc.php';
require_once 'db.inc.php';
require_once 'logFile.php';
$val = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $iqe = $_POST['iqe'];
    $ipm = $_POST['ipm'];
    $core = $_POST['core'];
    $conn->query("insert into users(user_id,role) values('" . $iqe . "',2)");
    $conn->query("insert into users(user_id,role) values('" . $ipm . "',2)");
    $core = str_replace(",,", ",", $core);
    $coremembers = explode(",", $core);
    foreach ($coremembers as $mem)
        $conn->query("insert into users(user_id,role) values('" . $mem . "',2)");
    $query = "insert into projects(p_name,description,iqe,core,ipm) values('$name','$description','$iqe','," . $core . ",','" . $_POST['ipm'] . "')";
    if ($conn->query($query) == 1) {
        mkdir('log/' . $name);
        mkdir('log/' . $name . '/tmp');
        $details = "$name,$description,$iqe,$core,$ipm";
        createlog($name,"Add Project", $details,$conn);
        ini_set("SMTP", "mailsj-v1.corp.adobe.com");
        date_default_timezone_set('America/New_York');
        $c = $_SESSION['user'];
        $to = $c . ',' . $iqe . ',' . $ipm . ',' . $core;
        $subject = "Project $name Created in Fastrac";
        $message = '<html> 
            <body style="background-color:#FFFFFF;"> 
            <font color="#000000" face="calibri">
            Hi, <br><br> The Project <b>' . $name . '</b> has been created. <br> <br>
                Email notification will be sent once New Features will be available and assigned.<br><br><br>
                Thanks,<br> 
                CC-Fastrac Team
                </font>
                </body> 
                </html> ';

        $from = "CC Fastrac Team <anmani@adobe.com>";
        $headers = "From: $from\r\n";
        $headers .= "Content-type: text/html\r\n";
        mail($to, $subject, $message, $headers);
        header('Location: home.php?p_name=' . $name);
        exit();
    } else {
        $val = 'Error : ' . mysql_error();
    }
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Fastrac</title>
        <link rel="stylesheet" href="css/style.default.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="styles/style.css" />
        <link type="text/css" href="styles/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="scripts/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="scripts/jquery-ui-1.8.21.custom.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/style.default.css" type="text/css" />
        <script type="text/javascript" src="js/custom.js"></script>
        <script type="text/javascript" src="commonFunctions.js"></script>
        <style>
            .headmenu > li:nth-child(2) { background: rgba(255,255,255,0.1); }
            .headmenu > li:nth-child(2) > a .headmenu-label{ opacity: 1;}
            .headmenu > li:nth-child(2) > a .head-icon{ opacity: 1;}
        </style>
        <link rel="shortcut icon" href="images/logo2.png" />
    </head>
    <body>
        <div class="mainwrapper">
            <?php require 'header.inc.php'; ?>
            <?php require 'leftPanel.inc.php'; ?>
            <div class="rightpanel">
                <div class="pageheader">
                    <div class="pageicon"><span class="iconfa-laptop"></span></div>
                    <div class="pagetitle">
                        <h1>Add New Project</h1>
                    </div>
                </div><!--pageheader-->
                <div class="maincontent">
                    <div class="maincontentinner">
                        <div class="widgetbox box-inverse">
                            <h4 class="widgettitle">Add New Project</h4>
                            <div class="widgetcontent nopadding">
                                <form class="stdform stdform2" method="post" action="home.php" enctype="multipart/form-data" onsubmit="return validatepro('')">
                                    <div style="color: red;text-align: left" id="msgdiv"><?php echo $val; ?></div>
                                    <p>
                                        <label>Project Name<sup style="color: red">*</sup></label>
                                        <span class="field"><input type="text" name="name" id="project_name" class="input-xlarge" onchange="checkDup('')"/></span>
                                    </p>
                                    <p>
                                        <label>IQE<sup style="color: red">*</sup></label>
                                        <span class="field"><input type="text" name="iqe" id="iqe" class="input-large" /></span>
                                    </p>
                                    <p>
                                        <label>IPM</label>
                                        <span class="field"><input type="text" name="ipm" id="ipm" class="input-large" /></span>
                                    </p>
                                    <p>
                                        <label>Core Team <small><i>(Comma Separted Members)</i></small></label>
                                        <span class="field"><input type="text" name="core" id="core" class="input-large" /></span>
                                    </p>
                                    <p>
                                        <label>Description <small>You can put your own description for this field here.</small></label>
                                        <span class="field"><textarea cols="80" rows="5" name="description" id="location2" class="span5"></textarea></span>
                                    </p>
                                    <p class="stdformbutton">
                                        <button class="btn btn-primary" name="submit">Add Project</button>
                                        <button type="reset" class="btn">Reset</button>
                                        <button type="submit" class="btn" id="cancel" value="cancel" onclick="clicked()" style="float:right" name="cancel">Cancel</button>
                                    </p>
                                </form>
                            </div><!--widgetcontent-->
                        </div><!--widget-->
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
