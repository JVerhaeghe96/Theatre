$(document).ready(function(){

    // gestion des droits
    $(".droit").on("change",function(){
        var index = $(".droit").index(this);    //  On récupère l'index de l'élément sur lequel on va cliquer
        var indexUser = parseInt(index/14);    // On récupère l'index correspondant au nom d'utilisateur dont la case a été cliqué

        var user = document.getElementsByClassName("nom")[indexUser].textContent;   //  On récupère l'utilisateur dont on souhaite modifier le droit

        if(index > 0)

        var caseCliquee = $(this).attr("value");    //  On récupère la valeur de la case sur laquelle on a cliqué
        var isChecked = $(this).prop("checked");    //  On regarde si on a coché ou décoché la case
        var valeur = 'Z';   // valeur par défaut

        if(isChecked){  //  On a coché la case
            valeur = 'M';
        }else {   //  On l'a décoché
            valeur = 'Z';
        }

        $.post("traitements/TraitementDroits.php",
            { user: user  //  recupéerer le login du droit à modifier
            , droit: $(this).attr("name")        //  recupéerer le droit qui a été modifié
            ,valeur: valeur});                        //  recupéerer le nouveau droit.

    });

    //  Affiche le formulaire pour ajouter un utilisateur et assombrit l'arrière-plan
    $("#boutonAddRang").on("click", function(){
        $("#modal").fadeIn("slow");
        $("#formNewUser").fadeIn("slow");
    });

    //  Affiche le formulaire pour modifier le mot de passe d'un utilisateur
    $(".boutonModifierMdp").on("click", function(){
        $("#modal").fadeIn("slow");
        $("#formModifierMdp").fadeIn("slow");

        var index = $(".boutonModifierMdp").index(this);
        var login = document.getElementsByClassName("nom")[index].textContent;
        $("#login").val(login);
    });

    //  permet de fermer le formulaire d'ajout d'un utilisateur
    $(".closeButton").on("click", function(){
        $("#modal").fadeOut("slow");
        $("#formNewUser").fadeOut("slow");
        $("#formModifierMdp").fadeOut("slow");
    });
});

