ImagenPreview('foto_desembolso','img_desembolso');
ImagenPreview('garantia1','pic1');
ImagenPreview('garantia2','pic2');
ImagenPreview('garantia3','pic3');
ImagenPreview('garantia4','pic4');
ImagenPreview('garantia5','pic5');
ImagenPreview('garantia6','pic6');

ImagenPreview('prenda0','pren0');

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
            if(obj.respuesta == "true"){
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
if($("#modalidad").val()=="Diario"){
fn_fechaven_diario(plazo);
}else if($("#modalidad").val()=="Semanal"){
    fn_fechaven_semanal(plazo,7);
 }
 else if($("#modalidad").val()=="Quincenal"){
    fn_fechaven_Quincenal(plazo,15);
 }else {
    fn_fechaven_Mensual(plazo,30);
 }
 
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
             hoy = sumarDias(hoy, +1);

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
                    $("#tabla_cuotas").append("<tr><td>"+i+" </td><td>"+weekdays[diasemana]+"</td><td>"+dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear()+"</td><td>"+ $('#cuota').val()+"</td><td>"+ Math.round(cuotafija*100)/100+"</td><td>"+Math.round((parseFloat($("#deuda_total").val())- parseFloat(Math.round((i * $("#cuota").val())*100)/100)))+"</td></tr>");
                } //fin del for plazo
                $("#fecha_vencimiento").val(dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear());
                mes = mes + 1;
                if (mes == 13)
                    mes = 1;
                $("#fecha_de_vencimiento").val(hoy.getFullYear() + "-" + mes + "-" + dia);
                  $("#tabla_cuotas").append('</tbody>');
            }     //fin funcion success
        }); // fin llamada ajax
		$("#tabla_cuotas").addClass("table table-striped"); 
    }

    function fn_fechaven_semanal(plazo,salto) {
        var weekdays=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
        var hoy = new Date($("#fecha_desembolso").val());
        hoy = sumarDias(hoy, +1);


        var dia;
        var i;
        var salto;
         var mes;
        //salto = 7;
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
                    console.log(hoy);
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

                $("#tabla_cuotas").append("<tr><td>"+i+" </td><td>"+weekdays[diasemana]+"</td><td>"+dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear()+"</td><td>"+ $('#cuota').val()+"</td><td>"+ Math.round((i * $("#cuota").val())*100)/100+"</td><td>"+Math.round((parseFloat($("#deuda_total").val())- parseFloat(Math.round((i * $("#cuota").val())*100)/100)))+"</td></tr>");
                hoy = sumarDias(hoy, -temp);
              }// fin plazo

                $("#fecha_vencimiento").val(dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear());
                mes = mes + 1;
                if (mes == 13)
                    mes = 1;
                $("#fecha_de_vencimiento").val(hoy.getFullYear() + "-" + mes + "-" + dia);
                $("#tabla_cuotas").append('</tbody>');
            }     //fin funcion success
        }); // fin llamada ajax
    }

