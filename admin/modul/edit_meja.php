<?php
$tokenid = bin2hex(random_bytes(32));
$_SESSION['token'] = $tokenid;
$id_meja = isset($_GET['id']) ? $_GET['id'] : null;
$sql = "SELECT * FROM `tables` WHERE id = ?";
$data_meja = fetchOne($sql, [$id_meja]);
?>

<h1>Edit Meja</h1>
<div>
    <form id="form-edit-meja" class="form-modern" action="post.php" method="POST">
        <input type="hidden" name="id" value="<?= $data_meja['id'] ?? ''; ?>">
        <input type="hidden" name="action" value="update_meja">
        <input type="hidden" name="tokenform" value="<?= $_SESSION['token']; ?>">

        <div class="form-group">
            <label for="nomor_meja">Nomor Meja</label>
            <input type="text" id="nomor_meja" name="nomor_meja" placeholder="Masukkan nomor meja" value="<?= $data_meja['table_number'] ?? ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="kapasitas_meja">Kapasitas Meja</label>
            <input type="number" id="kapasitas_meja" name="kapasitas_meja" placeholder="Masukkan kapasitas meja" value="<?= $data_meja['capacity'] ?? '2'; ?>" required>
        </div>

        <button type="submit" class="btn btn-modern">Edit Meja</button>
    </form>

    <div id="result" style="margin-top: 15px;"></div>
</div>

<script>
    submitForm('form-edit-meja', 'result', 'reset');
</script>