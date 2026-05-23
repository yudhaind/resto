<?php
session_start();
require_once 'database.php';
$globaltoken=bin2hex(random_bytes(32));
$_SESSION['globaltoken']=$globaltoken;

if (isset($_SESSION['user']['id'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        include 'admin/admin_dash.php';
    } else if ($_SESSION['user']['role'] === 'kasir') {
    include 'kasir/kasir_dash.php';
    } else {
        echo "Role tidak dikenali.";
    }
} else {
    include 'login.php';
}
?>