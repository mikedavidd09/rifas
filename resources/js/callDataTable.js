$(".linking").on('click',function(){
   var ruta=$(this).attr("href");
   var origen = parseInt(ruta.substr(ruta.length-1,ruta.length));
   var thid=$(this);
   if(ruta != '#'){
        if(origen == 1){
            smsConfirm_(ruta,origen,thid);
        }else{
            ajaxEstado(ruta,origen,thid);
            return false;
        }
    }
});
function ajaxEstado(ruta,origen,thid){
    $.ajax({
        type: 'GET',
        url: ruta,
        success: function (response) {
        var r = JSON.parse(response);
            if(origen == 1){
            if(r == "Aprobado"){
                 alerta_("Prestamo Aprobado Correctamente.");
                thid.find("input[type=checkbox]").attr("disabled","disabled");
                thid.attr({
                   'title': 'Prestamo ya Aprobado',
                   'href': '#'
                });

            }
        }else{
            if(r == "Aprobado"){
                 alerta_("Prestamo Aprobado Correctamente.");
            }else if(r == "Rechazado"){
                 alerta_("El prestamo se a rechazado.");
            }
        }
        }
    });
}
function smsConfirm_(ruta,origen,thid){
    var confirm = alertify.confirm('Wolf Financiero Dice:', "Estas Seguro!!,Vas a Aprobar este prestamo", null, null).set('labels', {
            ok: 'Si',
            cancel: 'No'
        });
        confirm.set({transition: 'slide'});
        confirm.set('onok', function () { //callbak al pulsar botón positivo
            ajaxEstado(ruta,origen,thid);
        });
        confirm.set('oncancel', function () { //callbak al pulsar botón negativo
            // thid.prop('checked', false);
             thid.find("input[type=checkbox]").prop('checked', false);
            alertify.error('Se cancelo la Aprobaciòn del Prestamo.');
        });
        confirm.set('onnegativo', function () { //callbak al pulsar botón negativo
            alertify.error('Se cancela la transaccion');
        });
}
function alerta_(sms){
      //un alert$(this).attr('checked', false);
      alertify.alert('Wolf Financiero Dice:',sms, function () {
      });
}





/**
 * Created by ruiz on 08-24-17.
 */
 if ( $.fn.DataTable.isDataTable('#example') ) {
     $('#example').DataTable().destroy();
 }
 var table = $('#example')
    .addClass('responsive')/*realizar javascrip  q cuando sea pantalla moviles  se agrege la clase  table.addClass('responsive nowrap');*/
    .dataTable( {
        responsive: true,
        processing: true,
        "sLoadingRecords": "Cargando...",
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por paginas",
            "zeroRecords": "No se encontró nada, sin registros",
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(Filtrado de  _MAX_ total de registros)",
            "search": "Buscar en la Tabla"
        }
    } );

    $('#example tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );

    table.api().columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
