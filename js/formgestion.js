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
    $(".edituser").click(function (){
        let user_id = $(this).attr("user-id");
       $.post("request/ges/editprofileuser.php", {"userid": user_id}).then(function(res){
           if(res.includes("Unknow parameters")){alert("Unknow id");}
           let result = JSON.parse(res);
           $("#lastname").val(result['nom']);
           $("#firstname").val(result['prenom']);
           $("#status").val(result['status']);
           $("#city").val(result['ville']);
           $("#cpostal").val(result['codePostal']);
           $("#phonenum").val(result['numphone']);
           $("#address").val(result['adresse']);
           $("#email").val(result['email']);
           if(result['bannedAcount'] == true){
               $("#banacount").val("Oui");
               $("#banacount").addClass("border border-danger rounded border-3");
               if($("#bandiv").not(':has("#bantime")')){
                   $("#bandiv").append("<label class='m-2' for='bantime' id='labeltime'>Temps de ban</label>");
                   $("#bandiv").append("<input class='form-control mx-3 bantime text-center fw-bold' name='bantime' id='bantime' type='datetime-local' disabled>");
                   let date = new Date(result['bantime']);
                   $("#bantime").val(date.toDatetimeLocal());
               }
           }
           if(result['bannedAcount'] == false){
               $("#banacount").val("Non");
               $("#banacount").addClass("border border-success rounded border-3");
               $("#bandiv").remove("#bantime");
               $("#bandiv").remove("#labeltime");
               $("#banacount").addClass("ms-1 me-5");
           }
           if(result['status'] == "livreur"){
               $.post("request/ges/deliveryinfo.php", {"userid": user_id}).then(function(result2) {
                    let resultdelivery = JSON.parse(result2);
                    const inputdelivery = "<div class='input-group'><label class='m-2' for='brandvehicule'>Marque du véhicule : </label><input type='text' class='form-control mx-3' name='brandvehicule' id='brandvehicule' aria-describedby='brandvehicule' placeholder='Marque du Véhicule'><label class='m-2' for='ptacvehicule'>PTAC du Véhicule</label><input class='form-control mx-3' name='ptacvehicule' id='ptacvehicule' aria-describedby='ptacvehicule' placeholder='PTAC du véhicule'><label class='m-2' for='vehiculetype'>Type de vehicule</label><input class='form-control mx-3' name='vehiculetype' id='vehiculetype' aria-describedby='vehiculetype' placeholder='Type du véhicule'></div><br><div class='input-group'><label class='m-2' for='geozone'>Zone Géographique :</label><input type='text' class='form-control mx-3' name='geozone' id='geozone' aria-describedby='geozone' placeholder='Zone Géo'></div>";
                    $('#deliveryzone').append(inputdelivery);
                    $('#geozone').val(resultdelivery['zoneGeo']);
                    $('#brandvehicule').val(resultdelivery['brandvehicule']);
                    $('#ptacvehicule').val(resultdelivery['ptacvehicule']);
                    $('#vehiculetype').val(resultdelivery['vehiculetype']);

                    $('#drivinglicence').attr("href", window.location.origin + "/" + resultdelivery['lienPermis']);
                    $('#pointslicence').attr("href",  window.location.origin + "/" + resultdelivery['etatPermis']);
               });
           }
       });
    });
    $(".banuser").click(function (){
        let user_id = $(this).attr("user-id");
        const bantime = $("#bantime").val();
        $.post("request/ges/banuser.php", {"userid": user_id, "bantime": bantime});
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
