<style>
.laporan-page {
    display: block;
}
.laporan-description {
    color: var(--text-gray);
    margin-bottom: 16px;
}
.laporan-filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: end;
    margin-bottom: 16px;
}
.laporan-label {
    display: block;
    font-size: 12px;
    margin-bottom: 4px;
}
.laporan-select {
    padding: 8px 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    min-width: 150px;
}
.laporan-select-sm {
    min-width: 100px;
}
.laporan-select-md {
    min-width: 140px;
}
.laporan-select-lg {
    min-width: 120px;
}
.laporan-summary {
    margin-bottom: 12px;
    color: var(--text-gray);
}
.laporan-table-wrap {
    overflow-x: auto;
}
.laporan-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border: 1px solid #e5e7eb;
}
.laporan-table th,
.laporan-table td {
    padding: 10px;
    border-bottom: 1px solid #e5e7eb;
}
.laporan-table thead tr {
    background: #f8fafc;
    text-align: left;
}
.laporan-empty {
    padding: 16px;
    text-align: center;
    color: var(--text-gray);
}
</style>

<div id="laporan" class="page-section active laporan-page">
    <h1 class="page-title">Laporan Penjualan</h1>
    <p class="laporan-description">Tampilan laporan penjualan dalam bentuk statis untuk kebutuhan desain dan preview.</p>

    <form class="laporan-filter-form" method="GET">
        <input type="hidden" name="page" value="laporan">
        <div>
            <label class="laporan-label" for="filter_mode">Jenis Filter</label>
            <select class="laporan-select" name="filter_mode" id="filter_mode">
                <option value="">Pilih Filter</option>
                <option value="harian">Harian</option>
                <option value="bulanan">Bulanan</option>
                <option value="tahunan">Tahunan</option>
            </select>
        </div>

        <div>
            <label class="laporan-label" for="day">Tanggal</label>
            <select class="laporan-select laporan-select-sm" name="day" id="day">
                <option value="">Pilih Tanggal</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>

        <div>
            <label class="laporan-label" for="month">Bulan</label>
            <select class="laporan-select laporan-select-md" name="month" id="month">
                <option value="">Pilih Bulan</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
            </select>
        </div>

        <div>
            <label class="laporan-label" for="year">Tahun</label>
            <select class="laporan-select laporan-select-lg" name="year" id="year">
                <option value="">Pilih Tahun</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
        <div>
            <a href="admin_dash.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="laporan-summary">
        <strong>Total Penjualan:</strong> Rp 0 |
        <strong>Total Kembalian:</strong> Rp 0 |
        <strong>Jumlah Transaksi:</strong> 0
    </div>

    <div class="laporan-table-wrap">
        <table class="laporan-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Order</th>
                    <th>Total Dibayar</th>
                    <th>Jumlah Pembayaran</th>
                    <th>Kembalian</th>
                    <th>Jenis Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="laporan-empty" colspan="6">Belum ada data penjualan untuk ditampilkan.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php $_SESSION['lastpage'] = 'laporan'; ?>