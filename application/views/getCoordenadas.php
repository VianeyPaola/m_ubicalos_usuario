<!Doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="theme-color" content="#eb4646" />
    <link rel="icon" href="<?php echo base_url();?>img/LOGOWEBSFW.png" type="image/x-icon" />
    <title>Ubícalos</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">


    <link href="<?php echo base_url();?>css/main.css" rel="stylesheet">

</head>
<body>

	<form id="geolocalizacion" method="POST" action="<?php echo base_url(); ?>Welcome/Inicio">
		<input type="hidden" id="latUser" name="latUser">
		<input type="hidden" id="longUser" name="longUser">
	</form>

	<script src="<?php echo base_url();?>js/jquery.min.js"></script>
	<script src="<?php echo base_url();?>js/popper.min.js"></script>
</body>

<script>

    $(document).ready(function(){

			var latUser = 19.0438393;
			var longUser = -98.2004204;

			getLocation();

			function getLocation() {
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(showPosition);
				} else {
					x.innerHTML = "Geolocation is not supported by this browser.";
				}
			}

			function showPosition(position) {
				latUser = position.coords.latitude;
				longUser = position.coords.longitude;
				$("#latUser").val(latUser);
				$("#longUser").val(longUser);
				$("#geolocalizacion").submit();
			}

			$("#latUser").val(latUser);
			$("#longUser").val(longUser);

			$("#geolocalizacion").submit();


		});
		

</script>

</html>
