$(document).ready(function(){

    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /[a-z](-)?$/i.test(value);
    }, "Lettres seulement");

    $("#deliveryformcheck").validate({
        rules:{
            driveupload:{
                required:true,
                accept: "image/png, image/jpeg"
            },
            geozone:{
                required: true
            },
            driveuploadpoints:{
                required: true,
                accept: "image/png, image/jpeg"
            },
            vehiculetype:{
                required: true,
                lettersonly : true
            },
            brandvehicule:{
                required:true,
                lettersonly: true
            },
            ptacvehicule:{
                required:true,
                digits: true,
                min: 0
            }
        },
        messages:{
            driveupload:{
                required: 'Vous devez renseigner votre permis de conduire',
                accept: 'Attention l\'extension de votre fichier ne doit pas être différente du PDF'
            },
            geozone:{
                required: 'Vous devez renseigner votre zone de géographique'
            },
            driveuploadpoints:{
                required: 'Veuillez uploader une capture d\'écran qui atteste du nombre de points sur votre permis, la plaque d\'immatriculation doit être visible sur la capture d\'écran',
                accept: 'Attention l\'extension de votre fichier ne doit pas être différente du PDF, du PNG, du JPG ou du BMP'
            },
            vehiculetype:{
                required: 'Veuillez renseigner le type de votre véhicule',
                lettersonly : 'Attention, le type de votre véhicule ne doit pas contenir de chiffres ou de caractères spéciaux'
            },
            brandvehicule: {
                required: 'Veuillez renseigner la marque de votre véhicule',
                lettersonly: 'Attention, la marque de votre véhicule ne doit pas contenir de chiffres ou de caractères spéciaux'
            },
            ptacvehicule:{
                required: 'Veuillez renseigner le PTAC de votre véhicule',
                digits: 'Le PTAC ne doit pas contenir autre chose que des chiffres',
                min: 'Le PTAC ne peut pas être inférieur à 0'
            }
        }
    });
});
