<?php
session_start();
require_once 'database.php';
$action = $_POST['action'] ?? '';
$tokenform = $_POST['tokenform'] ?? '';
if ($tokenform !== $_SESSION['token']) {
    die("Token tidak valid!");
} else {
    if ($action === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error']="Username dan password wajib diisi";
            exit;
			header('location:../');
        }

        $sql="";
        // Lakukan validasi login di sini, misalnya dengan memeriksa database
    }
}

?>