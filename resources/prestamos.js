ImagenPreview('foto_desembolso','img_desembolso');
ImagenPreview('garantia1','pic1');
ImagenPreview('garantia2','pic2');
ImagenPreview('garantia3','pic3');
ImagenPreview('garantia4','pic4');
ImagenPreview('garantia5','pic5');
ImagenPreview('garantia6','pic6');

function upload_image(id_form,controller,action,input_file_image,input_photoname,carpeta,divloading) {

console.log(id_form);
console.log("controlador= "+controller);
console.log("action= "+action);
console.log("nombre file= "+input_file_image);
console.log("nombre del input foto guardada= "+input_photoname);
console.log("nombre carpeta a guardar= "+carpeta);
console.log("nombre div de carga= "+divloading);

    var formData = new FormData($('#' + id_form)[0]);
    $.ajax({
        type: 'POST',
        url: 'index.php?controller='+controller+'&action='+action+'&input_file_image='+input_file_image+'&carpeta='+carpeta+'&input_photoname='+input_photoname+'&divloading='+divloading,
        data: formData,
        contentType : false,
        processData : false,
        cache : false,
        beforeSend: function () {
           /* $('.pace').removeClass('pace-inactive');
            $('.pace').addClass('pace-active');
            */
            
            $('#'+divloading).empty();
            $('#'+divloading).append("<div class='col text-success'><strong>Subiendo foto, por favor espere ...</strong></div> <div calss='col'><i class='fa fa-spinner fa-spin' style='font-size:24px;color:Green'></i></col>");

        },
        success: function (response) {
            var obj = JSON.parse(response);
            data = obj;
            /*
            $('.pace').removeClass('pace-active');
            $('.pace').addClass('pace-inactive');             
           */

            if(obj.respuesta == "true"){
                console.log("---------------------------")
                console.log("ajax response"+obj.photoname);
                console.log(obj.input_photoname);
                console.log(obj.divloading);
                console.log("input file a limpiar"+obj.input_file_image);
                console.log(obj.size);

                    var input = $('#' + obj.input_file_image);
                    var clon = input.clone();  // Creamos un clon del elemento original
                    input.replaceWith( input.val('').clone( true ) );; 

                $('#'+obj.input_photoname).val(obj.photoname);
                $('#'+obj.divloading).empty();
                $('#'+obj.divloading).append("<i class='fa fa-check-circle' style='font-size:20px ;color:Green'>Tamano Imagen:"+ obj.size +" </i>");

                showNotify('success' ,'Correcto' ,obj.mensaje)
            }else{
                showNotify('warning' ,'Error' ,'El registro no se puedo guardar ' + obj.mensaje)
            }
        }
    });
   if(action=='crear'){
        $('#' + id_form)[0].reset();
        limpiarFotosGarantia(controller);
    }
}


//para que no se ingrese ningun caracter especial en los inputs
$(".frm-control").keypress(function (e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) return true; // 3
    if (tecla == 32) return true;
    patron = /\w/; //   patron =/[A-Za-z\s]/; patron solo letras
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
});

