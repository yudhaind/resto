<?php
$sql="SELECT value FROM global_settings WHERE `label`='nama_toko'";
$result = fetchOne($sql);   
?>
<div id="setting" class="page-section active">
    <h1 class="page-title">Global Setting</h1>

    <div class="grid-stats">

        <!-- Card: Toko Info -->
        <div class="card-settings">
            <div class="card-body-wrapper">
                <div class="stat-icon bg-blue-light">
                    <i class="fa-solid fa-store"></i>
                </div>

                <div class="stat-info">
                    <h3>Nama Toko</h3>
                    <p id="nama-toko-main"><?php echo $result['value']; ?></p>
                </div>

                <button class="btn-edit-setting" onclick="route('edit_global&part=namatoko','popupcontent','1','true');">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
        </div>


    </div>
</div>