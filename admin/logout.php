<?php
    include('../includes/config.php');
    
    //log user out
    $user->logout();
    header('location:index.php');
?>