<div class="card">
    <div class="card-header">
        <h3 class="card-title">Agregar NumeroS Ganadores</h3>
    </div>
    <div class="card-body">
        <?php 
        $columnasPorFila = 4; // Cambia a 3, 4, 6 según necesites
        $contador = 0;
        
        foreach($juegos as $juego){ 
            $maxdigits = strlen(abs($juego->max));
            $ceros = str_repeat('0', $maxdigits);
            // Abrir nueva fila cada X columnas
            if($contador % $columnasPorFila == 0) {
                if($contador != 0) echo '</div>'; // Cerrar fila anterior
                echo '<div class="row mb-3">'; // Abrir nueva fila
            }
        ?>
            <div class="col-md-<?php echo 12/$columnasPorFila; ?> mb-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr class="text-center">
                                <th colspan="3" class="btn-primary text-white">
                                    <h6 class="mb-0"><?php echo $juego->nombre; ?></h6>
                                </th>
                            </tr>
                            <tr class="btn-primary text-white">
                                <th>SORTEO</th>
                                <th>NUMERO</th>
                                <th>GUARDAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($juego->sorteos as $sorteo){ ?>
                            <tr class="text-center">
                                <td><?php echo $sorteo->etiqueta; ?></td>
                                <td><input type="text"  class="form-control input-underlined"  name="numero" id="numero_<?php echo $juego->id_juego; ?>_<?php echo $sorteo->id_sorteo; ?>" placeholder="<?php echo $ceros ?>" maxlength="<?php echo $maxdigits ?>"  required oninput="this.value = this.value.replace(/[^0-9]/g, '')"></td>
                                <td><button type="button" class="btn btn-info" onclick="guardar(<?php echo $juego->id_juego; ?>, <?php echo $sorteo->id_sorteo; ?>)"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php 
            $contador++;
        } 
        echo '</div>'; // Cerrar última fila
        ?>
    </div>
</div>


<style>

/* Input con solo borde inferior */
.input-underlined {
    border: none !important;
    border-bottom: 2px solid #ced4da !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    padding: 8px 0 !important;
    background-color: transparent !important;
}

.input-underlined:focus {
    border-bottom-color: #007bff !important;
    box-shadow: none !important;
    background-color: transparent !important;
}

.input-underlined:hover:not(:focus) {
    border-bottom-color: #6c757d !important;
}

</style>

<script>
function guardar(id_juego, id_sorteo){
    var numero = document.getElementById("numero_"+id_juego+"_"+id_sorteo).value.trim();
    if(!numero){
        alertify.error("Ingrese un numero por favor!");
        return;
    }
    console.log(numero);

        $.ajax({
            url: "index.php?controller=Juegos&action=guardarNumero",
            type: "POST",
            dataType: "json",
            data: {numero: numero, id_juego: id_juego, id_sorteo: id_sorteo},
            success: function(data){

                if(data.status){
                    alertify.success(data.message);
                }
                else{
                    alertify.error(data.message);
                }
            }
        });
}   
</script>