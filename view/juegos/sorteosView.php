<div class="card">
    <div class="card-header">
        <h3 class="card-title">Numero de Ganadores</h3>
    </div>
    <div class="card-body">
        <?php 
        $columnasPorFila = 4; // Cambia a 3, 4, 6 según necesites
        $contador = 0;
        
        foreach($juegos as $juego){ 
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
                                <th colspan="3" class="bg-primary text-white">
                                    <h5 class="mb-0" ><?php echo strtoupper($juego->nombre); ?></h5>
                                </th>
                            </tr>
                            <tr class="bg-primary text-white">
                                <th style="font-size: 1.4em;">SORTEO</th>
                                <th style="font-size: 1.4em;">INICIO</th>
                                <th style="font-size: 1.4em;">FIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($juego->sorteos as $sorteo){ ?>
                            <tr class="text-center">
                                <td style="font-size: 1.4em;"><?php echo $sorteo->etiqueta; ?></td>
                                <td style="font-size: 1.4em;"><?php echo Utils::getTimeFormatted($sorteo->inicio); ?></td>
                                <td style="font-size: 1.4em;"><?php echo Utils::getTimeFormatted($sorteo->fin); ?></td>
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