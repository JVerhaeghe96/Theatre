$(document).ready(function(){

    // archive
    $("#archiver").on("click", function(){
        $.post("traitements/TraitementArchive.php",
            {
                titre: $("#titre").val()
            },
            function(){
                $(".alert").hide();
                $(".alert-success").show();
            }).fail(function () {
                $(".alert").hide();
                $(".alert-danger").show();
            });
    });
});