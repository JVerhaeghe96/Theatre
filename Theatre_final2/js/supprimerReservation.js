$(document).ready(function(){

    // supprimer réservation
    $(".supprimer").on("click", function(){
        var index = $(".button").index(this) - 1;
        var resId = $(".idReservation:eq("+index+")").text();
        console.log(resId);

        $.post("traitements/TraitementSuppression.php",
            {
                resId: resId
            },
            function(){
                $(".alert").hide();
                $(".alert-success").show();

                setTimeout(refreshListe(), 1000);
            }).fail(function(){
                $(".alert").hide();
                $(".alert-danger.pasSupprime").show();
            }
        );

    });
});

function refreshListe(){
    $("#consulterListe").click();
    $("#rechercherReservation").click();
}