<?php
$tokenform = bin2hex(random_bytes(32));
$_SESSION['token'] = $tokenform;
?>

<h1>Tambah User</h1>
<div class="sub-popup-content">
<form id="form-tambah-user" name="form-tambah-user" class="form-modern" method="POST" action="post.php">
    <div class="form-group">
        <input type="hidden" name="action" id="action" value="tambah_user">
        <input type="hidden" name="tokenform" id="tokenform" value="<?= $_SESSION['token']; ?>">
        <label for="name">Nama Karyawan</label>
        <input type="text" id="name" name="name" placeholder="Masukkan nama karyawan" required>
    </div>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Masukkan username" required>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
    </div>

    <div class="form-group">
        <label for="role">Role / Jabatan</label>
        <select id="role" name="role" required>
            <option value="">Pilih Role</option>
            <option value="admin">Admin</option>
            <option value="cashier">Kasir</option>
        </select>

        <label for="is_active">Status Aktif</label>
        <select id="is_active" name="is_active" required>
            <option value="">Pilih Status</option>
            <option value="1">Aktif</option>
            <option value="2">Nonaktif</option>
        </select>
    </div>

    <div id="result"></div>

    <button type="submit" class="btn-modern">Simpan User</button>
</form>
</div>
<script>
    submitForm('form-tambah-user', 'result', 'null', function() {
        route('list_user', 'table-user', '0', 'false');
    });
</script> 