function fn_fechaven_Quincenal(plazo,saltoV) {
         var weekdays=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
         var RegExPattern = /^\d{2,4}\-\d{1,2}\-\d{1,2}$/;
		 //var  fec = $("#primera_cuota").val();
    	 var fecha = $('#fecha_desembolso').val();
         if ((fecha.match(RegExPattern))) {
                fecha =$("#fecha_desembolso").val()+"T00:00:00";
            } else {
                fecha;
            }
        var hoy = new Date(fecha);
         var parafechaVen;
		 console.log("Hoy",hoy);
        var dia;
        var i;
        var salto;
         var mes;
        salto = saltoV;
        /*if (hoy.getDay() == 6)
            hoy = sumarDias(hoy, -1);
        if (hoy.getDay() == 0)
            hoy = sumarDias(hoy, -2);*/
        hoy = sumarDias(hoy, salto);
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
                   
                    dia = hoy.getDate();
                    mes = hoy.getMonth();
                    fecha = dia + "-" + mes;
                    diasemana = hoy.getDay();                   
                    var temp  = 0,contador =0;
                    var diasMes = new Date(hoy.getFullYear(), mes +1 , 0).getDate();
					if(weekdays[diasemana] === undefined){
						//var  fec = $("#primeraCuotaQM").val();
						// var fecha = fec.split("-");
						hoy = new Date($("#primeraCuotaQM").val());
						dia = hoy.getDate();
						mes = hoy.getMonth();
						fecha = dia + "-" + mes;
						diasemana = hoy.getDay(); 
					}
					if (diasemana == 6) {
                        hoy = sumarDias(hoy, 2);
                        dia=hoy.getDate();
                        mes=hoy.getMonth();

                        fecha=dia + "-" + mes;
                        diasemana = hoy.getDay();
                         temp=-2;
                    }else if(diasemana == 0){
                        hoy = sumarDias(hoy, 1);
                        dia=hoy.getDate();
                        mes=hoy.getMonth();

                        fecha=dia + "-" + mes;
                        diasemana = hoy.getDay();
                         temp=-1;
                    }
					
                 $("#tabla_cuotas").append("<tr><td>"+i+" </td><td>"+weekdays[diasemana]+"</td><td>"+dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear()+"</td><td>"+ $('#cuota').val()+"</td><td>"+ Math.round((i * $("#cuota").val())*100)/100+"</td></tr>");
                
                
                 console.log("Dia De fecha(",dia,") Dia del Mes (",diasMes,") Salto (",salto,") Temp (",temp,") Dia Semana (",diasemana,")");
                 if(temp < 0){
                    hoy = sumarDias(hoy,temp);
                    dia=hoy.getDate();
                        mes=hoy.getMonth();
                 }
                 
                  /*if(dia >= 16){
                        if(diasMes == 31){
                        salto=16;
                        }else if(diasMes == 28){
                        salto=13;
                        }
                        else if(diasMes == 29){
                        salto=14;
                        }
                        else{
                        salto=15;
                        }
                }*/
                  console.log(hoy);
                  hoy = sumarDias(hoy, salto);
                  console.log("Despues de sumar salto",hoy,"Salto (",salto,")");
                /* while (diasemana == 6 || diasemana == 0 || feriado == 1) {
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
                } //fin while*/
                contador = salto + contador;
                salto=15;
                temp=0;
                //hoy = sumarDias(hoy,temp);
              }// fin plazo
			 // hoy = sumarDias(hoy, salto);
				var totalDias = (plazo - 1) * contador;
        				var fecha = $('#primera_cuota').val();
                 if ((fecha.match(RegExPattern))) {
                        fecha =$("#primera_cuota").val()+"T00:00:00";
                    } else {
                        fecha;
                    }
                 parafechaVen = new Date(fecha);
				console.log("TotalDias",totalDias,parafechaVen);
				parafechaVen = sumarDias(parafechaVen, totalDias);
				 //hoy = sumarDias(hoy, 1);
				dia = parafechaVen.getDate();
				mes = parafechaVen.getMonth();
				
                $("#fecha_vencimiento").val(dia + "-" + obtenerMes(mes, 2) + "-" + parafechaVen.getFullYear());
                mes = mes + 1;
                if (mes == 13)
                    mes = 1;
                $("#fecha_de_vencimiento").val(parafechaVen.getFullYear() + "-" + mes + "-" + dia);
                $("#tabla_cuotas").append('</tbody>');
            }     //fin funcion success
        }); // fin llamada ajax
    }
    function fn_fechaven_Mensual(plazo,saltoV) {
         var weekdays=["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
         var RegExPattern = /^\d{2,4}\-\d{1,2}\-\d{1,2}$/;
         //var  fec = $("#primera_cuota").val();
         var fecha = $('#fecha_desembolso').val();
         if ((fecha.match(RegExPattern))) {
                fecha =$("#fecha_desembolso").val()+"T00:00:00";
            } else {
                fecha;
            }
        var hoy = new Date(fecha);
         var parafechaVen;
         console.log("Hoy",hoy);
        var dia;
        var i;
        var salto;
         var mes;
        salto = saltoV;
        /*if (hoy.getDay() == 6)
            hoy = sumarDias(hoy, -1);
        if (hoy.getDay() == 0)
            hoy = sumarDias(hoy, -2);*/
        hoy = sumarDias(hoy,salto);
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
                   
                    dia = hoy.getDate();
                    mes = hoy.getMonth();
                    fecha = dia + "-" + mes;
                    diasemana = hoy.getDay();                   
                    var temp  = 0,contador =0;
                    var diasMes = new Date(hoy.getFullYear(), mes +1 , 0).getDate();
                    if(weekdays[diasemana] === undefined){
                        //var  fec = $("#primeraCuotaQM").val();
                        // var fecha = fec.split("-");
                        hoy = new Date($("#primeraCuotaQM").val());
                        dia = hoy.getDate();
                        mes = hoy.getMonth();
                        fecha = dia + "-" + mes;
                        diasemana = hoy.getDay(); 
                    }
                    if (diasemana == 6) {
                        hoy = sumarDias(hoy, 2);
                        dia=hoy.getDate();
                        mes=hoy.getMonth();

                        fecha=dia + "-" + mes;
                        diasemana = hoy.getDay();
                         temp=-2;
                    }else if(diasemana == 0){
                        hoy = sumarDias(hoy, 1);
                        dia=hoy.getDate();
                        mes=hoy.getMonth();

                        fecha=dia + "-" + mes;
                        diasemana = hoy.getDay();
                         temp=-1;
                    }
                    
                 $("#tabla_cuotas").append("<tr><td>"+i+" </td><td>"+weekdays[diasemana]+"</td><td>"+dia + "-" + obtenerMes(mes, 2) + "-" + hoy.getFullYear()+"</td><td>"+ $('#cuota').val()+"</td><td>"+ Math.round((i * $("#cuota").val())*100)/100+"</td></tr>");
                
                
                 console.log("Dia De fecha(",dia,") Dia del Mes (",diasMes,") Salto (",salto,") Temp (",temp,") Dia Semana (",diasemana,")");
                 if(temp < 0){
                    hoy = sumarDias(hoy,temp);
                    dia=hoy.getDate();
                        mes=hoy.getMonth();
                 }
                 
                /*  if(dia >= 16){
                        if(diasMes == 31){
                        salto=16;
                        }else if(diasMes == 28){
                        salto=13;
                        }
                        else if(diasMes == 29){
                        salto=14;
                        }
                        else{
                        salto=15;
                        }
                }*/
                  console.log(hoy);
                  hoy = sumarDias(hoy, salto);
                  console.log("Despues de sumar salto",hoy,"Salto (",salto,")");
                /* while (diasemana == 6 || diasemana == 0 || feriado == 1) {
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
                } //fin while*/
                contador = salto + contador;
               // salto=15;
                temp=0;
                //hoy = sumarDias(hoy,temp);
              }// fin plazo
             // hoy = sumarDias(hoy, salto);
                var totalDias = (plazo) * contador;
                        var fecha = $('#fecha_desembolso').val();
                 if ((fecha.match(RegExPattern))) {
                        fecha =$("#fecha_desembolso").val()+"T00:00:00";
                    } else {
                        fecha;
                    }
                 parafechaVen = new Date(fecha);
                console.log("TotalDias",totalDias,parafechaVen);
                parafechaVen = sumarDias(parafechaVen, totalDias);
                 //hoy = sumarDias(hoy, 1);
                  diasemana = parafechaVen.getDay();  
                 if (diasemana == 6) {
                        parafechaVen = sumarDias(parafechaVen, 2);
                        dia=parafechaVen.getDate();
                        mes=parafechaVen.getMonth();

                        fecha=dia + "-" + mes;
                        diasemana = parafechaVen.getDay();
                         temp=-2;
                    }else if(diasemana == 0){
                        parafechaVen = sumarDias(parafechaVen, 1);
                        dia=parafechaVen.getDate();
                        mes=parafechaVen.getMonth();

                        fecha=dia + "-" + mes;
                        diasemana = parafechaVen.getDay();
                         temp=-1;
                    }
                dia = parafechaVen.getDate();
                mes = parafechaVen.getMonth();
                
                $("#fecha_vencimiento").val(dia + "-" + obtenerMes(mes, 2) + "-" + parafechaVen.getFullYear());
                mes = mes + 1;
                if (mes == 13)
                    mes = 1;
                $("#fecha_de_vencimiento").val(parafechaVen.getFullYear() + "-" + mes + "-" + dia);
                $("#tabla_cuotas").append('</tbody>');
            }     //fin funcion success
        }); // fin llamada ajax
    }
    function fn_calcular_deudatotal() {                     //calcula la deuda totsl
        var interes = $("#total_interes").val();
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
	function fn_calcular_interes() {
        plazo = parseInt($("#plazo option:selected").text());
        interes = parseFloat($("#id_interes option:selected").text());
        var modalidad = $("#modalidad").val();
        if (modalidad == "Semanal")
            $("#total_interes").val(plazo / 4 * interes);
        else if (modalidad == "Quincenal")
            $("#total_interes").val(plazo / 2 * interes);
        else if (modalidad == "Diario")
            $("#total_interes").val(plazo / 20 * interes);
        else
            $("#total_interes").val(plazo * interes);
        if ($("#capital").val().length !== 0)
            fn_calcular_deudatotal();
    }
    $('#id_interes').on('change', function () {                         // evento cambio del select interes
         if (($("#id_interes option:selected").text() !== "-Seleccione-" ) && ($("#plazo option:selected").text() !== "-Seleccione-")) {
            fn_calcular_interes();
        } else {
            $("#deuda_total").val("");
            $("#cuota").val(" ");
			$("#total_interes").val(" ");
        }
    });

    $('#plazo').on('change', function () {
		 fn_calcular_interes();
        // capturar el evento cambio del select plazo
        var modalidad = $("#modalidad").val();
        if ($("#plazo option:selected").text() !== "-Seleccione-") {
            if ($("#deuda_total").val().length !== 0)
                fn_calcular_cuota(parseInt($("#plazo option:selected").text()));
            if ($("#fecha_desembolso").val().length !== 0) {
                if (modalidad == "Diario"){
                    fn_fechaven_diario(parseInt($("#plazo option:selected").text()));
                }else if (modalidad == "Semanal") {
                    fn_fechaven_semanal(parseInt($("#plazo option:selected").text()),7);
                }else if (modalidad == "Quincenal") {
                        fn_fechaven_Quincenal(parseInt($("#plazo option:selected").text()),15);
                 }else{
                      fn_fechaven_Mensual(parseInt($("#plazo option:selected").text()),30);
                 }
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
        weekdays[0] = "Domingo";
        weekdays[1] = "Lunes";
        weekdays[2] = "Martes";
        weekdays[3] = "Miercoles";
        weekdays[4] = "Jueves";
        weekdays[5] = "Viernes";
        weekdays[6] = "Viernes";
        console.log(fecha.getDay());
        return weekdays[fecha.getDay()];
    }
function calcular_primeraCuota(plazo="",salto){
     var RegExPattern = /^\d{2,4}\-\d{1,2}\-\d{1,2}$/;
     var mon = {0:"Jan", 1:"Feb", 2:"Mar", 3:"Apr", 4:"May", 5:"Jun", 6:"Jul", 7:"Aug", 8:"Sep", 9:"Oct", 10:"Nov", 11:"Dec"};
     var fecha = $('#fecha_desembolso').val();
     if ((fecha.match(RegExPattern))) {
            fecha =$("#fecha_desembolso").val()+"T00:00:00";
        } else {
            fecha;
        }
    var hoy = new Date(fecha);
    
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
     
        $.ajax({
            url: 'index.php?controller=prestamo&action=DiaFeriado' + '',
            type: 'POST',
            data: parametros,
            success: function (datos) {
                var diaferiado = JSON.parse(datos);

                   hoy = sumarDias(hoy, salto);
                    dia = hoy.getDate();
                    mes = hoy.getMonth();
                    fecha = dia + "-" + mes;
                    diasemana = hoy.getDay();
                   // while (diasemana == 6 || diasemana == 0 || feriado == 1) {
                        /*if (diasemana == 6 || diasemana == 0) {
                            hoy = sumarDias(hoy, 1);
                            dia = hoy.getDate();
                            mes = hoy.getMonth();
                            fecha = dia + "-" + mes;
                            diasemana = hoy.getDay();
                        }*/
                        for (var x = 0; x < diaferiado.length; x++)
                            if (fecha == diaferiado[x].dia + "-" + obtenerMes(diaferiado[x].mes, 1)) {
                                hoy = sumarDias(hoy, 1);
                                dia = hoy.getDate();
                                mes = hoy.getMonth();
                                fecha = dia + "-" + mes;
                                diasemana = hoy.getDay();
                            }
                        //feriado = 0;
                     //}fin while
                //fin del for plazo
                if(diasemana == 6) {
                    if(salto == 7){
                            hoy = sumarDias(hoy, -1);
                          
                    }else{
                          hoy = sumarDias(hoy, 2);
                    }
                    
                }else if(diasemana == 0){
                    if(salto == 7){
                            hoy = sumarDias(hoy, -2);
                          
                    }else{
                          hoy = sumarDias(hoy, 1);
                    }
                }
                  dia = hoy.getDate();
                 mes = parseInt(hoy.getMonth());
                 fecha = dia + "-" + mes;
                 diasemana = hoy.getDay();
                 dia = (dia <=9) ? "0"+dia:dia;
                 var mesaux = (mes <=9) ? "0"+(mes+1):(mes+1);
                $("#primera_cuota").val(dia + "-" + mon[mes] + "-" + hoy.getFullYear());
                 $("#primera_cuota_hidden").val(hoy.getFullYear()+ "-" +mesaux+ "-" + dia);
                if(salto == 15){
                      fn_fechaven_Quincenal(plazo,salto);
                }else if(salto==30){
                     fn_fechaven_Mensual(plazo,salto);
                }
                else if(salto == 7){
                    fn_fechaven_semanal(plazo,salto);
                }else{
                    fn_fechaven_diario(plazo);
                }
                
            }     //fin funcion success
        }); // fin llamada ajax

}
function calcular_primeraCuotaQuincenal(plazo,salto){
     var RegExPattern = /^\d{2,4}\-\d{1,2}\-\d{1,2}$/;
     var mon = {0:"Jan", 1:"Feb", 2:"Mar", 3:"Apr", 4:"May", 5:"Jun", 6:"Jul", 7:"Aug", 8:"Sep", 9:"Oct", 10:"Nov", 11:"Dec"};
     var fecha = $('#fecha_desembolso').val();
     if ((fecha.match(RegExPattern))) {
            fecha =$("#fecha_desembolso").val()+"T00:00:00";
        } else {
            fecha;
        }
    var hoy = new Date(fecha);
    
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
     
        $.ajax({
            url: 'index.php?controller=prestamo&action=DiaFeriado' + '',
            type: 'POST',
            data: parametros,
            success: function (datos) {
                var diaferiado = JSON.parse(datos);
                    /**Validar el salto si es mayor o igual a 16 y es 31 sumarle 16 de lo contrario 15**/
                     mes = hoy.getMonth();
                     var diasMes = new Date(hoy.getFullYear(), mes +1 , 0).getDate();
                      dia = hoy.getDate();


        /*if(dia >= 16){
        if(diasMes == 31){
        salto=16;
        }else if(diasMes == 28){
        salto=13;
        }
        else if(diasMes == 29){
        salto=14;
        }
        else{
        salto=15;
        }
        }*/
                   hoy = sumarDias(hoy, salto);
                   mes = hoy.getMonth();
                   dia = hoy.getDate();

                    fecha = dia + "-" + mes;
                    diasemana = hoy.getDay();
                   // while (diasemana == 6 || diasemana == 0 || feriado == 1) {
                        /*if (diasemana == 6 || diasemana == 0) {
                            hoy = sumarDias(hoy, 1);
                            dia = hoy.getDate();
                            mes = hoy.getMonth();
                            fecha = dia + "-" + mes;
                            diasemana = hoy.getDay();
                        }*/
                        for (var x = 0; x < diaferiado.length; x++)
                            if (fecha == diaferiado[x].dia + "-" + obtenerMes(diaferiado[x].mes, 1)) {
                                hoy = sumarDias(hoy, 1);
                                dia = hoy.getDate();
                                mes = hoy.getMonth();
                                fecha = dia + "-" + mes;
                                diasemana = hoy.getDay();
                            }
                        //feriado = 0;
                     //}fin while
                //fin del for plazo
                if(diasemana == 6) {
                          hoy = sumarDias(hoy, 2);
                    
                }else if(diasemana == 0){
                   
                          hoy = sumarDias(hoy, 1);
                }
                  dia = hoy.getDate();
                 mes = hoy.getMonth();
                 fecha = dia + "-" + mes;
                 diasemana = hoy.getDay();
                 dia = (dia <=9) ? "0"+dia:dia;
                $("#primera_cuota").val(dia + "-" + mon[mes] + "-" + hoy.getFullYear());
                
                
                      fn_fechaven_Quincenal(plazo,salto);
                
                
            }     //fin funcion success
        }); // fin llamada ajax

}
    $('#fecha_desembolso').on('change', function () {  
        var fecha = new Date($(this).val());                                // capturar el evento cambio del select plazo
        if ($("#plazo option:selected").text() !== "-Seleccione-") {
            var modalidad = $("#modalidad").val();

            var plazo = $("#plazo option:selected").text();
             if (modalidad == "Diario") {
                var fecha_dia= $('#fecha_desembolso').val();
                 $("#dia_semana").val("Diario");
                //fn_fechaven_diario(parseInt($("#plazo option:selected").text()));
                calcular_primeraCuota(parseInt($("#plazo option:selected").text()),1);
            }
            else if(modalidad == "Semanal"){
                var fecha_dia= $('#fecha_desembolso').val();
                 $("#dia_semana").val(dias(fecha_dia));
                //fn_fechaven_semanal(parseInt($("#plazo option:selected").text()),7);
                calcular_primeraCuota(parseInt($("#plazo option:selected").text()),7);
            }
            else if(modalidad == "Quincenal"){
                var fecha_dia= $('#fecha_desembolso').val();
                 $("#dia_semana").val("Quincenal");
                  calcular_primeraCuotaQuincenal(parseInt($("#plazo option:selected").text()),15);   
               // fn_fechaven_Quincenal(parseInt($("#plazo option:selected").text()),15);
                            
            }
            else{
                var fecha_dia= $('#fecha_desembolso').val();
                 $("#dia_semana").val("Mensual");
                  calcular_primeraCuota(parseInt($("#plazo option:selected").text()),30);
               // fn_fechaven_Quincenal(parseInt($("#plazo option:selected").text()),30);
               
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
              calcular_primeraCuota(1);
        }
        else if(modalidad == "Semanal"){
            getOptionCombo('plazos','dataByComboPlazosSemanales','plazo','id_plazo','descripcion','','');
            var fecha_dia= $('#fecha_desembolso').val();
            var fecha_Split = fecha_dia.split("-");
            var fecha_dia_ = fecha_Split[2]+"-"+months[parseInt(fecha_Split[1])]+"-"+fecha_Split[0];
            console.log(fecha_dia_);
            calcular_primeraCuota(7)
            var mes =  parseInt(fecha_Split[1]);
            if (isNaN(mes))
                $("#dia_semana").val(dias(fecha_dia));
            else
                $("#dia_semana").val(dias(fecha_dia_));
        }else if(modalidad == "Quincenal"){
            getOptionCombo('plazos','dataByComboPlazosQuincenales','plazo','id_plazo','descripcion','','');
            var fecha_dia= $('#fecha_desembolso').val();
            var fecha_Split = fecha_dia.split("-");
            var fecha_dia_ = fecha_Split[2]+"-"+months[parseInt(fecha_Split[1])]+"-"+fecha_Split[0];
            console.log(fecha_dia_);
            calcular_primeraCuota(15)
            var mes =  parseInt(fecha_Split[1]);
            if (isNaN(mes))
                $("#dia_semana").val("Quincenal");
            else
                $("#dia_semana").val("Quincenal");
        }else{
            getOptionCombo('plazos','dataByComboPlazosMensuales','plazo','id_plazo','descripcion','','');
            var fecha_dia= $('#fecha_desembolso').val();
            var fecha_Split = fecha_dia.split("-");
            var fecha_dia_ = fecha_Split[2]+"-"+months[parseInt(fecha_Split[1])]+"-"+fecha_Split[0];
            console.log(fecha_dia_);
            calcular_primeraCuota(30)
            var mes =  parseInt(fecha_Split[1]);
            if (isNaN(mes))
                $("#dia_semana").val("Mensual");
            else
                $("#dia_semana").val("Mensual");
        }
    });
     $("#imprimir").on("click",function() {
 var ficha = document.getElementById('cronograma');
 $(".imgicon").attr("src","./resources/imagen/logo.png");
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
   $(".oculto").show();
    $("#imprimir").show();
   if(($("#plazo option:selected").text() !== "-Seleccione-")&&($("#modalidad option:selected").text() !== "-Seleccione-")) {
    var plazo = $("#plazo option:selected").text();
  plazo = parseInt(plazo);
  if($("#modalidad").val()=="Diario"){
  fn_fechaven_diario(plazo);
  }else if($("#modalidad").val()=="Semanal"){
  fn_fechaven_semanal(plazo,7);
  }else if($("#modalidad").val()=="Quincenal"){
  fn_fechaven_Quincenal(plazo,15);
  }else{
      fn_fechaven_Mensual(plazo,30);
  }
  }

});

});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    var map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: new ol.View({
            center: ol.proj.fromLonLat([-86.877821512927,12.434955331961703]),
            zoom: 13
        })
    });

    var vectorSource = new ol.source.Vector();

    var vectorLayer = new ol.layer.Vector({
        source: vectorSource
    });

    map.addLayer(vectorLayer);

    function addMarker(position) {
        var marker = new ol.Feature({
            geometry: new ol.geom.Point(position)
        });

        var markerStyle = new ol.style.Style({
            image: new ol.style.Icon({
                anchor: [0.5, 1],
                src: 'https://openlayers.org/en/latest/examples/data/icon.png'
            })
        });

        marker.setStyle(markerStyle);
        vectorSource.addFeature(marker);
    }

   /* var position1 = ol.proj.fromLonLat([-86.867285186683,12.445449634258267]);
    addMarker(position1);

    var position2 = ol.proj.fromLonLat([-86.86610332523911,12.444656893905787]);
    addMarker(position2);

    var position3 = ol.proj.fromLonLat([-86.8708355565576,12.446193913319675]);
    addMarker(position3);

    var position4 = ol.proj.fromLonLat([-86.90261702963642,12.434919113470954]);
    addMarker(position4);

    var position5 = ol.proj.fromLonLat([-86.88148757091965,12.426886950341327]);
    addMarker(position5);*/
});



