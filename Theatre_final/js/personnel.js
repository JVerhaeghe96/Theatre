$(document).ready(function(){
    // afficher ou non le formulaire du perosnnage
    $("#fonction").on("change", function(){
        if($("#fonction").val() == "acteur")
            displayForm();
        else
            hideForm();
    });

    // ajouter le personnel
    $("#addActeurDB").on("click", function(){
        var spectacle = $("#titre").val();
        var nom = $("#nomActeur").val();
        var prenom = $("#prenomActeur").val();
        var fonction = $("#fonction").val();
        var nomPers = $("#nomPers").val();
        var prenomPers = $("#prenomPers").val();

        $.post("traitements/TraitementPersonnel.php",
            { spectacle: spectacle
                , nom: nom
                , prenom: prenom
                , fonction: fonction
                , nomPers: nomPers
                , prenomPers: prenomPers
            },
            function(){
                $(".alert").hide();
                $(".alert-success").show();
            })
            .fail(function(data){
                $(".alert").hide();
                $(".alert-danger").show();
            });
    });
});

function displayForm(){
    $("#personnage").css("display", "block");
    $("#acteur.formAdd").height(300);
}

function hideForm(){
    $("#personnage").css("display", "none");
    $(" #nomPers").val("");
    $(" #prenomPers").val("");
    $("#acteur.formAdd").height(220);
}