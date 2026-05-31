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
        foreach ($enum_array as $produk) {
            // Menentukan icon berdasarkan kategori
            switch ($produk) {
                case 'food':    $icon = "fa-utensils"; break;
                case 'drink':   $icon = "fa-glass-water"; break;
                case 'snack':   $icon = "fa-cookie-bite"; break;
                case 'dessert': $icon = "fa-ice-cream"; break;
                default:        $icon = "fa-list"; break;
            }
        ?>
            <tr class="pembatas-kategori kategori-<?= $produk; ?>" data-target="<?= $produk; ?>-item" style="cursor: pointer;">
                <td colspan="5" class="py-2.5 px-3 fw-bold table-light">
                    <i class="fa-solid <?= $icon; ?> me-2"></i> <?= ucfirst($produk); ?>
                </td>
            </tr>
            
            <?php
            $sql_item = "SELECT * FROM `products` WHERE `category` = ?";
            $rslt_item = fetchAll($sql_item, [$produk]);
            $count_item = numRows($sql_item, [$produk]);

            // PERBAIKAN 1: Logika diubah, jika count_item == 0 berarti kosong
            if ($count_item == 0) { 
                // PERBAIKAN 4: Ditambahkan colspan="5" agar tidak merusak layout
                echo '<tr class="' . $produk . '-item">
                        <td colspan="5" class="text-muted text-center italic py-3">Belum ada item di kategori ini.</td>
                      </tr>';
            } else {
                foreach ($rslt_item as $itm) {
                    // PERBAIKAN 2: Menggunakan data asli dari database ($itm)
                    // PERBAIKAN 3: Menambahkan class target untuk JQuery ($produk.-item)
                    $id_item=$itm['id'];
                    ?>
                    <tr class="<?= $produk; ?>-item">
                        <td><strong><?= htmlspecialchars($itm['name']); // Sesuaikan dengan nama kolom Anda ?></strong></td>
                        <td><?= ucfirst($produk); ?></td>
                        <td>Rp <?= number_format($itm['price'], 0, ',', '.'); // Sesuaikan dengan kolom harga ?></td>
                        <td>
                            <?php if($itm['is_available'] == 1): ?>
                                <span class="badge ok-message" style="font-size: 11px;">Tersedia</span>
                            <?php else: ?>
                                <span class="badge error-message" style="font-size: 11px;">Habis</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="route('edit_item&id=<?= $id_item; ?>', 'popupcontent', '1', 'true')"><i class="fa-solid fa-pen"></i> Edit</button>
                        </td>
                    </tr>
            <?php
                }
            }
        }
        ?>
    </tbody>
</table>

<script>
$(document).ready(function() {
    // Event ketika baris kategori diklik
    $('.pembatas-kategori').on('click', function() {
        var targetClass = $(this).data('target');
        // Melakukan toggle sembunyi/tampil pada baris produk terkait
        $('.' + targetClass).toggle(150); 
    });
});
</script>