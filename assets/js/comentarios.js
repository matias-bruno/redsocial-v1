function renderComentario(element, usuario_id) {
    let eliminar = "";
    if(element.usuario_id == usuario_id) {
        eliminar = `<a href="javascript:void(0)" onclick="eliminarComentario(${element.id})" class="delete-comment">x</a>`;
    }
    return `
            <div class="comentario" id="comentario${element.id}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <img class="user-img-mini rounded-circle" src="${serverUrl}/assets/img/fotos_perfil/${element.imagen_usuario}" alt="Imagen del Usuario">
                        ${element.nombre_usuario}
                    </div>
                    <div>
                        <span class="time_stamp">Hace ${element.time_stamp}</span>
                    </div>
                </div>
                <div class="contenido-comentario">
                    <p>${element.contenido}</p>
                    ${eliminar}
                </div>
            </div>
        `;
}
function renderComentarios(data, publicacion_id, usuario_id){
    let comentarios = document.querySelector('#comentarios' + publicacion_id);
    comentarios.innerHTML = "";
    data.forEach(element => {
        comentarios.innerHTML += renderComentario(element, usuario_id);
    });
}



function verComentarios(publicacion_id) {
    let next = $('#comentarios' + publicacion_id).children('div').length;
    $.ajax({
        url: serverUrl + "/ajax/comentarios",
        type: "POST",
        data: {accion: "mostrar", publicacion_id: publicacion_id, next: next},
        dataType: "json",
        success:
            function(data) {
                console.log(data);
                const comentarios = data.comentarios;
                if(comentarios && comentarios.length > 0) {
                    renderComentarios(comentarios, publicacion_id, data.usuario_id);
                    next += comentarios.length;
                }
                if(next > 0) {
                    if($('#comentarios' + publicacion_id).is(':hidden')) {
                        $('#comentarios' + publicacion_id + ':hidden').show("slow");
                        $('#verComentarios' + publicacion_id).html("Ocultar Comentarios");
                    } else {
                        $('#comentarios' + publicacion_id + ':visible').hide("slow");
                        $('#verComentarios' + publicacion_id).html("Ver Comentarios");
                    }
                }
            }
    });
}
function comentar(id) {
    let comentario = $('#comentar' + id).val();
    if(comentario.trim().length) {
        $.ajax({
            url: serverUrl + "/ajax/comentarios",
            type: "POST",
            data: {accion: "comentar", id: id, comentario: comentario},
            dataType: "json",
            success:
                function(data) {
                    let comentario = data.comentario;
                    $('#comentar' + id).val("");
                    if($('#comentarios' + id).is(':hidden')) {
                        $('#comentarios' + id + ':hidden').show("slow");
                        $('#verComentarios' + id).html("Ocultar Comentarios");
                    }
                    $('#comentarios' + id).append(renderComentario(comentario, data.comentario.usuario_id));
                    $('#total-comentarios' + id).html(`<i class="far fa-comment"></i> ${comentario.total_comentarios}`);
                }
        });
    }
}

function eliminarComentario(id) {
    $.ajax({
        url: serverUrl + "/ajax/comentarios",
        type: "POST",
        data: {accion: "eliminar" ,id: id},
        dataType: "json",
        success:
            function(data) {
                $('#comentario' + id).hide();
                $('#comentario' + id).remove();
                $('#total-comentarios' + data.publicacion_id).html(`<i class="far fa-comment"></i> ${data.comments_count}`);
                if(data.comments_count == 0) {
                    if($('#comentarios' + data.publicacion_id).is(':visible')) {
                        $('#comentarios' + data.publicacion_id + ':visible').hide("slow");
                    }
                    $('#verComentarios' + data.publicacion_id).html("Ver Comentarios");
                }
            }
    });
}