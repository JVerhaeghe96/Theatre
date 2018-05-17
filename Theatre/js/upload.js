window.onload = init;

function init() {
    var form = new FormData();
    var fileInput = document.querySelector('#file');
    var progress = document.querySelector('#progress');

    fileInput.addEventListener('change', function() {
        if(fileInput.files.length > 0){ // nombre de fichiers sélectionnés
            $(".alert").hide();
            $(".alert-warning").show();

            var xhr = getXHR();

            if(xhr == null){
                $(".alert").hide();
                $(".alert-danger.noXHR").show();
            }else{
                xhr.open('POST', 'utils/upload.php');

                // désactiver le bouton mail
                $("#bouton").prop("disabled", true);

                //barre de progression
                progress.style.display = "block";
                xhr.upload.addEventListener('progress', function(e) {
                    progress.value = e.loaded;
                    progress.max = e.total;
                });

                // upload
                xhr.addEventListener('load', function(){
                    if(xhr.status == 200){ // normalement ok
                        if(xhr.responseText[0] != '<'){ // si taille du fichier trop grand
                            var responseText = JSON.parse(xhr.responseText);
                            if(responseText[0]["error"] == 1){ // upload échoué
                                $(".alert").hide();
                                $(".alert-danger.fail").show();
                            }else{ // upload a réussi
                                $("#bouton").prop("disabled", false);
                                $(".alert").hide();
                                $(".alert-success").show();
                            }
                        }else{ // taille du fichier trop grand
                            $(".alert").hide();
                            $(".alert-danger.fileSize").show();
                        }
                    }else{ // taille du fichier trop grand
                        $(".alert").hide();
                        $(".alert-danger.fileSize").show();
                    }
                });

                //mettre les pièces jointes dans une variable
                for(var i = 0; i < fileInput.files.length; i++){
                    form.append('file'+i, fileInput.files[i]);
                }
                xhr.send(form);
            }
        }else{ // si aucun fichier n'a été sélectionné
            $(".alert").hide();
            $("#bouton").prop("disabled", false);
        }
    });

}

// récupéer un élément XHR
function getXHR(){
    var xhr = null;
    // vérifier si le navigateur est compatible
    if(window.XMLHttpRequest || window.ActiveXObject){
        try{
            xhr = new XMLHttpRequest();
        }catch(e){
            try{
                xhr = new ActiveXObject("Msxml12.XMLHTTP");
            }catch(e){
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
        }
    }else{
        xhr = null;
    }

    return xhr;
}
