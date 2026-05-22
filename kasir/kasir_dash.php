<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Kasir Terpadu</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f4f6f9; padding: 15px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; }
        
        @media (min-width: 768px) {
            .container { flex-direction: row; align-items: flex-start; }
            .menu-section { flex: 1.8; }
            .checkout-section { flex: 1.2; position: sticky; top: 15px; }
        }

        .card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 15px; }
        h2 { font-size: 16px; margin-bottom: 15px; color: #4a90e2; border-bottom: 2px solid #f0f4f8; padding-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* Form Field */
        .form-row { display: flex; gap: 10px; margin-bottom: 10px; }
        .form-group { flex: 1; }
        label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px; color: #666; }
        select, input[type="text"], input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ccd4e0; border-radius: 6px; font-size: 14px; }
        
        /* Radio Box Opsi Bayar */
        .option-container { display: flex; gap: 15px; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px dashed #cbd5e1; }
        .radio-label { display: flex; align-items: center; gap: 6px; font-size: 14px; cursor: pointer; font-weight: 500; }

        /* Grid Produk */
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 10px; }
        .product-button { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; text-align: center; cursor: pointer; transition: all 0.2s; }
        .product-button:hover { border-color: #4a90e2; background: #fdfeff; }
        .p-name { font-weight: 600; font-size: 14px; }
        .p-price { color: #e28743; font-size: 12px; font-weight: bold; margin-top: 3px; }

        /* Item Keranjang */
        .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #edf2f7; }
        .cart-info { flex: 2; }
        .cart-name { font-size: 14px; font-weight: 600; }
        .cart-price { font-size: 12px; color: #777; }
        .cart-qty-controls { display: flex; align-items: center; gap: 8px; }
        .btn-qty { background: #edf2f7; border: none; width: 26px; height: 26px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        
        /* Kalkulator Pembayaran */
        .calc-box { background: #f8fafc; border-radius: 8px; padding: 15px; margin-top: 15px; border: 1px solid #e2e8f0; }
        .calc-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; align-items: center; }
        .calc-row.total { font-size: 18px; font-weight: bold; border-top: 2px dashed #cbd5e1; padding-top: 10px; color: #1e293b; }
        .input-money { width: 130px; padding: 6px; text-align: right; font-size: 15px; font-weight: 600; }
        
        .text-kembalian { font-weight: bold; color: #2ecc71; }
        .text-kembalian.minus { color: #e74c3c; }

        .btn-submit { width: 100%; color: white; border: none; padding: 14px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 15px; transition: background 0.2s; }
        .btn-submit.btn-nanti { background: #e28743; }
        .btn-submit.btn-nanti:hover { background: #cd7633; }
        .btn-submit.btn-sekarang { background: #2ecc71; }
        .btn-submit.btn-sekarang:hover { background: #27ae60; }
        .btn-submit:disabled { background: #cbd5e1 !important; cursor: not-allowed; }
        .empty-text { color: #aaa; text-align: center; padding: 15px; font-style: italic; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    
    <!-- SISI KIRI: INPUT DATA & MENU MAKANAN -->
    <div class="menu-section">
        <!-- Info Transaksi -->
        <div class="card">
            <h2>1. Detail Pelanggan & Alur Bayar</h2>
            <div class="form-row">
                <div class="form-group">
                    <label>Nomor Meja</label>
                    <select id="select-meja">
                        <option value="1">Meja 01</option>
                        <option value="2">Meja 02</option>
                        <option value="3">Meja 03</option>
                        <option value="4">Meja 04</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" id="input-nama" placeholder="Nama (opsional)">
                </div>
            </div>

            <!-- Opsi Pilihan Alur Kerja -->
            <div class="form-group" style="margin-top: 10px;">
                <label>Opsi Pembayaran</label>
                <div class="option-container">
                    <label class="radio-label">
                        <input type="radio" name="payment_type" value="nanti" checked> Bayar Nanti (Makan Dulu)
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="payment_type" value="sekarang"> Bayar Langsung (Lunas)
                    </label>
                </div>
            </div>
        </div>

        <!-- Pilih Menu -->
        <div class="card">
            <h2>2. Pilih Menu</h2>
            <div class="menu-grid">
                <!-- Contoh Data Menu -->
                <div class="product-button" data-id="10" data-nama="Nasi Goreng" data-harga="25000">
                    <div class="p-name">Nasi Goreng</div>
                    <div class="p-price">Rp 25.000</div>
                </div>
                <div class="product-button" data-id="11" data-nama="Mie Goreng" data-harga="22000">
                    <div class="p-name">Mie Goreng</div>
                    <div class="p-price">Rp 22.000</div>
                </div>
                <div class="product-button" data-id="14" data-nama="Es Teh Manis" data-harga="5000">
                    <div class="p-name">Es Teh Manis</div>
                    <div class="p-price">Rp 5.000</div>
                </div>
                <div class="product-button" data-id="15" data-nama="Jeruk Peras" data-harga="7000">
                    <div class="p-name">Jeruk Peras</div>
                    <div class="p-price">Rp 7.000</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SISI KANAN: KERANJANG & KAKULATOR HITUNG OTOMATIS -->
    <div class="checkout-section">
        <div class="card">
            <h2>3. Ringkasan & Kalkulator</h2>
            
            <!-- FORM FORMAL UNTUK SERIALIZE JQUERY -->
            <form id="form-kasir">
                <!-- Input Kontrol Hidden -->
                <input type="hidden" name="table_id" id="hidden-table-id" value="1">
                <input type="hidden" name="cashier_id" value="1"> <!-- ID Kasir bertugas -->
                <input type="hidden" name="customer_name" id="hidden-customer-name" value="">
                <input type="hidden" name="payment_type" id="hidden-payment-type" value="nanti">

                <!-- Daftar Item Belanja -->
                <div id="box-item-belanja">
                    <p class="empty-text">Belum ada menu dipilih</p>
                </div>

                <!-- Blok Perhitungan Uang -->
                <div class="calc-box">
                    <div class="calc-row total">
                        <span>Total:</span>
                        <input type="hidden" id="num-total-harga" value="0">
                        <span id="text-total-harga">Rp 0</span>
                    </div>

                    <!-- Area Input ini dinamis aktif/mati bergantung opsi pembayaran -->
                    <div class="calc-row section-kalkulator" style="display: none; margin-top: 15px;">
                        <label style="margin: 0;">Uang Tunai (Rp):</label>
                        <input type="number" name="amount_paid" id="input-cash" class="input-money" value="0" min="0">
                    </div>

                    <div class="calc-row section-kalkulator" style="display: none; border-top: 1px dashed #cbd5e1; padding-top: 10px;">
                        <span>Kembalian:</span>
                        <span id="text-kembalian" class="text-kembalian">Rp 0</span>
                    </div>
                </div>

                <!-- Tombol Dinamis -->
                <button type="submit" class="btn-submit btn-nanti" id="btn-aksi-utama" disabled>Simpan Pesanan (Bayar Nanti)</button>
            </form>
        </div>
    </div>

</div>

<!-- SCRIPT JQUERY -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // 1. Sinkronisasi data dasar ke input form hidden
    $('#select-meja').on('change', function() { $('#hidden-table-id').val($(this).val()); });
    $('#input-nama').on('input', function() { $('#hidden-customer-name').val($(this).val()); });

    // 2. Kontrol Opsi "Bayar Nanti" vs "Bayar Langsung"
    $('input[name="payment_type"]').on('change', function() {
        var tipe = $(this).val();
        $('#hidden-payment-type').val(tipe);

        if(tipe === 'sekarang') {
            $('.section-kalkulator').show(); // Tampilkan input uang & kembalian
            $('#btn-aksi-utama').removeClass('btn-nanti').addClass('btn-sekarang').text('Proses & Cetak Lunas');
            $('#input-cash').val('').focus();
        } else {
            $('.section-kalkulator').hide(); // Sembunyikan kalkulator uang
            $('#btn-aksi-utama').removeClass('btn-sekarang').addClass('btn-nanti').text('Simpan Pesanan (Bayar Nanti)');
        }
        validasiTombol();
    });

    // 3. Tambah Menu ke Keranjang
    $('.product-button').on('click', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var harga = parseInt($(this).data('harga'));

        $('.empty-text').remove();
        var itemEksis = $('#cart-' + id);

        if(itemEksis.length > 0) {
            var inputQty = itemEksis.find('.input-qty');
            var qtyBaru = parseInt(inputQty.val()) + 1;
            inputQty.val(qtyBaru);
            itemEksis.find('.text-qty').text(qtyBaru);
        } else {
            var html = `
                <div class="cart-item" id="cart-${id}" data-harga="${harga}">
                    <div class="cart-info">
                        <div class="cart-name">${nama}</div>
                        <div class="cart-price">Rp ${harga.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="cart-qty-controls">
                        <input type="hidden" name="product_id[]" value="${id}">
                        <input type="hidden" name="price[]" value="${harga}">
                        <input type="hidden" name="quantity[]" value="1" class="input-qty">
                        
                        <button type="button" class="btn-qty btn-min" data-id="${id}">-</button>
                        <span class="text-qty">1</span>
                        <button type="button" class="btn-qty btn-plus" data-id="${id}">+</button>
                    </div>
                </div>
            `;
            $('#box-item-belanja').append(html);
        }
        hitungTotalNota();
    });

    // 4. Tombol Plus & Minus Quantity
    $(document).on('click', '.btn-plus', function() {
        var id = $(this).data('id');
        var item = $('#cart-' + id);
        var input = item.find('.input-qty');
        var qty = parseInt(input.val()) + 1;
        input.val(qty); item.find('.text-qty').text(qty);
        hitungTotalNota();
    });

    $(document).on('click', '.btn-min', function() {
        var id = $(this).data('id');
        var item = $('#cart-' + id);
        var input = item.find('.input-qty');
        var qty = parseInt(input.val()) - 1;

        if(qty <= 0) {
            item.remove();
            if($('#box-item-belanja').children().length === 0) {
                $('#box-item-belanja').html('<p class="empty-text">Belum ada menu dipilih</p>');
            }
        } else {
            input.val(qty); item.find('.text-qty').text(qty);
        }
        hitungTotalNota();
    });

    // 5. Fungsi Hitung Total Harga & Kembalian
    function hitungTotalNota() {
        var total = 0;
        $('.cart-item').each(function() {
            var harga = parseInt($(this).data('harga'));
            var qty = parseInt($(this).find('.input-qty').val());
            total += (harga * qty);
        });

        $('#num-total-harga').val(total);
        $('#text-total-harga').text('Rp ' + total.toLocaleString('id-ID'));
        
        kalkulatorKembalian();
    }

    // Hitung kembalian secara real-time saat mengetik uang
    $('#input-cash').on('input', function() {
        kalkulatorKembalian();
    });

    function kalkulatorKembalian() {
        var total =  parseInt($('#num-total-harga').val()) || 0;
        var bayar = parseInt($('#input-cash').val()) || 0;
        var tipe = $('#hidden-payment-type').val();

        if (tipe === 'nanti') {
            validasiTombol();
            return;
        }

        var sisa = bayar - total;
        if(sisa < 0) {
            $('#text-kembalian').addClass('minus').text('Uang Kurang: -Rp ' + Math.abs(sisa).toLocaleString('id-ID'));
        } else {
            $('#text-kembalian').removeClass('minus').text('Rp ' + sisa.toLocaleString('id-ID'));
        }
        validasiTombol();
    }

    // Fungsi validasi tombol submit aktif / mati
    function validasiTombol() {
        var totalItem = $('.cart-item').length;
        var totalHarga = parseInt($('#num-total-harga').val()) || 0;
        var uangBayar = parseInt($('#input-cash').val()) || 0;
        var tipe = $('#hidden-payment-type').val();

        if (totalItem === 0) {
            $('#btn-aksi-utama').prop('disabled', true);
            return;
        }

        if (tipe === 'sekarang' && uangBayar < totalHarga) {
            $('#btn-aksi-utama').prop('disabled', true); // Kunci jika uang kurang pada bayar langsung
        } else {
            $('#btn-aksi-utama').prop('disabled', false);
        }
    }

    // 6. SUBMIT DATA AJAX (DATA FORM SERIALIZE)
    $('#form-kasir').on('submit', function(e) {
        e.preventDefault();
        var dataSerialize = $(this).serialize();

        $.ajax({
            url: 'simpan_pesanan.php',
            type: 'POST',
            data: dataSerialize,
            success: function(res) {
                if(res.trim() === "SUKSES") {
                    alert('Transaksi Berhasil Diproses!');
                    // Reset Halaman ke awal
                    $('#box-item-belanja').html('<p class="empty-text">Belum ada menu dipilih</p>');
                    $('#input-nama').val('');
                    $('#input-cash').val('0');
                    hitungTotalNota();
                } else {
                    alert('Gagal: ' + res);
                }
            },
            error: function() { alert('Koneksi internet bermasalah.'); }
        });
    });

});
</script>
</body>
</html>