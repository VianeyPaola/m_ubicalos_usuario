$(document).ready(function() {
    
    $(".upload").on('click', function() {
        var formData = new FormData();
        var files = $('#image')[0].files[0];
        formData.append('file',files);
        $.ajax({
            url: 'subir_foto_perfil',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response != 0) {
                    location.reload();//$(".img-perfil").attr("src", response);
                } else {
                    alert('Formato de imagen incorrecto.');
                }
            }
        });

        return false;
    });

    $(function() {
        $('#image').change(function(e) {
            addImage(e); 
           });
      
           function addImage(e){
            var file = e.target.files[0],
            imageType = /image.*/;
          
            if (!file.type.match(imageType))
             return;
        
            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);
           }
        
           function fileOnload(e) {
            var result=e.target.result;
            $('#imagenprincipal-modal').attr("src",result);
           }
          });
});