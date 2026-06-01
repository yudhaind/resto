<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Modern - Flexible Payment & Compact</title>
    <link rel="stylesheet" href="assets/css/kasirstyle.css">
    
</head>
<body>

<div class="container">
    <input type="hidden" id="target-sync" value="meja-di-kasir" disabled>
    <div class="menu-section">
        <div class="card" id="section-pelanggan">
            <h2>1. Detail Pelanggan & Meja</h2>
            <div class="form-row">
                <div class="form-group" style="flex: 1.2;">
                    <label>Meja Transaksi</label>
                    <button type="button" class="btn-pilih-meja" id="trigger-modal-meja">
                        <span id="text-meja-terpilih">Klik Pilih Meja...</span>
                        <div class="meja-status-counter">
                            <span class="badge-count ready" title="Ready">4</span>
                            <span class="badge-count full" title="Full">1</span>
                        </div>
                    </button>
                </div>
                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" id="input-nama" placeholder="Nama (opsional)">
                </div>
            </div>
        </div>

        <div class="card" id="section-pilih-menu">
            <h2>2. Pilihan Menu</h2>
            <div class="menu-grid">
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

    <div class="checkout-section">
        <div class="card">
            <h2>3. Ringkasan Pesanan</h2>
            <form id="form-kasir">
                <div id="badge-order-type" class="badge-addon">PESANAN TAMBAHAN (ADD-ON)</div>

                <input type="hidden" name="table_id" id="hidden-table-id" value="">
                <input type="hidden" name="cashier_id" value="1">
                <input type="hidden" name="customer_name" id="hidden-customer-name" value="">
                <input type="hidden" name="payment_method_selected" id="hidden-payment-method" value="tunai">
                <input type="hidden" name="is_addon" id="hidden-is-addon" value="0">

                <div id="box-item-belanja">
                    <p class="empty-text">Keranjang masih kosong</p>
                </div>

                <div class="form-group" style="margin-top: 14px;">
                    <label>Metode Pembayaran</label>
                    <div class="option-container" id="payment-options-wrapper">
                        </div>
                </div>

                <div class="calc-box">
                    <div class="calc-row total">
                        <span id="label-total-harga">Total Harga</span>
                        <input type="hidden" id="num-total-harga" value="0">
                        <span id="text-total-harga">Rp 0</span>
                    </div>
                    <div class="calc-row section-kalkulator" style="margin-top: 8px;">
                        <label style="margin: 0; font-weight: 600;">Uang Tunai</label>
                        <input type="number" name="amount_paid" id="input-cash" class="input-money" value="0" min="0">
                    </div>
                    <div class="calc-row section-kalkulator" style="border-top: 1px dashed var(--border); padding-top: 8px;">
                        <span>Kembalian</span>
                        <span id="text-kembalian" class="text-kembalian">Rp 0</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit btn-sekarang" id="btn-aksi-utama" disabled>Proses & Cetak Lunas</button>
            </form>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modal-meja-overlay">
    <div class="modal-window">
        <div class="modal-header">
            <span class="modal-title">Pilih Nomor Meja</span>
            <button type="button" class="modal-close" id="close-modal-meja">&times;</button>
        </div>
        <div class="meja-grid-modal">
            <?php 
            $sqlmeja="SELECT * FROM `tables`";
            $hasilmeja=fetchAll($sqlmeja);
            foreach($hasilmeja as $itemmeja) {
                $idmeja=$itemmeja['id'];
                $status=$itemmeja['status'];
                $nomeja=$itemmeja['table_number'];
                $kapasitas=$itemmeja['capacity'];
                    if ($status=='available'){
                        $data='ready';
                    } else {
                        $data='full';
                    }
            ?>
            <div class="meja-box kosong" data-meja-id="<?= $idmeja; ?>" data-status="<?= $data; ?>"><span class="m-number"><?= $nomeja; ?></span><span class="m-status">Kapasitas : <?= $kapasitas; ?></span><span class="m-status"><?= $data; ?></span></div>
            <?php } ?>
            <!--
            <div class="meja-box terisi" data-meja-id="2" data-status="full"><span class="m-number">M-02</span><span class="m-status">Full</span></div>
            <div class="meja-box kosong" data-meja-id="3" data-status="ready"><span class="m-number">M-03</span><span class="m-status">Ready</span></div>
            <div class="meja-box kosong" data-meja-id="4" data-status="ready"><span class="m-number">M-04</span><span class="m-status">Ready</span></div>
            <div class="meja-box kosong" data-meja-id="5" data-status="ready"><span class="m-number">M-05</span><span class="m-status">Ready</span></div>
