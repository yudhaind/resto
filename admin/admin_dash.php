<?php 
$sql="SELECT * FROM `global_settings`";
$hasil=fetchOne($sql);
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="assets/fontawesome-web/css/all.min.css">
    <script src="assets/js/jquery-4.0.0.min.js"></script>
    <link rel="stylesheet" href="assets/css/adminstyle.css">
    <script src="assets/js/main.js"></script>
    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="assets/css/form.css">
    <style>

    </style>
</head>
<body>
    <input type="hidden" id="globaltoken" value="<?= $_SESSION['globaltoken']; ?>">
    <!-- LIGHTBOX UNTUK POPUP -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-content">
        <button class="btn btn-danger btn-sm close-btn" onclick="closeLightbox()"><i class="fa-solid fa-xmark"></i></button>
		<div id="popupcontent" class="popup-content"> </div>
    </div>
</div>
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-shop"></i><span id="nama-toko"><?= $hasil['value']; ?></span>
        </div>
        <ul class="sidebar-menu">
            <?php 
            $lastpage = $_SESSION['lastpage'] ?? 'dashboard'; // Ambil nilai lastpage dari session, default ke 'dashboard' jika tidak ada
            $classDashboard = ($lastpage === 'dashboard') ? 'active' : '';
            $classMeja = ($lastpage === 'meja') ? 'active' : '';
            $classUsers = ($lastpage === 'users') ? 'active' : '';
            $classMenuHarga = ($lastpage === 'menuharga') ? 'active' : '';
            $classLaporan = ($lastpage === 'laporan') ? 'active' : '';
            $classSetting = ($lastpage === 'setting') ? 'active' : '';
            ?>
            <li class="<?= $classDashboard; ?>"><a href="#" data-target="dashboard"><i class="fa-solid fa-chart-pie"></i> Ringkasan</a></li>
            <li class="<?= $classMeja; ?>"><a href="#" data-target="meja"><i class="fa-solid fa-chair"></i> Denah & Meja</a></li>
            <li class="<?= $classUsers; ?>"><a href="#" data-target="users"><i class="fa-solid fa-users"></i> Kelola User</a></li>
            <li class="<?= $classMenuHarga; ?>"><a href="#" data-target="menuharga"><i class="fa-solid fa-utensils"></i> Menu & Harga</a></li>
            <li class="<?= $classLaporan; ?>"><a href="#" data-target="laporan"><i class="fa-solid fa-file-invoice-dollar"></i> Laporan</a></li>
            <li class="<?= $classSetting; ?>"><a href="#" data-target="setting"><i class="fa-solid fa-gear"></i> Setting</a></li>
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
                <span>Halo, <?= $_SESSION['user']['name']; ?> </span>
            </div>
        </div>

        <!-- CONTENT BODY -->
        <div class="content-body" id="content-body">
            
            <!-- 1. SECTION: DASHBOARD / RINGKASAN -->
            <?php include 'modul/'.($_SESSION['lastpage'] ?? 'dashboard') . '.php'; ?>
            
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
<?php $_SESSION['lastpage'] = $_POST['page'] ?? 'dashboard'; ?>
    <!-- SCRIPT LOGIKAL INTERAKSI -->
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
            
            $(document).on('click', '.btn-ubah-status', function() {
                var card = $(this).closest('.card-meja'); 
                var id_meja = card.data('nomor');
                var globaltoken = $('#globaltoken').val(); 
                route('ubah_status_meja&id_meja=' + id_meja + '&token=' + globaltoken, 'popupcontent', '0', 'false');
            });
        });
    </script>
    <script src="assets/js/lightbox.js"></script>
</body>
</html>
