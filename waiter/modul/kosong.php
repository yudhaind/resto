<?php
$id=$_GET['id'] ?? '';
$sql="UPDATE `tables` SET `status` = 'available' WHERE `tables`.`id` = ?";
query($sql,[$id]);
?>