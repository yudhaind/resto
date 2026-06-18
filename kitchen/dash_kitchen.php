<?php 
// 1. KEAMANAN & OTORISASI (PHP)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'kitchen') {
    header('Location: ../index.php');
    exit;
}
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display System (KDS) - Pro</title>
    <style>
        /* ==========================================================================
           2. MODERN DARK MODE KDS THEME (CSS)
           ========================================================================== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #0b0f19; /* Lebih gelap untuk kontras maksimal */
            color: #f8fafc;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Modern Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #141b2d;
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid #1f293d;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            color: #10b981; 
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        header p {
            color: #94a3b8;
            font-size: 13px;
            margin-top: 4px;
        }

        /* Controls Area */
        .header-controls {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .badge-online {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid rgba(16, 185, 129, 0.2);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-logout {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
            transition: background 0.2s;
        }
        .btn-logout:hover { background: #dc2626; }

        /* Container Layout */
        .main-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            flex: 1;
        }

        @media (min-width: 1024px) {
            .main-container {
                grid-template-columns: 300px 1fr; /* Memperlebar sidebar meja */
            }
        }

        .section-title {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* SIDEBAR: Status Meja */
        aside {
            background-color: #141b2d;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #1f293d;
            height: fit-content;
        }

        .grid-meja {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 12px;
        }

        .kartu-meja {
            padding: 14px 10px;
            border-radius: 10px;
            background-color: #1e293b;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 95px;
            border: 1px solid #334155;
            transition: all 0.2s ease;
        }

        /* Indikator Status Meja Lebih Tegas */
        .meja-wait { border: 2px solid #f59e0b; background-color: rgba(245, 158, 11, 0.03); } 
        .meja-cook { border: 2px solid #3b82f6; background-color: rgba(59, 130, 246, 0.03); } 

        .nomor { font-size: 24px; font-weight: 900; color: #fff; }

        .status-badge { font-size: 11px; padding: 4px 10px; border-radius: 6px; font-weight: 800; text-transform: uppercase; width: 100%; text-align: center; }
        .status-badge.wait { background: rgba(245,158,11,0.15); color:#f59e0b; }
        .status-badge.cook { background: rgba(59,130,246,0.15); color:#3b82f6; }
        .status-badge.ready { background: rgba(16,185,129,0.15); color:#10b981; }
        .status-badge.occupied { background: rgba(148,163,184,0.15); color:#94a3b8; }
        .status-badge.dirty { background: rgba(239,68,68,0.15); color:#ef4444; }

        /* KONTEN UTAMA: Antrean Pesanan */
        .grid-order {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        @media (min-width: 768px) { .grid-order { grid-template-columns: repeat(2, 1fr); } }  
        @media (min-width: 1440px) { .grid-order { grid-template-columns: repeat(3, 1fr); } } 

        /* Kartu Pesanan Berdesain Handal */
        .kartu-order {
            background-color: #141b2d;
            border-radius: 14px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid #1f293d;
        }

        .order-header {
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1e293b;
            border-bottom: 1px solid #1f293d;
        }

        .header-title .sub-id { font-size: 11px; font-weight: 700; color: #64748b; display: block; }
        .header-title h3 { font-size: 24px; font-weight: 900; color: #ffffff; }
        
        .waktu { font-family: 'Courier New', Courier, monospace; font-size: 14px; font-weight: 900; background-color: #0b0f19; color: #f59e0b; padding: 6px 10px; border-radius: 6px; border: 1px solid rgba(245,158,11,0.2); }

        .order-body { padding: 16px; flex: 1; }

        /* Item Menu (Teks Diperbesar agar Koki Bisa Lihat dari Jauh) */
        .item-menu {
            border-bottom: 1px solid #1f293d;
            padding: 14px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .item-menu:last-child { border: none; padding-bottom: 0; }

        .item-details { flex-grow: 1; }
        .item-flex { display: flex; align-items: center; }

        /* Badge Quantity Menonjol */
        .qty { 
            font-size: 20px; 
            font-weight: 800; 
            padding: 4px 10px;
            border-radius: 6px;
            margin-right: 12px;
            min-width: 42px;
            text-align: center;
        }
        .status-pending .qty { color: #f59e0b; background-color: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); } 
        .status-cooking .qty { color: #3b82f6; background-color: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); }

        .nama-menu { font-size: 18px; color: #f8fafc; font-weight: 700; } 
        
        /* Catatan Dapur Menolak Dilewatkan */
        .catatan-khusus { 
            color: #ffffff; font-size: 13px; font-weight: 700; margin-top: 6px; margin-left: 54px;
            background-color: #ef4444; padding: 4px 8px; border-radius: 6px; display: inline-block;
            box-shadow: 0 2px 4px rgba(239,68,68,0.2);
        }
        .catatan-normal { display: none; } 

        /* Tombol Aksi */
        .btn-item {
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }
        .btn-masak { background-color: #f59e0b; color: #0b0f19; }
        .btn-masak:hover { background-color: #d97706; }
        .btn-selesai { background-color: #3b82f6; color: white; }
        .btn-selesai:hover { background-color: #2563eb; }

        /* Bulk Action Header Card */
        .order-actions-zone {
            padding: 12px 16px;
            background-color: #1a2333;
            border-top: 1px solid #1f293d;
            display: flex;
            gap: 10px;
        }

        .btn-table { 
            flex: 1;
            padding: 10px; 
            border-radius: 8px; 
            font-weight: 800; 
            font-size: 13px; 
            cursor: pointer; 
            border: none; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
            gap: 6px; 
            transition: transform 0.1s; 
        }
        .btn-table:active { transform: scale(0.98); }
        .btn-table-masak { background: #f59e0b; color: #0b0f19; }
        .btn-table-selesai { background: #3b82f6; color: white; }

        /* ==========================================================================
           PERUBAHAN BARU: MODERENISASI INDIKATOR STATUS (DOT GLOWING)
           ========================================================================== */
        #sync-indicator { 
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }
        .sync-ok { 
            background-color: #10b981; 
            box-shadow: 0 0 12px #10b981 !important;
        }
        .sync-problem { 
            background-color: #ef4444; 
            box-shadow: 0 0 12px #ef4444 !important;
            animation: pulse 0.8s infinite alternate; 
        }
        .sync-loading {
            background-color: #f59e0b;
            box-shadow: 0 0 12px #f59e0b !important;
        }

        @keyframes pulse { from { opacity: 0.4; transform: scale(0.9); } to { opacity: 1; transform: scale(1.1); } }
    </style>
</head>
<body>

    <header>
        <div>
            <h1>👨‍🍳 DAPUR UTAMA</h1>
            <p>Monitor Antrean Masak Real-Time • Berbasis Item & Meja</p>
        </div>
        <div class="header-controls">
            <span class="badge-online">● <?= isset($_SESSION['user']['name']) ? htmlspecialchars($_SESSION['user']['name']) : 'Kitchen Monitor' ?></span>
            <span id="sync-indicator" class="sync-loading" title="Menghubungkan..."></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <input type="hidden" id="target-sync" value="kitchen" disabled>
    
    <div class="main-container">
        <aside>
            <h2 class="section-title">🪑 Status Meja</h2>
            <div class="grid-meja" id="grid-meja">
                <div style="color:#94a3b8; text-align:center; padding:8px; font-size:12px;">Memuat...</div>
            </div>
        </aside>

        <main>
            <h2 class="section-title">📋 Antrean Pesanan Masuk</h2>
            <div class="grid-order" id="grid-order">
                <div style="color:#94a3b8; text-align:center; padding:40px; font-size:16px;">Memuat antrean pesanan...</div>
            </div>
        </main>
    </div>

    <div id="ajax-log" style="margin:20px 0 0 0;color:#ef4444;background:#1e1b29;padding:12px;border-radius:8px;font-size:13px;max-height:120px;overflow:auto;display:none;border:1px solid #2d1f2e;"></div>

    <script src="assets/js/jquery-4.0.0.min.js"></script>
    <script>
    /* ==========================================================================
       3. OPERASIONAL REAL-TIME LOGIC
       ========================================================================== */
    $(document).ready(function() {
        const targetSync = $('#target-sync').val();
        const globaltoken = '<?php echo isset($_SESSION['globaltoken']) ? $_SESSION['globaltoken'] : ''; ?>';

        function formatMinutes(createdAt) {
            const created = new Date(createdAt);
            const diffMs = Date.now() - created.getTime();
            return Math.max(0, Math.floor(diffMs / 60000)) + ' Mnt';
        }

        function showAjaxLog(text) {
            try { text = typeof text === 'string' ? text : JSON.stringify(text, null, 2); } catch(e) {}
            $('#ajax-log').text(text).show();
        }
        
        // PERBAIKAN DI SINI: Tanda kurung penutup fungsi yang double sebelumnya sudah diperbaiki
        function clearAjaxLog() { 
            $('#ajax-log').hide().text(''); 
        }

        // Render Antrean Meja di Sidebar
        function renderKitchenTables(respon) {
            const container = $('#grid-meja');
            container.empty();
            const tables = respon.list_tables || [];

            if (tables.length === 0) {
                container.append('<div style="color:#94a3b8; text-align:center; padding:8px; font-size:12px;">Kosong</div>');
                return;
            }

            tables.forEach(function(table) {
                const waiting = parseInt(table.pending_count || 0, 10);
                const cooking = parseInt(table.cooking_count || 0, 10);

                let statusText = 'Siap';
                let badgeClass = 'ready';
                let cardClass = '';

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
                    cardClass = '';
                } else if (table.status === 'dirty') {
                    statusText = 'Kotor';
                    badgeClass = 'dirty';
                    cardClass = '';
                }

                container.append(`
                    <div class="kartu-meja ${cardClass}" data-table-id="${table.id}">
                        <span class="nomor">${table.table_number}</span>
                        <span class="status-badge ${badgeClass}">${statusText}</span>
                    </div>
                `);
            });
        }

        // Render Kartu Pesanan Utama
        function renderKitchenOrders(respon) {
            const container = $('#grid-order');
            container.empty();
            const orders = respon.orders || [];

            if (orders.length === 0) {
                container.append('<div style="grid-column:1 / -1; color:#94a3b8; text-align:center; padding:60px; font-size: 16px;">✨ Tidak ada antrean pesanan saat ini. Kerja bagus!</div>');
                return;
            }

            orders.forEach(function(order) {
                const timeLabel = formatMinutes(order.created_at);
                let itemsHtml = '';

                (order.items || []).forEach(function(item) {
                    const noteHtml = item.notes ? `<p class="catatan-khusus">⚠️ PENTING: ${item.notes}</p>` : `<p class="catatan-normal">-</p>`;
                    
                    let actionButton = '';
                    if (item.cooking_status === 'pending') {
                        actionButton = `<button class="btn-item btn-masak" data-item-id="${item.id}" data-current-status="pending">Masak 👨‍🍳</button>`;
                    } else if (item.cooking_status === 'cooking') {
                        actionButton = `<button class="btn-item btn-selesai" data-item-id="${item.id}" data-current-status="cooking">Selesai 🍽️</button>`;
                    }

                    itemsHtml += `
                        <div class="item-menu status-${item.cooking_status}">
                            <div class="item-details">
                                <div class="item-flex">
                                    <span class="qty">${item.quantity}x</span>
                                    <span class="nama-menu">${item.name}</span>
                                </div>
                                ${noteHtml}
                            </div>
                            <div>${actionButton}</div>
                        </div>
                    `;
                });

                let orderActions = '';
                if ((order.pending_items || 0) > 0) {
                    orderActions += `<button class="btn-table btn-table-masak" data-order-id="${order.order_id}" data-table-id="${order.table_id}">Masak Semua</button>`;
                }
                if ((order.cooking_items || 0) > 0) {
                    orderActions += `<button class="btn-table btn-table-selesai" data-order-id="${order.order_id}" data-table-id="${order.table_id}">Selesai Semua</button>`;
                }

                container.append(`
                    <div class="kartu-order" data-order-id="${order.order_id}" data-table-id="${order.table_id}">
                        <div class="order-header">
                            <div class="header-title">
                                <span class="sub-id">ORDER ID: #${order.order_id}</span>
                                <h3>MEJA ${order.table_number}</h3>
                            </div>
                            <span class="waktu">⏱️ ${timeLabel}</span>
                        </div>
                        <div class="order-body">
                            ${itemsHtml}
                        </div>
                        ${orderActions ? `<div class="order-actions-zone">${orderActions}</div>` : ''}
                    </div>
                `);
            });
        }

        // Background Polling Fetch Data
        function fetchData() {
            $.ajax({
                url: 'ajaxserver.php?page=sync',
                type: 'POST',
                data: { globaltoken: globaltoken, target: targetSync },
                dataType: 'json',
                success: function(respon) {
                    if (respon.status === 'success') {
                        renderKitchenTables(respon);
                        renderKitchenOrders(respon);
                        $('#sync-indicator').attr('class', 'sync-ok').attr('title', 'Sistem Aktif');
                        clearAjaxLog();
                    }
                },
                complete: function() {
                    if (document.getElementById('grid-order')) {
                        setTimeout(fetchData, 5000);
                    }
                },
                error: function(xhr, status, err) {
                    const msg = xhr && xhr.responseText ? xhr.responseText : (status || err || 'Terputus dari server');
                    console.error('FetchData error:', msg);
                    $('#sync-indicator').attr('class', 'sync-problem').attr('title', 'Koneksi Bermasalah');
                    showAjaxLog(msg);
                }
            });
        }

        // Action Per Item
        $(document).on('click', '.btn-item', function(e) {
            e.preventDefault();
            const itemId = $(this).data('item-id');
            const currentStatus = $(this).data('current-status');
            const nextStatus = currentStatus === 'pending' ? 'cooking' : (currentStatus === 'cooking' ? 'served' : currentStatus);

            $.ajax({
                url: 'ajaxserver.php?page=update_item_status',
                type: 'POST',
                data: { globaltoken: globaltoken, target: targetSync, item_id: itemId, status: nextStatus },
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') { fetchData(); } else { alert('Gagal: ' + resp.message); }
                },
                error: function() { alert('Error menghubungi server.'); }
            });
        });

        // Action Massal Meja/Nota
        $(document).on('click', '.btn-table-masak, .btn-table-selesai', function(e) {
            e.preventDefault();
            const orderId = $(this).data('order-id');
            const tableId = $(this).data('table-id');
            const isMasak = $(this).hasClass('btn-table-masak');
            const statusTo = isMasak ? 'cooking' : 'served';

            const postData = { globaltoken: globaltoken, target: targetSync, status: statusTo };
            if (orderId) postData.order_id = orderId; else postData.table_id = tableId;

            $.ajax({
                url: 'ajaxserver.php?page=update_table_status',
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') { fetchData(); } else { alert('Gagal: ' + resp.message); }
                },
                error: function() { alert('Error menghubungi server.'); }
            });
        });

        fetchData();
    });
    </script>
</body>
</html>