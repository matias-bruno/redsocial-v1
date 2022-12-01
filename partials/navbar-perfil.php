<nav id="user-nav" class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-perfil" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu-perfil" d-flex justify-content-between align-content-center>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0" id="menu-list">
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/perfil/<?= $url[1] ?? "" ?>">Linea de Tiempo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/perfil-amigos/<?= $url[1] ?? "" ?>">Amigos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= SERVER_URL ?>/perfil-fotos/<?= $url[1] ?? "" ?>">Fotos</a>
            </li>
        </ul>
        <?php if(!$perfilUsuarioLogueado && !$amistadBloqueada) : ?>
            <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                <li class="nav-item" id="contenedorBtnAmistad">
                    
                </li>
            </ul>
        <?php endif; ?>
    </div>
</nav>