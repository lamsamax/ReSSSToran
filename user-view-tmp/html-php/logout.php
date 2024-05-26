<?php
    session_start();
    session_destroy();
    header("Location: ../../project-2024-group-4/php/login.php");
    exit();
?>
