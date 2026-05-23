<?php 
session_start();
require_once 'database.php';
$role = $_SESSION['user']['role'];
$files = glob($role . '/modul/*.php');
$allowed_pages = array_map(function($file){
    return basename($file, '.php');
}, $files);

$page = $_GET['page'] ?? '';


if (isset($_SESSION['user']['id'])){
    if (isset($_POST['globaltoken']) && $_POST['globaltoken'] === $_SESSION['globaltoken']) {
    // Validasi token berhasil, lanjutkan dengan memproses permintaan AJAX
     if (in_array($page, $allowed_pages)) {
                include ($role . '/modul/' . $page . '.php');
            } else {
                echo 'PAGE_NOT_FOUND';
            }
    } else {
        echo 'SESSION_EXPIRED';
    }
} else {
    echo 'SESSION_EXPIRED';
}
?>