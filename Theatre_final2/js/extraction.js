$(document).ready(function(){
    // extraction liste réservation
    $("#extractList").on("click", function(){
        $("#lien").hide();

        afficherChargement();

        if(liste == "listeReservation"){
            $.post('traitements/TraitementExtractionListes.php?action=listeReservation',
                {
                    date:$("#date").val()
                },
                function(data){
                    $("#lien").show();
                    $("#lien").attr("href", "jasper_reports/"+data+".pdf"); // mettre le fichier dans le lien
                    masquerChargement();
                }
            );
        }else if(liste == "listeSpectateur"){ // extraction liste specateurs
            $.post('traitements/TraitementExtractionListes.php?action=listeSpectateur',
                {
                },
                function(data){
                    $("#lien").show();
                    $("#lien").attr("href", "jasper_reports/"+data+".pdf"); // mettre le fichier dans le lien
                    masquerChargement();
                }
            );
        }
    });

    // afficher la liste de dates
    $(".radioListe").on("change", function(){
        if($(this).val() == "listeReservation"){
            $("#listeDate").show();
            liste = "listeReservation";
        }else{
            $("#listeDate").hide();
            liste = "listeSpectateur";
        }
    });

    // afficher les dates en fonction des titres
    $("#titre").on('change', function () {
        $("#date").empty();
        $.post('traitements/TraitementListe.php?action=getDate',
            {titre: $("#titre").val()},
            function (data) {
                data = JSON.parse(data);
                data.map(function (value) {
                    // ajout des éléments dans le dom
                    $("#date").append("<option value='" + value['date'] + '|' + value['heure'] + "'>" + value['date'] + " " + value['heure']+"</option>");
                });
            });
    });

    // extraire etiquette
    $("#extraireEtiquettes").on("click", function(){
        $("#lien").hide();
        afficherChargement();
        $.post("traitements/TraitementEtiquettes.php",
            {
                inclureMail: $("#mailEtiquette").prop("checked") ? "true" : "false"
            },
            function(data){
                masquerChargement();
                $("#lien").show();
                $("#lien").attr("href", "jasper_reports/"+data+".pdf"); // mettre le fichier dans le lien
            });
    });
});

function afficherChargement(){
    $("#modal").fadeIn("slow");
    $("#chargement").fadeIn("slow");
}

function masquerChargement(){
    $("#modal").fadeOut("slow");
    $("#chargement").fadeOut("slow");
}