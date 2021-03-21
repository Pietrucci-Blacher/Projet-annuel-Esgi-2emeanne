#include <common.h>

size_t returnCodeCurl(char *ptr, size_t size, size_t nmemb, void *stream){
    char returnCode;
    returnCode = *ptr;

    //printf("%c",returnCode);

    // 1 : erreur id | 2 : erreur mdp | 3 : succés
    if(returnCode == '1'){
        gtk_label_set_text(GTK_LABEL(label), "Merci de vous inscrire d'abord sur le site");
    }else if (returnCode == '2'){
        gtk_label_set_text(GTK_LABEL(label), g_locale_to_utf8("Merci de vérifier votre mot de passe",-1, NULL, NULL, NULL));
    }else if (returnCode == '3'){
        strcpy(idEntreprise,gtk_entry_get_text(GTK_ENTRY(entry[0])));
        //printf("%s",idEntreprise);
        checkExcel();
        changeScreen(GTK_BUTTON(btn[0]),(gpointer)2);
        //gtk_label_set_text(GTK_LABEL(label), g_locale_to_utf8("Connexion réussi",-1, NULL, NULL, NULL));
    }

    return size*nmemb;
}

void connexion(){
    const gchar *id;
    const gchar *mdp;
    char postContent[255];

    id = gtk_entry_get_text(GTK_ENTRY(entry[0]));
    mdp = gtk_entry_get_text(GTK_ENTRY(entry[1]));

    CURL *curl;
    CURLcode res;
    curl_global_init(CURL_GLOBAL_ALL);
    curl = curl_easy_init();

    if(curl) {
        strcpy(postContent,"id=");
        strcat(postContent,curl_escape(id,0));
        strcat(postContent,"&mdp=");
        strcat(postContent,curl_escape(mdp,0));
        //printf("%s",postContent);

        curl_easy_setopt(curl, CURLOPT_URL, "https://pa2021-esgi.herokuapp.com/connexionAppliC.php");

        curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0L); //skip verif SSL
        curl_easy_setopt(curl, CURLOPT_SSL_VERIFYHOST, 0L);

        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, postContent);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, returnCodeCurl);
        res = curl_easy_perform(curl);

        if(res != CURLE_OK){
          fprintf(stderr, "curl_easy_perform() failed: %s\n", curl_easy_strerror(res));
        }

        curl_easy_cleanup(curl);
    }
    curl_global_cleanup();
}

void sendExcelFTP(){

    CURL *curl;
    CURLcode res;
    FILE *file;

    file = fopen("new.csv", "rb");

    curl = curl_easy_init();
    if(curl) {

    curl_easy_setopt(curl, CURLOPT_URL,"ftp://");

    curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0L);
    curl_easy_setopt(curl, CURLOPT_SSL_VERIFYHOST, 0L);

    curl_easy_setopt(curl, CURLOPT_UPLOAD, 1L);

    curl_easy_setopt(curl, CURLOPT_READDATA, file);

    curl_easy_setopt(curl, CURLOPT_VERBOSE, 1L);

    res = curl_easy_perform(curl);

    if(res != CURLE_OK) {
      fprintf(stderr, "curl_easy_perform() failed: %s\n",
              curl_easy_strerror(res));

    }

    curl_easy_cleanup(curl);
    }
    fclose(file);

}
