$("#btn-informe").on("click",function(){
	var mes = $("#mes").val();
	var anho = $("#anho").val();
    var auxmesLetra =$("#mes option:selected").text();
    var mesLetra =auxmesLetra.toLowerCase();
    var periodo = mesLetra.substr(0,3)+"-"+anho.substr(2,2);

	var data={
			 		'mes':mes,
			 		'anho':anho
			 };

             $.ajax({
        type: 'POST',
        url: 'index.php?controller=Colaborador&action=getInformeCierre',
        data:data,
        beforeSend: function () {
            $('.pace').removeClass('pace-inactive');
            $('.pace').addClass('pace-active');
            $('#cover-spin').show();

        },
        success: function (response) {
            $('.pace').removeClass('pace-active');
            $('.pace').addClass('pace-inactive');
            console.log(response);
            var obj = JSON.parse(response);
            console.log(obj);
            data = obj;
            $('#informe-1 > tbody').empty();
            $("#informe-2 > tbody").empty();
            $("#informe-3 > tbody").empty();
            $("#informe-4 > tbody").empty();
            $("#informe-5 > tbody").empty();
            if(obj.respuesta == "true"){
                obj.informeCierre.capital_inicial
                var interes_recuperado_bruto = parseFloat(((parseFloat(obj.informeCierre.capital_inicial)-parseFloat(obj.informeCierre.reserva)-parseFloat(obj.informeCierre.capital_riesgo))*(parseFloat(obj.informeCierre.interes)/100)));
               console.log(interes_recuperado_bruto);
               $(".titleinfromeBruto").html(NumeroALetras(interes_recuperado_bruto)); 

               var interes_recuperado_neto = parseFloat((parseFloat(obj.informeCierre.capital_inicial)-parseFloat(obj.informeCierre.reserva)-parseFloat(obj.informeCierre.capital_riesgo))*(parseFloat(obj.informeCierre.interes)/100)-16000);
               $(".titleinfromeNeto").html(NumeroALetras(interes_recuperado_neto)); 
               
                $("#informe-1").append("<tbody><tr><td>"+obj.informeCierre.nombre+"</td><td>"+parseFloat(obj.informeCierre.capital_inicial).toLocaleString("en-US")+"</td><td class='danger'> ("+parseFloat(obj.informeCierre.capital_riesgo).toLocaleString("en-US")+")</td><td>"+parseFloat(obj.informeCierre.reserva).toLocaleString("en-US")+"</td><td>"+parseFloat(parseFloat(obj.informeCierre.capital_inicial)-parseFloat(obj.informeCierre.reserva)-parseFloat(obj.informeCierre.capital_riesgo)).toLocaleString("en-US")+"</td><td>"+obj.informeCierre.interes+" %</td><td>"+parseFloat(((parseFloat(obj.informeCierre.capital_inicial)-parseFloat(obj.informeCierre.reserva)-parseFloat(obj.informeCierre.capital_riesgo))*(parseFloat(obj.informeCierre.interes)/100))).toLocaleString("en-US")+"</td><td>16,000</td><td>"+parseFloat((parseFloat(obj.informeCierre.capital_inicial)-parseFloat(obj.informeCierre.reserva)-parseFloat(obj.informeCierre.capital_riesgo))*(parseFloat(obj.informeCierre.interes)/100)-16000).toLocaleString("en-US")+"</td></tr></tbody>");
                $("#informe-2").append("<tbody><tr><td>"+obj.informeCierre.nombre+"</td><td>"+parseFloat(obj.informeCierre.capital_inicial).toLocaleString("en-US")+"</td><td>"+parseFloat(obj.informeCierre.capital_riesgo).toLocaleString("en-US")+"</td><td>"+parseFloat(obj.informeCierre.reserva).toLocaleString("en-US")+"</td><td>"+parseFloat(parseFloat(obj.informeCierre.capital_inicial)-parseFloat(obj.informeCierre.reserva)-parseFloat(obj.informeCierre.capital_riesgo)).toLocaleString("en-US")+"</td><td>"+obj.informeCierre.interes+" %</td><td>"+parseFloat(((parseFloat(obj.informeCierre.capital_inicial)-parseFloat(obj.informeCierre.reserva)-parseFloat(obj.informeCierre.capital_riesgo))*(parseFloat(obj.informeCierre.interes)/100))).toLocaleString("en-US")+"</td><td>16,000</td><td>"+parseFloat((parseFloat(obj.informeCierre.capital_inicial)-parseFloat(obj.informeCierre.reserva)-parseFloat(obj.informeCierre.capital_riesgo))*(parseFloat(obj.informeCierre.interes)/100)-16000).toLocaleString("en-US")+"</td></tr></tbody>");

                var tr1  ="<tr>";
                    tr1 += "<td>"+obj.informeCierre.nombre+"</td>";
                    tr1 += "<td>50 %</td>";
                    tr1 += "<td>"+obj.informeCierre.utilidad+"</td>";

                    tr1 += "<td>"+obj.informeCierre.utilidad+"</td>";
                    tr1 += "<td>--</td>z/tr>";

                var tr2  ="<tr>";
                    tr2 += "<td>OLIVER BLANCO</td>";
                    tr2 += "<td>50 %</td>";
                    tr2 += "<td>"+obj.informeCierre.utilidad+"</td>";

                    tr2 += "<td>"+obj.informeCierre.utilidad+"</td>";
                    tr2 += "<td>--</td></tr>";

                $("#informe-3").append("<tbody>"+tr1+"</tbody>");
                $("#informe-3").append("<tbody>"+tr2+"</tbody>");

                $("#informe-4").append("<tbody>"+tr1+"</tbody>");
                $("#informe-4").append("<tbody>"+tr2+"</tbody>");

                var read = obj.informeHistorialAU.length;
                var i=0;

                for (var i = 0; i < read; i++) {
                    console.log(periodo,"==",obj.informeHistorialAU[i].mes);
                    if(periodo != obj.informeHistorialAU[i].mes){
                        tr1  ="<tr>";
                        tr1 += "<td>"+obj.informeHistorialAU[i].mes+"</td>";
                        tr1 += "<td>"+obj.informeHistorialAU[i].capital+"</td>";
                        tr1 += "<td>"+obj.informeHistorialAU[i].utilidad+"</td>";

                        tr1 += "<td>"+obj.informeHistorialAU[i].pagado+"</td></tr>";
                       // tr1 += "<td>--</td>z/tr>";
                        $("#informe-5").append("<tbody>"+tr1+"</tbody>");
                     }else{
                           tr1  ="<tr>";
                        tr1 += "<td>"+obj.informeHistorialAU[i].mes+"</td>";
                        tr1 += "<td>"+obj.informeHistorialAU[i].capital+"</td>";
                        tr1 += "<td>"+obj.informeHistorialAU[i].utilidad+"</td>";

                        tr1 += "<td>"+obj.informeHistorialAU[i].pagado+"</td></tr>";
                       // tr1 += "<td>--</td>z/tr>";
                        $("#informe-5").append("<tbody>"+tr1+"</tbody>"); 
                        break;
                     }
                }

            }

            $(".updateLetracierremes").html(obj.UltimoDia+" de "+auxmesLetra+" del año "+anho);
             $(".mesiniciofinal").html("1 de "+auxmesLetra+" al "+obj.UltimoDia+" del "+anho);
            
            $('#cover-spin').hide();
        }
    });

	return false;
});

