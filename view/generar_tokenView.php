<div class="card">
<div class="row">
        <div class="col-lg-6">
            <label>Usuario:</label>
            <input id="user" name="user" class=" form-control">
        </div>
        <div class="col-lg-6">
            <label>Password:</label>
            <input id="pass" name="pass" type="password" class="form-control">
            <input id="p" name="p" type="hidden" class="form-control">
        </div>
        <div class="col-lg-12">
            <label>Token:</label>
            <input id="token" name="token" type="text" class="form-control">
        </div>
    </div><br>
    <button type="button" id="generar_token" class="btn btn-success">Generar</button>
    <button type="button" id="copy" class="btn btn-warning">Copiar en portapapeles</button>
    <span class="text-danger">Este token solo es valido por 1 hora al caducar usted puede generar uno nuevo</span>
</div>
<script type="text/javascript" src="resources/js/generar-token.js"></script>
<script src="assets/js/bootstrap-notify.js"></script>

