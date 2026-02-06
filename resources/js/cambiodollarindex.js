$.getScript("resources/js/jsDatatable.js", function(){

    DatatableJs('#cambiodollar',"index.php?controller=CambioDolar&action=listado",0,[{

        "targets": 6,

        "render": function ( data, type, row, meta ) {

            var id_cambiodolar = row[0];

            var codigo = row[1];

			var estado = row[4];

			var arqueo = row[5];

			var fecha_creacion = row[3];

			var cambioDolar = row[2];

			var modal ="<div class='modal fade' id='modal"+id_cambiodolar+"' role='dialog'><div class='modal-dialog modal-sm' ><!-- Modal content--><div class='modal-content'><form name='form"+id_cambiodolar+"' id='form"+id_cambiodolar+"' method ='POST'><div class='modal-header'><button type='button' class='close' data-dismiss='modal'>&times;</button><h4 class='modal-title'>Editar Cambio Dolar</h4></div><div class='modal-body'><label> <span class='txRed'>*</span>Codigo: </label><INPUT type='text' name='code_cambiodolar' id='code_cambiodolar"+id_cambiodolar+"' value='"+codigo+"' class= 'form-control'readonly='readonly'><label> <span class='txRed'>*</span>fecha_creacion: </label><INPUT type='text' name='fecha_creacion' id='fecha_creacion"+id_cambiodolar+"' value='"+fecha_creacion+"' class= 'form-control' readonly='readonly'><label> <span class='txRed'>*</span>Estado: </label><INPUT type='text' name='estado' id='estado"+id_cambiodolar+"' value='"+estado+"' class= 'form-control' readonly='readonly'><label> <span class='txRed'>*</span>Cambio Dolar: </label><INPUT type='text' name='cambio_dolar' id='cambio_dolar"+id_cambiodolar+"' value='"+cambioDolar+"' class= 'form-control'><input type='hidden' name='id_cambiodolar' id='"+id_cambiodolar+"' value='"+id_cambiodolar+"'></<div><div class='modal-footer'><a href='#' onclick=\"validarEnviar('form"+id_cambiodolar+"','CambioDolar','update',"+id_cambiodolar+");\"><input type='button' value='Actualizar' class='btn btn-primary' data-dismiss='modal'	/></a><button type='button' class='btn btn-default' data-dismiss='modal'>Cancelar</button></div></div></div></form></div>";

			if(estado == 'Desactivo' || arqueo == 'Si'){

				return "<a href='#'> <span class='btn btn-danger' >Delete  <em class='fa fa-lock'></em> </span></a><a href='#'  ><span class='btn btn-success' data-title='Editar' data-toggle='modal' data-target='#modal'>Edit <em class='fa fa-lock'></em> </span></a>";

			}else{

				//return "<a href='#' onclick='realizarBorrado('CambioDolar',"+id_cambiodolar+")'> <span class='btn btn-danger' >Delete  <em class='fa fa-trash'></em> </span></a><a href='#'  ><span class='btn btn-success' data-title='Editar' data-toggle='modal' data-target='#modal"+id_cambiodolar+"'>Edit <em class='fa fa-pencil'></em> </span></a>";

				return "<a href='#' onclick=\"realizarBorrado('CambioDolar',"+id_cambiodolar+")\" > <span class='btn btn-danger' >Delete  <em class='fa fa-trash'></em> </span></a><a href='#'  ><span class='btn btn-success' data-title='Editar' data-toggle='modal' data-target='#modal"+id_cambiodolar+"'>Edit <em class='fa fa-pencil'></em> </span></a><div class='modal fade' id='modal"+id_cambiodolar+"' role='dialog'><div class='modal-dialog modal-sm' ><!-- Modal content--><div class='modal-content'><form name='form"+id_cambiodolar+"' id='form"+id_cambiodolar+"' method ='POST'><div class='modal-header'><button type='button' class='close' data-dismiss='modal'>&times;</button><h4 class='modal-title'>Editar Cambio Dolar</h4></div><div class='modal-body'><label> <span class='txRed'>*</span>Codigo: </label><INPUT type='text' name='code_cambiodolar' id='code_cambiodolar"+id_cambiodolar+"' value='"+codigo+"' class= 'form-control'readonly='readonly'><label> <span class='txRed'>*</span>fecha_creacion: </label><INPUT type='text' name='fecha_creacion' id='fecha_creacion"+id_cambiodolar+"' value='"+fecha_creacion+"' class= 'form-control' readonly='readonly'><label> <span class='txRed'>*</span>Estado: </label><INPUT type='text' name='estado' id='estado"+id_cambiodolar+"' value='"+estado+"' class= 'form-control' readonly='readonly'><label> <span class='txRed'>*</span>Cambio Dolar: </label><INPUT type='text' name='cambio_dolar' id='cambio_dolar"+id_cambiodolar+"' value='"+cambioDolar+"' class= 'form-control'><input type='hidden' name='id_cambiodolar' id='"+id_cambiodolar+"' value='"+id_cambiodolar+"'></<div><div class='modal-footer'><a href='#' onclick=\"validarEnviar('form"+id_cambiodolar+"','CambioDolar','update',"+id_cambiodolar+");\"><input type='button' value='Actualizar' class='btn btn-primary' data-dismiss='modal'	/></a><button type='button' class='btn btn-default' data-dismiss='modal'>Cancelar</button></div></div></div></form></div>";	

			}

        }

    }])

});

