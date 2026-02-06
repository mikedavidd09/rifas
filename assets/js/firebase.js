
$.getScript('https://www.gstatic.com/firebasejs/live/3.1/firebase.js', function(){
    (function(){

        const config ={
            apiKey:"AIzaSyBZ9jb0dVm7fSrdaMgIJlnsLZtz3a8Al8U",
            authDomain:"dbfirebase-d7e4e.firebaseapp.com",
            databaseURL:"https://dbfirebase-d7e4e.firebaseio.com/",
            storageBucket:"dbfirebase-d7e4e.appspot.com",
        }

        firebase.initializeApp(config);



        const dbRefObject = firebase.database().ref().child('dbfirebase');

            dbRefObject.on('child_changed',snap=>{
                //console.log(snap.val());
                Push.create(snap.val().colaborador, {
                body: "Nombre_Cliente:"+snap.val().nombre+" Codigo:"+snap.val().codigo+" Monto:C$"+snap.val().monto,
                icon: '/assets/img/fotos_colaborador/'+snap.val().imagen,
                timeout: 20000,
                onClick: function () {
                    window.focus();
                    var link =snap.val().url+snap.val().id;
                    $.ajax({
                        url: link
                    }).done(function (html) {
                        $('#page').html(html);
                    });
                    this.close();
                    return false;

                }
            });
        });

        const dbRefObjectCliente = firebase.database().ref().child('dbfirebase_');

        dbRefObjectCliente.on('child_changed',snap=>{
                //console.log(snap.val());
                Push.create(snap.val().colaborador, {
                body: "Nombre_Cliente:"+snap.val().nombre+" Codigo_Cliente:"+snap.val().codigo,
                icon: '/assets/img/fotos_colaborador/'+snap.val().imagen,
                timeout: 20000,
                onClick: function () {
                    window.focus();
                    var link =snap.val().url+snap.val().id;
                    $.ajax({
                        url: link
                    }).done(function (html) {
                        $('#page').html(html);
                    });
                    this.close();
                    return false;

                }
            });
        });


        const dbRefObject2 = firebase.database().ref().child('bandeja');
        dbRefObject2.on('value',snap=>{
            $(".badge").text(snap.numChildren());
        });

        const dbRefObject4 = firebase.database().ref().child('avisos');
        dbRefObject4.on('child_changed',snap=>{
                Push.create(snap.val().titulo, {
                body: snap.val().mensaje,
                icon: '/assets/img/fotos_colaborador/'+snap.val().imagen,
                timeout: 60000,
                onClick: function () {
                    window.focus();
                    if(snap.val().redireccionar == 'si'){
                        var link =snap.val().url;
                        $.ajax({
                            url: link
                        }).done(function (html) {
                            $('#page').html(html);
                        });
                    }
                    this.close();
                    return false;

                }
            });
            $('.modal-title').text(snap.val().titulo);
            $('#img').attr('src',snap.val().imagen);
            $('#mensaje').text(snap.val().mensaje);
            $("#avisos").modal({backdrop: 'static', keyboard: false})
    });

        const dbRefObject3 = firebase.database().ref().child('arqueo');
        dbRefObject3.on('child_changed',snap=>{
            $.ajax({
            type: 'GET',
            url: 'index.php?controller=Usuarios&action=arqueoCerrarSession&id_colaborador='+snap.val().id_colaborador,
            success: function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                obj=JSON.parse(response);
                if(obj.respuesta == "true"){
                    $('.modal-title').text('Jornada terminada');
                    $('#img').attr('src',"assets/img-avisos/deslogeo.png");
                    $('#mensaje').text("Usted ya entrego arqueo. su jornada ya finalizo!....");
                    $("#entendido").on("click",function() {
                        window.location.href = "https://woflfinanciero.site/";
                    });
                    $("#avisos").modal({backdrop: 'static', keyboard: false})
                }
            }
        });
    });

    }());

});