$("#mes").on("change",function(){
	var mes = $(this).val();
	var anho = $("#anho").val();
	console.log(mes,anho);
	if(mes != "0"){
		if(anho != "0"){
			//aquie se ahora el cambio en los encabezados
			var textomes = $("#mes option:selected").text();
			console.log(textomes.toUpperCase()+" "+anho);
			$(".updateperiodo").text(textomes.toUpperCase()+" "+anho);
		}
	}
});

$("#anho").on("change",function(){
	var anho = $(this).val();
	var mes = $("#mes").val();
	console.log(mes,anho);
	if(anho != "0"){
		if(mes != "0"){
			//aquie se ahora el cambio en los encabezados
			var textomes = $("#mes option:selected").text();
			console.log(textomes.toUpperCase()+" "+anho);
			$(".updateperiodo").text(textomes.toUpperCase()+" "+anho);
		}
	}
});


function Unidades(num){

    switch(num)
    {
        case 1: return "UN";
        case 2: return "DOS";
        case 3: return "TRES";
        case 4: return "CUATRO";
        case 5: return "CINCO";
        case 6: return "SEIS";
        case 7: return "SIETE";
        case 8: return "OCHO";
        case 9: return "NUEVE";
    }

    return "";
}//Unidades()
Decenas(40800);
function Decenas(num){

    decena = Math.floor(num/10);
    unidad = num - (decena * 10);

    console.log(decena);
    console.log(unidad);

    switch(decena)
    {
        case 1:
            switch(unidad)
            {
                case 0: return "DIEZ";
                case 1: return "ONCE";
                case 2: return "DOCE";
                case 3: return "TRECE";
                case 4: return "CATORCE";
                case 5: return "QUINCE";
                default: return "DIECI" + Unidades(unidad);
            }
        case 2:
            switch(unidad)
            {
                case 0: return "VEINTE";
                default: return "VEINTI" + Unidades(unidad);
            }
        case 3: return DecenasY("TREINTA", unidad);
        case 4: return DecenasY("CUARENTA", unidad);
        case 5: return DecenasY("CINCUENTA", unidad);
        case 6: return DecenasY("SESENTA", unidad);
        case 7: return DecenasY("SETENTA", unidad);
        case 8: return DecenasY("OCHENTA", unidad);
        case 9: return DecenasY("NOVENTA", unidad);
        case 0: return Unidades(unidad);
    }
}

