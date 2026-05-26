<?php 
$tokenid=bin2hex(random_bytes(32));
$_SESSION['token']=$tokenid;
?>
<h1>Tambah Meja Baru</h1>
<div>
    <form id="form-tambah-meja" class="form-modern" action="post.php" method="POST">
        <input type="hidden" name="action" value="tambah_meja">
        <input type="hidden" name="tokenform" value="<?= $_SESSION['token']; ?>">
        <div class="form-group">
            <label for="nomor_meja">Nomor Meja</label>
            <input type="text" id="nomor_meja" name="nomor_meja" placeholder="Masukkan nomor meja" required>
        </div>
        <div class="form-group">
            <label for="kapasitas_meja">Kapasitas Meja</label>
            <input type="number" id="kapasitas_meja" name="kapasitas_meja" placeholder="Masukkan kapasitas meja" value="2" required>
        </div>
        <button type="submit" class="btn btn-modern">Tambah Meja</button>
    </form>
    <div id="result" style="margin-top: 15px;"></div>
</div>
<script>
            submitForm('form-tambah-meja', 'result', 'reset');
</script>