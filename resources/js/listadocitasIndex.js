$.getScript("resources/js/jsDatatable.js", function(){
    var zona = $("#zona").val();
      var hiden = ((zona == "resumida") ? "0,3,4":((zona == "miscitas") ? "0,4":"0"));
    //var hiden = (zona == "") ? "0":"0,3,4"
     var citasCargar = ((zona == "resumida") ? "actual":((zona == "miscitas") ? "total":""));
    //var citasCargar = (zona == "") ? "":"actual"
    DatatableJs('#listascitas',"index.php?controller=Colaborador&action=listadocitas&list="+citasCargar,hiden,[{
        "targets": 5,
        "render": function ( data, type, row, meta ) {
            var itemID = row[0];
            var itemIDCita = row[5];
            var valor = itemID+"-"+itemIDCita;
            return ' <button class="btn btn-success" id="seguimiento" value="'+valor+'" data-toggle="modal" data-target="#myModal" title="Seguimiento Citas"><i class="fa fa-database" aria-hidden="true" title="Seguimiento" data-target="#myModal"></i></button><button class="btn btn-info" id="cerrar" value="'+valor+'" data-toggle="modal" data-target="#myModal"><i class="fa fa-sign-out" aria-hidden="true" title="Cerrar" title="Cerrar Citas"> </i></button>';
        }, 


    },

    ])

});

$(document).ready(function () {
    //var table = $('#listascitas').DataTable();
 
    $('#listascitas tbody').on('click', 'tr button', function () {
        var data = $(this).val();
        var ids = data.split("-");
        var id = $(this).attr("id");
        if(id == "seguimiento"){
            $("#Title").text("Seguimientos Citas");
             $("#cierrecita").val("0");
              $("#optionbutton").val("Reprogramar");
        }else{
              $("#Title").text("Cerrar Citas");
               $("#cierrecita").val("1");
                $("#optionbutton").val("Cerrar Cita");
        }
        
         $("#id_agenda").val(ids[0]);
         $("#id_citas").val(ids[1]);
    });
});