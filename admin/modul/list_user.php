<table>
    <thead>
        <tr>
            <th>Nama Karyawan</th>
            <th>Username</th>
            <th>Role / Jabatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

        <?php
        if (isset($_GET['cat']) && $_GET['cat']=='terhapus' ){
            $sql = "SELECT *  FROM `users` WHERE `is_active` = 3";
        } else {
            $sql = "SELECT *  FROM `users` WHERE `is_active` = 1 or is_active = 2";
        }
        
        $result = fetchAll($sql);

        foreach ($result as $row) {
            $name = $row['name'];
            $username = $row['username'];
            $role = $row['role'];
            $status = $row['is_active'];
            $id = $row['id'];

            if ($status == 1) {
                $statusBadge = 'badge-success';
                $statusText = 'Aktif';
            } else if ($status == 2){
                $statusBadge = 'badge-danger';
                $statusText = 'Nonaktif';
            } else if ($status==3) {
                $statusBadge = 'badge-danger';
                $statusText = 'Terhapus';
            } else {
                $statusBadge = 'badge-success';
                $statusText = 'Aktif';
            }

            $idsession = $_SESSION['user']['id'];
            if ($id === $idsession && $role === 'admin') {
                $confirm = "alert('Maaf Tidak Bisa Menghapus Diri Sendiri atau User Admin')";
            } else {
                $confirm = "confirm('Apakah anda mau hapus user " . $username . " ? ') && del('user','".$username."','".$id."','list_user','table-user','delete')";
            }
        ?>
            <tr>
                <td><strong><?= $name ?></strong></td>
                <td><?= $username ?></td>
                <td><?= $role ?></td>
                <td><span class="<?= $statusBadge ?>"><?= $statusText ?></span></td>
                <td>
                    <?php
                    if(isset($_GET['cat']) && $_GET['cat']==='terhapus') {
?>
                    <button class="btn btn-warning btn-sm" onclick="confirm('User <?= $username; ?> Telah terhapus dan tidak dapat login \nApakah anda mau mengembalikan hak akses User ini ? ') && del('statuser','<?= $username; ?>','<?= $id; ?>','list_user&cat=terhapus','table-user','restore')"><i class="fa-solid fa-undo"></i></button>
<?php
                    } else {
                    ?>
                    <button class="btn btn-warning btn-sm" onclick="route('form_edit_user&id=<?= $id;  ?>','popupcontent','1','true');"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="<?= $confirm ?>"><i class="fa-solid fa-trash"></i></button> <?php } ?>
                </td>
            </tr>
        <?php
        }
        ?>

        <!--
        <tr>
            <td><strong>Rian Hidayat</strong></td>
            <td>rian_admin</td>
            <td>Admin Toko</td>
            <td><span class="badge badge-success">Aktif</span></td>
            <td>
                <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
            </td>
        </tr>
        <tr>
            <td><strong>Linda Putri</strong></td>
            <td>linda_kasir</td>
            <td>Kasir Malam</td>
            <td><span class="badge badge-danger">Nonaktif</span></td>
            <td>
                <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></button>
                <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
            </td>
        </tr> -->
    </tbody>
</table>

