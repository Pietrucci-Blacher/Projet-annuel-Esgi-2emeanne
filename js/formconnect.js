$(document).ready(function () {
    $("#showpassword").click(function(e){
        $(this).is(':checked') ? $('#password').attr('type', 'text') : $('#password').attr('type', 'password');
    });

    $("#formconnectcheck").validate({
        rules: {
            email:{
                required : true, 
                email : true
            }, 
            password : {
                required : true
            }
        }, 
        messages:{
            email:{
                required: 'Veuillez insérer un email', 
                email:'Veuillez entrer un email valide du type : test@test.com'
            }, 
            password:{
                required: 'Veuillez insérer votre mot de passe'
            }
        }
    }); 
});