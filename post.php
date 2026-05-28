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
        
        $sql = "SELECT * FROM users WHERE username = ?";
        $rsl = fetchOne($sql, [$username]);
        $status=$rsl['is_active'];
        if (empty($username) || empty($password) || $status === 3) {
            if ($status===3){
                $_SESSION['error']="User Tidak Aktif";
            } else {
                $_SESSION['error']="Username dan password wajib diisi";
            }
            header('location:logout.php');
            exit;
        } 
       
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
    } else if ($action === 'update_meja') {
        $id = $_POST['id'] ?? '';
        $nomor_meja = $_POST['nomor_meja'] ?? '';
        $kapasitas_meja = $_POST['kapasitas_meja'] ?? '';

        if (empty($nomor_meja) || empty($kapasitas_meja)) {
            echo '<div class="error-message">Nomor meja dan kapasitas wajib diisi</div>';  
         } else {
            $sql_cek = "SELECT * FROM `tables` WHERE table_number = ? AND id != ?";
            $rsl_cek = numRows($sql_cek, [$nomor_meja, $id]);
            if ($rsl_cek > 0) {
                echo '<div class="error-message">Nomor meja sudah ada</div>';
            } else {
              $sql = "UPDATE `tables` SET table_number = ?, capacity = ? WHERE id = ?";
              query($sql, [$nomor_meja, $kapasitas_meja, $id]);
              echo '<div class="ok-message">Meja berhasil diperbarui</div>'; 
            }
         }
    } else if ($action === 'tambah_user') {
        $name = $_POST['name'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        $is_active = $_POST['is_active'] ?? '';

        if (empty($name) || empty($username) || empty($password) || empty($role) || empty($is_active)) {
            echo '<div class="error-message">Semua field wajib diisi</div>';  
         } else {
            $sql_cek = "SELECT * FROM `users` WHERE username = ?";
            $rsl_cek = numRows($sql_cek, [$username]);
            if ($rsl_cek > 0) {
                echo '<div class="error-message">Username sudah ada</div>';
            } else {
              $hashed_password = password_hash($password, PASSWORD_DEFAULT);
              $sql = "INSERT INTO `users` (name, username, password, role, is_active) VALUES (?, ?, ?, ?, ?)";
              query($sql, [$name, $username, $hashed_password, $role, $is_active]);
              echo '<div class="ok-message">User berhasil ditambahkan</div>'; 
            }
        }
    } else if ($action === 'edit_user') {
        $name = $_POST['name'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        $is_active = $_POST['is_active'] ?? '';
        $id=$_POST['id'] ?? '';

        if (empty($name) || empty($username) || empty($role) || empty($is_active)) {
            echo '<div class="error-message">Semua field wajib diisi</div>';  

        } else {
            $sql="SELECT * FROM users WHERE username = ?";
            $rsl_cek=fetchOne($sql,[$username]);
            $count_cek=numRows($sql,[$username]);
            $iddb = $rsl_cek ? $rsl_cek['id'] : null;
            //echo $count_cek." data ditemukan, id sendiri :".$id.", id database : ".$iddb;
            if ($count_cek > 0) {
                if ($id == $iddb){
                    if (empty($password)) {
                        $sql_update="UPDATE `users` SET `username` = ?, `name` = ?, `role` = ?, `is_active` = ? WHERE `users`.`id` = ?";
                        query($sql_update,[$username, $name, $role, $is_active, $id]);
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $sql_update="UPDATE `users` SET `username` = ?, `password` = ? , `name` = ?, `role` = ?, `is_active` = ? WHERE `users`.`id` = ?";
                        query($sql_update,[$username, $hashed_password, $name, $role, $is_active, $id]);
                    }
                    echo '<div class="ok-message">Data berhasil di update</div>';
                } else {
                    echo '<div class="error-message">Username Telah di gunakan Oranglain</div>'; 
                }
            } else {
                if (empty($password)) {
                        $sql_update="UPDATE `users` SET `username` = ?, `name` = ?, `role` = ?, `is_active` = ? WHERE `users`.`id` = ?";
                        query($sql_update,[$username, $name, $role, $is_active, $id]);
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $sql_update="UPDATE `users` SET `username` = ?, `password` = ? , `name` = ?, `role` = ?, `is_active` = ? WHERE `users`.`id` = ?";
                        query($sql_update,[$username, $hashed_password, $name, $role, $is_active, $id]);
                    }
                    echo '<div class="ok-message">Data berhasil di update</div>';    
            }
            
        }
    }
}