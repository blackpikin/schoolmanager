<?php
session_start();
include "includes/Model.php";
include "includes/PrimaryModel.php";
include 'includes/Calendar.php';
include 'includes/Lang.php';

if (isset($_SESSION['id'])){
    if (time() - $_SESSION['timer']  > 3400 ){
        session_destroy();
        header('Location: ../');
    }else{
        $_SESSION['timer'] = time();
    }
}

$lng = $_SESSION['lang'];
$section = 0;
if($lng == 'fr'){
    $section = 1;
}
$title = "ClassMaster Pro";
$Model = new Model($section);
$Primodel = new PrimaryModel($section);
$calendar = new Calendar();
?>
<!DOCTYPE html>
<html>
<head lang="fr">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" id="viewport-meta" />
    <script>
        // Store the meta element
        var viewport_meta = document.getElementById('viewport-meta');

        // Define our viewport meta values
        var viewports = {
            bydefault: viewport_meta.getAttribute('content'),
            landscape: 'width=990'
        };

        // Change the viewport value based on screen.width
        var viewport_set = function() {
            if ( screen.width > 768 )
                viewport_meta.setAttribute( 'content', viewports.landscape );
            else
                viewport_meta.setAttribute( 'content', viewports.bydefault );
        };

        // Set the correct viewport value on page load
        viewport_set();

        // Set the correct viewport after device orientation change or resize
        window.onresize = function() {
            viewport_set();
        }
    </script>
    <title><?php echo $title ?></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="stylesheet" href="./css/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="./css/font-awesome.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="./css/bootstrap-theme.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="./css/style.css" type="text/css" media="all">
    <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="./css/ie.css" />
    <![endif]-->
    <script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="./js/bootstrap.min.js"></script>
</head>
<body class="grey-body" oncontextmenu="return false">
<header class="row">
         <div id="logo" class="col-xs-2" onclick="Goto()" onmouseover="SetPointer(this)">
         <?php 
                if (!isset($Model->GetCurrentYear()[0]['id'])){
                    ?>
                        <img src="./img/worldschoollogo.webp" alt="app_logo" class="logo">
                    <?php
                }else{
                    ?>
                        <img src="./img/logo.png" alt="app_logo" class="logo">
                    <?php
                }
            ?>
         </div>
         <div class="col-xs-8">
            <h3 class="school-title"><?= isset($Model->GetSchoolInfo(1)[0]['name']) ? $Model->GetSchoolInfo(1)[0]['name'] : "Unknown School" ?></h3>
            <h4 class="app_name"><?= strToUpper($lang[$_SESSION['lang']]["SystemName"]) ?></h4>
            <?php 
                if (!isset($Model->GetCurrentYear()[0]['id'])){
                    ?>

                    <?php
                }else{
                    ?>
                        <h5 class="app_name"><?= $Model->GetYearName($Model->GetCurrentYear()[0]['id']) ?></h5>
                    <?php
                }
            ?>
            
        </div>
        <div class="col-xs-2">
            <?php 
                if(isset($_SESSION['username']) && $_SESSION['username'] !== ""){
                    ?>
                    <div class="user-cred-box">
                        <span class="white-label"><?= $lang[$_SESSION['lang']]["User"] ?> : <?= $_SESSION['username']; ?></span><br></br>
                        <span class="white-label"><?= $lang[$_SESSION['lang']]["Role"] ?>: <?= $_SESSION['role'] ?></span><br>
                        <br>
                        <button onclick="GotoPage('logout')"><?= $lang[$_SESSION['lang']]["Logout"] ?></button>
                    </div>
                    <?php
                }
            ?>
        </div>
     </header>
