function validateForm(id_form, url, page) {
  var validaion = validarFormulario(id_form);
  if (validaion) {
    guardarRegistro(id_form, url, page);
  }
}

function guardarRegistro(id_form, url, page) {
  var formData = new FormData($("#" + id_form)[0]);
  $.ajax({
    type: "POST",
    url: "index.php?" + url,
    data: formData,
    contentType: false,
    processData: false,
    cache: false,
    beforeSend: function () {
      $(".pace").removeClass("pace-inactive");
      $(".pace").addClass("pace-active");
    },
    success: function (response) {
      $(".pace").removeClass("pace-active");
      $(".pace").addClass("pace-inactive");
      if (response == 1 || response == true) {
        showNotify("success", "Correcto", "El registro se guardo con exito");
        $("#" + id_form)[0].reset();
        reloadData(page);
      } else {
        console.log(response);
        if (response == -50) {
          window.location.replace("index.php");
        } else {
          showNotify(
            "warning",
            "Error",
            "El registro no se puedo guardar " + response
          );
        }
      }
    },
  });
}

function reloadData(page) {
  $("#" + page)
    .DataTable()
    .ajax.reload(function () {
      $(".paginate_button > a").on("focus", function () {
        $(this).blur();
      });
    }, false);
}
function validarEnviar(id_form, controller, action, id) {
  var validaion = validarFormulario(id_form);
  if (validaion) {
    realizaGuardado(id_form, controller, action, id);
  }
}

function loging(id_form, controller) {
  var validaion = validarFormulario(id_form);
  if (validaion) {
    validationLoging(id_form, controller);
  } else {
    console.log("error");
  }
}
function validarEnviar_() {
  var id_form = $("form").attr("id");
  var url = $("#add").attr("href");
  var type = $("#add").attr("value");
  var validaion = validarFormulario(id_form);
  if (validaion) {
    realizaGuardado_(id_form, url, type);
    return true;
  } else return false;
}
function validarFormulario(id_form) {
  var todoCorrecto = true;
  var radio_button = false;
  $("#" + id_form)
    .find(":input")
    .each(function () {
      if (!$(this).hasClass("noValidated")) {
        var elemento = this;
        if (
          elemento.type == "password" ||
          elemento.type == "tel" ||
          elemento.type == "text" ||
          elemento.tagName == "TEXTAREA"
        ) {
          if (
            elemento.value == null ||
            elemento.value.length == 0 ||
            /^\s*$/.test(elemento.value)
          ) {
            elemento.style.border = "1px solid red";
            todoCorrecto = false;
          } else {
            elemento.style.border = "";
          }
        }
        console.log(elemento.tagName);
        console.log(elemento.style.border);
        if (elemento.tagName == "SELECT") {
          if (elemento.value == 0) {
            elemento.style.border = "1px solid red";
            todoCorrecto = false;
          } else {
            elemento.style.border = "";
          }
        }
        if (elemento.type == "radio") {
          if (!elemento.checked == true && !radio_button) {
            $("#div_btn_radio").addClass("div_error");
            todoCorrecto = false;
            radio_button = false;
          } else {
            todoCorrecto = true;
            radio_button = true;
            $("#div_btn_radio").removeClass("div_error");
          }
        }
      }
    });
  return todoCorrecto;
}

