<div id="portada">
    <img id="foto-portada" src="<?= SERVER_URL ?>/assets/img/portadas/<?= $portada ?>" alt="Imagen de portada">
    <?php if($perfilUsuarioLogueado) : ?>
        <form action="" method="post" id="form-portada" enctype="multipart/form-data">
            <label for="file-portada" class="btn-portada" id="cambiar-portada"><i class="fas fa-camera"></i></label>
            <input type="file" id="file-portada" name="file-portada" hidden>
            <label class="btn-portada" id="quitar-portada"><i class="fas fa-trash-alt"></i></label>
        </form>
    <?php endif; ?>
    <div id="user-info-perfil">
        <img class="rounded-circle user-img-perfil" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $imagenUsuarioPerfil ?>" alt="Imagen del Usuario">
        <div><h3 id="user-nombre-perfil"><?= $nombreUsuario ?></h3></div>
    </div>
</div>
