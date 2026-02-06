jQuery(function($){

iniciarDatos();

    let cola = 0;

    $("#saveCuota").on("click", function () {
        if (++cola > 1) {
            alertify.alert('Wolf Financiero S.A. Dice:', "Espere por favor, se está ejecutando una transacción");
            return;
        }

        const montoVal = $("#monto").val().trim();
        const refVal = $("#nreferenciaBanca").val().trim();
        const depositoVal = $("#Deposito").val(); // nuevo
        let valid = true;

        // Reiniciar bordes
        $("#monto, #nreferenciaBanca").css("border", "");

        if (montoVal === "") {
            $("#monto").css("border", "1px solid red");
            alertify.alert("Campo obligatorio", "Debe ingresar el monto.");
            valid = false;
        }

        // Validar referencia SOLO si el depósito es diferente de "seleccione"
        if (depositoVal !== "0" && refVal === "") {
            $("#nreferenciaBanca").css("border", "1px solid red");
            alertify.alert("Campo obligatorio", "Debe ingresar el número de referencia.");
            valid = false;
        }

        if (!valid) {
            cola = 0;
            return;
        }

        const saldoText = $("#psaldo_pendiente").val().trim();
        const saldoParts = saldoText.split("C$");
        const saldo = parseFloat(saldoParts[1]);

        if (isNaN(saldo)) {
            showNotify('error', 'Error', 'El saldo pendiente no es válido.');
            cola = 0;
            return;
        }

        const monto = parseFloat(montoVal);
        if (monto > saldo) {
            showNotify('warning', 'Error', 'El abono actual supera la deuda total del cliente.');
            $("#monto").val("");
            cola = 0;
            return;
        }

        const $this = $(this);
        CallAjax($this); // Asegúrate de que esta función reinicie `cola` después
    });


    function CallAjax($this){
    $this.button('loading');
	$.ajax({
        type: 'POST',
        url: 'index.php?controller=Pagos&action=crear',
        data: $("#abonar_form").serialize(),
        beforeSend: function () {
            $('.pace').removeClass('pace-inactive');
            $('.pace').addClass('pace-active');
            $('#cover-spin').show();
            $("#saveCuota").hide();

        },
        success: function (response) {
			$('.pace').removeClass('pace-active');
            $('.pace').addClass('pace-inactive');
        	console.log(response);
            var obj = JSON.parse(response);
            console.log(obj);
            data = obj;
            if(obj.respuesta == "true"){
                showNotify('success' ,'Correcto' ,'Cuota aplicada correctamente al prestamo. Espere 10 seg para volver aplicar otra');
				 ActualizarValores(obj.data);
				cola=0;
            }else{
                showNotify('warning' ,'Error' ,'La cuota no se pude aplicar al prestamo Espere 10 seg para volver aplicar otra :'+obj.mensaje);
				cola=0;
            }
            $this.button('reset');
            $('#cover-spin').hide();
            setTimeout(function() {
                $("#saveCuota").show();
            }, 10000);
        }
    });
}
function ActualizarValores(data){
var data_ =JSON.parse(data);
var cuotaBase = new String();
cuotaBase=$("#ccuota").val();
var cbase = cuotaBase.split("C$");
var ncuotas_atrasadas = parseFloat(data_.mora)/parseFloat(cbase[1]);
var ndvencidos = parseFloat($("#ndvencidos").val());

$("#monto").val("");
$("#mpagadoCl").val(data_.acumulado);
$("#cmora").val("C$ "+data_.mora);
$("#ncuotas_atrasadas").val(Math.round(ncuotas_atrasadas*1000)/1000);
$("#cmfavor").val("C$ "+data_.saldo_favor);
$("#psaldo_pendiente").val("C$ "+data_.saldo_pendiente);
$("#ccuotafaltantes").val(Math.round(parseFloat(data_.saldo_pendiente)/parseFloat(cbase[1])));
}
function showNotify(type ,label_type, Menssege) {
    $.notify({
        title: '<strong>'+label_type+'!</strong>',
        message: Menssege
    },{
        type: type,
        animate: {
            enter: 'animated bounceInDown',
            exit: 'animated bounceOutUp'
        }
    });
}
function showNotify(type ,label_type, Menssege) {
    $.notify({
        title: '<strong>'+label_type+'!</strong>',
        message: Menssege
    },{
        type: type,
        animate: {
            enter: 'animated bounceInDown',
            exit: 'animated bounceOutUp'
        }
    });
}
function iniciarDatos(){
	$('#monto').mask('999999.99');
	var f = new Date();
	var dia = (f.getDate() > 9) ? f.getDate():'0'+f.getDate();
	var mes = ((f.getMonth() + 1) > 9) ? f.getMonth()+1:'0'+String(f.getMonth()+1);
	var fecha = f.getFullYear()+'-'+mes+'-'+dia;
	$("#pfecha").val(fecha);//f.toISOString().substr(0, 10)
	$("#fechapago").val(fecha);
}
$("input[type=radio]").on("click",function(){
if($(this).val() == "1"){
$(".comenreprestable").show();
}else{
$(".comenreprestable").hide();
}
});

});

