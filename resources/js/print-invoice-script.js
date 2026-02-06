/**
 * Created by fleming on 4/6/22.
 */



$( "#saveCuota" ).on( "click", function() {
    var abono = $("#monto").val();
    $(".monto").val(abono);
});