    $('#carrusel_imagenes').owlCarousel({
        loop: true,
        padding: 0,
        margin: 30,
        nav: false,
        items: 1
    })


    function mostrarModal(div_number, total_div)
    {
        $( ".app-header").css( "z-index", "8" );
        $( ".modal-content").css( "height", "100%");
        $('#carruselModal').modal();
        $('#carrusel_imagenes').trigger('to.owl.carousel', [div_number,300]);
    }

    function mostrarModalVideo(id_video)
    {
        $( ".app-header").css( "z-index", "8" );
        $( ".modal-content").css( "height", "100%");
        $('#carruselModal').modal();

        
        $.ajax({
            type: 'POST',
            url: 'get_Video',
            data: {'id_video': id_video }
        })
        .done(function(video){
            $('#div_video').html(video);
        })
        .fail(function(){
            mostrarModalVideo(id_video);
        })
    

    }

    function cerrarModalGV()
    {
        $( ".app-header" ).css( "z-index", "10" );

        if ( $("#video-seleccionado").length ){
            document.getElementById('video-seleccionado').pause();
            $('#video-seleccionado').remove();
        }

    }

    function cerrarModalGE()
    {
        $( ".app-header" ).css( "z-index", "10" );
        document.getElementById('video-seleccionado').pause();
    }

    function mostrarModalVE()
    {
        $( ".app-header").css( "z-index", "8" );
        $( ".modal-content").css( "height", "100%");
        $('#carruselModal1').modal();
    }
