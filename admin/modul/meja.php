 <div id="meja" class="page-section active">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h1 class="page-title" style="margin: 0;">Manajemen & Status Meja</h1>
                    <button class="btn btn-primary" id="btn-tambah-meja" onclick="route('tambah_meja', 'popupcontent', '1', 'true')"><i class="fa-solid fa-plus"></i> Tambah Meja</button>
                </div>

                <!-- Container Denah Layout Meja -->
                <div class="grid-meja" id="box-denah-meja">
                    
                    <!-- Meja 1 -->
                    <div class="card-meja status-terisi" data-nomor="1">
                        <div class="meja-actions">
                            <button class="btn-action-meja edit" onclick="route('edit_meja', 'popupcontent', '1', 'true')"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn-action-meja delete btn-hapus-meja"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div class="meja-icon"><i class="fa-solid fa-couch meja-icon-color"></i></div>
                        <div class="meja-name">Meja 01</div>
                        <span class="status-indicator status-terisi">Terisi (Makan)</span>
                        <button class="btn btn-outline btn-sm btn-ubah-status">Kosongkan Meja</button>
                    </div>

                    <!-- Meja 2 -->
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

                    <!-- Meja 3 -->
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

                    <!-- Meja 4 -->
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

                </div>
            </div>
