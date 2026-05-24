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

function submitForm(formSelector,resultTarget,preform){
    $(document).off("submit", '#' + formSelector).on("submit", '#' + formSelector, function(e){
        e.preventDefault();
        var form = this;
        console.log($(form).serialize());

        $.ajax({
            url: 'post.php',
            type: "POST",
            data: $(form).serialize(),

            success:function(res){
                $('#' + resultTarget).html(res);
                if (preform=='reset'){
                    form.reset();	
                }
            },

            error:function(){
               alert("Terjadi kesalahan");
            }
        });
    });
}


