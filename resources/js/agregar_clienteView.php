<?php $login = $_SESSION["Login_View"];?>
<div class="card">
    <h3>Dato Cliente:</h3>
    <hr>
    <ul class='nav nav-tabs'>
        <li class='active'><a data-toggle='tab' id="toggle-data-prestamo" href='#home'><h4>Perfil Cliente</h4></a></li>
        <li><a data-toggle='tab' id="toggle-referencias" href='#ref_person'><h4>Referencias Personales</h4></a></li>
        <li><a data-toggle='tab' id="toggle-evaluacion" href='#evaluacion'><h4>Evaluaci&oacuten</h4></a></li>
        <li><a data-toggle='tab' id="toggle-deudas" href='#deudas'><h4>Deudas</h4></a></li>
        <li><a data-toggle='tab' id="toggle-garantiasMol" href='#garan_moviliarias'><h4>Garantias Mobiliarias</h4></a></li>
        <li><a data-toggle='tab' id="toggle-fiador" href='#fiador'><h4>Fiador</h4></a></li>
<li><a data-toggle='tab' id="toggle-data-prestamo" href='#Solicitud'><h4>Solicitud Prestamo</h4></a></li>
    </ul> <br />
    <form id="cliente_form" method="post">
        <div class='tab-content'>
            <div id='home' class='tab-pane fade in active'><br >
                <div class="row">
                    <div class="col-lg-3">
                        <div class="col-lg-12">
                            <label><img src="assets/img/fotos_clientes/user.png" alt="..." class="img-thumbnail" id="img_cliente" width="40%" height="40%"></label>
                        </div>
                        <div class="col-lg-12">
                            <label><img src="assets/img/fotos_clientes/user.png" alt="..." class="img-thumbnail" id="img_cliente_reverse" width="40%" height="40%"></label>
                        </div>
                        <br />
                        <label><span class="txRed">*</span>Nombres:</label> <input type="text"  name="nombre" class="form-control"/>
                        <label><span class="txRed">*</span>Apellidos:</label> <input type="text" name="apellido" class="form-control"/>
                        <label><span class="txRed">*</span>Sexo:</label>
                        <div id="div_btn_radio" class="div_btn_button">
                            <label class="radio-inline"><input type="radio" value="M"  name="sexo">Masculino</label>
                            <label class="radio-inline"><input type="radio" value="F" name="sexo">Femenino</label>
                        </div>
                        <label><span class="txRed">*</span>C&eacutedula:</label>   <input type="text" placeholder="000-000000-0000A"  id="cedula" name="cedula" size="15" minlength="15" maxlength="15" class="numero form-control"/>
                        <label><span class="txRed">*</span>Estado Civil:</label> 
                         <SELECT name="estado_civil" id="estado_civil" class=" form-control">
                                <option value="0">-Seleccione-</option>
                                <option >Casad@</option>
                                <option >Solter@</option>
                                <option >Viud@</option>
                                <option >Union de hecho estable</option>
                             </SELECT>

                        <label><span class="txRed">*</span>Condici&oacuten de la Vivienda:</label> 
                        <SELECT name="condicion_vivienda" id="condicion_vivienda" class="form-control">
                                <option value="0">-Seleccione-</option>
                                <option >Propia</option>
                                <option >Familiar</option>
                                <option >Alquilada</option>
                            </SELECT>
                        </div>
                    <div class="col-lg-3">
                        <label><span class="txRed">*</span>Tel&eacutefono 1:</label> <input type="tel" placeholder="0000-0000" id="telefono" name="telefono" class="numero form-control"/>
                        <label><span class="txRed"></span>Tel&eacutefono 2:</label> <input type="tel" placeholder="0000-0000" id="telefono_1" name="telefono_1" class="numero form-control noValidated"/>
                         <label><span class="txRed">*</span>No Personas a cargo:</label> <input type="text" placeholder="00" id="n_persona_cargo" name="n_persona_cargo" class="numero form-control"/>