/*
var map = new ol.Map({
    target: 'map',
    layers: [
        new ol.layer.Tile({
            source: new ol.source.OSM()
        })
    ],
    view: new ol.View({
        center: ol.proj.fromLonLat([-86.879139,12.434169]),
        zoom: 2
    })
});
*/
$(document).ready(function () {
    var lat = 0.0;
    var lon = 0.0;

    //Si el navegador soporta geolocalizacion
    if (!!navigator.geolocation) {
        $('#cover-spin').show();

        //Pedimos los datos de geolocalizacion al navegador
        navigator.geolocation.getCurrentPosition(
            //Si el navegador entrega los datos de geolocalizacion los imprimimos
            function (position) {
                window.alert("Navegador Autorizado para geolocalizacion");
                lat = position.coords.latitude;
                lon = position.coords.longitude;
                loadMap(lat,lon);
                $("#coordenadas").val(lon +","+ lat);
                console.log(lat, lon);
                $('#cover-spin').hide();

            },
            //Si no los entrega manda un alerta de error+
            function () {

                window.alert("debe autorizar el navegador para Obtener su ubicacion actual");
                window.location.href = "https://wolfinanciero.tech/index.php?controller=logout&action=salir";

            }
        );
    }
    console.log(lat, lon);
function loadMap(lat, lon) {
    var map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: new ol.View({
            center: ol.proj.fromLonLat([lon,lat]),
            zoom: 17
        })
    });

    var mousePositionControl = new ol.control.MousePosition({
        coordinateFormat: ol.coordinate.createStringXY(6),
        projection: 'EPSG:4326',
        className: 'custom-mouse-position',
        target: document.getElementById('coordinates'),
        undefinedHTML: '&nbsp;'
    });
    map.addControl(mousePositionControl);

    var vectorSource = new ol.source.Vector();

    var vectorLayer = new ol.layer.Vector({
        source: vectorSource
    });

    map.addLayer(vectorLayer);

    var marker = new ol.Feature();
    var markerLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
            features: [marker]
        }),
        style: new ol.style.Style({
            image: new ol.style.Icon({
                anchor: [0.5, 1],
                src: 'https://openlayers.org/en/latest/examples/data/icon.png'
            })
        })
    });

    map.addLayer(markerLayer);

    map.on('click', function(event) {
        var coordinate = event.coordinate;

        marker.setGeometry(new ol.geom.Point(coordinate));
    });

    map.on('click', function(evt) {
        var coord = ol.proj.toLonLat(evt.coordinate);
        alert(coord);
        //$("#coordenadas").val(lon +","+ lat);
    });

    var marker = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat([lon,lat]))
    });
    marker.setStyle(
        new ol.style.Style({
            image: new ol.style.Icon({
                anchor: [0.5, 1],
                src: 'https://openlayers.org/en/latest/examples/data/icon.png'
            })
        })
    );

    vectorSource.addFeature(marker);

}

});
