$(document).ready(function(){
    $("#fonction").on("change", function(){
        if($("#fonction").val() == "acteur")
            displayForm()
        else
            hideForm();
    });

    $("#addActeurDB").on("click", function(){
        var spectacle = $("#titre").val();
        var nom = $("#nomActeur").val();
        var prenom = $("#prenomActeur").val();
        var fonction = $("#fonction").val();
        var nomPers = $("#nomPers").val();
        var prenomPers = $("#prenomPers").val();

        console.log(spectacle + ", " + nom + ", " + prenom + ", " + fonction + ", " + nomPers + ", " + prenomPers);

        $.post("traitements/TraitementPersonnel.php",
            { spectacle: spectacle
                , nom: nom
                , prenom: prenom
                , fonction: fonction
                , nomPers: nomPers
                , prenomPers: prenomPers})
            .done(function(){
                alert("Ajout réussi !");
            })
            .fail(function(){
                alert("Erreur : champs manquants ou personnel déjà existant.");
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