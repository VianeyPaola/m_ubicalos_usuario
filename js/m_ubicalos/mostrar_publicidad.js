
function MostrarPublicidadPantalla(id_publicidad)
{
	$( ".app-header").css( "z-index", "8" );
    $( ".modal-content").css( "height", "100%");
	$('#ModalPublicidadP').modal();

	$.ajax({
		type: 'POST',
		url: 'getPublicidadPrincipalPantalla',
		data: {'id_tarjeta':id_publicidad}
	})
	.done(function(body_publicidad){
		$('#body-publicidad').html(body_publicidad)
	})
	.fail(function(){
		location.reload();
	})
}

function CerrarPublicidadPantalla()
{
	$( ".app-header" ).css( "z-index", "10" );
	if($('#video-pantalla-c-p').length)
	{
		document.getElementById('video-pantalla-c-p').pause();
        $('#video-pantalla-c-p').remove();
	}
}
