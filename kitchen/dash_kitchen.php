<?php 
// 1. KEAMANAN & OTORISASI (PHP)
// Memeriksa status sesi. Jika belum ada sesi yang berjalan, maka aktifkan sesi.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Proteksi halaman: Jika user belum login ATAU role (peran) user bukan 'kitchen' (dapur),
// maka user akan ditendang (dialihkan) secara paksa ke halaman login utama.
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'kitchen') {
    header('Location: ../index.php');
    exit; // Menghentikan eksekusi skrip PHP selanjutnya
}
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display System (KDS)</title>
    <style>
        /* ==========================================================================
           2. GAYA TAMPILAN (CSS) - Tema Kegelapan (Dark Mode) untuk Monitor Dapur
           ========================================================================== */
        
        /* Reset default margin, padding, dan atur font agar konsisten */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        }

        /* Mengatur warna latar belakang gelap gulita agar koki tidak silau */
        body {
            background-color: #0f172a;
            color: #f8fafc;
            padding: 16px;
            min-height: 100vh;
        }

        /* Tata letak bagian atas (Header) */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        header h1 {
            color: #10b981; /* Warna hijau khas KDS aktif */
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        header p {
            color: #94a3b8;
            font-size: 14px;
            margin-top: 2px;
        }

        /* Badge indikator bahwa sistem KDS sedang menyala */
        .badge-online {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 9999px;
            text-transform: uppercase;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        /* Grid Layout Utama: Default 1 kolom untuk layar handphone */
        .main-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        /* Jika layar berukuran besar (Tablet/Monitor PC), bagi jadi 2 kolom: Sidebar & Konten Utama */
        @media (min-width: 1024px) {
            .main-container {
                grid-template-columns: 260px 1fr;
            }
        }

        h2 {
            font-size: 16px;
            color: #94a3b8;
            margin-bottom: 14px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* SIDEBAR: Area Daftar Meja */
        aside {
            background-color: #1e293b;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #334155;
            height: fit-content;
        }

        /* Grid mini untuk menyusun kotak-kotak nomor meja */
        .grid-meja {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }

        /* Desain kartu status meja */
        .kartu-meja {
            padding: 12px 10px;
            border-radius: 10px;
            background-color: #334155;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 88px;
        }

        /* Border khusus penanda meja yang butuh perhatian dapur */
        .meja-wait { border: 1px solid rgba(245, 158, 11, 0.4); } /* Ada pesanan belum dimasak */
        .meja-cook { border: 1px solid rgba(59, 130, 246, 0.4); } /* Sedang dimasak */

        .nomor { font-size: 20px; font-weight: 900; color:#fff; display:block; }

        /* Pewarnaan Badge Status Meja */
        .status-badge { font-size:12px; padding:6px 8px; border-radius:999px; font-weight:800; }
        .status-badge.wait { background: rgba(245,158,11,0.12); color:#f59e0b; border:1px solid rgba(245,158,11,0.25); } /* Menunggu */
        .status-badge.cook { background: rgba(59,130,246,0.12); color:#3b82f6; border:1px solid rgba(59,130,246,0.22); } /* Dimasak */
        .status-badge.ready { background: rgba(16,185,129,0.16); color:#059669; border:1px solid rgba(16,185,129,0.28); } /* Kosong / Siap */
        .status-badge.occupied { background: rgba(239,68,68,0.12); color:#ef4444; border:1px solid rgba(239,68,68,0.25); } /* Pelanggan sedang makan */
        .status-badge.dirty { background: rgba(239,68,68,0.06); color:#ef4444; border:1px solid rgba(239,68,68,0.12); } /* Meja kotor */

        /* KONTEN UTAMA: Area Antrean Kartu Pesanan */
        .grid-order {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        /* Responsivitas jumlah kartu pesanan berdasarkan lebar layar monitor */
        @media (min-width: 768px) { .grid-order { grid-template-columns: repeat(2, 1fr); } }  /* Layar sedang: 2 kolom */
        @media (min-width: 1400px) { .grid-order { grid-template-columns: repeat(3, 1fr); } } /* Layar TV besar: 3 kolom */

        /* Desain Kotak Kartu Pesanan (Order Card) */
        .kartu-order {
            background-color: #1e293b;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 2px solid #334155;
        }

        /* Bagian Atas Kartu Pesanan (Berisi ID Pesanan & No Meja) */
        .order-header {
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #334155;
            color: #ffffff;
        }

        .header-title span { text-transform: uppercase; font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #94a3b8; }
        .header-title h3 { font-size: 22px; font-weight: 900; line-height: 1.2; }
        
        /* Box penanda waktu tunggu pesanan */
        .waktu { font-family: monospace; font-size: 13px; font-weight: 800; background-color: rgba(0, 0, 0, 0.3); color: #fff; padding: 4px 8px; border-radius: 6px;}

        /* Bagian Isi List Menu di dalam Kartu Pesanan */
        .order-body { padding: 16px; }

        /* Pembatas antar menu yang dipesan */
        .item-menu {
            border-bottom: 1px solid #334155;
            padding-bottom: 12px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .item-menu:last-child { border: none; padding-bottom: 0; margin-bottom: 0; }

        .item-details { flex-grow: 1; }
        .item-flex { display: flex; align-items: center; }

        /* Badge Jumlah (Quantity) Makanan */
        .qty { 
            font-size: 16px; 
            font-weight: 800; 
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 10px;
            min-width: 35px;
            text-align: center;
        }
        
        /* Pewarnaan Angka Qty Berdasarkan Status Proses Item Makanan */
        .status-pending .qty { color: #f59e0b; background-color: rgba(245, 158, 11, 0.15); } /* Belum dimasak (Orange) */
        .status-cooking .qty { color: #3b82f6; background-color: rgba(59, 130, 246, 0.15); } /* Sedang dimasak (Biru) */

        .nama-menu { font-size: 16px; color: #f1f5f9; font-weight: 600; }
        
        /* Desain teks catatan khusus koki (Misal: "Pedas sekali!", "Tanpa bawang!") */
        .catatan-khusus { 
            color: #ef4444; font-size: 12px; font-weight: 700; margin-top: 4px; margin-left: 45px;
            background-color: rgba(239, 68, 68, 0.1); padding: 2px 6px; border-radius: 4px; display: inline-block;
        }
        .catatan-normal { color: #94a3b8; font-size: 12px; margin-top: 4px; margin-left: 45px; }

        /* Gaya tombol aksi interaktif dapur */
        .btn-item {
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        /* Desain tombol aksi massal (Bulk Action) */
        .btn-table { padding: 8px 12px; border-radius: 8px; font-weight: 800; font-size: 13px; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px; transition: all .15s ease; }
        .btn-table:focus { outline: 2px solid rgba(255, 255, 255, 0.06); }
        
        /* Tombol "Masak Semua" */
        .btn-table-masak { background: linear-gradient(180deg, #f59e0b, #d97706); color: #0f172a; box-shadow: 0 6px 14px rgba(217, 119, 6, 0.12); }
        .btn-table-masak:hover { transform: translateY(-2px); filter: brightness(.98); }
        
        /* Tombol "Selesai Semua" */
        .btn-table-selesai { background: linear-gradient(180deg, #3b82f6, #2563eb); color: #ffffff; box-shadow: 0 6px 14px rgba(37, 99, 235, 0.12); }
        .btn-table-selesai:hover { transform: translateY(-2px); filter: brightness(.98); }

        .btn-selesai { background-color: #3b82f6; color: #ffffff; }
        .btn-selesai:hover { background-color: #2563eb; }
    </style>
</head>
<body>

    <header>
        <div>
            <h1>DAPUR UTAMA</h1>
            <p>Memantau pesanan masuk secara Real-Time (Sistem Per Item)</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <span class="badge-online">● KDS Aktif</span>
            <span id="sync-indicator" style="font-size:13px;color:#94a3b8;">Memuat...</span>
        </div>
    </header>

    <input type="hidden" id="target-sync" value="kitchen" disabled>
    
    <div class="main-container">
        <aside>
            <h2>🪑 Status Meja</h2>
            <div class="grid-meja" id="grid-meja">
                <div style="color:#94a3b8; text-align:center; padding:8px; font-size:12px;">Memuat...</div>
            </div>
        </aside>

        <main>
            <h2>📋 Antrean Pesanan</h2>
            <div class="grid-order" id="grid-order">
                <div style="color:#94a3b8; text-align:center; padding:16px; font-size:14px;">Memuat antrean pesanan...</div>
            </div>
        </main>
    </div>

    <div id="ajax-log" style="margin:12px 16px 0 16px;color:#ffdddd;background:#2b1220;padding:8px;border-radius:6px;font-size:13px;max-height:160px;overflow:auto;display:none;"></div>

    <script src="assets/js/jquery-4.0.0.min.js"></script>
    <script>
    /* ==========================================================================
       4. LOGIKA OPERASIONAL REAL-TIME (JAVASCRIPT & JQUERY)
       ========================================================================== */
    $(document).ready(function() {
        // Ambil token sesi PHP dan target halaman untuk parameter validasi AJAX ke database
        const targetSync = $('#target-sync').val();
        const globaltoken = '<?php echo isset($_SESSION['globaltoken']) ? $_SESSION['globaltoken'] : ''; ?>';

        // Fungsi pembantu: Menghitung selisih waktu dari pesanan dibuat hingga saat ini dalam satuan Menit
        function formatMinutes(createdAt) {
            const created = new Date(createdAt);
            const diffMs = Date.now() - created.getTime();
            return Math.max(0, Math.floor(diffMs / 60000)) + ' Mnt';
        }

        // Fungsi pembantu: Menampilkan log pesan error sistem di bagian bawah layar jika koneksi terputus
        function showAjaxLog(text) {
            try { text = typeof text === 'string' ? text : JSON.stringify(text, null, 2); } catch(e) {}
            $('#ajax-log').text(text).show();
        }
        function clearAjaxLog(){ $('#ajax-log').hide().text(''); }

        // FUNGSI 1: Mengubah data JSON meja dari server menjadi HTML dan menampilkannya di sidebar
        function renderKitchenTables(respon) {
            const container = $('#grid-meja');
            container.empty(); // Kosongkan animasi loading awal
            const tables = respon.list_tables || [];

            if (tables.length === 0) {
                container.append('<div style="color:#94a3b8; text-align:center; padding:8px; font-size:12px;">Kosong</div>');
                return;
            }

            // Looping/perulangan data setiap meja dari database
            tables.forEach(function(table) {
                const waiting = parseInt(table.pending_count || 0, 10);
                const cooking = parseInt(table.cooking_count || 0, 10);

                // Logika default status tampilan meja
                let statusText = 'Siap';
                let badgeClass = 'ready';
                let cardClass = '';

                // Menentukan indikator warna meja berdasarkan jumlah pesanan aktif dapur
                if (waiting > 0) {
                    statusText = waiting + ' Tunggu';
                    badgeClass = 'wait';
                    cardClass = 'meja-wait';
                } else if (cooking > 0) {
                    statusText = 'Dimasak';
                    badgeClass = 'cook';
                    cardClass = 'meja-cook';
                } else if (table.status === 'occupied') {
                    statusText = 'Terisi';
                    badgeClass = 'occupied';
                } else if (table.status === 'dirty') {
                    statusText = 'Kotor';
                    badgeClass = 'dirty';
                }

                // Munculkan tombol aksi massal per meja jika di meja tersebut ada menu aktif
                let actionsHtml = '';
                if (waiting > 0) {
                    actionsHtml += `<button class="btn-table btn-table-masak" data-table-id="${table.id}">Masak Semua 👨‍🍳</button>`;
                }
                if (cooking > 0) {
                    actionsHtml += `<button class="btn-table btn-table-selesai" data-table-id="${table.id}">Selesai Semua 🍽️</button>`;
                }

                // Tempelkan elemen html meja baru ke dalam container di layar
                container.append(`
                    <div class="kartu-meja ${cardClass}" data-table-id="${table.id}">
                        <span class="nomor">${table.table_number}</span>
                        <span class="status-badge ${badgeClass}">${statusText}</span>
                        <div class="table-actions">${actionsHtml}</div>
                    </div>
                `);
            });
        }

        // FUNGSI 2: Mengubah data JSON antrean pesanan dari server menjadi Kartu Antrean Makanan (Order Cards)
        function renderKitchenOrders(respon) {
            const container = $('#grid-order');
            container.empty(); // Kosongkan text loading awal
            const orders = respon.orders || [];

            if (orders.length === 0) {
                container.append('<div style="grid-column:1 / -1; color:#94a3b8; text-align:center; padding:40px; font-size: 16px;">Tidak ada antrean pesanan saat ini.</div>');
                return;
            }

            // Looping/perulangan data per ID pesanan (per Nota transaksi)
            orders.forEach(function(order) {
                const timeLabel = formatMinutes(order.created_at);
                let itemsHtml = '';

                // Looping list menu/makanan di dalam satu ID pesanan tersebut
                (order.items || []).forEach(function(item) {
                    const noteHtml = item.notes ? `<p class="catatan-khusus">⚠️ ${item.notes}</p>` : `<p class="catatan-normal">-</p>`;
                    
                    // Logika memunculkan tombol interaktif dapur per item makanan sesuai statusnya saat ini
                    let actionButton = '';
                    if (item.cooking_status === 'pending') {
                        actionButton = `<button class="btn-item btn-masak" data-item-id="${item.id}" data-current-status="pending">Masak 👨‍🍳</button>`;
                    } else if (item.cooking_status === 'cooking') {
                        actionButton = `<button class="btn-item btn-selesai" data-item-id="${item.id}" data-current-status="cooking">Selesai 🍽️</button>`;
                    }

                    // Gabungkan susunan menu ke dalam string HTML
                    itemsHtml += `
                        <div class="item-menu status-${item.cooking_status}">
                            <div class="item-details">
                                <div class="item-flex">
                                    <span class="qty">${item.quantity}x</span>
                                    <span class="nama-menu">${item.name}</span>
                                </div>
                                ${noteHtml}
                            </div>
                            <div>
                                ${actionButton}
                            </div>
                        </div>
                    `;
                });

                // Mempersiapkan tombol aksi massal (bulk action) untuk memproses seluruh isi kartu pesanan sekaligus
                let orderActions = '';
                if ((order.pending_items || 0) > 0) {
                    orderActions += `<button class="btn-table btn-table-masak" data-order-id="${order.order_id}" data-table-id="${order.table_id}">Masak Semua 👨‍🍳</button>`;
                }
                if ((order.cooking_items || 0) > 0) {
                    orderActions += `<button class="btn-table btn-table-selesai" data-order-id="${order.order_id}" data-table-id="${order.table_id}">Selesai Semua 🍽️</button>`;
                }

                // Gabungkan seluruh data di atas dan tempelkan menjadi satu struktur Kartu Pesanan utuh di layar monitor
                container.append(`
                    <div class="kartu-order" data-order-id="${order.order_id}" data-table-id="${order.table_id}">
                        <div class="order-header">
                            <div class="header-title">
                                <span class="sub-id">ID: #${order.order_id}</span>
                                <h3>${order.table_number}</h3>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span class="waktu">${timeLabel}</span>
                                ${orderActions}
                            </div>
                        </div>
                        <div class="order-body">
                            ${itemsHtml}
                        </div>
                    </div>
                `);
            });
        }

        // FUNGSI CORE SINKRONISASI: Mengambil data terbaru dari server (Database) via AJAX secara background proses
        function fetchData() {
            $.ajax({
                url: 'ajaxserver.php?page=sync', // Mengarah ke backend pemroses data sinkronisasi KDS
                type: 'POST',
                data: { globaltoken: globaltoken, target: targetSync },
                dataType: 'json',
                success: function(respon) {
                    if (respon.status === 'success') {
                        // Jika server merespon sukses, panggil fungsi render data ke layar monitor dapur
                        renderKitchenTables(respon);
                        renderKitchenOrders(respon);
                        $('#sync-indicator').text('OK').css({color:'#059669','font-weight':'800'});
                        clearAjaxLog();
                    }
                },
                complete: function() {
                    // LOGIKA REAL-TIME POLLING: Jalankan ulang fungsi fetchData ini secara terus-menerus setiap 5 detik (5000 milidetik)
                    if (document.getElementById('grid-order')) {
                        setTimeout(fetchData, 5000);
                    }
                },
                error: function(xhr, status, err) {
                    // Menangkap log error jika koneksi server/hosting drop atau mati terputus
                    const msg = xhr && xhr.responseText ? xhr.responseText : (status || err || 'Unknown error');
                    console.error('FetchData error:', msg);
                    $('#sync-indicator').text('Problem').css({color:'#ef4444', 'font-weight':'800'});
                    showAjaxLog(msg); // Tampilkan detail log eror di bagian bawah layar monitor
                }
            });
        }

        // EVENT LISTENER 1: Event ketika koki mengklik tombol aksi per item satuan menu makanan
        $(document).on('click', '.btn-item', function(e) {
            e.preventDefault();
            const itemId = $(this).data('item-id');
            const currentStatus = $(this).data('current-status');
            
            // Logika rantai perubahan status: jika pending -> jadikan cooking. jika cooking -> jadikan served (selesai)
            const nextStatus = currentStatus === 'pending' ? 'cooking' : (currentStatus === 'cooking' ? 'served' : currentStatus);

            // Kirim perubahan status item makanan ke database secara instan
            $.ajax({
                url: 'ajaxserver.php?page=update_item_status', // endpoint backend khusus update per menu item
                type: 'POST',
                data: {
                    globaltoken: globaltoken,
                    target: targetSync,
                    item_id: itemId,
                    status: nextStatus
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        fetchData(); // Muat ulang layar KDS agar perubahan langsung terlihat di monitor koki & pelayan
                    } else {
                        alert('Gagal update item: ' + resp.message);
                    }
                },
                error: function(xhr, status, err) {
                    alert('Error menghubungi server.');
                }
            });
        });

        // EVENT LISTENER 2: Event ketika koki mengklik tombol aksi massal (Masak Semua / Selesai Semua) per Meja atau per Nota
        $(document).on('click', '.btn-table-masak, .btn-table-selesai', function(e) {
            e.preventDefault();
            const orderId = $(this).data('order-id');
            const tableId = $(this).data('table-id');
            const isMasak = $(this).hasClass('btn-table-masak');
            const statusTo = isMasak ? 'cooking' : 'served';

            // Menyiapkan paket data untuk dikirim ke backend
            const postData = { globaltoken: globaltoken, target: targetSync, status: statusTo };
            
            // Jika diklik dari kartu pesanan gunakan order_id, jika klik dari sidebar gunakan table_id
            if (orderId) postData.order_id = orderId; else postData.table_id = tableId;

            // Kirim perubahan status massal ke database via AJAX
            $.ajax({
                url: 'ajaxserver.php?page=update_table_status', // endpoint backend khusus update massal kelompok meja/nota
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        fetchData(); // Tarik data terbaru untuk merefresh layar monitor dapur seketika itu juga
                    } else {
                        alert('Gagal update: ' + resp.message);
                    }
                },
                error: function(xhr, status, err) {
                    alert('Error menghubungi server.');
                }
            });
        });

        // JALANKAN PERTAMA KALI: Pemicu awal saat monitor dapur pertama kali dibuka di web browser
        fetchData();
    });
    </script>
</body>
</html>