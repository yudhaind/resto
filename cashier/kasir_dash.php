<?php
// Diasumsikan session_start() sudah dipanggil di file sebelum ini jika menggunakan $_SESSION
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Modern - Flexible Payment & Compact</title>
    <link rel="stylesheet" href="assets/css/kasirstyle.css">
</head>
<body>

<header class="topbar">
    <div class="topbar-store-info">
        <span class="topbar-brand">Nama Toko Anda</span>
        <span class="topbar-address">Jl. Raya Pusat No. 123, Kota Anda</span>
    </div>
    <div class="topbar-user-zone">
        <div class="topbar-user-info">
            <span>Kasir: <strong><?= isset($_SESSION['user']['name']) ? htmlspecialchars($_SESSION['user']['name']) : 'Kasir'; ?></strong></span>
            <span class="topbar-user-role">ID Kasir: #1</span>
        </div>
        <a href="logout.php" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Logout</a>
    </div>
</header>

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
                            <span class="badge-count ready" title="Ready">0</span>
                            <span class="badge-count full" title="Full">0</span>
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
            
            <div class="category-filter-wrapper">
                <button type="button" class="btn-category active" data-target="all">Semua</button>
                <?php
                foreach ($enum_array as $kategori) {
                    echo '<button type="button" class="btn-category" data-target="' . htmlspecialchars($kategori) . '">' . ucfirst(htmlspecialchars($kategori)) . '</button>';
                }
                ?>
            </div>
        
            <div class="menu-grid" style="max-height: 65vh; overflow-y: auto; padding-right: 5px; align-content: start; padding-top: 5px;">
                <?php
                $sql_menu = "SELECT * FROM `products`";
                $rslt_menu = fetchAll($sql_menu);
                foreach ($rslt_menu as $menu) {
                    $id_menu = $menu['id'];
                    $nama_menu = htmlspecialchars($menu['name'], ENT_QUOTES);
                    $harga_menu = $menu['price'];
                    $kategori_menu = htmlspecialchars($menu['category'], ENT_QUOTES);
                    $gambar_menu = (!empty($menu['image'])) ? 'assets/images/' . $menu['image'] : 'assets/images/'.$kategori_menu.'.png';
                    
                    echo '<div class="product-button" data-id="'.$id_menu.'" data-nama="'.$nama_menu.'" data-harga="'.$harga_menu.'" data-kategori="'.$kategori_menu.'">
                        <div class="p-image-container">
                            <img src="'.$gambar_menu.'" alt="'.$nama_menu.'" onerror="this.onerror=null; this.src=\'https://placehold.co/100x100?text=Food\';\">
                        </div>
                        <div class="p-details">
                            <div class="p-name">'.$nama_menu.'</div>
                            <div class="p-price">Rp '.number_format($harga_menu, 0, ',', '.').'</div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="checkout-section">
        <div class="card">
            <h2>3. Ringkasan Pesanan</h2>
            <form id="form-kasir">
                <div id="badge-order-type" class="badge-addon" style="display: none;">PESANAN TAMBAHAN (ADD-ON)</div>

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
                    <div class="option-container" id="payment-options-wrapper"></div>
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

<div class="modal-overlay" id="modal-meja-overlay" style="display: none;">
    <div class="modal-window">
        <div class="modal-header">
            <span class="modal-title">Pilih Nomor Meja</span>
            <button type="button" class="modal-close" id="close-modal-meja">&times;</button>
        </div>
        <div class="meja-grid-modal" id="kontainer-meja-live">
            <div class="meja-box kosong" data-meja-id="1" data-status="ready">
                <span class="m-number">Meja 1</span>
                <span class="m-status">Kapasitas : 2</span>
                <span class="m-status">Ready</span>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    var listMetodeBayar = [
        { id: "tunai",    label: "Tunai",   defaultChecked: true },
        { id: "qris",     label: "QRIS" },
        { id: "transfer", label: "Transfer" }
    ];

    function renderMetodePembayaran() {
        var wrapper = $('#payment-options-wrapper');
        wrapper.empty(); 

        listMetodeBayar.forEach(function(item) {
            var checkedAttr = item.defaultChecked ? 'checked' : '';
            var htmlButton = `
                <label class="radio-label">
                    <input type="radio" name="payment_method" value="${item.id}" ${checkedAttr}> ${item.label}
                </label>
            `;
            wrapper.append(htmlButton);
        });
        // Set default value ke input hidden
        $('#hidden-payment-method').val($('input[name="payment_method"]:checked').val());
    }
    renderMetodePembayaran();

    // FILTER KATEGORI PRODUK VIA JQUERY
    $('.btn-category').on('click', function() {
        $('.btn-category').removeClass('active');
        $(this).addClass('active');

        var kategoriTarget = $(this).data('target');

        if (kategoriTarget === 'all') {
            $('.product-button').hide().fadeIn(200);
        } else {
            $('.product-button').hide();
            $(`.product-button[data-kategori="${kategoriTarget}"]`).fadeIn(200);
        }
    });

    // MODAL EVENTS
    $('#trigger-modal-meja').on('click', function() {
        $('#modal-meja-overlay').css('display', 'flex').hide().fadeIn(150);
    });

    $('#close-modal-meja, .modal-overlay').on('click', function(e) {
        if (e.target === this) { $('#modal-meja-overlay').fadeOut(150); }
    });

    // PROSES PILIH MEJA
    $(document).on('click', '.meja-box', function() {
        $('.meja-box').removeClass('selected');
        $(this).addClass('selected');
        $('#trigger-modal-meja').removeClass('shake-warning');

        var idMeja = $(this).data('meja-id');
        var statusMeja = $(this).data('status');
        var namaMejaTerpilih = $(this).find('.m-number').text() || `Meja M-0${idMeja}`;

        $('#hidden-table-id').val(idMeja).attr('data-nama-meja-aktif', namaMejaTerpilih); 
        
        if (statusMeja === 'full') {
            $('#hidden-is-addon').val('1');
            $('#badge-order-type').fadeIn(150);
            $('#label-total-harga').text('Total Add-on');
            $('#text-meja-terpilih').html(`${namaMejaTerpilih} <span style="color:var(--warning); font-weight:bold;">(Add-on)</span>`);
        } else {
            $('#hidden-is-addon').val('0');
            $('#badge-order-type').fadeOut(150);
            $('#label-total-harga').text('Total Harga');
            $('#text-meja-terpilih').text(`${namaMejaTerpilih} (Ready)`);
        }

        $('#modal-meja-overlay').fadeOut(150);
        simpanKeLocalStorage();
        kalkulatorKembalian();
    });

    // SIMPAN DATA KE LOCAL STORAGE
    function simpanKeLocalStorage() {
        var keranjang = [];
        $('.cart-item').each(function() {
            var id = $(this).attr('id').replace('cart-', '');
            var nama = $(this).find('.cart-name').text();
            var harga = parseInt($(this).data('harga'));
            var qty = parseInt($(this).find('.input-qty').val());
            keranjang.push({ id: id, nama: nama, harga: harga, qty: qty });
        });

        var targetMejaActive = $('.meja-box.selected');
        var mejaTerpilih = targetMejaActive.data('meja-id') || $('#hidden-table-id').val() || "";
        var statusMeja = targetMejaActive.data('status') || ($('#hidden-is-addon').val() === '1' ? 'full' : 'ready');
        var namaMejaTerpilih = $('#hidden-table-id').attr('data-nama-meja-aktif') || "";

        var dataKasir = {
            meja: mejaTerpilih,
            namaMeja: namaMejaTerpilih,
            statusMeja: statusMeja,
            namaPelanggan: $('#input-nama').val(),
            metodeBayar: $('input[name="payment_method"]:checked').val(),
            uangTunai: $('#input-cash').val(),
            itemBelanja: keranjang
        };
        localStorage.setItem('kasir_terpadu_data', JSON.stringify(dataKasir));
    }

    // LOAD DATA DARI LOCAL STORAGE
    function muatDariLocalStorage() {
        var dataLokal = localStorage.getItem('kasir_terpadu_data');
        if (!dataLokal) return;
        var data = JSON.parse(dataLokal);
        
        if (data.meja && data.meja !== "") {
            $('#hidden-table-id').val(data.meja).attr('data-nama-meja-aktif', data.namaMeja);
            
            if(data.statusMeja === 'full') {
                $('#hidden-is-addon').val('1');
                $('#badge-order-type').show();
                $('#label-total-harga').text('Total Add-on');
                $('#text-meja-terpilih').html(`${data.namaMeja || "Meja M-0"+data.meja} <span style="color:var(--warning); font-weight:bold;">(Add-on)</span>`);
            } else {
                $('#hidden-is-addon').val('0');
                $('#badge-order-type').hide();
                $('#label-total-harga').text('Total Harga');
                $('#text-meja-terpilih').text(`${data.namaMeja || "Meja M-0"+data.meja} (Ready)`);
            }
        }

        $('#input-nama').val(data.namaPelanggan || "").trigger('input');
        $('#input-cash').val(data.uangTunai || "0");
        
        if (data.metodeBayar && $(`input[name="payment_method"][value="${data.metodeBayar}"]`).length > 0) {
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

    // LIVE SYNC NAMA PELANGGAN
    $('#input-nama').on('input', function() { 
        $('#hidden-customer-name').val($(this).val()); 
        simpanKeLocalStorage(); 
    });

    // DETEKSI METODE BAYAR CHANGED
    $(document).on('change', 'input[name="payment_method"]', function() {
        var tipe = $(this).val();
        $('#hidden-payment-method').val(tipe);
        if(tipe !== 'tunai') { 
            $('.section-kalkulator').hide(); 
        } else { 
            $('.section-kalkulator').show(); 
        }
        kalkulatorKembalian();
        simpanKeLocalStorage();
    });

    // TAMBAH KE KERANJANG
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

    // BUTTON PLUS MINUS QTY
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

    // KALKULATOR TOTAL NOTA & KEMBALIAN
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
        if (metodeBayar !== 'tunai') { 
            $('#text-kembalian').removeClass('minus').text('Rp 0 (Non-Tunai)'); 
            validasiTombol(); 
            return; 
        }
        var bayar = parseInt($('#input-cash').val()) || 0;
        var sisa = bayar - total;
        if(sisa < 0) { 
            $('#text-kembalian').addClass('minus').text('Uang Kurang: -Rp ' + Math.abs(sisa).toLocaleString('id-ID')); 
        } else { 
            $('#text-kembalian').removeClass('minus').text('Rp ' + sisa.toLocaleString('id-ID')); 
        }
        validasiTombol();
    }

    function validasiTombol() {
        var totalItem = $('.cart-item').length;
        var totalHarga = parseInt($('#num-total-harga').val()) || 0;
        var uangBayar = parseInt($('#input-cash').val()) || 0;
        var metodeBayar = $('#hidden-payment-method').val();
        
        if (totalItem === 0) { $('#btn-aksi-utama').prop('disabled', true); return; }
        if (metodeBayar === 'tunai' && uangBayar < totalHarga) { 
            $('#btn-aksi-utama').prop('disabled', true); 
        } else { 
            $('#btn-aksi-utama').prop('disabled', false); 
        }
    }
    
    muatDariLocalStorage();

    // SUBMIT FORM KASIR (AJAX)
    $('#form-kasir').on('submit', function(e) {
        e.preventDefault();
        var dataSerialize = $(this).serialize();
        $.ajax({
            url: 'simpan_pesanan.php', type: 'POST', data: dataSerialize,
            success: function(res) {
                if(res.trim() === "SUKSES") {
                    alert('Transaksi Berhasil Diproses!');
                    localStorage.removeItem('kasir_terpadu_data');
                    
                    // Reset Form DOM secara bersih
                    $('#form-kasir')[0].reset();
                    $('#input-nama').val('');
                    $('#hidden-customer-name').val('');
                    
                    $('.meja-box').removeClass('selected');
                    $('#hidden-table-id').val('').removeAttr('data-nama-meja-aktif');
                    $('#hidden-is-addon').val('0');
                    $('#badge-order-type').hide();
                    $('#label-total-harga').text('Total Harga');
                    $('#text-meja-terpilih').text('Klik Pilih Meja...');
                    
                    renderMetodePembayaran();
                    $('input[name="payment_method"][value="tunai"]').prop('checked', true).trigger('change');

                    $('#box-item-belanja').html('<p class="empty-text">Keranjang masih kosong</p>');
                    $('#input-cash').val('0');
                    hitungTotalNota();
                } else { alert('Gagal: ' + res); }
            },
            error: function() { alert('Koneksi internet bermasalah.'); }
        });
    });
});