function DecenasY(strSin, numUnidades) {
    if (numUnidades > 0)
    return strSin + " Y " + Unidades(numUnidades)

    return strSin;
}//DecenasY()

function Centenas(num) {
    centenas = Math.floor(num / 100);
    decenas = num - (centenas * 100);

    switch(centenas)
    {
        case 1:
            if (decenas > 0)
                return "CIENTO " + Decenas(decenas);
            return "CIEN";
        case 2: return "DOSCIENTOS " + Decenas(decenas);
        case 3: return "TRESCIENTOS " + Decenas(decenas);
        case 4: return "CUATROCIENTOS " + Decenas(decenas);
        case 5: return "QUINIENTOS " + Decenas(decenas);
        case 6: return "SEISCIENTOS " + Decenas(decenas);
        case 7: return "SETECIENTOS " + Decenas(decenas);
        case 8: return "OCHOCIENTOS " + Decenas(decenas);
        case 9: return "NOVECIENTOS " + Decenas(decenas);
    }

    return Decenas(decenas);
}//Centenas()

function Seccion(num, divisor, strSingular, strPlural) {
    cientos = Math.floor(num / divisor)
    resto = num - (cientos * divisor)
console.log("math floor en seccion",num,divisor,Math.floor(num / divisor));
    letras = "";

    if (cientos > 0)
        if (cientos > 1)
            letras = Centenas(cientos) + " " + strPlural;
        else
            letras = strSingular;

    if (resto > 0)
        letras += "";

    return letras;
}//Seccion()

function Miles(num) {
    divisor = 1000;
    cientos = Math.floor(num / divisor)
    resto = num - (cientos * divisor)

    strMiles = Seccion(num, divisor, "UN MIL", "MIL");
    strCentenas = Centenas(resto);

    if(strMiles == "")
        return strCentenas;

    return strMiles + " " + strCentenas;
}//Miles()

function Millones(num) {
    divisor = 1000000;
    cientos = Math.floor(num / divisor)
    resto = num - (cientos * divisor)
console.log("math floor  en millones",Math.floor(num/divisor));
    strMillones = Seccion(num, divisor, "UN MILLON DE", "MILLONES DE");
    strMiles = Miles(resto);

    if(strMillones == "")
        return strMiles;

    return strMillones + " " + strMiles;
}//Millones()

function NumeroALetras(num) {
    var data = {
        numero: num,
        enteros: Math.floor(num),
        centavos: (((Math.round(num * 100)) - (Math.floor(num) * 100))),
        letrasCentavos:"",
        letrasMonedaPlural: 'CORDOBAS',//"PESOS", 'Dólares', 'Bolívares', 'etcs'
        letrasMonedaSingular: 'CORDOBA', //"PESO", 'Dólar', 'Bolivar', 'etc'

        letrasMonedaCentavoPlural: "CENTAVOS",
        letrasMonedaCentavoSingular: "CENTAVO"
    };
    	console.log("math floor ",Math.floor(num));
    if (data.centavos > 0) {
        /*data.letrasCentavos = "CON " + (function (){
            if (data.centavos == 1)
                return Millones(data.centavos) + " " + data.letrasMonedaCentavoSingular;
            else
                return Millones(data.centavos) + " " + data.letrasMonedaCentavoPlural;
            })();*/
            data.letrasCentavos = "CON " + data.centavos+"/100"+ " " + data.letrasMonedaCentavoPlural;
    };

    if(data.enteros == 0)
        return "CERO " + data.letrasMonedaPlural + " " + data.letrasCentavos;
    if (data.enteros == 1)
        return Millones(data.enteros) + " " + data.letrasMonedaSingular + " " + data.letrasCentavos;
    else
        return Millones(data.enteros) + " " + data.letrasMonedaPlural + " " + data.letrasCentavos;
}//NumeroALetras()
