 <div id="meja" class="page-section active">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h1 class="page-title" style="margin: 0;">Manajemen & Status Meja</h1>
                    <button class="btn btn-primary" id="btn-tambah-meja" onclick="route('tambah_meja', 'popupcontent', '1', 'true')"><i class="fa-solid fa-plus"></i> Tambah Meja</button>
                </div>

                <!-- Container Denah Layout Meja -->
                <div class="grid-meja" id="box-denah-meja">
                    <!-- Meja 2 
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

                     Meja 3 
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

                     Meja 4
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
--> 
                </div>
            </div>
            <script>
    $(document).ready(function() {
    // 1. Inisialisasi nilaiLama di luar fungsi fetchData agar nilainya tetap tersimpan
     let nilaiLamaIsi = null; 
     let nilaiLamaKsg = null;
    var globaltoken='<?php echo $_SESSION['globaltoken']; ?>';

   function fetchData() {
        $.ajax({
            url: 'ajaxserver.php?page=sync_meja',
            type: 'POST',
            data: { ajax: 'ajax',globaltoken:globaltoken},
            dataType: 'json',
            success: function(respon) {
                if(respon.status === 'success') {
                    // 2. Cek apakah nilai baru berbeda dengan nilai lama
                    if ((respon.nilai_isi !== nilaiLamaIsi) || (respon.nilai_ksg !== nilaiLamaKsg)) {
                            route('denah_meja', 'box-denah-meja', '0', 'false');
                        // Perbarui nilai lama dengan nilai yang baru
                        

                        nilaiLamaIsi = respon.nilai_isi;
                        nilaiLamaKsg = respon.nilai_ksg; 
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Koneksi gagal atau file tidak ditemukan:", error);
            },
            complete: function() {
                // 3. AKTIFKAN KEMBALI: Lakukan polling setiap 1 detik setelah request selesai
                const halamanMejaMasihAktif = document.getElementById('box-denah-meja');
                if (halamanMejaMasihAktif) {
                    setTimeout(fetchData, 5000);
                } else {
                    console.log("Halaman Manajemen Meja sudah tidak aktif. Polling dihentikan.");
                }
            }
        });
    }
    // Pemicu pertama kali saat halaman siap
    fetchData();

    /*function fetchData() {
    $.ajax({
        url: 'ajaxserver.php?page=sync_meja',
        type: 'POST',
        data: { ajax: 'ajax', globaltoken: globaltoken },
        dataType: 'json',
        success: function(respon) {
            if(respon.status === 'success') {
                // 2. Cek apakah nilai baru berbeda dengan nilai lama
                if (respon.nilai !== nilaiLama) {
                    route('denah_meja', 'box-denah-meja', '0', 'false');
                    
                    // ============================================================
                    // SISIPAN KODE: Proses ambil data JSON "daftarmeja" & update class
                    // ============================================================
                    const arrayMeja = respon.daftarmeja;

                    arrayMeja.forEach(meja => {
                        const nomorMeja = meja.table_number;  // Mengambil "1", "2", dst
                        const statusMeja = meja.status_meja; // Mengambil "occupied" atau "available"

                        // Cari elemen HTML berdasarkan atribut data-nomor
                        const elemenHTML = document.querySelector(`.card-meja[data-nomor="${nomorMeja}"]`);

                        // Jika elemen meja ditemukan di HTML, ubah class-nya
                        if (elemenHTML) {
                            const indicatorSpan = elemenHTML.querySelector('.status-indicator');
                            const tombolStatus = elemenHTML.querySelector('.btn-ubah-status');
                            if (statusMeja === 'occupied') {
                                elemenHTML.classList.remove('status-kosong');
                                elemenHTML.classList.add('status-terisi');
                                if (indicatorSpan) {
                                    indicatorSpan.classList.remove('status-kosong');
                                    indicatorSpan.classList.add('status-terisi');
                                    indicatorSpan.textContent = 'Terisi (Makan)';
                                }
                                if (tombolStatus) {
                                    tombolStatus.textContent = 'Kosongkan Meja';
                                }
                            } else if (statusMeja === 'available') {
                                elemenHTML.classList.remove('status-terisi');
                                elemenHTML.classList.add('status-kosong');
                                if (indicatorSpan) {
                                    indicatorSpan.classList.remove('status-terisi');
                                    indicatorSpan.classList.add('status-kosong');
                                    indicatorSpan.textContent = 'Kosong';
                                }
                                if (tombolStatus) {
                                    tombolStatus.textContent = 'Isi Meja';
                                }
                            }
                        }
                    });
                    // ============================================================

                    // Perbarui nilai lama dengan nilai yang baru
                    nilaiLama = respon.nilai; 
                }
            }
        },
        error: function(xhr, status, error) {
            console.error("Koneksi gagal atau file tidak ditemukan:", error);
        },
        complete: function() {
            // 3. AKTIFKAN KEMBALI: Lakukan polling setiap 5 detik setelah request selesai
            setTimeout(fetchData, 3000);
        }
    });
}
// Pemicu pertama kali saat halaman siap
fetchData();
*/
}); 


</script>
