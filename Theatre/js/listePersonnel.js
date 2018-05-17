$(document).ready(function() {
    $("#consulterPersonnel").on("click",function () {
        $.post('traitements/TraitementListePersonnel.php',
            {
                titre:$("#titre").val()
            },function (data) {
                data=JSON.parse(data);
                console.log(data);

                table = "<table>" +
                    "<tr>" +
                    "<th>Titre</th>" +
                    "<th>Nom personnel</th>" +
                    "<th>Prénom personnel</th>" +
                    "<th>Fonction</th>" +
                    "<th>Nom personnage</th>" +
                    "<th>Prénom personnage</th>" +
                    "</tr>";

                for(var i = 0; i < data.length; i++){
                    table += "" +
                        "<tr>" +
                        "   <td>"+ data[i]['titre'] +"</td>" +
                        "   <td>"+ data[i]['Nompersonnel'] +"</td>" +
                        "   <td>"+ data[i]['Prenompersonnel'] +"</td>" +
                        "   <td>"+ data[i]['fonction'] +"</td>" +
                        "   <td>"+ data[i]['Nompersonnage'] +"</td>" +
                        "   <td>"+ data[i]['Prenompersonnage'] +"</td>" +
                        "</tr>";
                }

                table += "</table>";

                $("#listePersonnel").html(table);

            });

    });
});
