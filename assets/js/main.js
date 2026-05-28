function route(target,container,action,loader){ 
    var globaltoken = $('#globaltoken').val(); // Ambil token dari elemen tersembunyi
    if (action === '1') {
        openLightbox();
    }
    var q={ajax:"true", globaltoken: globaltoken};
    stream(target, container, q , loader);
}

function stream(t, c, q, loader) {
    // Menampilkan pesan loading awal
    if (loader=='true') {
        $('#' + c).html('<p style="color:blue;">Memuat Data...</p>');
    }
    $.ajax({
        url: 'ajaxserver.php?page=' + t,
        type: 'POST',
        data: q,
        success: function(response) {
            // Memeriksa apakah session habis
            if (response.trim() === 'SESSION_EXPIRED') {
                alert('Session habis, silakan login lagi');
                window.location.href = 'logout.php';
                return; // Menghentikan eksekusi selanjutnya
            }
            
            // Jika sukses dan session aman, masukkan response ke elemen target
            $('#' + c).html(response);
        },
        error: function(xhr, status, error) {
            // Menampilkan pesan error jika request gagal
            $('#' + c).html('<p style="color:red;">Gagal Memuat Data</p>');
			console.error("Detail Ajax Error:", xhr.responseText);

        }
    });
}

function submitForm(formSelector, resultTarget, preform, callback) {
    $(document).off("submit", '#' + formSelector).on("submit", '#' + formSelector, function(e) {
        e.preventDefault();
        var form = this;
        console.log($(form).serialize());

        $.ajax({
            url: 'post.php',
            type: "POST",
            data: $(form).serialize(),

            success: function(res) {
                // 1. Masukkan hasil respons ke dalam target elemen
                $('#' + resultTarget).html(res);
                
                if (typeof callback === 'function') {
                    callback();
                }
                // 2. Cek apakah di dalam respons (atau di dalam resultTarget) ada class .ok-message
                // Kita gunakan $(res).find('.ok-message').length atau periksa langsung ke elemen target
                if ($('#' + resultTarget).find('.ok-message').length > 0 || $(res).hasClass('ok-message')) {
                    
                    // Jika ada class .ok-message, jalankan hitung mundur closeLightbox
                    setTimeout(function () {
                        closeLightbox(); 
                    }, 2000); // 2000 ms = 2 detik
                    
                }

                // 3. Jalankan reset form jika parameter preform adalah 'reset'
                if (preform == 'reset') {
                    form.reset();   
                }
            },

            error: function(xhr, status, error) { // Menambahkan parameter xhr agar tidak error saat di-log
               alert("Terjadi kesalahan");
               console.log(xhr.responseText);
            }
        });
    });
}

function del(parameter,name,id,target,result,act){
    route('del&id='+id+'&parameter='+parameter,'popupcontent','0','false');
    if (act=='restore'){
        alert('Data '+name+' Berhasil di restore');
    } else  {
        alert('Data '+name+' Berhasil Dihapus');
    }
    route(target, result, '0', 'false');
}
