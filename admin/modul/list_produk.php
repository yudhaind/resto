<?php
$sql = "SELECT COLUMN_TYPE
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = 'db_resto'
        AND TABLE_NAME = 'products'
        AND COLUMN_NAME = 'category'";
$h_enum = fetchOne($sql);
$enum_mentah = $h_enum['COLUMN_TYPE'];
$cleaned = substr($enum_mentah, 5, -1);
$cleaned = str_replace("'", "", $cleaned);
$enum_array = explode(",", $cleaned);
?>

<table class="table table-bordered align-middle">
    <thead>
        <tr class="table-secondary">
            <th>Nama Menu</th>
            <th>Kategori</th>
            <th>Harga Jual</th>
            <th>Stok Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $icon = '';
        foreach ($enum_array as $produk) {
            switch ($produk) {
                case 'food':
                    $icon = "fa-utensils";
                    break;
                case 'drink':
                    $icon = "fa-glass-water";
                    break;
                case 'snack':
                    $icon = "fa-cookie-bite";
                    break;
                case 'dessert':
                    $icon = "fa-ice-cream";
                    break;
            }
        ?>
            <tr class="pembatas-kategori kategori-<?= $produk; ?>">
                <td colspan="5" class="py-2.5 px-3">
                    <i class="fa-solid <?= $icon; ?>"></i> <?= $produk; ?>
                </td>
            </tr>
            <?php
            $sql_item = "SELECT * FROM `products` WHERE `category` = ?";
            $rslt_item = fetchAll($sql_item, [$produk]);

            foreach ($rslt_item as $itm) {
            ?>
                <tr>
                    <td><strong>Nasi Goreng Spesial</strong></td>
                    <td>Makanan Utama</td>
                    <td>Rp 25.000</td>
                    <td><span class="badge badge-success">Tersedia</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i> Edit</button>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
        <!--
        <tr class="pembatas-kategori kategori-minuman">
            <td colspan="5" class="py-2.5 px-3">
                <i class="fa-solid fa-glass-water"></i> Kelompok Menu: Minuman
            </td>
        </tr>
        <tr>
            <td><strong>Es Teh Manis</strong></td>
            <td>Minuman</td>
            <td>Rp 5.000</td>
            <td><span class="badge badge-success">Tersedia</span></td>
            <td>
                <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i> Edit</button>
            </td>
        </tr>
        -->
    </tbody>
</table>
