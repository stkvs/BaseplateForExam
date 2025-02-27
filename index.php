<?php
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: ./pages/register.php");
        exit();
    }
    
    require('./components/header.html');
    require('./components/footer.html');
?>