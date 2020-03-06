$(document).ready(function(){
    $.getScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyCXN8SbAr3U1mjYvjnCCujUJi8jb0gB00o&libraries=places&callback=initMap');
    /* */
});

/*Obtine la coordenada actual */
function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
      x.innerHTML = "Geolocation is not supported by this browser.";
    }  
}


/*Información del negocio */

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 19.0438393, lng:  -98.2004204 },
        zoom: 11,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    setMarkers(map, sucursales,icono)
}

function showPosition(position) {
    var markerPosition = {};
    markerPosition.lat = position.coords.latitude;
    markerPosition.lng = position.coords.longitude;

    // Create a marker and place it on the map
    var marker = new google.maps.Marker({
        position: markerPosition,
        map: map,
        animation: google.maps.Animation.BOUNCE,
        title: "Aquí te encuentras.",
        //icon: "http://www.clker.com/cliparts/g/R/z/I/u/o/map-pin-hi.png"
    });
}

function setMarkers(map, sucursales,icono) {
    var marker, i
    console.log("sucursales", sucursales);
    console.log("icono", icono);
    for (i = 0; i < sucursales.length; i++) {
        var lat = sucursales[i][0]
        var long  = sucursales[i][1]
        var add= sucursales[i][2]
        var nombre = nombre_negocio

        latlngset = new google.maps.LatLng(lat, long);
        console.log("lat: ",lat, "long: ",long);
        var marker = new google.maps.Marker({
            map: map, title: nombre, position: latlngset
        });
        map.setCenter(marker.getPosition())


        var content = 
                '<div class="row">' +
                    '<div class="col-12">' +
                        '<div class="card text-center" style="max-width: 16rem;">' +
                            '<img class="card-img-top mt-2" src="'+foto+'" alt="Card image cap" style="height:90px;width:90px; border-radius:50%;margin-right: auto; margin-left: auto;">'+
                            '<div class ="card-body">' +                                     
                                '<div class="row">'+
                                    '<div class="col-12">'+
                                        '<div class="estrellas">'+
                                            '<form>'+
                                                '<p class="clasificacion mb-0">'+
                                                    '<input id="radio1" type="radio" name="estrellas" value="5"><label for="radio1">★</label>'+
                                                    '<input id="radio2" type="radio" name="estrellas" value="4"><label for="radio2">★</label>'+
                                                    '<input id="radio3" type="radio" name="estrellas" value="3">'+
                                                    '<label for="radio3">★</label>'+
                                                    '<input id="radio4" type="radio" name="estrellas" value="2">'+
                                                    '<label for="radio4">★</label>'+
                                                    '<input id="radio5" type="radio" name="estrellas" value="1">'+
                                                    '<label for="radio5">★</label>'+
                                                '</p>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="w-100"></div>'+
                                    '<div class="col-12">'+
                                        '<p class="card-text text-center color-black" ><b>'+ nombre_negocio + '</b></p>'+
                                    '</div>'+
                                    '<div class="w-100"></div>'+
                                    '<div class="col-12">'+
                                        '<p class="card-text text-center">'+ add + '</p>'+
                                    '</div>'+
                                    '<div class="w-100"></div>'+
                                    '<div class="col-12">'+
                                        '<a target="_blank" href="https://www.google.com/maps/search/?api=1&query='+lat+','+long +'" >¿Cómo llegar?'+'</a>'+
                                    '</div>'+3
                                '</div>' +
                            '</div>'+
                        '</div>' +
                    '</div>'+
                '</div>'

        var infowindow = new google.maps.InfoWindow()
        google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
            return function () {
                infowindow.setContent(content);
                infowindow.open(map, marker);
            };
        })(marker, content, infowindow));
    }
}
