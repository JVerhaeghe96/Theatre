$(document).ready(function(){
    $("#copierPlanSalle").on("click", function(){
        var currentDate = $("#currentDate").val();
        var dateCopie = $("#dateCopie").val();
        console.log(currentDate);
        console.log(dateCopie);

        if(dateCopie == currentDate){
            alert("Veuillez sélectionner une autre date");
        }else{
            $.post("traitements/TraitementSalle.php?action=copier",
                {dateCopie: dateCopie, currentDate: currentDate})
                .done(function(){
                    alert("Copie effectuée !");
            }).fail(function(){
                alert("Échec lors de la copie.");
            });
        }
    });

    $(".boutonChaise").on("click", function(){
        var id = $(this).val();
        var date = $("#currentDate").val();

        var elem = $(this);

        $.post("traitements/TraitementSalle.php?action=changerEtat",
            {id: id, date:date})
            .done(function(){
                $(elem).attr("class") == 'remove boutonChaise' ? changeToAdd(elem) : changeToRemove(elem);
            }).fail(function(){
                alert("Cette chaise ne peut pas être modifiée.");
            });
    });
});

function changeToAdd(elem){
    console.log(elem);
    elem.attr("class", "add boutonChaise");
    elem.text("+");
}

function changeToRemove(elem){
    console.log(elem);
    elem.attr("class", "remove boutonChaise");
    elem.text("-");
}