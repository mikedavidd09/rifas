function DatatableJs(selector,url,col,colHiperVinc) {

    var filtere = $("#searchfilter").val();
    
    if(filtere === undefined){
        filtere='';
    }

    if ( $.fn.DataTable.isDataTable(selector) ) {
        $(selector).DataTable().destroy();
    } else {
        $(document).ready(function(e){
            var tabla = $(selector).dataTable({
                responsive: true,
                "rowCallback": function( row, data ) {
                    if(data[14] == "ModPago" ){
                       if ( data[1] == 1 ) {
                            $(row).addClass('selected');
                        }else if(data[10] == 'Primera Cuota') {
                            $(row).addClass('primera_cuota');
                        }else if(data[9] == 'Vencido') {
                            $(row).addClass('vencido');
                        }else if(data[11] =='Semanal'){
                                $(row).addClass('semanal');
                        }else if(data[13] =='Con Mora'){
                                $(row).addClass('conmora');
                        }else if(data[11] == 'Quincenal'){
                                $(row).addClass('label-success');
                        }else if(data[12] == 'True'){
                                $(row).addClass('endiadegracia');
                        }
                    }
                },
                "sLoadingRecords": "Cargando...",
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por paginas",
                    "zeroRecords": "No se encontró nada, sin registros",
                    "info": "Mostrando la página _PAGE_ de _PAGES_ Total de registros _MAX_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(Filtrado de  _MAX_ total de registros)",
                    "search": "Buscar en la Tabla"
                },
                "bProcessing": true,
                "serverSide": true,
                "ajax":{
                    url :url,
                    type: "POST",
                    error: function(){
                        $("#post_list_processing").css("display","none");
                    }
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: '<i class="fa fa-refresh"">Actualizar</i>',
                        action: function ( e, dt, node, config ) {
                            dt.ajax.reload();
                        }
                    },
                ],
                "columnDefs": colHiperVinc
            });
            tabla.api().columns([col]).visible(false);
            tabla.api().search( filtere ).draw();
            $(selector+' tbody').on( 'click', ' tr a', function (row) {
                var link = $(this).attr("href");
                //$(this).parents('tr').hasClass('selected');
                if(link.indexOf("cargar=dataTable") != -1){
                    var datos = link.split("&");
                    var registro = datos.length;
                    var estado=datos[registro - 1];
                    var method = estado.substr(estado.indexOf("=") + 1,estado.length - estado.indexOf("="));
                    var sms="";
                    var autorizado = $("#clscheckboxAuotizado").val();
                    console.log(autorizado);
                    if(method == "sanear"){
                        sms="Estas Seguro!!,Desea Pasar este prestamo a Saneado?";
                    }else if(method == "activar"){
                        sms="Estas Seguro!!,Desea Reactivar este prestamo?";
                    }else if(method == "autorizar"){
                        sms = (link.indexOf("autorizado=0") != -1) ? "Estas Seguro!!,Desea Auotirzar Este Prestamo para que sea represtado?":"Estas Seguro!!,Desea Desauotirzar Este Prestamo para que no sea represtado?";
                        //sms="Estas Seguro!!,Desea Auotirzar Este Prestamo para que sea represtado?";
                    }
                        smsConfirm(link,tabla,$(this).parents('tr'),sms);
                }
                    
                } );
                
        });
    }
    tablasearch=selector;
}
function ajaxDataTable(url,tabla,parentTR){

 $.ajax({
                url: url
            }).done(function (response) {
               var obj = JSON.parse(response);
               data = obj;
                if(obj.respuesta == "true"){
                    alertify.alert('Wolf Financiero S.A. Dice:',obj.mensaje, function () {
                        //tabla.row( parentTR ).remove().draw();
                        //tabla.ajax.reload();
                        tabla.api().row(parentTR).remove().draw();
                        //console.log(tabla.api().row(parentTR).remove().draw());
                    });
                    
                }else{
                    alertify.alert('Wolf Financiero S.A. Dice:',obj.mensaje, function () {});
                }
            });
}
function smsConfirm(link,tabla,parentTR,mensaje){
    var confirm = alertify.confirm('Wolf Financiero S.A. Dice:', mensaje, null, null).set('labels', {
            ok: 'Si',
            cancel: 'No'
        });
        confirm.set({transition: 'slide'});
        confirm.set('onok', function () { //callbak al pulsar botón positivo
        
           ajaxDataTable(link,tabla,parentTR);
        });
        confirm.set('oncancel', function () { //callbak al pulsar botón negativo
            alertify.error('Se cancelo la transacción!.');
            tabla.api().row(parentTR).remove().draw();
        });
        confirm.set('onnegativo', function () { //callbak al pulsar botón negativo
            alertify.error('Se cancela la transaccion');
        });
}
