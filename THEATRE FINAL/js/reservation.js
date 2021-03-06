$(document).ready(function(){

    //  Affiche le formulaire pour ajouter un spectateur et assombrit l'arrière-plan
    $("#boutonAjoutSpectateur").on("click", function(){
        $("#modal").fadeIn("slow");
        $("#formNewSpectator").fadeIn("slow");
    });

    //  permet de fermer le formulaire d'ajout d'un spectateur
    $(".closeButton").on("click", function(){
        $("#modal").fadeOut("slow");
        $("#formNewSpectator").fadeOut("slow");
    });

    // afficher les dates en fonction des titres
    $("#titre").on('change',function () {
        $("#date").empty();
        $.post('traitements/TraitementReservation.php?action=getTitre', //URL
            {titre:$("#titre").val()},
            function (data) { //fonction exécutée après le traitement
            data=JSON.parse(data);
            data.map(function (value){
                $("#date").append("<option value='"+ value['date'] +'|'+ value['heure']+"'>"+value['date']+" "+value['heure']+" ("+ value['nbPlacesDispo'] +") </option>");
            });
        });
    });

    var legende;
    var nbrPlaces=0;
    var nbSelect=0;
    var tabPlacesSelect = [];

    // réservation
    $("#boutonReserver").on('click',function () {
        var titre=$("#titreSelect").val();
        var specNom = $("#nom").val();
        var specPrenom = $("#prenom").val();
        var remarque=$("#commentaire").val();

        tabPlacesSelect=transformerTableau(tabPlacesSelect);
       $.post('traitements/TraitementReservation.php?action=reservation', //URL
            {titre:titre,
            nbPlaces:nbrPlaces,
            commentaire:remarque,
            nom:specNom,
            prenom:specPrenom,
            tabPlaces:tabPlacesSelect},
            function () { //fonction exécutée après le traitement
                $(".alert").hide();
                $(".alert-success").show();
            }
        ).done(function(){
           $("#nbPlaces").val(0);
           $("#commentaire").val("");
           tabPlacesSelect = [];
           nbSelect = 0;
           nbrPlaces = 0;
       }).fail(function(){
           $(".alert").hide();
           $(".alert-danger.zeroChaiseSelect").show();
       });
    });

    //sélectionner une des cases de la légende
    $(".legende").on("click",function () {
        $("#etatSelect").attr("id","");
        $(this).attr("id","etatSelect");
        legende=$(this).attr("name");
    });

    // récuper le nombre de places
    $("#nbPlaces").on('change',function () {
        nbrPlaces=$(this).val();
    });

    // changer l'etat de base de la case en fonction de la légende
    $("td.tabSalle").on("click",function () {
        var caseSelect=$(this);
        switch(legende) {
            case 'dispo': {
                var index=tabPlacesSelect.findIndex(function (element) {
                    return element.text()==caseSelect.text();  //comparer chaque case du tableau tabPlacesSelect à la case sélectionnée
                });

                if(index!=-1) {
                    $(this).attr("class","tabSalle dispo"); // remplacer la classe de la case par la la classe de la legende
                    tabPlacesSelect.splice(index,1);
                    nbSelect--;
                }

                break;
            }

            case 'pasDispo': {
                $(".alert").hide();
                $(".alert-danger.pasDisponible").show();
                break;
            }

            default:{
                if($(this).attr("class")=="tabSalle dispo") {
                    if (nbrPlaces == 0) {
                        $(".alert").hide();
                        $(".alert-danger.zeroChaiseSelect").show();
                    } else {
                        if (nbSelect < nbrPlaces) {
                            $(this).attr("class", "tabSalle " + legende);   // remplacer la classe de la case par la la classe de la legende
                            nbSelect++;
                            tabPlacesSelect.push($(this));
                            $(".alert").hide();
                        }else{
                            $(".alert").hide();
                            $(".alert-warning.limitReached").show();
                        }
                    }
                }
            }
        }
    });

    // autocompletion
    $("#nom").autocomplete({
        source:'traitements/TraitementAutocomplete.php?action=getNom'
    });

    $("#prenom").autocomplete({
        source:'traitements/TraitementAutocomplete.php?action=getPrenom'
    });
});

// tableau associatif de chaise
function transformerTableau(tabPlacesSelect) {
    var tab=new Array();
    var etat;
    for(var i=0;i<tabPlacesSelect.length;i++){
        etat= tabPlacesSelect[i].attr("class")=='tabSalle occupe' ? 'O':'R';
        var cases={"id":tabPlacesSelect[i].text(),"etat":etat,"date":$("#dateSelect").val(),"heure":$("#heureSelect").val()};
        tab.push(cases);
    }
    return tab;

}

