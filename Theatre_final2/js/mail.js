$(document).ready(function(){

    // mail
    $("#bouton").on("click",function(){
        var fileInput = document.querySelector('#file');
        var tabFichier = [];

        // remplir le tableau avec les pièces jointes
        for(var i = 0; i < fileInput.files.length; i++){
            tabFichier[i] = fileInput.files[i].name;
        }

        $.post("traitements/TraitementMail.php",
            {
                fichier: tabFichier,
                message: $("#message").val(),
            });
    });
});