function realizaGuardado_(id_form, url, type) {
  $id = type == "Guardar" ? "" : $("#codigo").val();
  var formData = new FormData($("#" + id_form)[0]);
  var saldoN =
    id_form == "pagos_form"
      ? parseInt($("#saldo_pendiente").val()) - parseInt($("#mabonar").val())
      : 0;
  $.ajax({
    type: "POST",
    url: "index.php?" + url + "&id=" + $id,
    data: formData,
    contentType: false,
    processData: false,
    cache: false,
    beforeSend: function () {
      $(".pace").removeClass("pace-inactive");
      $(".pace").addClass("pace-active");
    },
    success: function (response) {
      $(".pace").removeClass("pace-active");
      $(".pace").addClass("pace-inactive");
      if (response == 1 || response == true) {
        showNotify("success", "Correcto", "El registro se guardo con exito");
        if (type == "Guardar") {
          if (id_form == "pagos_form") {
            $("#saldo_pendiente").val(saldoN);
          }
        }
        table.api().ajax.reload(function () {
          $(".paginate_button > a").on("focus", function () {
            $(this).blur();
          });
        }, false);
      } else {
        console.log(response);
        if (response == -50) {
          window.location.replace("index.php");
        } else {
          showNotify(
            "warning",
            "Error",
            "El registro no se puedo guardar " + response
          );
        }
      }
    },
  });
  if (type == "Guardar") {
    if (id_form == "pagos_form") {
      $("#mabonar").val("");
      $("#nrecibo").val("");
    } else {
      $("#" + id_form)[0].reset();
    }
  }
}
function realizaGuardado(id_form, controller, action, id) {
  var id_field = typeof id != "undefined" ? "&id=" + id : "";
  var formData = new FormData($("#" + id_form)[0]);
  $.ajax({
    type: "POST",
    url: "index.php?controller=" + controller + "&action=" + action + id_field,
    data: formData,
    contentType: false,
    processData: false,
    cache: false,
    beforeSend: function () {
      $(".pace").removeClass("pace-inactive");
      $(".pace").addClass("pace-active");
      $("#cover-spin").show();
    },
    success: function (response) {
      $(".pace").removeClass("pace-active");
      $(".pace").addClass("pace-inactive");
      var obj = JSON.parse(response);
      data = obj;
      if (obj.respuesta == "true") {
        showNotify("success", "Correcto", obj.mensaje);
        direccionarBandeja(action);
      } else {
        showNotify(
          "warning",
          "Error",
          "El registro no se puedo guardar " + obj.mensaje
        );
      }
      $("#cover-spin").hide();
    },
  });
  if (action == "crear") {
    $("#" + id_form)[0].reset();
    limpiarFotosGarantia(controller);
  }
  if (action == "crearagenda") {
    $("#" + id_form)[0].reset();
  }
  if (action == "crearcita") {
    $("#" + id_form)[0].reset();
    $("#listascitas").DataTable().draw();
    //$("#listascitas").dataTable().fnReloadAjax();
    //$('#listascitas').DataTable().ajax.reload();
    /*$("#myModal").removeClass("modal fade in");
        $("#myModal").addClass("modal fade");
        $("#myModal").attr("aria-hidden","true");
         $("#myModal").hiden();*/
  }
  if (action == "listanegra_form") {
    $("#" + id_form)[0].reset();
    $("#listaclientesnegras").DataTable().draw();
    //$("#listascitas").dataTable().fnReloadAjax();
    //$('#listascitas').DataTable().ajax.reload();
    /*$("#myModal").removeClass("modal fade in");
        $("#myModal").addClass("modal fade");
        $("#myModal").attr("aria-hidden","true");
         $("#myModal").hiden();*/
  }
}
function direccionarBandeja(action) {
  var tipobandeja = $("#tipobandeja").val();
  console.log("LLamando la direccion bandjea", tipobandeja);
  if (tipobandeja == "Aprobado") {
    var url = "index.php?controller=prestamo&action=getBandejaPrestamoAprobado";
    $.ajax({
      url: url,
    }).done(function (html) {
      $("#bandeja").html(html);
    });
  }
  if (tipobandeja == "Retornado") {
    var url =
      "index.php?controller=prestamo&action=getBandejaPrestamoRetornado";
    $.ajax({
      url: url,
    }).done(function (html) {
      $("#bandeja").html(html);
    });
  }
  if (tipobandeja == "Rechazado") {
    var url = "index.php?controller=prestamo&action=bandejaRechazados";
    $.ajax({
      url: url,
    }).done(function (html) {
      $("#bandeja").html(html);
    });
  }
  if (tipobandeja == "Comite") {
    if ((action = !"update")) {
      var url = "index.php?controller=prestamo&action=getBandejaPrestamoComite";
      $.ajax({
        url: url,
      }).done(function (html) {
        $("#bandeja").html(html);
      });
    }
  }
  if (tipobandeja == "index") {
    var url = "index.php?controller=prestamo&action=index";
    $.ajax({
      url: url,
    }).done(function (html) {
      $("#bandeja").html(html);
    });
  }
}
function realizarBorrado_(controller, id) {
  var enviar_post = $("form").attr("class");
  var id_form = $("form").attr("id");
  var url = $("#delete").attr("href");
  var id = $("#codigo").val();
  var formData;

  formData = new FormData($("#" + id_form)[0]);
  var confirm = alertify
    .confirm("Eliminar", "Seguro ?", null, null)
    .set("labels", {
      ok: "Si",
      cancel: "No",
    });
  confirm.set({ transition: "slide" });
  confirm.set("onok", function () {
    //callbak al pulsar botón positivo
    $.ajax({
      type: "POST",
      url: "index.php?" + url + "&id=" + id,
      data: formData,
      contentType: false,
      processData: false,
      cache: false,
      success: function (response) {
        console.log(response);
        if (response == true) {
          $("#div-form").remove();
          showNotify("success", "Correcto", "El registro se elimino con exito");
          table.api().ajax.reload(function () {
            $(".paginate_button > a").on("focus", function () {
              $(this).blur();
            });
          }, false);
        } else {
          var msj = response != "" ? response : "";
          showNotify(
            "warning",
            "Error",
            "El registro se no se puedo eliminar " + msj
          );
        }
      },
    });
  });
  confirm.set("oncancel", function () {
    //callbak al pulsar botón negativo
    alertify.error("No se eliminara el registro");
  });
  confirm.set("onnegativo", function () {
    //callbak al pulsar botón negativo
    alertify.error("Se cancela el proceso");
  });
}

function validationLoging(id_form, controller) {
  $.ajax({
    type: "POST",
    url: "index.php?controller=" + controller + "&action=crear",
    data: $("#" + id_form).serialize(),
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      obj = JSON.parse(response);
      console.log(obj.respuesta);
      if (obj.respuesta == "true") {
        $("#" + id_form).submit();
      } else {
        showNotify("danger", "Error", obj.mensaje);
      }
    },
  });
  $("#" + id_form)[0].reset();
}

