function loadEstadoAmistad() {
    let url = window.location.href;
    let tokens = url.split('/');
    let usuario = tokens.at(-1);
    $.ajax({
        url: serverUrl + "/ajax/estadoAmistad",
        type: "post",
        data: {usuario: usuario},
        dataType: "json",
        success:
            function(data) {
                if(data["ok"]) {
                    let usuario = data["usuarioAmigo"];
                    let accion = data["status"];
                    $('#contenedorBtnAmistad').html(getStatusHtml(accion, usuario));
                }
            }
    });
}

function getStatusHtml(status, usuario) {
    switch(status) {
        case "agregar":
            return `<button id='btnAgregarAmigo' class='btn btn-secondary' onclick="accionAmigo('enviar', '${usuario}')">Agregar Amigo</button>`;
        case "aceptada":
            return `
                    <div class='dropdown' id='solicitudAceptada'>
                        <button class='btn btn-secondary dropdown-toggle' type='button' id='btnSolicitudAceptada' data-toggle='dropdown' aria-expanded='false'>
                            Amigos
                        </button>
                        <div class='dropdown-menu' aria-labelledby='btnSolicitudAceptada'>
                            <a class='dropdown-item' href='javascript:void(0)' onclick="accionAmigo('quitar', '${usuario}')">Quitar Amigo</a>
                            <a class='dropdown-item' href="${serverUrl}/chat/${usuario}">Enviar Mensaje</a>
                        </div>
                    </div>
                    `;
        case "enviada":
            return `
                    <div class="dropdown" id="solicitudEnviada">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="btnSolicitudEnviada" data-toggle="dropdown" aria-expanded="false">
                            Solicitud Enviada
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnSolicitudEnviada">
                            <a class="dropdown-item" href="javascript:void(0)" onclick="accionAmigo('cancelar', '${usuario}')">Cancelar Solicitud</a>
                            <a class="dropdown-item" href="${serverUrl}/chat/${usuario}">Enviar Mensaje</a>
                            <a class="dropdown-item" href="${serverUrl}/solicitudes">Ver Solicitudes</a>
                        </div>
                    </div>
                    `;
        case "recibida":
            return `
                    <div class="dropdown" id="solicitudRecibida">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="btnSolicitudRecibida" data-toggle="dropdown" aria-expanded="false">
                            Solicitud Recibida
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnSolicitudRecibida">
                            <a class="dropdown-item" href="javascript:void(0)" onclick="accionAmigo('aceptar', '${usuario}')">Aceptar Solicitud</a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="accionAmigo('rechazar', '${usuario}')">Rechazar Solicitud</a>
                            <a class="dropdown-item" href="${serverUrl}/chat/${usuario}">Enviar Mensaje</a>
                            <a class="dropdown-item" href="${serverUrl}/solicitudes">Ver Solicitudes</a>
                        </div>
                    </div>
                    `;
    }
    return "";
}
function getTextConfirm(accion, usuario) {
    switch(accion) {
        case "enviar":
            return "¿Desea enviar una solicitud de amistad a " + usuario + "?";
        case "aceptar":
            return "¿Desea aceptar la solicitud de amistad de " + usuario + "?";
        case "cancelar":
            return "¿Desea cancelar la solicitud de amistad enviada a " + usuario + "?";
        case "rechazar":
            return "¿Desea rechazar la solicitud de amistad de " + usuario + "?";
        case "quitar":
            return "¿Desea dejar de ser amigo de " + usuario + "?";
    }
    return "";
}
function getStatusLabel(accion) {
    switch(accion) {
        case "cancelar":
        case "rechazar":
        case "quitar":
            return "agregar";
        case "enviar":
            return "enviada";
        case "aceptar":
            return "aceptada";
    }
    return "";
}
function accionAmigo(accion, usuario) {
    const textConfirm = getTextConfirm(accion, usuario);
    if(confirm(textConfirm)) {
        $.ajax({
            url: serverUrl + "/ajax/acciones-amigos",
            type: "post",
            data: {usuario: usuario, accion: accion},
            dataType: "json",
            success:
                function(data) {
                    console.log(data);
                    if(data["ok"]) {
                        const statusLabel = getStatusLabel(accion);
                        $('#contenedorBtnAmistad').html(getStatusHtml(statusLabel, usuario));
                    }
                }
        });
    }
};
