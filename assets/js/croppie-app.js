var resize = $('#upload-demo').croppie({
    enableExif: true,
    enableOrientation: true,    
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: 200,
        height: 200,
        type: 'square' //square
    },
    boundary: {
        width: 250,
        height: 250
    }
});
var change = false;
$('#image').on('change', function () {
  change = true;
  var reader = new FileReader();
    reader.onload = function (e) {
      resize.croppie('bind',{
        url: e.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    if($('#image') !== "") {
      $('.btn-upload-image').on('click', function (ev) {
      resize.croppie('result', {
        type: 'canvas',
        size: 'viewport'
      }).then(function (img) {
        $.ajax({
          url: serverUrl + "/ajax/cambiar-foto",
          type: "POST",
          data: {"image":img},
          success:
            function(data) {
              if(data) {
                console.log(serverUrl + "/editar-perfil");
                window.location.href = serverUrl + "/editar-perfil";
              }
            }
        });
      });
  });
} 
});
