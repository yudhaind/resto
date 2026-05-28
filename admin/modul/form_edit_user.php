<?php
$tokenform = bin2hex(random_bytes(32));
$_SESSION['token'] = $tokenform;

$id=$_GET['id'] ?? '';

$sql="SELECT *  FROM `users` WHERE `id` = ?";
$rslt=fetchOne($sql,[$id]);
$name=$rslt['name'];
$role=$rslt['role'];
$stat=$rslt['is_active'];
$username=$rslt['username'];
$password=$rslt['password'];

?>

<h1>Edit User</h1>
<div class="sub-popup-content">
<form id="form-edit-user" name="form-tambah-user" class="form-modern" method="POST" action="post.php">
    <div class="form-group">
        <input type="hidden" name="action" id="action" value="edit_user">
        <input type="hidden" name="tokenform" id="tokenform" value="<?= $_SESSION['token']; ?>">
        <input type="hidden" name="id" id="id" value="<?= $id; ?>"
        <label for="name">Nama Karyawan</label>
        <input type="text" id="name" name="name" placeholder="Masukkan nama karyawan" value="<?= $name ?>" required>
    </div>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Masukkan username" value="<?= $username ?>" required>
    </div>

    <div class="form-group">
        <label for="password">Password <i>(*Kosongkan jika password tidak di ganti)</i></label>
        <input type="password" id="password" name="password" placeholder="Masukkan password">
    </div>

    <div class="form-group">
        <label for="role">Role / Jabatan</label>
        <select id="role" name="role" required>
            <option value="">Pilih Role</option>
            <option value="admin" <?php if($role=='admin'){echo ' selected';}  ?>>Admin</option>
            <option value="cashier" <?php if($role=='cashier'){echo ' selected';} ?>>Kasir</option>
        </select>

        <label for="is_active">Status Aktif</label>
        <select id="is_active" name="is_active" required>
            <option value="">Pilih Status</option>
            <option value="1" <?php if ($stat=='1'){ echo ' selected';} ?>>Aktif</option>
            <option value="2" <?php if ($stat=='2'){ echo ' selected';} ?>>Nonaktif</option>
        </select>
    </div>

    <div id="result"></div>

    <button type="submit" class="btn-modern">Simpan User</button>
</form>
</div>
<script>
    submitForm('form-edit-user', 'result', 'null', function() {
        route('list_user', 'table-user', '0', 'false');
    });
</script> 