$(document).ready(function(){
	obtenerAutoComplete();
});

function obtenerAutoComplete()
{
	$.ajax({
		type: 'POST',
		url: 'autocompletado_buscador'
	})
	.done(function(resultados){
		$('#resultados').html(resultados)
	}).fail(function(){
		obtenerAutoComplete()
	})
}
