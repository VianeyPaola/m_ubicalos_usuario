
$('#carrusel_imagenes').owlCarousel({
    loop: true,
    margin: 30,
    nav: false,
    items: 1
})

function verModal(div_number){
    $( ".app-header").css( "z-index", "8" );
    $( ".modal-content").css( "height", "100%");
    $('#carruselModal').modal();
    $('#carrusel_imagenes').trigger('to.owl.carousel', [div_number,300]);
}

function cerrarModalGV()
{
    $( ".app-header" ).css( "z-index", "10" );

    if ( $("#video-seleccionado").length ){
        document.getElementById('video-seleccionado').pause();
        $('#video-seleccionado').remove();
    }
}

function mostrarModalVideo(id_video)
{
    $( ".app-header").css( "z-index", "8" );
    $( ".modal-content").css( "height", "100%");
    $('#carruselModalV').modal();

    
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

function galeria_div()
{
    
}

function informacion_div()
{
    
}

function promociones_div()
{
}

function blogs_div(){
    
}

function eventos_div(){
}