$(document).ready(function() {
    loadMensajes(true);
    setInterval(loadNew, 1000);
});

$('#form-mensaje').on('submit', function (event) {
    event.preventDefault();

    let contenido = $('#contenido-mensaje').val();
    
    let url = window.location.href;
    let tokens = url.split('/');
    let usuario = tokens.at(-1);

    if(contenido && usuario) {
        $.ajax({
            url: "../ajax/mensajes",
            type: "post",
            data: {accion: "new", usuario: usuario, contenido: contenido},
            dataType: 'json',
            success:
                function(data) {
                    $('#contenido-mensaje').val("");
                    $('#mensajes').append(renderMensaje(data, usuario));
                    $("#mensajes").scrollTop($("#mensajes")[0].scrollHeight);
                }
        });
    }
});

$('#mensajes').scroll(function() {
    let scroll = $('#mensajes')[0].scrollTop;
    let height = $('#mensajes')[0].scrollHeight;
    if(scroll == 0) {
        loadMensajes(false);
        let heightDif = $('#mensajes')[0].scrollHeight - height;
        $('#mensajes').scrollTop(scroll + heightDif + 10);
    }
});
function renderMensaje(data, usuario) {
    let opcion = usuario !== data.usuario ? 1 : 2;
    return `
        <div class="mensaje${opcion}" id="${data.id}">
            <div class="contenido">
                <p>${data.contenido}</p>
            </div>
        </div>
    `;
}
function loadMensajes(shouldScroll) {
    let url = window.location.href;
    let tokens = url.split('/');
    let usuario = tokens.at(-1);
    let next = $('#mensajes').children('div').length;
    $.ajax({
        url: "../ajax/mensajes",
        type: "POST",
        data: {accion: "load", next: next, usuario: usuario},
        dataType: "json",
        success:
            function(data) {
                if(data.length > 0) {
                    for(let i = 0; i < data.length; ++i) {
                        $('#mensajes').prepend(renderMensaje(data[i], usuario));
                    }
                    if(shouldScroll)
                        $("#mensajes").scrollTop($("#mensajes")[0].scrollHeight);
                    markAsRead();
                }
            }
    });
}
function loadNew() {
    let url = window.location.href;
    let tokens = url.split('/');
    let usuario = tokens.at(-1);
    let id = 0;
    if($('#mensajes').children('div').length > 0)
        id = $('#mensajes').children('div').last().attr("id");
    $.ajax({
        url: "../ajax/mensajes",
        type: "POST",
        data: {accion: "loadNew", lastId: id, usuario: usuario},
        dataType: "json",
        success:
            function(data) {
                if(data.length > 0) {
                    let scroll = false;
                    if($("#mensajes")[0].scrollHeight == $("#mensajes")[0].scrollTop + $("#mensajes")[0].clientHeight)
                        scroll = true;
                    for(let i = 0; i < data.length; ++i) {
                        $('#mensajes').append(renderMensaje(data[i], usuario));
                    }
                    if(scroll)
                        $("#mensajes").scrollTop($("#mensajes")[0].scrollHeight);
                }
            }
    })
}
function markAsRead() {
    let url = window.location.href;
    let tokens = url.split('/');
    let usuario = tokens.at(-1);
    $.ajax({
        url: "../ajax/mensajes",
        type: "POST",
        data: {accion: "mark", usuario: usuario}
    });
}