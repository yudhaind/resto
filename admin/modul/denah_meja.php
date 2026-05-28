<?php
$sql="SELECT * FROM `tables`";
$result=fetchAll($sql);
$jumlah_meja = numRows($sql);
if ($jumlah_meja == 0) {
    echo '<div class="error-message">Belum ada meja yang ditambahkan. Silakan tambahkan meja terlebih dahulu.</div>';
}
else {
foreach($result as $row){
    $nomor_meja=$row['table_number'];
    $id_meja=$row['id'];
    $status_meja=$row['status'];
    if ($status_meja=='available'){
            $status_class='status-kosong';
            $status_text='Kosong';
            $status_button_text='Isi Manual';
        } else if ($status_meja=='occupied'){
            $status_class='status-terisi'; 
            $status_text='Terisi (Makan)';
            $status_button_text='Kosongkan Meja';
        } else {
            $status_class='status-kosong';
            $status_text='Kosong';
            $status_button_text='Isi Manual';
  
        }

                ?>
                    <!-- Meja 1 -->
                    <div class="card-meja <?= $status_class; ?>" data-nomor="<?= $id_meja ?>">
                        <div class="meja-actions">
                            <button class="btn-action-meja edit" onclick="route('edit_meja&id=<?= $id_meja; ?>', 'popupcontent', '1', 'true')"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-action-meja delete btn-hapus-meja" onclick="if(confirm('hapus meja')) { route('hapus_meja&id=<?= $id_meja ?>', 'popupcontent', '0', 'false'); }"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div class="meja-icon"><i class="fa-solid fa-couch meja-icon-color"></i></div>
                        <div class="meja-name"><?= $nomor_meja ?></div>
                        <span class="status-indicator <?= $status_class ?>"><?= $status_text ?></span>
                        <button class="btn btn-outline btn-sm btn-ubah-status"><?= $status_button_text; ?></button>
                    </div>
<?php 
    } 
}?>