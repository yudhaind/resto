<?php
if (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] === 'admin') {
    // User is logged in and has the admin role
    $id_meja=$_GET['id'];
    $sql="DELETE FROM `tables` WHERE id='$id_meja'";
    query($sql);

echo $id_meja;
} else {
    // User is not logged in or does not have the admin role
    header('Location: logout.php'); // Redirect to login page
    exit();
}

?>