jQuery(function ($) {

function vercronograma(){

if(($("#plazo option:selected").text() !== "-Seleccione-")&&($("#modalidad option:selected").text() !== "-Seleccione-")) {

 var plazo = $("#plazo option:selected").text();
  
console.log(plazo);
 
if($("#modalidad").val()=="Diario")

fn_fechaven_diario(plazo);
else if($("#modalidad").val()=="Semanal")
fn_fechaven_semanal(plazo,7);
else
 fn_fechaven_semanal(plazo,14);
 
}

}


    function sumarDias(fecha, dias) {
        fecha.setDate(fecha.getDate() + dias);
        return fecha;
    }

    function obtenerMes(mimes, opcion) {
        var mesnumero = new Array();
        mesnumero['Enero'] = 0;
        mesnumero['Febrero'] = 1;
        mesnumero['Marzo'] = 2;
        mesnumero['Abril'] = 3;
        mesnumero['Mayo'] = 4;
        mesnumero['Junio'] = 5;
        mesnumero['Julio'] = 6;
        mesnumero['Agosto'] = 7;
        mesnumero['Septiembre'] = 8;
        mesnumero['Octubre'] = 9;
        mesnumero['Noviembre'] = 10;
        mesnumero['Diciembre'] = 11;
        var mesletras = [];
        mesletras[0] = "Enero";
        mesletras[1] = "Febrero";
        mesletras[2] = "Marzo";
        mesletras[3] = "Abril";
        mesletras[4] = "Mayo";
        mesletras[5] = "Junio";
        mesletras[6] = "Julio";
        mesletras[7] = "Agosto";
        mesletras[8] = "Septiembre";
        mesletras[9] = "Octubre";
        mesletras[10] = "Noviembre";
        mesletras[11] = "Diciembre";
        if (opcion == 1)
            return (mesnumero[mimes]);
        else
            return (mesletras[mimes]);
    }

    function fn_fechaven_diario(plazo) {
        var dia;
        var i;
        var salto;
        var modalidad;
        var diaferiado;
        var fecha;
        var mes;
        var cuotafija;
        salto = 1;

        var weekdays=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];

             var hoy = new Date($("#fecha_desembolso").val());
			 var primeraCuota = new Date($("#fecha_desembolso").val());

    if ($('#busqueda').val() ===  undefined){

     var cliente = $('#cliente').val();

     console.log("cliente:"+cliente);

           var id_colaborador = $('#id_colaborador').val();

           var parametros = {"id_colaborador": id_colaborador};

        }

       else{

   var cliente = $('#busqueda').val();

       var id_cliente = $('#busqueda').val();

       var cadena = id_cliente.split('-');
 
       var longitud = cadena[0].length;

       var id_cliente = cadena[0].substr(4,longitud-4);

       var parametros = {"id_cliente": id_cliente};

       }
 $("#tabla_cuotas > tbody").html("").addClass("table table-striped");     
     
        $.ajax({
            url: 'index.php?controller=prestamo&action=DiaFeriado' + '',
            type: 'POST',
            data: parametros,
            success: function (datos) {
                var diaferiado = JSON.parse(datos);
				
					//sumar un dia antes del ciclo para que comienze un dia despues
					hoy = sumarDias(hoy, salto);
                  $("#tabla_cuotas").append('<tbody>');
                for (i = 1; i <= plazo; i++) {
                    var feriado = 1;
                    hoy = sumarDias(hoy, salto);
                    dia = hoy.getDate();
                    mes = hoy.getMonth();
                    fecha = dia + "-" + mes;
                    diasemana = hoy.getDay();
                    while (diasemana == 6 || diasemana == 0 || feriado == 1) {
                        if (diasemana == 6 || diasemana == 0) {
                            hoy = sumarDias(hoy, 1);
                            dia = hoy.getDate();
                            mes = hoy.getMonth();
                            fecha = dia + "-" + mes;
                            diasemana = hoy.getDay();
                        }
                        for (var x = 0; x < diaferiado.length; x++)
                            if (fecha == diaferiado[x].dia + "-" + obtenerMes(diaferiado[x].mes, 1)) {
                                  $("#tabla_cuotas").append("<tr class='danger'><td>"+diaferiado[x].descripcion+"</td><td>"+weekdays[hoy.getDay()]+"</td><td>"+dia + "-" + obtenerMes(hoy.getMonth(), 2) + "-" + hoy.getFullYear()+"</td><td>0</td><td>"+Math.round(cuotafija*100)/100+"</td></tr>");
                                hoy = sumarDias(hoy, 1);
                                dia = hoy.getDate();
                                mes = hoy.getMonth();
                                fecha = dia + "-" + mes;
                                diasemana = hoy.getDay();
                            }
                        feriado = 0;
                    } //fin while
                     cuotafija = i * $("#cuota").val()
                    $("#tabla_cuotas").append("<tr><td>"+i+" </td><td>"+weekdays[diasemana]+"</td><td>"+dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear()+"</td><td>"+ $('#cuota').val()+"</td><td>"+ Math.round(cuotafija*100)/100+"</td></tr>");
                } //fin del for plazo
                $("#fecha_vencimiento").val(dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear());
                mes = mes + 1;
                if (mes == 13)
                    mes = 1;
                $("#fecha_de_vencimiento").val(hoy.getFullYear() + "-" + mes + "-" + dia);
                  $("#tabla_cuotas").append('</tbody>');
				  // agregar primera cuota 
				   primeraCuota = sumarDias(primeraCuota, 2);
				   
					diasemanaPC = primeraCuota.getDay();
					// por si la primera cuota cae sabado o domingo se corre hasta el lunes
					primeraCuota = ((diasemanaPC == 6) ? sumarDias(primeraCuota, 2):((diasemanaPC == 0) ? sumarDias(primeraCuota, 1):primeraCuota));
					 diaPC = primeraCuota.getDate();
					 mesPC = primeraCuota.getMonth();
					 fechaPC = diaPC + "-" + mesPC;
					  for (var x = 0; x < diaferiado.length; x++){
						console.log(fechaPC,diaferiado[x].dia + "-" + obtenerMes(diaferiado[x].mes, 1));
						if (fechaPC == diaferiado[x].dia + "-" + obtenerMes(diaferiado[x].mes, 1)) {
                                primeraCuota = sumarDias(primeraCuota, 1);
								diasemanaPC = primeraCuota.getDay();
								primeraCuota = ((diasemanaPC == 6) ? sumarDias(primeraCuota, 2):((diasemanaPC == 0) ? sumarDias(primeraCuota, 1):primeraCuota));
								diaPC = primeraCuota.getDate();
								 mesPC = primeraCuota.getMonth();
								 fechaPC = diaPC + "-" + mesPC;
                            }
					  }
				   $("#primera_cuota").val(primeraCuota.getDate() + "-" + obtenerMes(primeraCuota.getMonth(), 2) + "-" +primeraCuota.getFullYear() );
				   
				   mesPC = mesPC + 1;
                if (mesPC == 13)
                    mesPC = 1;
				
				mesPC = (mesPC <= 9) ? "0"+mesPC:mesPC;
				diaPC = (diaPC <= 9) ? "0"+diaPC:diaPC;
				$("#primera_cuota_hidden").val(primeraCuota.getFullYear() + "-" + mesPC + "-" + diaPC);
            }     //fin funcion success
        }); // fin llamada ajax
		$("#tabla_cuotas").addClass("table table-striped"); 
    }

    function fn_fechaven_semanal(plazo,saltoV) {
         var weekdays=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
        var hoy = new Date($("#fecha_desembolso").val());
		 var primeraCuota = new Date($("#fecha_desembolso").val());

        var dia;
        var i;
        var salto;
         var mes;
        salto = saltoV;
        if (hoy.getDay() == 6)
            hoy = sumarDias(hoy, -1);
        if (hoy.getDay() == 0)
            hoy = sumarDias(hoy, -2);

        if ($('#busqueda').val() ===  undefined){

             var cliente = $('#cliente').val();
    
            var id_colaborador = $('#id_colaborador').val();
            var parametros = {"id_colaborador": id_colaborador};
            
         }
        else{
        
        var cliente = $('#busqueda').val();
        var id_cliente = $('#busqueda').val();
        var cadena = id_cliente.split('-');
        var longitud = cadena[0].length;
        var id_cliente = cadena[0].substr(4,longitud-4);
        var parametros = {"id_cliente": id_cliente};

        }
		 $("#tabla_cuotas > tbody").html("");       
           $("#tabla_cuotas").append('<tbody>');
        $.ajax({
            url: 'index.php?controller=prestamo&action=DiaFeriado' + '',
            type: 'POST',
            data: parametros,
            success: function (datos) {
                var diaferiado = JSON.parse(datos);
                for (i = 1; i <= plazo; i++) {
                    var feriado = 1;
                    hoy = sumarDias(hoy, salto);
                    dia = hoy.getDate();
                    mes = hoy.getMonth();
                    fecha = dia + "-" + mes;
                    diasemana = hoy.getDay();                   
var temp  = 0;

                while (diasemana == 6 || diasemana == 0 || feriado == 1) {
                      feriado=0;
                     if (diasemana == 6 || diasemana == 0) {
                        hoy = sumarDias(hoy, 1);
                        dia=hoy.getDate();
                        mes=hoy.getMonth();

                        fecha=dia + "-" + mes;
                        diasemana = hoy.getDay();
                         temp ++;
                    }
                    else
                    for (var x = 0; x < diaferiado.length; x++)
                        if (fecha == diaferiado[x].dia + "-" + obtenerMes(diaferiado[x].mes, 1)) {
                            $("#tabla_cuotas").append("<tr class='danger'><td>"+diaferiado[x].descripcion+"</td><td>"+weekdays[hoy.getDay()]+"</td><td>"+dia + "-" + obtenerMes(hoy.getMonth(), 2) + "-" + hoy.getFullYear()+"</td><td>FERIADO</td><td>"+ Math.round(((i-1) * $("#cuota").val())*100)/100 +"</td></tr>");
                            hoy = sumarDias(hoy, 1);
                            dia = hoy.getDate();
                            mes = hoy.getMonth();
                            fecha = dia + "-" + mes;
                            diasemana = hoy.getDay();
                            feriado = 1;
                              temp ++;
                        }
                    feriado = 0;
                } //fin while

                $("#tabla_cuotas").append("<tr><td>"+i+" </td><td>"+weekdays[diasemana]+"</td><td>"+dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear()+"</td><td>"+ $('#cuota').val()+"</td><td>"+ Math.round((i * $("#cuota").val())*100)/100+"</td></tr>");
                hoy = sumarDias(hoy, -temp);
              }// fin plazo

                $("#fecha_vencimiento").val(dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear());
                mes = mes + 1;
                if (mes == 13)
                    mes = 1;
                $("#fecha_de_vencimiento").val(hoy.getFullYear() + "-" + mes + "-" + dia);
				// agregar primera cuota 
				   //primeraCuota = sumarDias(primeraCuota, 7);
				   diasemanaPC = primeraCuota.getDay();
					// por si la primera cuota cae sabado o domingo se corre hasta el lunes
					primeraCuota = ((diasemanaPC == 6) ? sumarDias(primeraCuota, 6):((diasemanaPC == 0) ? sumarDias(primeraCuota, 5):sumarDias(primeraCuota, 7)));
					 diaPC = primeraCuota.getDate();
					 mesPC = primeraCuota.getMonth();
					 fechaPC = diaPC + "-" + mesPC;
					  for (var x = 0; x < diaferiado.length; x++){
						console.log(fechaPC,diaferiado[x].dia + "-" + obtenerMes(diaferiado[x].mes, 1));
						if (fechaPC == diaferiado[x].dia + "-" + obtenerMes(diaferiado[x].mes, 1)) {
                                primeraCuota = sumarDias(primeraCuota, 1);
								diasemanaPC = primeraCuota.getDay();
								primeraCuota = ((diasemanaPC == 6) ? sumarDias(primeraCuota, 2):((diasemanaPC == 0) ? sumarDias(primeraCuota, 1):primeraCuota));
								diaPC = primeraCuota.getDate();
								 mesPC = primeraCuota.getMonth();
								 fechaPC = diaPC + "-" + mesPC;
                            }
					  }
				   $("#primera_cuota").val(primeraCuota.getDate() + "-" + obtenerMes(primeraCuota.getMonth(), 2) + "-" +primeraCuota.getFullYear() );
				   
				   mesPC = mesPC + 1;
                if (mesPC == 13)
                    mesPC = 1;
				
				mesPC = (mesPC <= 9) ? "0"+mesPC:mesPC;
				diaPC = (diaPC <= 9) ? "0"+diaPC:diaPC;
				$("#primera_cuota_hidden").val(primeraCuota.getFullYear() + "-" + mesPC + "-" + diaPC);
				   
				   
				   
				   
                $("#tabla_cuotas").append('</tbody>');
            }     //fin funcion success
        }); // fin llamada ajax
    }


    function fn_calcular_deudatotal() {                     //calcula la deuda totsl
        var interes = $("#id_interes option:selected").text();
        var capital = parseInt($("#capital").val());
//alert("funcion calcular DeudaTotal---> Capital="+capital+" Interes"+interes);
        var parametros = {"capital": capital, "interes": interes, action: 1};
        $.ajax({
            data: parametros,
            url: "index.php?controller=Prestamo&action=calcularDeudaTotal",
            type: 'post',
            success: function (deudatotal) {
                //alert("deudatotal="+deudatotal);
                $("#deuda_total").val(deudatotal);
                if ($("#plazo option:selected").text() !== "-Seleccione-" && $("#deuda_total").val().length !== 0) {
                    var plazo = $("#plazo option:selected").text();
                    plazo = parseInt(plazo);
                    fn_calcular_cuota(plazo);
                }
            }
        });
    }

    function fn_calcular_cuota(plazo) {                             //calcula la cuota
        var deudatotal = $("#deuda_total").val();
        //alert("funcion calcular cuota---> DeudaTotal="+deudatotal+" Plazo="+plazo);
        var parametros = {"deudatotal": deudatotal, "plazo": plazo, action: 0};
        $.ajax({
            data: parametros,
            url: "index.php?controller=Prestamo&action=calcularCuota",
            type: 'post',
            success: function (cuota) {
                //  alert(cuota);
                $("#cuota").val(cuota);
            }
        });
    }

    $('#id_interes').on('change', function () {                         // evento cambio del select interes
        if ($("#id_interes option:selected").text() !== "-Seleccione-") {
            if ($("#capital").val().length !== 0)
                fn_calcular_deudatotal();
        } else {
            $("#deuda_total").val("");
            $("#cuota").val(" ");
        }
    });

    $('#plazo').on('change', function () {
        // capturar el evento cambio del select plazo
        var modalidad = $("#modalidad").val();
        if ($("#plazo option:selected").text() !== "-Seleccione-") {
            if ($("#deuda_total").val().length !== 0)
                fn_calcular_cuota(parseInt($("#plazo option:selected").text()));
            if ($("#fecha_desembolso").val().length !== 0) {
                if (modalidad == "Diario")
                    fn_fechaven_diario(parseInt($("#plazo option:selected").text()));
                else if(modalidad == "Semanal")
                    fn_fechaven_semanal(parseInt($("#plazo option:selected").text()),7);
				else
				  fn_fechaven_semanal(parseInt($("#plazo option:selected").text()),14);
            }
        }
        else {
            $("#cuota").val("");
            $("#fecha_vencimiento").val("");
        }
    });

     function dias(fecha_dia) {
        var fecha=new Date(fecha_dia);
        var weekdays = new Array(7);
        weekdays[0] = "Viernes";
        weekdays[1] = "Lunes";
        weekdays[2] = "Martes";
        weekdays[3] = "Miercoles";
        weekdays[4] = "Jueves";
        weekdays[5] = "Viernes";
        weekdays[6] = "Viernes";
        console.log(fecha.getDay());
        return weekdays[fecha.getDay()];
    }

    $('#fecha_desembolso').on('change', function () {                                  // capturar el evento cambio del select plazo
        if ($("#plazo option:selected").text() !== "-Seleccione-") {
            var modalidad = $("#modalidad").val();
            var plazo = $("#plazo option:selected").text();
             if (modalidad == "Diario") {
                var fecha_dia= $('#fecha_desembolso').val();
                 $("#dia_semana").val("Diario");
				 
                fn_fechaven_diario(parseInt($("#plazo option:selected").text()));
            }
           else if(modalidad == "Semanal")
            {
                var fecha_dia= $('#fecha_desembolso').val();
                 $("#dia_semana").val(dias(fecha_dia));
                fn_fechaven_semanal(parseInt($("#plazo option:selected").text()),7);
                
            }else{
				var fecha_dia= $('#fecha_desembolso').val();
                 $("#dia_semana").val(dias(fecha_dia));
                fn_fechaven_semanal(parseInt($("#plazo option:selected").text()),14);
			}
        }
    });
    $("#capital").keyup(function () {

        //if((key.charCode < 48 || key.charCode > 57 ) && (key.charCode != 45)  && (key.charCode != 32)) return false;   jQuery("capital").keypress(function(tecla)
        if ($("#capital").val().length !== 0) {
            if ($("#id_interes option:selected").text() !== "-Seleccione-")
                fn_calcular_deudatotal();
        } else {
            $("#deuda_total").val(" ");
            $("#cuota").val(" ");
        }
    });

    $("#capital").keypress(function (e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8) return true; // 3
        patron = /\d/; //   patron =/[A-Za-z\s]/; patron solo letras
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    });
    $( '.datepicker' ).pickadate({
        format: 'dd-mmm-yyyy',
        formatSubmit: 'yyyy/mm/dd',
        // min: [2015, 7, 14],
        container: '#contenedor',
        // editable: true,
        closeOnSelect: false,
        closeOnClear: false,
    });

     var months = {1:"Jan", 2:"Feb", 3:"Mar", 4:"Apr", 5:"May", 6:"Jun", 7:"Jul", 8:"Aug", 9:"Sep", 10:"Oct", 11:"Nov", 12:"Dec"};
    $('#modalidad').on('change', function () {                                  // capturar el evento cambio del select modalidad

        var modalidad = this.value;
        if (modalidad == "Diario"){
            getOptionCombo('plazos','dataByComboPlazosDiario'  ,'plazo','id_plazo','descripcion','','');
             $("#dia_semana").val("Diario");
        }
        else if(modalidad == "Semanal"){
            getOptionCombo('plazos','dataByComboPlazosSemanales','plazo','id_plazo','descripcion','','');
            var fecha_dia= $('#fecha_desembolso').val();
            var fecha_Split = fecha_dia.split("-");
            var fecha_dia_ = fecha_Split[2]+"-"+months[parseInt(fecha_Split[1])]+"-"+fecha_Split[0];
            console.log(fecha_dia_);
            var mes =  parseInt(fecha_Split[1]);
            if (isNaN(mes))
                $("#dia_semana").val(dias(fecha_dia));
            else
                $("#dia_semana").val(dias(fecha_dia_));
        }else{
			getOptionCombo('plazos','dataByComboPlazosCatorcenales','plazo','id_plazo','descripcion','','');
            var fecha_dia= $('#fecha_desembolso').val();
            var fecha_Split = fecha_dia.split("-");
            var fecha_dia_ = fecha_Split[2]+"-"+months[parseInt(fecha_Split[1])]+"-"+fecha_Split[0];
            console.log(fecha_dia_);
            var mes =  parseInt(fecha_Split[1]);
            if (isNaN(mes))
                $("#dia_semana").val(dias(fecha_dia));
            else
                $("#dia_semana").val(dias(fecha_dia_));
		
		}
    });
