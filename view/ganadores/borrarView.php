<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><h3 class="card-title alert alert-success" role="alert">Borrar Numeros Ganadores</h3></h5>
            </div>
            <div class="card-body"> 
            <form id="formBorrar" class="form-group">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr class="btn-primary text-white">
                            <th>JUEGO</th>
                            <th>SORTEO</th>
                            <th>NUMERO</th>
                            <th>GUARDAR</th>
                  </tr>
                  <tbody>
                   
              <?php 
              if(count($juegos)){
              foreach($juegos as $juego){
                echo '<tr class="text-center">';
                echo '<td>'.$juego->nombre.'</td>';
                echo '<td>'.$juego->etiqueta.'</td>';
                echo '<td>'.$juego->numero.'</td>';
                echo '<td><button type="button" class="btn btn-danger" onclick="borrar('.$juego->id_numero_ganador.')"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>';
                echo '</tr>';
                }

                }else{
                    echo '<tr class="text-center">';
                     echo '<td colspan="4">No hay numeros ganadores</td>';
                     echo '</tr>';
                }
              ?>
            </form>
     
        </div>
      </div>
    </div>
</div>