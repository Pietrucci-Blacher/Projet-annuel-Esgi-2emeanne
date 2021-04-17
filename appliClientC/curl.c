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

static size_t read_callback(char *ptr, size_t size, size_t nmemb, void *stream)
{
  size_t retcode;

  retcode = fread(ptr, size, nmemb, stream);

  return retcode;
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

        curl_easy_setopt(curl, CURLOPT_URL, "https://pa2021-esgi.herokuapp.com/appliC/connexionAppliC.php");

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

void sendExcelFTP(char *path){

    CURL *curl;
  CURLcode res;
  FILE * fp;
  struct stat file_info;

  char *url = "http://pa2021-esgi.herokuapp.com/appliC/uploadAppliC.php";

  stat(path, &file_info);

  fp = fopen(path, "rb");

  curl_global_init(CURL_GLOBAL_ALL);


  curl = curl_easy_init();
  if(curl) {

    curl_easy_setopt(curl, CURLOPT_READFUNCTION, read_callback);

    curl_easy_setopt(curl, CURLOPT_UPLOAD, 1L);

    curl_easy_setopt(curl, CURLOPT_URL, url);

    curl_easy_setopt(curl, CURLOPT_READDATA, fp);

    curl_easy_setopt(curl, CURLOPT_INFILESIZE_LARGE,
                     (curl_off_t)file_info.st_size);

    res = curl_easy_perform(curl);

    if(res != CURLE_OK)
      fprintf(stderr, "curl_easy_perform() failed: %s\n",
              curl_easy_strerror(res));

    curl_easy_cleanup(curl);
  }
  fclose(fp);

  curl_global_cleanup();

}
