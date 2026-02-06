    $(document).ready(function () { 
      $("#alert").hide();  
         $("#showhide").click(function() 
         {
            if ($(this).data('val') == "1") 
            {
               $("#pwd").prop('type','text');
               $("#eye").attr("class","glyphicon glyphicon-eye-close");
               $(this).data('val','0');
               return false;
            }
            else
            {
               $("#pwd").prop('type', 'password');
               $("#eye").attr("class","glyphicon glyphicon-eye-open");
               $(this).data('val','1');
               return false;
            }
            //return false;
         });
      

         $("#remove").click(function()
         {
           $("#user").val('');
           return false;
         });
});
    //para que no se ingrese ningun caracter especial en los inputs
    $(".form-control").keypress(function (e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8) return true; // 3
        if (tecla == 32) return true;
        patron = /\w/; //   patron =/[A-Za-z\s]/; patron solo letras
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    });
