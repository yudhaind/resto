<?php 
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
    <title>Kitchen Display System (KDS)</title>
    <style>
        /* BASE STYLE */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #111827;
            color: #f3f4f6;
            padding: 16px;
            min-height: 100vh;
        }

        /* HEADER */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #374151;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        header h1 {
            color: #34d399;
            font-size: 24px;
            font-weight: 700;
        }

        header p {
            color: #9ca3af;
            font-size: 13px;
        }

        .badge-online {
            background-color: rgba(52, 211, 153, 0.1);
            color: #34d399;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 9999px;
            text-transform: uppercase;
            border: 1px solid rgba(52, 211, 153, 0.2);
        }

        /* MAIN LAYOUT GRID */
        .main-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 1024px) {
            .main-container {
                grid-template-columns: 240px 1fr;
            }
        }

        h2 {
            font-size: 16px;
            color: #d1d5db;
            margin-bottom: 12px;
            font-weight: 600;
        }

        /* SIDEBAR: DAFTAR MEJA */
        aside {
            background-color: #1f2937;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #374151;
            height: fit-content;
        }

        .grid-meja {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 8px;
        }

        .kartu-meja {
            padding: 8px;
            border-radius: 6px;
            text-align: center;
        }

        .meja-wait {
            background-color: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .meja-wait .nomor { color: #fbbf24; font-size: 18px; font-weight: 700; display: block; }
        .meja-wait .status { color: #fef3c7; font-size: 11px; }

        .meja-cook {
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .meja-cook .nomor { color: #60a5fa; font-size: 18px; font-weight: 700; display: block; }
        .meja-cook .status { color: #dbeafe; font-size: 11px; }

        /* MAIN CONTENT: DAFTAR ORDER */
        .grid-order {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 768px) {
            .grid-order { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1280px) {
            .grid-order { grid-template-columns: repeat(3, 1fr); }
        }

        /* KARTU ANTREAN ORDER */
        .kartu-order {
            background-color: #1f2937;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .order-baru { border: 1px solid #f59e0b; }
        .order-masak { border: 1px solid #3b82f6; }

        /* Header Kartu */
        .order-header {
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-baru .order-header { background-color: #f59e0b; color: #111827; }
        .order-masak .order-header { background-color: #3b82f6; color: #ffffff; }

        .header-title span { text-transform: uppercase; font-size: 9px; font-weight: 800; letter-spacing: 1px; }
        .header-title h3 { font-size: 18px; font-weight: 900; }
        .waktu { font-family: monospace; font-size: 12px; font-weight: bold; background-color: rgba(0,0,0,0.15); padding: 2px 6px; border-radius: 4px;}

        /* Isi Menu */
        .order-body {
            padding: 12px;
        }

        .item-menu {
            border-bottom: 1px solid #374151;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .item-menu:last-child {
            border: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .qty { font-size: 16px; font-weight: 700; color: #ffffff; margin-right: 6px; }
        .nama-menu { font-size: 14px; color: #f3f4f6; }
        .catatan-normal { color: #9ca3af; font-size: 11px; margin-top: 1px;}

        /* Tombol Aksi Utama */
        .order-footer {
            padding: 12px;
            border-top: 1px solid #374151;
        }

        .btn {
            width: 100%;
            border: none;
            padding: 10px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
        }

        .order-baru .btn { background-color: #f59e0b; color: #111827; }
        .order-baru .btn:hover { background-color: #d97706; }

        .order-masak .btn { background-color: #3b82f6; color: #ffffff; }
        .order-masak .btn:hover { background-color: #2563eb; }
    </style>
</head>
<body>

    <header>
        <div>
            <h1>DAPUR UTAMA</h1>
            <p>Memantau pesanan masuk dari Kasir</p>
        </div>
        <div>
            <span class="badge-online">Sistem Online</span>
        </div>
    </header>

    <input type="hidden" id="target-sync" value="kitchen" disabled>
    <div class="main-container">
        
        <aside>
            <h2>🪑 Status Meja</h2>
            <div class="grid-meja" id="grid-meja">
                <div style="color:#9ca3af; text-align:center; padding:8px; font-size:12px;">Memuat...</div>
            </div>
        </aside>

        <main>
            <h2>📋 Antrean Pesanan</h2>
            <div class="grid-order" id="grid-order">
                <div style="color:#9ca3af; text-align:center; padding:16px; font-size:14px;">Memuat antrean pesanan...</div>
            </div>
        </main>
    </div>

    <script src="assets/js/jquery-4.0.0.min.js"></script>
    <script>
    $(document).ready(function() {
        const targetSync = $('#target-sync').val();
        const globaltoken = '<?php echo isset($_SESSION['globaltoken']) ? $_SESSION['globaltoken'] : ''; ?>';

        function formatMinutes(createdAt) {
            const created = new Date(createdAt);
            const diffMs = Date.now() - created.getTime();
            const minutes = Math.max(0, Math.floor(diffMs / 60000));
            return minutes + ' Mnt';
        }

        function renderKitchenTables(respon) {
            const container = $('#grid-meja');
            container.empty();
            const tables = respon.list_tables || [];

            if (tables.length === 0) {
                container.append('<div style="color:#9ca3af; text-align:center; padding:8px; font-size:12px;">Kosong</div>');
                return;
            }

            tables.forEach(function(table) {
                const waiting = parseInt(table.pending_count || 0, 10);
                const cooking = parseInt(table.cooking_count || 0, 10);
                let statusText = 'Ready';
                let className = 'meja-wait';

                if (waiting > 0) {
                    statusText = waiting + ' Tunggu';
                    className = 'meja-wait';
                } else if (cooking > 0) {
                    statusText = 'Dimasak';
                    className = 'meja-cook';
                } else if (table.status === 'occupied') {
                    statusText = 'Terisi';
                } else if (table.status === 'dirty') {
                    statusText = 'Kotor';
                }

                const html = `
                    <div class="kartu-meja ${className}">
                        <span class="nomor">${table.table_number}</span>
                        <span class="status">${statusText}</span>
                    </div>
                `;
                container.append(html);
            });
        }

        function renderKitchenOrders(respon) {
            const container = $('#grid-order');
            container.empty();
            const orders = respon.orders || [];

            if (orders.length === 0) {
                container.append('<div style="grid-column:1 / -1; color:#9ca3af; text-align:center; padding:24px;">Tidak ada antrean pesanan saat ini.</div>');
                return;
            }

            orders.forEach(function(order) {
                const isPending = parseInt(order.pending_items || 0, 10) > 0;
                const cardClass = isPending ? 'order-baru' : 'order-masak';
                const headerLabel = isPending ? 'Baru Masuk' : 'Sedang Dimasak';
                const buttonLabel = isPending ? 'Masak Semua 👨‍🍳' : 'Selesai / Sajikan 🍽️';
                const timeLabel = formatMinutes(order.created_at);

                let itemsHtml = '';
                (order.items || []).forEach(function(item) {
                    const noteText = item.notes ? `Catatan: ${item.notes}` : 'Catatan: -';
                    
                    itemsHtml += `
                        <div class="item-menu">
                            <div>
                                <span class="qty">${item.quantity}x</span>
                                <span class="nama-menu">${item.name}</span>
                            </div>
                            <p class="catatan-normal">${noteText}</p>
                        </div>
                    `;
                });

                const html = `
                    <div class="kartu-order ${cardClass}">
                        <div class="order-header">
                            <div class="header-title">
                                <span>${headerLabel}</span>
                                <h3>${order.table_number}</h3>
                            </div>
                            <span class="waktu">${timeLabel}</span>
                        </div>
                        <div class="order-body">
                            ${itemsHtml}
                        </div>
                        <div class="order-footer">
                            <button class="btn" data-order-id="${order.id}" data-current-status="${isPending ? 'pending' : 'cooking'}">${buttonLabel}</button>
                        </div>
                    </div>
                `;
                container.append(html);
            });
        }

        function fetchData() {
            $.ajax({
                url: 'ajaxserver.php?page=sync',
                type: 'POST',
                data: {
                    globaltoken: globaltoken,
                    target: targetSync
                },
                dataType: 'json',
                success: function(respon) {
                    if (respon.status === 'success') {
                        renderKitchenTables(respon);
                        renderKitchenOrders(respon);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                },
                complete: function() {
                    if (document.getElementById('grid-order')) {
                        setTimeout(fetchData, 5000);
                    }
                }
            });
        }

        // Event Handler Utama untuk Nota Pesanan (Satu Tombol untuk Semua Item di Nota Tersebut)
        $(document).on('click', '.btn', function(e) {
            e.preventDefault();
            const orderId = $(this).data('order-id');
            const currentStatus = $(this).data('current-status');
            const nextStatus = currentStatus === 'pending' ? 'cooking' : 'done';

            $.ajax({
                url: 'ajaxserver.php?page=update_order_status',
                type: 'POST',
                data: {
                    globaltoken: globaltoken,
                    target: targetSync,
                    order_id: orderId,
                    status: nextStatus
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.status === 'success') {
                        fetchData();
                    } else {
                        alert('Gagal update order: ' + resp.message);
                    }
                },
                error: function() {
                    alert('Error updating order status');
                }
            });
        });

        fetchData();
    });
    </script>
</body>
</html>