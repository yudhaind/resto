<h1>Edit Nama Toko</h1>
<div class="sub-popup-content">
<form id="form-edit-namatoko" method="post" action="post.php" class="form-modern">
            <?php
$part=$_GET['part'] ?? '';
$tokenform = bin2hex(random_bytes(32));
$_SESSION['token'] = $tokenform;
switch($part){
    case 'namatoko':
        $sql="SELECT value FROM global_settings WHERE `label`='nama_toko'";
        $result = fetchOne($sql);
        ?>
      
      
            <input type="hidden" name="action" value="edit_namatoko">
            <input type="hidden" name="tokenform" id="tokenform" value="<?php echo $_SESSION['token']; ?>">
            <div class="form-group">
                <label for="namatoko">Nama Toko:</label>
                <input type="text" id="namatoko" name="namatoko" value="<?php echo $result['value']; ?>" required>
            </div>
           
        <?php
        break;
    default:
        echo '<div class="popup-content"><h2>Part not found</h2></div>';
    break;
}
?>
<div id="result"></div>
<button type="submit" class="btn btn-primary">Simpan</button>
</form>
</div>

<script>
    submitForm('form-edit-namatoko', 'result', 'null', function() {
        $('#nama-toko').text($('#namatoko').val());
        $('#nama-toko-main').text($('#namatoko').val());
    });
</script>