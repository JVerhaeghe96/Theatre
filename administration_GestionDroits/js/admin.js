$(document).ready(function(){
    $(".droit").on("change",function(){
        var index = $(".droit").index(this);    //  On récupère l'index de l'élément sur lequel on va cliquer
        var indexUser = parseInt(index/28);    // On récupère l'index correspondant au nom d'utilisateur dont la case a été cliqué

        var user = document.getElementsByClassName("nom")[indexUser].textContent;   //  On récupère l'utilisateur dont on souhaite modifier le droit

        var caseSuivante = document.getElementsByClassName("droit")[index + 1]; //  On récupère la case à cocher qui suit celle qui a été cliquée
        if(index > 0)
            var casePrec = document.getElementsByClassName("droit")[index - 1];//  On récupère la case à cocher qui précède celle qui a été cliquée

        var caseCliquee = $(this).attr("value");    //  On récupère la valeur de la case sur laquelle on a cliqué
        var isChecked = $(this).prop("checked");    //  On regarde si on a coché ou décoché la case
        var valeur = 'Z';   // valeur par défaut

        switch(caseCliquee){
            case 'M':{  //  On a cliqué sur la case M
                if(isChecked){  //  On a coché la case
                    valeur = 'M';
                    casePrec.setAttribute("checked", "");
                }else   //  On l'a décoché
                    if(casePrec.getAttribute("checked")){   //  La case C est cochée
                       valeur = 'C';
                    }
                break;
            }
            case 'C':{  //  On a cliqué sur la case C
                if(isChecked){  //  On a coché la case
                    valeur = 'C';
                }else{  //  On l'a décoché
                    valeur = 'Z';
                    caseSuivante.removeAttribute("checked");
                }
                break;
            }
        }

        $.post("utils/TraitementDroits.php",
            { user: user  //  recupéerer le login du droit à modifier
            , droit: $(this).attr("name")        //  recupéerer le droit qui a été modifié
            ,valeur: valeur});                        //  recupéerer le nouveau droit.

    });
});

