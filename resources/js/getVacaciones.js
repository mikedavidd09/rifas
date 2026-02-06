$.getScript("resources/js/jsDatatable.js", function () {
  DatatableJs(
    "#vacaciones",
    "index.php?controller=Vacaciones&action=vacacionesListado",
    0,
    [
      { targets: [0, 3], visible: false },
      {
        targets: 1,

        render: function (data, type, row, meta) {
          var itemID = row[0];
          return (
            '<a class="link" href="index.php?controller=Colaborador&action=ver_datos&obj=' +
            itemID +
            '">' +
            data +
            "</a>"
          );
        },
      },
      {
        targets: 5,
        render: function (data, type, row, meta) {
          if (data == 1) {
            return "Activo";
          } else {
            return "Inactivo";
          }
        },
      },
      {
        targets: 8,
        render: function (data, type, row, meta) {
          return toRound(data);
        },
      },
      {
        targets: 9,
        render: function (data, type, row, meta) {
          return toRound(data);
        },
      },
      {
        targets: 10,
        render: function (data, type, row, meta) {
          return toRound(data);
        },
      },
      {
        targets: 11,
        render: function (data, type, row, meta) {
          return toRound(data);
        },
      },
      {
        targets: 12,
        render: function (data, type, row, meta) {
          return (
            '<button class="btn btn-primary" id="agregarVacacion" data-id="' +
            data +
            '" data-nombre="' +
            row[1] +
            " " +
            row[1] +
            '" data-id_sucursal="' +
            row[3] +
            '"><i class="fa fa-plus"></i> </button>'
          );
        },
      },
      {
        targets: 13,
        render: function (data, type, row, meta) {
          return (
            '<button class="btn btn-danger" id="verVacacion" data-id="' +
            data +
            '" data-nombre="' +
            row[1] +
            '" "' +
            row[2] +
            '"><i class="fa fa-eye"></i> </button>'
          );
        },
      },
    ]
  );
});

$(document).on("click", "#agregarVacacion", function () {
  let id_colaborador = $(this).data("id");
  let nombre = $(this).data("nombre");
  let id_sucursal = $(this).data("id_sucursal");
  $("#id_colaborador").val(id_colaborador);
  $("#id_sucursal").val(id_sucursal);
  $("#nombre")
    .addClass("text-center")
    .html("<strong>" + nombre + "</strong>");
  $("#errorMensaje").html("");
  $("#modalAgregar").modal("show");
});

$(document).on("click", "#verVacacion", function () {
  let id_colaborador = $(this).data("id");
  let nombre = $(this).data("nombre");
  $("#id_colaborador").val(id_colaborador);
  $("#nombre2").val(nombre);
  $("#errorMensaje").html("");

  fillTable(
    "#tbodyVerVacaciones",
    "controller=Vacaciones&action=vacacionesByIdColaborador",
    id_colaborador
  );
  $("#modalVer").modal("show");
});

$(document).on("click", "#borrarVacacion", function () {
  let id_delete = $(this).data("id_delete");
  let id_table = $(this).data("id_table");
  let url = $(this).data("url");
  let id = $(this).data("id_update");
  deleteRow(id_delete, "controller=Vacaciones&action=eliminar").then(
    (result) => {
      if (result) {
        fillTable(id_table, url, id);
        reloadData("vacaciones");
      }
    }
  );
});

async function deleteRow(id_delete, url) {
  const confirmado = await confirmarEliminar();

  if (!confirmado) {
    showNotify("warning", "Cancelado", "Se cancela el proceso");
    return;
  }
  console.log("borrado confirmado");
  const response = await $.ajax({
    url: "index.php?" + url,
    type: "POST",
    data: {
      id_delete: id_delete,
    },
  });
  const data = await JSON.parse(response);
  if (data.respuesta == true) {
    showNotify("success", "Correcto", data.mensaje);
    return true;
  } else {
    showNotify("warning", "Error", data.mensaje);
    return false;
  }
}

