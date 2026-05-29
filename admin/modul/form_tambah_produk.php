<h1>Tambah Produk</h1>
<div class="sub-popup-content">
    <form class="form-modern">
        <div class="form-group">
            <label for="cat">Jenis Produk</label>
            <select name="cat" id="cat" required>
                <option value="" selected>Pilih Jenis Produk</option>
                <option value="food">Makanan</option>
                <option value="drink">Minuman</option>
                <option value="snack">Camilan</option>
                <option value="dessret">Dessert</option>
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
    function formatRupiah(input) {
    // Ambil value, hapus semua karakter selain angka
    let value = input.value.replace(/\D/g, '');
    
    // Ubah angka menjadi format ribuan dengan titik
    let formatted = new Intl.NumberFormat('id-ID').format(value);
    
    // Jika input kosong, tampilkan kosong, jika ada tampilkan yang sudah diformat
    input.value = value ? formatted : '';
}
</script>