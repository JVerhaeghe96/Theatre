$(document).ready(function(){
    $("#archiver").on("click", function(){
        $.ajax({
            url: "traitements/TraitementArchive.php",
            type: "POST",
            data: "titre="+$("#titre").val(),
            datatype: "text",
            success: function(){
                $(".alert").hide();
                $(".alert-success").show();
            }
        });
    });
});