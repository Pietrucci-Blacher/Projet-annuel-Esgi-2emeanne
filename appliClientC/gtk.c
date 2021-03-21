#include <common.h>

void addLabel(char txtContent[100]){
    gchar *text;
    label = gtk_label_new(NULL);
    text = g_locale_to_utf8(txtContent,-1, NULL, NULL, NULL);
    gtk_label_set_markup(GTK_LABEL(label), text);
    gtk_box_pack_start(GTK_BOX(boxFrame), label, TRUE, FALSE, 0);
}

void addEntry(int index, char text[255]){
    entry[index] = gtk_entry_new();
    gtk_entry_set_text(GTK_ENTRY(entry[index]),text);
    gtk_box_pack_start(GTK_BOX(boxFrame), entry[index], TRUE, FALSE, 0);
}


