$.getScript("resources/js/jsDatatable.js", function(){
    //var zona = $("#zona").val();
      //var hiden = ((zona == "resumida") ? "0,3,4":((zona == "miscitas") ? "0,4":"0"));
    //var hiden = (zona == "") ? "0":"0,3,4"
    // var citasCargar = ((zona == "resumida") ? "actual":((zona == "miscitas") ? "total":""));
    //var citasCargar = (zona == "") ? "":"actual"
    DatatableJs('#listaclientesnegras',"index.php?controller=Cliente&action=listadoClientesListaNegra",0,[{
        "targets": 4,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            var itemIDCita = row[5];
            var valor = itemID;
            return ' <button class="btn btn-success" id="addlistanegra" value="'+valor+'" data-toggle="modal" data-target="#myModal" title="Agregar a Lista Negra"><i class="fa fa-database" aria-hidden="true" title="Agregar" data-target="#myModal"></i></button>';
        }, 


    },

    ])

});

$(document).ready(function () {
    //var table = $('#listascitas').DataTable();
 
    $('#listaclientesnegras tbody').on('click', 'tr button', function () {
        var data = $(this).val();
        //var ids = data.split("-");
        /*var id = $(this).attr("id");
        if(id == "seguimiento"){
            $("#Title").text("Seguimientos Citas");
             $("#cierrecita").val("0");
              $("#optionbutton").val("Reprogramar");
        }else{
              $("#Title").text("Cerrar Citas");
               $("#cierrecita").val("1");
                $("#optionbutton").val("Cerrar Cita");
        }*/
        
         $("#id_cliente").val(data);
         //$("#id_citas").val(ids[1]);
    });
});