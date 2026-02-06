/**
 * Created by PhpStorm.
 * User: ruiz
 * Date: 03-04-18
 * Time: 09:16 PM
 */

var publicador = {
    subscriptors: [],

    subscribe: function (subscriptor) {
        this.subscriptors.push(subscriptor);
    },
    unsubscribe : function () {
        this.subscriptors = this.subscriptors.filter( item => item !== subscriptor );
    },
    notify: function (obj) {
        console.log(this.subscriptors);
        this.subscriptors.forEach(item => {item.call('', obj);});
        ;
    }
};

function input_error_live(obj) {
    validaciones.msg_input_error(obj);
}

$(function () {
    var index = (function () {
        var _DOM = {
            $body: ""
        };
        function init() {
            cache_dom();
            asignar_eventos();
        }
        function cache_dom() {
            _DOM.$body=$('body');
        }

        function asignar_eventos(){
            //LLamada a las validaciones.
            _DOM.$body.on("keyup", ".letras", function (e) {
                publicador.subscribe(input_error_live);
                publicador.notify({
                    flat: validaciones.letras(e, $(this)),
                    selector: $(this),
                    tooltip: {
                        msg: 'Este campo debe ser llenado solo con letras [A-Z],[a-z]',
                        color: 'red-tooltip',
                        posicion: 'top'
                    }
                });
            });

            _DOM.$body.on("keyup", ".numeros", function (e) {
                publicador.subscribe(input_error_live);
                publicador.notify({
                    flat: validaciones.numeros(e, $(this)),
                    selector: $(this),
                    tooltip: {
                        msg: 'Este campo debe ser llenado solo con numeros [0-9]',
                        color: 'red-tooltip',
                        posicion: 'top'
                    }
                });
            });

            _DOM.$body.on("keyup", ".decimales", function (e) {
                publicador.subscribe(input_error_live);
                publicador.notify({
                    flat: validaciones.decimales($(this)),
                    selector: $(this),
                    tooltip: {
                        msg: 'Este campo debe ser llenado con numeros decimales [0.00000]',
                        color: 'red-tooltip',
                        posicion: 'top'
                    }
                });
            });

        }

        return{
            init:init
        }

    })();

    index.init();
});



var validaciones = {
    validar_formularios: function (id_form) {
        var error = true;
        var valido = true;

        $(id_form).find('.form-control:not(.no_requerido) ').each(function (index) {
            if (validaciones.vacio($(this)) || $(this).hasClass('is-invalid')) {
                $('.alert').remove();
                validaciones.input_red({flat: false, selector: $(this)});
                console.log($(this).attr('id'));
                error = false;
            }
            else {
                validaciones.input_red({flat: true, selector: $(this)});
                console.log($(this).attr('id'));
                valido = true;
            }
        });
        if (error && valido)
            $('.alert').remove();
        else
            $(id_form).append("<div class='alert alert-danger'><strong>Error!</strong> Debe rellenar campos Marcados en rojo.</div>");
        return (error && valido);
    },
    numeros: function (e, selector) {
        var regex = /^([0-9])*$/;
        var key = window.Event ? e.which : e.keyCode;
        console.log((!(regex.test(selector.val()))));
        return ((key >= 48 && key <= 57) || (key == 8) || (key == 0)) && ((regex.test(selector.val())));
    },
    decimales: function (selector) {
        var regex = /^\d+(\.\d{0,4})?$/g;
        return (regex.test(selector.val())) ? true : false;
    },
    letras: function (e, selector) {
        var regex = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;
        var key = window.Event ? e.which : e.keyCode;
        return (!((key < 97 || key > 122)//letras mayusculas
            && (key < 65 || key > 90) //letras minusculas
            && (key != 45) //retroceso
            && (key != 241) //ñ
            && (key != 209) //Ñ
            && (key != 32) //espacio
            && (key != 225) //á
            && (key != 233) //é
            && (key != 237) //í
            && (key != 243) //ó
            && (key != 250) //ú
            && (key != 193) //Á
            && (key != 201) //É
            && (key != 205) //Í
            && (key != 211) //Ó
            && (key != 218) //Ú
            && (key != 8)//del
            && (key != 0)//derecha izquierda
            && (key != 37)
            && (key != 39)
            && (key != 46)
        ) && (regex.test(selector.val())) );
    },
    vacio: function (selector) {
        return ((selector.val() != undefined) && (selector.val() == "")) ? true : false;
    },
    fecha: function (selector) {
        var accept = (selector.val()).match(/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/);
        return (!accept);
    },
    msg_input_error: function (obj) {
        console.log(obj.flat);
        if (!obj.flat) {
            obj.selector.parent('div').addClass('has-error');
            validaciones.tooltip_custom(obj);
        }
        else
            obj.selector.parent('div').removeClass('has-error');
    },
    input_red: function (obj) {
        if (!obj.flat) {
            obj.selector.parent('div').addClass('has-error');
        }
        else
            obj.selector.parent('div').removeClass('has-error');
    },
    tooltip_custom: function (obj) {
        obj.selector.addClass(obj.tooltip.color);
        obj.selector.attr("data-toggle", "tooltip");
        $('[data-toggle="tooltip"]').tooltip({title: obj.tooltip.msg, placement: obj.tooltip.posicion});
    }


};





