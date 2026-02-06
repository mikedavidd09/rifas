

$(document).ready(function(){
    $(document).on('click','#save-programer',function(){
        var list = new Array();
        let arrSotr = new Array();
        $('#sortable').find('.ui-state-default').each(function(i){
            var id = $(this).attr('data-id');
            var lu = $(this).find('input:checkbox[name=lu]:checked').prop('checked') == true? 1:0;
            var ma = $(this).find('input:checkbox[name=ma]:checked').prop('checked') == true? 1:0;
            var mi = $(this).find('input:checkbox[name=mi]:checked').prop('checked') == true? 1:0;
            var ju = $(this).find('input:checkbox[name=ju]:checked').prop('checked') == true? 1:0;
            var vi = $(this).find('input:checkbox[name=vi]:checked').prop('checked') == true? 1:0;
            var sa = $(this).find('input:checkbox[name=sa]:checked').prop('checked') == true? 1:0;

            var programer = lu+","+ma+","+mi+","+ju+","+vi+","+sa;
            arrSotr.push({'id_prestamo':id,'order':i, 'programer':programer });
        });
        var data=JSON.stringify(arrSotr);
        console.log(data);
        $.ajax({
            url: 'index.php?controller=Prestamo&action=updateProgramer', // server url
            type: 'POST', //POST or GET
            data: {token:'reorder',data:data}, // data to send in ajax format or querystring format
            datatype: 'json',
            success: function(response) {
                var obj = JSON.parse(response);

                if(obj.respuesta == "true"){
                    showNotify('success' ,'Correcto' ,obj.mensaje);
                }else{
                    showNotify('warning' ,'Error' ,obj.mensaje);
                }
            }

        });
    });

    $(document).on('change','#id_colaborador',function(){
        $("#mostrar").attr('href', 'index.php?controller=cliente&action=programer&id_colaborador='+$("#id_colaborador option:selected").val())
    });

    $("#search_engine").on("keyup", function() {

        var patron = $(this).val().toUpperCase();

        // si el campo está vacío
        if (patron == "") {

            // mostrar todos los elementos
            $(".route-list").css("display", "list-item");

            // si tiene valores, realizar la búsqueda
        } else {

            // atravesar la lista
            $(".route-list").each(function() {

                if ($(this).text().split('|')[0].indexOf(patron) < 0) {
                    console.log($(this).text().split('|')[0]);
                    // si el texto NO contiene el patrón de búsqueda, esconde el elemento
                    $(this).css("display", "none");
                } else {
                    console.log($(this).text().split('|')[0]);
                    // si el texto SÍ contiene el patrón de búsqueda, muestra el elemento
                    $(this).css("display", "list-item");
                }

            });
        }

    });

});