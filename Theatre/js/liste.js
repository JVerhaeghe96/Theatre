
$(document).ready(function() {
    var liste = "";
    // afficher les dates en fonction des titres
    $("#titre").on('change', function () {
        $("#date").empty();
        $.post('traitements/TraitementListe.php?action=getDate',
            {titre: $("#titre").val()},
            function (data) {
                data = JSON.parse(data);
                data.map(function(value){
                    // ajout des éléments dans le dom
                    $("#date").append("<option value='" + value['date'] + '|' + value['heure'] + "'>" + value['date'] + " " + value['heure']+"</option>");
                });
            });
    });

    // rechercher une réservation
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
                data = JSON.parse(data);
                if(data == false || data.length == 0){
                    $(".alert-danger").show();
                    $("#afficheReservation").html("");
                }
                else{
                    // afficher le tableau d'une réservation
                    $(".alert").hide();
                    var donneesReservation = '<script src=\'js/supprimerReservation.js\'></script><br/>';
                    var i = 0;
                    while (i < data.length) {
                        var resId = data[i]['resId'];
                        donneesReservation += "" +
                            "<tr class='reservationElement'>" +
                            "   <td class='idReservation'>" + resId + "</td>" +
                            "   <td>" + data[i]['prenom'] + " " + data[i]['nom'] + "</td>" +
                            "   <td>" + data[i]['nbSieges'] + "</td>";

                        var chaises = data[i]["cId"];
                        while (data[i + 1] != undefined && resId == data[i + 1]['resId']) {
                            chaises += ", " + data[i + 1]["cId"];
                            i++;
                        }

                        donneesReservation += "" +
                            "   <td>" + chaises + "</td>" +
                            "<td><input type='button' class='supprimer button' value='Supprimer'/></td>" +
                            "</tr>";
                        i++;
                    }

                    var tab = "" +
                        "<table>" +
                        "   <tr>" +
                        "       <td>Numero de réservation</td>" +
                        "       <td>Nom</td>" +
                        "       <td>Nombre de places</td>" +
                        "       <td>Numéro des chaises</td>" +
                        "       <td>Supprimer</td>" +
                        "   </tr>" +
                        donneesReservation +
                        "</table>";

                    $("#afficheReservation").html(tab);

                }
           }
       );
    });

    // autocompletion
    $("#nom").autocomplete({
        source:'traitements/TraitementAutocomplete.php?action=getNom'
    });

    $("#prenom").autocomplete({
        source:'traitements/TraitementAutocomplete.php?action=getPrenom'
    });

    // rechercher un spectateur
    $("#rechercherSpectateur").on('click',function () {
        var nom=$("#nom").val();
        var prenom=$("#prenom").val();

        $.post('traitements/TraitementListe.php?action=spectateur',
            {
                nom:nom,
                prenom:prenom
            },
            function (data){
                data = JSON.parse(data);
                if(data == undefined || data == null || data == false || data.length == 0){
                    $("#afficherSpectateur").html("");
                    $(".alert-danger").show();
                }
                else{
                    // afficher le tableau un specateur
                    $(".alert-danger").hide();

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

    // afficher ou masquer la liste de dates
    $(".radioListe").on("change", function(){
        if($(this).val() == "listeReservation"){
            $("#listeDate").show();
            liste = "listeReservation";
        }else{
            $("#listeDate").hide();
            liste = "listeSpectateur";
        }
    });

    // consulter liste
    $("#consulterListe").on('click',function () {
        if(liste == "listeSpectateur"){
            $.post('traitements/TraitementListe.php?action=listeSpectateur',
                {},
                function(data){
                    data = JSON.parse(data);
                    if(data == false || data.length == 0){
                        $("#afficherListe").html("");
                        $(".alert-danger").show();
                    }else {
                        // afficher le tableau liste des spectateurs
                        $(".alert-danger").hide();
                        var donneesSpectateurs = "";
                        for (var i = 0; i < data.length; i++) {
                            donneesSpectateurs +=
                                "<tr>" +
                                "   <td>" + data[i]['nom'] + "</td>" +
                                "   <td>" + data[i]['prenom'] + "</td>" +
                                "   <td>" + data[i]['telFixe'] + "</td>" +
                                "   <td>" + data[i]['telMobile'] + "</td>" +
                                "   <td>" + data[i]['adresseMail'] + "</td>" +
                                "   <td>" + data[i]['commentaire'] + "</td>" +
                                "</tr>";
                        }
                        var tab = "" +
                            "<table>" +
                            "<tr>" +
                            "   <td>Nom</td>" +
                            "   <td>Prénom</td>" +
                            "   <td>Téléphone Fixe</td>" +
                            "   <td>Téléphone Mobile</td>" +
                            "   <td>Adresse Mail</td>" +
                            "   <td>Commentaire</td>" +
                            "</tr>" +
                            donneesSpectateurs +
                            "</table>";

                        $("#afficherListe").html(tab);
                    }
                });
        }else
        if(liste == "listeReservation"){
            $.post('traitements/TraitementListe.php?action=listeReservation',
                {
                    date:$("#date").val()
                },
                function(data){
                    data = JSON.parse(data);
                    if(data == false || data.length == 0){
                        $("#afficherListe").html("");
                        $(".alert-danger.pasTrouve").show();
                    }else {
                        // afficher la liste des réservations
                        $(".alert-danger").hide();

                        var donneesReservation = '<script src=\'js/supprimerReservation.js\'></script><br/>';
                        var i = 0;
                        while (i < data.length) {
                            var resId = data[i]['resId'];
                            donneesReservation += "" +
                                "<tr class='reservationElement'>" +
                                "   <td class='idReservation'>" + resId + "</td>" +
                                "   <td>" + data[i]['prenom'] + " " + data[i]['nom'] + "</td>" +
                                "   <td>" + data[i]['nbSieges'] + "</td>";

                            var chaises = data[i]["cId"];
                            while (data[i + 1] != undefined && resId == data[i + 1]['resId']) {
                                chaises += ", " + data[i + 1]["cId"];
                                i++;
                            }

                            donneesReservation += "" +
                                "   <td>" + chaises + "</td>" +
                                "<td><input type='button' class='supprimer button' value='Supprimer'/></td>" +
                                "</tr>";
                            i++;
                        }

                        var tab = "" +
                            "<table>" +
                            "   <tr>" +
                            "       <td>Numero de réservation</td>" +
                            "       <td>Nom</td>" +
                            "       <td>Nombre de places</td>" +
                            "       <td>Numéro des chaises</td>" +
                            "       <td>Supprimer</td>" +
                            "   </tr>" +
                            donneesReservation +
                            "</table>";

                        $("#afficherListe").html(tab);
                    }
                });
        }
    });
});