<div id="users" class="page-section active">
    <h1 class="page-title">Management User & Hak Akses</h1>
    <div class="card-table">
        <div class="table-header">
            <h3 style="font-size: 15px;">Daftar Karyawan / Kasir</h3>
            <div style="display: flex; gap: 10px; align-items: center;">
                <button class="btn btn-primary" onclick="route('form_tambah_user','popupcontent','1','true')">
                    <i class="fa-solid fa-plus"></i> Tambah User
                </button>
                <button class="btn btn-danger" id="tombol-terhapus" style="width: 160px;" onclick="route('list_user&cat=terhapus','table-user','0','false'); $('#tombol-aktif').show(); $('#tombol-terhapus').hide();">
                    <i class="fa-solid fa-lock"></i> User Terhapus
                </button>
                <button class="btn btn-success" id="tombol-aktif" style="display:none; width: 160px;" onclick="route('list_user','table-user','0','false'); $('#tombol-aktif').hide(); $('#tombol-terhapus').show();">
                    <i class="fa-solid fa-check"></i> User Aktif
                </button>
            </div>
        </div>
        <div class="table-responsive" id="table-user">
            <?php include('admin/modul/list_user.php'); ?>
        </div>
    </div>
</div>