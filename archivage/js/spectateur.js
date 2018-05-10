$(document).ready(function(){
    $("#nom").on('blur',function () {
        if($("#prenom").val()!=""){
            getSpectator();
        }
    });

    $("#prenom").on('blur',function () {
        if($("#nom").val()!=""){
            getSpectator();
        }
    });
});

function getSpectator() {
    $.post('traitements/TraitementModifierSpectator.php?action=getId',
        {nom:$("#nom").val(),prenom:$("#prenom").val()},
        function (data) {
            $("#nom").val(data['nom']);
            $("#prenom").val(data['prenom']);
            $("#rue").val(data['rue']);
            $("#numero").val(data['numero']);
            $("#localite").val(data['localite']);
            $("#cPostal").val(data['codePostal']);
            $("#noFixe").val(data['telFixe']);
            $("#noGsm").val(data['telMobile']);
            $("#mail").val(data['adresseMail']);
            $("#commentaire").val(data['commentaire']);
        }, "json");
}