-->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // =========================================================================
    // [EDIT DI SINI] - ARRAY UNTUK MEMBUAT METODE PEMBAYARAN MENJADI FLEXIBLE
    // Kasir/Developer cukup menambah atau menghapus baris di bawah ini
    // =========================================================================
    var listMetodeBayar = [
        { id: "tunai",    label: "Tunai",   defaultChecked: true },
        { id: "qris",     label: "QRIS" },
        { id: "transfer", label: "Transfer" },
        // Contoh cara menambah metode baru, hilangkan tanda // di bawah ini untuk mencobanya:
        // { id: "shopeepay", label: "ShopeePay" }
    ];

    // Fungsi Otomatis Render Tombol Metode Pembayaran ke halaman HTML Ringkas
    function renderMetodePembayaran() {
        var wrapper = $('#payment-options-wrapper');
        wrapper.empty(); // bersihkan area lama

        listMetodeBayar.forEach(function(item) {
            var checkedAttr = item.defaultChecked ? 'checked' : '';
            var htmlButton = `
                <label class="radio-label">
                    <input type="radio" name="payment_method" value="${item.id}" ${checkedAttr}> ${item.label}
                </label>
            `;
            wrapper.append(htmlButton);
        });
    }
    // Jalankan render pembayaran saat aplikasi pertama dimuat
    renderMetodePembayaran();


    // LOGIKA OPEN/CLOSE MODAL MEJA
    $('#trigger-modal-meja').on('click', function() {
        $('#modal-meja-overlay').css('display', 'flex').hide().fadeIn(150);
    });

    $('#close-modal-meja, .modal-overlay').on('click', function(e) {
        if (e.target === this) { $('#modal-meja-overlay').fadeOut(150); }
    });

    // AMBIL DATA PILIHAN MEJA MODAL
    $('.meja-box').on('click', function() {
        $('.meja-box').removeClass('selected');
        $(this).addClass('selected');
        $('#trigger-modal-meja').removeClass('shake-warning');

        var idMeja = $(this).data('meja-id');
        var statusMeja = $(this).data('status');
        $('#hidden-table-id').val(idMeja);
        
        if (statusMeja === 'full') {
            $('#hidden-is-addon').val('1');
            $('#badge-order-type').fadeIn(150);
            $('#label-total-harga').text('Total Add-on');
            $('#text-meja-terpilih').html(`Meja M-0${idMeja} <span style="color:var(--warning); font-weight:bold;">(Add-on)</span>`);
        } else {
            $('#hidden-is-addon').val('0');
            $('#badge-order-type').fadeOut(150);
            $('#label-total-harga').text('Total Harga');
            $('#text-meja-terpilih').text(`Meja M-0${idMeja} (Ready)`);
        }

        $('#modal-meja-overlay').fadeOut(150);
        simpanKeLocalStorage();
        kalkulatorKembalian();
    });

    function updateCounterMeja() {
        var ready = $('.meja-grid-modal .meja-box.kosong').length;
        var full = $('.meja-grid-modal .meja-box.terisi').length;
        $('.badge-count.ready').text(ready);
        $('.badge-count.full').text(full);
    }
    updateCounterMeja();

    // MANAGEMENT SYSTEM LOCALSTORAGE REAL-TIME
    function simpanKeLocalStorage() {
        var keranjang = [];
        $('.cart-item').each(function() {
            var id = $(this).attr('id').replace('cart-', '');
            var nama = $(this).find('.cart-name').text();
            var harga = parseInt($(this).data('harga'));
            var qty = parseInt($(this).find('.input-qty').val());
            keranjang.push({ id: id, nama: nama, harga: harga, qty: qty });
        });

        var mejaTerpilih = $('.meja-box.selected').data('meja-id') || "";
        var statusMeja = $('.meja-box.selected').data('status') || "ready";

        var dataKasir = {
            meja: mejaTerpilih,
            statusMeja: statusMeja,
            namaPelanggan: $('#input-nama').val(),
            metodeBayar: $('input[name="payment_method"]:checked').val(),
            uangTunai: $('#input-cash').val(),
            itemBelanja: keranjang
        };
        localStorage.setItem('kasir_terpadu_data', JSON.stringify(dataKasir));
    }

    function muatDariLocalStorage() {
        var dataLokal = localStorage.getItem('kasir_terpadu_data');
        if (!dataLokal) return;
        var data = JSON.parse(dataLokal);
        
        if (data.meja) {
            $(`.meja-box[data-meja-id="${data.meja}"]`).addClass('selected');
            $('#hidden-table-id').val(data.meja);
            if(data.statusMeja === 'full') {
                $('#hidden-is-addon').val('1');
                $('#badge-order-type').show();
                $('#label-total-harga').text('Total Add-on');
                $('#text-meja-terpilih').html(`Meja M-0${data.meja} <span style="color:var(--warning); font-weight:bold;">(Add-on)</span>`);
            } else {
                $('#text-meja-terpilih').text(`Meja M-0${data.meja} (Ready)`);
            }
        }

        $('#input-nama').val(data.namaPelanggan).trigger('input');
        $('#input-cash').val(data.uangTunai);
        
        // Pastikan radio button dicentang sesuai localstorage jika metodenya terdaftar di Array
        if ($(`input[name="payment_method"][value="${data.metodeBayar}"]`).length > 0) {
            $(`input[name="payment_method"][value="${data.metodeBayar}"]`).prop('checked', true);
        }
        $(`input[name="payment_method"]:checked`).trigger('change');

        if (data.itemBelanja && data.itemBelanja.length > 0) {
            $('.empty-text').remove();
            data.itemBelanja.forEach(function(item) {
                var html = `
                    <div class="cart-item" id="cart-${item.id}" data-harga="${item.harga}">
                        <div class="cart-info"><div class="cart-name">${item.nama}</div><div class="cart-price">Rp ${item.harga.toLocaleString('id-ID')}</div></div>
                        <div class="cart-qty-controls">
                            <input type="hidden" name="product_id[]" value="${item.id}"><input type="hidden" name="price[]" value="${item.harga}"><input type="hidden" name="quantity[]" value="${item.qty}" class="input-qty">
                            <button type="button" class="btn-qty btn-min" data-id="${item.id}">-</button><span class="text-qty">${item.qty}</span><button type="button" class="btn-qty btn-plus" data-id="${item.id}">+</button>
                        </div>
                    </div>`;
                $('#box-item-belanja').append(html);
            });
        }
        hitungTotalNota();
    }

    $('#input-nama').on('input', function() { $('#hidden-customer-name').val($(this).val()); simpanKeLocalStorage(); });

    // EVENT DETECT UNTUK METODE BAYAR YANG DINAMIS
    $(document).on('change', 'input[name="payment_method"]', function() {
        var tipe = $(this).val();
        $('#hidden-payment-method').val(tipe);
        if(tipe !== 'tunai') { $('.section-kalkulator').hide(); } else { $('.section-kalkulator').show(); }
        kalkulatorKembalian();
        simpanKeLocalStorage();
    });

    // MENANGGAPI PILIHAN PRODUK
    $('.product-button').on('click', function() {
        var idMejaTerpilih = $('#hidden-table-id').val();
        if(!idMejaTerpilih) {
            $('#trigger-modal-meja').addClass('shake-warning');
            return;
        }

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
                    <div class="cart-info"><div class="cart-name">${nama}</div><div class="cart-price">Rp ${harga.toLocaleString('id-ID')}</div></div>
                    <div class="cart-qty-controls">
                        <input type="hidden" name="product_id[]" value="${id}"><input type="hidden" name="price[]" value="${harga}"><input type="hidden" name="quantity[]" value="1" class="input-qty">
                        <button type="button" class="btn-qty btn-min" data-id="${id}">-</button><span class="text-qty">1</span><button type="button" class="btn-qty btn-plus" data-id="${id}">+</button>
                    </div>
                </div>`;
            $('#box-item-belanja').append(html);
        }
        hitungTotalNota();
        simpanKeLocalStorage();
    });

    $(document).on('click', '.btn-plus', function() {
        var id = $(this).data('id'); var item = $('#cart-' + id); var input = item.find('.input-qty');
        var qty = parseInt(input.val()) + 1; input.val(qty); item.find('.text-qty').text(qty);
        hitungTotalNota(); simpanKeLocalStorage();
    });

    $(document).on('click', '.btn-min', function() {
        var id = $(this).data('id'); var item = $('#cart-' + id); var input = item.find('.input-qty');
        var qty = parseInt(input.val()) - 1;
        if(qty <= 0) {
            item.remove();
            if($('#box-item-belanja').children().length === 0) { $('#box-item-belanja').html('<p class="empty-text">Keranjang masih kosong</p>'); }
        } else { input.val(qty); item.find('.text-qty').text(qty); }
        hitungTotalNota(); simpanKeLocalStorage();
    });

    // HITUNG MATEMATIKA NOTA & KEMBALIAN
    function hitungTotalNota() {
        var total = 0;
        $('.cart-item').each(function() { total += (parseInt($(this).data('harga')) * parseInt($(this).find('.input-qty').val())); });
        $('#num-total-harga').val(total);
        $('#text-total-harga').text('Rp ' + total.toLocaleString('id-ID'));
        kalkulatorKembalian();
    }

    $('#input-cash').on('input', function() { kalkulatorKembalian(); simpanKeLocalStorage(); });

    function kalkulatorKembalian() {
        var total = parseInt($('#num-total-harga').val()) || 0;
        var metodeBayar = $('#hidden-payment-method').val();
        if (metodeBayar !== 'tunai') { $('#text-kembalian').removeClass('minus').text('Rp 0 (Non-Tunai)'); validasiTombol(); return; }
        var bayar = parseInt($('#input-cash').val()) || 0;
        var sisa = bayar - total;
        if(sisa < 0) { $('#text-kembalian').addClass('minus').text('Uang Kurang: -Rp ' + Math.abs(sisa).toLocaleString('id-ID')); }
        else { $('#text-kembalian').removeClass('minus').text('Rp ' + sisa.toLocaleString('id-ID')); }
        validasiTombol();
    }

    function validasiTombol() {
        var totalItem = $('.cart-item').length;
        var totalHarga = parseInt($('#num-total-harga').val()) || 0;
        var uangBayar = parseInt($('#input-cash').val()) || 0;
        var metodeBayar = $('#hidden-payment-method').val();
        if (totalItem === 0) { $('#btn-aksi-utama').prop('disabled', true); return; }
        if (metodeBayar === 'tunai' && uangBayar < totalHarga) { $('#btn-aksi-utama').prop('disabled', true); }
        else { $('#btn-aksi-utama').prop('disabled', false); }
    }
    
    // Tarik data localstorage pasca reload
    muatDariLocalStorage();

    // AJAX POST SUBMIT DATA
    $('#form-kasir').on('submit', function(e) {
        e.preventDefault();
        var dataSerialize = $(this).serialize();
        $.ajax({
            url: 'simpan_pesanan.php', type: 'POST', data: dataSerialize,
            success: function(res) {
                if(res.trim() === "SUKSES") {
                    alert('Transaksi Berhasil Diproses!');
                    localStorage.removeItem('kasir_terpadu_data');
                    $('#form-kasir')[0].reset();
                    $('.meja-box').removeClass('selected');
                    $('#hidden-table-id').val('');
                    $('#hidden-is-addon').val('0');
                    $('#badge-order-type').hide();
                    $('#label-total-harga').text('Total Harga');
                    $('#text-meja-terpilih').text('Klik Pilih Meja...');
                    $('#input-nama').val('').trigger('input');
                    
                    // Reset ke metode bayar default di dalam array
                    renderMetodePembayaran();
                    $(`input[name="payment_method"]:checked`).trigger('change');

                    $('#box-item-belanja').html('<p class="empty-text">Keranjang masih kosong</p>');
                    $('#input-cash').val('0');
                    hitungTotalNota();
                } else { alert('Gagal: ' + res); }
            },
            error: function() { alert('Koneksi internet bermasalah.'); }
        });
    });
});

$(document).ready(function() {
    let nilaiLamaIsi = null;
    let nilaiLamaKsg = null;
    let nilaiWaktu = null;
    const globaltoken = '<?php echo $_SESSION['globaltoken']; ?>';
    const targetSync = $('#target-sync').val();

    function fetchData() {
        $.ajax({
            url: 'ajaxserver.php?page=sync',
            type: 'POST',
            data: {
                ajax: 'ajax',
                globaltoken: globaltoken,
                target: targetSync
            },
            dataType: 'json',
            success: function(respon) {
                if (respon.status === 'success') {
                    console.log('syncing');
                }
            },
            error: function(xhr, status, error) {
                console.error('Koneksi gagal atau file tidak ditemukan:', error);
            },
            complete: function() {
                const halamanMejaMasihAktif = document.getElementById('box-denah-meja');
                if (halamanMejaMasihAktif) {
                    setTimeout(fetchData, 5000);
                } else {
                    console.log('Halaman Manajemen Meja sudah tidak aktif. Polling dihentikan.');
                }
            }
        });
    }

    fetchData();
});

</script>
</body>
</html>