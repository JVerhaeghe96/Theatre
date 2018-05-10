$(document).ready(function(){

    $("#boutonAjoutSpectateur").on("click", function(){ //  Affiche le formulaire pour ajouter un spectateur et assombrit l'arrière-plan
        $("#modal").fadeIn("slow");
        $("#formNewSpectator").fadeIn("slow");
    });

    $("#closeButton").on("click", function(){  //  permet de fermer le formulaire d'ajout d'un spectateur
        $("#modal").fadeOut("slow");
        $("#formNewSpectator").fadeOut("slow");
    });

    $("#titre").on('change',function () { // afficher les dates en fonction des titres
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
    var tabPlacesSelect =new Array();


    $("#boutonReserver").on('click',function () {
        var titre=$("#titreSelect").val();
        var specId=$("#specId").val();
        var remarque=$("#commentaire").val();

        tabPlacesSelect=transformerTableau(tabPlacesSelect);
       $.post('traitements/TraitementReservation.php?action=reservation', //URL
            {titre:titre,
            nbPlaces:nbrPlaces,
            commentaire:remarque,
            nom:specId,
            tabPlaces:tabPlacesSelect},
            function () { //fonction exécutée après le traitement
                alert("Réservation effectuée")
            });
    });


    $(".legende").on("click",function () { //sélectionner une des cases de la légende
        $("#etatSelect").attr("id","");
        $(this).attr("id","etatSelect");
        legende=$(this).attr("name");
    });

    $("#nbPlaces").on('change',function () { // récuper le nombre de places
        nbrPlaces=$(this).val();

    });

    $("td.tabSalle").on("click",function () { // changer l'etat de base de la case en fonction de la légende
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
                alert("Pas disponible");
                break;

            }

            default:{
                if($(this).attr("class")=="tabSalle dispo"){
                    if(nbSelect<nbrPlaces){
                        $(this).attr("class","tabSalle "+legende);   // remplacer la classe de la case par la la classe de la legende
                        nbSelect++;
                        tabPlacesSelect.push($(this));
                    }
                }
            }
        }

    })
});

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

