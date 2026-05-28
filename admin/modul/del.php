<?php
$id = $_GET['id'] ?? '';
$parameter  = $_GET['parameter'] ?? '';
if ($parameter === 'user' && $id !== '') {
    $sql = "UPDATE `users` SET `is_active` = '3' WHERE `id` = ?";
    query($sql, [$id]);
} if ($parameter== 'statuser') {
    $sql="UPDATE `users` SET `is_active` = '2' WHERE `id` = ?";
    query($sql,[$id]);
}

?>
