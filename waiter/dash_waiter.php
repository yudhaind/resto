<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Meja Responsif</title>
    <script src="assets/js/jquery-4.0.0.min.js"></script>
    <style>
        /* Reset dasar & Font */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
        }

        /* Background Halaman Utama */
        body {
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Lebih aman untuk konten panjang */
            padding: 24px 16px;
        }

        /* Container Utama */
        .container {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 24px;
            width: 100%;
            max-width: 1000px; /* Diperlebar agar layout fluid lebih maksimal */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        /* Bagian Header / Judul */
        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            color: #94a3b8;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #334155;
            padding-bottom: 16px;
        }

        .icon-chair {
            font-size: 1.5rem;
        }

        /* --- TAMPILAN FLUID GRID (OTOMATIS RESPONSIF) --- */
        .grid-meja {
            display: grid;
            /* auto-fit akan otomatis menambah kolom jika layar lebar, dan mengurangi jika layar sempit */
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }

        /* Kartu Meja (Box) */
        .card-meja {
            background-color: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 12px;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 180px; /* Menggunakan min-height agar tinggi seragam namun tetap fluid */
            justify-content: space-between; /* Menjaga tombol tetap di bawah */
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .card-meja:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
            border-color: #475569;
        }

        /* Wrapper konten atas untuk struktur flex */
        .card-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            gap: 8px;
            margin-bottom: 12px;
        }

        /* Tulisan Nama Meja */
        .nama-meja {
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: 600;
        }

        /* Badge Status Umum */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 50px;
            text-align: center;
            width: fit-content;
            letter-spacing: 0.3px;
        }

        /* Status Terisi (Merah) */
        .badge.terisi {
            background-color: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Status kosong (Hijau) */
        .badge.kosong {
            background-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        /* Keterangan Makanan */
        .status-makanan {
            text-align: left;
            font-size: 0.70rem;
            color: #94a3b8;
            line-height: 1.4;
            background: #1e293b;
            padding: 4px 8px;
            border-radius: 6px;
            width: 100%;
        }

        /* Tombol Update / Kosongkan */
        .btn-update {
            width: 100%;
            padding: 8px 12px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        .btn-update:hover {
            background-color: #1d4ed8;
        }

        .btn-update:active {
            transform: scale(0.98);
        }

        /* Status Sync Debugging */
        #status-sync {
            margin-top: 20px;
            font-size: 0.8rem;
            color: #64748b;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <span class="icon-chair">🪑</span>
            <span>STATUS MEJA</span>
        </div>

        <div class="grid-meja" id="grid-meja">
            <div class="card-meja">
                <div class="card-content">
                    <span class="nama-meja">Memuat...</span>
                </div>
            </div>
        </div>
        
        <div id="status-sync"></div>
    </div>

<input type="hidden" id="target-sync" value="table_waiter" disabled>

<script>
function RenderHalaman(ResponServer) {
    // 1. Ambil data list_tables
    var data_meja = ResponServer.list_tables; 
    // 2. Ambil data orders
    var data_pesanan = ResponServer.orders;

    if (!data_meja || !data_pesanan) {
        console.error('Data meja atau pesanan tidak ditemukan.');
        return;
    }
    
    // Update total meja di elemen debug
    $('#status-sync').text('Total Meja: ' + data_meja.length + ' | Pesanan Aktif: ' + data_pesanan.length);
    
    var gridMeja = $('#grid-meja');
    gridMeja.empty(); // Bersihkan grid sebelum render ulang
    
    // Looping untuk merender kartu meja
    data_meja.forEach(function(meja) {
        // Cek status meja berdasarkan nilai dari API ('occupied' atau 'available')
        var isOccupied = meja.status === 'occupied';
        var statusClass = isOccupied ? 'terisi' : 'kosong';
        var statusText = isOccupied ? 'Terisi' : 'Kosong';
        
        // Contoh mencari detail pesanan khusus untuk meja ini di dalam array orders (opsional)
        var detailPesananMeja = data_pesanan.find(order => order.table_id === meja.id);
        // Tentukan teks makanan jika ada pesanan aktif di meja tersebut
        var infoMakanan = '';
        
        if (isOccupied && detailPesananMeja && detailPesananMeja.items.length > 0) {
            // Pindahkan logika penentuan warna ke dalam .map()
            var namaMenu = detailPesananMeja.items.map(item => {
                var c_stat = "green"; // nilai default jika selesai
                
                if (item.cooking_status == 'pending') {
                    c_stat = "white";
                } else if (item.cooking_status == 'cooking') {
                    c_stat = "yellow";
                }
              
                
                return `${item.name} (${item.quantity}x)   <span style="display: inline-block; border: thin solid; width: 10px; height: 10px; background-color: ${c_stat};"></span>`;
            }).join('<br> ');
            
            infoMakanan = `<span class="status-makanan">${namaMenu}</span>`;
        } else if (isOccupied) {
            // Fallback jika occupied tapi data pesanan di orders belum sinkron
            if (meja.pending_count == 0 && meja.cooking_count == 0) {
                infoMakanan = 'Selesai dimasak';
            } else {
                 infoMakanan = 'Sedang dimasak';
            }
        }

        var cardMeja = `
            <div class="card-meja">
                <div class="card-content">
                    <span class="nama-meja">${meja.table_number}</span>
                    <span class="badge ${statusClass}">${statusText}</span>
                    ${infoMakanan}
                </div>
                ${isOccupied ? `<button class="btn-update">Kosongkan</button>` : ''}
            </div>
        `;
        
        gridMeja.append(cardMeja);
    });
}

$(document).ready(function() {
    const globaltoken = '<?php echo $_SESSION['globaltoken'] ?? ""; ?>';
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
                // Pastikan status dari server sukses
                if (respon.status === 'success') {
                    console.log('Data sukses diterima:', respon);
                    
                    // Panggil fungsi render tunggal dengan membawa objek 'respon' utuh
                    RenderHalaman(respon); 
                }
            },
            error: function(xhr, status, error) {
                console.error('Koneksi atau parsing data gagal:', error);
            },
            complete: function() {
                setTimeout(fetchData, 5000);
            }
        });
    }

    fetchData();
});
</script>       
</body>
</html>