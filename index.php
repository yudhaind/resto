<?php
session_start();
require_once 'database.php';
if (isset($_SESSION['userid']['id'])) {
    include 'kasir/index.php';
    exit();
} else {
    include 'login.php';
}
?>