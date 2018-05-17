$(document).ready(function(){

    // copier salle
    $("#copierPlanSalle").on("click", function(){
        var currentDate = $("#currentDate").val();
        var dateCopie = $("#dateCopie").val();

        if(dateCopie == currentDate){
            $(".alert").hide();
            $(".alert-danger.dateInvalide").show();
        }else{
            $.post("traitements/TraitementSalle.php?action=copier",
                {dateCopie: dateCopie, currentDate: currentDate},
                function(){
                    $(".alert").hide();
                    $(".alert-success").show();
                }
            )
            .fail(function(){
                $(".alert").hide();
                $(".alert-danger.copyFailed").show();
            });
        }
    });

    // changer l'état d'une chaise D ou N
    $(".boutonChaise").on("click", function(){
        var id = $(this).val();
        var date = $("#currentDate").val();
        var elem = $(this);

        $.post("traitements/TraitementSalle.php?action=changerEtat",
            {id: id, date:date}
        ).done(function(){
            $(elem).attr("class") == 'remove boutonChaise' ? changeToAdd(elem) : changeToRemove(elem);
            $(".alert").hide();
        }).fail(function(){
            $(".alert").hide();
            $(".alert-danger.chaiseNonModifiable").show();
        });
    });
});

function changeToAdd(elem){
    elem.attr("class", "add boutonChaise");
    elem.text("+");
}

function changeToRemove(elem){
    elem.attr("class", "remove boutonChaise");
    elem.text("-");
}