<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Kasir</title>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4a90e2;
            --primary-dark: #357abd;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f1c40f;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text-gray: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background-color: #f1f5f9; color: var(--dark); display: flex; min-height: 100vh; overflow-x: hidden; }

        /* --- SIDEBAR --- */
        .sidebar { width: 260px; background-color: var(--dark); color: #fff; display: flex; flex-direction: column; transition: all 0.3s ease; z-index: 100; }
        .sidebar-brand { padding: 20px; font-size: 20px; font-weight: bold; border-bottom: 1px solid #334155; display: flex; align-items: center; gap: 10px; }
        .sidebar-brand i { color: var(--primary); }
        .sidebar-menu { list-style: none; padding: 15px 0; flex: 1; }
        .sidebar-menu li a { display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #94a3b8; text-decoration: none; font-size: 15px; transition: all 0.2s; border-left: 4px solid transparent; }
        .sidebar-menu li a:hover, .sidebar-menu li.active a { color: #fff; background-color: #334155; border-left-color: var(--primary); }
        
        /* --- MAIN CONTENT --- */
        .main-content { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        
        /* Top Navbar */
        .navbar { background: #fff; height: 60px; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); }
        .toggle-btn { background: none; border: none; font-size: 20px; cursor: pointer; color: var(--dark); display: none; }
        .user-profile { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; }
        .user-profile i { font-size: 24px; color: var(--text-gray); }

        /* Content Wrapper */
        .content-body { padding: 20px; flex: 1; overflow-y: auto; }
        .page-section { display: none; }
        .page-section.active { display: block; animation: fadeIn 0.4s ease; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .page-title { font-size: 22px; margin-bottom: 20px; font-weight: 700; color: #0f172a; }

        /* --- UTILITIES & CARDS --- */
        .grid-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .card-stat { background: #fff; padding: 20px; border-radius: 10px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .stat-info h3 { font-size: 14px; color: var(--text-gray); font-weight: 500; text-transform: uppercase; }
        .stat-info p { font-size: 24px; font-weight: bold; margin-top: 5px; }
        .stat-icon { width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        
        .bg-blue-light { background: #e0f2fe; color: #0284c7; }
        .bg-green-light { background: #dcfce7; color: #15803d; }
        .bg-orange-light { background: #ffedd5; color: #ea580c; }

        /* --- MANAJEMEN MEJA (GRID DENAH) --- */
        .grid-meja { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; margin-top: 15px; }
        .card-meja { position: relative; background: #fff; border-radius: 10px; border: 1px solid var(--border); padding: 20px 15px 15px 15px; text-align: center; display: flex; flex-direction: column; gap: 10px; transition: transform 0.2s; }
        .card-meja:hover { transform: translateY(-3px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .meja-icon { font-size: 32px; margin-bottom: 5px; }
        .meja-name { font-weight: bold; font-size: 16px; }
        
        /* Tombol Aksi Edit & Hapus Meja Melayang */
        .meja-actions { position: absolute; top: 8px; right: 8px; display: flex; gap: 5px; }
        .btn-action-meja { background: #f1f5f9; border: 1px solid var(--border); width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px; transition: all 0.2s; color: var(--dark); }
        .btn-action-meja.edit:hover { background: var(--warning); color: white; border-color: var(--warning); }
        .btn-action-meja.delete:hover { background: var(--danger); color: white; border-color: var(--danger); }
        
        /* Status Meja */
        .status-indicator { display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px; }
        .status-kosong { background: #dcfce7; color: #15803d; }
        .status-kosong .meja-icon-color { color: var(--success); }
        .status-terisi { background: #fee2e2; color: #b91c1c; }
        .status-terisi .meja-icon-color { color: var(--danger); }

        /* Table & Action Elements */
        .card-table { background: #fff; border-radius: 10px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 20px; }
        .table-header { padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid var(--border); }
        .btn { padding: 8px 16px; border-radius: 6px; border: none; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: background 0.2s; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-sm { padding: 5px 10px; font-size: 12px; border-radius: 4px; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--dark); }
        .btn-outline:hover { background: var(--light); }

        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        th, td { padding: 12px 20px; border-bottom: 1px solid var(--border); }
        th { background: var(--light); font-weight: 600; color: #475569; }
        tr:hover { background-color: #f8fafc; }

        /* Badge Status */
        .badge { padding: 4px 8px; border-radius: 50px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }

        /* --- RESPONSIVE MOBILE CONFIG --- */
        @media (max-width: 768px) {
            body { position: relative; }
            .sidebar { position: fixed; left: -260px; top: 0; bottom: 0; }
            .sidebar.open { left: 0; }
            .toggle-btn { display: block; }
            .table-header { flex-direction: column; align-items: stretch; }
            .table-header .btn { justify-content: center; }
            
            /* Overlay saat menu HP terbuka */
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 99; }
            .sidebar.open + .sidebar-overlay { display: block; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-shop"></i> Admin Kopi-Yuk
        </div>
        <ul class="sidebar-menu">
            <li class="active"><a href="#" data-target="dashboard"><i class="fa-solid fa-chart-pie"></i> Ringkasan</a></li>
            <li><a href="#" data-target="meja"><i class="fa-solid fa-chair"></i> Denah & Meja</a></li>
            <li><a href="#" data-target="users"><i class="fa-solid fa-users"></i> Kelola User</a></li>
            <li><a href="#" data-target="menuharga"><i class="fa-solid fa-utensils"></i> Menu & Harga</a></li>
            <li><a href="#" data-target="laporan"><i class="fa-solid fa-file-invoice-dollar"></i> Laporan</a></li>
            <li><a href="logout.php" data-target="logout"><i class="fa-solid fa-user"></i> Logout</a></li>
        </ul>
    </div>
    <!-- Overlay untuk klik di luar sidebar versi HP -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- TOP NAVBAR -->
        <div class="navbar">
            <button class="toggle-btn" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
            <div class="user-profile">
                <i class="fa-solid fa-circle-user"></i>
                <span>Halo, Admin Utama</span>
            </div>
        </div>

        <!-- CONTENT BODY -->
        <div class="content-body">
            
            <!-- 1. SECTION: DASHBOARD / RINGKASAN -->
            <?php include 'modul/dashboard.php'; ?>

            <!-- 2. SECTION: MANAJEMEN MEJA -->
            <div id="meja" class="page-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h1 class="page-title" style="margin: 0;">Manajemen & Status Meja</h1>
                    <button class="btn btn-primary" id="btn-tambah-meja"><i class="fa-solid fa-plus"></i> Tambah Meja</button>
                </div>

                <!-- Container Denah Layout Meja -->
                <div class="grid-meja" id="box-denah-meja">
                    
                    <!-- Meja 1 -->
                    <div class="card-meja status-terisi" data-nomor="1">
                        <div class="meja-actions">
                            <button class="btn-action-meja edit" onclick="alert('Ubah setelan Meja 01')"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-action-meja delete btn-hapus-meja"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div class="meja-icon"><i class="fa-solid fa-couch meja-icon-color"></i></div>
                        <div class="meja-name">Meja 01</div>
                        <span class="status-indicator status-terisi">Terisi (Makan)</span>
                        <button class="btn btn-outline btn-sm btn-ubah-status">Kosongkan Meja</button>
                    </div>

                    <!-- Meja 2 -->
                    <div class="card-meja status-kosong" data-nomor="2">
                        <div class="meja-actions">
                            <button class="btn-action-meja edit" onclick="alert('Ubah setelan Meja 02')"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-action-meja delete btn-hapus-meja"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div class="meja-icon"><i class="fa-solid fa-couch meja-icon-color"></i></div>
                        <div class="meja-name">Meja 02</div>
                        <span class="status-indicator status-kosong">Kosong</span>
                        <button class="btn btn-outline btn-sm btn-ubah-status">Isi Manual</button>
                    </div>

                    <!-- Meja 3 -->
                    <div class="card-meja status-terisi" data-nomor="3">
                        <div class="meja-actions">
                            <button class="btn-action-meja edit" onclick="alert('Ubah setelan Meja 03')"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-action-meja delete btn-hapus-meja"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div class="meja-icon"><i class="fa-solid fa-couch meja-icon-color"></i></div>
                        <div class="meja-name">Meja 03</div>
                        <span class="status-indicator status-terisi">Terisi (Makan)</span>
                        <button class="btn btn-outline btn-sm btn-ubah-status">Kosongkan Meja</button>
                    </div>

                    <!-- Meja 4 -->
                    <div class="card-meja status-kosong" data-nomor="4">
                        <div class="meja-actions">
                            <button class="btn-action-meja edit" onclick="alert('Ubah setelan Meja 04')"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-action-meja delete btn-hapus-meja"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div class="meja-icon"><i class="fa-solid fa-couch meja-icon-color"></i></div>
                        <div class="meja-name">Meja 04</div>
                        <span class="status-indicator status-kosong">Kosong</span>
                        <button class="btn btn-outline btn-sm btn-ubah-status">Isi Manual</button>
                    </div>

                </div>
            </div>

            <!-- 3. SECTION: MANAGEMENT USER -->
            <div id="users" class="page-section">
                <h1 class="page-title">Management User & Hak Akses</h1>
                <div class="card-table">
                    <div class="table-header">
                        <h3 style="font-size: 15px;">Daftar Karyawan / Kasir</h3>
                        <button class="btn btn-primary" onclick="alert('Buka Modal Tambah User')"><i class="fa-solid fa-plus"></i> Tambah User</button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Karyawan</th>
                                    <th>Username</th>
                                    <th>Role / Jabatan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Siti Aminah</strong></td>
                                    <td>siti_kasir</td>
                                    <td>Kasir Siang</td>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Rian Hidayat</strong></td>
                                    <td>rian_admin</td>
                                    <td>Admin Toko</td>
                                    <td><span class="badge badge-success">Aktif</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4. SECTION: MENU & HARGA -->
            <div id="menuharga" class="page-section">
                <h1 class="page-title">Kelola Daftar Menu & Harga</h1>
                <div class="card-table">
                    <div class="table-header">
                        <h3 style="font-size: 15px;">Produk Aktif</h3>
                        <button class="btn btn-primary" onclick="alert('Buka Modal Tambah Produk')"><i class="fa-solid fa-plus"></i> Tambah Item Baru</button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Menu</th>
                                    <th>Kategori</th>
                                    <th>Harga Jual</th>
                                    <th>Stok Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Nasi Goreng Spesial</strong></td>
                                    <td>Makanan Utama</td>
                                    <td>Rp 25.000</td>
                                    <td><span class="badge badge-success">Tersedia</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i> Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Es Teh Manis</strong></td>
                                    <td>Minuman</td>
                                    <td>Rp 5.000</td>
                                    <td><span class="badge badge-success">Tersedia</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i> Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jeruk Peras</strong></td>
                                    <td>Minuman</td>
                                    <td>Rp 7.000</td>
                                    <td><span class="badge badge-danger">Habis</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i> Edit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 5. SECTION: LAPORAN -->
            <div id="laporan" class="page-section">
                <h1 class="page-title">Laporan Penjualan</h1>
                <p style="color: var(--text-gray);">Fitur penarikan data laporan penjualan, eksport Excel, dan cetak omset berkala.</p>
            </div>

        </div>
    </div>

    <!-- SCRIPT LOGIKAL INTERAKSI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       $(document).ready(function() {
            
            // 1. Logika Klik Menu Sidebar (Pindah Halaman Tanpa Reload)
            $('.sidebar-menu li a').on('click', function(e) {
                e.preventDefault();
                $('.sidebar-menu li').removeClass('active');
                $(this).parent().addClass('active');
                
                var targetSection = $(this).data('target');
                alert(targetSection);
                //$('.page-section').removeClass('active');
                //$('#' + targetSection).addClass('active');

                if($(window).width() <= 768) {
                    $('#sidebar').removeClass('open');
                }
            });
            // 2. Tombol Toggle Sidebar Versi HP / Mobile
            $('#toggleBtn, #sidebarOverlay').on('click', function() {
                $('#sidebar').toggleClass('open');
            });

            // 3. LOGIKA INTERAKTIF MANAJEMEN MEJA
            
            // A. Tambah Meja Baru
            $('#btn-tambah-meja').on('click', function() {
                var jumlahMejaSaatIni = $('.card-meja').length;
                var nomorMejaBaru = jumlahMejaSaatIni + 1;
                var formatNomor = nomorMejaBaru < 10 ? '0' + nomorMejaBaru : nomorMejaBaru;

                var htmlMejaBaru = `
                    <div class="card-meja status-kosong" data-nomor="${nomorMejaBaru}">
                        <div class="meja-actions">
                            <button class="btn-action-meja edit" onclick="alert('Ubah setelan Meja ${formatNomor}')"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-action-meja delete btn-hapus-meja"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div class="meja-icon"><i class="fa-solid fa-couch meja-icon-color"></i></div>
                        <div class="meja-name">Meja ${formatNomor}</div>
                        <span class="status-indicator status-kosong">Kosong</span>
                        <button class="btn btn-outline btn-sm btn-ubah-status">Isi Manual</button>
                    </div>
                `;
                
                $('#box-denah-meja').append(htmlMejaBaru);
                hitungUlangMejaTerisi();
            });

            // B. Mengubah Status Meja (Kosong <-> Terisi)
            $(document).on('click', '.btn-ubah-status', function() {
                var card = $(this).closest('.card-meja');
                
                if (card.hasClass('status-kosong')) {
                    card.removeClass('status-kosong').addClass('status-terisi');
                    card.find('.status-indicator').removeClass('status-kosong').addClass('status-terisi').text('Terisi (Makan)');
                    $(this).text('Kosongkan Meja');
                } else {
                    card.removeClass('status-terisi').addClass('status-kosong');
                    card.find('.status-indicator').removeClass('status-terisi').addClass('status-kosong').text('Kosong');
                    $(this).text('Isi Manual');
                }
                
                hitungUlangMejaTerisi();
            });

            // C. Hapus Meja Berdasarkan Kartu
            $(document).on('click', '.btn-hapus-meja', function() {
                var card = $(this).closest('.card-meja');
                var namaMeja = card.find('.meja-name').text();
                
                if(confirm('Apakah Anda yakin ingin menghapus ' + namaMeja + '?')) {
                    card.fadeOut(300, function() {
                        $(this).remove();
                        hitungUlangMejaTerisi();
                    });
                }
            });

            // D. Sinkronisasi Data Jumlah Meja Terisi ke Bagian Utama Dashboard
            function hitungUlangMejaTerisi() {
                var jumlahTerisi = $('.card-meja.status-terisi').length;
                $('#stat-pending-meja').text(jumlahTerisi + " Meja");
            }

        });
    </script>
</body>
</html>