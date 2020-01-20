
    </div>
</div>

<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
</div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>js/main.js"></script>

<script src="<?php echo base_url();?>js/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/popper.min.js"></script>
<script src="<?php echo base_url();?>js/owl.carousel.js"></script>

<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/bootstrap4-toggle.min.js"></script>
<link href="<?php echo base_url();?>css/bootstrap4-toggle.min.css" rel="stylesheet">
</body>

<script>

    $(document).ready(function(){

		if(screen.width >= 1024){location.href="http://192.168.1.69/ubicalos/Welcome/Sesion"; }
		window.addEventListener("orientationchange", function() { if(screen.width >= 1024){location.href="http://192.168.1.69/ubicalos/Welcome/Sesion"; } }, false);

        var band = true;
        document.getElementById("logotipo").style.display = "block";
        $('#barritas').click(function () {
            if (band != true) {
                document.getElementById("logotipo").style.display = "none";
            } else {
                document.getElementById("logotipo").style.display = "block";
            }
            band = !band;

        });

        $('.logo-src').show();

		$('.owl-theme').owlCarousel({
			margin: 10,
			nav: false,
			dots: false,
			autoWidth: false,
			items: 1,
			loop:false,
			responsiveClass:true,
			responsive:{
				600:{
					items: 2
				}
			}
		})

		$('.owl-carousel').owlCarousel({
			margin: 10,
			nav: false,
			dots: false,
			autoWidth: false,
			items: 1,
			loop:true,
			autoplay:true,
			autoplayTimeout: 3000,
		})


        /* funciones dinamicas para informacion principal */
       
        /* fin */

		/* Cargamos las fotos de todas las cards con sus sucursales 
		$(".card-img").one("load", function() {
		}).each(function() {
			var id_sucursal = this.id;
			//$('#suc_'+id_sucursal).hide();
			
			$.ajax({
				type: 'POST',
				url: 'getFotoSucursal',
				data: {'id_sucursal': id_sucursal }
			})
			.done(function(FotoSucursal){
				$('#suc_'+id_sucursal).html(FotoSucursal)
			})
			.fail(function(){
				//location.reload();
			})
			
		});
		*/
		

    });
    
	
	function btnBusca()
	{
		$('#logo-principal-ubicalos').hide();
		$('#lupa-ubicalos-search').hide();
		$('#input-ubicalos-search').show();
	}


</script>

</html>
