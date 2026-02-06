//para obtener los combos
function ComboDependent(value, option_id) {
    $('#'+option_id+' option').each(function() {this.selected = (this.value == value); });
}

function checkedRadioButton(value_, radio_id){
    $("input[name="+radio_id+"]").each(function (index) {
        if(this.value == value_ ){
            $(this).prop("checked", true);
        }
    });

}

function generateCombo(element, data, value, text1, text2,consulta) {
    var _data = JSON.parse(data);

    if(!("msg" in _data)){
      $('#msj_error').empty();
        element.empty();
        for (var x = 0; x < _data.length; x++) {

            element.append("<option value ='"+ _data[x][text1]+"-"+_data[x][value] +"'>"+ _data[x][text1]+"-"+_data[x][value] +"</option>");
        }
        element.append('<option value="Busqueda: '+consulta+'" title="Si no encuentra su resultado Sea mas especifico con su busqueda Coloque nombre completo+apellido Completo">Si no encuentra su resultado Sea mas especifico con su busqueda</option>');
    } else {
            $('#msj_error').html(_data['msg']);
    }

}
function generateComboSelect(element, data, value, text1, text2,consulta) {
    var _data = JSON.parse(data);

    if(!("msg" in _data)){
        $('#msj_error').empty();
        element.empty();
        element.append('<option value="" title="Debe Seleccionar un liente para realizar el represtamo">--Selecione un Cliente-</option>');
        for (var x = 0; x < _data.length; x++) {

            element.append("<option value ='"+ _data[x][text1]+"-"+_data[x][value] +"'>"+ _data[x][text1]+"-"+_data[x][value] +"</option>");
        }
        element.append('<option value="Busqueda: '+consulta+'" title="Si no encuentra su resultado Sea mas especifico con su busqueda Coloque nombre completo+apellido Completo">Si no encuentra su resultado Sea mas especifico con su busqueda</option>');
    } else {
        $('#msj_error').html(_data['msg']);
    }

}
function cleanChartEspecial() {
    $(".form-control").keypress(function (e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8) return true; // backspace
        if (tecla == 32) return true; // space
        patron = /\w/; //   patron =/[A-Za-z\s]/; patron solo letras
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    });
}

function getOptionCombo(controller, action, id_select, value, text1, text2,selected) {
    $.ajax({
        url: 'index.php?controller=' + controller + '&action=' + action + '',
        type: 'POST',
        success: function (result) {
            var data = JSON.parse(result);
            var option = $('#' + id_select + ' option');
            var select = $('#' + id_select);
            var op_selected;
            option.each(function () {if (this.value != 0 && this.value != 'All' ) {this.remove();}});
            for (var x = 0; x < data.length; x++) {
                op_selected = data[x][value] == selected? 'selected=selected':'';
                if (text2 != '') {
                    select.append('<option '+op_selected+' value ='+data[x][value]+'>'+data[x][text1] + ' ' + data[x][text2]+'</option>');
                } else {
                    select.append('<option '+op_selected+' value ='+data[x][value]+'>'+data[x][text1]+'</option>');
                }
            }
        }
    });
}

function SetOptionComboIdselect( id_select, value, text1,text2) {

    var option = $('#' + id_select + ' option');
    var select = $('#' + id_select);
    var op_selected;
    option.each(function () {if (this.value != 0 && this.value != 'All' ) {this.remove();}});

    op_selected =  'selected=selected';
    if (text2 != '')
        select.append('<option '+op_selected+' value ='+value+'>' + text1 + ' ' + text2 +'</option>');
    else
        select.append('<option '+op_selected+' value ='+value+'>' + text1+'</option>');
}

function getDataBytable(controller, action) {
    $.ajax({
        url: 'index.php?controller=' + controller + '&action=' + action + '',
        type: 'POST',
        success: function (result) {
            var data = JSON.parse(result);
        }
    });

}
function showPrestamo(id_div, id_button) {
    $("#" + id_div).toggle(function () {
        if ($("#" + id_div).css("display") == "block") {
            $("#" + id_button).val("(-) Prestamos");
            siValidarHidden(id_div);
        }
        else {
            $("#" + id_button).val("(+) Prestamos");
            noValidarHidden(id_div);
        }
    });
}

function noValidarHidden(id_div) {
    $('#' + id_div).find(':input').each(function () {
        $(this).addClass('noValidated');
    });
}
function siValidarHidden(id_div) {
    $('#' + id_div).find(':input').each(function () {
        $(this).removeClass('noValidated');
    });
}


function ImagenPreview(id,elemento_preview) {
    $('#'+id).on('change', function (e) {
        addImage(e);
    });

    function addImage(e){
        var file = e.target.files[0],
            imageType = /image.*/;

        if (!file.type.match(imageType))
            return;

        var reader = new FileReader();
        reader.onload = fileOnload;
        reader.readAsDataURL(file);
    }

    function fileOnload(e) {
        var result=e.target.result;
        $('#'+elemento_preview).attr("src",result);
    }
}



function getDateInLetters(fecha) { // fecha en format AAAA-MM-DD
    var fecha = obj.fecha_desembolso.split('-');
    var options = { year: 'numeric', month: 'long', day: 'numeric' };
    var fecha_format  = new Date(fecha[0],fecha[1],fecha[2]);

    return fecha_format.toLocaleDateString("es-ES", options);
}
