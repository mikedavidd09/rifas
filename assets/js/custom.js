/*------------------------------------------------------
    Author : www.webthemez.com
    License: Commons Attribution 3.0
    http://creativecommons.org/licenses/by/3.0/
---------------------------------------------------------  */

(function ($) {
    "use strict";
    var mainApp = {

        initFunction: function () {
            /*MENU 
            ------------------------------------*/
            $('#main-menu').metisMenu();
			
            $(window).bind("load resize", function () {
                if ($(this).width() < 768) {
                    $('div.sidebar-collapse').addClass('collapse')
                } else {
                    $('div.sidebar-collapse').removeClass('collapse')
                }
            });
     
        },

        initialization: function () {
            mainApp.initFunction();

        }

    }
    // Initializing ///

    $(document).ready(function () {
        mainApp.initFunction();
    });

}(jQuery));

$.getScript('https://www.gstatic.com/firebasejs/live/3.1/firebase.js', function(){
    (function(){

        const config ={
            apiKey:"AIzaSyBZ9jb0dVm7fSrdaMgIJlnsLZtz3a8Al8U",
            authDomain:"dbfirebase-d7e4e.firebaseapp.com",
            databaseURL:"https://dbfirebase-d7e4e.firebaseio.com/test/",
            storageBucket:"dbfirebase-d7e4e.appspot.com",
        }

        firebase.initializeApp(config);



        const dbRefObject = firebase.database().ref().child('dbfirebase');

        dbRefObject.on('child_added',snap=>{
            Push.create("Prestamo Realizado:"+snap.val().colaborador, {
            body: "Cliente:"+snap.val().nombre+" Codigo:"+snap.val().codigo,
            icon: '',
            timeout: 20000,
            onClick: function () {
                var link ='index.php?controller=Cliente&action=ver_datos&obj='+snap.val().id;
                $.ajax({
                    url: link
                }).done(function (html) {
                    $('#page').html(html);
                });
                return false;
            }
        });
    });

    }());

});
