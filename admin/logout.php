<?php
/**
 * Abdullah Property Dealer - Admin Logout
 */
require_once __DIR__ . '/../includes/config.php';

unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_user']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_role']);

session_destroy();

header('Location: login.php');
exit;

