$(document).ready(function(){
    $("#extractList").on("click", function(){
        if(liste == "listeReservation"){
            $.post('traitements/TraitementExtractionListes.php?action=listeReservation',
                {
                    date:$("#date").val()
                },
                function(data){
                    $("#lien").show();
                    $("#lien").attr("href", "jasper_reports/"+data+".pdf");
                }
            );
        }
    });

    $(".radioListe").on("change", function(){
        if($(this).val() == "listeReservation"){
            $("#listeDate").show();
            liste = "listeReservation";
        }else{
            $("#listeDate").hide();
            liste = "listeSpectateur";
        }
    });

    $("#titre").on('change', function () { // afficher les dates en fonction des titres
        $("#date").empty();
        $.post('traitements/TraitementListe.php?action=getDate', //URL
            {titre: $("#titre").val()},
            function (data) { //fonction exécutée après le traitement
                data = JSON.parse(data);
                data.map(function (value) {
                    $("#date").append("<option value='" + value['date'] + '|' + value['heure'] + "'>" + value['date'] + " " + value['heure']+"</option>");
                });
            });
    });
});