// RE-RENDER LIVE UPDATE DARI REALTIME POLLING
function renderMejaLiveUpdate(responServer) {
    var dataMeja = responServer.list_meja;
    if (!dataMeja) return;

    var container = $('#kontainer-meja-live');
    var mejaTerpilihSaatIni = $('#hidden-table-id').val(); 
    container.empty();

    dataMeja.forEach(function(meja) {
        var isAvailable = (meja.status === 'available');
        var statusClass = isAvailable ? 'kosong' : 'terisi';
        var statusText = isAvailable ? 'Ready' : 'Full';
        var dataStatusValue = isAvailable ? 'ready' : 'full';

        var selectedClass = (meja.id == mejaTerpilihSaatIni) ? 'selected' : '';

        var html = `
            <div class="meja-box ${statusClass} ${selectedClass}" data-meja-id="${meja.id}" data-status="${dataStatusValue}">
                <span class="m-number">${meja.nomeja}</span>
                <span class="m-status">Kapasitas : ${meja.kapasitas}</span>
                <span class="m-status">${statusText}</span>
            </div>`;
        container.append(html);
    });
}

// REAL-TIME POLLING SINKRONISASI MEJA
$(document).ready(function() {
    const globaltoken = '<?php echo isset($_SESSION['globaltoken']) ? $_SESSION['globaltoken'] : ""; ?>';
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
                if (respon.status === 'success' || respon.list_meja) {
                    console.log('Syncing data meja dengan dapur berhasil...');
                    var nilaiIsi = (respon.nilai_isi) ? respon.nilai_isi : 0;
                    var nilaiKsg = (respon.nilai_ksg) ? respon.nilai_ksg : 0;
        
                    $('.badge-count.ready').text(nilaiKsg);
                    $('.badge-count.full').text(nilaiIsi);
                    
                    renderMejaLiveUpdate(respon);
                }
            },
            error: function(xhr, status, error) {
                console.error('Respon kotor / Error dari server:', xhr.responseText);
            },
            complete: function() {
                // Pastikan element masih eksis di DOM sebelum loop interval berjalan lagi
                if (document.getElementById('section-pelanggan')) {
                    setTimeout(fetchData, 5000); 
                }
            }
        });
    }

    fetchData();
});
</script>
</body>
</html>