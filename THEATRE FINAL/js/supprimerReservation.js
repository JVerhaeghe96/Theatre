$(document).ready(function(){

    // supprimer réservation
    $(".supprimer").on("click", function(){
        var index = $(".button").index(this);
        var resId = $(".idReservation:eq("+index+")").text();

        console.log(index);

        $.post("traitements/TraitementSuppression.php",
            {
                resId: resId
            },
            function(){
                $(".alert").hide();
                $(".alert-success").show();

                $(".reservationElement:eq("+index+")").remove();
            }).fail(function(){
                $(".alert").hide();
                $(".alert-danger.pasSupprime").show();
            }
        );

    });
});