function fillTable(id_table, url, id) {
  console.log("creando tabla ..." + id_table);
  $.ajax({
    url: "index.php?" + url,
    type: "POST",
    data: {
      id: id,
    },
    success: function (data) {
      let datos = JSON.parse(data);
      let html = "";
      $.each(datos, function (index, value) {
        html += "<tr>";
        html += "<td>" + value.id_vacaciones + "</td>";
        html += "<td>" + value.estado + "</td>";
        html += "<td>" + value.fecha_inicio + "</td>";
        html += "<td>" + value.fecha_final + "</td>";
        html += "<td>" + value.total_dias + "</td>";
        html += "<td>" + value.comentario + "</td>";
        html +=
          '<td><button class="btn btn-danger" id="borrarVacacion" data-id_delete="' +
          value.id_vacaciones +
          '" data-id_table="' +
          id_table +
          '" data-url="' +
          url +
          '" data-id_update="' +
          id +
          '"><i class="fa fa-trash"></i></button></td>';
        html += "</tr>";
      });
      $(id_table).html(html);
    },
  });
}

function confirmarEliminar() {
  return new Promise((resolve, reject) => {
    let confirmacion = alertify
      .confirm("Eliminar", "Seguro ?", null, null)
      .set("labels", {
        ok: "Si",
        cancel: "No",
      });
    confirmacion.set("onok", function () {
      resolve(true);
    });
    confirmacion.set("oncancel", function () {
      resolve(false);
    });
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

$('input[name="radio"]').change(function () {
  var tipoVacaciones = $(this).val();

  if (tipoVacaciones === "descansadas") $("#dias").attr("readonly", "readonly");
  else $("#dias").removeAttr("readonly");
});

$("#fecha_inicio").on("change", function () {
  if (
    $("#fecha_inicio").val().length != 0 &&
    $("#fecha_final").val().length != 0
  ) {
    let fecha1 = new Date($("#fecha_inicio").val());
    let fecha2 = new Date($("#fecha_final").val());
    if (fecha1 > fecha2) {
      $("#errorMensaje").html(
        "<div class='alert alert-danger' role='alert'>La fecha de inicio no puede ser mayor a la fecha final</div>"
      );
      $("#dias").val("");
      $("#fecha_inicio").val("");
      $("#fecha_final").val("");
      return;
    }
    $("#errorMensaje").html("");
    let dias = restarfechas(fecha1, fecha2);
    if (dias > 4) dias += 2;
    getNumDiaseriados({
      fecha_inicio: $("#fecha_inicio").val(),
      fecha_final: $("#fecha_final").val(),
      id_sucursal: $("#id_sucursal").val(),
    }).then((diasFeriados) => {
      dias = dias - diasFeriados;
      $("#dias").val(dias);
    });
  }
});

$("#fecha_final").on("change", function () {
  if (
    $("#fecha_inicio").val().length != 0 &&
    $("#fecha_final").val().length != 0
  ) {
    let fecha1 = new Date($("#fecha_inicio").val());
    let fecha2 = new Date($("#fecha_final").val());
    if (fecha1 > fecha2) {
      $("#errorMensaje").html(
        "<div class='alert alert-danger' role='alert'>La fecha de inicio no puede ser mayor a la fecha final</div>"
      );
      $("#fecha_inicio").val("");
      $("#fecha_final").val("");
      $("#dias").val("");
      return;
    }
    $("#errorMensaje").html("");
    let dias = restarfechas(fecha1, fecha2);
    if (dias > 4) dias += 2;
    getNumDiaseriados({
      fecha_inicio: $("#fecha_inicio").val(),
      fecha_final: $("#fecha_final").val(),
      id_sucursal: $("#id_sucursal").val(),
    }).then((diasFeriados) => {
      dias = dias - diasFeriados;
      $("#dias").val(dias);
    });
  }
});

function restarfechas(fecha1, fecha2) {
  return (fecha2 - fecha1) / (1000 * 60 * 60 * 24) + 1;
}

async function getNumDiaseriados(datos = {}) {
  let url = "controller=vacaciones&action=getNumDiasFeriados";
  let response = await $.ajax({
    url: "index.php?" + url,
    type: "POST",
    data: datos,
  });
  let data = await JSON.parse(response);
  return parseInt(data.dias);
}

function toRound(num) {
  return Math.round(parseFloat(num * 100)) / 100;
}
