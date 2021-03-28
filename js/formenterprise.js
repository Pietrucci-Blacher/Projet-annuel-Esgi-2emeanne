$(document).ready(function(){
    $("#enterpiseformcheck").validate({
        rules:{
            siret:{
                required:true,
                digits: true,
                minlength:14,
                maxlength:14
            }
        },
        messages:{
            siret:{
                required: 'Veuillez insérer votre SIRET',
                digits: 'Votre SIRET ne doit contenir que des chiffres',
                minlength: 'Votre SIRET doit avoir une taille de 14 chiffres',
                maxlength: 'Votre SIRET doit avoir une taille de 14 chiffres'
            }
        }
    });
});