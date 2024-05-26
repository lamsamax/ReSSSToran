<?php
session_start();
include('dbconnect.php');

function checkUserRole($requiredRole) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != $requiredRole) {
        header('Location: php/login.php');
        exit();
    }
}