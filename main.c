#include <dirent.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <winsock.h>
#include <mysql.h>

MYSQL mysql;
char infoParcel[11][255];

void readExcel();

void mysql_connection(){
    mysql_init(&mysql);
    if (!mysql_real_connect(&mysql, "eu-cdbr-west-03.cleardb.net", "bdd1b420797f42", "190eb870", "heroku_a4b01b2a0b88f60", 3306, NULL, 0)){
        // error
        printf("MYSQL CONNECT ERROR");
        mysql_close(&mysql);
    } else {
        printf("MYSQL CONNECT SUCCESS");
        readExcel();
    }
}

void displayArray(){
    printf("\n");
    for (int i = 0; i<11; i++){
        printf(" %s ", infoParcel[i]);
    }
}

void emptyArray(){
    for (int i = 0; i<11; i++){
        memset(infoParcel[i],0,sizeof(infoParcel[i]));
    }

}

void queryParcel(int idClient){
    char idClientStr[50];
    char query[1000];

    sprintf(idClientStr, "%d", idClient);

    strcpy(query,"INSERT INTO COLIS(entreprise,poids,modeLivraison,refQrcode,status,client) VALUES (");
    for(int i = 0; i<3; i++){
        strcat(query,"\"");
        strcat(query,infoParcel[i]);
        strcat(query,"\"");
        strcat(query,",");
    }

    strcat(query,"\"");
    strcat(query,infoParcel[10]);
    strcat(query,"\"");
    strcat(query,",");
    strcat(query,"\"En attente du partenaire\"");
    strcat(query,",");
    strcat(query,idClientStr);
    strcat(query,");");

    printf("\n%s",query);
    if (mysql_query(&mysql, query)) {
        printf("\nINSERT ERROR");
    }else{
        printf("\nINSERT SUCCESS");
    }
}


void queryClient(){
    int userId;
    char query[1000];
    strcpy(query,"INSERT INTO CLIENT(nom,prenom,adresse,ville,codePostal,info,numPhone,status) VALUES (");
    for(int i = 3; i<11;i++){
        if(i == 10){
            strcat(query,"\"client\"");
            strcat(query,");");
        }else{
            strcat(query,"\"");
            strcat(query,infoParcel[i]);
            strcat(query,"\"");
            strcat(query,",");
        }
    }

    printf("\n%s",query);

    if (mysql_query(&mysql, query)) {
        printf("\nINSERT ERROR");
    }else{
        printf("\nINSERT SUCCESS");
        userId = mysql_insert_id(&mysql);
        queryParcel(userId);
    }
}


void readExcel(){
    DIR *d;
    struct dirent *dir;
    char path[255];
    FILE* file;

    strcpy(path,"upload/");
    char character;
    int col = 0;
    int i =0;

    d = opendir("upload");
    if(d){

        while ((dir = readdir(d)) != NULL)
        {
            if(strcmp(dir->d_name,".") != 0 && strcmp(dir->d_name,"..") != 0){
                //printf("-%s-",dir->d_name);
                strcat(path,dir->d_name);
                file = fopen(path,"r");
                if(file){
                    printf("file");
                    while(feof(file)==0){
                        character = fgetc(file);
                        if(character=='\n'){
                            //displayArray();
                            queryClient();
                            emptyArray();
                            i=0;
                            col=0;
                        }else if (character == ';'){
                            col +=1;
                            i=0;
                        }else{
                            infoParcel[col][i] = character;
                            //printf("%s\n",infoParcel[col]);
                            i+=1;
                        }
                    }
                    fclose(file);
                }
            }
        }
        closedir(d);
    }
    mysql_close(&mysql);
}

int main(int argc,char **argv)
{
    mysql_connection();
    return 0;
}
