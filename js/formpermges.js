$(document).ready(function (){
    $('.showperm').hide();
    $('.showpermis').click(function(){
        let userid = $(this).attr('user-id');
        $.post("request/ges/deliveryinfo.php",{"userid": userid}).then(function(res){
            let result = JSON.parse(res);
            $('#drivinglicence').attr("href",result['etatPermis']);
            $('#pointslicence').attr("href",result['lienPermis']);
            $('.showperm').show();
        });
        $('#permval').click(function(){
            if($('#permval').is(":checked")){
                if(window.confirm("Etes vous sûr de valider le permis de l'utilisateur ?")){
                    $.post("request/ges/permischeck.php",{"userid": userid, "perm": true});
                }
            }
        });
    });
});