<nav id="main-nav" class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="<?= SERVER_URL ?>"><?= PROJECT_NAME ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">
        <form class="form-inline my-2 my-lg-0 ml-auto" action="buscar">
            <input class="form-control mr-sm-2" name="query" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><img src="<?= SERVER_URL ?>/assets/img/iconos/magnifying_glass.png"></button>
        </form>
        <ul class="navbar-nav ml-auto mt-2 mt-lg-0" id="menu-list">
            <li class="nav-item dropdown">
                <a class="nav-link" href="<?= SERVER_URL ?>/perfil" title="Perfil del usuario">
                    <img class="user-img-mini rounded-circle" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $imagenUsuario ?>" alt="Imagen del Usuario">
                    <span><?= $usuario->__get("usuario") ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/inicio" title="Inicio"><i class="fas fa-home"></i><span class="d-lg-none">&nbsp;Inicio</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/perfil-fotos" title="Imagenes"><i class="far fa-images"></i><span class="d-lg-none">&nbsp;Imagenes</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/notificaciones" title="Notificaciones">
                    <i class="far fa-bell"></i>
                    <span class="d-lg-none">&nbsp;Notificaciones</span>
                    <?php if($notificacionesNuevas > 0) : ?>
                        <span class="notificacion" id="notificacion"><?= $notificacionesNuevas ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/solicitudes" title="Solicitudes de amistad">
                    <i class="fas fa-user-plus"></i>
                    <span class="d-lg-none">&nbsp;Amigos</span>
                    <?php if($solicitudesNuevas > 0) : ?>
                        <span class="notificacion" id="notificacion-amigo"><?= $solicitudesNuevas ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/chats" title="chats">
                    <i class="fas fa-envelope"></i>
                    <span class="d-lg-none">&nbsp;Mensajes</span>
                    <?php if($mensajesSinLeer > 0) : ?>
                        <span class="notificacion" id="notificacion-mensaje"><?= $mensajesSinLeer ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/editar-perfil" title="Editar Perfil">
                    <i class="fas fa-cog"></i>
                    <span class="d-lg-none">&nbsp;Editar Perfil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/logout" title="Cerrar sesión">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-lg-none">&nbsp;Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>
</nav>