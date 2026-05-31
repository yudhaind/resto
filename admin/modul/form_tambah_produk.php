<?php 
$tokenform = bin2hex(random_bytes(32));
$_SESSION['token'] = $tokenform;
?>
<h1>Tambah Produk</h1>
<div class="sub-popup-content">
    <form class="form-modern" action="post.php" method="POST" id="form-tambah-produk">
        <div class="form-group">
            <input type="hidden" name="tokenform" id="tokenform" value="<?= $_SESSION['token']; ?>">
            <input type="hidden" name="action" id="action" value="tambah_produk">
            <label for="cat">Jenis Produk</label>
            <select name="cat" id="cat" required>
                <option value="" selected>Pilih Jenis Produk</option>
                <option value="food">Makanan</option>
                <option value="drink">Minuman</option>
                <option value="snack">Camilan</option>
                <option value="dessert">Dessert</option>
            </select>

            <label for="namamenu">Nama Menu :</label>
            <input type="text" name="namamenu" id="namamenu" placeholder="Makanan / Minuman / Desert /Snack" required>

            <label for="harga">Harga :</label>
            <input type="text" name="price" id="price" onkeyup="formatRupiah(this)" maxlength="8" required placeholder="Harga">

            <label for="status">Status :</label>
            <select name="status" id="status" required>
                <option value="" required selected>Pilih Salah status</option>
                <option value="1">Tersedia</option>
                <option value="2">Kosong</option>
            </select>

        </div>
        <div id="result"></div>
        <input type="submit" class="btn-modern" value="Tambah Produk">

    </form>
</div>
<script>

    submitForm('form-tambah-produk', 'result', 'null', function() {
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