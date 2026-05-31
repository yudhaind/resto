<?php 
$tokenform = bin2hex(random_bytes(32));
$_SESSION['token'] = $tokenform;

function formatRupiah($angka) {
    // 1. Ambil nilai, hapus semua karakter selain angka (\D diganti dengan [^0-9])
    $value = preg_replace('/[^0-9]/', '', $angka);
    
    // Jika setelah dibersihkan hasilnya kosong atau bukan angka, kembalikan string kosong
    if ($value === '') {
        return '';
    }
    
    // 2. Ubah angka menjadi format ribuan dengan titik (tanpa desimal)
    // Parameter: (angka, jumlah desimal, pemisah desimal, pemisah ribuan)
    $formatted = number_format((float)$value, 0, ',', '.');
    
    return $formatted;
}


$id_pr = $_GET['id'] ?? '';
$sql = "SELECT * FROM `products` WHERE id = ?";
$hasil = fetchOne($sql,[$id_pr]);
$cat = $hasil['category'];
$status = $hasil['is_available'];
?>
<h1>Tambah Produk</h1>
<div class="sub-popup-content">
    <form class="form-modern" action="post.php" method="POST" id="form-ubah-produk">
        <div class="form-group">
            <input type="hidden" name="tokenform" id="tokenform" value="<?= $_SESSION['token']; ?>">
            <input type="hidden" name="action" id="action" value="ubah_produk">
            <input type="hidden" name="id_item" id="action" value="<?= $id_pr; ?>"
            <label for="cat">Jenis Produk</label>
            <select name="cat" id="cat" required>
                <option value="food" <?= $cat == 'food' ? 'selected' : ''; ?>>Makanan</option>
                <option value="drink" <?= $cat == 'drink' ? 'selected' : ''; ?>>Minuman</option>
                <option value="snack" <?= $cat == 'snack' ? 'selected' : ''; ?>>Camilan</option>
                <option value="dessert" <?= $cat == 'dessert' ? 'selected' : ''; ?>>Dessert</option>
            </select>

            <label for="namamenu">Nama Menu :</label>
            <input type="text" name="namamenu" id="namamenu" placeholder="Makanan / Minuman / Desert /Snack" required value="<?= $hasil['name']; ?>">

            <label for="harga">Harga :</label>
            <input type="text" name="price" id="price" onkeyup="formatRupiah(this)" maxlength="8" required placeholder="Harga" value="<?= formatRupiah($hasil['price']); ?>">

            <label for="status">Status :</label>
            <select name="status" id="status" required>
                <option value="" required selected>Pilih Salah status</option>
                <option value="1" <?= $status == '1' ? 'selected' : ''; ?>>Tersedia</option>
                <option value="2" <?= $status == '2' ? 'selected' : ''; ?>>Kosong</option>
            </select>

        </div>
        <div id="result"></div>
        <input type="submit" class="btn-modern" value="Simpan Produk">

    </form>
</div>
<script>

    submitForm('form-ubah-produk', 'result', 'null', function() {
        route('list_produk', 'table-product', '0', 'false');
    });
    
    function formatRupiah(input) {
    // Ambil value, hapus semua karakter selain angka
    let value = input.value.replace(/\D/g, '');
    
    // Ubah angka menjadi format ribuan dengan titik
    let formatted = new Intl.NumberFormat('id-ID').format(value);
    
    // Jika input kosong, tampilkan kosong, jika ada tampilkan yang sudah diformat
    input.value = value ? formatted : '';  
}


</script>