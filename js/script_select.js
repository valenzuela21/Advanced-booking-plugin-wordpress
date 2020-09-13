var $ = jQuery.noConflict(true);
var url = String(admin_url.url);

$(document).ready(function(){
    $('#btn-import-add').click(function (e) {
        e.preventDefault();

        var id = $("#select-id").val();
        var id_product = $("#id-product").val();

        var respuestas = [id, id_product];

        var datos = {
            action: 'consult_import_booking',
            respuesta: respuestas
        }

        $.ajax({
            url: admin_url.ajax_url,
            type: 'post',
            data: datos
        }).done(function(respuesta) {
            window.app.consultUpdate();
        });

    });


    $("#select-id").select2({
        ajax: {
            url: url,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true

        }
    });


});





