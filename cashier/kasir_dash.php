<?php
// Diasumsikan session_start() sudah dipanggil di file sebelum ini jika menggunakan $_SESSION
$tokenform  = bin2hex(random_bytes(16)); // Generate token acak untuk form
$_SESSION['token'] = $tokenform; // Simpan token di session untuk validasi nanti
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

$sqlnt="SELECT value FROM `global_settings` WHERE `id` = 1";
$hnt=fetchOne($sqlnt);

$sqlat="SELECT value FROM `global_settings` WHERE `id` = 2";
$hat=fetchOne($sqlat);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Modern - Flexible Payment & Compact</title>
    <link rel="stylesheet" href="assets/css/kasirstyle.css">
    <script src="assets/js/jquery-4.0.0.min.js"></script>
    <style>
        /* CSS Tambahan untuk Indikator Sync */
        .topbar-sync-status {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sync-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Status Online (Hijau) */
        .sync-dot.online {
            background-color: #10b981;
            box-shadow: 0 0 8px #10b981;
        }

        /* Status Offline (Merah) */
        .sync-dot.offline {
            background-color: #ef4444;
            box-shadow: 0 0 8px #ef4444;
        }

        #sync-text {
            font-weight: 500;
            transition: color 0.3s ease;
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="topbar-store-info">
        <span class="topbar-brand"><?= $hnt['value'];?></span>
        <span class="topbar-address"><?= $hat['value'];?></span>
    </div>
    
    <div class="topbar-sync-status">
        <span id="sync-dot" class="sync-dot offline"></span>
        <span id="sync-text" style="color: #ef4444;">Offline</span>
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
                <input type="hidden" name="tokenform" id="tokenform" value="<?= $tokenform ?>">
                <input type="hidden" name="table_id" id="hidden-table-id" value="">
                <input type="hidden" name="cashier_id" value="1">
                <input type="hidden" name="customer_name" id="hidden-customer-name" value="">
                <input type="hidden" name="payment_method_selected" id="hidden-payment-method" value="tunai">
                <input type="hidden" name="is_addon" id="hidden-is-addon" value="0">
                <input type="hidden" name="action" id="hidden-action" value="submit_order">
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
                        <input type="text" name="amount_paid_display" id="input-cash" class="input-money" value="0" placeholder="0">
                        <input type="hidden" name="amount_paid" id="hidden-amount-paid" value="0">
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
        $('#hidden-payment-method').val($('input[name="payment_method"]:checked').val());
    }
    renderMetodePembayaran();

    function generateCartRowKey(productId) {
        return productId + '-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6);
    }

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

    $('#trigger-modal-meja').on('click', function() {
        $('#modal-meja-overlay').css('display', 'flex').hide().fadeIn(150);
    });

    $('#close-modal-meja, .modal-overlay').on('click', function(e) {
        if (e.target === this) { $('#modal-meja-overlay').fadeOut(150); }
    });

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

    function simpanKeLocalStorage() {
        var keranjang = [];
        $('.cart-item').each(function() {
            var rowKey = $(this).attr('id').replace('cart-', '');
            var productId = $(this).find('input[name="product_id[]"]').val();
            var nama = $(this).find('.cart-name').text();
            var harga = parseInt($(this).data('harga'));
            var qty = parseInt($(this).find('.input-qty').val());
            var note = $(this).find('.input-note').val() || '';
            keranjang.push({ rowKey: rowKey, productId: productId, nama: nama, harga: harga, qty: qty, note: note });
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
            uangTunai: getRawAmountPaid(),
            itemBelanja: keranjang
        };
        localStorage.setItem('kasir_terpadu_data', JSON.stringify(dataKasir));
    }

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
        var uangTunai = (data.uangTunai && data.uangTunai !== "") ? data.uangTunai.toString() : '0';
        $('#hidden-amount-paid').val(uangTunai);
        $('#input-cash').val(uangTunai === '0' ? '0' : formatRupiah(uangTunai));
        
        if (data.metodeBayar && $(`input[name="payment_method"][value="${data.metodeBayar}"]`).length > 0) {
            $(`input[name="payment_method"][value="${data.metodeBayar}"]`).prop('checked', true);
        }
        $(`input[name="payment_method"]:checked`).trigger('change');

        if (data.itemBelanja && data.itemBelanja.length > 0) {
            $('.empty-text').remove();
                data.itemBelanja.forEach(function(item) {
                var noteValue = item.note ? item.note : '';
                var rowKey = item.rowKey || generateCartRowKey(item.productId);
                var html = `
                    <div class="cart-item" id="cart-${rowKey}" data-harga="${item.harga}">
                        <div class="cart-info"><div class="cart-name">${item.nama}</div><div class="cart-price">Rp ${item.harga.toLocaleString('id-ID')}</div></div>
                        <div class="cart-qty-controls">
                            <input type="hidden" name="product_id[]" value="${item.productId}"><input type="hidden" name="price[]" value="${item.harga}"><input type="hidden" name="quantity[]" value="${item.qty}" class="input-qty">
                            <button type="button" class="btn-qty btn-min" data-row-key="${rowKey}">-</button><span class="text-qty">${item.qty}</span><button type="button" class="btn-qty btn-plus" data-row-key="${rowKey}">+</button>
                        </div>
                        <div class="cart-note" style="margin-top:6px;">
                            <textarea name="notes[]" class="input-note" maxlength="200" placeholder="Catatan (opsional)" style="width:100%; min-height:30px; padding:6px; border:1px solid #e0e0e0; border-radius:6px; font-size:0.9rem; line-height:1.15;">${$('<div>').text(noteValue).html()}</textarea>
                            <div class="note-meta" style="display:flex; justify-content:flex-end; font-size:0.78rem; color:#666; margin-top:4px;"><span class="note-count">${noteValue.length}/200</span></div>
                        </div>
                    </div>`;
                $('#box-item-belanja').append(html);
            });
        }
        hitungTotalNota();
    }

    $('#input-nama').on('input', function() { 
        $('#hidden-customer-name').val($(this).val()); 
        simpanKeLocalStorage(); 
    });

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
        var itemEksis = $('.cart-item').filter(function() {
            var productId = $(this).find('input[name="product_id[]"]').val();
            var noteText = $(this).find('.input-note').val().trim();
            return productId == id && noteText === '';
        }).first();

        if(itemEksis.length > 0) {
            var inputQty = itemEksis.find('.input-qty');
            var qtyBaru = parseInt(inputQty.val()) + 1;
            inputQty.val(qtyBaru);
            itemEksis.find('.text-qty').text(qtyBaru);
        } else {
            var rowKey = generateCartRowKey(id);
            var html = `
                <div class="cart-item" id="cart-${rowKey}" data-harga="${harga}">
                    <div class="cart-info"><div class="cart-name">${nama}</div><div class="cart-price">Rp ${harga.toLocaleString('id-ID')}</div></div>
                    <div class="cart-qty-controls">
                        <input type="hidden" name="product_id[]" value="${id}"><input type="hidden" name="price[]" value="${harga}"><input type="hidden" name="quantity[]" value="1" class="input-qty">
                        <button type="button" class="btn-qty btn-min" data-row-key="${rowKey}">-</button><span class="text-qty">1</span><button type="button" class="btn-qty btn-plus" data-row-key="${rowKey}">+</button>
                    </div>
                    <div class="cart-note" style="margin-top:6px;">
                        <textarea name="notes[]" class="input-note" maxlength="200" placeholder="Catatan (opsional)" style="width:100%; min-height:30px; padding:6px; border:1px solid #e0e0e0; border-radius:6px; font-size:0.9rem; line-height:1.15;"></textarea>
                        <div class="note-meta" style="display:flex; justify-content:flex-end; font-size:0.78rem; color:#666; margin-top:4px;"><span class="note-count">0/200</span></div>
                    </div>
                </div>`;
            $('#box-item-belanja').append(html);
        }
        hitungTotalNota();
        simpanKeLocalStorage();
    });

    $(document).on('click', '.btn-plus', function() {
        var rowKey = $(this).data('row-key');
        var item = $('#cart-' + rowKey);
        var input = item.find('.input-qty');
        if (item.length === 0) return;
        var qty = parseInt(input.val()) + 1;
        input.val(qty);
        item.find('.text-qty').text(qty);
        hitungTotalNota(); simpanKeLocalStorage();
    });

    $(document).on('click', '.btn-min', function() {
        var rowKey = $(this).data('row-key');
        var item = $('#cart-' + rowKey);
        if (item.length === 0) return;
        var input = item.find('.input-qty');
        var qty = parseInt(input.val()) - 1;
        if(qty <= 0) {
            item.remove();
            if($('#box-item-belanja').children().length === 0) { $('#box-item-belanja').html('<p class="empty-text">Keranjang masih kosong</p>'); }
        } else { input.val(qty); item.find('.text-qty').text(qty); }
        hitungTotalNota(); simpanKeLocalStorage();
    });

    $(document).on('input', '.input-note', function() {
        var val = $(this).val() || '';
        var max = parseInt($(this).attr('maxlength') || 200, 10);
        $(this).closest('.cart-note').find('.note-count').text(val.length + '/' + max);
        simpanKeLocalStorage();
    });

    function hitungTotalNota() {
        var total = 0;
        $('.cart-item').each(function() { total += (parseInt($(this).data('harga')) * parseInt($(this).find('.input-qty').val())); });
        $('#num-total-harga').val(total);
        $('#text-total-harga').text('Rp ' + total.toLocaleString('id-ID'));
        kalkulatorKembalian();
    }

    function formatRupiah(angka) {
        return angka.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function unformatNumber(nilai) {
        return nilai.toString().replace(/[^0-9]/g, '');
    }

    function getRawAmountPaid() {
        return unformatNumber($('#hidden-amount-paid').val()) || '0';
    }

    function updateAmountPaid(rawValue) {
        rawValue = unformatNumber(rawValue);
        if (rawValue === '') {
            rawValue = '0';
        }
        $('#hidden-amount-paid').val(rawValue);
        $('#input-cash').val(rawValue === '0' ? '0' : formatRupiah(rawValue));
    }

    $('#input-cash').on('focus', function() {
        var raw = unformatNumber($(this).val());
        if (raw === '0') {
            $(this).val('');
        }
    });

    $('#input-cash').on('blur', function() {
        var raw = unformatNumber($(this).val());
        updateAmountPaid(raw);
    });

    $('#input-cash').on('input', function() {
        var raw = unformatNumber($(this).val());
        if (raw === '') {
            $('#hidden-amount-paid').val('0');
            $(this).val('');
        } else {
            $('#hidden-amount-paid').val(raw);
            $(this).val(formatRupiah(raw));
        }
        kalkulatorKembalian();
        simpanKeLocalStorage();
    });

    function kalkulatorKembalian() {
        var total = parseInt($('#num-total-harga').val()) || 0;
        var metodeBayar = $('#hidden-payment-method').val();
        if (metodeBayar !== 'tunai') { 
            $('#text-kembalian').removeClass('minus').text('Rp 0 (Non-Tunai)'); 
            validasiTombol(); 
            return; 
        }
        var bayar = parseInt(getRawAmountPaid()) || 0;
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
        var uangBayar = parseInt(getRawAmountPaid()) || 0;
        var metodeBayar = $('#hidden-payment-method').val();
        
        if (totalItem === 0) { $('#btn-aksi-utama').prop('disabled', true); return; }
        if (metodeBayar === 'tunai' && uangBayar < totalHarga) { 
            $('#btn-aksi-utama').prop('disabled', true); 
        } else { 
            $('#btn-aksi-utama').prop('disabled', false); 
        }
    }
    
    muatDariLocalStorage();

    $('#form-kasir').on('submit', function(e) {
        e.preventDefault();
        var dataSerialize = $(this).serialize();
        $.ajax({
            url: 'post.php', type: 'POST', data: dataSerialize,
            success: function(res) {
                if(res.trim() === "SUKSES") {
                    alert('Transaksi Berhasil Diproses!');
                    localStorage.removeItem('kasir_terpadu_data');
                    
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
                    $('#hidden-amount-paid').val('0');
                    $('#input-cash').val('0');
                    hitungTotalNota();
                } else { alert('Gagal: ' + res); }
            },
            error: function() { alert('Koneksi internet bermasalah.'); }
        });
    });
});

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

// REAL-TIME POLLING SINKRONISASI MEJA dengan Indikator Status
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

                    // Ubah Indikator ke Online (Hijau)
                    $('#sync-dot').removeClass('offline').addClass('online');
                    $('#sync-text').text('Online').css('color', '#34d399');
                } else {
                    // Kasus jika server membalas, tapi data tidak sesuai/error
                    $('#sync-dot').removeClass('online').addClass('offline');
                    $('#sync-text').text('Error Data').css('color', '#f87171');
                }
            },
            error: function(xhr, status, error) {
                console.error('Respon kotor / Error dari server:', xhr.responseText);
                
                // Ubah Indikator ke Offline (Merah)
                $('#sync-dot').removeClass('online').addClass('offline');
                $('#sync-text').text('Offline').css('color', '#f87171');
            },
            complete: function() {
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