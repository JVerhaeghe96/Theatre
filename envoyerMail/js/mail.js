$(document).ready(function(){

    $("#bouton").on("click",function(){
        var fileInput = document.querySelector('#file');
        var tabFichier = [];

        for(var i = 0; i < fileInput.files.length; i++){
            tabFichier[i] = fileInput.files[i].name;
        }

        var touteboite = $("#touteBoite").prop("checked") ? "true" : "false";

        $.post("traitements/TraitementMail.php",
            {
                fichier: tabFichier,
                message: $("#message").val(),
                touteboite: touteboite
            });
    });
});