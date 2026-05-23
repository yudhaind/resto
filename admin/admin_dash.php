<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Kasir</title>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="assets/js/jquery-4.0.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/adminstyle.css">
    <script src="assets/js/main.js"></script>
    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="assets/css/form.css">
    <style>

    </style>
</head>
<body>
</body>
<input type="hidden" id="globaltoken" value="<?= $_SESSION['globaltoken']; ?>">
<!-- LIGHTBOX UNTUK POPUP -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-content">
        <span class="close-btn" onclick="closeLightbox()">&times;</span>
		<div id="popupcontent"> </div>
    </div>
</div>
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
            <li><a href="#" data-target="logout"><i class="fa-solid fa-user"></i> Logout</a></li>
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
        <div class="content-body" id="content-body">
            
            <!-- 1. SECTION: DASHBOARD / RINGKASAN -->
            <?php include 'modul/dashboard.php'; ?>
            
            <!-- 2. SECTION: MANAJEMEN MEJA -->
           <?php //include 'modul/meja.php'; ?>
            <!-- 3. SECTION: MANAGEMENT USER -->
           <?php //include 'modul/users.php'; ?>
             <!-- 4. SECTION: MENU & HARGA -->
           <?php //include 'modul/menuharga.php'; ?>
             <!-- 5. SECTION: LAPORAN -->
           <?php //include 'modul/laporan.php'; ?>

           
           

        </div>
    </div>

    <!-- SCRIPT LOGIKAL INTERAKSI -->
    <script src="assets/js/jquery-4.0.0.min.js"></script>
    <script>
       $(document).ready(function() {
            
            // 1. Logika Klik Menu Sidebar (Pindah Halaman Tanpa Reload)
            $('.sidebar-menu li a').on('click', function(e) {
                e.preventDefault();
                $('.sidebar-menu li').removeClass('active');
                $(this).parent().addClass('active');
                
                var targetSection = $(this).data('target');
                if(targetSection === 'logout') {
                    if(confirm('Apakah Anda yakin ingin logout?')) {
                        window.location.href = 'logout.php'; // Ganti dengan URL logout yang sesuai
                    }
                    return;
                } else {
                route(targetSection, 'content-body', '0','true'); // Panggil fungsi route untuk load konten
                //$('.page-section').removeClass('active');
                //$('#' + targetSection).addClass('active');
                }

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
    <script src="assets/js/lightbox.js"></script>
</body>
</html>