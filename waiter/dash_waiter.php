<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Waiter - Manajemen Meja</title>
    <style>
        :root {
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-color: #333333;
            --text-muted: #777777;
            --primary: #4a90e2;
            
            /* Status Colors */
            --status-pending: #ff9800;   /* Menunggu */
            --status-cooking: #2196f3;   /* Dimasak */
            --status-served: #4caf50;    /* Disajikan */
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        }

        header {
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }

        header p {
            margin: 5px 0 0 0;
            color: var(--text-muted);
        }

        /* Grid Sistem untuk Meja */
        .grid-meja {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        /* Kartu Meja */
        .card-meja {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 20px;
            border-top: 4px solid var(--primary);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header-meja {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .nomor-meja {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        .jumlah-pelanggan {
            font-size: 14px;
            color: var(--text-muted);
            background: #eef2f7;
            padding: 4px 8px;
            border-radius: 12px;
        }

        /* Daftar Pesanan */
        .daftar-pesanan {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        .item-pesanan {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .item-pesanan:last-child {
            border-bottom: none;
        }

        .nama-menu {
            font-size: 15px;
        }

        .qty {
            font-weight: bold;
            color: var(--primary);
            margin-right: 5px;
        }

        /* Badge Status */
        .badge {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pending {
            background-color: #fff3e0;
            color: var(--status-pending);
        }

        .badge-cooking {
            background-color: #e3f2fd;
            color: var(--status-cooking);
        }

        .badge-served {
            background-color: #e8f5e9;
            color: var(--status-served);
        }

        /* Tombol Aksi */
        .btn-aksi {
            width: 100%;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-aksi:hover {
            background-color: #357abd;
        }
    </style>
</head>
<body>

    <header>
        <h1>Dashboard Waiter</h1>
        <p>Sistem Pemantauan Pesanan & Meja Real-time</p>
    </header>

    <main class="grid-meja">

        <div class="card-meja">
            <div>
                <div class="header-meja">
                    <span class="nomor-meja">Meja 01</span>
                    <span class="jumlah-pelanggan">👥 2 Orang</span>
                </div>
                <ul class="daftar-pesanan">
                    <li class="item-pesanan">
                        <span><span class="qty">2x</span> Nasi Goreng Spesial</span>
                        <span class="badge badge-served">Disajikan</span>
                    </li>
                    <li class="item-pesanan">
                        <span><span class="qty">1x</span> Es Teh Manis</span>
                        <span class="badge badge-served">Disajikan</span>
                    </li>
                    <li class="item-pesanan">
                        <span><span class="qty">1x</span> Jus Alpukat</span>
                        <span class="badge badge-cooking">Dimasak</span>
                    </li>
                </ul>
            </div>
            <button class="btn-aksi" onclick="alert('Panggil kasir atau tambah pesanan Meja 01')">Aksi Meja</button>
        </div>

        <div class="card-meja">
            <div>
                <div class="header-meja">
                    <span class="nomor-meja">Meja 04</span>
                    <span class="jumlah-pelanggan">👥 4 Orang</span>
                </div>
                <ul class="daftar-pesanan">
                    <li class="item-pesanan">
                        <span><span class="qty">1x</span> Mie Goreng Seafood</span>
                        <span class="badge badge-pending">Menunggu</span>
                    </li>
                    <li class="item-pesanan">
                        <span><span class="qty">1x</span> Ayam Bakar Taliwang</span>
                        <span class="badge badge-pending">Menunggu</span>
                    </li>
                    <li class="item-pesanan">
                        <span><span class="qty">4x</span> Air Mineral</span>
                        <span class="badge badge-served">Disajikan</span>
                    </li>
                </ul>
            </div>
            <button class="btn-aksi" onclick="alert('Panggil kasir atau tambah pesanan Meja 04')">Aksi Meja</button>
        </div>

        <div class="card-meja">
            <div>
                <div class="header-meja">
                    <span class="nomor-meja">Meja 07</span>
                    <span class="jumlah-pelanggan">👥 3 Orang</span>
                </div>
                <ul class="daftar-pesanan">
                    <li class="item-pesanan">
                        <span><span class="qty">3x</span> Ramen Shoyu</span>
                        <span class="badge badge-cooking">Dimasak</span>
                    </li>
                    <li class="item-pesanan">
                        <span><span class="qty">3x</span> Ocha (Cold)</span>
                        <span class="badge badge-served">Disajikan</span>
                    </li>
                </ul>
            </div>
            <button class="btn-aksi" onclick="alert('Panggil kasir atau tambah pesanan Meja 07')">Aksi Meja</button>
        </div>

    </main>

</body>
</html>