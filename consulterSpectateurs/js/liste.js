
$(document).ready(function() {
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

    $("#rechercherReservation").on('click',function () {
       var titre= $("#titre").val();
       var date=$("#date").val();
       var nom=$("#nom").val();
       var prenom=$("#prenom").val();
       var numReservation=$("#numReservation").val();

       $.post('traitements/TraitementListe.php?action=reservation',
           {
               titre:titre,
               date:date,
               nom:nom,
               prenom:prenom,
               numReservation:numReservation
           },
           function (data){
                if(data == false || data == undefined){
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
    });

    $("#nom").autocomplete({
        source:'traitements/TraitementAutocomplete.php?action=getNom'
    });

    $("#prenom").autocomplete({
        source:'traitements/TraitementAutocomplete.php?action=getPrenom'
    });

    $("#rechercherSpectateur").on('click',function () {
        var nom=$("#nom").val();
        var prenom=$("#prenom").val();

        $.post('traitements/TraitementListe.php?action=spectateur',
            {
                nom:nom,
                prenom:prenom,
            },
            function (data){
                if(data == false || data == undefined){
                    alert("erreur");
                }
                else{
                    console.log(data);
                    data=JSON.parse(data);

                    var tab="<table> " +
                        "       <tr>" +
                        "           <td>Nom</td>" +
                        "           <td>Prenom</td>" +
                        "           <td>Téléphone Fixe</td>" +
                        "           <td>Téléphone Mobile</td>" +
                        "           <td>Adresse mail</td>" +
                        "           <td>Commentaire</td>"+
                        "       </tr>" +
                        "       <tr>" +
                        "           <td>"+data['nom']+"</td>" +
                        "           <td>"+data['prenom']+"</td>" +
                        "           <td>"+data['telFixe']+"</td>" +
                        "           <td>"+data['telMobile']+"</td>" +
                        "           <td>"+data['adresseMail']+"</td>"+
                        "           <td>"+ data['commentaire']+"</td>" +
                        "       </tr>" +

                        "     </table>";

                    $("#afficherSpectateur").html(tab);

                }
            }
        );
    });

    $(".radioListe").on("change", function(){
        if($(this).val() == "listeReservation")
            $("#listeDate").show();
        else
            $("#listeDate").hide();
    });

    $("#consulterListe").on('click',function () {
        var titre= $("#titre").val();
        var date=$("#date").val();
        var nom=$("#nom").val();
        var prenom=$("#prenom").val();
        var numReservation=$("#numReservation").val();

        $.post('traitements/TraitementListe.php?action=liste',
            {
                titre:titre,
                date:date,
            },
            function (data){
                if(data == false || data == undefined){
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

                    $("#afficherListeReservation").html(tab);

                }
            }
        );
    });





});