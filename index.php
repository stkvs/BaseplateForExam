<?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        echo "Email: " . $_SESSION['user_id'];
    }

    $pageTitle = "Home";
    
    require('./components/header.php');
    include('./components/navbar.php');
    require('./components/footer.html');
?>