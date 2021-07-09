<?php 
    session_start();
    unset($_SESSION['log_id']);
    session_destroy();
    header("Location: ../views/login.php");
?>