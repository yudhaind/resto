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
			header('location:logout.php');
        } 
        $sql = "SELECT * FROM users WHERE username = ?";
        $rsl = fetchOne($sql, [$username]);
        // Lakukan validasi login di sini, misalnya dengan memeriksa database
        if ($rsl && password_verify($password, $rsl['password'])) {
            // Login berhasil, simpan informasi pengguna di session
            $_SESSION['user'] = [
                'id' => $rsl['id'],
                'username' => $rsl['username'],
                'role' => $rsl['role']
            ];
            header('location:./');
        } else {
            $_SESSION['error']="Username atau password salah";
            header('location:logout.php');
        }
    }
}

?>