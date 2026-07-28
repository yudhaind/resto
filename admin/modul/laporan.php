<style>
.laporan-page {
    display: block;
}
.laporan-description {
    color: var(--text-gray, #666);
    margin-bottom: 16px;
}
.laporan-filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: flex-end;
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
    color: var(--text-gray, #666);
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
    color: var(--text-gray, #666);
}
.laporan-pagination {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
}
.laporan-pagination button {
    border: 1px solid #d1d5db;
    background: #fff;
    color: #374151;
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
}
.laporan-pagination button:hover:not(:disabled) {
    background: #f3f4f6;
}
.laporan-pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.laporan-pagination button.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}
.laporan-page-info {
    color: var(--text-gray, #666);
    font-size: 13px;
}
.field-hidden {
    display: none;
}
</style>

<div id="laporan" class="page-section active laporan-page">
    <h1 class="page-title">Laporan Penjualan</h1>
    <p class="laporan-description">Tampilan laporan penjualan dinamis berdasarkan filter waktu.</p>

    <div class="laporan-filter-form">
        <input type="hidden" name="page" value="laporan">
        
        <!-- Jenis Filter -->
        <div>
            <label class="laporan-label" for="filter_mode">Jenis Filter</label>
            <select class="laporan-select" name="filter_mode" id="filter_mode" onchange="toggleFilterInputs()">
                <option value="">Pilih Filter</option>
                <option value="harian" <?= (isset($_GET['filter_mode']) && $_GET['filter_mode'] == 'harian') ? 'selected' : '' ?>>Harian</option>
                <option value="bulanan" <?= (isset($_GET['filter_mode']) && $_GET['filter_mode'] == 'bulanan') ? 'selected' : '' ?>>Bulanan</option>
                <option value="tahunan" <?= (isset($_GET['filter_mode']) && $_GET['filter_mode'] == 'tahunan') ? 'selected' : '' ?>>Tahunan</option>
            </select>
        </div>

        <!-- Tanggal (1-31) -->
        <div id="container-day" style="display: none;">
            <label class="laporan-label" for="day">Tanggal</label>
            <select class="laporan-select laporan-select-sm" name="day" id="day">
                <option value="">Pilih Tanggal</option>
                <?php for ($d = 1; $d <= 31; $d++): ?>
                    <option value="<?= $d ?>" <?= (isset($_GET['day']) && $_GET['day'] == $d) ? 'selected' : '' ?>><?= $d ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- Bulan (Jan-Des) -->
        <div id="container-month" style="display: none;">
            <label class="laporan-label" for="month">Bulan</label>
            <select class="laporan-select laporan-select-md" name="month" id="month">
                <option value="">Pilih Bulan</option>
                <?php 
                $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                foreach ($bulan as $num => $nama): 
                ?>
                    <option value="<?= $num ?>" <?= (isset($_GET['month']) && $_GET['month'] == $num) ? 'selected' : '' ?>><?= $nama ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tahun -->
        <div id="container-year" style="display: none;">
            <label class="laporan-label" for="year">Tahun</label>
            <select class="laporan-select laporan-select-lg" name="year" id="year">
                <option value="">Pilih Tahun</option>
                <?php 
                $tahun_sekarang = date('Y');
                for ($y = $tahun_sekarang; $y >= $tahun_sekarang - 5; $y--): 
                ?>
                    <option value="<?= $y ?>" <?= (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div>
            <button type="button" class="btn btn-primary" onclick="submitFilters()">Tampilkan</button>
        </div>
        <div>
            <a href="#" class="btn btn-secondary" onclick="resetFilters()">Reset</a>
        </div>
    </div>

    <div class="laporan-summary">
        <strong>Total Penjualan:</strong> Rp <?= number_format($total_penjualan ?? 0, 0, ',', '.') ?> |
        <strong>Total Kembalian:</strong> Rp <?= number_format($total_kembalian ?? 0, 0, ',', '.') ?> |
        <strong>Jumlah Transaksi:</strong> <?= $jumlah_transaksi ?? 0 ?>
    </div>

    <div class="laporan-table-wrap" id="laporanTableWrap">
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
            <tbody id="laporanTableBody">
                <?php if (!empty($data_laporan)): ?>
                    <?php $no = 1; foreach ($data_laporan as $row): ?>
                        <tr class="laporan-row">
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['order_id']) ?></td>
                            <td>Rp <?= number_format($row['total_dibayar'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($row['jumlah_pembayaran'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($row['kembalian'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['jenis_pembayaran']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="laporan-empty" colspan="6">Belum ada data penjualan untuk ditampilkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div id="laporanPagination" class="laporan-pagination" style="display: none;"></div>
    </div>
</div>

<script>
function toggleFilterInputs() {
    const filterMode = document.getElementById('filter_mode').value;
    const containerDay = document.getElementById('container-day');
    const containerMonth = document.getElementById('container-month');
    const containerYear = document.getElementById('container-year');

    // Sembunyikan semua input secara default saat dimuat atau saat tidak ada filter terpilih
    containerDay.style.display = 'none';
    containerMonth.style.display = 'none';
    containerYear.style.display = 'none';

    // Tampilkan hanya input yang sesuai dengan opsi yang dipilih
    if (filterMode === 'harian') {
        containerDay.style.display = 'block';
        containerMonth.style.display = 'block';
        containerYear.style.display = 'block';
    } else if (filterMode === 'bulanan') {
        containerMonth.style.display = 'block';
        containerYear.style.display = 'block';
    } else if (filterMode === 'tahunan') {
        containerYear.style.display = 'block';
    }
}

// Jalankan saat pertama kali halaman dimuat
document.addEventListener('DOMContentLoaded', function () {
    toggleFilterInputs();

    const tableBody = document.getElementById('laporanTableBody');
    const pagination = document.getElementById('laporanPagination');

    if (!tableBody || !pagination) {
        return;
    }

    const rows = Array.from(tableBody.querySelectorAll('tr.laporan-row'));
    const rowsPerPage = 10;
    const totalRows = rows.length;

    if (totalRows <= rowsPerPage) {
        rows.forEach(function (row) {
            row.style.display = '';
        });
        pagination.style.display = 'none';
        return;
    }

    pagination.style.display = 'flex';
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    let currentPage = 1;

    function renderPage(page) {
        currentPage = page;
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach(function (row, index) {
            row.style.display = (index >= start && index < end) ? '' : 'none';
        });

        const pageButtons = pagination.querySelectorAll('.page-number-btn');
        pageButtons.forEach(function (button) {
            const pageNumber = Number(button.getAttribute('data-page'));
            button.classList.toggle('active', pageNumber === page);
        });

        const prevButton = pagination.querySelector('.prev-page-btn');
        const nextButton = pagination.querySelector('.next-page-btn');
        if (prevButton) prevButton.disabled = page === 1;
        if (nextButton) nextButton.disabled = page === totalPages;

        const infoText = pagination.querySelector('.laporan-page-info');
        if (infoText) {
            infoText.textContent = 'Halaman ' + page + ' dari ' + totalPages;
        }
    }

    pagination.innerHTML = '';

    const prevButton = document.createElement('button');
    prevButton.type = 'button';
    prevButton.className = 'prev-page-btn';
    prevButton.textContent = 'Prev';
    prevButton.addEventListener('click', function () {
        if (currentPage > 1) {
            renderPage(currentPage - 1);
        }
    });

    pagination.appendChild(prevButton);

    for (let page = 1; page <= totalPages; page++) {
        const pageButton = document.createElement('button');
        pageButton.type = 'button';
        pageButton.className = 'page-number-btn';
        pageButton.setAttribute('data-page', page);
        pageButton.textContent = page;
        pageButton.addEventListener('click', function () {
            renderPage(page);
        });
        pagination.appendChild(pageButton);
    }

    const nextButton = document.createElement('button');
    nextButton.type = 'button';
    nextButton.className = 'next-page-btn';
    nextButton.textContent = 'Next';
    nextButton.addEventListener('click', function () {
        if (currentPage < totalPages) {
            renderPage(currentPage + 1);
        }
    });

    pagination.appendChild(nextButton);

    const infoText = document.createElement('span');
    infoText.className = 'laporan-page-info';
    pagination.appendChild(infoText);

    renderPage(1);
});
</script>

<?php $_SESSION['lastpage'] = 'laporan'; ?>