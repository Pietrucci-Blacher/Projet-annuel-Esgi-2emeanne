#include <common.h>

int main(int argc , char **argv){

    gtk_init(&argc,&argv);

    window = gtk_window_new(GTK_WINDOW_TOPLEVEL);

    gtk_window_set_title(GTK_WINDOW(window), "Ultimate Parcel");
    gtk_window_set_default_size(GTK_WINDOW(window), 500,350);
    g_signal_connect(G_OBJECT(window),"destroy",G_CALLBACK(gtk_main_quit),NULL);

    box = gtk_box_new(FALSE, 0);
    gtk_container_add(GTK_CONTAINER(window), box);

    frame = gtk_frame_new(NULL);
    gtk_box_pack_start(GTK_BOX(box), frame, TRUE, 10, 30);

    boxFrame = gtk_box_new(TRUE, 20);
    gtk_container_add(GTK_CONTAINER(frame), boxFrame);

    changeScreen(GTK_BUTTON(btn[0]),(gpointer)1);
    actualIndex = -1;

    gtk_widget_show_all(window);
    gtk_main();

    return 0;
}

