$(document).ready(function (){
    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /[a-z](-)?$/i.test(value);
    }, "Lettres seulement");
    $("#updateinfoform").validate({
       rules:{
           lastname:{
               maxlength:70,
               required: true,
               lettersonly: true
           },
           firstname:{
               required: true,
               maxlength: 60,
               lettersonly: true
           },
           email:{
               required : true,
               email : true
           },
           address:{required:true},
           city:{
               required:true,
               lettersonly: true
           },
           cpostal:{
               required:true,
               minlength:5,
               maxlength:5,
               digits: true
           },
           phonenum:{
               required:true,
               minlength: 10,
               maxlength: 10,
               digits: true
           },
           status:{
               required:true,
           }
       },
       messages:{
           lastname: {
               required: 'Veuillez insérer votre nom',
               maxlength: 'Le nom inséré est beaucoup trop long'
           },
           firstname: {
               maxlength: 'Le prénom inséré est beaucoup trop long',
               required: 'Veuilez entrer un prénom'
           },
           email: {
               required: 'Veuillez insérer un email',
               email:'Veuillez entrer un email'
           },
           address: {
               required: 'Veuillez insérer votre adresse'
           },
           city: {
               required: 'Veuillez insérer votre ville'
           },
           cpostal: {
               required: 'Veuillez insérer votre code postal',
               minlength: 'Votre code postal doit être strictement égal à 5 nombres',
               maxlength: 'Votre code postal doit être strictement égal à 5 nombres',
               digits: 'Veuillez n\'insérer que des chiffres'
           },
           phonenum: {
               required: 'Veuillez insérer votre numéro de téléphone',
               minlength: 'Votre code postal doit être strictement égal à 10 nombres',
               maxlength: 'Votre code postal doit être strictement égal à 10 nombres',
               digits: 'Veuillez n\'insérer que des chiffres'
           },
           status: {
               required: 'Veuillez sélectionner votre statut juridique'
           }
       }
    });

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
                   $("#bantime").val(result['bantime']);
                   console.log("ok");
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
        let user_id = $(".deleteuser").attr("user-id");
        if(window.confirm("Voulez vous supprimer l'utilisateur ?")){
            $.post("request/ges/deleteuser.php",{"userid": user_id}).then(function(){
                window.location.reload();
            });
        }
    });
});