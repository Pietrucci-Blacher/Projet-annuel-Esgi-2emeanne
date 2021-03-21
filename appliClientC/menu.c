#include <common.h>

void connexionScreen(){
    addLabel("<span size=\"20000\">ID</span>");

    addEntry(0,"");

    addLabel("<span size=\"20000\">MDP</span>");

    addEntry(1,"");

    gtk_entry_set_visibility(GTK_ENTRY(entry[1]), FALSE);
    gtk_entry_set_invisible_char(GTK_ENTRY(entry[1]),'*');

    label = gtk_label_new(NULL);
    gtk_container_add(GTK_CONTAINER(boxFrame),label);

    btn[0] = gtk_button_new_with_label("Connexion");
    g_signal_connect(G_OBJECT(btn[0]), "clicked", G_CALLBACK(connexion), NULL);
    gtk_box_pack_start(GTK_BOX(boxFrame), btn[0], TRUE, FALSE, 0);
    gtk_widget_show_all(window);
}

void newParcelScreen(){
    if(actualIndex + 1 == 25){
        gtk_label_set_text(GTK_LABEL(label),"Maximum de colis atteint en attente merci de les envoyer avant de continuer");
    }else{
        actualIndex += 1;
        tmpIndex = actualIndex;
        changeScreen(GTK_BUTTON(btn[0]),(gpointer)3);
    }
}

void menuScreen(){
    label = gtk_label_new(NULL);
    gtk_container_add(GTK_CONTAINER(boxFrame),label);

    btn[0] = gtk_button_new_with_label("Ajouter un colis");

    g_signal_connect(G_OBJECT(btn[0]), "clicked", G_CALLBACK(newParcelScreen),NULL);
    gtk_box_pack_start(GTK_BOX(boxFrame), btn[0], TRUE, FALSE, 0);

    btn[1] = gtk_button_new_with_label("Validation des colis");
    g_signal_connect(G_OBJECT(btn[1]), "clicked", G_CALLBACK(changeScreen), GINT_TO_POINTER(5));
    gtk_box_pack_start(GTK_BOX(boxFrame), btn[1], TRUE, FALSE, 0);

    btn[2] = gtk_button_new_with_label(g_locale_to_utf8("Déconnexion",-1, NULL, NULL, NULL));
    g_signal_connect(G_OBJECT(btn[2]), "clicked", G_CALLBACK(changeScreen), GINT_TO_POINTER(1));
    gtk_box_pack_start(GTK_BOX(boxFrame), btn[2], TRUE, FALSE, 0);


    gtk_widget_show_all(window);
}

void addParcelScreen(){
    addLabel("<span size=\"20000\">POIDS</span>");
    addEntry(0,infoParcel[1][actualIndex]);

    radioBtn[0] = gtk_radio_button_new_with_label (NULL,g_locale_to_utf8("Livraison Standard (5j ouvrés)",-1, NULL, NULL, NULL));

    radioBtn[1] = gtk_radio_button_new_with_label_from_widget (GTK_RADIO_BUTTON (radioBtn[0]),g_locale_to_utf8("Livraison Express (2j ouvrés)",-1, NULL, NULL, NULL));

    gtk_box_pack_start (GTK_BOX (boxFrame), radioBtn[0], TRUE, FALSE, 0);
    gtk_box_pack_start (GTK_BOX (boxFrame), radioBtn[1], TRUE, FALSE, 0);

    label = gtk_label_new(NULL);
    gtk_container_add(GTK_CONTAINER(boxFrame),label);

    btn[0] = gtk_button_new_with_label("Valider");
    g_signal_connect(G_OBJECT(btn[0]), "clicked", G_CALLBACK(verifyParcel), NULL);
    gtk_box_pack_start(GTK_BOX(boxFrame), btn[0], TRUE, FALSE, 0);

    btn[1] = gtk_button_new_with_label("Retour");
    g_signal_connect(G_OBJECT(btn[1]), "clicked", G_CALLBACK(abortParcel), NULL);
    gtk_box_pack_start(GTK_BOX(boxFrame), btn[1], TRUE, FALSE, 0);

    gtk_widget_show_all(window);
}

