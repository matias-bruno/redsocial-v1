

function ajustarPortada() {
    if($("#foto-portada").length) {
        let bottom = (document.getElementById("foto-portada").height - 300) / 2;
        $("#foto-portada").css("bottom", bottom);
    }
}

$("#foto-portada").on("load", function() {
    let bottom = ($("#foto-portada").height() - 300) / 2;
    $("#foto-portada").css("bottom", bottom);
})

$('#file-portada').on('change', function(event) {
    let formData = new FormData();
    let files = $('#file-portada')[0].files[0];
    formData.append('file',files);
    formData.append('accion', 'cambiar');
    $.ajax({
        url: serverUrl + "/ajax/acciones-portada",
        type: "post",
        data: formData,
        dataType: "json",
        // Estas 2 lineas siguientes son necesarias
        contentType: false,
        processData: false,
        success: 
            function(data) {
                console.log(data);
                if (data["ok"]) {
                    $("#foto-portada").attr("src", data["imagen"]);
                } else {
                    alert(data["error"]);
                }
            },
        error:
            function(data) {
                console.log(data);
            }
    });
});

$("#quitar-portada").on('click', function(event) {
    if(confirm("Confirma que desea quitar la imagen de portada")) {
        $.ajax({
            url: serverUrl + "/ajax/acciones-portada",
            type: "post",
            data: {accion: "quitar"},
            dataType: "json",
            success: 
                function(data) {
                    if (data["ok"]) {
                        $("#foto-portada").attr("src", data["imagen"]);
                    }
                }
        });
    }
});
$("#foto-portada").on('mouseover',  function(event) {
    $("#cambiar-portada").show();
    $("#quitar-portada").show();
});
$("#foto-portada").on('mouseout',  function(event) {
    $("#cambiar-portada").hide();
    $("#quitar-portada").hide();
});
$("#cambiar-portada").on('mouseover', function(event) {
    $("#cambiar-portada").show();
    $("#quitar-portada").show();
});
$("#quitar-portada").on('mouseover', function(event) {
    $("#cambiar-portada").show();
    $("#quitar-portada").show();
});