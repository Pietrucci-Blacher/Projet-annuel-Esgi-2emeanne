$(document).ready(function () {
    $("#showPassword").click(function(e){
        $(this).is(':checked') ? $('#password').attr('type', 'text') : $('#password').attr('type', 'password');
        $(this).is(':checked') ? $('#passwordcheck').attr('type', 'text') : $('#passwordcheck').attr('type', 'password');
    });
    // Vérification du mot de passe
    $.validator.addMethod("passwordcheck", function (value, element){
        return this.optional( element ) || /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/.test(value);
    }, "Veuillez insérer un mot de passe valide");
    // Vérification de présence de lettres seulement
    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /[a-z](-)?$/i.test(value);
    }, "Lettres seulement");
    $.validator.addMethod("selectedgender", function(value, element) {
        return this.optional(element) || /\b(?:Monsieur|Madame)\b$/i.test(value);
    }, "Veuillez choisir un dénominatif");

    $("#formcheck").validate({
        rules: {
            gender:{
                required:true,
                selectedgender: true
            },
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
            emailcheck:{
                required: true,
                email : true,
                equalTo: "#email"
            },
            password:{
                required: true,
                passwordcheck: true
            },
            passwordcheck:{
                required: true,
                equalTo: "#password"
            },
            address:{required:true},
            city:{
                required:true,
                lettersonly: true
            },
            zipcode:{
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
            gender: {
                required:'Veuillez choisir un dénominatif'
            },
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
            emailcheck: {
                required: 'Veuillez insérer votre email',
                email:'Veuillez confirmer votre email',
                equalTo: 'Le mail inséré doit être équivalent au premier'
            },
            password: {
                required: 'Veuillez insérer votre mot de passe'
            },
            passwordcheck:{
                required: 'Veuillez insérer le mot de passe précédemment inséré',
                equalTo: 'Le mot de passe inséré doit être équivalent au premier'
            },
            address: {
                required: 'Veuillez insérer votre adresse'
            },
            city: {
                required: 'Veuillez insérer votre ville'
            },
            zipcode: {
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
});
