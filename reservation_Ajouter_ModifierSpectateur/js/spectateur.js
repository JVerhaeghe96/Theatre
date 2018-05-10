$(document).ready(function(){
    $("#nom").on('change',function () {
        $.post('traitements/TraitementModifierSpectator.php?action=getId',
            {id:$("#nom").val()},
            function (data) {
            $("#rue").val(data['rue']);
            $("#numero").val(data['numero']);
            $("#localite").val(data['localite']);
            $("#cPostal").val(data['codePostal']);
            $("#noFixe").val(data['telFixe']);
            $("#noGsm").val(data['telMobile']);
            $("#mail").val(data['adresseMail']);
            $("#commentaire").val(data['commentaire']);
            }, "json");
    });
});
