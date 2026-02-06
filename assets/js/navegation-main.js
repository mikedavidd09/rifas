$.ajaxPrefilter(function (options, original_Options, jqXHR) {
    options.async = true;
});
var link_old = '';
var tablasearch;
$("body").on('click','.link', CallAjax);
function CallAjax() {
    var table = $(tablasearch).DataTable();
    var search = "&filter="+table.search();

    if (table.search() === undefined || table.search() == ""){
        search="";
    }

    var link = $(this).attr("href");
    var logout = link.substr(21, 6);
    if (link != '#' && link != link_old) {
        $.ajax({
            url: link+search
        }).done(function (html) {
            if (logout != "logout") {
                $("#page").html(html);
                link_old = link;
            }
            else {
                window.location.replace("index.php");
            }
        })
            .fail(function () {
                console.log("error");
            })
            .fail(function () {
                console.log("complete");
            });
        return false;
    } else if(link != '#') {
        return false;
    }
}
/*beforeSend: function () {
      $("#page").html('<div id="capa_cargando" class="clase_cargando"><div style="position:absolute; top:250px; left:350px; width:200px; height:20px; border:1px solid #FF6666;background-color:#000000;color:#FFFFFF; text-align:center;">	<b>cargando...</b></div></div>');
}*/
