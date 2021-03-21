#include<gtk/gtk.h>
#include<stdio.h>
#include<stdlib.h>
#include<string.h>
#include<curl/curl.h>
#include <time.h>
#include <qrcodegen.h>
#include <libbmp.h>

GtkWidget *window;
GtkWidget *entry[10];
GtkWidget *box;
GtkWidget *label;
GtkWidget *frame;
GtkWidget *boxFrame;
GtkWidget *btn[25];
GtkWidget *radioBtn[2];

char infoParcel[11][25][255];
char idEntreprise[255];
int actualIndex;
int tmpIndex;

void checkExcel();
void sendExcelFTP();
void changeScreen();
void connexion();
void verifyParcel();
void abortParcel();
void addLabel();
void addEntry();
void verifyParcelClient();
void modifyParcel();
void deleteParcel();
void sendExcel();
void generateQrcode();
void dataToExcell();
void generateQrcode();
void deleteQrcode();
