Date.prototype.toDatetimeLocal =
    function toDatetimeLocal() {
        let
            date = this,
            ten = function (i) {
                return (i < 10 ? '0' : '') + i;
            },
            YYYY = date.getFullYear(),
            MM = ten(date.getMonth() + 1),
            DD = ten(date.getDate()),
            HH = ten(date.getHours()),
            II = ten(date.getMinutes()),
            SS = ten(date.getSeconds())
        ;
        return YYYY + '-' + MM + '-' + DD + 'T' +
            HH + ':' + II + ':' + SS;
    };


$(document).ready(function (){
    $('#modifyzone').hide();
    $(".edituser").click(function (){
        let user_id = $(this).attr("user-id");
        $.post("request/ges/editprofileuser.php", {"userid": user_id}).then(function(res){
           if(res.includes("Unknow parameters")){alert("Unknow id");}
           let result = JSON.parse(res);
           if(!(Object.keys(result) === 0)){
               $('#modifyzone').show();
           }
           $("#lastname").val(result['nom']);
           $("#firstname").val(result['prenom']);
           $("#status").val(result['status']);
           $("#city").val(result['ville']);
           $("#zipcode").val(result['codePostal']);
           $("#phonenum").val(result['numphone']);
           $("#address").val(result['adresse']);
           $("#email").val(result['email']);
           $("#birthdate").val(result['birthdate']);
           $("#banacount").addClass("border rounded border-3");
           if(result['bannedAcount'] == true){
               $("#banacount").val("Oui");
               if($("#banacount").hasClass("border-success")) {
                   $("#banacount").removeClass("border-success");
                   $("#banacount").addClass("border-danger");
               }else{
                   $("#banacount").addClass("border-danger");
               }
               $("#labeltime").show();
               $("#bantime").show();
               let date = new Date(result['bantime']);
               $("#bantime").val(date.toDatetimeLocal());
           }else{
               $("#banacount").val("Non");
               if($("#banacount").hasClass("border-danger")){
                   $("#banacount").removeClass("border-danger");
                   $("#banacount").addClass("border-success");
               }else {
               $("#banacount").addClass("border-success");
               }
               $("#labeltime").hide();
               $("#bantime").hide();
           }

           if(result['status'] == "livreur"){
               $.post("request/ges/deliveryinfo.php", {"userid": user_id}).then(function(result2) {
                    let resultdelivery = JSON.parse(result2);
                    if(!(Object.keys(resultdelivery) === 0)){
                        $("#deliveryzone").show();
                    }
                    $('#brandvehicule').val(resultdelivery['brandvehicule']);
                    $('#ptacvehicule').val(resultdelivery['ptacvehicule']);
                    $('#vehiculetype').val(resultdelivery['vehiculetype']);
                    $('#depot').val(resultdelivery['depot']);
                    $('#radiusdepot').val(resultdelivery['zoneGeo']);
               });
           }else{
               $("#deliveryzone").hide();
           }
       });
    });
    
    $(".banuser").click(function (){
        let user_id = $(this).attr("user-id");
        $(".banuserval").click(function(){
            const bantime = $("#bantimeinp").val();
            const todaydate = new Date();
            const bantdate = new Date(bantime);
            if(bantdate > todaydate){
                $.post("request/ges/banuser.php", {"userid": user_id, "bantime": bantime});
            }else{
                window.alert("La date insérée est inférieur à la date d'aujourd'hui");
            }
        });
    });

    $(".deleteuser").click(function (){
        let user_id = $(this).attr("user-id");
        if(window.confirm("Voulez vous supprimer l'utilisateur ?")){
            $.post("request/ges/deleteuser.php",{"userid": user_id}).then(function(){
                window.location.reload();
            });
        }
    });
});
