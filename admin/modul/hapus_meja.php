<?php
session_start();
include "database.php";
$id_meja=$_GET['id'] ?? '';
$sql="DELETE FROM `tables` WHERE id = ? ";
query($sql,[$id_meja]);
?>