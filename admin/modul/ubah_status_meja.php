<?php
session_start();
require_once 'database.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('location:logout.php');
    exit;
} else {
    $id_meja = $_GET['id_meja'] ?? '';
    $token = $_GET['token'] ?? '';
    if ($token !== $_SESSION['globaltoken']) {
        die("Token tidak valid!");
    } else {
        if (empty($id_meja)) {
            die("ID meja tidak valid!");
        } else {
            $sql_c="SELECT * FROM `tables` WHERE id = ?";
            $rsl_c=fetchOne($sql_c, [$id_meja]);
            $status_meja = $rsl_c['status'];
                if ($status_meja == 'available') {
                    $status_baru = 'occupied';
                } else {
                    $status_baru = 'available';
                }
            $sql_u="UPDATE `tables` SET `status` = ? WHERE `tables`.`id` = ?";
            query($sql_u, [$status_baru, $id_meja]);
        }
    }
}        
?>