<div class="row">
    <div class="col-xs-2">
        <?php 
        if(isset($_SESSION['username']) && $_SESSION['username'] !== ""){
            ?>
            <div class="sidebar-decor">
                <?php 
                if($_SESSION['role'] == "Admin"){ ?>
                    <ul class="menu-list">
                        <li class="menu-list-item" onclick="GotoPage('users')" onmouseover="SetPointer(this)"> <?= $lang[$_SESSION['lang']]["Staff"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('students')"onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Students"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('reports')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Reports"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('home')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Calendar"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('events')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Add events"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('sendEmail')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["SendAnEmail"]?></li>
                        <!--<li class="menu-list-item" onclick="GotoPage('sendSMS')" onmouseover="SetPointer(this)">Envoyer des SMS</li> -->
                        <li class="menu-list-item" onclick="GotoPage('changelogininfo')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Change your password"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('settings')"onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Settings"]?></li>
                    </ul>
                    <hr>
                <?php
                }

                if($_SESSION['role'] == "Admin-p"){ ?>
                    <ul class="menu-list">
                        <li class="menu-list-item" onclick="GotoPage('primaryUsers')" onmouseover="SetPointer(this)"> <?= $lang[$_SESSION['lang']]["Staff"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('pupils')"onmouseover="SetPointer(this)">Pupils</li>
                        <li class="menu-list-item" onclick="GotoPage('primaryReports')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Reports"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('home')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Calendar"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('events')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Add events"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('primarySendEmail')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["SendAnEmail"]?></li>
                        <!--<li class="menu-list-item" onclick="GotoPage('sendSMS')" onmouseover="SetPointer(this)">Envoyer des SMS</li> -->
                        <li class="menu-list-item" onclick="GotoPage('changelogininfo')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Change your password"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('primarySettings')"onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Settings"]?></li>
                    </ul>
                    <hr>
                <?php
                }

                if($_SESSION['role'] == "Teacher"){ ?>
                    <ul class="menu-list">
                        <li class="menu-list-item" onclick="GotoPage('fillmarks')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["FillMarks"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('changelogininfo')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Change your password"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('home')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Calendar"]?></li>
                    </ul>
                    <hr>
                <?php
                }

                if($_SESSION['role'] == "Bursar"){ ?>
                    <ul class="menu-list">
                        <li class="menu-list-item" onclick="GotoPage('primaryFeeRec')" onmouseover="SetPointer(this)">Receive fees (Pri)</li>
                        <li class="menu-list-item" onclick="GotoPage('feeRec')" onmouseover="SetPointer(this)">Receive fees (Sec)</li>
                        <li class="menu-list-item" onclick="GotoPage('cashRec')" onmouseover="SetPointer(this)">Receive cash</li>
                        <li class="menu-list-item" onclick="GotoPage('cashPay')" onmouseover="SetPointer(this)">Pay out cash</li>
                        <li class="menu-list-item" onclick="GotoPage('discount')" onmouseover="SetPointer(this)">Discount</li>
                        <li class="menu-list-item" onclick="GotoPage('bursarReports')" onmouseover="SetPointer(this)">Reports</li>
                        <li class="menu-list-item" onclick="GotoPage('changelogininfo')" onmouseover="SetPointer(this)">Change password</li>
                        <li class="menu-list-item" onclick="GotoPage('home')" onmouseover="SetPointer(this)">Calendar</li>
                    </ul>
                    <hr>
                <?php
                }
                if($_SESSION['role'] == "Manager"){ ?>
                    <ul class="menu-list">
                        <li class="menu-list-item" onclick="GotoPage('feeSettings')" onmouseover="SetPointer(this)">Fee settings</li>
                        <li class="menu-list-item" onclick="GotoPage('discountReasons')" onmouseover="SetPointer(this)">Discount reasons</li>
                        <li class="menu-list-item" onclick="GotoPage('revenueSources')" onmouseover="SetPointer(this)">Revenue sources</li>
                        <li class="menu-list-item" onclick="GotoPage('finReports')" onmouseover="SetPointer(this)">Reports</li>
                        <li class="menu-list-item" onclick="GotoPage('changelogininfo')" onmouseover="SetPointer(this)">Change password</li>
                        <li class="menu-list-item" onclick="GotoPage('home')" onmouseover="SetPointer(this)">Calendar</li>
                    </ul>
                    <hr>
                <?php
                }

                if($_SESSION['role'] == "System"){ ?>
                <h5 class="system-menu-title">Primary Menu</h5>
                    <ul class="menu-list">
                        <li class="menu-list-item" onclick="GotoPage('primaryUsers')" onmouseover="SetPointer(this)"> <?= $lang[$_SESSION['lang']]["Staff"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('pupils')"onmouseover="SetPointer(this)">Pupils</li>
                        <li class="menu-list-item" onclick="GotoPage('primaryReports')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Reports"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('home')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Calendar"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('events')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Add events"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('primarySendEmail')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["SendAnEmail"]?></li>
                        <!--<li class="menu-list-item" onclick="GotoPage('sendSMS')" onmouseover="SetPointer(this)">Envoyer des SMS</li> -->
                        <li class="menu-list-item" onclick="GotoPage('changelogininfo')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Change your password"]?></li>
                        <li class="menu-list-item" onclick="GotoPage('primarySettings')"onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["Settings"]?></li>
                    </ul>
                    <hr>
                    <h5 class="system-menu-title">Secondary Menu</h5>
                    <ul class="menu-list">
                        <li class="menu-list-item" onclick="GotoPage('users')" onmouseover="SetPointer(this)">Staff</li>
                        <li class="menu-list-item" onclick="GotoPage('students')"onmouseover="SetPointer(this)">Students</li>
                        <li class="menu-list-item" onclick="GotoPage('reports')" onmouseover="SetPointer(this)">Reports</li>
                        <li class="menu-list-item" onclick="GotoPage('home')" onmouseover="SetPointer(this)">Calendar</li>
                        <li class="menu-list-item" onclick="GotoPage('events')" onmouseover="SetPointer(this)">Add events</li>
                        <li class="menu-list-item" onclick="GotoPage('sendEmail')" onmouseover="SetPointer(this)">Send Emails</li>
                        <li class="menu-list-item" onclick="GotoPage('sendSMS')" onmouseover="SetPointer(this)">Send SMS</li>
                        <li class="menu-list-item" onclick="GotoPage('changelogininfo')" onmouseover="SetPointer(this)">Change password</li>
                        <li class="menu-list-item" onclick="GotoPage('settings')"onmouseover="SetPointer(this)">Settings</li>
                    </ul>
                    <hr>
                    <h5 class="system-menu-title">Bursar Menu</h5>
                    <ul class="menu-list">
                        <li class="menu-list-item" onclick="GotoPage('primaryFeeRec')" onmouseover="SetPointer(this)">Receive fees (Pri)</li>
                        <li class="menu-list-item" onclick="GotoPage('feeRec')" onmouseover="SetPointer(this)">Receive fees (Sec)</li>
                        <li class="menu-list-item" onclick="GotoPage('cashRec')" onmouseover="SetPointer(this)">Receive cash</li>
                        <li class="menu-list-item" onclick="GotoPage('cashPay')" onmouseover="SetPointer(this)">Pay out cash</li>
                        <li class="menu-list-item" onclick="GotoPage('discount')" onmouseover="SetPointer(this)">Discount</li>
                        <li class="menu-list-item" onclick="GotoPage('bursarReports')" onmouseover="SetPointer(this)">Reports</li>
                    </ul>
                    <hr>
                    <h5 class="system-menu-title">Manager Menu</h5>
                    <ul class="menu-list">
                        <li class="menu-list-item" onclick="GotoPage('feeSettings')" onmouseover="SetPointer(this)">Fee settings</li>
                        <li class="menu-list-item" onclick="GotoPage('discountReasons')" onmouseover="SetPointer(this)">Discount reasons</li>
                        <li class="menu-list-item" onclick="GotoPage('revenueSources')" onmouseover="SetPointer(this)">Revenue sources</li>
                        <li class="menu-list-item" onclick="GotoPage('expenseSources')" onmouseover="SetPointer(this)">Expense sources</li>
                        <li class="menu-list-item" onclick="GotoPage('finReports')" onmouseover="SetPointer(this)">Reports</li>
                    </ul>
                    <hr>
                 <?php
                }
                ?> 
        <ul class="menu-list">
            <li class="menu-list-item" onclick="GotoPage('viewchild')" onmouseover="SetPointer(this)"><?= $lang[$_SESSION['lang']]["studentProfile"]?></li>
        </ul>
        </div>
            <?php
        }
        
        ?>
    </div>
    <div id="container" class="col-xs-10 container-fluid">
        <?php 
      if(isset($_SESSION['username']) && $_SESSION['username'] !== ""){
            if (isset($_GET['p']) && $_GET['p'] != "forgottenPw") {
                include "./html/".$_GET['p'].".php";
            }
            else{
                include "./html/login.php";
            }
       }elseif(isset($_GET['p']) && $_GET['p'] == 'forgottenPw'){
            include "./html/forgottenPw.php";
       }elseif(isset($_GET['p']) && $_GET['p'] == 'viewchild'){
            include "./html/viewchild.php";
       }else{
           include "./html/login.php";
      } 
        ?>
    </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<footer class="footer-style">
    <br>
    <br>
    <div class="row">
        <div class="col-md-1 col-sm-1 hidden-xs">

        </div>
        <div class="col-md-5 col-sm-5 col-xs-12">
        <span style="color:#95AAC9;">&copy; Flango Services.<?= $lang[$_SESSION['lang']]["AllRights"] ?></span>
        </div>
        <div class="col-md-1 col-sm-1 hidden-xs">

        </div>
        <div id="marg" class="col-md-3 col-sm-3 col-xs-12">

        </div>
        <div id="barg" class="col-md-2 col-sm-2 col-xs-12">
            
        </div>
    </div>
    <br>
    <br>
    
</footer>
<script src="js/app.js" type="text/javascript"></script>
</body>
</html>








