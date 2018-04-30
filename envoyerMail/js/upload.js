window.onload = init;

function init() {

    var form = new FormData();

    var fileInput = document.querySelector('#file');
    var progress = document.querySelector('#progress');

    fileInput.addEventListener('change', function() {
        if(fileInput.files.length > 0){
            $(".alert").hide();
            $(".alert-warning").show();

            var xhr = getXHR();

            if(xhr == null){
                $(".alert").hide();
                $(".alert-danger.noXHR").show();
            }else{
                xhr.open('POST', 'utils/upload.php');

                $("#bouton").prop("disabled", true);

                progress.style.display = "block";

                xhr.upload.addEventListener('progress', function(e) {
                    progress.value = e.loaded;
                    progress.max = e.total;
                });

                xhr.addEventListener('load', function(){
                    if(xhr.status == 200){
                        if(xhr.responseText[0] != '<'){
                            var responseText = JSON.parse(xhr.responseText);
                            if(responseText[0]["error"] == 1){
                                $(".alert").hide();
                                $(".alert-danger.fail").show();
                            }else{
                                $("#bouton").prop("disabled", false);
                                $(".alert").hide();
                                $(".alert-success").show();
                            }
                        }else{
                            $(".alert").hide();
                            $(".alert-danger.fileSize").show();
                        }
                    }else{
                        $(".alert").hide();
                        $(".alert-danger.fileSize").show();
                    }
                });

                for(var i = 0; i < fileInput.files.length; i++){
                    form.append('file'+i, fileInput.files[i]);
                }
                xhr.send(form);
            }
        }else{
            $(".alert").hide();
            $("#bouton").prop("disabled", false);
        }
    });

}

function getXHR(){
    var xhr = null;
    if(window.XMLHttpRequest || window.ActiveXObject){
        try{
            xhr = new XMLHttpRequest();
        }catch(e){
            try{
                xhr = new ActiveXObject("Msxml12.XMLHTTP");
            }catch(e){
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
        }
    }else{
        xhr = null;
    }

    return xhr;
}
