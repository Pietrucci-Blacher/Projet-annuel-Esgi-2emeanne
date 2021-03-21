#include <common.h>

void verifyParcel(){
    const gchar *weight;
    weight = gtk_entry_get_text(GTK_ENTRY(entry[0]));
    int temp = atoi(weight);
    char weightNb[12];
    sprintf(weightNb,"%d",temp);

    //printf("%s",weightNb);
    if (strcmp(weightNb,"")==0 || strcmp(weightNb,"0")==0){
        gtk_label_set_text(GTK_LABEL(label), "Poids non valide");
    }else if (gtk_toggle_button_get_active(GTK_TOGGLE_BUTTON(radioBtn[1])) == TRUE && atoi(weight)>30){
        gtk_label_set_text(GTK_LABEL(label), "Poids trop important pour la livraison express (max 30 kg)");
    }else{
        strcpy(infoParcel[0][actualIndex], idEntreprise);
        strcpy(infoParcel[1][actualIndex], weightNb);
        if(gtk_toggle_button_get_active(GTK_TOGGLE_BUTTON(radioBtn[1])) == TRUE){
            strcpy(infoParcel[2][actualIndex], "express");
        }else{
            strcpy(infoParcel[2][actualIndex], "standard");
        }
        changeScreen(GTK_BUTTON(btn[0]),(gpointer)4);
    }
}

void verifyParcelClient(){
    const gchar *clientInfo;
    char error[255];
    char errorList[5][50]={" Nom"," Prénom"," Adresse"," Ville"," Code Postal"};
    int count = 0;

    strcpy(error,"Vérifiez les champs : ");
    for (int i = 0; i<5; i++){
        clientInfo = gtk_entry_get_text(GTK_ENTRY(entry[i]));
        if(strcmp(clientInfo,"")==0){
            if(count != 0){
                strcat(error,",");
            }
            strcat(error,errorList[i]);
            count +=1;
        }
    }

    if(count == 0){
        for(int i = 3 ; i<10;i++){
            clientInfo = gtk_entry_get_text(GTK_ENTRY(entry[i-3]));
            strcpy(infoParcel[i][actualIndex],clientInfo);
        }
        generateQrcode();
        dataToExcell();
        actualIndex = tmpIndex;
        changeScreen(GTK_BUTTON(btn[0]),(gpointer)2);
    }else{
        gtk_label_set_text(GTK_LABEL(label),g_locale_to_utf8(error,-1, NULL, NULL, NULL) );
    }
}

void abortParcel(){
    actualIndex -= 1;
    changeScreen(GTK_BUTTON(btn[0]),(gpointer)2);
}

void modifyParcel(GtkButton *btn, gpointer index){
    tmpIndex = actualIndex;
    actualIndex = GPOINTER_TO_INT(index);
    changeScreen(GTK_BUTTON(&btn[0]),(gpointer)3);
}

void deleteParcel(GtkButton *btn, gpointer index){

    int nbIndex = GPOINTER_TO_INT(index);

    deleteQrcode(nbIndex);

    for(int i  = 0; i< 10; i++){
        memset(infoParcel[i][nbIndex],0,sizeof(infoParcel[i][nbIndex]));
        strcpy(infoParcel[i][nbIndex],infoParcel[i][actualIndex]);
        memset(infoParcel[i][actualIndex],0,sizeof(infoParcel[i][actualIndex]));
    }

    dataToExcell();

    actualIndex -= 1;
    changeScreen(GTK_BUTTON(&btn[0]),(gpointer)5);
}
