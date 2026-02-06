ImagenPreview('imagen','img_cliente');
ImagenPreview('imagen_cedula_reversa','img_cliente_reverse');
ImagenPreview('imagen_vienda','img_cliente_vivienda');
ImagenPreview('garantia1','pic1');
ImagenPreview('garantia2','pic2');
ImagenPreview('garantia3','pic3');
ImagenPreview('garantia4','pic4');
ImagenPreview('garantia5','pic5');
ImagenPreview('garantia6','pic6');

$('.div_oculto').find(':input').each(function () {
    $(this).addClass('noValidated');
});
getOptionCombo('Colaborador','dataByComboAnalista','id_colaborador','id_colaborador','nombre','apellido','');
getOptionCombo('Sucursal', 'dataByComboSucursal', 'id_sucursal', 'id_sucursal', 'sucursal', '', '');
getOptionCombo('intereses', 'dataByComboInteres', 'id_interes', 'id_interes', 'porcentaje', '', '');

$("#cedula").mask("999-999999-9999S");
$("#cedula_fiador").mask("999-999999-9999S");
$("#telefono").mask("9999-9999");
$("#telefono_1").mask("9999-9999");
$("#telefono_fiador").mask("9999-9999");
$("#edad").mask("99");
$("#n_persona_cargo").mask("99");
$("#cedulafiador").mask("999-999999-9999S");
$("#telefono_referencia").mask("9999-9999");
$("#telefono_referencia_zonal").mask("9999-9999");

    $(".form-control").keypress(function (e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8) return true; // 3
        if (tecla==32) return true; 
        patron = /\w/; //   patron =/[A-Za-z\s]/; patron solo letras
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    });
$( "#cedula" ).change(function() {
    validationsForCedula();
});


function validationsForCedula($this) {
    var cedula = $("input[name='cedula']").val();
    $.ajax({
        type: 'POST',
        url: 'index.php?controller=Cliente&action=validationsForCedula&cedula=' + cedula,
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            console.log(obj);
            if (obj.respuesta) {
                console.log(Array.isArray(obj));
                var url = 'index.php?controller=Cliente&action=ver_datos&obj=' + obj.data.id_cliente;

                alertify.alert().set('message', "Un cliente con esta Cedula :" + obj.data.cedula + " Ya existe en el sistema!!! = " + obj.data.nombre + " " + obj.data.apellido + " <br/><br/><br/>" +
                    "Click Para ver detalles del Usuario: " +
                    "<a class='link' href=" + url + "> " + obj.data.codigo_cliente + " </a>").show();

            }

        }
    });

}

var elementoPadre1 = document.querySelector(".inputDiv.i1");
var inputsRy = [];

function Input() {
    //<input type="range" value="35" min="0" max="100" autocomplete="off" step="1">
    this.att = {};
    this.att.type = "range";
    this.att.value = 1000;
    this.att.min = 0;
    this.att.max = 50000;
    this.att.autocomplete = "off";
    this.att.step = "1000";
    this.input;
    this.output;

    this.crear = function(elementoPadre) {
        // crea un nuevo elemento input
        this.input = document.createElement("input");
        //para cada propiedad del objeto att establece un nuevo atributo del elemento input
        for (var name in this.att) {
            if (this.att.hasOwnProperty(name)) {
                this.input.setAttribute(name, this.att[name]);
            }
        }
        // crea un nuevo elemento div
        this.output = document.createElement("input");
        // establece el valor del atributo class del nuevo div
        this.output.setAttribute("class", "output");
        this.output.setAttribute("class", "form-control");
        this.output.setAttribute("id", "capital_max");
        this.output.setAttribute("name", "capital_max");
        this.output.setAttribute("placeholder", "C$");
        this.output.setAttribute("min", "1000");
        this.output.setAttribute("max", "50000");
        this.output.setAttribute("type", "number");
        // y el contenido (innerHTML) de este
        this.output.value = this.att.value;

        // inserta los dos elementos creados al final  del elemento Padre
        elementoPadre.appendChild(this.input);
        elementoPadre.appendChild(this.output);
        this.outputSpan = document.createElement("span");
        this.outputSpan.setAttribute("class", "input-group-addon");
        var textNode = document.createTextNode("C$");
        this.outputSpan.appendChild(textNode);
        elementoPadre.appendChild(this.outputSpan);
    }

    this.actualizar = function() {
        this.output.value = this.input.value;
        this.att.value = this.input.value;
    }
}

// setup
/*
var i = new Input();
i.crear(elementoPadre1);
inputsRy.push(i);
//i2.crear(elementoPadre2);
//inputsRy.push(i2);

for (var n = 0; n < inputsRy.length; n++) {
    (function(n) {
        inputsRy[n].input.addEventListener("input", function() {
            inputsRy[n].actualizar();
        }, false)
    }(n));
}

/* Draw
 function Draw(){
 requestId = window.requestAnimationFrame(Draw);
 for( var n = 0; n< inputsRy.length; n++){
 inputsRy[n].update();
 }
 }

 requestId = window.requestAnimationFrame(Draw);
 */
// JavaScript Document



