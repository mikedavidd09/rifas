$("#Prestamos").on("change",function(){
	var valor = $(this).val();
	 //console.log($(this).is(":checked"));
	if($(this).is(":checked")){
             $("#tble_reporte_permiso tbody tr").each(function (index) {
                var campo1, campo2, campo3;
                $(this).children("td").each(function (index2) {
						if($(this).find("input").attr("data-label") == "Prestamos"){
							$(this).find("input").attr("disabled",false)
						}
               })

            })
	}else{
		$("#tble_reporte_permiso tbody tr").each(function (index) {
                var campo1, campo2, campo3;
                $(this).children("td").each(function (index2) {
						if($(this).find("input").attr("data-label") == "Prestamos"){
							$(this).find("input").attr("disabled",true)
						}
               })

            })
	}
});

$("#Gestores").on("change",function(){
	var valor = $(this).val();
	 //console.log($(this).is(":checked"));
	if($(this).is(":checked")){
             $("#tble_reporte_permiso tbody tr").each(function (index) {
                var campo1, campo2, campo3;
                $(this).children("td").each(function (index2) {
						if($(this).find("input").attr("data-label") == "Gestores"){
							$(this).find("input").attr("disabled",false)
						}
               })

            })
	}else{
		$("#tble_reporte_permiso tbody tr").each(function (index) {
                var campo1, campo2, campo3;
                $(this).children("td").each(function (index2) {
						if($(this).find("input").attr("data-label") == "Gestores"){
							$(this).find("input").attr("disabled",true)
						}
               })

            })
	}
});

$("#Clientes").on("change",function(){
	var valor = $(this).val();
	 //console.log($(this).is(":checked"));
	if($(this).is(":checked")){
             $("#tble_reporte_permiso tbody tr").each(function (index) {
                var campo1, campo2, campo3;
                $(this).children("td").each(function (index2) {
						if($(this).find("input").attr("data-label") == "Clientes"){
							$(this).find("input").attr("disabled",false)
						}
               })

            })
	}else{
		$("#tble_reporte_permiso tbody tr").each(function (index) {
                var campo1, campo2, campo3;
                $(this).children("td").each(function (index2) {
						if($(this).find("input").attr("data-label") == "Clientes"){
							$(this).find("input").attr("disabled",true)
						}
               })

            })
	}
});

$("#tble_reporte_permiso tbody tr td input").on("click",function (index) {
            var modulo = $(this).attr("data-label");
			 modulo = modulo.toLowerCase();
			var del;
			var moduloInidca = $("#modulo_"+modulo).val();
			
			 if($(this).is(":checked")){
				if(moduloInidca == "")
					moduloInidca = $(this).attr("name");
				else
					moduloInidca +="," + $(this).attr("name");
			 }else{
				del = $(this).attr("name");
				var i=0;
				var modnew="";
				var ModuloArra = moduloInidca.split(",");
				var count = ModuloArra.length;
				for(i=0; i< count;i++){
					if(i == 0){
						if(del != ModuloArra[i]){
							modnew = ModuloArra[i];
						}
						
					}else{
						if(del != ModuloArra[i]){
							if(modnew == "")
									modnew = ModuloArra[i];
							else
							modnew +=","+ModuloArra[i];
						}
					}
				}
				console.log(ModuloArra);
				console.log(del);
				moduloInidca=modnew;
			 }
			 console.log(moduloInidca);
			 $("#modulo_"+modulo).val(moduloInidca);
});