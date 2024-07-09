<?php
session_start();

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('redirect_if_not_logged_in')) {
    function redirect_if_not_logged_in() {
        if (!is_logged_in()) {
            header('Location: login.php');
            exit;
        }
    }
}
?>
