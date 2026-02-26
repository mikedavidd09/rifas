
function alertConfirm(url,message,table = null){
    let confirm = alertify.confirm('Rifas el regalon:', message, null, null).set('labels', {
            ok: 'Si',
            cancel: 'No'
        });
        confirm.set({transition: 'slide'});
        confirm.set('onok', function () { //callbak al pulsar botón positivo
            $.ajax({
				url: url,
                type: "POST",
                dataType: "json",
                success: function(data){
                    if(data.status){
                        alertify.success(data.message);

                        if(table != null)
                            $('#'+table).DataTable().ajax.reload();
                    }
                    else{
                        alertify.error(data.message);
                    }
                }
            });
        });
}
