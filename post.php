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
   } else if ($action === 'tambah_meja') {
        $nomor_meja = $_POST['nomor_meja'] ?? '';
        $kapasitas_meja = $_POST['kapasitas_meja'] ?? '';

        if (empty($nomor_meja) || empty($kapasitas_meja)) {
            echo '<div class="error-message">Nomor meja dan kapasitas wajib diisi</div>';  
         } else {
            $sql_cek = "SELECT * FROM `tables` WHERE table_number = ?";
            $rsl_cek = numRows($sql_cek, [$nomor_meja]);
            if ($rsl_cek > 0) {
                echo '<div class="error-message">Nomor meja sudah ada</div>';
            } else {
              $sql = "INSERT INTO `tables` (table_number, capacity, status) VALUES (?, ?, 'available')";
              query($sql, [$nomor_meja, $kapasitas_meja]);
              echo '<div class="ok-message">Meja berhasil ditambahkan</div>'; 
            }
         }
    }
}