void addParcelClientScreen(){

    addLabel("<span size=\"15000\">Nom</span>");
    addEntry(0,infoParcel[3][actualIndex]);
    addLabel("<span size=\"15000\">Prénom</span>");
    addEntry(1,infoParcel[4][actualIndex]);
    addLabel("<span size=\"15000\">Adresse</span>");
    addEntry(2,infoParcel[5][actualIndex]);
    addLabel("<span size=\"15000\">Ville</span>");
    addEntry(3,infoParcel[6][actualIndex]);
    addLabel("<span size=\"15000\">Code Postal</span>");
    addEntry(4,infoParcel[7][actualIndex]);
    addLabel("<span size=\"15000\">Informations Supplémentaires*</span>");
    addEntry(5,infoParcel[8][actualIndex]);
    addLabel("<span size=\"15000\">Numéro de téléphone*</span>");
    addEntry(6,infoParcel[9][actualIndex]);

    label = gtk_label_new(NULL);
    gtk_container_add(GTK_CONTAINER(boxFrame),label);

    btn[0] = gtk_button_new_with_label("Valider");
    g_signal_connect(G_OBJECT(btn[0]), "clicked", G_CALLBACK(verifyParcelClient), NULL);
    gtk_box_pack_start(GTK_BOX(boxFrame), btn[0], TRUE, FALSE, 0);

    btn[1] = gtk_button_new_with_label("Retour");
    g_signal_connect(G_OBJECT(btn[1]), "clicked", G_CALLBACK(changeScreen), GINT_TO_POINTER(3));
    gtk_box_pack_start(GTK_BOX(boxFrame), btn[1], TRUE, FALSE, 0);

    gtk_widget_show_all(window);
}

void allParcelScreen(){
    GtkWidget *table;
    gchar *text;

    table = gtk_table_new(4, 4, TRUE);

    for(int i = 0; i<25;i++){
        if(strcmp(infoParcel[3][i],"")!=0){
            label = gtk_label_new(NULL);
            text = g_locale_to_utf8(infoParcel[3][i],-1, NULL, NULL, NULL);
            gtk_label_set_markup(GTK_LABEL(label), text);

            btn[i] = gtk_button_new_with_label("Modifier");
            g_signal_connect(G_OBJECT(btn[i]), "clicked", G_CALLBACK(modifyParcel), GINT_TO_POINTER(i));

            gtk_table_attach_defaults(GTK_TABLE(table), btn[i], 3, 4, i, i+1);

            btn[i] = gtk_button_new_with_label("Supprimer");
            g_signal_connect(G_OBJECT(btn[i]), "clicked", G_CALLBACK(deleteParcel), GINT_TO_POINTER(i));

            gtk_table_attach_defaults(GTK_TABLE(table), btn[i], 2, 3, i, i+1);

            gtk_table_attach_defaults(GTK_TABLE(table), label, 0, 2, i, i+1);
        }
    }

    gtk_container_add(GTK_CONTAINER(boxFrame), table);

    btn[0] = gtk_button_new_with_label("Retour");
    g_signal_connect(G_OBJECT(btn[0]), "clicked", G_CALLBACK(changeScreen), GINT_TO_POINTER(2));
    gtk_box_pack_start(GTK_BOX(boxFrame), btn[0], TRUE, FALSE, 0);

    FILE* file;
    file = fopen("generated/excel/new.csv","r");

    if(file){
        btn[1] = gtk_button_new_with_label("Valider");
        g_signal_connect(G_OBJECT(btn[1]), "clicked", G_CALLBACK(sendExcel), NULL);
        gtk_box_pack_start(GTK_BOX(boxFrame), btn[1], TRUE, FALSE, 0);
    }
    fclose(file);

    gtk_widget_show_all(window);
}

void changeScreen(GtkButton *btn, gpointer screen){

    gtk_window_resize (GTK_WINDOW(window),500,400);
    gtk_container_remove(GTK_CONTAINER(frame),GTK_WIDGET(boxFrame));
    boxFrame = gtk_box_new(TRUE, 20);
    gtk_container_add(GTK_CONTAINER(frame), boxFrame);

    int screenNb = GPOINTER_TO_INT(screen);

    if (screenNb == 1){
        gtk_frame_set_label(GTK_FRAME(frame),"Connexion");
        connexionScreen();
    }else if (screenNb == 2){
        gtk_frame_set_label(GTK_FRAME(frame),"Menu");
        menuScreen();
    }else if (screenNb == 3){
        gtk_frame_set_label(GTK_FRAME(frame),"Rentrez les informations du colis");
        addParcelScreen();
    }else if(screenNb == 4){
        gtk_frame_set_label(GTK_FRAME(frame),"Rentrez les informations du destinataire (* : facultatif)");
        addParcelClientScreen();
    }else if(screenNb == 5){
        gtk_frame_set_label(GTK_FRAME(frame),"Liste des colis en attentes de validation");
        allParcelScreen();
    }
}
