<input type="hidden" id="target-sync" value="dashboard">
<!-- LIGHTBOX UNTUK POPUP -->
<div id="dashboard" class="page-section active">
    <h1 class="page-title">Ringkasan Hari Ini</h1>
    <div class="grid-stats">
        <div class="card-stat">
            <div class="stat-info">
                <h3>Pendapatan</h3>
                <p id="stat-revenue">Rp 0</p>
            </div>
            <div class="stat-icon bg-green-light"><i class="fa-solid fa-wallet"></i></div>
        </div>
        <div class="card-stat">
            <div class="stat-info">
                <h3>Pesanan Lunas</h3>
                <p id="stat-paid-transactions">0 Transaksi</p>
            </div>
            <div class="stat-icon bg-blue-light"><i class="fa-solid fa-circle-check"></i></div>
        </div>
        <div class="card-stat">
            <div class="stat-info">
                <h3>Bayar Nanti (Pending)</h3>
                <p id="stat-pending-meja">0 Meja</p>
            </div>
            <div class="stat-icon bg-orange-light"><i class="fa-solid fa-clock"></i></div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    const globaltoken = $('#globaltoken').val();
    const targetSync = $('#target-sync').val() || 'dashboard';
    let pollTimer = null;

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(Number(value || 0));
    }

    function renderDashboard(respon) {
        if (!respon || respon.status !== 'success') {
            $('#stat-revenue').text('Rp 0');
            $('#stat-paid-transactions').text('0 Transaksi');
            $('#stat-pending-meja').text('0 Meja');
            return;
        }

        $('#stat-revenue').text(formatCurrency(respon.revenue || 0));
        $('#stat-paid-transactions').text((Number(respon.paid_transactions || 0)) + ' Transaksi');
        $('#stat-pending-meja').text((Number(respon.pending_tables || 0)) + ' Meja');
        clearAjaxLog();
    }

    function fetchData() {
        $.ajax({
            url: 'ajaxserver.php?page=syncdash',
            type: 'POST',
            data: {
                globaltoken: globaltoken,
                target: targetSync
            },
            dataType: 'json',
            success: function (respon) {
                renderDashboard(respon);
            },
            error: function (xhr, status, err) {
                const msg = xhr && xhr.responseText ? xhr.responseText : (status || err || 'Terputus dari server');
                console.error('FetchData error:', msg);
                $('#sync-indicator').attr('class', 'sync-problem').attr('title', 'Koneksi Bermasalah');
                showAjaxLog(msg);
            }
        });
    }

    function startPolling() {
        if (pollTimer) {
            clearInterval(pollTimer);
        }

        if (!document.getElementById('dashboard')) {
            return;
        }

        pollTimer = setInterval(function () {
            if (!document.getElementById('dashboard')) {
                clearInterval(pollTimer);
                pollTimer = null;
                return;
            }
            fetchData();
        }, 5000);
    }

    fetchData();
    startPolling();
});
</script>