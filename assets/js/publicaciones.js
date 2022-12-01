function loadMore(){
    let urlActual = window.location.href;
    let next = $('#publicaciones').children('div').length;
    $.ajax({
        url: serverUrl + "/ajax/ver-publicaciones",
        type: "POST",
        data: {next: next, urlActual: urlActual},
        dataType: "json",
        success:
            function(data) {
                if(data[0].length > 0) {
                    // page = data[2].page;
                    const userId = data[1].userId;
                    data[0].forEach(element => {
                        $('#publicaciones').append(renderPublicacion(element, userId));
                    });
                }
            }
    });
}

function renderPublicacion(element, userId = false) {
    let imagen  = "";
    let icono_like = "far fa-thumbs-up";
    let post_options = "";
    if(element.imagen) {
        imagen = `<img class="img-publicacion" src="${serverUrl}/assets/img/${element.album}/${element.imagen}" alt="Imagen de la publicacion">`;
    }
    if(element.liked > 0) {
        icono_like = "fas fa-thumbs-up";
    }
    if(!userId || userId == element.usuario_id) {
        post_options = `
            <span class="dropdown">
                <a class="btn text-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="">Editar</a>
                    <a class="dropdown-item" onclick="deletePublicacion(${element.id})" href="javascript:void(0)">Eliminar</a>
                </div>
            </span>
        `;
    }
    return `
        <div class="card publicacion" id="publicacion${element.id}">
            <div class="card-header d-flex justify-content-between align-items-center navbar-dark px-2">
                <div>
                    <a href="${serverUrl}/perfil/${element.nombre_usuario}" class="link-profile" alt="Enlace al perfil del usuario">
                        <img class="user-img-mini rounded-circle" src="${serverUrl}/assets/img/fotos_perfil/${element.imagen_usuario}" alt="Imagen del Usuario">
                        ${element.nombre_usuario}
                    </a>
                    <span class="descripcion">${element.descripcion}</span>
                </div>
                <div>
                    <span class="time_stamp">Hace ${element.time_stamp}</span>
                    ${post_options}
                </div>
            </div>
            <div class="card-body bg-white">
                <p>${element.contenido}</p>
                <div class="img-container">${imagen}</div>
            </div>
            <div class="card-footer d-flex justify-content-around align-items-center">
                <div>
                    <a href="javascript:void(0)" onclick="meGusta(${element.id})" id="${element.id}" class="like" title="Me Gusta">
                        <i class="${icono_like}"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="showLikes(${element.id})" id="count${element.id}" title="A quienes le gusta" class="who-likes">
                        ${element.likes_count}
                    </a>
                </div>
                <div>
                    <a href="javascript:void(0)" onclick="showComments(${element.id})" id="total-comentarios${element.id}" class="cuenta-comentarios">
                        <i class="far fa-comment"></i>
                        ${element.comments_count}
                    </a>
                </div>
            </div>
            <div id="btnComentarios${element.id}" class="text-center">
                <a href="javascript:void(0)" id="verComentarios${element.id}" class="verComentarios" onclick="verComentarios(${element.id})">Ver Comentarios</a>
            </div>
            <div id="comentarios${element.id}" class="comentarios"> </div>
            <div class="agregarComentario">
                <input type="text" id="comentar${element.id}" class="txtComentario" placeholder="Escribe tu comentario">
                <button onclick="comentar(${element.id})" class="btn-comentario btn btn-primary">Enviar</button>
            </div>
        </div>
    `;
}

$('#form-publicar').on('submit', function (event) {
    event.preventDefault();

    let formData = new FormData();
    let dataToSend = false;

    if($('#imagen').val()) {
        formData.append('imagen', $('#imagen')[0].files[0]);
        dataToSend = true;
    }

    if($('#publicar').val()) {
        formData.append('contenido', $('#publicar').val());
        dataToSend = true;
    }
    
    if(dataToSend) {
        $.ajax({
            url: serverUrl + "/ajax/publicar",
            type: "post",
            data: formData,
            dataType: 'json',
            contentType:false,
            processData:false,
            success:
                function(data) {
                    $('#publicar').val("");
                    $('#imagen').val("");
                    $('#publicaciones').prepend(renderPublicacion(data)).hide().show("slow");
                }
        });
    }
});
function deletePublicacion(id) {
    $.ajax({
        url: serverUrl + "/ajax/delete-publicacion",
        type: "POST",
        data: {id, id},
        success:
            function(data) {
                $('#publicacion' + id).hide();
                $('#publicacion' + id).remove();
            }
    });
}

function meGusta(id) {
    $.ajax({
        url: serverUrl + "/ajax/likes",
        type: "POST",
        data: {id: id},
        dataType: 'json',
        success:
            function(data) {
                //No se llama a JSON.parse porque especificamos el parámetro dataType con el valor 'json'
                // Sino tendríamos que hacerlo
                //data = JSON.parse(data);
                let {icono} = data;
                let {likes} = data;
                $('#' + id).html(icono);
                $('#count' + id).html(likes);
            }
    });
};
