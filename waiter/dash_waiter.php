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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Background Halaman Utama */
        body {
            background-color: #1a2332;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Container Utama (Lebar maksimal diperbesar agar muat 4 kolom di PC) */
        .container {
            background-color: #222e43;
            border: 1px solid #2d3d56;
            border-radius: 16px;
            padding: 24px;
            width: 100%;
            max-width: 800px; /* Diperlebar dari 380px ke 800px */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        /* Bagian Header / Judul */
        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            color: #94a3b8;
            font-weight: bold;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .icon-chair {
            font-size: 1.2rem;
        }

        /* --- TAMPILAN DEFAULT (UNTUK HP) --- */
        /* Menggunakan 2 Kolom secara default */
        .grid-meja {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        /* Kartu Meja (Box) */
        .card-meja {
            background-color: #2c3a50;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1 / 1;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Tulisan Nama Meja */
        .nama-meja {
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 12px;
        }

        /* Badge Status Umum */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 20px;
            border-radius: 50px;
            text-align: center;
            min-width: 70px;
        }

        /* Status Terisi (Merah) */
        .badge.terisi {
            background-color: #4c2729;
            color: #ef4444;
            border: 1px solid #7f2d2f;
        }

        /* Status kosong (Hijau) */
        .badge.kosong {
            background-color: #1e3a31;
            color: #10b981;
            border: 1px solid #14532d;
        }

        /*tombol update*/
        .btn-update {
            margin-top: 12px;
            padding: 6px 12px;
            background-color: #3b82f6;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            transition: background-color 0.3s ease;
        }

        .status-makanan {
            margin-top: 8px;
            font-size: 0.75rem;
            color: #94a3b8;
        }

        /* --- TAMPILAN UNTUK PC / LAYAR LEBAR --- */
        /* Jika lebar layar minimal 600px (ukuran PC/Tablet), grid berubah jadi 4 kolom */
        @media (min-width: 600px) {
            .grid-meja {
                grid-template-columns: repeat(4, 1fr);
            }
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
                <span class="nama-meja">Meja 1</span>
                <span class="badge terisi">Terisi</span>
            </div>

            <div class="card-meja">
                <span class="nama-meja">Meja 2</span>
                <span class="badge terisi">Terisi</span>
            </div>

            <div class="card-meja">
                <span class="nama-meja">Meja 3</span>
                <span class="badge siap">Siap</span>
            </div>

            <div class="card-meja">
                <span class="nama-meja">Meja 4</span>
                <span class="badge siap">Siap</span>
            </div>

            <div class="card-meja">
                <span class="nama-meja">Meja 5</span>
                <span class="badge siap">Siap</span>
                <button class="btn-update">Kosongkan</button>
            </div>
        </div>
        <div id="status-sync"></div>
    </div>
<input type="hidden" id="target-sync" value="table_waiter" disabled>

<script>
function RenderMeja(ResponServer){
    var data_meja = ResponServer.list_meja; 
    if (!data_meja) {
        console.error('Data meja tidak ditemukan dalam respon server.');
        return;
    }
    
    var gridMeja = $('#grid-meja');
    gridMeja.empty(); // Bersihkan konten grid
    
    data_meja.forEach(function(meja) {
        var statusClass = meja.status === 'occupied' ? 'terisi' : 'kosong';
        
        // 1. Tentukan style display: jika occupied maka '', jika kosong maka 'display: none;'
        var tombolDisplay = meja.status === 'occupied' ? '' : 'style="display: none;"';
        
        // 2. Gunakan CLASS untuk tombol, jangan gunakan ID yang sama berulang kali
        var cardMeja = `
            <div class="card-meja">
                <span class="nama-meja">${meja.nomeja}</span>
                <span class="badge ${statusClass}">${meja.status === 'occupied' ? 'Terisi' : 'Kosong'}</span>
                <span class="status-makanan" ${tombolDisplay}>Status Makanan:</span>
                <button class="btn-update" ${tombolDisplay}>Kosongkan</button>
            </div>
        `;
        
        gridMeja.append(cardMeja);
    });
}

 $(document).ready(function() {
    // 1. Inisialisasi token dan target
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
                    // 2. Cek apakah nilai baru berbeda dengan nilai lama
                    console.log('Data berhasil diambil:', respon);
                    $('#status-sync').text('Total Meja: ' + respon.total_meja); // Menampilkan data untuk debugging
                    RenderMeja(respon);
                    // Tempatkan logika perbandingan nilai Anda di sini
                }
            },
            error: function(xhr, status, error) {
            // Check if the response was empty
            if (xhr.responseText === "") {
                console.error('Error: Server returned an empty response.');
                } else {
                console.error('Koneksi gagal atau file tidak ditemukan:', error);
                }
            },
            complete: function() {
                // 3. Polling setiap 5 detik setelah request selesai
                setTimeout(fetchData, 5000);
            }
        }); // <-- Penutup $.ajax yang benar
    } // <-- Penutup fungsi fetchData() yang benar

    // Pemicu pertama kali saat halaman siap
    fetchData();
});
</script>       
</body>
</html>