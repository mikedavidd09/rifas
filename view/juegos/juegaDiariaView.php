    <!-- Basic Bootstrap Table -->
     
    <div class="row g-12">
    
      <div class="col-md-6">
        <div class="card">
          <div class="alert alert-success" role="alert">
            <h4 class="alert-heading"><?php echo $juego; ?></h4>
          </div>
          <div class="card-body">
            <!-- Use flexbox so buttons stay horizontal when space allows -->
           
              <div class="row mb-3">
                
                <div class="col-xs-4"><button id="random" class="btn btn-primary" data-toggle="modal" data-target="#randomModal"><i class="fa fa-random"></i></button></div>
                <div class="col-xs-4"><button id="linea"  class="btn btn-primary" data-toggle="modal" data-target="#lineaModal"> <i class="fa fa-signal"></i></button></div>
                <div class="col-xs-4"><button id="suerte" class="btn btn-primary" data-toggle="modal" data-target="#paresModal"> <i class="fa fa-exchange"></i></button></div>
              </div>
       
        <hr class="my-4" style="border-top: 1px solid #7b7a7aff !important;" />
            <div class="row g-6">
              <input type="hidden" id="id_juego" value="<?php echo $id_juego; ?>">
              <?php if(isset($min) && isset($max)){ ?>
                <input type="hidden" id="min" value="<?php echo $min; ?>">
                <input type="hidden" id="max" value="<?php echo $max; ?>">
                <input type="hidden" id="juego" value="<?php echo $juego; ?>">
              <?php } ?>
              <div class="col-md-6">
                <label class="form-label" for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" />
              </div>
              <div class="col-md-6"> 
                <label class="form-label" for="numero"> <?php echo 'Número   ['.$min.'-'.$max.']'; ?></label>
                <input type="tel" id="numero" class="form-control onlynumber" />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="monto">Monto C$</label>
                <input type="tel" id="monto" class="form-control onlynumber" />
              </div>

              <div class="col-md-6">
                <div class="form-group">
                <button
               
                  id="addButton"
                  class="btn btn-primary"
                  data-repeater-create=""
                  fdprocessedid="7v82oo"
                >
                  <i class="fa fa-plus-circle"></i>
                  <span class="align-middle">Agregar</span>
                </button>
                <button
                 
                  class="btn btn-primary "
                  onclick="showReceipt()"
                >
                  <i class="fa fa-save"></i>
                  <span class="align-middle">Guardar</span>
                </button>
                </div>
              </div>
            </div>
           
          </div>
        </div>
      </div>

      
      <div class="col-md-6">
        <div class="card">
          <buton id='deleteAll' class="btn btn-danger" >Borrar Todo</buton>
           <div class="table-responsive text-nowrap">
              <table id="dataTable" class="table text-nowrap">
                <thead>
                  <tr>
                    <th> Nº </th>
                    <th> Numero </th>
                    <th> Monto C$</th>
                    <th> Premio C$</th>
                    <th> Borrar</th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0"></tbody>
              </table>
            </div>
        </div>
      </div>
    
    </div>
<!-- Modal  resgistrar numero aleatorios -->
<div id="randomModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="randomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- Header -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">  <span aria-hidden="true">&times;</span> </button>
        <h5 class="modal-title" id="randomModalLabel">Registrar Aleatorio</h5>
        
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col">
                    <label class="form-label" for="montoRandom">Monto C$</label>
                    <input type="tel" id="montoRandom" class="form-control onlynumber" />
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label class="form-label" for="cantidadRandom">Cantidad de Aleatorios</label>
                    <input type="tel" id="cantidadRandom" class="form-control onlynumber" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="addRandomNumber" type="button" class="btn btn-primary">Agregar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="lineaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="lineaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="randomModalLabel">Registrar Linea</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">   <span aria-hidden="true">&times;</span> </button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                 <div class="row">
                <div class="col">
                  <label class="form-label" for="montoLinea">Monto C$</label>
                  <input type="tel" id="montoLinea" class="form-control onlynumber" />
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label class="form-label" for="cantidadLinea">Inicio</label>
                  <input type="tel" id="inicioLinea" class="form-control onlynumber" />
                </div>
              </div>
              <div class="row">
              <div class="col">
                  <label class="form-label" for="finalLinea">Inicio</label>
                  <input type="tel" id="finalLinea" class="form-control onlynumber" />
                </div>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="addLineaNumber" type="button" class="btn btn-primary">Agregar</button>
      </div>
    </div>
  </div>
</div>
  

<!-- Modal  resgistrar todos los numeros pares -->
<div id="paresModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="paresModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- Header -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">  <span aria-hidden="true">&times;</span> </button>
        <h5 class="modal-title" id="randomModalLabel">Registrar todos los numeros pares</h5>
        
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col">
                    <label class="form-label" for="montoPar">Monto C$</label>
                    <input type="tel" id="montoPar" class="form-control onlynumber" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="addparNumber" type="button" class="btn btn-primary">Agregar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Imprimir Recibo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
         <div id="printableReceipt" class="receipt">
                      
            <div class="receipt-header" id="heder-receipt">
             
            </div>
            <table class="receipt-items">
              <thead>
                <tr>
                 
                  <th >Número</th>
                  <th class="text-center">Monto</th>
                  <th class="text-center">Premio</th>
                </tr>
              </thead>
              <tbody id="rows_item">
              
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="2" >Total:</th>
                  <th  id="total"></th>
                </tr>
              </tfoot>
            </table>
            <div class="receipt-footer">
              <div>Gracias por su compra</div>
              <div>¡Vuelva pronto!</div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="printReceipt()">Imprimir</button>
      </div>
    </div>
  </div>
</div>


    <script src="assets/js/loto/loto.js"></script>

<script>
  window.venta = {
    id_juego: null,
    nombre: null,
    juego: null,
    min: null,
    max: null,
    numeros: [],
    total:0
  };
 
  venta.id_juego = parseInt($('#id_juego').val());
  venta.min = parseInt($('#min').val());
  venta.max = parseInt($('#max').val());
  venta.juego = $('#juego').val();

  console.log(venta);


</script>



  