function realizarBorrado(controller, id) {
  var id_field = id != "" ? "&id=" + id : "";

  var confirm = alertify
    .confirm("Eliminar", "Seguro ?", null, null)
    .set("labels", {
      ok: "Si",
      cancel: "No",
    });
  confirm.set({ transition: "slide" });
  confirm.set("onok", function () {
    //callbak al pulsar botón positivo
    $.ajax({
      type: "POST",
      url: "index.php?controller=" + controller + "&action=delete" + id_field,
      success: function (response) {
        console.log(response);
        console.log(response);
        var obj = JSON.parse(response);
        data = obj;
        if (obj.respuesta == "true") {
          $("#div-form").remove();
          showNotify("success", "Correcto", obj.mensaje);
        } else {
          showNotify("warning", "Error", obj.mensaje);
        }
      },
    });
  });
  confirm.set("oncancel", function () {
    //callbak al pulsar botón negativo
    alertify.error("No se eliminara el registro");
  });
  confirm.set("onnegativo", function () {
    //callbak al pulsar botón negativo
    alertify.error("Se cancela el proceso");
  });
}

function enviarDatosReportes(id_form, controller) {
  var formData = new FormData($("#" + id_form)[0]);
  var action = $("#tipo").val();
  var id_colaborador = $("#id_colaborador").val();
  var nombre_col = $("#id_colaborador option:selected").text();
  var div_conteiner = $("#conteiner_reportes");
  var dato_colaborador;
  if ($("#id_colaborador").length > 0) {
    dato_colaborador =
      "colaborador=" + id_colaborador + "&nombre_col=" + nombre_col;
  }
  if (validarFormulario(id_form)) {
    $.ajax({
      type: "POST",
      url:
        "index.php?controller=" +
        controller +
        "&action=" +
        action +
        "&" +
        dato_colaborador,
      data: formData,
      contentType: false,
      processData: false,
      beforeSend: function () {
        div_conteiner.html('<div class="loader"></div>');
      },
      success: function (response) {
        if (div_conteiner.html() != "") {
          div_conteiner.html(response);
        } else {
          div_conteiner.html(response);
        }
      },
    });
  }
}

function enviarReports(url) {
  console.log(url);
  var id_form = $("form").attr("id");
  var formData = new FormData($("#" + id_form)[0]);
  var div_conteiner = $("#conteiner_reportes");
  if (validarFormulario(id_form)) {
    $.ajax({
      type: "POST",
      url: "index.php?" + url,
      data: formData,
      contentType: false,
      processData: false,
      beforeSend: function () {
        div_conteiner.html('<div class="loader"></div>');
      },
      success: function (response) {
        if (div_conteiner.html() != "") {
          div_conteiner.html(response);
          HeaderCustom();
        } else {
          div_conteiner.html(response);
        }
      },
    });
  }
}
function HeaderCustom() {
  $filtro = $("body").find("#filter_by").val();
  console.log($filtro);
  if ($filtro == "Recaudado Fechas") {
    var fechaInicio = $("input[name='fecha_bengin_submit']").val();
    var fechaFin = $("input[name='fecha_end_submit']").val();
    var fechaInicio = new Date(fechaInicio);
    var fechaFin = new Date(fechaFin);
    $header = "";
    while (fechaInicio.getTime() <= fechaFin.getTime()) {
      console.log(
        fechaInicio.getFullYear() +
          "/" +
          (fechaInicio.getMonth() + 1) +
          "/" +
          fechaInicio.getDate()
      );
      $header +=
        "<th colspan='3'>" +
        fechaInicio.getFullYear() +
        "-" +
        (fechaInicio.getMonth() + 1) +
        "-" +
        fechaInicio.getDate() +
        "</th>";
      fechaInicio.setDate(fechaInicio.getDate() + 1);
    }
    $(".header_reports").prepend("<tr>" + "<th></th>" + $header + "</tr>");
  } else if ($filtro == "Prestamo_PorDia") {
    $(".header_reports").prepend(
      "<tr>" +
        "<th></th><th></th><th colspan='3'>Lunes</th><th colspan='3'>Martes</th><th colspan='3'>Miercoles</th><th colspan='3'>Jueves</th><th colspan='3'>Viernes</th></tr>"
    );
  }
}

function showNotify(type, label_type, Menssege) {
  $.notify(
    {
      title: "<strong>" + label_type + "!</strong>",
      message: Menssege,
    },
    {
      type: type,
      animate: {
        enter: "animated bounceInDown",
        exit: "animated bounceOutUp",
      },
    }
  );
}

function limpiarFotosGarantia(controller) {
  if (controller == "Cliente") {
    $("#img_cliente").attr("src", "assets/img/user.png");
    $("#pic1").attr("src", "");
    $("#pic2").attr("src", "");
    $("#pic3").attr("src", "");
    $("#pic4").attr("src", "");
    $("#pic5").attr("src", "");
    $("#pic6").attr("src", "");
  } else if (controller == "Prestamo") {
    $("#pic1").attr("src", "");
    $("#pic2").attr("src", "");
    $("#pic3").attr("src", "");
  }
}
