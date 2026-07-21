<div id="meja" class="page-section active">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="page-title" style="margin: 0;">Manajemen & Status Meja</h1>
        <button class="btn btn-primary" id="btn-tambah-meja" onclick="route('tambah_meja', 'popupcontent', '1', 'true')">
            <i class="fa-solid fa-plus"></i> Tambah Meja
        </button>
    </div>

    <!-- Container Denah Layout Meja -->
    <div class="grid-meja" id="box-denah-meja">
        <input type="hidden" id="target-sync" value="meja" disabled>
    </div>
</div>
<?php $_SESSION['lastpage'] = 'meja'; ?>
<script>
    $(document).ready(function() {
        // 1. Inisialisasi nilaiLama di luar fungsi fetchData agar nilainya tetap tersimpan
        let nilaiLamaIsi = null;
        let nilaiLamaKsg = null;
        let nilaiWaktu = null;
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
                        if ((respon.nilai_isi !== nilaiLamaIsi) || (respon.nilai_ksg !== nilaiLamaKsg) || (respon.total_waktu !== nilaiWaktu)) {
                            route('denah_meja', 'box-denah-meja', '0', 'false');

                            // Perbarui nilai lama dengan nilai yang baru
                            nilaiLamaIsi = respon.nilai_isi;
                            nilaiLamaKsg = respon.nilai_ksg;
                            nilaiWaktu = respon.total_waktu;
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Koneksi gagal atau file tidak ditemukan:', error);
                },
                complete: function() {
                    // 3. AKTIFKAN KEMBALI: Lakukan polling setiap 5 detik setelah request selesai
                    const halamanMejaMasihAktif = document.getElementById('box-denah-meja');
                    if (halamanMejaMasihAktif) {
                        setTimeout(fetchData, 5000);
                    } else {
                        console.log('Halaman Manajemen Meja sudah tidak aktif. Polling dihentikan.');
                    }
                }
            });
        }

        // Pemicu pertama kali saat halaman siap
        fetchData();
    });
</script>