$("#toggle-data-prestamo").on("click",function(){
$("#imprimir").hide();
$(".title").text("Datos del Prestamo");
});
$("#toggle-pagos").on("click",function(){
$("#imprimir").hide();
$(".title").text("Pagos del Prestamo");
});
     $("#imprimir").on("click",function() {
 var ficha = document.getElementById('cronograma');
 $(".imgicon").attr("src","./resources/imagen/logo.jpg");
 var ventimp = window.open('', 'PRINT');
 ventimp.document.write('<html><head><title>' + document.title + '</title>');
 ventimp.document.write('<link rel="stylesheet" href="assets/css/main.css">');
 ventimp.document.write('</head><body >');
 ventimp.document.write( ficha.innerHTML );
 ventimp.document.write('</body></html>');
 ventimp.document.close();
 ventimp.focus();
 ventimp.onload = function() {
   ventimp.print();
   ventimp.close();
 };
 return true;
});
 
$('#toggle-cronograma').on('click', function () {
$(".title").text("Cronograma del Prestamo");
   $(".oculto").show();
    $("#imprimir").show();
  console.log("mostrar");
   if(($("#plazo option:selected").text() !== "-Seleccione-")&&($("#modalidad option:selected").text() !== "-Seleccione-")) {
    var plazo = $("#plazo option:selected").text();
  plazo = parseInt(plazo);
  console.log(plazo);
   if($("#modalidad").val()=="Diario")
  fn_fechaven_diario(plazo);
  else if ($("#modalidad").val()=="Semanal")
	fn_fechaven_semanal(plazo,7);
  else
	fn_fechaven_semanal(plazo,14);
 
  }

});

});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
