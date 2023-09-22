<?php
session_start();
if (isset($_SESSION['id'])){
    if (time() - $_SESSION['timer']  > 3400 ){
        header('Location: ./?p=logout');
    }else{
        $_SESSION['timer'] = time();
    }
}
if(isset($_SESSION['username']) && $_SESSION['username'] !== ""){
    //PDF Code here
}else{
    echo '<h3>You have been logged out or your browser window expired. Login again</h3>';
}