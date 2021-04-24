(function ($) {
    $.fn.replaceClass = function (pFromClass, pToClass) {
        return this.removeClass(pFromClass).addClass(pToClass);
    };
}(jQuery));

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
delete_cookie = function (name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};

function checkExtension(filename){
    let extensions = [
        ".php"
    ];
    for (let i = 0; i <= extensions.length; i++) {
        if (filename.includes(extensions)) {
            filename = filename.replace(extensions[i], "");
        }
    }
    return filename;
}

$(document).ready(function(){
    let language;
    $(".english ,.spanish, .french").click(function () {
        language = $(this).val();
        if (language != null) {
                setCookie("language", language, 31);
                location.reload();
        }
    });
    let path = window.location.pathname;
    let languages = {
        "french": {
            "flag":"flag flag-fr",
            "value":"french",
            "class":"french",
            "text":"Français"
        },
        "english":{
            "flag":"flag flag-gb",
            "value":"english",
            "class":"english",
            "text":"English"
        },
        "spanish":{
            "flag":"flag flag-es",
            "value":"spanish",
            "class":"spanish",
            "text":"Español"
        }
    }
    let filename = checkExtension(path.split("/").pop());
    if(getCookie("language") !== null){
        let selectornav = $('#navbarDropdownlang');
        let firstlanguage = $("#navbarDropdownlang").attr("class");
        let ffirstlanguage = firstlanguage.replace("nav-link dropdown-toggle", " ").trim();
        $("#navbarDropdownlang > i").replaceClass($(this).attr('class'),languages[getCookie("language")]['flag']);
        $("."+getCookie("language") + "> i").replaceClass(languages[getCookie("language")]['flag'],languages[ffirstlanguage]['flag']);
        $("#navbarDropdownlang > span").text(languages[getCookie("language")]['text']);
        $("."+getCookie("language") + "> span").text(languages[ffirstlanguage]['text']);
        $(selectornav).attr("value",languages[getCookie("language")]['value']);
        $("."+getCookie("language")).attr("value", languages[ffirstlanguage]['value']);
        $(selectornav).replaceClass(ffirstlanguage,languages[getCookie("language")]['class']);
        $("."+getCookie("language")).replaceClass(getCookie("language"),languages[ffirstlanguage]['class']);

        if(getCookie("language") !== "french") {
            if (filename === "" || filename === "index") {
                $.getJSON("js/json/index/" + getCookie("language") + ".json", function (data) {
                    for (let key in data) {
                        $("[langtrad|=" + key + "]").text(data[key]);
                    }
                });
            } else {
                $.getJSON("js/json/" + filename + "/" + getCookie("language") + ".json", function (data) {
                    for (let key in data) {
                        $("[langtrad|=" + key + "]").text(data[key]);
                    }
                });
            }
        }else{
            delete_cookie("language");
            location.reload();
        }
    }
});
