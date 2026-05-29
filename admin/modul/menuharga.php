<div id="menuharga" class="page-section active">
    <h1 class="page-title">Kelola Daftar Menu & Harga</h1>
    <div class="card-table">
        <div class="table-header">
            <h3 style="font-size: 15px;">Produk Aktif</h3>
            <button class="btn btn-primary" onclick="route('form_tambah_produk','popupcontent','1','true');">
                <i class="fa-solid fa-plus"></i> Tambah Item Baru
            </button>
        </div>

        <div class="table-responsive" id="table-product">
         <?php include('admin/modul/list_produk.php'); ?>
        </div>
    </div>
</div>