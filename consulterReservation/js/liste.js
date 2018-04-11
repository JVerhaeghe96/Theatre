
$(document).ready(function() {
    $("#titre").on('change', function () { // afficher les dates en fonction des titres
        $("#date").empty();
        $.post('traitements/TraitementReservation.php?action=getTitre', //URL
            {titre: $("#titre").val()},
            function (data) { //fonction exécutée après le traitement
                data = JSON.parse(data);
                data.map(function (value) {
                    $("#date").append("<option value='" + value['date'] + '|' + value['heure'] + "'>" + value['date'] + " " + value['heure']+"</option>");
                });
            });
    });

    $("#rechercherReservation").on('click',function () {
       var titre= $("#titre").val();
       var date=$("#date").val();
       var nom=$("#specId").val();
       var numReservation=$("#numReservation").val();

       $.post('traitements/TraitementListe.php',
           {titre:titre,date:date,nom:nom,numReservation:numReservation},
           function (data){
                if(!data){
                    alert("erreur");
                }
                else{
                    data=JSON.parse(data);
                    var chaises="";
                    for(var i=1;i<data[0]['nbSieges'];i++){
                        chaises+=', '+data[i]['cId'];

                    }
                    var tab="<table> " +
                        "       <tr>" +
                        "           <td>Numero de réservation</td>" +
                        "           <td>Tri</td>" +
                        "           <td>Nom</td>" +
                        "           <td>Nombre de places</td>" +
                        "           <td>Numéro des chaises</td>" +
                        "       </tr>" +
                        "       <tr>" +
                        "           <td>"+data[0]['resId']+"</td>" +
                                    "<td>"+data[0]['nom']+"</td>" +
                                    "<td>"+data[0]['prenom']+" "+data[0]['nom']+"</td>" +
                                    "<td>"+data[0]['nbSieges']+"</td>" +
                                    "<td>"+ data[0]['cId'] +chaises+"</td>" +
                        "       </tr>" +

                        "     </table>";

                    $("#afficheReservation").html(tab);

                }
           }




       );


    })
});