<label><span class="txRed">*</span>Direcci&oacuten del Domicilio:</label><textarea class="form-control" name="direccion" rows="4" id="direccion"></textarea>
              <label><span class="txRed"></span>Nombre del C&oacutenyugue:</label> <input type="text"  name="nombre_conyugue" class="form-control noValidated"/>
                        <label><span class="txRed"></span>C&eacutedula del C&oacutenyugue:</label> <input type="text" placeholder="000-000000-0000A"  name="cedula_conyugue" id="cedula_conyugue" class="form-control noValidated"/>
                        <label><span class="txRed"></span>Telefono del C&oacutenyugue:</label> <input type="text" placeholder="0000-0000" name="telefono_conyugue" id="telefono_conyugue" class="numero form-control noValidated"/>
                         

                    </div>
                    <div class="col-lg-3">
                         <?php
                            if($login->cargo != "Analista"){
                                echo"<label><span class='txRed'>*</span>Empleado:</label>
                    <select id='id_colaborador' name='id_colaborador' class='form-control'>
                        <option value='0'>-Seleccione-</option>
                    </select>";
                            }
                            ?>
						<label>Cedula Fiador:</label>   <input type="text" placeholder="000-000000-0000A"  id="cedulafiador" name="cedulafiador" size="16" minlength="16" maxlength="16" class="numero form-control noValidated"/>
                        <h4><span class="label label-success"> Foto C&eacutedula Cliente Frontal</span></h4>
                        <input name="imagen" id="imagen" accept="image/*"  type="file"
                               onchange="upload_image('cliente_form','Cliente','save_image','imagen','imagen_cliente','fotos_clientes','cargandocedula');return false;" />
                        <div class="row" id="cargandocedula"></div>
                        <input type="hidden" name="imagen_cliente" id="imagen_cliente"><br />
                        <h4><span class="label label-success"> Foto C&eacutedula Cliente Reversa</span></h4>
                        <input name="imagen_cedula_reversa" id="imagen_cedula_reversa" accept="image/*"  type="file"
                               onchange="upload_image('cliente_form','Cliente','save_image','imagen_cedula_reversa','imagen_cliente_reversa','fotos_clientes','cargandocedulareversa');return false;" />
                        <div class="row" id="cargandocedulareversa"></div>
                        <input type="hidden" name="imagen_cliente_reversa" id="imagen_cliente_reversa"><br />
                        <h4><span class="label label-success"> Foto de la Vivienda</span></h4>
                        <input name="imagen_vienda" id="imagen_vienda" accept="image/*"  type="file"
                               onchange="upload_image('cliente_form','Cliente','save_image','imagen_vienda','imagen_cliente_vivienda','fotos_clientes','cargandocedulavivienda');return false;" />
                        <div class="row" id="cargandocedulavivienda"></div>
                        <input type="hidden" name="imagen_cliente_vivienda" id="imagen_cliente_vivienda"><br />
                        <label><img src="assets/img/fotos_clientes/user.png" alt="..." class="img-thumbnail" id="img_cliente_vivienda" width="60%" height="60%"></label>
                    </div>
                </div>
            </div><!---//fin del conten home-->
             <div id='Solicitud' class='tab-pane fade'>
                 <div class="row">
                        <div class="col-lg-3">
                           
                            <label><span class="txRed">*</span> Capital: </label> <INPUT type="text" name="capital" id="capital"   placeholder="0.0" class= "decimal decimales form-control" >
                            <label> <span class="txRed">*</span>Interes: </label>
                            <SELECT name="interes" id="id_interes" class=" form-control">
                                <option value="0">-Seleccione-</option>
                            </SELECT>
                            <label> <span class="txRed">*</span>Modalidad: </label>
                            <SELECT name="modalidad" id="modalidad" class=" form-control">
                                <option value="0">-Seleccione-</option>
                                <option >Diario</option>
                                <option >Semanal</option>
                                
                            </SELECT>
                            <label><span class="txRed">*</span> Plazo : </label>
                            <SELECT name="plazo" id="plazo" class=" form-control">
                                <option value="0">-Seleccione-</option>
                            </SELECT>
                            <label><span class="txRed">*</span> Cuota: </label>
                            <INPUT type="text" id="cuota" name="cuota" readonly="readonly" class= "form-control">

                        </div>
                        <div class="col-lg-3">
                            <label> <span class="txRed">*</span>Fecha Desembolso: </label>
                            <div class='input-group date'>
                                <input id="fecha_desembolso" name="fecha_desembolso" class="datepicker form-control"   type="text" autofocus  >
                                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                            <label><span class="txRed">*</span> Primera Cuota: </label> <input type="text" name="primera_cuota" id="primera_cuota" class= " form-control" readOnly="readOnly"/>
							<input type="hidden" name="primera_cuota_hidden" id="primera_cuota_hidden" class= " form-control" />
                            <label><span class="txRed">*</span> Dia de la Semana : </label>
                            <input id="dia_semana" name="dia_semana"    type="text" class= " form-control" readonly="readonly" >

                            <label> <span class="txRed">*</span>Fecha Vencimiento: </label>
                            <div class='input-group date' id='datetimepicker1'>
                                <INPUT type="text" id="fecha_vencimiento" name="fecha_vencimiento" readonly="readonly" class= "form-control">
                                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                        <input type="hidden" name="fecha_de_vencimiento" id="fecha_de_vencimiento">
                            </div>

                            <label> <span class='txRed'>*</span>Deuda Total: </label> <INPUT type='text' name='deuda_total' id='deuda_total' class= 'form-control' readonly="readonly">
                        </div>
                         <div class="col-lg-3">
                            <label> <span class="txRed">*</span>Observacion: </label>
                            <textarea class="form-control" name="observacion" rows="4" id="observacion"></textarea>


                            <?php
                            $login=$_SESSION["Login_View"];
                            if($login->cargo == 'Operativo'){
                                echo "
                    <label><span class='txRed'>*</span>Descripcion Garantia:</label>   <input type='text' placeholder='0'  id='desc_garantia' name='desc_garantia' class='form-control'/>
                    <label>Imagen Garantia: </label><input name='garantiaimagen' id='garantiaimagen' accept='image/*'  type='file' />
                    <img id='img_garantia' src='' width='65%' height='65%'/><br>
                    <br>
                ";
                            }
                            ?>
                        </div>
                    </div>
                    </div> <!--TERMINA LA SOLICITUD DEL PRESTAMO-->
            <div id='ref_person' class='tab-pane fade'><br >
                <div class="row">
                    <!--div class="col-lg-12" -->
                    <div class="col-lg-4">
                        <label><span class="txRed">*</span>Nombre Referencia:</label> <input type="text"  name="nombre_referencia" class="form-control"/>
                        <label><span class="txRed">*</span>Direccion Referencia:</label> <input type="text"  name="direccion_referencia" class="form-control"/>
                        <label><span class="txRed">*</span>Telefono Referencia:</label> <input type="text"  placeholder="0000-0000" name="telefono_referencia" id="telefono_referencia" class="numero form-control"/>
                    </div>
                    <div class="col-lg-4">
                        <label><span class="txRed">*</span>Nombre Referencia Zonal:</label> <input type="text"  name="nombre_referencia_zonal" class="form-control"/>
                        <label><span class="txRed">*</span>Direccion Referencia Zonal:</label> <input type="text"  name="direccion_referencia_zonal" class="form-control"/>
                        <label><span class="txRed">*</span>Telefono Referencia Zonal:</label> <input type="text" placeholder="0000-0000" name="telefono_referencia_zonal" id="telefono_referencia_zonal" class="numero form-control"/>
                    </div>
                    <!--/div -->
                    <br ><br >
                </div>
            </div><!---//fin del conten referncia persona���les-->
            <div id='evaluacion' class='tab-pane fade'><br >
                <div class="row">
                    <div class="col-lg-4">
                        <label><span class="txRed">*</span>Tipo Negocio:</label> <input type="text" name="tipo_negocio" class="form-control"/>
                        <label><span class="txRed">*</span>Direccion Negocio:</label> <textarea class="form-control" name="direccion_negocio" rows="4" id="direccion_negocio"></textarea>
                        <label><span class="txRed">*</span>Tiempo en la Actividad(años):</label> <input type="text" placeholder="No años"name="tiempo_actividad" id="tiempo_actividad" class="numero form-control"/>
                        <label><span class="txRed">*</span>Ventas Maximas Diarias:</label> <input type="text" placeholder="0.0" name="ventas_max_diarias" id="ventas_max_diarias" class="numero form-control"/>
                        <label><span class="txRed">*</span>Ventas Normasles Diarias:</label> <input type="text" placeholder="0.0" name="ventas_normales_diarias" id="ventas_normales_diarias" class="numero form-control"/>
                        <label><span class="txRed">*</span>Ventas bajas Diarias:</label> <input type="text" placeholder="0.0" name="ventas_baja_diarias" id="ventas_baja_diarias" class="numero form-control"/>

                    </div>
                    <div class="col-lg-4">
                        <h4><span class="label label-success"> Fotos del Negocio</span></h4>
                        <label>Foto Negocio 1: </label><input name="garantia1" id="garantia1" accept="image/*" type="file"
                                                              onchange="upload_image('cliente_form','Cliente','save_image','garantia1','photo1','fotos_garantias','cargando1');return false;" /><div class="row" id="cargando1"></div>
                        <img id="pic1" src="assets/img/fotos_clientes/user.png" width="80%" height="80%" class="img-thumbnail" />
                        <input type="hidden" name="photo1" id="photo1"> <hr>

                        <label>Foto Negocio 2: </label><input name="garantia2" id="garantia2" accept="image/*"  type="file"
                                                              onchange="upload_image('cliente_form','Cliente','save_image','garantia2','photo2','fotos_garantias','cargando2');return false;" />
                        <div class="row" id="cargando2"></div>
                        <img id="pic2" src="assets/img/fotos_clientes/user.png" width="80%" height="80%" class="img-thumbnail" />
                        <input type="hidden" name="photo2" id="photo2"> <hr>

                    </div>

                </div>
            </div><!---//fin del conten Evaluacion -->
            <div id='deudas' class='tab-pane fade'><br >
                <h3>Entidad 1:</h3>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label><span class="txRed"></span>Nombre:</label> <input type="text" name="nombre_identidad_1" class="form-control noValidated"/>
                    </div>
                    <div class="col-md-2">
                        <label><span class="txRed"></span>Monto Otorgado:</label> <input type="text"  placeholder="0" name="monto_otrogado_identidad_1" id="monto_otrogado_identidad_1" id="monto_otrogado_identidad_1" class="form-control noValidated"/>
                    </div>
                    <div class="col-md-3">
                        <label><span class="txRed"></span>Frecuencia de pago:</label> 
                        <SELECT name="frecuencia_pago_identidad_1" id="frecuencia_pago_identidad_1" class="form-control noValidated">
                                <option value="0">-Seleccione-</option>
                                <option >Diarias</option>
                                <option >Semanal</option>
                                 <option >Quincenal</option>
                                <option >Mensual</option>
                            </SELECT>
                    </div>
                    <div class="col-md-2">
                        <label><span class="txRed"></span>Cuota de pago:</label> <input type="text" placeholder="0"  name="cuota_pago_identidad_1" id="cuota_pago_identidad_1" class="form-control noValidated"/>
                    </div>
                    <div class="col-md-2">
                        <label><span class="txRed"></span>Saldo:</label> <input type="text" placeholder="0"  name="saldo_identidad_1" id="saldo_identidad_1" class="form-control noValidated"/>
                    </div>
                </div>
                <h3>Entidad 2:</h3>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label><span class="txRed"></span>Nombre:</label> <input type="text" name="nombre_identidad_2" id="nombre_identidad_2" class="form-control noValidated"/>
                    </div>
                    <div class="col-md-2">
                        <label><span class="txRed"></span>Monto Otorgado:</label> <input type="text" placeholder="0"  name="monto_otrogado_identidad_2" id="monto_otrogado_identidad_2" id="monto_otrogado_identidad_2" class="form-control noValidated"/>
                    </div>
                    <div class="col-md-3">
                        <label><span class="txRed"></span>Frecuencia de pago:</label> 
                        <SELECT name="frecuencia_pago_identidad_2" id="frecuencia_pago_identidad_2" class="form-control noValidated">
                                <option value="0">-Seleccione-</option>
                                <option >Diarias</option>
                                <option >Semanal</option>
                                 <option >Quincenal</option>
                                <option >Mensual</option>
                            </SELECT>
                    </div>
                    <div class="col-md-2">
                        <label><span class="txRed"></span>Cuota de pago:</label> <input type="text" placeholder="0"  name="cuota_pago_identidad_2" id="cuota_pago_identidad_2" class="form-control noValidated"/>
                    </div>
                    <div class="col-md-2">
                        <label><span class="txRed"></span>Saldo:</label> <input type="text" placeholder="0"  name="saldo_identidad_2" id="saldo_identidad_2" class="form-control noValidated"/>
                    </div>
                </div>
                <hr>
                <h3>Entidad 3:</h3>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label><span class="txRed"></span>Nombre:</label> <input type="text" name="nombre_identidad_3" class="form-control noValidated"/>
                    </div>
                    <div class="col-md-2">
                        <label><span class="txRed"></span>Monto Otorgado:</label> <input type="text" placeholder="0"  name="monto_otrogado_identidad_3" id="monto_otrogado_identidad_3" class="form-control noValidated"/>
                    </div>
                    <div class="col-md-3">
                        <label><span class="txRed"></span>Frecuencia de pago:</label> 
                        <SELECT name="frecuencia_pago_identidad_3" id="frecuencia_pago_identidad_3" class="form-control noValidated">
                                <option value="0">-Seleccione-</option>
                                <option >Diarias</option>
                                <option >Semanal</option>
                                 <option >Quincenal</option>
                                <option >Mensual</option>
                            </SELECT>
                    </div>
                    <div class="col-md-2">
                        <label><span class="txRed"></span>Cuota de pago:</label> <input type="text" placeholder="0"  name="cuota_pago_identidad_3" id="cuota_pago_identidad_3" class="form-control noValidated"/>
                    </div>
                    <div class="col-md-2">
                        <label><span class="txRed"></span>Saldo:</label> <input type="text" placeholder="0"  name="saldo_identidad_3" id="saldo_identidad_3" class="form-control noValidated"/>
                    </div>
                </div>
                <br /><br /><br />
            </div><!---//fin del conten Dedudas -->
            <div id='garan_moviliarias' class='tab-pane fade'><br >
                <div class="row">
            <div class="col-xs-4"><h3>Garantias</h3> </div><div class="col-xs-3"></div><div class="col-lg-1"><input readonly class="btn btn-primary" name="counter" value="1" id="counter"></div>
        </div>
         <div id="prendasG">
         <div class="card">
            <div id="base" class="row">
                <div class="col-lg-4">
                    <label><span class="txRed"></span>Descripcion:</label>
                   <textarea class="form-control noValidated" name="descripcion_garantias0" rows="5" cols="30"></textarea>
                </div>
                <div class="col-lg-2">
                    <label><span class="txRed"></span>Precio Estimado:</label>
                   <input type="text" name="precio0" id="precio0"    placeholder="0.0" class="form-control noValidated"/>
                </div>
                <!--div class="col-lg-2">
                <label>Foto Garantia 1: </label><input name="prenda0" id="prenda0" accept="image/*"  type="file" 
                 onchange="upload_image('cliente_form','Cliente','save_image','prenda0','garan0','fotos_garantias','update0');return false;" />
                     <div class="row" id="update0"></div>
                     <img id="pren0" src="assets/img/fotos_clientes/user.png" width="40%" height="60%" class="img-thumbnail" />
                     <input type="hidden" name="garan0" id="garan0">
                </div-->
            </div>
        </div>
            <hr>
        </div>
        <br>
        <div class="row">
            <input type="hidden" id="typeForm" value="cliente_form" />
            <div class="col-lg-2"><i  class="btn btn-primary fa fa-plus-circle"  id="add_row"></i></div>
            <div class="col-lg-2"><i  class="btn btn-danger  fa fa-trash" id="sub_row"></i></div>
        </div>
            </div><!---//fin del conten garan_moviliarias -->
            <div id='fiador' class='tab-pane fade'><br >
                <div class="row">
                    <div class="col-lg-4">
                        <label><span class="txRed"></span>Nombre Fiador:</label> <input type="text" name="nombre_fiador" class="form-control noValidated"/>
                        <label><span class="txRed"></span>C&eacutedula Fiador:</label> <input type="text" placeholder="000-000000-0000A" name="cedula_fiador" id="cedula_fiador" class="form-control noValidated"/>
                        <label><span class="txRed"></span>Direcci&oacuten Fiador:</label> <textarea class="form-control noValidated" name="direccion_fiador" rows="4" id="direccion_fiador"></textarea>
                    </div>
                    <div class="col-lg-4">
                        <label><span class="txRed"></span>T&eacutelefono Fiador:</label> <input type="text"  placeholder="0000-0000" name="telefono_fiador" id="telefono_fiador" class="form-control noValidated"/>
                        <h4><span class="label label-success"> Foto C&eacutedula Fiador Frontal</span></h4>
                        <input name="imagen_cedula_frontal_fiador" id="imagen_cedula_frontal_fiador" accept="image/*"  type="file"
                               onchange="upload_image('cliente_form','Cliente','save_image','imagen_cedula_frontal_fiador','imagen_fiador_frontal','fotos_clientes','cargandocedulafiador1');return false;" />
                        <div class="row" id="cargandocedulafiador1"></div>
                        <input type="hidden" name="imagen_fiador_frontal" id="imagen_fiador_frontal"><br />
                        <h4><span class="label label-success"> Foto C&eacutedula Fiador Reversa</span></h4>
                        <input name="imagen_fiador_reversa" id="imagen_fiador_reversa" accept="image/*"  type="file"
                               onchange="upload_image('cliente_form','Cliente','save_image','imagen_fiador_reversa','imagen_fiador_reversa_ced','fotos_clientes','cargandocedulafiador2');return false;" />
                        <div class="row" id="cargandocedulafiador2"></div>
                        <input type="hidden" name="imagen_fiador_reversa_ced" id="imagen_fiador_reversa_ced"><br />
                    </div>
                </div>
            </div><!---//fin del conten fiador -->
        </div> <!---//fin contente-->
    </form>
</div>
<div id="contenedor"></div>

<div id="inferior">
    <input href="#" type="submit" value="Guardar" class="btn btn-success" onclick="validarEnviar('cliente_form','Cliente','crear');return false;"/>
    <!--input id="btn_prestamo" href="#" type="button" value="(+) Solicitud Prestamo" class="btn btn-success" onclick="showPrestamo('prestamo','btn_prestamo');return false;"/ -->
</div>
<script type="text/javascript"  src="assets/js/bootstrap-notify.js"></script>
<script type="text/javascript"  src="resources/js/cliente.js"></script>
<script type="text/javascript"  src="resources/js/prestamos.js"></script>
<script type="text/javascript"  src="assets/js/validaciones.js"></script>
<script type="text/javascript"  src="resources/js/garantias.js"></script>
<script type="text/javascript" src="resources/js/verificarCliente.js"></script>
<script src="assets/js/pace.min.js"></script>
