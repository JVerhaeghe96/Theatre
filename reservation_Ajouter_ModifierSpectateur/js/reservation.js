$(document).ready(function(){

    $("#boutonAjoutSpectateur").on("click", function(){ //  Affiche le formulaire pour ajouter un spectateur et assombrit l'arrière-plan
        $("#modal").fadeIn("slow");
        $("#formNewSpectator").fadeIn("slow");
    });

    $("#closeButton").on("click", function(){  //  permet de fermer le formulaire d'ajout d'un spectateur
        $("#modal").fadeOut("slow");
        $("#formNewSpectator").fadeOut("slow");
    });
});

